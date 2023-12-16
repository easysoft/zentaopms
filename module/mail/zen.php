<?php
declare(strict_types=1);
/**
 * The zen file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@easycorp.ltd>
 * @package     mail
 * @link        https://www.zentao.net
 */
class mailZen extends mail
{
    /**
     * 获取编辑页面的邮箱配置。
     * Get mail config for edit.
     *
     * @access protected
     * @return object|false
     */
    protected function getConfigForEdit(): object|false
    {
        $mailConfig = '';
        if($this->session->mailConfig) $mailConfig = $this->session->mailConfig;
        if($this->config->mail->turnon)
        {
            $mailConfig = $this->config->mail->smtp;
            $mailConfig->fromAddress = $this->config->mail->fromAddress;
            $mailConfig->fromName    = $this->config->mail->fromName;
            $mailConfig->charset     = zget($mailConfig, 'charset', 'utf-8');
        }

        if(empty($mailConfig)) return false;

        $mailConfig->domain = isset($this->config->mail->domain) ? $this->config->mail->domain : common::getSysURL();
        return $mailConfig;
    }

    /**
     * Get mail config for save.
     *
     * @access protected
     * @return object
     */
    protected function getConfigForSave(): object
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

        return $mailConfig;
    }
}

