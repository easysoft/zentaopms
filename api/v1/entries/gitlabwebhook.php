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
class gitlabWebhookEntry extends baseEntry
{

    /**
     * Repo webhook.
     *
     * @access public
     * @return string
     */
    public function post()
    {
        $headers = getallheaders(); /* Fetch all HTTP request headers. */
        $event   = isset($headers['X-Gitlab-Event']) ? $headers['X-Gitlab-Event'] : '';
        $token   = isset($headers['X-Gitlab-Token']) ? $headers['X-Gitlab-Token'] : '';
        if(empty($event) || empty($token)) return;

        $repoID = $this->param('repoID');
        if(empty($repoID)) return;

        $this->app->user = new stdclass();
        $this->app->user->account = '';
        $this->app->user->admin   = false;
        $repo = $this->loadModel('repo')->getByID($repoID);
        if(empty($repo)) return;


        $this->repo->handleWebhook($event, $this->requestBody, $repo);
    }
}
