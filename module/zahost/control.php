<?php
/**
 * The control file of zahost of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     zahost
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class zahost extends control
{
    /**
     * View host.
     *
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($browseType = 'all', $param = 0, $orderBy = 't1.id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $browseType = strtolower($browseType);
        $param      = (int)$param;

        $this->app->session->set('zahostList', $this->app->getURI(true));
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $hostList = $this->zahost->getList($browseType, $param, $orderBy, $pager);

        /* Build the search form. */
        $actionURL = $this->createLink('zahost', 'browse', "browseType=bySearch&param=myQueryID");
        $this->config->zahost->search['actionURL'] = $actionURL;
        $this->config->zahost->search['queryID']   = $param;
        $this->config->zahost->search['onMenuBar'] = 'no';
        $this->loadModel('search')->setSearchParams($this->config->zahost->search);

        $this->view->title      = $this->lang->zahost->common;
        $this->view->hostList   = $hostList;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter,noempty,noclosed');
        $this->view->pager      = $pager;
        $this->view->param      = $param;
        $this->view->orderBy    = $orderBy;
        $this->view->browseType = $browseType;

        $this->display();
    }

    /**
     * Create host.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $this->zahost->create();
            if(dao::isError())
            {
                return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title = $this->lang->zahost->create;
        $this->display();
    }

    /**
     * Create template.
     *
     * @access public
     * @return void
     */
    public function createTemplate()
    {
        if($_POST)
        {
            $this->zahost->createTemplate();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inLink('browse')));
        }

        $this->view->title = $this->lang->zahost->createTemplate;
        $this->display();
    }

    /*
     * Browse VM template.
     *
     * @param  int      $hostID
     * @param  string   $browseType
     * @param  string   $param
     * @param  string   $orderBy
     * @param  int      $recTotal
     * @param  int      $recPerPage
     * @param  int      $pageID
     * @access public
     * @return void
     */
    public function browseTemplate($hostID, $browseType = 'all', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $browseType = strtolower($browseType);
        $this->app->loadClass('pager', true);

        $queryID      = ($browseType == 'bysearch')  ? (int)$param : 0;
        $pager        = pager::init($recTotal, $recPerPage, $pageID);
        $templateList = $this->zahost->getVMTemplateList($hostID, $browseType, $queryID, $orderBy, $pager);

        /* Build the search form. */
        //$actionURL = $this->createLink('zahost', 'browseVM', "browseType=bySearch&queryID=myQueryID");
        //$this->config->zaTemplate->search['actionURL'] = $actionURL;
        //$this->config->zaTemplate->search['queryID']   = $queryID;
        //$this->config->zaTemplate->search['onMenuBar'] = 'no';
        //$this->loadModel('search')->setSearchParams($this->config->zaTemplate->search);

        $this->view->title        = $this->lang->zahost->vmTemplate->common;
        $this->view->users        = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $this->view->templateList = $templateList;
        $this->view->hostID       = $hostID;
        $this->view->pager        = $pager;
        $this->view->param        = $param;
        $this->view->orderBy      = $orderBy;
        $this->view->browseType   = $browseType;

        $this->display();
    }
}
