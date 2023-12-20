<?php
declare(strict_types=1);
/**
 * The control file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     mail
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class mail extends control
{
    /**
     * Construct.
     *
     * @access public
     * @return void
     */
    public function __construct(string $moduleName = '', string $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        /* Task #1967. check the function of fsocket. */
        if(isset($this->config->mail->mta) and !function_exists('fsockopen')) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->mail->nofsocket, 'locate' => array('back' => true))));
    }

    /**
     * The index page, goto edit page or detect page.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        if($this->config->mail->turnon)
        {
            if($this->config->mail->mta == 'smtp') $this->locate(inlink('edit'));
        }
        $this->view->title = $this->lang->mail->common . $this->lang->colon . $this->lang->mail->index;
        $this->display();
    }

    /**
     * Detect email config auto.
     *
     * @access public
     * @return void
     */
    public function detect()
    {
        if($_POST)
        {
            set_time_limit(30);
            $error = '';
            if($this->post->fromAddress == false) $error = sprintf($this->lang->error->notempty, $this->lang->mail->fromAddress);
            if(!validater::checkEmail($this->post->fromAddress)) $error .= '\n' . sprintf($this->lang->error->email, $this->lang->mail->fromAddress);

            if($error) return $this->sendError($error);

            $mailConfig = $this->mail->autoDetect($this->post->fromAddress);
            $mailConfig->fromAddress = $this->post->fromAddress;
            $mailConfig->domain      = common::getSysURL();
            $this->session->set('mailConfig', $mailConfig);

            $response['load'] = inlink('edit');
            return $this->sendSuccess($response);
        }

        $this->view->title       = $this->lang->mail->common . $this->lang->colon . $this->lang->mail->detect;
        $this->view->fromAddress = $this->session->mailConfig ? $this->session->mailConfig->fromAddress : '';

        $this->display();
    }

    /**
     * Edit the mail config.
     *
     * @access public
     * @return void
     */
    public function edit()
    {
        $mailConfig = $this->mailZen->getConfigForEdit();
        if(empty($mailConfig)) $this->locate(inlink('detect'));

        $this->view->title      = $this->lang->mail->common . $this->lang->colon . $this->lang->mail->edit;
        $this->view->mailExist  = $this->mail->mailExist();
        $this->view->mailConfig = $mailConfig;
        $this->view->openssl    = extension_loaded('openssl');
        $this->display();
    }

    /**
     * Save the email config.
     *
     * @access public
     * @return void
     */
    public function save()
    {
        if(!empty($_POST))
        {
            $mailConfig = $this->mailZen->getConfigForSave();

            if($mailConfig->turnon && empty($mailConfig->fromName)) return $this->sendError(array('fromName' => sprintf($this->lang->error->notempty, $this->lang->mail->fromName)));

            /* The mail need openssl and curl extension when secure is tls. */
            if($mailConfig->smtp->secure == 'tls')
            {
                if(!extension_loaded('openssl')) return $this->sendError($this->lang->mail->noOpenssl);
                if(!extension_loaded('curl'))    return $this->sendError($this->lang->mail->noCurl);
            }

            $this->session->set('mailConfig', $mailConfig->turnon);
            $this->loadModel('setting')->setItems('system.mail', $mailConfig);
            if(dao::isError()) return $this->sendError(dao::getError());

            if($mailConfig->turnon)
            {
                $mailExist = !empty($this->mail->mailExist());
                return $this->sendSuccess(array('callback' => "window.mailTips({$mailExist})"));
            }
        }
        return $this->sendSuccess(array('load' => inLink('detect')));
    }

    /**
     * Send test email.
     *
     * @access public
     * @return void
     */
    public function test()
    {
        if(!$this->config->mail->turnon) return $this->sendError($this->lang->mail->needConfigure);

        if($_POST)
        {
            /* The mail need openssl and curl extension when secure is tls. */
            if(isset($this->config->mail->async)) $this->config->mail->async = 0;
            if($this->config->mail->smtp->secure == 'tls')
            {
                if(!extension_loaded('openssl')) return $this->sendError($this->lang->mail->noOpenssl);
                if(!extension_loaded('curl'))    return $this->sendError($this->lang->mail->noCurl);
            }

            $this->mail->send($this->post->to, $this->lang->mail->testSubject, $this->lang->mail->testContent, '', true);
            if($this->mail->isError()) return $this->sendError(array('error' => implode("\n", $this->mail->getError())));

            return $this->sendSuccess(array('load' => inLink('test')));
        }

        $this->view->title = $this->lang->mail->common . $this->lang->colon . $this->lang->mail->test;
        $this->view->users = $this->mailZen->getHasMailUserPairs();
        $this->display();
    }

    /**
     * Reset the email config.
     *
     * @access public
     * @return void
     */
    public function reset()
    {
        $this->dao->delete('*')->from(TABLE_CONFIG)->where('module')->eq('mail')->exec();
        return $this->sendSuccess(array('load' => inlink('detect')));
    }

    /**
     * Async send mail.
     *
     * @access public
     * @return void
     */
    public function asyncSend()
    {
        /* Reload mail config. */
        $this->loadModel('common')->loadConfigFromDB();
        $this->app->loadConfig('mail');
        $queueList = $this->mail->getQueue('wait', 'id_asc');
        if(isset($this->config->mail->async))$this->config->mail->async = 0;

        foreach($queueList as $queue)
        {
            $log = $this->mailZen->sendQueues($queue, true);
            if($log) echo $log['message'];
        }

        /* Delete sended mail. */
        $this->mailZen->deleteSentQueue();

        echo "OK\n";
    }

    /**
     * Resend fail mails.
     *
     * @access public
     * @return void
     */
    public function resend(int $queueID)
    {
        $queue = $this->mail->getQueueById($queueID);
        if($queue and $queue->status == 'sended') return $this->sendSuccess(array('message' => $this->lang->mail->noticeResend, 'load' => true));

        if(isset($this->config->mail->async)) $this->config->mail->async = 0;
        $log = $this->mailZen->sendQueue($queue);
        if($log && $log['result'] == 'fail') return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.alert(" . json_encode(array('message' => array('html' => str_replace("\n", '<br />', $log['message'])))) . ")"));
        return $this->sendSuccess(array('result' => 'success', 'message' => $this->lang->mail->noticeResend, 'load' => true));
    }

    /**
     * Browse mail queue.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse(string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 100, int $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->mail->browse;

        $this->view->queueList = $this->mail->getQueue('all', $orderBy, $pager);
        $this->view->pager     = $pager;
        $this->view->orderBy   = $orderBy;
        $this->view->users     = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * Delete mail queue.
     *
     * @param  int    $id
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function delete(int $id)
    {
        $this->dao->delete()->from(TABLE_NOTIFY)->where('id')->eq($id)->exec();
        return $this->send(array('result' => 'success', 'callback' => 'loadCurrentPage()'));
    }

    /**
     * Batch delete mail queue.
     *
     * @access public
     * @return void
     */
    public function batchDelete()
    {
        $idList = implode('|', $this->post->mailIdList);
        if(empty($idList)) return $this->send(array('result' => 'fail', 'load' => true));

        /* Get deleted ID list from query string. */
        $this->dao->delete()->from(TABLE_NOTIFY)->where('id')->in($idList)->exec();
        return $this->send(array('result' => 'success', 'callback' => 'loadCurrentPage()'));
    }
}
