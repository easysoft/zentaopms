<?php
/**
 * The repo entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      xiawenlong <xiawenlong@cnezsoft.com>
 * @package     repo
 * @version     1
 * @link        https://www.zentao.net
 */
class gitfoxWebhookEntry extends baseEntry
{

    /**
     * 获取请求头值，头名不区分大小写。
     *
     * @param  array  $headers
     * @param  string $name
     * @access private
     * @return string
     */
    private function getHeaderValue(array $headers, string $name): string
    {
        foreach($headers as $key => $value)
        {
            if(strcasecmp($key, $name) === 0) return (string)$value;
        }

        return '';
    }

    /**
     * Repo webhook.
     *
     * @access public
     * @return string
     */
    public function post()
    {
        $headers = getallheaders(); /* Fetch all HTTP request headers. */
        $event   = $this->getHeaderValue($headers, 'X-Trigger');
        if(empty($event)) return;

        $repoID = $this->param('repoID');
        if(empty($repoID)) return;

        $this->app->user = new stdclass();
        $this->app->user->account = '';
        $this->app->user->admin   = false;
        $this->app->user->rights['rights'] = array();
        $this->app->user->rights['acls']   = array();

        $repo = $this->loadModel('repo')->fetchByID($repoID);
        if(empty($repo)) return;

        $token         = $this->getHeaderValue($headers, 'X-Gitfox-Token');
        $expectedToken = isset($repo->gitUID) ? $repo->gitUID : '';
        if(empty($expectedToken) || (string)$token !== (string)$expectedToken) return $this->sendError(401, 'Unauthorized');

        $server = $this->loadModel('gitfox')->getServer();
        if(empty($server)) return;

        $repo->apiPath  = sprintf($this->config->repo->gitfox->apiPath, $server->url, $repo->id);
        $repo->client   = $server->url;
        $repo->password = $server->token;

        $this->loadController('user', 'login');
        $this->repo->handleWebhook($event, $this->requestBody, $repo);
    }
}
