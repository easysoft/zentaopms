<?php
/**
 * The model file of mail module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     mail
 * @version     $Id$
 * @link        http://www.zentaoms.com
 */
?>
<?php
class mailModel extends model
{
    private static $instance;
    private $mta;
    private $mtaType;
    private $errors = array();

    public function __construct()
    {
        parent::__construct();
        $this->app->loadClass('phpmailer', $static = true);
        $this->setMTA();
    }

    /* 设置邮件传输代理: MTA。*/
    public function setMTA()
    {
        if(self::$instance == null) self::$instance = new phpmailer(true);
        $this->mta = self::$instance;
        $this->mta->CharSet = $this->config->encoding;
        $funcName = "set{$this->config->mail->mta}";
        if(!method_exists($this, $funcName)) echo $this->app->error("The MTA {$this->config->mail->mta} not supported now.", __FILE__, __LINE__, $exit = false);
        $this->$funcName();
    }

    /* SMTP方式。*/
    private function setSMTP()
    {
        $this->mta->isSMTP();
        $this->mta->SMTPDebug = $this->config->mail->smtp->debug;
        $this->mta->Host      = $this->config->mail->smtp->host;
        $this->mta->SMTPAuth  = $this->config->mail->smtp->auth;
        $this->mta->Username  = $this->config->mail->smtp->username;
        $this->mta->Password  = $this->config->mail->smtp->password;
        if(isset($this->config->mail->smtp->port)) $this->mta->Port = $this->config->mail->smtp->port;
    }

    /* PHP Mail方式。*/
    private function setPhpMail()
    {
        $this->mta->isMail();
    }

    /* SendMail方式。*/
    private function setSendMail()
    {
        $this->mta->isSendmail();
    }

    /* GMAIL方式。*/
    private function setGMail()
    {
        $this->mta->isSMTP();
        $this->mta->SMTPDebug  = $this->config->mail->gmail->debug;
        $this->mta->Host       = 'smtp.gmail.com';
        $this->mta->Port       = 465;
        $this->mta->SMTPSecure = "ssl";
        $this->mta->SMTPAuth   = true;
        $this->mta->Username   = $this->config->mail->gmail->username;
        $this->mta->Password   = $this->config->mail->gmail->password;
    }

    /* 发送邮件。*/
    public function send($toList, $subject, $body = '', $ccList = '')
    {
        if(!$this->config->mail->turnon) return;

        /* 获得用户的真实姓名和email列表。*/
        $this->loadModel('user');
        $emails = $this->user->getRealNameAndEmails(str_replace(' ', '', $toList . ',' . $ccList));
        
        $this->clear();

        try 
        {
            $this->mta->setFrom($this->config->mail->fromAddress, $this->config->mail->fromName);
            $this->setSubject($subject);
            $this->setTO($toList, $emails);
            $this->setCC($ccList, $emails);
            $this->setBody($body);
            $this->setErrorLang();
            $this->mta->send();
        }
        catch (phpmailerException $e) 
        {
            $this->errors[] = trim(strip_tags($e->errorMessage()));
        } 
        catch (Exception $e) 
        {
            $this->errors[] = trim(strip_tags($e->getMessage()));
        }
    }

    /* 设置发送地址。*/
    private function setTO($toList, $emails)
    {
        $toList = explode(',', str_replace(' ', '', $toList));
        foreach($toList as $account)
        {
            if(!isset($emails[$account]) or isset($emails[$account]->sended) or strpos($emails[$account]->email, '@') == false) continue;
            $this->mta->addAddress($emails[$account]->email, $emails[$account]->realname);
            $emails[$account]->sended = true;
        }
    }

    /* 设置抄送地址。*/
    private function setCC($ccList, $emails)
    {
        $ccList = explode(',', str_replace(' ', '', $ccList));
        if(!is_array($ccList)) return;
        foreach($ccList as $account)
        {
            if(!isset($emails[$account]) or isset($emails[$account]->sended) or strpos($emails[$account]->email, '@') == false) continue;
            $this->mta->addCC($emails[$account]->email, $emails[$account]->realname);
            $emails[$account]->sended = true;
        }
    }

    /* 设置主题。*/
    private function setSubject($subject)
    {
        $this->mta->Subject = stripslashes($subject);
    }

    /* 设置body。*/
    private function setBody($body)
    {
        $this->mta->msgHtml("$body");
    }

     /* 设置错误提示语言。*/
    private function setErrorLang()
    {
        $this->mta->SetLanguage($this->app->getClientLang());
    }
   
    /* 清楚地址和附件。*/
    private function clear()
    {
        $this->mta->clearAddresses();
        $this->mta->clearAttachments();
    }

    /* 判断是否有错！*/
    public function isError()
    {
        return !empty($this->errors);
    }

    /* 获得错误。*/
    public function getError()
    {
        $errors = $this->errors;
        $this->errors = array();
        return $errors;
    }
}
