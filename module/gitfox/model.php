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
    public function getApiRoot(int $gitfoxID, bool $sudo = true): string|object
    {
        $gitfox = $this->getByID($gitfoxID);
        if(!$gitfox || $gitfox->type != 'gitfox') return '';

        $sudoParam = '';
        if($sudo == true && !$this->app->user->admin)
        {
            $openID = $this->loadModel('pipeline')->getOpenIdByAccount($gitfoxID, 'gitfox', $this->app->user->account);
            if($openID) $sudoParam = "&sudo={$openID}";
        }

        $apiRoot = new stdclass;
        $apiRoot->url    = rtrim($gitfox->url, '/') . '/api/v1%s' . $sudoParam;
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
        $repo    = json_decode(commonModel::http($url, null, array(), $apiRoot->header));

        $repo->name_with_namespace = $repo->path;
        $this->repos[$gitfoxID][$repoID] = $repo;
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

        $url = sprintf($apiRoot->url, "/repos");

        $allResults = array();
        for($page = 1; true; $page++)
        {
            $results = json_decode(commonModel::http($url . "?query={$query}&page={$page}&limit=100"));
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

    /**
     * 通过api创建gitfox项目。
     * Create a gitfox repo by api.
     *
     * @param  int    $gitfoxID
     * @param  object $repo
     * @access public
     * @return object|array|null|false
     */
    public function apiCreateRepo(int $gitfoxID, object $repo): object|array|null|false
    {
        if(empty($repo->identifier)) return false;

        $apiRoot = $this->getApiRoot($gitfoxID);
        $url     = sprintf($apiRoot->url, "/repos");

        $repo->default_branch = 'main';
        $repo->is_public      = true;
        $repo->readme         = true;
        $repo->git_ignore     = '';

        return json_decode(commonModel::http($url, $repo, array(), $apiRoot->header, 'json'));
    }

    /**
     * 获取gitfox的群组列表。
     * Get groups of one gitfox.
     *
     * @param  int     $gitfoxID
     * @param  string  $orderBy
     * @param  string  $minRole
     * @param  string  $keyword
     * @access public
     * @return array
     */
    public function apiGetGroups(int $gitfoxID, string $orderBy = 'id_desc', string $keyword = ''): array
    {
        $apiRoot = $this->getApiRoot($gitfoxID);
        $url     = sprintf($apiRoot->url, "/spaces");

        if($keyword) $url .= '&query=' . urlencode($keyword);

        $order = 'desc';
        $sort  = 'id';
        if(strpos($orderBy, '_') !== false) list($sort, $order) = explode('_', $orderBy);

        $allResults = array();
        for($page = 1; true; $page++)
        {
            $pageUrl = $url . "?order={$order}&sort={$sort}&page={$page}&limit=100";
            $results = json_decode(commonModel::http($pageUrl, null, array(), $apiRoot->header));
            if(!is_array($results)) break;
            if(!empty($results)) $allResults = array_merge($allResults, $results);
            if(count($results) < 100) break;
        }

        return $allResults;
    }

    /**
     * 通过api删除一个gitfox代码库。
     * Delete a gitfox project by api.
     *
     * @param  int    $gitfoxID
     * @param  int    $repoID
     * @access public
     * @return object|array|null|false
     */
    public function apiDeleteRepo(int $gitfoxID, int $repoID): object|array|null|false
    {
        if(empty($repoID)) return false;

        $apiRoot = $this->getApiRoot($gitfoxID);
        $url     = sprintf($apiRoot->url, "/repos/{$repoID}");
        return json_decode(commonModel::http($url, array(),  array(CURLOPT_CUSTOMREQUEST => 'DELETE'), $apiRoot->header));
    }

    /**
     * 错误处理。
     * Api error handling.
     *
     * @param  object $response
     * @access public
     * @return bool
     */
    public function apiErrorHandling(object $response): bool
    {
        if(!empty($response->error))
        {
            dao::$errors[] = $response->error;
            return false;
        }
        if(!empty($response->message))
        {
            if(is_string($response->message))
            {
                $errorKey = array_search($response->message, $this->lang->gitfox->apiError);
                dao::$errors[] = $errorKey === false ? $response->message : zget($this->lang->gitfox->errorLang, $errorKey);
            }
            else
            {
                foreach($response->message as $field => $fieldErrors)
                {
                    if(empty($fieldErrors)) continue;

                    if(is_string($fieldErrors))
                    {
                        $errorKey = array_search($fieldErrors, $this->lang->gitfox->apiError);
                        if($fieldErrors) dao::$errors[$field][] = $errorKey === false ? $fieldErrors : zget($this->lang->gitfox->errorLang, $errorKey);
                    }
                    else
                    {
                        foreach($fieldErrors as $error)
                        {
                            $errorKey = array_search($error, $this->lang->gitfox->apiError);
                            if($error) dao::$errors[$field][] = $errorKey === false ? $error : zget($this->lang->gitfox->errorLang, $errorKey);
                        }
                    }
                }
            }
        }

        if(!$response) dao::$errors[] = false;
        return false;
    }

    /**
     * 获取最新用户。
     * Get current user.
     *
     * @param  int $gitfoxID
     * @access public
     * @return object|array|null|false
     */
    public function apiGetCurrentUser(int $gitfoxID): object|array|null|false
    {
        $apiRoot = $this->getApiRoot($gitfoxID);
        $url     = sprintf($apiRoot->url, "/user");
        return json_decode(commonModel::http($url, null, array(), $apiRoot->header));
    }

    /**
     * 获取gitfox用户列表。
     * Get gitfox user list.
     *
     * @param  int    $gitfoxID
     * @param  bool   $onlyLinked
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function apiGetUsers(int $gitfoxID, bool $onlyLinked = false, string $orderBy = 'id_desc'): array
    {
        /* GitLab API '/users' can only return 20 users per page in default, so we use a loop to fetch all users. */
        $page     = 1;
        $perPage  = 100;
        $response = array();
        $apiRoot  = $this->getApiRoot($gitfoxID);

        /* Get order data. */
        $orders = explode('_', $orderBy);
        $order  = array_pop($orders);
        $sort   = join('_', $orders);

        while(true)
        {
            /* Also use `per_page=20` to fetch users in API. Fetch active users only. */
            $url      = sprintf($apiRoot->url, "/admin/users") . "?order={$order}&sort={$sort}&page={$page}&limit={$perPage}";
            $httpData = commonModel::http($url, null, array(), $apiRoot->header, 'data', 'GET', 30, true, false);
            if(empty($httpData['body'])) break;

            $result   = json_decode($httpData['body']);
            if(!empty($result) && is_array($result))
            {
                $response = array_merge($response, $result);
                $page += 1;

                $resultPage      = isset($httpData['header']['X-Page']) ? $httpData['header']['X-Page'] : $httpData['header']['x-page'];
                $resultTotalPage = isset($httpData['header']['X-Total-Pages']) ? $httpData['header']['X-Total-Pages'] : $httpData['header']['x-total-pages'];
                if($resultPage == $resultTotalPage) break;
            }
            else
            {
                break;
            }
        }

        if(!$response) return array();

        /* Get linked users. */
        $linkedUsers = array();
        if($onlyLinked) $linkedUsers = $this->loadModel('pipeline')->getUserBindedPairs($gitfoxID, 'gitfox', 'openID,account');

        $users = array();
        foreach($response as $gitfoxUser)
        {
            if($onlyLinked and !isset($linkedUsers[$gitfoxUser->id])) continue;

            $user = new stdclass;
            $user->id             = $gitfoxUser->uid;
            $user->realname       = $gitfoxUser->display_name;
            $user->account        = $gitfoxUser->uid;
            $user->email          = zget($gitfoxUser, 'email', '');
            $user->createdAt      = zget($gitfoxUser, 'created', '');
            $user->lastActivityOn = zget($gitfoxUser, 'updated', '');

            $users[] = $user;
        }

        return $users;
    }

    /**
     * 获取匹配的gitfox用户列表。
     * Get matched gitfox users.
     *
     * @param  int    $gitfoxID
     * @param  array  $gitfoxUsers
     * @param  array  $zentaoUsers
     * @access public
     * @return array
     */
    public function getMatchedUsers(int $gitfoxID, array $gitfoxUsers, array $zentaoUsers): array
    {
        $matches = new stdclass;
        foreach($gitfoxUsers as $gitfoxUser)
        {
            foreach($zentaoUsers as $zentaoUser)
            {
                if($gitfoxUser->email == $zentaoUser->email)       $matches->emails[$gitfoxUser->email][]     = $zentaoUser->account;
                if($gitfoxUser->account == $zentaoUser->account)   $matches->accounts[$gitfoxUser->account][] = $zentaoUser->account;
                if($gitfoxUser->realname == $zentaoUser->realname) $matches->names[$gitfoxUser->realname][]   = $zentaoUser->account;
            }
        }

        $bindedUsers = $this->loadModel('pipeline')->getUserBindedPairs($gitfoxID, 'gitfox', 'openID,account');

        $matchedUsers = array();
        foreach($gitfoxUsers as $gitfoxUser)
        {
            if(isset($bindedUsers[$gitfoxUser->id]))
            {
                $gitfoxUser->zentaoAccount     = $bindedUsers[$gitfoxUser->id];
                $matchedUsers[$gitfoxUser->id] = $gitfoxUser;
                continue;
            }

            $matchedZentaoUsers = array();
            if(isset($matches->emails[$gitfoxUser->email]))     $matchedZentaoUsers = array_merge($matchedZentaoUsers, $matches->emails[$gitfoxUser->email]);
            if(isset($matches->names[$gitfoxUser->realname]))   $matchedZentaoUsers = array_merge($matchedZentaoUsers, $matches->names[$gitfoxUser->realname]);
            if(isset($matches->accounts[$gitfoxUser->account])) $matchedZentaoUsers = array_merge($matchedZentaoUsers, $matches->accounts[$gitfoxUser->account]);

            $matchedZentaoUsers = array_unique($matchedZentaoUsers);
            if(count($matchedZentaoUsers) == 1)
            {
                $gitfoxUser->zentaoAccount     = current($matchedZentaoUsers);
                $matchedUsers[$gitfoxUser->id] = $gitfoxUser;
            }
        }

        return $matchedUsers;
    }
}
