<?php
declare(strict_types=1);
/**
 * The control file of webhook module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@cnezsoft.com>
 * @package     webhook
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class webhook extends control
{
    /**
     * Construct
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return void
     */
    public function __construct(string $moduleName = '', string $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->loadModel('message');
    }

    /**
     * Webhook 列表。
     * Browse webhooks.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse(string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Unset selectedDepts cookie. */
        helper::setcookie('selectedDepts', '', 0, $this->config->webRoot, '', $this->config->cookieSecure, true);

        $this->view->title    = $this->lang->webhook->api . $this->lang->colon . $this->lang->webhook->list;
        $this->view->webhooks = $this->webhook->getList($orderBy, $pager);
        $this->view->orderBy  = $orderBy;
        $this->view->pager    = $pager;
        $this->display();
    }

    /**
     * 创建 Webhook。
     * Create a webhook.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $webhook = form::data($this->config->webhook->form->create)->get();
            $this->webhook->create($webhook);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $webhookID));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('browse')));
        }

        $this->app->loadLang('action');
        $this->view->title      = $this->lang->webhook->api . $this->lang->colon . $this->lang->webhook->create;
        $this->view->products   = $this->loadModel('product')->getPairs();
        $this->view->executions = $this->loadModel('execution')->getPairs();
        $this->display();
    }

    /**
     * 编辑 Webhook。
     * Edit a webhook.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function edit(int $id)
    {
        if($_POST)
        {
            $webhook = form::data($this->config->webhook->form->edit)->get();
            $this->webhook->update($id, $webhook);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('browse')));
        }

        $webhook = $this->webhook->getByID($id);
        $this->app->loadLang('action');

        $this->view->title      = $this->lang->webhook->edit . $this->lang->colon . $webhook->name;
        $this->view->products   = $this->loadModel('product')->getPairs();
        $this->view->executions = $this->loadModel('execution')->getPairs();
        $this->view->webhook    = $webhook;

        $this->display();
    }

    /**
     * 删除 Webhook。
     * Delete a webhook.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function delete(int $id)
    {
        $this->webhook->delete(TABLE_WEBHOOK, $id);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->sendSuccess(array('load' => true));
    }

    /**
     * Browse logs of a webhook.
     *
     * @param  int    $id
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function log($id, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save session. */
        $uri   = $this->app->getURI(true);
        $this->session->set('productList',     $uri, 'product');
        $this->session->set('productPlanList', $uri, 'product');
        $this->session->set('releaseList',     $uri, 'product');
        $this->session->set('storyList',       $uri, 'product');
        $this->session->set('executionList',   $uri, 'execution');
        $this->session->set('taskList',        $uri, 'execution');
        $this->session->set('buildList',       $uri, 'execution');
        $this->session->set('bugList',         $uri, 'qa');
        $this->session->set('caseList',        $uri, 'qa');
        $this->session->set('testtaskList',    $uri, 'qa');
        $this->session->set('todoList',        $uri, 'my');
        $this->session->set('docList',         $uri, 'doc');

        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $webhook = $this->webhook->getByID($id);
        $this->view->title      = $this->lang->webhook->log . $this->lang->colon . $webhook->name;
        $this->view->logs       = $this->webhook->getLogList($id, $orderBy, $pager);
        $this->view->webhook    = $webhook;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->display();
    }

    /**
     * 绑定钉钉、企业微信、飞书的用户。
     * Bind dingtalk, wechat, feishu user.
     *
     * @param  int    $id
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function bind(int $id, int $recTotal = 0, int $recPerPage = 15, int $pageID = 1)
    {
        if($_POST)
        {
            $this->webhook->bind($id);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
        }

        $webhook = $this->webhook->getById($id);
        if(!in_array($webhook->type, array('dinguser', 'wechatuser', 'feishuuser'))) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->webhook->note->bind, 'locate' => $this->createLink('webhook', 'browse'))));
        $webhook->secret = json_decode($webhook->secret);

        /* Get selected depts. */
        if($this->get->selectedDepts)
        {
            helper::setcookie('selectedDepts', $this->get->selectedDepts, 0, $this->config->webRoot, '', $this->config->cookieSecure, true);
            $_COOKIE['selectedDepts'] = $this->get->selectedDepts;
        }

        $response = $this->webhookZen->getResponse($webhook);

        if($response['result'] == 'fail')
        {
            if($response['message'] == 'nodept') return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->webhook->error->noDept, 'locate' => $this->createLink('webhook', 'chooseDept', "id={$id}"))));
            return $this->send(array('result' => 'fail', 'load' => array('alert' => $response['message'], 'locate' => $this->createLink('webhook', 'browse'))));
        }

        $oauthUsers = $response['data'];

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title       = $this->lang->webhook->bind;
        $this->view->webhook     = $webhook;
        $this->view->oauthUsers  = $oauthUsers;
        $this->view->useridPairs = array_flip($oauthUsers);
        $this->view->users       = $this->loadModel('user')->getByQuery('inside', '', $pager);
        $this->view->pager       = $pager;
        $this->view->bindedUsers = $this->webhook->getBoundUsers($id);
        $this->display();
    }

    /**
     * 选择部门，用于钉钉、企业微信、飞书的用户绑定。
     * Choose depts for dingtalk, wechat, feishu user bind.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function chooseDept(int $id)
    {
        $webhook = $this->webhook->getById($id);
        if(!in_array($webhook->type, array('dinguser', 'wechatuser', 'feishuuser'))) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->webhook->note->bind, 'locate' => $this->createLink('webhook', 'browse'))));

        $webhook->secret = json_decode($webhook->secret);

        if($webhook->type == 'dinguser')
        {
            $this->app->loadClass('dingapi', true);
            $dingapi  = new dingapi($webhook->secret->appKey, $webhook->secret->appSecret, $webhook->secret->agentId);
            $response = $dingapi->getDeptTree();
        }

        if($webhook->type == 'feishuuser') $response = array('result' => 'success', 'data' => array());

        if($response['result'] == 'fail') return $this->send(array('result' => 'fail', 'load' => array('alert' => $response['message'], 'locate' => $this->createLink('webhook', 'browse'))));

        if($response['result'] == 'selected')
        {
            $locateLink  = $this->createLink('webhook', 'bind', "id={$id}");
            $locateLink .= strpos($locateLink, '?') !== false ? '&' : '?';
            $locateLink .= 'selectedDepts=' . join(',', $response['data']);
            return $this->send(array('result' => 'success', 'load' => $locateLink));
        }

        $this->view->title       = $this->lang->webhook->chooseDept;
        $this->view->webhookType = $webhook->type;
        $this->view->deptTree    = $response['data'];
        $this->view->webhookID   = $id;
        $this->display();
    }

    public function ajaxGetFeishuDeptList($webhookID)
    {
        $webhook = $this->webhook->getById($webhookID);
        $webhook->secret = json_decode($webhook->secret);

        if($_POST)
        {
            $this->app->loadClass('feishuapi', true);
            $feishuApi    = new feishuapi($webhook->secret->appId, $webhook->secret->appSecret);
            $departmentID = $_POST['departmentID'] ? $_POST['departmentID'] : '';
            $depts        = $feishuApi->getChildDeptTree($departmentID);
            echo json_encode($depts, true);
        }
        else
        {
            $this->app->loadClass('feishuapi', true);
            $feishuApi = new feishuapi($webhook->secret->appId, $webhook->secret->appSecret);
            $depts  = $feishuApi->getDeptTree();
            echo json_encode($depts, true);
        }
    }

    /**
     * Send data by async.
     *
     * @access public
     * @return void
     */
    public function asyncSend()
    {
        $webhooks = $this->webhook->getList($orderBy = 'id_desc', $pager = null, $decode = false);
        if(empty($webhooks))
        {
            echo "NO WEBHOOK EXIST.\n";
            return false;
        }

        $dataList = $this->webhook->getDataList();
        if(empty($dataList))
        {
            echo "OK\n";
            return true;
        }

        $this->webhook->setSentStatus(array_keys($dataList), 'senting');

        $now  = helper::now();
        $diff = 0;
        foreach($dataList as $data)
        {
            $webhook = zget($webhooks, $data->objectID, '');
            if($webhook)
            {
                /* if connect time is out then ignore it.*/
                if($diff < 29)
                {
                    $time = time();
                    $result = $this->webhook->fetchHook($webhook, $data->data, $data->action);
                    $diff = time() - $time;
                }
                $this->webhook->saveLog($webhook, $data->action, $data->data, $result);
            }

            $this->webhook->setSentStatus($data->id, 'sended', $now);
        }

        $this->dao->delete()->from(TABLE_NOTIFY)->where('status')->eq('sended')->exec();

        echo "OK\n";
        return true;
    }
}
