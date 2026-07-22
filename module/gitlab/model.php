<?php
declare(strict_types=1);
/**
 * The model file of gitlab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: $
 * @link        https://www.zentao.net
 */

class gitlabModel extends model
{

    /**
     * 发送一个get api请求。
     * Send an api get request.
     *
     * @param  int|string $host gitlab server ID | gitlab host url.
     * @param  string     $api
     * @access public
     * @return object|array|null
     */
    public function apiGet(int|string $host, string $api): object|array|null
    {
        if(strpos($host, 'http://') !== 0 and strpos($host, 'https://') !== 0) return null;

        $url = sprintf($host, $api);
        return json_decode(commonModel::http($url));
    }

    /**
     * 添加一个事件触发器的 webhook 到 gitlab 项目。
     * Add webhook with push and merge request events to GitLab project.
     *
     * @param  string     $pipelineID
     * @param  string     $callbackToken
     * @param  string     $url        gitlab server URL string.
     * @param  string     $token
     * @param  string     $projectID  gitlab project ID.
     * @access public
     * @return bool|array
     */
    public function addPushWebhook(string $pipelineID, string $callbackToken, string $url, string $token = '', string $projectID = ''): bool|array
    {
        /* 先验证 url 和 token 是否有效。 */
        $user = $this->checkTokenAccess($url, $token);
        if(!$user || !isset($user->id)) return false;

        $apiRoot     = rtrim($url, '/') . "/api/v4%s?private_token={$token}";
        $systemURL   = dirname(common::getSysURL() . $_SERVER['REQUEST_URI']);
        $callbackURL = $systemURL . '/api.php/v1/gitlab/webhook?pipelineID=' . $pipelineID;

        /* Check if webhook already exists. */
        if($this->isWebhookExists($url, $token, $projectID, $callbackURL)) return true;

        $hook = new stdClass;
        $hook->url                      = $callbackURL;
        if($callbackToken) $hook->token = $callbackToken;
        $hook->enable_ssl_verification  = "false";
        $hook->push_events              = true;
        $hook->merge_requests_events    = true;
        $hook->tag_push_events          = true;

        $result = json_decode(commonModel::http(sprintf($apiRoot, "/projects/{$projectID}/hooks"), $hook, array(), array(), 'json'));
        if(!empty($result->id)) return true;
        if(!empty($result->message)) return array('result' => 'fail', 'message' => $this->lang->gitlab->failCreateWebhook);
        return false;
    }

    /**
     * 检查webhook是否存在。
     * Check if Webhook exists.
     *
     * @param  string $url
     * @param  string $token
     * @param  string $projectID
     * @param  string $callbackURL
     * @return bool
     */
    public function isWebhookExists(string $url, string $token, string $projectID, string $callbackURL = ''): bool
    {
        $apiRoot  = rtrim($url, '/') . "/api/v4%s?private_token={$token}";
        $hookList = json_decode(commonModel::http(sprintf($apiRoot, "/projects/{$projectID}/hooks") . '&per_page=100'));
        if(!is_array($hookList)) return false;

        foreach($hookList as $hook)
        {
            if(empty($hook->url)) continue;
            if($hook->url == $callbackURL) return true;
        }
        return false;
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
        $apiRoot  = rtrim($url, '/') . '/api/v4%s' . "?private_token={$token}";
        $url      = sprintf($apiRoot, "/users") . "&per_page=5&active=true";
        $response = commonModel::http($url);
        $users    = json_decode($response);
        if(empty($users)) return false;
        if(isset($users->message) or isset($users->error)) return null;

        $apiRoot .= '&sudo=' . $users[0]->id;
        return $this->apiGet($apiRoot, '/user');
    }

    /**
     * 通过api创建一个流水线。
     * Create a new pipeline by api.
     *
     * @param  string $url
     * @param  string $token
     * @param  string $projectID
     * @param  object $params
     * @access public
     * @return object
     * @docment https://docs.gitlab.com/ee/api/pipelines.html#create-a-new-pipeline
     */
    public function apiCreatePipeline(string $url, string $token, string $projectID, object $params): object|array|null
    {
        if(!is_string($params)) $params = json_encode($params);
        $apiRoot  = rtrim($url, '/') . '/api/v4%s' . "?private_token={$token}";
        $url = sprintf($apiRoot, "/projects/{$projectID}/pipeline");
        return json_decode(commonModel::http($url, $params, array(), array("Content-Type: application/json")));
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
                $errorKey = array_search($response->message, $this->lang->gitlab->apiError);
                dao::$errors[] = $errorKey === false ? $response->message : zget($this->lang->gitlab->errorLang, $errorKey);
            }
            else
            {
                foreach($response->message as $field => $fieldErrors)
                {
                    if(empty($fieldErrors)) continue;

                    if(is_string($fieldErrors))
                    {
                        $errorKey = array_search($fieldErrors, $this->lang->gitlab->apiError);
                        if($fieldErrors) dao::$errors[$field][] = $errorKey === false ? $fieldErrors : zget($this->lang->gitlab->errorLang, $errorKey);
                    }
                    else
                    {
                        foreach($fieldErrors as $error)
                        {
                            $errorKey = array_search($error, $this->lang->gitlab->apiError);
                            if($error) dao::$errors[$field][] = $errorKey === false ? $error : zget($this->lang->gitlab->errorLang, $errorKey);
                        }
                    }
                }
            }
        }

        if(!$response) dao::$errors[] = false;
        return false;
    }

    /**
     * 通过api获取执行信息。
     * Get execution info by api.
     *
     * @param  int    $number
     * @param  string $pipelineName
     * @param  object $provider
     * @access public
     * @return array|object
     */
    public function apiGetExecInfo(int $number, string $pipelineName, object $provider): array|object
    {
        if(empty($provider->token) || empty($provider->url)) return array();

        $url = rtrim($provider->url, '/') . '/api/v4%s' . "?private_token={$provider->token}";
        $url = sprintf($url, "/projects/{$pipelineName}/pipelines/{$number}");

        $response = json_decode(common::http($url, null, array(), array('Accept: application/json'), 'json'));
        if(empty($response) || empty($response->status)) return array();
        return $response;
    }

    /**
     * 通过api获取流水线的日志。
     * Get pipeline logs by api.
     *
     * @param  int    $buildNumber
     * @param  string $pipelineName
     * @param  object $provider
     * @access public
     * @return string
     */
    public function getLogs(int $buildNumber, string $pipelineName, object $provider): string
    {
        if(empty($provider->token) || empty($provider->url)) return '';

        $apiURL = rtrim($provider->url, '/') . '/api/v4%s' . "?private_token={$provider->token}";
        $url    = sprintf($apiURL, "/projects/{$pipelineName}/pipelines/{$buildNumber}/jobs");
        $jobs   = json_decode(commonModel::http($url));
        if(empty($jobs)) return '';

        $this->loadModel('ci');
        $logs = '';
        foreach($jobs as $gitlabJob)
        {
            if(!is_object($gitlabJob)) continue;

            if(empty($gitlabJob->duration)) $gitlabJob->duration = '-';
            $logs .= "<font style='font-weight:bold'>&gt;&gt;&gt; Job: {$gitlabJob->name}, Stage: {$gitlabJob->stage}, Status: {$gitlabJob->status}, Duration: {$gitlabJob->duration} Sec\r\n </font>";
            $logs .= "Job URL: <a href=\"{$gitlabJob->web_url}\" target='_blank'>$gitlabJob->web_url</a> \r\n";

            $jobLogUrl = sprintf($apiURL, "/projects/{$pipelineName}/jobs/{$gitlabJob->id}/trace");
            $jobLog    = commonModel::http($jobLogUrl);
            if(empty($jobLog)) continue;

            $logs .= $this->ci->transformAnsiToHtml($jobLog);
        }

        $this->dao->update(TABLE_PIPELINEEXEC)->set('logs')->eq($logs)->where('number')->eq($buildNumber)->exec();
        return $logs;
    }
}
