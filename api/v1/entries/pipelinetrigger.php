<?php
/**
 * The pipeline trigger entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mazhiyuan <mazhiyuan@chandao.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class pipelinetriggerEntry extends baseEntry
{
    /**
     * GET method.
     *
     * @access public
     * @return string
     */
    public function get()
    {
        $header = getallheaders();
        $token  = isset($header['Authorization']) ? $header['Authorization'] : '';
        $entry  = $this->loadModel('entry')->getByCode('gitfox');

        if(empty($token) || empty($entry->key) || $token != $entry->key)
        {
            return $this->sendError(401, 'Unauthorized');
        }

        if(!isset($this->app->user))
        {
            $this->app->user = new stdclass();
            $this->app->user->account = 'admin';
            $this->app->user->rights  = array(
                'rights' => array(),
                'acls'   => array()
            );
            $this->app->user->groups = array();
            $this->app->user->view   = array('products' => array(), 'projects' => array());
            $this->app->user->admin  = false;
        }

        $pipelineID = $this->param('id');
        $pipeline   = $this->loadModel('pipeline')->getByID($pipelineID);
        if(!$pipeline) return $this->sendError(404, 'Pipeline not found');

        if($pipeline->engine == 'gitlab')
        {
            $result = $this->pipeline->execGitlabPipeline($pipeline);
            if($result) return $this->send(200, 'Pipeline triggered successfully');
            return $this->sendError(400, 'Pipeline trigger failed');
        }

        return $this->sendError(400, 'Unsupported pipeline engine');
    }
}
