<?php
declare(strict_types=1);
/**
 * The model file of gitfox module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@easycorp.ltd>
 * @package     gitfox
 * @link        https://www.zentao.net
 */
class gitfoxModel extends model
{
    protected $repos = array();

    /**
     * 获取gitfox根据id。
     * Get a gitfox by id.
     *
     * @param  int $id
     * @access public
     * @return object|false
     */
    public function getByID(int $id): object|false
    {
        return $this->loadModel('pipeline')->getByID($id);
    }

    /**
     * 获取gitfox列表。
     * Get gitfox list.
     *
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList(string $orderBy = 'id_desc', object $pager = null): array
    {
        $gitfoxList = $this->loadModel('pipeline')->getList('gitfox', $orderBy, $pager);

        return $gitfoxList;
    }

    /**
     * 获取gitfox id name 键值对。
     * Get gitfox pairs.
     *
     * @access public
     * @return array
     */
    public function getPairs(): array
    {
        return $this->loadModel('pipeline')->getPairs('gitfox');
    }

    /**
     * 获取gitfox api 基础url 根据gitfox id。
     * Get gitfox api base url by gitfox id.
     *
     * @param  int    $gitfoxID
     * @access public
     * @return string|object
     */
    public function getApiRoot(int $gitfoxID): string|object
    {
        $gitfox = $this->getByID($gitfoxID);
        if(!$gitfox || $gitfox->type != 'gitfox') return '';

        $apiRoot = new stdclass;
        $apiRoot->url    = rtrim($gitfox->url, '/') . '/api/v1%s';
        $apiRoot->header = array('Authorization: Bearer ' . $gitfox->token);

        return $apiRoot;
    }

    /**
     * 通过api创建一个gitfox用户。
     * Create a gitab user by api.
     *
     * @param  int    $gitfoxID
     * @param  int    $projectID
     * @param  object $branch
     * @access public
     * @return object|null|false
     */
    public function apiCreateBranch(int $gitfoxID, int $projectID, object $branch): object|null|false
    {
        if(empty($branch->name) || empty($branch->target)) return false;

        $apiRoot = $this->getApiRoot($gitfoxID);
        $url     = sprintf($apiRoot->url, "/repos/{$projectID}/branches");
        return json_decode(commonModel::http($url, $branch, array(), $apiRoot->header, 'json'));
    }

    /**
     * 通过api获取一个代码库信息。
     * Get single repo by API.
     *
     * @param  int    $gitfoxID
     * @param  int    $projectID
     * @param  bool   $useUser
     * @access public
     * @return object|array|null
     */
    public function apiGetSingleRepo(int $gitfoxID, int $repoID): object|array|null
    {
        if(isset($this->repos[$gitfoxID][$repoID])) return $this->repos[$gitfoxID][$repoID];

        $apiRoot = $this->getApiRoot($gitfoxID);
        $url     = sprintf($apiRoot->url, "/repos/$repoID");
        $this->repos[$gitfoxID][$repoID] = json_decode(commonModel::http($url, null, array(), $apiRoot->header));
        return $this->repos[$gitfoxID][$repoID];
    }

    /**
     * 检查token。
     * Check token access.
     *
     * @param  string $url
     * @param  string $token
     * @access public
     * @return object|array|null|false
     */
    public function checkTokenAccess(string $url = '', string $token = ''): object|array|null|false
    {
        $url      = rtrim($url, '/') . '/api/v1/admin/users';
        $header   = array('Authorization: Bearer ' . $token);
        $response = commonModel::http($url, null, array(), $header);

        $users    = json_decode($response);
        if(empty($users)) return false;
        if(isset($users->message) or isset($users->error)) return null;

        return $users;
    }

    /**
     * 获取gitfox的代码库列表。
     * Get repos of one gitfox.
     *
     * @param  int    $gitfoxID
     * @param  string $simple
     * @param  int    $minID
     * @param  int    $maxID
     * @param  bool   $sudo
     * @access public
     * @return array
     */
    public function apiGetRepos(int $gitfoxID, string $query = ''): array
    {
        $apiRoot = $this->getApiRoot($gitfoxID);
        if(!$apiRoot) return array();

        $url = sprintf($apiRoot->url, "/repos?listAllRepos");

        $allResults = array();
        for($page = 1; true; $page++)
        {
            $results = json_decode(commonModel::http($url . "&query={$query}&page={$page}&limit=100"));
            if(!is_array($results)) break;
            if(!empty($results)) $allResults = array_merge($allResults, $results);
            if(count($results) < 100) break;
        }

        return $allResults;
    }

    /**
     * 更新版本库的代码地址。
     * Update repo code path.
     *
     * @param  int    $gitfoxID
     * @param  int    $projectID
     * @param  int    $repoID
     * @access public
     * @return bool
     */
    public function updateCodePath(int $gitfoxID, int $repoID, int $id): bool
    {
        $project = $this->apiGetSingleRepo($gitfoxID, $repoID);
        if(is_object($project) and !empty($project->git_url))
        {
            $this->dao->update(TABLE_REPO)->set('path')->eq($project->git_url)->where('id')->eq($id)->exec();
            return true;
        }

        return false;
    }

    /**
     * 通过api获取项目 hooks。
     * Get hooks.
     *
     * @param  int    $gitfoxID
     * @param  int    $repoID
     * @access public
     * @link   https://docs.gitfox.com/ee/api/projects.html#list-project-hooks
     * @return object|array|null
     */
    public function apiGetHooks(int $gitfoxID, int $repoID, int $hookID = 0): object|array|null
    {
        $apiRoot  = $this->getApiRoot($gitfoxID);
        $apiPath  = "/repos/{$repoID}/webhooks" . ($hookID ? "/{$hookID}" : '');
        $url      = sprintf($apiRoot->url, $apiPath);

        return json_decode(commonModel::http($url, null, array(), $apiRoot->header));
    }

    /**
     * 通过api创建hook。
     * Create hook by api.
     *
     * @param  int    $gitfoxID
     * @param  int    $repoID
     * @param  object $hook
     * @access public
     * @link   https://docs.gitfox.com/ee/api/projects.html#add-project-hook
     * @return object|array|null|false
     */
    public function apiCreateHook(int $gitfoxID, int $repoID, object $hook): object|array|null|false
    {
        if(!isset($hook->url)) return false;

        $newHook = new stdclass;
        $newHook->insecure = true; /* Disable ssl verification for every hook. */

        foreach($hook as $index => $item) $newHook->$index= $item;

        $apiRoot = $this->getApiRoot($gitfoxID);
        $url     = sprintf($apiRoot->url, "/repos/{$repoID}/webhooks");

        return json_decode(commonModel::http($url, $newHook, array(), $apiRoot->header, 'json'));
    }

    /**
     * 添加一个推送和合并请求事件的webhook到gitfox项目。
     * Add webhook with push and merge request events to GitLab project.
     *
     * @param  object $repo
     * @param  string $token
     * @access public
     * @return bool|array
     */
    public function addPushWebhook(object $repo, string $token = ''): bool|array
    {
        $systemURL = dirname(common::getSysURL() . $_SERVER['REQUEST_URI']);

        $hook = new stdClass;
        $hook->url     = $systemURL . '/api.php/v1/gitfox/webhook?repoID='. $repo->id;
        $hook->display_name = "zentao_{$repo->id}_" . date('Ymd');
        $hook->enabled = true;
        if($token) $hook->secret = $token;

        /* Return an empty array if where is one existing webhook. */
        if($this->isWebhookExists($repo, $hook->url)) return true;

        $result = $this->apiCreateHook($repo->gitService, (int)$repo->project, $hook);

        if(!empty($result->id)) return true;

        if(!empty($result->message)) return array('result' => 'fail', 'message' => $result->message);
        return false;
    }

    /**
     * 检查webhook是否存在。
     * Check if Webhook exists.
     *
     * @param  object $repo
     * @param  string $url
     * @return bool
     */
    public function isWebhookExists(object $repo, string $url = ''): bool
    {
        $hookList = $this->apiGetHooks($repo->gitService, (int)$repo->project);
        foreach($hookList as $hook)
        {
            if(empty($hook->url)) continue;
            if($hook->url == $url) return true;
        }
        return false;
    }
}
