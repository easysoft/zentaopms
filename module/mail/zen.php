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

    /**
     * Get has mail user pairs.
     *
     * @access protected
     * @return array
     */
    protected function getHasMailUserPairs(): array
    {
        $users     = $this->dao->select('*')->from(TABLE_USER)->where('email')->ne('')->andWhere('deleted')->eq(0)->orderBy('account')->fetchAll();
        $userPairs = array();
        foreach($users as $user) $userPairs[$user->account] = $user->realname . ' ' . $user->email;

        return $userPairs;
    }

    /**
     * Send a queue.
     *
     * @param  object    $queue
     * @access protected
     * @return array|false
     */
    protected function sendQueue(object $queue, bool $includeMe = false): array|false
    {
        $now        = helper::now();
        $log        = '';
        $mailStatus = '';
        if(!isset($queue->merge) or $queue->merge == false) $mailStatus = $this->dao->select('*')->from(TABLE_NOTIFY)->where('id')->eq($queue->id)->fetch('status');
        if(empty($mailStatus) or $mailStatus != 'wait') return false;

        $this->dao->update(TABLE_NOTIFY)->set('status')->eq('sending')->where('id')->in($queue->id)->exec();
        $this->mail->send($queue->toList, $queue->subject, $queue->data, $queue->ccList, $includeMe);

        $data = new stdclass();
        $data->sendTime = $now;
        $data->status   = 'sended';
        if($this->mail->isError())
        {
            $data->status = 'fail';
            $data->failReason = implode("\n", $this->mail->getError());
        }
        $this->dao->update(TABLE_NOTIFY)->data($data)->where('id')->in($queue->id)->exec();

        $log .= "Send #$queue->id  result is {$data->status}\n";
        if($data->status == 'fail') $log .= "reason is $data->failReason\n";

        return array('result' => $data->status == 'fail' ? 'fail' : 'success', 'message' => $log);
    }

    /**
     * Delete sent queue.
     *
     * @access protected
     * @return void
     */
    protected function deleteSentQueue()
    {
        $lastMail  = $this->dao->select('id,status')->from(TABLE_NOTIFY)->where('objectType')->eq('mail')->orderBy('id_desc')->limit(1)->fetch();
        if(!empty($lastMail) and $lastMail->id > 1000000)
        {
            $unSendNum = $this->dao->select('count(id) as count')->from(TABLE_NOTIFY)->where('status')->eq('wait')->fetch('count');
            if($unSendNum == 0) $this->dao->exec('TRUNCATE table ' . TABLE_NOTIFY);
        }

        /* Delete two days ago queues. */
        $this->dao->delete()->from(TABLE_NOTIFY)->where('status')->eq('sended')->andWhere('sendTime')->le(date('Y-m-d H:i:s', time() - 2 * 24 * 3600))->exec();
    }
}
