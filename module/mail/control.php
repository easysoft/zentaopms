<?php
/**
 * The control file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
        if(isset($this->config->mail->mta) and $this->config->mail->mta != 'sendcloud' and !function_exists('fsockopen'))
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
        if($this->config->mail->turnon)
        {
            if($this->config->mail->mta == 'sendcloud') $this->locate(inlink('sendcloud'));
            if($this->config->mail->mta == 'ztcloud') $this->locate(inlink('ztcloud'));
            if($this->config->mail->mta == 'smtp') $this->locate(inlink('edit'));
        }
        $this->view->title = $this->lang->mail->common . $this->lang->colon . $this->lang->mail->index;
        $this->view->position[] = html::a(inlink('index'), $this->lang->mail->common);
        $this->view->position[] = $this->lang->mail->index;
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

            if($error) die(js::alert($error));

            echo "<script>setTimeout(function(){parent.location.href='" . inlink('edit') . "'}, 10000)</script>";
            $mailConfig = $this->mail->autoDetect($this->post->fromAddress);
            $mailConfig->fromAddress = $this->post->fromAddress;
            $mailConfig->domain      = common::getSysURL();
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

        $mailConfig->domain = isset($this->config->mail->domain) ? $this->config->mail->domain : common::getSysURL();

        $this->view->title      = $this->lang->mail->common . $this->lang->colon . $this->lang->mail->edit;
        $this->view->position[] = html::a(inlink('index'), $this->lang->mail->common);
        $this->view->position[] = $this->lang->mail->edit;

        $this->view->mailExist   = $this->mail->mailExist();
        $this->view->mailConfig  = $mailConfig;
        $this->view->openssl     = extension_loaded('openssl');
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
            $mailConfig->domain         = trim($this->post->domain);
            $mailConfig->smtp->host     = trim($this->post->host);
            $mailConfig->smtp->port     = trim($this->post->port);
            $mailConfig->smtp->auth     = $this->post->auth;
            $mailConfig->smtp->username = trim($this->post->username);
            $mailConfig->smtp->password = $this->post->password;
            $mailConfig->smtp->secure   = $this->post->secure;
            $mailConfig->smtp->debug    = $this->post->debug;
            $mailConfig->smtp->charset  = $this->post->charset;

            if(empty($mailConfig->fromName))
            {
                echo js::alert(sprintf($this->lang->error->notempty, $this->lang->mail->fromName));
                die(js::locate($this->server->http_referer));
            }

            /* The mail need openssl and curl extension when secure is tls. */
            if($mailConfig->smtp->secure == 'tls')
            {
                if(!extension_loaded('openssl'))
                {
                    echo js::alert($this->lang->mail->noOpenssl);
                    die(js::locate($this->server->http_referer));
                }
                if(!extension_loaded('curl'))
                {
                    echo js::alert($this->lang->mail->noCurl);
                    die(js::locate($this->server->http_referer));
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
     * Set SendCloud.
     * 
     * @access public
     * @return void
     */
    public function sendCloud()
    {
        if($_POST)
        {
            $mailConfig = new stdclass();
            $mailConfig->sendcloud = new stdclass();

            $mailConfig->turnon         = $this->post->turnon;
            $mailConfig->mta            = 'sendcloud';
            $mailConfig->async          = $this->post->async;
            $mailConfig->fromAddress    = ''; 
            $mailConfig->fromName       = '';
            $mailConfig->domain         = trim($this->post->domain);
            $mailConfig->sendcloud->accessKey = trim($this->post->accessKey);
            $mailConfig->sendcloud->secretKey = trim($this->post->secretKey);

            if(empty($mailConfig->sendcloud->accessKey)) die(js::alert(sprintf($this->lang->error->notempty, $this->lang->mail->accessKey)));
            if(empty($mailConfig->sendcloud->secretKey)) die(js::alert(sprintf($this->lang->error->notempty, $this->lang->mail->secretKey)));

            $this->loadModel('setting')->setItems('system.mail', $mailConfig);
            if(dao::isError()) die(js::error(dao::getError()));

            die(js::reload('parent'));
        }

        $mailConfig = new stdclass();
        if($this->config->mail->turnon)
        {
            $mailConfig = $this->config->mail->sendcloud;
            $mailConfig->fromAddress = $this->config->mail->fromAddress;
            $mailConfig->fromName    = $this->config->mail->fromName;
            $mailConfig->turnon      = $this->config->mail->turnon;
            $mailConfig->domain      = isset($this->config->mail->domain) ? $this->config->mail->domain : common::getSysURL();
            $mailConfig->async       = isset($this->config->mail->async) ? $this->config->mail->async : 0;
        }

        $this->view->title      = $this->lang->mail->sendCloud;
        $this->view->position[] = html::a(inlink('index'), $this->lang->mail->common);
        $this->view->position[] = $this->lang->mail->sendCloud;

        $this->view->mailExist  = $this->mail->mailExist();
        $this->view->mailConfig = $mailConfig;
        $this->display();
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
        $this->view->users      = $this->dao->select('account,  CONCAT(realname, " ", email) AS email' )->from(TABLE_USER)->where('email')->ne('')->andWhere('deleted')->eq(0)->orderBy('account')->fetchPairs();
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
        $this->locate(inlink('index'));
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
        $this->app->loadConfig('mail');
        $queueList = $this->mail->getQueue('wait', 'id_asc');
        $now       = helper::now();
        if(isset($this->config->mail->async))$this->config->mail->async = 0;
        $log = '';
        foreach($queueList as $queue)
        {
            if(!isset($queue->merge) or $queue->merge == false)
            {
                $mailStatus = $this->dao->select('*')->from(TABLE_MAILQUEUE)->where('id')->eq($queue->id)->fetch('status');
                if(empty($mailStatus) or $mailStatus != 'wait') continue;
            }

            $this->dao->update(TABLE_MAILQUEUE)->set('status')->eq('sending')->where('id')->in($queue->id)->exec();
            $this->mail->send($queue->toList, $queue->subject, $queue->body, $queue->ccList, true);

            $data = new stdclass();
            $data->sendTime = $now;
            $data->status   = 'send';
            if($this->mail->isError())
            {
                $data->status = 'fail';
                $data->failReason = join("\n", $this->mail->getError());
            }
            $this->dao->update(TABLE_MAILQUEUE)->data($data)->where('id')->in($queue->id)->exec();

            $log .= "Send #$queue->id  result is $data->status\n";
            if($data->status == 'fail') $log .= "reason is $data->failReason\n";
        }

        /* Delete sended mail. */
        $lastMail  = $this->dao->select('id,status')->from(TABLE_MAILQUEUE)->orderBy('id_desc')->limit(1)->fetch();
        if(!empty($lastMail) and $lastMail->id > 1000000)
        {
            $unSendNum = $this->dao->select('count(id) as count')->from(TABLE_MAILQUEUE)->where('status')->eq('wait')->fetch('count');
            if($unSendNum == 0) $this->dao->exec('TRUNCATE table ' . TABLE_MAILQUEUE);
        }
        $this->dao->delete()->from(TABLE_MAILQUEUE)->where('status')->eq('send')->andWhere('sendTime')->le(date('Y-m-d H:i:s', time() - 2 * 24 * 3600))->exec();

        echo $log;
        echo "OK\n";
    }

    /**
     * Resend fail mails. 
     * 
     * @access public
     * @return void
     */
    public function resend($queueID)
    {
        $queue = $this->mail->getQueueById($queueID);
        if($queue and $queue->status == 'send')
        {
            echo js::alert($this->lang->mail->noticeResend);
            die(js::reload('parent'));
        }

        if(isset($this->config->mail->async)) $this->config->mail->async = 0;
        $this->mail->send($queue->toList, $queue->subject, $queue->body, $queue->ccList);

        $data = new stdclass();
        $data->sendTime   = helper::now();
        $data->status     = 'send';
        $data->failReason = '';
        if($this->mail->isError())
        {
            $data->status     = 'fail';
            $data->failReason = join("\n", $this->mail->getError());
        }
        $this->dao->update(TABLE_MAILQUEUE)->data($data)->where('id')->in($queue->id)->exec();

        if($data->status == 'fail') die(js::alert($data->failReason));
        echo js::alert($this->lang->mail->noticeResend);
        die(js::reload('parent'));
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

    /**
     * Sendcloud user.
     * 
     * @access public
     * @return void
     */
    public function sendcloudUser()
    {
        if($this->config->mail->mta != 'sendcloud') die(js::locate('back'));

        $this->mta = $this->mail->setMTA();
        if($_POST)
        {
            $data = fixer::input('post')->get();
            $action   = $data->action;
            $listName = $action == 'delete' ? 'syncedList' : 'unsyncList';

            $users = array_unique($data->$listName);
            if(empty($users)) die(js::reload('parent'));

            $realnameAndEmails = $this->loadModel('user')->getRealNameAndEmails($users);
            $actionedEmail = array();
            foreach($realnameAndEmails as $realnameAndEmail)
            {
                $email = $realnameAndEmail->email;
                if(isset($actionedEmail[$email])) continue;

                $result = $this->mail->syncSendCloud($action, $email, $realnameAndEmail->realname);
                if(!$result->result)
                {
                    echo(js::alert($this->lang->mail->sendCloudFail . str_replace("'", '"', $result->message) . "(CODE: $result->statusCode)"));
                    die(js::reload('parent'));
                }

                $actionedEmail[$email] = $email;
            }

            echo(js::alert($this->lang->mail->sendCloudSuccess));
            die(js::reload('parent'));
        }

        $this->view->title      = $this->lang->mail->sendcloudUser;
        $this->view->position[] = html::a(inlink('index'), $this->lang->mail->common);
        $this->view->position[] = $this->lang->mail->sendcloudUser;

        $this->view->members = $this->mta->memberList();
        $this->view->users   = $this->loadModel('user')->getList();
        $this->display();
    }

    /**
     * zentao cloud.
     * 
     * @access public
     * @return void
     */
    public function ztCloud()
    {
        if($_POST)
        {
            $mailConfig = new stdclass();
            $mailConfig->sendcloud = new stdclass();

            $mailConfig->turnon      = $this->post->turnon;
            $mailConfig->mta         = 'ztcloud';
            $mailConfig->async       = $this->post->async;
            $mailConfig->fromAddress = $this->post->fromAddress; 
            $mailConfig->fromName    = $this->post->fromName;
            $mailConfig->domain      = trim($this->post->domain);

            if(empty($mailConfig->fromName)) die(js::alert(sprintf($this->lang->error->notempty, $this->lang->mail->fromName)));

            $this->loadModel('setting')->setItems('system.mail', $mailConfig);
            die(js::reload('parent'));
        }

        $this->view->title      = $this->lang->mail->ztCloud;
        $this->view->position[] = html::a(inlink('index'), $this->lang->mail->common);
        $this->view->position[] = $this->lang->mail->ztCloud;
        if(!empty($this->config->mail->ztcloud->secretKey) and !empty($this->config->global->community))
        {
            $mailConfig = new stdclass();
            $mailConfig->fromAddress = $this->config->mail->fromAddress;
            $mailConfig->fromName    = $this->config->mail->fromName;
            $mailConfig->turnon      = $this->config->mail->turnon;
            $mailConfig->domain      = isset($this->config->mail->domain) ? $this->config->mail->domain : common::getSysURL();
            $mailConfig->async       = isset($this->config->mail->async) ? $this->config->mail->async : 0;

            $this->view->mailExist  = $this->mail->mailExist();
            $this->view->mailConfig = $mailConfig;
            $this->view->step       = 'config';
            die($this->display());
        }

        if(empty($this->config->global->ztPrivateKey) or $this->config->global->community == 'na' or empty($this->config->global->community))
        {
            if(!empty($this->config->global->community) and $this->config->global->community != 'na') die(js::locate($this->createLink('admin', 'bind', 'from=mail')));
            die(js::locate($this->createLink('admin', 'register', 'from=mail')));
        }

        if($this->cookie->ztCloudLicense != 'yes')
        {
            $this->view->step = 'license';
            die($this->display());
        }

        $result = $this->loadModel('admin')->getSecretKey();
        if(empty($result))die(js::alert($this->lang->mail->connectFail) . js::locate($this->createLink('admin', 'register', "from=mail")));
        if($result->result == 'fail' and empty($result->data)) die(js::alert($this->lang->mail->centifyFail) . js::locate($this->createLink('admin', 'register', "from=mail")));

        $data = $result->data;
        if((isset($data->qq) and empty($data->qq)) or (isset($data->company) and empty($data->company)))
        {
            $params = '';
            if(empty($data->qq))$params .= 'qq,';
            if(empty($data->company))$params .= 'company,';
            die(js::locate($this->createLink('admin', 'ztCompany', 'fields=' . trim($params, ','))));
        }
        if($result->result == 'fail' and empty($data->emailCertified))
        {
            die(js::locate($this->createLink('admin', 'certifyZtEmail', 'email=' . helper::safe64Encode($data->email))));
        }
        if($result->result == 'fail' and empty($data->mobileCertified))
        {
            die(js::locate($this->createLink('admin', 'certifyZtMobile', 'mobile=' . helper::safe64Encode($data->mobile))));
        }
        if($result->result == 'success')
        {
            $this->loadModel('setting')->setItem('system.mail.ztcloud.secretKey', $data->secretKey);
            $this->setting->setItem('system.mail.fromAddress', $data->email);

            $mailConfig = new stdclass();
            $mailConfig->turnon      = true;
            $mailConfig->fromAddress = $data->email;
            $mailConfig->fromName    = $this->config->mail->fromName;
            $mailConfig->domain      = isset($this->config->mail->domain) ? $this->config->mail->domain : common::getSysURL();

            $this->view->mailConfig = $mailConfig;
            $this->view->step       = 'config';
            die($this->display());
        }
    }
}
