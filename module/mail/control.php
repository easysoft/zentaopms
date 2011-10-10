<?php
/**
 * The control file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     mail
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class mail extends control
{
    /**
     * Config email. 
     * 
     * @access public
     * @return void
     */
    public function set()
    {
        $this->view->mailConfig = $this->app->loadConfig('mail')->mail;
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
            if('gmail' == $this->post->mta)
            {
                $config = <<<EOT
<?php
\$config->mail->turnon          = {$this->post->turnon};
\$config->mail->fromAddress     = "{$this->post->fromAddress}"; 
\$config->mail->mta             = "gmail";
\$config->mail->fromName        = "{$this->post->fromName}";
\$config->mail->gmail->debug    = {$this->post->gmailDebug};
\$config->mail->gmail->username = "{$this->post->gmailUsername}";
\$config->mail->gmail->password = "{$this->post->gmailPassword}";
EOT;
            }
            elseif('smtp' == $this->post->mta)
            {
                $position = strpos($this->post->fromAddress, '@');
                if($this->post->smtpHost == '')
                {
                    $host = 'smtp.' . substr($this->post->fromAddress, $position + 1); 
                }
                else
                {
                    $host = $this->post->smtpHost;
                }

                $config = <<<EOT
<?php
\$config->mail->turnon         = {$this->post->turnon};
\$config->mail->fromAddress    = "{$this->post->fromAddress}"; 
\$config->mail->mta            = "{$this->post->mta}";
\$config->mail->fromName       = "{$this->post->fromName}";
\$config->mail->smtp->debug    = {$this->post->smtpDebug};
\$config->mail->smtp->username = "{$this->post->smtpUsername}";
\$config->mail->smtp->password = "{$this->post->smtpPassword}";
\$config->mail->smtp->auth     = {$this->post->smtpAuth};
\$config->mail->smtp->host     = "$host";
\$config->mail->smtp->secure   = "{$this->post->smtpSecure}";
\$config->mail->smtp->port     = "{$this->post->smtpPort}";
EOT;
            }
            elseif('phpmail' == $this->post->mta or 'sendmail' == $this->post->mta)
            {
                $config = <<<EOT
<?php
\$config->mail->turnon      = {$this->post->turnon};
\$config->mail->fromAddress = "{$this->post->fromAddress}"; 
\$config->mail->mta         = "{$this->post->mta}";
\$config->mail->fromName    = "{$this->post->fromName}";
EOT;
            }

            /* Output config to the extconfig file of mail */
            $configPath = $this->app->getModuleExtPath('mail', 'config');
            if(is_writable($configPath))
            {
                if(file_put_contents($configPath . 'zzzemail.php', $config))
                {
                    /* Send test mail */
                    $this->mail->send($this->app->user->account, $this->lang->mail->subject, $this->lang->mail->content,"");
                    if($this->mail->isError()) echo js::error($this->mail->getError());
                    echo js::confirm($this->lang->mail->confirmSave,$this->createLink('mail', 'set'));
                }
                else
                {
                    $this->view->config     = $config;
                    $this->view->configPath = $configPath;
                    $this->display();
                }
            }
            else
            {
                $this->view->config     = $config;
                $this->view->configPath = $configPath;
                $this->display();
            }
        }
    }
}
