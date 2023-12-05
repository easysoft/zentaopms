<?php
/**
 * The pipeline entry point of ZenTaoPMS.
 * It is only used by Zcli.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     pipeline
 * @version     1
 * @link        http://www.zentao.net
 */
class pipelinesEntry extends entry
{
    /**
     * GET method.
     *
     * @access public
     * @return string
     */
    public function get()
    {

        $pipeline = $this->param('pipeline', '');
        $repoUrl  = $this->param('repoUrl', '');
        if(empty($pipeline) and empty($repoUrl)) return $this->sendError(400, 'The parameter is incorrect!');

        $repo = $this->loadModel('repo')->getRepoByUrl($repoUrl, $pipeline);
        if(empty($repo['data'])) return $this->sendError(400, $repo['message']);

        return $this->send(200, array('status' => 'success', 'repo' => $repo['data']));
    }
}
