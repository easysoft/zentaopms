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
     * 获取gitlab根据id。
     * Get a gitlab by id.
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
     * 获取gitlab api 基础url 根据gitlab id。
     * Get gitlab api base url by gitlab id.
     *
     * @param  int    $gitlabID
     * @param  bool   $sudo
     * @access public
     * @return string
     */
    public function getApiRoot(int $gitlabID, bool $sudo = true): string
    {
        $gitlab = $this->getByID($gitlabID);
        if(!$gitlab || $gitlab->type != 'gitlab') return '';

        $sudoParam = '';
        if($sudo == true && !$this->app->user->admin)
        {
            $openID = $this->loadModel('pipeline')->getOpenIdByAccount($gitlabID, 'gitlab', $this->app->user->account);
            if($openID) $sudoParam = "&sudo={$openID}";
        }

        return rtrim($gitlab->url, '/') . '/api/v4%s' . "?private_token={$gitlab->token}" . $sudoParam;
    }

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
        if(is_numeric($host)) $host = $this->getApiRoot($host);
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
        $callbackURL = $systemURL . '/api.php/v1/gitlab/webhook?pipeline=' . $pipelineID;

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
        $hookList = json_decode(commonModel::http(sprintf($apiRoot, "/projects/{$projectID}/hooks")));
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
}
