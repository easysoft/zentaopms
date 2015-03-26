<?php
/**
 * The control file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     mail
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class mail extends control
{
    /**
     * Construct.
     * 
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        /* Task #1967. check the function of fsocket. */
        if(!function_exists('fsockopen'))
        {
            echo js::alert($this->lang->mail->nofsocket);
            die(js::locate('back'));
        }
    }

    /**
     * The index page, goto edit page or detect page.
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        if($this->config->mail->turnon) $this->locate(inlink('edit'));
        $this->locate(inlink('detect'));
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
            $error = '';
            if($this->post->fromAddress == false) $error = sprintf($this->lang->error->notempty, $this->lang->mail->fromAddress);
            if(!validater::checkEmail($this->post->fromAddress)) $error .= '\n' . sprintf($this->lang->error->email, $this->lang->mail->fromAddress);

            if($error) die(js::alert($error));

            $mailConfig = $this->mail->autoDetect($this->post->fromAddress);
            $mailConfig->fromAddress = $this->post->fromAddress;
            $this->session->set('mailConfig',  $mailConfig);

            die(js::locate(inlink('edit'), 'parent'));
        }

        $this->view->title      = $this->lang->mail->common . $this->lang->colon . $this->lang->mail->detect;
        $this->view->position[] = html::a(inlink('index'), $this->lang->mail->common);
        $this->view->position[] = $this->lang->mail->detect;

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
        if($this->config->mail->turnon)
        {
            $mailConfig = $this->config->mail->smtp;
            $mailConfig->fromAddress = $this->config->mail->fromAddress;
            $mailConfig->fromName    = $this->config->mail->fromName;
            $mailConfig->charset     = zget($mailConfig, 'charset', 'utf-8');
        }
        elseif($this->session->mailConfig)
        {
            $mailConfig = $this->session->mailConfig;
        }
        else
        {
            $this->locate(inlink('detect'));
        }

        $this->view->title      = $this->lang->mail->common . $this->lang->colon . $this->lang->mail->edit;
        $this->view->position[] = html::a(inlink('index'), $this->lang->mail->common);
        $this->view->position[] = $this->lang->mail->edit;

        $this->view->mailExist   = $this->mail->mailExist();
        $this->view->mailConfig  = $mailConfig;
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
            $mailConfig = new stdclass();
            $mailConfig->smtp = new stdclass();

            $mailConfig->turnon         = $this->post->turnon;
            $mailConfig->mta            = 'smtp';
            $mailConfig->async          = $this->post->async;
            $mailConfig->fromAddress    = trim($this->post->fromAddress); 
            $mailConfig->fromName       = trim($this->post->fromName);
            $mailConfig->smtp->host     = trim($this->post->host);
            $mailConfig->smtp->port     = trim($this->post->port);
            $mailConfig->smtp->auth     = $this->post->auth;
            $mailConfig->smtp->username = trim($this->post->username);
            $mailConfig->smtp->password = $this->post->password;
            $mailConfig->smtp->secure   = $this->post->secure;
            $mailConfig->smtp->debug    = $this->post->debug;
            $mailConfig->smtp->charset  = $this->post->charset;

            /* The mail need openssl and curl extension when secure is tls. */
            if($mailConfig->smtp->secure == 'tls')
            {
                if(!extension_loaded('openssl'))
                {
                    echo js::alert($this->lang->mail->noOpenssl);
                    die(js::locate('back'));
                }
                if(!extension_loaded('curl'))
                {
                    echo js::alert($this->lang->mail->noCurl);
                    die(js::locate('back'));
                }
            }

            $this->loadModel('setting')->setItems('system.mail', $mailConfig);
            if(dao::isError()) die(js::error(dao::getError()));

            $this->session->set('mailConfig', '');

            $this->view->title      = $this->lang->mail->common . $this->lang->colon . $this->lang->mail->save;
            $this->view->position[] = html::a(inlink('index'), $this->lang->mail->common);
            $this->view->position[] = $this->lang->mail->save;

            $this->view->mailExist   = $this->mail->mailExist();
            $this->display();
        }
    }

    /**
     * Send test email.
     * 
     * @access public
     * @return void
     */
    public function test()
    {
        if(!$this->config->mail->turnon)
        {
            die(js::alert($this->lang->mail->needConfigure) . js::locate('back'));
        }

        if($_POST)
        {
            /* The mail need openssl and curl extension when secure is tls. */
            if(isset($this->config->mail->async))$this->config->mail->async = 0;
            if($this->config->mail->smtp->secure == 'tls')
            {
                if(!extension_loaded('openssl'))
                {
                    $this->view->error = array($this->lang->mail->noOpenssl);
                    die($this->display());
                }
                if(!extension_loaded('curl'))
                {
                    $this->view->error = array($this->lang->mail->noCurl);
                    die($this->display());
                }
            }

            $this->mail->send($this->post->to, $this->lang->mail->subject, $this->lang->mail->content, "", true);
            if($this->mail->isError())
            {
                $this->view->error = $this->mail->getError();
                die($this->display());
            }
            die(js::alert($this->lang->mail->successSended) . js::locate(inlink('test'), 'parent'));
        }

        $this->view->title      = $this->lang->mail->common . $this->lang->colon . $this->lang->mail->test;
        $this->view->position[] = html::a(inlink('index'), $this->lang->mail->common);
        $this->view->position[] = $this->lang->mail->test;
        $this->view->users      = $this->dao->select('account,  CONCAT(realname, " ", email) AS email' )->from(TABLE_USER)->where('email')->ne('')->orderBy('account')->fetchPairs();
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
        $this->locate(inlink('detect'));
    }

    /**
     * Async send mail.
     * 
     * @access public
     * @return void
     */
    public function asyncSend()
    {
        $queueList = $this->mail->getQueue('wait', 'id_asc');
        $now       = helper::now();
        if(isset($this->config->mail->async))$this->config->mail->async = 0;
        $log = '';
        foreach($queueList as $queue)
        {
            $this->mail->send($queue->toList, $queue->subject, $queue->body, $queue->ccList);

            $data = new stdclass();
            $data->sendTime = $now;
            $data->status   = 'send';
            if($this->mail->isError())
            {
                $data->status = 'fail';
                $data->failReason = join("\n", $this->mail->getError());
            }
            $this->dao->update(TABLE_MAILQUEUE)->data($data)->where('id')->eq($queue->id)->exec();

            $log .= "Send #$queue->id  result is $data->status\n";
            if($data->status == 'fail') $log .= "reason is $data->failReason\n";
        }
        echo $log;
        echo "OK\n";
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
    public function browse($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->mail->browse;
        $this->view->position[] = html::a(inlink('edit'), $this->lang->mail->common);
        $this->view->position[] = $this->lang->mail->browse;

        $this->view->queueList = $this->mail->getQueue(null, $orderBy, $pager);
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
    public function delete($id, $confirm = 'no')
    {
        if($confirm == 'no') die(js::confirm($this->lang->mail->confirmDelete, inlink('delete', "id=$id&confirm=yes")));

        $this->dao->delete()->from(TABLE_MAILQUEUE)->where('id')->eq($id)->exec();
        die(js::reload('parent'));
    }

    /**
     * Batch delete mail queue.
     * 
     * @param  string $confirm 
     * @access public
     * @return void
     */
    public function batchDelete($confirm = 'no')
    {
        if($confirm == 'no')
        {
            if(empty($_POST)) die(js::reload('parent'));
            $idList = join('|', $this->post->mailIDList);
            die(js::confirm($this->lang->mail->confirmDelete, inlink('batchDelete', "confirm=yes") . ($this->config->requestType == 'GET' ? '&' : '?') . "idList=$idList"));
        }
        $idList = array();
        if(isset($_GET['idList'])) $idList = explode('|', $_GET['idList']);

        if($idList) $this->dao->delete()->from(TABLE_MAILQUEUE)->where('id')->in($idList)->exec();
        die(js::reload('parent'));
    }
}
