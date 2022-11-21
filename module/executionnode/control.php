<?php
/**
 * The control file of execution node of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      liyuchun <liyuchun@easycorp.ltd>
 * @package     qa
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class executionnode extends control
{

    /**
     * Browse execution node page.
     *
     * @param  string   $browseType
     * @param  string   $param
     * @param  string   $orderBy
     * @param  int      $recTotal
     * @param  int      $recPerPage
     * @param  int      $pageID
     * @access public
     * @return void
     */
    public function browse($browseType = 'all', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $browseType = strtolower($browseType);

        $this->app->loadClass('pager', $static = true);


        $queryID  = ($browseType == 'bysearch')  ? (int)$param : 0;
        $pager    = pager::init($recTotal, $recPerPage, $pageID);
        $nodeList = $this->executionnode->getListByQuery($browseType, $queryID, $orderBy, $pager);
        $hosts    = $this->loadModel('zahost')->getPairs('host');

        /* Build the search form. */
        $actionURL = $this->createLink('executionnode', 'browse', "browseType=bySearch&queryID=myQueryID");
        $this->config->executionnode->search['actionURL'] = $actionURL;
        $this->config->executionnode->search['queryID']   = $queryID;
        $this->config->executionnode->search['onMenuBar'] = 'no';
        $this->config->executionnode->search['params']['hostID']['values'] = array('' => '') + $hosts;
        $this->loadModel('search')->setSearchParams($this->config->executionnode->search);

        $this->view->title      = $this->lang->executionnode->common;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $this->view->nodeList   = $nodeList;
        $this->view->pager      = $pager;
        $this->view->param      = $param;
        $this->view->orderBy    = $orderBy;
        $this->view->browseType = $browseType;

        $this->display();
    }

    /**
     * Create node page.
     *
     * @param  int    $imageID
     * @access public
     * @return void
     */
    public function create()
    {
        if(!empty($_POST))
        {
            $this->executionnode->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title     = $this->lang->executionnode->create;
        $this->view->hostPairs = array('' => '') + $this->loadModel('zahost')->getPairs('host');

        return $this->display();
    }

    /**
     * View VM.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function view($id)
    {
        $this->view->title   = $this->lang->executionnode->view;
        $this->view->node    = $this->executionnode->getVMByID($id);
        $this->view->actions = $this->loadModel('action')->getList('executionnode', $id);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * Suspend VM.
     *
     * @param  int    $nodeID
     * @access public
     * @return void
     */
    public function suspend($nodeID)
    {
        $this->handleVM($nodeID, 'suspend');
    }

    /**
     * Reboot VM.
     *
     * @param  int    $nodeID
     * @access public
     * @return void
     */
    public function reboot($nodeID)
    {
        $this->handleVM($nodeID, 'reboot');
    }

    /**
     * Resume VM.
     *
     * @param  int    $nodeID
     * @access public
     * @return void
     */
    public function resume($nodeID)
    {
        $this->handleVM($nodeID, 'resume');
    }

    /**
     * Boot node.
     *
     * @param  int    $nodeID
     * @param  string $type
     * @return void
     */
    public function handleVM($nodeID, $type)
    {
        $error = $this->executionnode->handleVM($nodeID, $type);

        if($error)
        {
            return print(js::error($error));
        }
        else
        {
            return print(js::alert($this->lang->executionnode->actionSuccess) . js::reload('parent'));
        }
    }

    /**
     * Desctroy node.
     *
     * @param  int  $nodeID
     * @return void
     */
    public function destroy($nodeID)
    {
        $error = $this->executionnode->destroy($nodeID);

        if($error)
        {
            return print(js::alert($error));
        }
        else
        {
            if(isonlybody()) return print(js::alert($this->lang->executionnode->actionSuccess) . js::reload('parent.parent'));
            return print(js::alert($this->lang->executionnode->actionSuccess) . js::reload('parent'));
        }
    }

    /**
     * Ajax get template pairs by api.
     *
     * @param  int    $hostID
     * @access public
     * @return void
     */
    public function ajaxGetImages($hostID)
    {
        $templatePairs = $this->loadModel('zahost')->getImagePairs($hostID);

        return print(html::select('image', $templatePairs, '', "class='form-control chosen'"));
    }

    /**
     * Ajax get template info.
     *
     * @param  int    $imageID
     * @access public
     * @return void
     */
    public function ajaxGetImage($imageID)
    {
        $template = $this->loadModel('zahost')->getImageByID($imageID);
        return print(json_encode($template));
    }
}
