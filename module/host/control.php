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
 * @package     ops
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

        $hostList = $this->host->getList($browseType, $param, $orderBy, $pager);
        $rooms    = $this->loadModel('serverroom')->getPairs();
        $accounts = array(0 => '') + $this->loadModel('account')->getPairs();
        $groups   = array('', '') + $this->loadModel('tree')->getOptionMenu(0, 'host');

        /* Build the search form. */
        $actionURL = $this->createLink('host', 'browse', "browseType=bySearch&queryID=myQueryID");
        $this->config->host->search['actionURL'] = $actionURL;
        $this->config->host->search['queryID']   = $param;
        $this->config->host->search['onMenuBar'] = 'no';
        $this->config->host->search['params']['serverRoom']['values'] = $rooms;
        $this->config->host->search['params']['group']['values'] = $groups;
        $this->loadModel('search')->setSearchParams($this->config->host->search);

        $this->view->title      = $this->lang->host->common;
        $this->view->hostList   = $hostList;
        $this->view->rooms      = $rooms;
        $this->view->accounts   = $accounts;
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
    public function create($osName = 'linux')
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
     * @access public
     * @return void
     */
    public function edit($id, $osName = '')
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
        $this->view->host       = $this->host->getById($id);
        $this->view->osName     = $osName ? $osName : $this->view->host->osName;
        $this->view->rooms      = $this->loadModel('serverroom')->getPairs();
        $this->view->accounts   = $this->loadModel('account')->getPairs();
        $this->view->optionMenu = $this->loadModel('tree')->getOptionMenu(0, 'host');
        $this->display();
    }

    public function view($id)
    {
        $this->view->title      = $this->lang->host->view;
        $this->view->position[] = html::a($this->createLink('host', 'browse'), $this->lang->host->common);
        $this->view->position[] = $this->lang->host->view;

        $this->view->host       = $this->host->getById($id);
        $this->view->rooms      = $this->loadModel('serverroom')->getPairs();
        $this->view->actions    = $this->loadModel('action')->getList('host', $id);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->optionMenu = $this->loadModel('tree')->getOptionMenu(0, 'host');
        $this->display();
    }

    public function delete($id)
    {
        $this->dao->update(TABLE_HOST)->set('deleted')->eq(1)->where('id')->eq($id)->exec();
        $this->loadModel('action')->create('host', $id, 'deleted', '', $extra = ACTIONMODEL::CAN_UNDELETED);

        if(dao::isError())
        {
            $response['result']  = 'fail';
            $response['message'] = dao::getError(true);
        }
        else
        {
            $response['result']     = 'success';
            $response['message']    = '';
            $response['load']       = inLink('browse');
            $response['closeModal'] = true;

            if(helper::isAjaxRequest('modal')) return print(js::execute("loadCurrentPage();$('.modal').removeClass('show')"));
        }
        $this->send($response);
    }

    public function changeStatus($id, $status)
    {
        $hostStatus = $status == 'offline' ? 'online' : 'offline';
        $reasonKey  = $hostStatus . 'Reason';
        $reason     = $this->lang->host->{$reasonKey};
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $postData = fixer::input('post')->skipSpecial('reason')->get();
            if(empty($postData->reason))
            {
                dao::$errors['reason'][] = sprintf($this->lang->error->notempty, $reason);
                return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            $this->host->updateStatus($id, $hostStatus);

            $this->loadModel('action')->create('host', $id, $hostStatus, $postData->reason);
            return $this->send(array('result' => 'success', 'closeModal' => true, 'load' => true));
        }

        $this->view->title  = $this->lang->host->{$hostStatus};
        $this->view->reason = $reason;
        $this->display();
    }

    public function treemap($type = 'serverroom')
    {
        $this->view->title = $this->lang->host->featureBar['browse'][$type];
        $this->view->position[] = html::a(inlink('browse'), $this->lang->host->common);
        $this->view->position[] = $this->lang->host->featureBar['browse'][$type];

        /* Build the search form. */
        $actionURL = $this->createLink('host', 'browse', "browseType=bySearch&queryID=myQueryID");
        $this->config->host->search['actionURL'] = $actionURL;
        $this->config->host->search['queryID']   = 0;
        $this->config->host->search['onMenuBar'] = 'no';
        $this->config->host->search['params']['serverRoom']['values'] = $this->loadModel('serverroom')->getPairs();
        $this->config->host->search['params']['group']['values'] = $this->loadModel('tree')->getOptionMenu(0, 'host');
        $this->loadModel('search')->setSearchParams($this->config->host->search);

        $func = "get{$type}Treemap";
        $this->view->treemap = $this->host->$func();
        $this->view->type    = $type;
        $this->display();
    }

    public function ajaxGetByModule($moduleIdList, $defaultHosts = '')
    {
        $hosts = $this->host->getPairs($moduleIdList);
        die(html::select('hosts[]', $hosts, $defaultHosts, "class='form-control chosen' multiple"));
    }

    public function ajaxGetByService($serviceID)
    {
        $hosts = $serviceID ? $this->host->getPairsByService($serviceID) : $this->host->getPairs();
        $hosts = array(0 => '') + $hosts;
        die(html::select('hosts[]', $hosts, '', "class='form-control chosen' multiple"));
    }

    public function ajaxGetOSVersion($field)
    {
        die(html::select('osVersion', $this->lang->host->{$field . 'List'}, '', "class='form-control chosen'"));
    }
}
