<?php
declare(strict_types=1);

use Symfony\Component\Yaml\Inline;

use function zin\isAjaxRequest;

/**
 * The control file of host of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jiangxiu Peng <pengjiangxiu@cnezsoft.com>
 * @package     host
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class host extends control
{
    /**
     * 主机列表页面。
     * Browse host.
     *
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse(string $browseType = 'all', int $param = 0, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->app->session->set('hostList', $this->app->getURI(true));
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $rooms  = $this->loadModel('serverroom')->getPairs();
        $groups = $this->loadModel('tree')->getOptionMenu(0, 'host');

        /* Build the search form. */
        $actionURL = $this->createLink('host', 'browse', "browseType=bySearch&queryID=myQueryID");
        $this->config->host->search['actionURL'] = $actionURL;
        $this->config->host->search['queryID']   = $param;
        $this->config->host->search['onMenuBar'] = 'no';
        $this->config->host->search['params']['serverRoom']['values'] = $rooms;
        $this->config->host->search['params']['group']['values'] = $groups;
        $this->loadModel('search')->setSearchParams($this->config->host->search);

        $this->view->title      = $this->lang->host->common;
        $this->view->hostList   = $this->host->getList($browseType, $param, $orderBy, $pager);
        $this->view->rooms      = $rooms;
        $this->view->accounts   = $this->loadModel('account')->getPairs();
        $this->view->param      = $param;
        $this->view->browseType = $browseType;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->moduleTree = $this->tree->getHostTreeMenu();
        $this->view->optionMenu = $groups;
        $this->display();
    }

    /**
     * 创建主机。
     * Create host.
     *
     * @param  string $osName
     * @access public
     * @return void
     */
    public function create(string $osName = 'linux')
    {
        if($_POST)
        {
            $formData = form::data($this->config->host->form->create)->add('createdBy', $this->app->user->account)->add('createdDate', helper::now())->get();
            $this->hostZen->checkFormData($formData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->host->create($formData);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title      = $this->lang->host->create;
        $this->view->osName     = $osName;
        $this->view->rooms      = $this->loadModel('serverroom')->getPairs();
        $this->view->accounts   = $this->loadModel('account')->getPairs();
        $this->view->optionMenu = $this->loadModel('tree')->getOptionMenu(0, 'host');
        $this->display();
    }

    /**
     * 编辑主机。
     * Edit host.
     *
     * @param  int    $id
     * @param  string $osName
     * @access public
     * @return void
     */
    public function edit(int $id, string $osName = '')
    {
        if($_POST)
        {
            $formData = form::data($this->config->host->form->edit)->add('id', $id)->add('editedBy', $this->app->user->account)->add('editedDate', helper::now())->get();
            $this->hostZen->checkFormData($formData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->host->update($formData);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('browse'), 'closeModal' => true));
        }

        $this->view->title      = $this->lang->host->edit;
        $this->view->host       = $this->host->fetchByID($id);
        $this->view->osName     = $osName ? $osName : $this->view->host->osName;
        $this->view->rooms      = $this->loadModel('serverroom')->getPairs();
        $this->view->accounts   = $this->loadModel('account')->getPairs();
        $this->view->optionMenu = $this->loadModel('tree')->getOptionMenu(0, 'host');
        $this->display();
    }

    /**
     * 主机详情页面。
     * View a host.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function view(int $id)
    {
        $this->view->title      = $this->lang->host->view;
        $this->view->host       = $this->host->fetchByID($id);
        $this->view->rooms      = $this->loadModel('serverroom')->getPairs();
        $this->view->optionMenu = $this->loadModel('tree')->getOptionMenu(0, 'host');
        $this->view->actions    = $this->loadModel('action')->getList('host', $id);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * 删除主机。
     * Delete a host.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function delete(int $id)
    {
        $this->host->delete(TABLE_HOST, $id);

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('browse'), 'closeModal' => true));
    }

    /**
     * 上架或者下架主机。
     * Change host status.
     *
     * @param  int    $id
     * @param  string $status offline|online
     * @access public
     * @return void
     */
    public function changeStatus(int $id, string $status = 'online')
    {
        $this->lang->host->reason = zget($this->lang->host, $status . 'Reason', $this->lang->host->reason);
        if($_POST)
        {
            $formData = form::data($this->config->host->form->changeStatus)->add('id', $id)->add('status', $status)->get();

            $this->host->updateStatus($formData);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
        }

        $this->view->title = zget($this->lang->host, $status);
        $this->display();
    }

    /**
     * 展示物理拓扑图和分组拓扑图。
     * Show host treemap.
     *
     * @param  string $type
     * @access public
     * @return void
     */
    public function treemap(string $type = 'serverroom')
    {
        $func = 'get' . ucfirst($type) . 'Treemap';
        $this->view->title   = $this->lang->host->featureBar['browse'][$type];
        $this->view->treemap = $this->host->$func();
        $this->view->type    = $type;
        $this->display();
    }
}
