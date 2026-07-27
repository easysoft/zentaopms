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
     * 检查服务是否健康。
     * Check service health.
     *
     * @access public
     * @return bool|string
     */
    public function checkHealth(): bool|string
    {
        $url = $this->config->devops->gitfoxURL;
        if($this->config->devops->gitfoxPort) $url .= ':' . $this->config->devops->gitfoxPort;

        $url = rtrim($url, '/') . '/public/health';

        $result  = json_decode(common::http($url));
        if(empty($result) || empty($result->status) || $result->status != 'healthy') return false;

        $checkResult = 'healthy';
        $version     = zget($result, 'version', '');
        if($version != $this->config->devops->gitfoxVersion) $checkResult = 'upgrade';
        if(!$version) $checkResult = 'beta';

        return $checkResult;
    }

    /**
     * 获取gitfox 服务器信息。
     * Get gitfox server info.
     *
     * @access public
     * @return object
     */
    public function getServer(): object
    {
        $server = $this->dao->select('*')->from(TABLE_ENTRY)->where('code')->eq('gitfox')->fetch();

        $gitfox = new stdclass;
        $gitfox->url   = $this->config->devops->gitfoxURL;
        if($this->config->devops->gitfoxPort) $gitfox->url .= ':' . $this->config->devops->gitfoxPort;
        $gitfox->token = empty($server->key) ? '' : md5('zentao' . $this->app->user->account . $server->key);
        return $gitfox;
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
     * 通过api创建一个分支用户。
     * Create a repo branch.
     *
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
        $url     = sprintf($apiRoot->url, '/repos/' . $repoID . '/merge-check');
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
        $url     = sprintf($apiRoot->url, "/repos/{$repoID}/diff-stats");
        $result  = json_decode(common::http($url, array('source' => $source, 'target' => $target), array(), $apiRoot->header, 'json'));
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
