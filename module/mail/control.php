<?php
/**
 * The control file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
        $this->loadModel('message');

        /* Task #1967. check the function of fsocket. */
        if(isset($this->config->mail->mta) and $this->config->mail->mta != 'sendcloud' and !function_exists('fsockopen'))
        {
            echo js::alert($this->lang->mail->nofsocket);
            return print(js::locate('back'));
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
            $this->session->set('mailConfig',  $mailConfig);

            $response['load'] = inlink('edit');
            return $this->send($response);
        }

        $this->view->title      = $this->lang->mail->common . $this->lang->colon . $this->lang->mail->detect;

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

            if($mailConfig->turnon && empty($mailConfig->fromName)) return $this->sendError(array('fromName' => sprintf($this->lang->error->notempty, $this->lang->mail->fromName)));

            /* The mail need openssl and curl extension when secure is tls. */
            if($mailConfig->smtp->secure == 'tls')
            {
                if(!extension_loaded('openssl'))
                {
                    return $this->sendError($this->lang->mail->noOpenssl);
                }
                if(!extension_loaded('curl'))
                {
                    return $this->sendError($this->lang->mail->noCurl);
                }
            }

            $this->session->set('mailConfig', $mailConfig->turnon);
            $this->loadModel('setting')->setItems('system.mail', $mailConfig);
            if(dao::isError()) return $this->sendError(dao::getError());

            $result = array('result' => 'success');
            if($mailConfig->turnon)
            {
                $mailExist = !empty($this->mail->mailExist());
                $result['callback'] = "window.mailTips({$mailExist});";
            }
            else
            {
                $result['load'] = inlink('detect');
            }
            return $this->send($result);
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

            $mailConfig->turnon      = $this->post->turnon;
            $mailConfig->mta         = 'sendcloud';
            $mailConfig->async       = $this->post->async;
            $mailConfig->fromAddress = '';
            $mailConfig->fromName    = '';
            $mailConfig->domain      = trim($this->post->domain);
            $mailConfig->sendcloud->accessKey = trim($this->post->accessKey);
            $mailConfig->sendcloud->secretKey = trim($this->post->secretKey);

            if(empty($mailConfig->sendcloud->accessKey)) return print(js::alert(sprintf($this->lang->error->notempty, $this->lang->mail->accessKey)));
            if(empty($mailConfig->sendcloud->secretKey)) return print(js::alert(sprintf($this->lang->error->notempty, $this->lang->mail->secretKey)));

            $this->loadModel('setting')->setItems('system.mail', $mailConfig);
            if(dao::isError()) return print(js::error(dao::getError()));

            return print(js::reload('parent'));
        }

        $mailConfig = new stdclass();
        if($this->config->mail->turnon)
        {
            $mailConfig = isset($this->config->mail->sendcloud) ? $this->config->mail->sendcloud : new stdclass();
            $mailConfig->fromAddress = $this->config->mail->fromAddress;
            $mailConfig->fromName    = $this->config->mail->fromName;
            $mailConfig->turnon      = $this->config->mail->turnon;
            $mailConfig->domain      = isset($this->config->mail->domain) ? $this->config->mail->domain : common::getSysURL();
            $mailConfig->async       = isset($this->config->mail->async) ? $this->config->mail->async : 0;
        }

        $this->view->title      = $this->lang->mail->sendCloud;

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
        if(!$this->config->mail->turnon) return $this->sendError($this->lang->mail->needConfigure);

        if($_POST)
        {
            /* The mail need openssl and curl extension when secure is tls. */
            if(isset($this->config->mail->async)) $this->config->mail->async = 0;
            if($this->config->mail->smtp->secure == 'tls')
            {
                if(!extension_loaded('openssl')) return $this->sendError($this->lang->mail->noOpenssl);

                if(!extension_loaded('curl')) return $this->sendError($this->lang->mail->noCurl);
            }

            $this->mail->send($this->post->to, $this->lang->mail->testSubject, $this->lang->mail->testContent, '', true);
            if($this->mail->isError()) return $this->sendError(array('error' => implode("\n", $this->mail->getError())));

            return $this->sendSuccess(array('load' => inLink('test')));
        }

        $users     = $this->dao->select('*')->from(TABLE_USER)->where('email')->ne('')->andWhere('deleted')->eq(0)->orderBy('account')->fetchAll();
        $userPairs = array();
        foreach($users as $user) $userPairs[$user->account] = $user->realname . ' ' . $user->email;

        $this->view->title = $this->lang->mail->common . $this->lang->colon . $this->lang->mail->test;
        $this->view->users = $userPairs;
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
        $this->loadModel('common')->loadConfigFromDB();
        $this->app->loadConfig('mail');
        $queueList = $this->mail->getQueue('wait', 'id_asc');
        $now       = helper::now();
        if(isset($this->config->mail->async))$this->config->mail->async = 0;
        $log = '';
        foreach($queueList as $queue)
        {
            if(!isset($queue->merge) or $queue->merge == false)
            {
                $mailStatus = $this->dao->select('*')->from(TABLE_NOTIFY)->where('id')->eq($queue->id)->fetch('status');
                if(empty($mailStatus) or $mailStatus != 'wait') continue;
            }

            $this->dao->update(TABLE_NOTIFY)->set('status')->eq('sending')->where('id')->in($queue->id)->exec();
            $this->mail->send($queue->toList, $queue->subject, $queue->data, $queue->ccList, true);

            $data = new stdclass();
            $data->sendTime = $now;
            $data->status   = 'sended';
            if($this->mail->isError())
            {
                $data->status = 'fail';
                $data->failReason = implode("\n", $this->mail->getError());
            }
            $this->dao->update(TABLE_NOTIFY)->data($data)->where('id')->in($queue->id)->exec();

            $log .= "Send #$queue->id  result is $data->status\n";
            if($data->status == 'fail') $log .= "reason is $data->failReason\n";
        }

        /* Delete sended mail. */
        $lastMail  = $this->dao->select('id,status')->from(TABLE_NOTIFY)->where('objectType')->eq('mail')->orderBy('id_desc')->limit(1)->fetch();
        if(!empty($lastMail) and $lastMail->id > 1000000)
        {
            $unSendNum = $this->dao->select('count(id) as count')->from(TABLE_NOTIFY)->where('status')->eq('wait')->fetch('count');
            if($unSendNum == 0) $this->dao->exec('TRUNCATE table ' . TABLE_NOTIFY);
        }
        $this->dao->delete()->from(TABLE_NOTIFY)->where('status')->eq('sended')->andWhere('sendTime')->le(date('Y-m-d H:i:s', time() - 2 * 24 * 3600))->exec();

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
        if($queue and $queue->status == 'sended') return $this->sendSuccess(array('message' => $this->lang->mail->noticeResend, 'load' => true));

        if(isset($this->config->mail->async)) $this->config->mail->async = 0;
        $this->mail->send($queue->toList, $queue->subject, $queue->data, $queue->ccList);

        $data = new stdclass();
        $data->sendTime   = helper::now();
        $data->status     = 'sended';
        $data->failReason = '';
        if($this->mail->isError())
        {
            $data->status     = 'fail';
            $data->failReason = str_replace("\n", '', implode("\n", $this->mail->getError()));
        }
        $this->dao->update(TABLE_NOTIFY)->data($data)->where('id')->in($queue->id)->exec();

        if($data->status == 'fail') return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.alert(" . json_encode(array('message' => array('html' => $data->failReason))) . ")"));
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
    public function browse($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->mail->browse;

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
    public function delete($id)
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

    /**
     * Sendcloud user.
     *
     * @access public
     * @return void
     */
    public function sendcloudUser()
    {
        if($this->config->mail->mta != 'sendcloud') return print(js::locate('back'));

        $this->mta = $this->mail->setMTA();
        if($_POST)
        {
            $data = fixer::input('post')->get();
            $action   = $data->action;
            $listName = $action == 'delete' ? 'syncedList' : 'unsyncList';

            $users = array_unique($data->$listName);
            if(empty($users)) return print(js::reload('parent'));

            $realnameAndEmails = $this->loadModel('user')->getRealNameAndEmails($users);
            $actionedEmail = array();
            foreach($realnameAndEmails as $realnameAndEmail)
            {
                $email = $realnameAndEmail->email;
                if(isset($actionedEmail[$email])) continue;

                $result = $this->mail->syncSendCloud($action, $email, $realnameAndEmail->realname);
                if(!$result->result)
                {
                    echo js::alert($this->lang->mail->sendCloudFail . str_replace("'", '"', $result->message) . "(CODE: $result->statusCode)");
                    return print(js::reload('parent'));
                }

                $actionedEmail[$email] = $email;
            }

            echo js::alert($this->lang->mail->sendCloudSuccess);
            return print(js::reload('parent'));
        }

        $this->view->title      = $this->lang->mail->sendcloudUser;

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

            if(empty($mailConfig->fromName)) return print(js::alert(sprintf($this->lang->error->notempty, $this->lang->mail->fromName)));

            $this->loadModel('setting')->setItems('system.mail', $mailConfig);
            return print(js::reload('parent'));
        }

        $this->view->title      = $this->lang->mail->ztCloud;
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
            return print($this->display());
        }

        if(empty($this->config->global->ztPrivateKey) or $this->config->global->community == 'na' or empty($this->config->global->community))
        {
            if(!empty($this->config->global->community) and $this->config->global->community != 'na') return print(js::locate($this->createLink('admin', 'bind', 'from=mail')));
            return print(js::locate($this->createLink('admin', 'register', 'from=mail')));
        }

        if($this->cookie->ztCloudLicense != 'yes')
        {
            $this->view->step = 'license';
            return print($this->display());
        }

        $result = $this->loadModel('admin')->getSecretKey();
        if(empty($result))return print(js::alert($this->lang->mail->connectFail) . js::locate($this->createLink('admin', 'register', "from=mail")));
        if($result->result == 'fail' and empty($result->data)) return print(js::alert($this->lang->mail->centifyFail) . js::locate($this->createLink('admin', 'register', "from=mail")));

        $data = $result->data;
        if((isset($data->qq) and empty($data->qq)) or (isset($data->company) and empty($data->company)))
        {
            $params = '';
            if(empty($data->qq)) $params .= 'qq,';
            if(empty($data->company)) $params .= 'company,';
            return print(js::locate($this->createLink('admin', 'ztCompany', 'fields=' . trim($params, ','))));
        }
        if($result->result == 'fail' and empty($data->emailCertified))
        {
            return print(js::locate($this->createLink('admin', 'certifyZtEmail', 'email=' . helper::safe64Encode($data->email))));
        }
        if($result->result == 'fail' and empty($data->mobileCertified))
        {
            return print(js::locate($this->createLink('admin', 'certifyZtMobile', 'mobile=' . helper::safe64Encode($data->mobile))));
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
            return print($this->display());
        }
    }
}
