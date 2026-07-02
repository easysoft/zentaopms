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
     * 检查服务是否健康。
     * Check service health.
     *
     * @access public
     * @return bool
     */
    public function checkHealth(): bool
    {
        $url = $this->config->devops->gitfoxURL;
        if($this->config->devops->gitfoxPort) $url .= ':' . $this->config->devops->gitfoxPort;

        $url = rtrim($url, '/') . '/public/health';

        $result = json_decode(common::http($url));
        return !empty($result) && !empty($result->status) && $result->status == 'healthy';
    }

    /**
     * 获取gitfox 服务器信息。
     * Get gitfox server info.
     *
     * @access public
     * @return object|bool
     */
    public function getServer(): object|bool
    {
        $server = $this->dao->select('*')->from(TABLE_ENTRY)->where('code')->eq('gitfox')->fetch();
        if(empty($server)) return false;

        $server->url   = $this->config->devops->gitfoxURL;
        if($this->config->devops->gitfoxPort) $server->url .= ':' . $this->config->devops->gitfoxPort;
        $server->token = md5('zentao' . $this->app->user->account . $server->key);
        return $server;
    }

    /**
     * 获取gitfox api 基础url 根据gitfox id。
     * Get gitfox api base url by gitfox id.
     *
     * @param  int    $gitfoxID
     * @access public
     * @return string|object
     */
    public function getApiRoot(): string|object
    {
        $gitfox = $this->getServer();
        if(!$gitfox) return '';

        $apiRoot = new stdclass;
        $apiRoot->url    = rtrim($gitfox->url, '/') . '/api/v2%s';
        $apiLanguage     = common::checkNotCN() ? 'en-US' : 'zh-CN';
        $apiRoot->header = array('Authorization: ' . $gitfox->token, 'App: zentao', 'Operator: ' . $this->app->user->account, 'Accept-Language: ' . $apiLanguage);

        return $apiRoot;
    }

    /**
     * 发送http请求。
     * Send http request.
     *
     * @param  string $url
     * @param  string $method
     * @param  array  $data
     * @access public
     * @return object|array|bool
     */
    public function request($url, $method = 'GET', $data = array()): object|array|bool
    {
        $originURL = $url;
        if($method == 'GET')
        {
            $url .= '?' . http_build_query($data);
            $data = array();
        }
        $options = array();
        if(in_array($method, array('DELETE', 'PUT', 'PATCH'))) $options = array(CURLOPT_CUSTOMREQUEST => $method);

        $apiRoot = $this->getApiRoot();
        if(!$apiRoot) return false;

        $url    = sprintf($apiRoot->url, $url);
        $result = json_decode(common::http($url, $data, $options, $apiRoot->header, 'json', $method));
        $result = $this->getResponse($result);
        if(isset($result->pager) && $result->pager->total > 0 && empty($result->data))
        {
            if(is_array($data))
            {
                $data['page'] = 1;
            }
            else
            {
                $data->page = 1;
            }
            $result = $this->request($originURL, $method, $data);
        }
        return $result;
    }

    /**
     * 获取api返回的数据。
     * Get api response data.
     *
     * @param  object $response
     * @param  bool   $parseError
     * @access public
     * @return object|array|bool
     */
    public function getResponse(?object $response, bool $parseError = false): object|array|bool
    {
        if(is_null($response)) return false;
        if(dao::isError()) dao::getError();
        if(empty($response) || empty($response->code) || $response->code != 'success')
        {
            if(!empty($response->message))
            {
                $message  = $response->message;
                $message  = explode('::', $message);
                $errorKey = count($message) > 1 ? current($message) : '';
                if($errorKey && $parseError)
                {
                    dao::$errors[$errorKey][] = ltrim($response->message, $errorKey . '::');
                }
                else
                {
                    dao::$errors['apiMessage'] = $response->message;
                }
            }
            else
            {
                if(!empty($response->data))
                {
                    dao::$errors['apiData'] = $response->data;
                }
                else
                {
                    dao::$errors['apiMessage'] = $this->lang->error->httpServerError;
                }
            }
        }
        if(dao::isError()) return false;

        if(isset($response->data))
        {
            $result = new stdclass();
            if(isset($response->listArgs))
            {
                $result->data  = $response->data;
                $result->pager = $response->listArgs;
            }
            else
            {
                $result = $response->data;
            }

            return $result;
        }

        return true;
    }

    /**
     * 获取分页信息。
     * Get page info.
     *
     * @param  object|null $pager
     * @access public
     * @return array
     */
    public function getPage(object|null $pager): array
    {
        if(is_null($pager)) return array();

        return array(
            'page'     => zget($pager, 'pageID', 1),
            'pageSize' => zget($pager, 'recPerPage', 20),
        );
    }

    /**
     * 通过api创建一个gitfox用户。
     * Create a gitfox user by api.
     *
     * @param  int    $gitfoxID
     * @param  int    $projectID
     * @param  object $branch
     * @access public
     * @return object|null|false
     */
    public function apiCreateBranch( int $projectID, object $branch): object|null|false
    {
        if(empty($branch->name) || empty($branch->source)) return new stdclass();

        $apiRoot = $this->getApiRoot();
        $url     = sprintf($apiRoot->url, "/repos/{$projectID}/branches");

        $httpData = common::http($url, $branch, array(), $apiRoot->header, 'json', 'POST', 30, true, false);
        if(empty($httpData)) return false;

        $result = json_decode($httpData['body']);
        if($httpData[1] == 422 && !empty($result->violations))
        {
            foreach($result->violations as $violation)
            {
                if(!empty($violation->bypassable))
                {
                    $branch->bypass_rules = true;
                    return json_decode(common::http($url, $branch, array(), $apiRoot->header, 'json'));
                }
            }
        }
        return $result;
    }

    /**
     * Creates a tag in the GitFox repository using the provided tag information.
     *
     * @param int    $gitfoxID  The ID of the GitFox user.
     * @param int    $projectID The ID of the GitFox project.
     * @param object $tag       The tag information including the name and target.
     * @return object|null|false The created tag information as an object if successful, null if the tag name or target is empty, or false if there is an error.
     */
    public function apiCreateTag(int $projectID, object $tag): object|null|false
    {
        if(empty($tag->name) || empty($tag->source)) return false;
        $apiRoot = $this->getApiRoot();
        $url     = sprintf($apiRoot->url, "/repos/{$projectID}/tags");

        return json_decode(common::http($url, $tag, array(), $apiRoot->header, 'json'));
    }

    /**
     * 通过api获取一个代码库信息。
     * Get single repo by API.
     *
     * @param  int        $gitfoxID
     * @param  int|string $projectID
     * @param  bool       $useUser
     * @access public
     * @return object|array|null
     */
    public function apiGetSingleRepo(int|string $repoID): object|array|null
    {
        if(isset($this->repos[$repoID])) return $this->repos[$repoID];

        $apiRoot = $this->getApiRoot();
        $url     = sprintf($apiRoot->url, "/repos/$repoID");

        $response = json_decode(common::http($url, null, array(), $apiRoot->header));
        $repo     = $this->getResponse($response);
        if(!$repo) return array();

        $repoPath = $this->loadModel('repo')->parseRepoPath($repo->gitURL);

        $repo->name_with_namespace = $repo->path;
        $repo->path_with_namespace = $repo->path;
        $repo->http_url_to_repo    = $repoPath;
        $repo->web_url             = $repoPath;

        $this->repos[$repoID] = $repo;
        return $this->repos[$repoID];
    }

    /**
     * 获取镜像仓库同步进度。
     * Get mirror sync progress for a mirror repo.
     *
     * @param  int    $repoID
     * @access public
     * @return object|null
     */
    public function apiGetMirrorSyncProgress(int $repoID): ?object
    {
        $apiRoot = $this->getApiRoot();
        if(!is_object($apiRoot)) return null;

        $url      = sprintf($apiRoot->url, "/repos/mirror-sync-progress?repoID=$repoID");
        $response = json_decode(common::http($url, null, array(), $apiRoot->header));
        $progress = $this->getResponse($response);
        if(!$progress || !is_object($progress)) return null;
        return $progress;
    }

    /**
     * 触发镜像仓库同步。
     * Trigger mirror sync for a mirror repo.
     *
     * @param  int    $repoID
     * @access public
     * @return object|array|bool
     */
    public function apiMirrorSync(int $repoID)
    {
        $apiRoot = $this->getApiRoot();
        if(!is_object($apiRoot)) return false;

        $url = sprintf($apiRoot->url, "/repos/mirror-sync?repoID=$repoID");
        return json_decode(common::http($url, array(), array(CURLOPT_CUSTOMREQUEST => 'POST'), $apiRoot->header, 'json'));
    }

    public function __call($funcName, $arguments)
    {
        $funcName = strtolower($funcName);
        if(strpos($funcName, 'project')) $funcName = str_replace('project', 'repo', $funcName);

        if(method_exists($this, $funcName)) return call_user_func_array(array($this, $funcName), $arguments);
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
        $response = common::http($url, null, array(), $header);

        $users    = json_decode($response);
        if(empty($users)) return false;
        if(isset($users->message) or isset($users->error)) return null;

        return $users;
    }

    /**
     * 获取gitfox的代码库列表。
     * Get repos of gitfox.
     *
     * @param  string $query
     * @access public
     * @return array
     */
    public function apiGetRepos(string $query = ''): array
    {
        $apiRoot = $this->getApiRoot();
        if(!$apiRoot) return array();

        $allResults = array();
        $apiUrl     = sprintf($apiRoot->url, "/repos/list");

        for($page = 1; true; $page++)
        {
            $param                     = array();
            $param['page']             = $page;
            $param['pageSize']         = 100;
            if($query) $param['query'] = $query;

            $response = json_decode(common::http($apiUrl, $param, array(), $apiRoot->header, 'json', 'POST'));
            $result   = $this->getResponse($response);
            $tagList  = isset($result->data) ? $result->data : array();
            $isLast   = isset($result->listArgs) ? $result->listArgs->isLast : true;

            if(empty($tagList) || !is_array($tagList)) break;
            $allResults = array_merge($allResults, $tagList);
            if(!empty($isLast)) break;
        }

        return $allResults;
    }

    /**
     * 更新版本库的代码地址。
     * Update repo code path.
     *
     * @param  int    $projectID
     * @param  int    $repoID
     * @access public
     * @return bool
     */
    public function updateCodePath(int $repoID, int $id): bool
    {
        $project = $this->apiGetSingleRepo($repoID);
        if(is_object($project) and !empty($project->gitURL))
        {
            $repoPath = $project->gitURL;
            $server   = $this->getServer();
            if($server)
            {
                $serverUrl = trim($server->url, '/');
                $repoPath  = str_replace("{$serverUrl}/git", $serverUrl, $repoPath);
                if(substr($repoPath, -4) == '.git') $repoPath = substr($repoPath, 0, -4);
            }
            $this->dao->update(TABLE_REPO)->set('path')->eq($repoPath)->where('id')->eq($id)->exec();
            return true;
        }

        return false;
    }

    /**
     * 通过 api 获取 webhook。
     * Get webhooks by api.
     *
     * @param  int   $repoID
     * @param  int   $hookID
     * @param  array $params
     * @access public
     * @return object|array|false
     */
    public function apiGetHooks(int $repoID, int $hookID = 0, array $params = array()): object|array|false
    {
        $apiPath = "/repos/{$repoID}/webhooks" . ($hookID ? "/{$hookID}" : '');
        $result  = $this->request($apiPath, 'GET', $params);
        if(!$hookID && is_object($result) && isset($result->data)) return $result->data;
        return $result;
    }

    /**
     * 通过api获取合并请求。
     * Get Pull Requests/Merge Requests by API.
     *
     * @param  mixed $gitfoxID
     * @param  mixed $repoID
     * @return object|array|null
     */
    public function apiGetMergeRequests(int $gitfoxID, int $repoID): object|array|null
    {
        $apiRoot  = $this->getApiRoot($gitfoxID, false);
        $apiPath  = "/repos/{$repoID}/pullreq";
        $url      = sprintf($apiRoot->url, $apiPath);

        $mrList = json_decode(common::http($url, null, array(), $apiRoot->header));
        foreach($mrList as $mr)
        {
            $mr->merge_status      = $mr->merge_check_status == 'mergeable' ? 'can_be_merged' : 'cannot_be_merged';
            $mr->iid               = $mr->number;
            $mr->source_project_id = $mr->source_repo_id;
            $mr->target_project_id = $mr->target_repo_id;
            $mr->work_in_progress  = $mr->is_draft;
            $mr->draft             = $mr->is_draft;

            if($mr->state == 'open') $mr->state = 'opened';
        }

        return $mrList;
    }

    /**
     * 通过 api 创建 webhook。
     * Create webhook by api.
     *
     * @param  int    $repoID
     * @param  object $hook
     * @access public
     * @return object|array|false
     */
    public function apiCreateHook(int $repoID, object $hook): object|array|false
    {
        if(!isset($hook->url)) return false;
        if(!isset($hook->insecure)) $hook->insecure = true;

        return $this->request("/repos/{$repoID}/webhooks", 'POST', $hook);
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
        $hook->url         = $systemURL . '/api.php/v1/gitfox/webhook?repoID='. $repo->id;
        $hook->displayName = "zentao_{$repo->id}_" . date('Ymd');
        $hook->enabled     = true;
        if($token) $hook->secret = $token;

        /* Return an empty array if where is one existing webhook. */
        if($this->isWebhookExists($repo, $hook->url)) return true;

        $result = $this->apiCreateHook((int)$repo->id, $hook);

        if(!empty($result->id)) return true;
        return !dao::isError();
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
        $hookList = $this->apiGetHooks((int)$repo->id);
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
     * @param  object $repo
     * @param  bool   $useUser
     * @access public
     * @return object|false
     */
    public function apiCreateRepo(object $repo): object|false
    {
        if(empty($repo->name) || empty($repo->space)) return false;

        $apiRoot = $this->getApiRoot();
        $url     = sprintf($apiRoot->url, "/repos");

        $data = new stdClass();
        $data->name          = $repo->name;
        $data->defaultBranch = 'main';
        $data->readme        = true;
        $data->desc          = $repo->desc;
        $data->acl           = $repo->acl;
        $data->spaceID       = $repo->space;
        $data->product       = $repo->product;

        $result = json_decode(common::http($url, $data, array(), $apiRoot->header, 'json'));

        return $this->getResponse($result);
    }

    /**
     * 获取gitfox的群组列表。
     * Get groups of one gitfox.
     *
     * @param  int     $gitfoxID
     * @param  string  $orderBy
     * @param  bool    $minRole
     * @param  string  $keyword
     * @access public
     * @return array
     */
    public function apiGetGroups(int $gitfoxID, string $orderBy = 'id_desc', bool $minRole = false, string $keyword = ''): array
    {
        $apiRoot = $this->getApiRoot($gitfoxID, $minRole);
        $url     = sprintf($apiRoot->url, "/spaces");

        if($keyword) $url .= '&query=' . urlencode($keyword);

        $order = 'desc';
        $sort  = 'id';
        if(strpos($orderBy, '_') !== false) list($sort, $order) = explode('_', $orderBy);

        $allResults = array();
        for($page = 1; true; $page++)
        {
            $pageUrl = $url . "?order={$order}&sort={$sort}&page={$page}&limit=100";
            $results = json_decode(common::http($pageUrl, null, array(), $apiRoot->header));
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

        $apiRoot = $this->getApiRoot($gitfoxID, false);
        $url     = sprintf($apiRoot->url, "/repos/{$repoID}");
        return json_decode(common::http($url, array(),  array(CURLOPT_CUSTOMREQUEST => 'DELETE'), $apiRoot->header, 'json', 'DELETE'));
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

        if(!$response || empty($response->message)) return false;
        if(is_string($response->message))
        {
            $errorKey = array_search($response->message, $this->lang->gitfox->apiError);
            dao::$errors[] = $errorKey === false ? $response->message : zget($this->lang->gitfox->errorLang, $errorKey);
            return false;
        }

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
        $apiRoot = $this->getApiRoot($gitfoxID, false);
        $url     = sprintf($apiRoot->url, "/user");
        return json_decode(common::http($url, null, array(), $apiRoot->header));
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
        $apiRoot  = $this->getApiRoot($gitfoxID, false);

        /* Get order data. */
        $orders = explode('_', $orderBy);
        $order  = array_pop($orders);
        $sort   = implode('_', $orders);

        while(true)
        {
            /* Also use `per_page=20` to fetch users in API. Fetch active users only. */
            $url      = sprintf($apiRoot->url, "/admin/users") . "?order={$order}&sort={$sort}&page={$page}&limit={$perPage}";
            $httpData = common::http($url, null, array(), $apiRoot->header, 'data', 'GET', 30, true, false);
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
            $user->id             = $gitfoxUser->id;
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

    /**
     * 通过API删除GitFox分支。
     * Api delete branch.
     *
     * @param  int    $repoID
     * @param  string $branch
     * @access public
     * @return bool
     */
    public function apiDeleteBranch(int $repoID, string $branch): bool
    {
        $apiRoot = $this->getApiRoot();
        if(!$apiRoot) return false;

        $api    = "/repos/{$repoID}/branches";
        $header = array();
        if(is_object($apiRoot))
        {
            $header  = $apiRoot->header;
            $apiRoot = $apiRoot->url;
        }

        $resp = json_decode(common::http(sprintf($apiRoot, $api), array('name' => $branch), array(CURLOPT_CUSTOMREQUEST => 'DELETE'), $header, 'json', 'DELETE'));
        return $this->getResponse($resp);
    }

    /**
     * 通过api获取一个流水线。
     * Get single pipeline by api.
     *
     * @param  int    $gitfoxID
     * @param  int    $projectID
     * @param  string $pipelineID
     * @param  int    $executionID
     * @access public
     * @return object|array|null
     */
    public function apiGetSinglePipeline(int $gitfoxID, int $projectID, string $pipelineID, int $executionID): object|array|null
    {
        $apiRoot = $this->getApiRoot($gitfoxID, false);
        $url = sprintf($apiRoot->url, "/repos/{$projectID}/pipelines/{$pipelineID}/executions/{$executionID}");
        return json_decode(common::http($url, null, array(), $apiRoot->header));
    }

    /**
     * 通过API获取版本库的流水线列表。
     * Get the pipelines list of the repo by API.
     *
     * @param  int|string $gitfoxID
     * @param  int|string $projectID
     * @param  string     $branch
     * @return object|array|null
     */
    public function apiGetPipeline(int|string $gitfoxID, int|string $projectID, $branch = ''): object|array|null
    {
        $apiRoot = $this->getApiRoot((int)$gitfoxID, false);

        $url = sprintf($apiRoot->url, "/repos/{$projectID}/pipelines");
        $pipelines = json_decode(common::http($url, null, array(), $apiRoot->header));

        $filteredPipelines = array();
        foreach($pipelines as $pipeline)
        {
            $pipeline->ref = $pipeline->default_branch;

            if($branch == $pipeline->ref) $filteredPipelines[] = $pipeline;
        }

        return empty($branch) ? $pipelines : $filteredPipelines;
    }

    /**
     * 通过api获取一个流水线日志。
     * Get single pipeline logs by api.
     *
     * @param  int    $gitfoxID
     * @param  int    $projectID
     * @param  object $pipeline
     * @access public
     * @return string
     */
    public function apiGetPipelineLogs(int $gitfoxID, int $projectID, object $pipeline): string
    {
        if(empty($pipeline->name) || empty($pipeline->number)) return '';

        $apiRoot = $this->getApiRoot($gitfoxID, false);
        $log     = '';
        $jobInfo = $this->apiGetSinglePipeline($gitfoxID, $projectID, $pipeline->name, $pipeline->number);
        if(!$jobInfo) return $log;

        $url    = sprintf($apiRoot->url, "/repos/{$projectID}/pipelines/{$pipeline->name}/executions/{$pipeline->number}/logs");
        $jobUrl = isset($pipeline->params->DRONE_BUILD_LINK) ? $pipeline->params->DRONE_BUILD_LINK : '';
        foreach($jobInfo->stages as $stage)
        {
            $duration = empty($stage->stopped) || empty($stage->started) ? '-' : ($stage->stopped - $stage->started) / 1000;

            $log .= "<font style='font-weight:bold'>&gt;&gt;&gt; Job: {$stage->name}, Status: {$stage->status}, Duration: $duration Sec\r\n </font>";
            $log .= "Job URL: <a href=\"{$jobUrl}\" target='_blank'>{$jobUrl}</a> \r\n";
            if(empty($stage->steps)) continue;

            foreach($stage->steps as $step)
            {
                if(!isset($step->started)) $step->started = 0;
                if(!isset($step->stopped)) $step->stopped = 0;
                $duration = ($step->stopped - $step->started) / 1000;
                if(!$step->stopped || !$step->started) $duration = '-';

                $log .= "<font style='font-weight:bold'>&gt;&gt;&gt; Step: {$step->name}, Status: {$step->status}, Duration: $duration Sec\r\n </font>";
                $logs = json_decode(common::http("{$url}/{$stage->number}/{$step->number}", null, array(), $apiRoot->header));
                if(!is_array($logs)) continue;

                foreach($logs as $row) $log .= $row->out;
            }
        }
        if(!empty($log)) $log = !empty($logs) ? preg_replace('/\x1B\[[^m]*m/', '', $log) : '';

        return $log;
    }

    /**
     * 获取分支保护信息。
     * Get branch protection info.
     *
     * @access public
     * @return array
     */
    public function apiGetBranchPrivs(): array
    {
        return array();
    }

    /**
     * 根据路径获取制品列表。
     * Get artifacts list by path.
     *
     * @param  int    $gitfoxID
     * @param  string $project
     * @param  string $path
     * @param  string $format
     * @param  string $level
     * @access public
     * @return array
     */
    public function getFilesByPath(int $gitfoxID, string $project, string $path = '', string $format = '', string $level = 'version'): array
    {
        $params = array('level' => $level);
        if($path)   $params['path']   = $path;
        if($format) $params['format'] = $format;

        $apiRoot = $this->getApiRoot($gitfoxID, false);
        $url     = sprintf($apiRoot->url, "/artifacts/{$project}/nodes") . '?' . http_build_query($params);
        $result  = json_decode(common::http($url, null, array(), $apiRoot->header, 'json'));
        if(!$result || isset($result->message)) return array();

        $folders = array();
        foreach($result as $node)
        {
            $folder = new stdclass();
            $folder->id       = $node->path == '/' ? "/{$node->name}" : $node->path;
            $folder->text     = $node->name;
            $folder->format   = $node->format;
            $folder->leaf     = $node->leaf;
            $folder->path     = $node->path;
            $folder->type     = 'folder';
            $folder->metadata = zget($node, 'metadata', null);
            $folders[] = $folder;
        }
        return $folders;
    }

    /**
     * 通过api获取制品包列表。
     * Get artifacts list by api.
     *
     * @param  int    $gitfoxID
     * @param  string $project
     * @param  string $format
     * @param  string $package
     * @param  string $version
     * @param  string $group
     * @access public
     * @return array
     */
    public function apiGetArtifacts(int $gitfoxID, string $project, string $format, string $package, string $version, string $group = ''): array
    {
        $params = array('package' => $package, 'version' => $version);
        if($group) $params['group'] = $group;

        $apiRoot = $this->getApiRoot($gitfoxID, false);
        $url     = sprintf($apiRoot->url, "/artifacts/{$project}/{$format}/assets") . '?' . http_build_query($params);
        $result  = json_decode(common::http($url, null, array(), $apiRoot->header, 'json'));
        if(!$result) return array();

        if(isset($result->images)) return $result->images;
        return $result;
    }

    /**
     * 通过id列表获取制品详情列表。
     * Get artifacts list by id list.
     *
     * @param  int $gitfoxID
     * @param  array $idList
     * @access public
     * @return array
     */
    public function apiGetArtifactByIdList(int $gitfoxID, array $idList): array
    {
        $data = array("entity_ids" => $idList);

        $apiRoot = $this->getApiRoot($gitfoxID, false);
        $url     = sprintf($apiRoot->url, "/artifacts/nodeInfo");
        $result  = json_decode(common::http($url, $data, array(), $apiRoot->header, 'json'));
        if(!$result) return array();

        return $result;
    }

    /**
     * 获取仓库设置。
     * Get repo settings.
     *
     * @param  object $repo
     * @param  string $type
     * @access public
     * @return array
     */
    public function apiGetRepoSettings(object $repo, string $type = 'advanced'): array
    {
        if(empty($repo->serviceProject) || strtolower($repo->SCM) != 'gitfox') return array();
        $apiRoot = $this->getApiRoot((int)$repo->gitService, false);

        $url    = sprintf($apiRoot->url, "/repos/{$repo->serviceProject}/settings/{$type}");
        $result = json_decode(common::http($url, null, array(), $apiRoot->header, 'json'), true);
        if(empty($result)) return array();

        return $result;
    }

    /**
     * 设置仓库设置。
     * Set repo settings.
     *
     * @param  object $repo
     * @param  array  $data
     * @param  string $type
     * @access public
     * @return bool
     */
    public function apiSetRepoSettings(object $repo, array $data, string $type = 'advanced'): bool
    {
        if(empty($repo->serviceProject) || strtolower($repo->SCM) != 'gitfox') return false;
        $apiRoot = $this->getApiRoot((int)$repo->gitService, false);

        $url    = sprintf($apiRoot->url, "/repos/{$repo->serviceProject}/settings/{$type}");
        $result = json_decode(common::http($url, $data, array(CURLOPT_CUSTOMREQUEST => 'PATCH'), $apiRoot->header, 'json', 'PATCH'));

        if(isset($result->message)) dao::$errors[] = $result->message;
        if(empty($result) || isset($result->message)) return false;

        return true;
    }

    /**
     * 设置默认分支。
     * Set default branch.
     *
     * @param  object $repo
     * @param  string $branch
     * @access public
     * @return bool
     */
    public function setDefaultBranch(object $repo, string $branch): bool
    {
        if(empty($repo->serviceProject) || strtolower($repo->SCM) != 'gitfox') return false;
        $apiRoot = $this->getApiRoot();
        $data = array('name' => $branch);

        $url    = sprintf($apiRoot->url, "/repos/{$repo->gitfoxID}/default-branch");
        $result = json_decode(commonModel::http($url, $data, array(), $apiRoot->header, 'json'));
        return $this->getResponse($result);
    }

    /**
     * 通过API设置项目成员。
     * Set project members by API.
     *
     * @param  int $serviceID
     * @param  int $projectID
     * @param  array $members
     * @access public
     * @return bool
     */
    public function apiSetProjectMembers(int $serviceID, int $projectID, array $members): bool
    {
        $apiRoot = $this->getApiRoot($serviceID, false);
        $url     = sprintf($apiRoot->url, "/spaces/{$projectID}/members");

        $projectMembers = $this->apiGetProjectMembers($serviceID, $projectID);
        foreach($members as $account)
        {
            if(!$account) continue;
            if(in_array($account, array_keys($projectMembers))) continue;

            $result = json_decode(commonModel::http($url, array('user_uid' => $account, 'role' => 'space_owner'), array(), $apiRoot->header, 'json'));
            if(empty($result) || isset($result->message) || !isset($result->principal)) dao::$errors['committer'][] = $account;
        }

        return true;
    }

    /**
     * 通过API删除项目成员。
     * Delete project members by API.
     *
     * @param  int $serviceID
     * @param  int $projectID
     * @param  array $members
     * @access public
     * @return bool
     */
    public function apiDeleteProjectMembers(int $serviceID, int $projectID, array $members): bool
    {
        $apiRoot = $this->getApiRoot($serviceID, false);
        $url     = sprintf($apiRoot->url, "/spaces/{$projectID}/members");

        $users = $this->loadModel('user')->getPairs('noletter|noempty|nodeleted|noclosed');
        foreach($members as $account)
        {
            if(!$account) continue;
            if(!in_array($account, array_keys($users))) continue;
            $deleteURL = $url . '/' . $account;
            $result    = common::http($deleteURL, array(), array(CURLOPT_CUSTOMREQUEST => 'DELETE'), $apiRoot->header, 'json', 'DELETE', 30, true);
            if(empty($result) || !is_array($result) || !isset($result[1]) || $result[1] != 204) dao::$errors['committer'][] = $account;
        }

        return true;
    }

    /**
     *  通过API获取项目成员。
     *  Get project members by API.
     *
     *  @param  int $serviceID
     *  @param  int $projectID
     *  @access public
     *  @return array|object
     */
    public function apiGetProjectMembers(int $serviceID, int $projectID): array|object
    {
        $apiRoot = $this->getApiRoot($serviceID, false);
        if(empty($apiRoot)) return array();

        $limit  = 100;
        $apiUrl = sprintf($apiRoot->url, "/spaces/{$projectID}/members?limit={$limit}");

        $page    = 1;
        $members = array();
        while(true)
        {
            $url = $apiUrl . '&page=' . $page;
            $result = json_decode(commonModel::http($url, array(), array(), $apiRoot->header, 'json'));
            if(empty($result) || !is_array($result) || isset($result['message'])) break;

            foreach($result as $member)
            {
                if(!isset($member->principal)) continue;
                $member->principal->role = $member->role;

                $members[$member->principal->uid] = $member->principal;
            }

            if(count($result) < $limit) break;
            $page++;
        }

        return $members;
    }

    /*
     * 通过api获取用户信息。
     * Get user info by api.
     *
     * @param  int    $gitfoxID
     * @param  string $account
     * @access public
     * @return object|false
     */
    public function apiGetUser(int $gitfoxID, string $account): object|false
    {
        $apiRoot = $this->getApiRoot($gitfoxID, false);
        $url     = sprintf($apiRoot->url, "/admin/users/{$account}");
        $result  = json_decode(common::http($url, null, array(), $apiRoot->header, 'json'));

        if(empty($result) || empty($result->id)) return false;
        return $result;
    }

    /**
     * 通过api创建用户。
     * Create user by api.
     *
     * @param  int    $gitfoxID
     * @param  array  $data
     * @access public
     * @return object|false
     */
    public function apiCreateUser(int $gitfoxID, array $data): object|false
    {
        $apiRoot = $this->getApiRoot($gitfoxID, false);
        $url     = sprintf($apiRoot->url, "/admin/users");
        $result  = json_decode(common::http($url, $data, array(CURLOPT_CUSTOMREQUEST => 'POST'), $apiRoot->header, 'json'));

        if(empty($result) || empty($result->id)) return false;
        return $result;
    }

    /**
     * 通过api更新用户信息。
     * Update user info by api.
     *
     * @param  int    $gitfoxID
     * @param  string $account
     * @param  array  $data
     * @access public
     * @return object|false
     */
    public function apiUpdateGetUser(int $gitfoxID, string $account, array $data): object|false
    {
        $apiRoot = $this->getApiRoot($gitfoxID, false);
        $url     = sprintf($apiRoot->url, "/admin/users/{$account}");
        $result  = json_decode(common::http($url, $data, array(CURLOPT_CUSTOMREQUEST => 'PATCH'), $apiRoot->header, 'json', 'PATCH'));

        if(empty($result) || empty($result->id)) return false;
        return $result;
    }

    /**
     * 通过api删除用户。
     * Delete user by api.
     *
     * @param  int    $gitfoxID
     * @param  string $account
     * @access public
     * @return bool
     */
    public function apiDeleteGetUser(int $gitfoxID, string $account): bool
    {
        $apiRoot = $this->getApiRoot($gitfoxID, false);
        $url     = sprintf($apiRoot->url, "/admin/users/{$account}");
        $result  = json_decode(common::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'DELETE'), $apiRoot->header, 'json', 'DELETE'));

        if($result && isset($result->message)) return false;
        return true;
    }

    /**
     * 同步用户数据到Gitfox。
     * Sync user data to Gitfox.
     *
     * @param  object $server
     * @param  array  $userList
     * @access public
     * @return bool
     */
    public function syncUser(object $server, array $userList): bool
    {
        foreach($userList as $user)
        {
            $user->objectID = zget($this->config->gitfox->escapeUserList, $user->objectID);

            $userData = array();
            $userData['uid']        = $user->objectID;
            $userData['identifier'] = $user->objectID;
            $userData['source']     = 'zentao';
            if(!empty($user->email))    $userData['email']         = $user->email;
            if(!empty($user->name))     $userData['display_name']  = $user->name;
            if(!empty($user->password)) $userData['password_hash'] = $user->password;

            $res = true;
            $gitfoxUser = $this->apiGetUser($server->id, $user->objectID);
            if($gitfoxUser && $user->operate == 'add') $user->operate = 'edit';

            if(!$gitfoxUser && $user->operate == 'add')  $res = $this->apiCreateUser($server->id, $userData);
            if($gitfoxUser  && $user->operate == 'edit') $res = $this->apiUpdateGetUser($server->id, $user->objectID, $userData);
            if($gitfoxUser  && $user->operate == 'del')  $res = $this->apiDeleteGetUser($server->id, $user->objectID);
            $this->gitfoxTao->saveSyncResult($user->id, (bool)$res, $user->times);
        }
        return !dao::isError();
    }

    /**
     * 镜像导入代码库到Gitfox。
     * Mirror import repo to Gitfox.
     *
     * @param  int    $gitfoxID
     * @param  object $repo
     * @access public
     * @return object|false
     */
    public function apiMirrorRepo(int $gitfoxID, object $repo): object|false
    {
        $apiRoot = $this->getApiRoot($gitfoxID, false);
        $url     = sprintf($apiRoot->url, "/repos/import");

        $repo->mirror    = true;
        $repo->pipelines = 'ignore';
        $result = json_decode(common::http($url, $repo, array(CURLOPT_CUSTOMREQUEST => 'POST'), $apiRoot->header, 'json'));

        if(empty($result) || empty($result->id)) return false;
        return $result;
    }

    /**
     * 通过api创建项目空间。
     * Create project space by api.
     *
     * @param  object $space
     * @access public
     * @return object|false
     */
    public function apiCreateSpace(object $space): object|false
    {
        $apiRoot = $this->getApiRoot();
        $url     = sprintf($apiRoot->url, '/spaces');

        $response = json_decode(common::http($url, $space, array(CURLOPT_CUSTOMREQUEST => 'POST'), $apiRoot->header, 'json'));
        $result   = $this->getResponse($response);
        if(dao::isError())
        {
            $error = dao::getError();
            if(!empty($error['apiMessage']) && strpos($error['apiMessage'], 'already exists'))
            {
                $this->app->loadLang('devopsspace');
                dao::$errors['name'] = sprintf($this->lang->error->unique, $this->lang->devopsspace->name, $space->name);
            }
            else
            {
                dao::$errors[] = $error['apiMessage'];
            }
            return false;
        }

        return $result;
    }

    /**
     * 镜像导入代码库到Gitfox。
     * Mirror import repo to Gitfox.
     *
     * @param  int    $gitfoxID
     * @param  object $repo
     * @param  object $repoServer
     * @access public
     * @return object|bool
     */
    public function mirrorRepo(int $gitfoxID, object $repo, object $repoServer): object|bool
    {
        if(!in_array($repo->SCM, $this->config->repo->gitServiceTypeList) || $repo->SCM == 'GitFox') return false;

        $repo->name = preg_replace('/\s/', '', $repo->name);
        $repo->SCM  = strtolower($repo->SCM);

        $serverUrl   = str_replace('https://', 'http://', $repoServer->url);
        $repoURL     = str_replace('https://', 'http://', $repo->path);
        $codePath    = str_replace($serverUrl, '', $repoURL);
        $codePath    = trim($codePath, '/');
        if(substr($codePath, -4) == '.git') $codePath = mb_substr($codePath, 0, -4);
        if(empty($codePath))
        {
            if($repo->SCM == 'Gitlab') $repo->serviceProject = (int)$repo->serviceProject;
            $project = $this->loadModel($repo->SCM)->apiGetSingleProject((int)$repo->serviceHost, $repo->serviceProject, false);
            if(empty($project) || !isset($project->path_with_namespace)) return false;

            $codePath = $project->path_with_namespace;
        }

        $convertName = common::convert2Pinyin(array($repo->name));
        $repoName    = explode(' ', $convertName[$repo->name]);

        $data = new stdclass();
        $data->description   = $repo->desc;
        $data->parent_ref    = $repo->SCM . '_' . $repoName[0];
        $data->identifier    = $data->parent_ref;
        $data->provider_repo = $codePath;

        $gitfoxRepo = $this->apiGetSingleRepo($gitfoxID, urlencode("{$data->parent_ref}/{$data->identifier}"));
        if($gitfoxRepo)
        {
            $this->dao->update(TABLE_REPO)->set('gitfoxID')->eq($gitfoxRepo->id)->where('id')->eq($repo->id)->exec();
            return true;
        }

        $data->provider = new stdclass();
        $data->provider->type     = $repo->SCM;
        $data->provider->host     = $repoServer->url;
        $data->provider->password = empty($repoServer->token) ? $repoServer->password : $repoServer->token;
        $data->provider->username = empty($repoServer->token) ? $repoServer->username : '';

        $result = $this->apiMirrorRepo($gitfoxID, $data);
        if($result) $this->dao->update(TABLE_REPO)->set('gitfoxID')->eq($result->id)->where('id')->eq($repo->id)->exec();

        return $result;
    }

    /**
     * 同步代码库数据到Gitfox。
     * Sync repo data to Gitfox.
     *
     * @param  object $server
     * @param  array  $repoList
     * @access public
     * @return bool
     */
    public function syncRepo(object $server, array $repoList): bool
    {
        $repoData   = $this->loadModel('repo')->getByIdList(array_column($repoList, 'objectID'));
        $serverList = $this->loadModel('pipeline')->getList(implode(',', $this->config->repo->gitServiceList));

        foreach($repoList as $syncData)
        {
            $result = false;
            if($syncData->operate == 'add')
            {
                if(empty($repoData[$syncData->objectID])) continue;

                $repo = $repoData[$syncData->objectID];
                if(empty($serverList[$repo->serviceHost])) continue;

                $result = $this->mirrorRepo($server->id, $repo, $serverList[$repo->serviceHost]);
            }
            else
            {
                if(!$this->apiGetSingleRepo($server->id, $syncData->objectID)) continue;
                $res    = $this->apiDeleteRepo($server->id, (int)$syncData->objectID);

                $result = $this->getResponse($res);
            }
            $this->gitfoxTao->saveSyncResult($syncData->id, (bool)$result, $syncData->times);
        }
        return !dao::isError();
    }

    /**
     * 通过 api 更新 webhook。
     * Update webhook by api.
     *
     * @param  int    $repoID
     * @param  int    $webhookID
     * @param  object $data
     * @access public
     * @return object|array|false
     */
    public function apiUpdateWebhook(int $repoID, int $webhookID, object $data): object|array|false
    {
        return $this->request("/repos/{$repoID}/webhooks/{$webhookID}", 'PUT', $data);
    }

    /**
     * 通过 api 获取 webhook 执行记录。
     * Get webhook execution info by api.
     *
     * @param  int   $repoID
     * @param  int   $webhookID
     * @param  int   $executionID
     * @param  array $params
     * @access public
     * @return array|object|false
     */
    public function apiGetWebhookExecution(int $repoID, int $webhookID, int $executionID = 0, array $params = array()): array|object|false
    {
        $apiPath = "/repos/{$repoID}/webhooks/{$webhookID}/executions";
        if($executionID) $apiPath .= "/{$executionID}";
        $result = $this->request($apiPath, 'GET', $params);
        if(!$executionID && is_object($result) && isset($result->data)) return $result->data;
        return $result;
    }

    /**
     * 通过 API 删除 webhook。
     * Delete webhook by api.
     *
     * @param  int $repoID
     * @param  int $webhookID
     * @access public
     * @return object|array|bool
     */
    public function apiDeleteWebhook(int $repoID, int $webhookID): object|array|bool
    {
        if(empty($repoID)) return false;
        return $this->request("/repos/{$repoID}/webhooks/{$webhookID}", 'DELETE');
    }

    /**
     * 通过api评审合并请求。
     * Review merge request by api.
     *
     * @param  object $mr
     * @param  string $sha
     * @param  string $result   approved|changereq
     * @access public
     * @return bool|object
     */
    public function apiReviewMR(object $mr, string $sha, string $result = 'approved'): bool|object
    {
        $apiRoot = $this->getApiRoot($mr->hostID);
        if(empty($apiRoot)) return false;

        $url     = sprintf($apiRoot->url, "/repos/{$mr->sourceProject}/pullreq/{$mr->mriid}/reviews");
        $result  = json_decode(common::http($url, array('commit_sha' => $sha, 'decision' => $result), array(CURLOPT_CUSTOMREQUEST => 'POST'), $apiRoot->header, 'json'));

        if(!empty($result) || !empty($result->message)) return $result;
        return true;
    }

    /**
     * 通过API获取代码库规则。
     * Api get repo rules.
     *
     * @param  int    $gitfoxID
     * @param  int    $repoID
     * @param  string $ruleID
     * @access public
     * @return array|object|null
     */
    public function apiGetRules(int $gitfoxID, int $repoID, string $ruleID = ''): array|object|null
    {
        $apiRoot = $this->getApiRoot($gitfoxID, false);
        $url     = sprintf($apiRoot->url, "/repos/{$repoID}/rules");
        if($ruleID) $url .= "/{$ruleID}";

        return json_decode(common::http($url, null, array(), $apiRoot->header, 'json'), true);
    }

    /**
     * 通过API创建代码库规则。
     * Api create repo rules.
     *
     * @param  int    $gitfoxID
     * @param  int    $repoID
     * @param  object $rules
     * @access public
     * @return object|bool
     */
    public function apiCreateRules(int $gitfoxID, int $repoID, object $rules): object|bool
    {
        $apiRoot = $this->getApiRoot($gitfoxID, false);
        $url     = sprintf($apiRoot->url, "/repos/{$repoID}/rules");
        $result  = json_decode(common::http($url, $rules, array(CURLOPT_CUSTOMREQUEST => 'POST'), $apiRoot->header, 'json'));

        if(empty($result) || empty($result->identifier)) return false;
        return $result;
    }

        /**
     * 通过API创建分支类型。
     * Api create branch type.
     *
     * @param  int    $gitfoxID
     * @param  int    $repoID
     * @param  object $rules
     * @access public
     * @return object|bool
     */
    public function apiCreateBranchType(int $gitfoxID, array $branchtype): object|false
    {
        $apiRoot = $this->getApiRoot();
        $url     = sprintf($apiRoot->url, "/repos/{$gitfoxID}/branch-types");
        $result  = json_decode(common::http($url, $branchtype, array(CURLOPT_CUSTOMREQUEST => 'POST'), $apiRoot->header, 'json'));
        $response = $this->getResponse($result);
        if(is_array($response)) return current($response);
        return false;
    }

    /**
     * 通过API删除分支类型。
     * Api delete branch type.
     *
     * @param  int $repoID
     * @param  int $typeID
     * @access public
     * @return bool
     */
    public function apiDeleteBranchType(int $repoID, int $typeID): bool
    {
        $apiRoot = $this->getApiRoot();
        $url     = sprintf($apiRoot->url, "/repos/{$repoID}/branch-types/{$typeID}");
        $result  = json_decode(common::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'DELETE'), $apiRoot->header, 'json'));
        return $this->getResponse($result);
    }

    /**
     * 通过API更新分支类型。
     * Api update branch type.
     *
     * @param  int   $repoID
     * @param  int   $typeID
     * @param  array $branchtype
     * @access public
     * @return bool
     */
    public function apiUpdateBranchType(int $repoID, int $typeID, array $branchtype): bool
    {
        $apiRoot = $this->getApiRoot();
        $url     = sprintf($apiRoot->url, "/repos/{$repoID}/branch-types/{$typeID}");
        $result  = json_decode(common::http($url, $branchtype, array(CURLOPT_CUSTOMREQUEST => 'PUT'), $apiRoot->header, 'json'));
        $response = $this->getResponse($result);
        return is_object($response) && !empty($response->id);
    }

    /**
     * 通过API更新代码库规则。
     * Api update repo rules.
     *
     * @param  int    $gitfoxID
     * @param  int    $repoID
     * @param  string $ruleID
     * @param  object $rules
     * @access public
     * @return object|bool
     */
    public function apiUpdateRules(int $gitfoxID, int $repoID, string $ruleID, object $rules): object|bool
    {
        $apiRoot = $this->getApiRoot($gitfoxID, false);
        $url     = sprintf($apiRoot->url, "/repos/{$repoID}/rules/{$ruleID}");
        $result  = json_decode(common::http($url, $rules, array(CURLOPT_CUSTOMREQUEST => 'PATCH'), $apiRoot->header, 'json'));

        if(empty($result) || empty($result->identifier)) return false;
        return $result;
    }

    /**
     * 通过API删除代码库规则。
     * Api delete repo rules.
     *
     * @param  int    $gitfoxID
     * @param  int    $repoID
     * @param  string $ruleID
     * @access public
     * @return object|array|null|false
     */
    public function apiDeleteRule(int $gitfoxID, int $repoID, string $ruleID): object|array|null|false
    {
        $apiRoot = $this->getApiRoot($gitfoxID, false);
        $url     = sprintf($apiRoot->url, "/repos/{$repoID}/rules/{$ruleID}");
        return json_decode(common::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'DELETE'), $apiRoot->header, 'json'));
    }

    /**
     * 通过API创建用户令牌。
     * Api create user token.
     *
     * @param  int    $gitfoxID
     * @param  string $account
     * @param  string $tokenName
     * @access public
     * @return string|false
     */
    public function apiCreateUserToken(int $gitfoxID, string $account , string $tokenName = ''): string|false
    {
        $openID = $this->loadModel('pipeline')->getOpenIdByAccount($gitfoxID, 'gitfox', $account);
        if(!$openID && $this->app->user->admin && $this->app->user->account == $account) $openID = $account;
        if(!$openID) return false;

        $apiRoot = $this->getApiRoot($gitfoxID, false);
        if(empty($apiRoot)) return false;

        if(empty($tokenName)) $tokenName = "zentao-{$account}-" . date('YmdHis');
        $url = sprintf($apiRoot->url, '/user/tokens');
        $apiRoot->header[] = "Sudo: $openID";
        $result = json_decode(common::http($url, array('identifier' => $tokenName), array(CURLOPT_CUSTOMREQUEST => 'POST'), $apiRoot->header, 'json'));
        if(empty($result) || empty($result->access_token)) return false;

        $linkage = new stdclass();
        $linkage->token        = $result->access_token;
        $linkage->openID       = $openID;
        $linkage->account      = $account;
        $linkage->providerType = 'gitfox';
        $linkage->providerID   = $gitfoxID;
        $this->dao->replace(TABLE_OAUTH)->data($linkage)->exec();
        return $result->access_token;
    }

    /**
     * 通过API创建流水线。
     * Api create pipeline.
     *
     * @param  int    $gitfoxID
     * @param  int    $repoID
     * @param  object $pipeline
     * @access public
     * @return object|bool
     */
    public function apiCreatePipeline(int $gitfoxID, int $repoID, object $pipeline): object|bool
    {
        $apiRoot = $this->getApiRoot($gitfoxID, false);
        $url     = sprintf($apiRoot->url, "/repos/{$repoID}/pipelines");
        $result  = json_decode(common::http($url, $pipeline, array(CURLOPT_CUSTOMREQUEST => 'POST'), $apiRoot->header, 'json'));

        if(empty($result) || empty($result->identifier)) return false;
        return $result;
    }

    /**
     * 获取代码库的成员。
     * Get repo members.
     *
     * @param  object $repo
     * @access public
     * @return array
     */
    public function getRepoMembers(object $repo): array|object
    {
        $singleRepo = $this->apiGetSingleRepo((int)$repo->serviceHost, (int)$repo->serviceProject);
        if(empty($singleRepo)) return array();
        return $this->apiGetProjectMembers((int)$repo->serviceHost, (int)$singleRepo->parent_id);
    }

    /**
     * 通过API更新流水线。
     * Api update pipeline.
     *
     * @param  int    $gitfoxID
     * @param  int    $repoID
     * @param  string $pipelineID
     * @param  object $data
     * @access public
     * @return object|false
     */
    public function apiUpdatePipeline(int $gitfoxID, int $repoID, string $pipelineID, object $data): object|false
    {
        $apiRoot = $this->getApiRoot($gitfoxID, false);
        $url     = sprintf($apiRoot->url, "/repos/{$repoID}/pipelines/{$pipelineID}");
        $result  = json_decode(common::http($url, $data, array(CURLOPT_CUSTOMREQUEST => 'PATCH'), $apiRoot->header, 'json', 'PATCH'));

        if(empty($result) || empty($result->identifier)) return false;
        return $result;
    }

    /**
     * 通过api删除流水线。
     * Delete pipeline by api.
     *
     * @param  int    $gitfoxID
     * @param  int    $repoID
     * @param  string $pipeline
     * @access public
     * @return bool
     */
    public function apiDeletePipeline(int $gitfoxID, int $repoID, string $pipeline): bool
    {
        $apiRoot = $this->getApiRoot($gitfoxID, false);
        $url     = sprintf($apiRoot->url, "/repos/{$repoID}/pipelines/{$pipeline}");
        $result  = json_decode(common::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'DELETE'), $apiRoot->header, 'json', 'DELETE'));

        if($result && isset($result->message)) return false;
        return true;
    }

    /**
     * 通过API获取代码库提交的统计数据。
     * Api get code repository commit statistics.
     *
     * @param  int    $gitfoxID
     * @param  int    $repoID
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return array|false
     */
    public function apiGetCommitStatistics(int $gitfoxID, int $repoID, string $begin, string $end, string $type = 'commits'): array|false
    {
        $apiRoot = $this->getApiRoot($gitfoxID, false);
        $url     = sprintf($apiRoot->url, "/statistics/repo/{$type}");

        $param = new stdclass();
        $param->begin_time = $begin;
        $param->end_time   = $end;
        $param->repo       = "$repoID";
        $result  = json_decode(common::http($url, $param, array(), $apiRoot->header, 'json', 'POST'));
        if(empty($result) || empty($result->stats)) return false;
        return $result->stats;
    }

    /**
     * 获取活跃度前十的代码库。
     * Get top ten repos.
     *
     * @param  string $begin
     * @param  string $end
     * @param  array  $repoList
     * @access public
     * @return array
     */
    public function getTopRepos(string $begin = '', string $end = '', array $repoList = array()): array
    {
        if(empty($repoList)) return array();

        foreach($repoList as $repo)
        {
            $repoIdList[] = "$repo->gitfoxID";

            $gitfoxList["$repo->gitfoxID"] = $repo;
        }
        $apiRoot = $this->getApiRoot((int)$repo->serviceHost, false);
        $url     = sprintf($apiRoot->url, '/statistics/repos/top10');

        $param = new stdclass();
        $param->begin_time = $begin;
        $param->end_time   = $end;
        $param->repos      = $repoIdList;

        $result  = json_decode(common::http($url, $param, array(), $apiRoot->header, 'json', 'POST'));
        if(empty($result) || !is_array($result)) return array();

        foreach($result as $repo)
        {
            $repo->name = isset($gitfoxList[$repo->repo_id]) ? $gitfoxList[$repo->repo_id]->name : $repo->repo_name;
            $repo->id   = isset($gitfoxList[$repo->repo_id]) ? $gitfoxList[$repo->repo_id]->id : 0;
        }
        return $result;
    }

    /**
     * 通过API获取活跃代码库的统计数据。
     * Api get active code repository statistics.
     *
     * @param  int    $gitfoxID
     * @param  array  $repoIdList
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return object|false
     */
    public function apiCountActiveRepos(int $gitfoxID, array $repoIdList, string $begin, string $end): object|false
    {
        $apiRoot = $this->getApiRoot($gitfoxID, false);
        $url     = sprintf($apiRoot->url, '/statistics/repos/active');

        $repoIdMap = array();
        foreach($repoIdList as $repoId) $repoIdMap[] = (string)$repoId;

        $param = new stdclass();
        $param->begin_time = $begin;
        $param->end_time   = $end;
        $param->repos      = $repoIdMap;
        $result  = json_decode(common::http($url, $param, array(), $apiRoot->header, 'json', 'POST'));
        if(empty($result) || isset($result->message) || empty($result->commit_count)) return false;
        return $result;
    }

    /**
     * 通过服务器代码库ID获取对应的禅道代码库ID。
     * Get code repo id by server repo id.
     *
     * @param  int $serviceProject
     * @param  int $serverID
     * @access public
     * @return int
     */
    public function getIdByServiceProject(int $serviceProject, int $serverID = 0): int
    {
        $repoID = $this->dao->select('id')->from(TABLE_REPO)
            ->where('serviceProject')->eq($serviceProject)
            ->fetch('id');
        return $repoID ? $repoID : 0;
    }

    /**
     * 回放流水线执行记录。
     * Resend pipeline execution record.
     *
     * @param  int $gitfoxID
     * @param  int $repoID
     * @param  string $jobName
     * @param  int $buildID
     * @access public
     * @return bool
     */
    public function resendJob(int $gitfoxID, int $repoID, string $jobName, int $buildID): bool
    {
        $apiRoot = $this->getApiRoot($gitfoxID, false);
        $url     = sprintf($apiRoot->url, "/repos/{$repoID}/pipelines/{$jobName}/executions/{$buildID}/retry");

        $result  = json_decode(common::http($url, array('retry' => 'true'), array(), $apiRoot->header, 'json', 'POST'));
        if($result && isset($result->detail)) return false;
        return true;
    }

    /**
     * 通过API获取代码所有者设置。
     * Api get code owner settings.
     *
     * @param  int $gitfoxID
     * @param  int $repoID
     * @access public
     * @return object|array
     *
     */
    public function apiGetOwnerSettings(int $gitfoxID, int $repoID): object|array
    {
        $apiRoot = $this->getApiRoot($gitfoxID, false);
        $url     = sprintf($apiRoot->url, "/repos/{$repoID}/codeowners");
        $result  = json_decode(common::http($url, array(), array(), $apiRoot->header, 'json', 'GET'));
        if(!$result || !empty($result->message)) return array();
        return $result;
    }

    /**
     * 通过API设置代码所有者设置。
     * Api set code owner settings.
     *
     * @param  int $gitfox
     * @param  int $repoID
     * @param  array $matchBranches
     * @param  string $content
     * @param  int    $patch
     * @access public
     * @return bool
     */
    public function apiSetOwnerSettings(int $gitfox, int $repoID, array $matchBranches, string $content, $patch = false): bool
    {
        $apiRoot = $this->getApiRoot($gitfox, false);
        $url     = sprintf($apiRoot->url, "/repos/{$repoID}/codeowners");
        $result  = json_decode(common::http($url, array('match_branches' => $matchBranches, 'content' => $content), $patch ? array(CURLOPT_CUSTOMREQUEST => 'PATCH') : array(), $apiRoot->header, 'json', $patch ? 'PATCH' : 'POST'));
        if(empty($result) || !empty($result->message))
        {
            if(!empty($result->message)) dao::$errors[] = $result->message;
            return false;
        }

        $notFoundUsers = array();
        if(is_array($result))
        {
            foreach($result as $message)
            {
                if(!empty($message->code) && $message->code == 'user_not_found' && !empty($message->params))
                {
                    foreach($message->params as $user)
                    {
                        $notFoundUsers[] = $user;
                    }
                }
            }
        }
        if(!empty($notFoundUsers))
        {
            $notFoundUsers = array_unique($notFoundUsers);
            dao::$errors[] = sprintf($this->lang->repo->rule->emailNotFound, implode(',', $notFoundUsers));
            return false;
        }
        return true;
    }

    /**
     * 通过API获取分支标签。
     * Api get branch labels.
     *
     * @param  int $gitfoxID
     * @param  int $repoID
     * @access public
     * @return object|array
     */
    public function apiGetBranchLabels(int $gitfoxID, int $repoID): object|array
    {
        $apiRoot = $this->getApiRoot($gitfoxID, false);
        $url     = sprintf($apiRoot->url, "/repos/{$repoID}/branch-labels");
        $result  = json_decode(common::http($url, array(), array(), $apiRoot->header, 'json', 'GET'));
        if(empty($result) || !empty($result->message)) return array();
        return $result;
    }

    /**
     * 获取仓库分支归档设置。
     * Get repo branch archive settings.
     *
     * @param  int $gitfoxID
     * @param  int $repoID
     * @access public
     * @return bool
     */
    public function apiGetRepoBranchArchiveEnabled(int $gitfoxID, int $repoID): bool
    {
        $apiRoot = $this->getApiRoot($gitfoxID, false);

        $url    = sprintf($apiRoot->url, "/repos/{$repoID}/settings/advanced");
        $result = json_decode(common::http($url, null, array(), $apiRoot->header, 'json'), true);
        if(!empty($result) && zget($result, 'archive_enabled', 0)) return true;
        return false;
    }

    /**
     * 通过API获取代码提交者列表。
     * Api get committers.
     *
     * @param  int $gitfoxID
     * @param  int $repoID
     * @access public
     * @return array
     */
    public function apiGetCommitters(int $gitfoxID, int $repoID): array
    {
        $apiRoot = $this->getApiRoot($gitfoxID, false);
        $url     = sprintf($apiRoot->url, "/repos/{$repoID}/committers");
        $result  = json_decode(common::http($url, null, array(), $apiRoot->header, 'json'), true);
        if(empty($result) || !empty($result['message'])) return array();
        return $result;
    }

    /**
     * 通过API绑定用户邮箱。
     * Api bind user email.
     *
     * @param  int $gitfoxID
     * @param  int $repoID
     * @param  int $userID
     * @param  array $email
     * @access public
     * @return bool
     */
    public function apiBindUserEmail(int $gitfoxID, int $repoID, int $userID, array $email): bool
    {
        $data = new stdClass();
        $data->principal_id = $userID;
        $data->emails       = empty($email['0']) ? array() : $email;

        $apiRoot = $this->getApiRoot($gitfoxID, false);
        $url     = sprintf($apiRoot->url, "/repos/{$repoID}/committers/bind");
        $result  = json_decode(common::http($url, $data, array(), $apiRoot->header, 'json', 'POST'));
        if(empty($result) || isset($result->message))
        {
            if(!empty($result->message)) dao::$errors[] = $result->message;
            return false;
        }
        return true;
    }

    /**
     * 通过API更新空间。
     * Api update space.
     *
     * @param  int $spaceID
     * @param  object $data
     * @access public
     * @return bool
     */
    public function apiUpdateSpace(int $spaceID, object $data): bool
    {
        $apiRoot = $this->getApiRoot();
        $url     = sprintf($apiRoot->url, "/spaces/{$spaceID}");

        $response = json_decode(common::http($url, $data, array(), $apiRoot->header, 'json', 'PUT'));
        $result   = $this->getResponse($response);
        if(dao::isError())
        {
            $error = dao::getError();
            if(!empty($error['apiMessage']) && strpos($error['apiMessage'], 'already exists'))
            {
                $this->app->loadLang('devopsspace');
                dao::$errors['name'] = sprintf($this->lang->error->unique, $this->lang->devopsspace->name, $data->name);
            }
            else
            {
                dao::$errors[] = $error['apiMessage'];
            }
            return false;
        }

        return !empty($result);
    }

    /**
     * 通过API获取空间列表。
     * Api get spaces.
     *
     * @param  array $query
     * @access public
     * @return array
     */
    public function apiGetSpaces(array $query, ?object $pager = null): object|bool
    {
        $apiRoot = $this->getApiRoot();
        $url     = sprintf($apiRoot->url, '/spaces');
        $pager   = $this->getPage($pager);
        if(!empty($pager)) $query = array_merge($query, $pager);
        if(!empty($query)) $url .= '?' . http_build_query($query);

        $result = json_decode(common::http($url, null, array(), $apiRoot->header, 'json', 'GET'));
        return $this->getResponse($result);
    }

    /**
     * 通过API获取空间详情。
     * Api get space.
     *
     * @param  int $gitfox
     * @param  int $spaceID
     * @access public
     * @return object|array
     */
    public function apiGetSpace(int $spaceID): object|array
    {
        $apiRoot = $this->getApiRoot();
        $url     = sprintf($apiRoot->url, '/spaces' . "/{$spaceID}");

        $response = json_decode(common::http($url, null, array(), $apiRoot->header));
        $result   = $this->getResponse($response);

        return $result ? $result : array();
    }

    /**
     * 通过API删除空间。
     * Api delete space.
     *
     * @param  int $spaceID
     * @access public
     * @return bool
     */
    public function apiDeleteSpace(int $spaceID): bool
    {
        $apiRoot = $this->getApiRoot();
        $url     = sprintf($apiRoot->url, '/spaces' . "/{$spaceID}");
        $result  = json_decode(common::http($url, null, array(CURLOPT_CUSTOMREQUEST => 'DELETE'), $apiRoot->header, 'json', 'DELETE'));
        return $this->getResponse($result);
    }

    /**
     * 获取gitfox 提交列表。
     * Get Gitfox commits.
     *
     * @param  object $repo
     * @param  string $entry
     * @param  object $pager
     * @param  string $begin
     * @param  string $end
     * @param  object $query
     * @access public
     * @return array
     */
    public function getCommits(object $repo, string $entry, ?object $pager = null, string $begin = '', string $end = '', ?object $query = null): array
    {
        $scm = $this->app->loadClass('scm');
        $scm->setEngine($repo);
        $fromRevision = $toRevision = $committer = '';
        if($query)
        {
            $fromRevision = zget($query, 'commit', '');
            $toRevision   = $fromRevision;
            $committer    = zget($query, 'committer', '');

            if(!empty($query->begin)) $begin = $query->begin;
            if(!empty($query->end))   $end   = $query->end;
        }
        $comments = $scm->engine->getCommitsByPath($entry, $fromRevision, $toRevision, isset($pager->recPerPage) ? $pager->recPerPage : 10, isset($pager->pageID) ? $pager->pageID : 1, $begin, $end, $committer);

        if(!is_array($comments)) return array();

        /* SVN 类型由 subversionRepo 静态变量回填真实总数;git 类型仍走伪分页推算。 */
        if(isset($pager->recTotal))
        {
            if(get_class($scm->engine) == 'subversionRepo')
            {
                $pager->recTotal = (int)subversionRepo::$lastCommitsTotal;
            }
            else
            {
                $pager->recTotal = count($comments) < $pager->recPerPage ? $pager->recPerPage * $pager->pageID : $pager->recPerPage * ($pager->pageID + 1);
            }
        }

        $commitIds   = array();
        foreach($comments as $comment)
        {
            $commitDate = isset($comment->author->when) ? $comment->author->when : $comment->committed_date;
            if(!isset($comment->id)) $comment->id = $comment->sha;

            $comment->revision        = $comment->id;
            $comment->originalComment = $comment->title;
            $comment->committed_date  = $commitDate;
            $comment->comment         = $this->loadModel('repo')->replaceCommentLink($comment->title);
            $comment->committer       = isset($comment->author->identity->name) ? $comment->author->identity->name : $comment->committer_name;
            $comment->time            = date("Y-m-d H:i:s", strtotime($commitDate));
            $commitIds[]              = $comment->id;
        }
        $commitCounts = $this->dao->select('revision,commit')->from(TABLE_REPOHISTORY)->where('revision')->in($commitIds)->fetchPairs();
        foreach($comments as $comment) $comment->commit = !empty($commitCounts[$comment->id]) ? $commitCounts[$comment->id] : '';

        return $comments;
    }

    /*
     * 通过api删除指定tag。
     * Delete specified tag by api.
     *
     * @param  int    $repoID
     * @param  string $tag
     * @access public
     * @return bool
     */
    public function apiDeleteTag(int $repoID, string $tag): bool
    {
        $apiRoot = $this->getApiRoot();
        if(!$apiRoot) return false;

        $tag = urlencode($tag);
        $api = "/repos/{$repoID}/tags/{$tag}";

        $header = array();
        if(is_object($apiRoot))
        {
            $header  = $apiRoot->header;
            $apiRoot = $apiRoot->url;
        }

        $resp = json_decode(common::http(sprintf($apiRoot, $api), array(), array(CURLOPT_CUSTOMREQUEST => 'DELETE'), $header, 'json', 'DELETE'));
        return $this->getResponse($resp);
    }

    /**
     * 还原空间数据。
     * Restore space data.
     *
     * @param  int    $spaceID
     * @access public
     * @return bool
     */
    public function apiRestoreSpace(int $spaceID): bool
    {
        $apiRoot = $this->getApiRoot();
        $url     = sprintf($apiRoot->url, "/spaces/{$spaceID}/restore");
        $result  = json_decode(common::http($url, array(), array(CURLOPT_CUSTOMREQUEST => 'POST'), $apiRoot->header, 'json'));
        return $this->getResponse($result);
    }

    /**
     * 通过API获取指定空间的提交列表。
     * Api get commits of specified space.
     *
     * @param  int    $repoID
     * @param  array  $params
     * @access public
     * @return object|bool
     */
    public function apiGetCommits(int $repoID, array $params): object|bool
    {
        $apiRoot = $this->getApiRoot();
        $url     = sprintf($apiRoot->url, '/repos/' . $repoID . '/commits/list');

        $response = json_decode(common::http($url, $params, array(), $apiRoot->header, 'json'));
        return $this->getResponse($response);
    }

    /**
     * 通过API获取合并检查信息。
     * Api get merge check message.
     *
     * @param  int    $repoID
     * @param  string $source
     * @param  string $target
     * @param  string $type
     * @access public
     * @return object|bool
     */
    public function apiGetMergeCheckMessage(int $repoID, string $source, string $target, $type = 'branch'): object|bool
    {
        if($type == 'branch')
        {
            $source = 'refs/heads/' . $source;
            $target = 'refs/heads/' . $target;
        }
        $apiRoot = $this->getApiRoot();
        $url     = sprintf($apiRoot->url, '/repos/' . $repoID . '/merge-check/' . $target . '...' . $source);
        $result  = json_decode(common::http($url, array('source' => $source, 'target' => $target), array(CURLOPT_CUSTOMREQUEST => 'POST'), $apiRoot->header, 'json'));
        return $this->getResponse($result);
    }

    /**
     * 通过API获取指定分支的diff统计。
     * Api get diff stats of specified branch.
     *
     * @param  int    $repoID
     * @param  string $source
     * @param  string $target
     * @param  string $type
     * @access public
     * @return object|bool
     */
    public function apiGetDiffStats(int $repoID, string $source, string $target, string $type = 'branch'): object|bool
    {
        if($type == 'branch')
        {
            $source = 'refs/heads/' . $source;
            $target = 'refs/heads/' . $target;
        }
        $apiRoot = $this->getApiRoot();
        $url     = sprintf($apiRoot->url, "/repos/{$repoID}/diff-stats/{$target}...{$source}");
        $result  = json_decode(common::http($url, array(), array(), $apiRoot->header, 'json'));
        return $this->getResponse($result);
    }

    /**
     * 通过API更新指定的仓库。
     * Api update repo.
     *
     * @param  int    $repoID
     * @param  object $repo
     * @access public
     * @return void
     */
    public function apiUpdateRepo(int $repoID, object $repo)
    {
        $apiRoot = $this->getApiRoot();
        $url     = sprintf($apiRoot->url, "/repos/{$repoID}");

        $data = new stdClass();
        $data->name          = $repo->name;
        $data->defaultBranch = $repo->defaultBranch;
        $data->readme        = true;
        $data->desc          = $repo->desc;
        $data->acl           = $repo->acl;
        $data->spaceID       = $repo->space;
        $data->product       = $repo->product;

        $response = json_decode(common::http($url, $data, array(CURLOPT_CUSTOMREQUEST => 'PUT'), $apiRoot->header, 'json', 'PUT'));
        return $this->getResponse($response);
    }
}
