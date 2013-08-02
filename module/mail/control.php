<?php
/**
 * The control file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     mail
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class mail extends control
{
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
            $mailConfig->fromAddress    = $this->post->fromAddress; 
            $mailConfig->fromName       = $this->post->fromName;
            $mailConfig->smtp->host     = $this->post->host;
            $mailConfig->smtp->port     = $this->post->port;
            $mailConfig->smtp->auth     = $this->post->auth;
            $mailConfig->smtp->username = $this->post->username;
            $mailConfig->smtp->password = $this->post->password;
            $mailConfig->smtp->secure   = $this->post->secure;
            $mailConfig->smtp->debug    = $this->post->debug;
            $mailConfig->smtp->charset  = $this->post->charset;

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
            $this->mail->send($this->post->to, $this->lang->mail->subject, $this->lang->mail->content,"", true);
            if($this->mail->isError())
            {
                $this->view->error = $this->mail->getError();
                die($this->display());
            }
            die(js::alert($this->lang->mail->successSended));
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
}
