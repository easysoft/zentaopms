<?php
/**
 * The repos entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class reposEntry extends entry
{
    /**
     * GET method.
     *
     * @access public
     * @return string
     */
    public function get()
    {
        $control = $this->loadController('repo', 'maintain');
        $repoUrl = $this->param('repoUrl', '');

        if(empty($repoUrl))
        {
            $control->maintain(0, $this->param('order', 'id_desc'), 0, $this->param('limit', 100), $this->param('page', 1));
            /* Response */
            $data = $this->getData();
        }
        else
        {
            $data = (object)$this->loadModel('repo')->getRepoListByUrl($repoUrl);
        }

        if(isset($data->status) and $data->status == 'success')
        {
            if(empty($repoUrl))
            {
                $result = array();
                $pager  = $data->data->pager;
                $repos  = $data->data->repoList;
                foreach($repos as $repo) $result[] = $this->format($repo, 'deleted:bool,lastSync:datetime,synced:bool,product:idList');

                return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'repos' => $result));
            }
            else
            {
                return $this->send(200, array('repos' => $data->repos));
            }
        }

        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);
        return $this->sendError(400, 'error');
    }
}
