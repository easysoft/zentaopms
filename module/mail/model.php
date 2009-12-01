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
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     mail
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php
class mailModel extends model
{
    private static $instance;
    private $mta;
    private $mtaType;

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
        $funcName = "set{$this->config->mail->mta}";
        if(!method_exists($this, $funcName)) echo $this->app->error("The MTA {$this->config->mail->mta} not supported now.", __FILE__, __LINE__, $exit = false);
        $this->$funcName();
    }

    /* 发送邮件。*/
    public function send($subject, $body, $toList, $ccList)
    {
        try 
        {
            $this->mta->setFrom($this->config->mail->fromAddress, $this->config->mail->fromName);
            $this->setSubject($subject);
            $this->setTO($toList);
            $this->setCC($ccList);
            $this->setBody($body);
            $this->mta->send();
        }
        catch (phpmailerException $e) 
        {
            echo $e->errorMessage();
        } 
        catch (Exception $e) 
        {
            echo $e->getMessage();
        }
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

    /* 设置发送地址。*/
    private function setTO($toList)
    {
        foreach($toList as $toName => $toAddress) $this->mta->addAddress($toAddress, $toName);
    }

    /* 设置抄送地址。*/
    private function setCC($ccList)
    {
        if(!is_array($ccList)) return;
        foreach($ccList as $ccName => $ccAddress) $this->mta->addCC($ccAddress, $ccName);
    }

    /* 设置主题。*/
    private function setSubject($subject)
    {
        $this->mta->Subject = stripslashes($subject);
    }

    /* 设置body。*/
    private function setBody($body)
    {
        $this->mta->msgHtml($body);
    }

    /* 清楚地址和附件。*/
    private function clear()
    {
        $this->mta->clearAddresses();
        $this->mta->cearAttachments();
    }
}
