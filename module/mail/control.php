<?php
/**
 * The control file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     mail
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class mail extends control
{
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

        $this->view->header->title = $this->lang->mail->detect;
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
        $mailConfig = $this->session->mailConfig ? $this->session->mailConfig : $this->config->mail->smtp;

        if(!isset($mailConfig->debug))    $mailConfig->debug    = 1;
        if(!isset($mailConfig->username)) $mailConfig->username = '';
        if(!isset($mailConfig->password)) $mailConfig->password = '';
        if(!isset($mailConfig->secure))   $mailConfig->secure   = '';
        if(!isset($mailConfig->fromName)) $mailConfig->fromName = 'zentao';

        $this->view->header->title = $this->lang->mail->edit;
        $this->view->position[] = html::a(inlink('index'), $this->lang->mail->common);
        $this->view->position[] = $this->lang->mail->edit;

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
            $mailConfig = <<<EOT
<?php
\$config->mail->turnon         = {$this->post->turnon};
\$config->mail->mta            = 'smtp';
\$config->mail->fromAddress    = "{$this->post->fromAddress}"; 
\$config->mail->fromName       = "{$this->post->fromName}";
\$config->mail->smtp->host     = "{$this->post->host}";
\$config->mail->smtp->port     = "{$this->post->port}";
\$config->mail->smtp->auth     = {$this->post->auth};
\$config->mail->smtp->username = "{$this->post->username}";
\$config->mail->smtp->password = "{$this->post->password}";
\$config->mail->smtp->secure   = "{$this->post->secure}";
\$config->mail->smtp->debug    = {$this->post->debug};
EOT;

            /* Output config to the extconfig file of mail */
            $configPath = $this->app->getModuleExtPath('mail', 'config');
            $configFile = $configPath . 'zzzemail.php';
            $saved      = false;
            if(is_file($configFile)  and is_writable($configFile)) $saved = file_put_contents($configFile, $mailConfig);
            if(!is_file($configFile) and is_writable($configPath)) $saved = file_put_contents($configFile, $mailConfig);

            $this->view->header->title = $this->lang->mail->save;
            $this->view->position[] = html::a(inlink('index'), $this->lang->mail->common);
            $this->view->position[] = $this->lang->mail->save;

            $this->view->mailConfig = $mailConfig;
            $this->view->configPath = $configPath;
            $this->view->configFile = $configFile;
            $this->view->saved      = $saved;
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
        if($_POST)
        {
            $this->mail->send($this->post->to, $this->lang->mail->subject, $this->lang->mail->content,"", true);
            if($this->mail->isError()) die(js::error($this->mail->getError()));
            die(js::alert($this->lang->mail->successSended));
        }

        $this->view->users = $this->dao->select('account,  CONCAT(realname, " ", email) AS email' )->from(TABLE_USER)->where('email')->ne('')->orderBy('account')->fetchPairs();
        $this->display();
    }
}
