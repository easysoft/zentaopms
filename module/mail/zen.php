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
}

