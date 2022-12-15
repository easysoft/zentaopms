<?php
/**
 * The host entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@easycorp.ltd>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class hostHeartbeatEntry extends baseEntry
{
    /**
     * Listen host heartbeat.
     *
     * @param  int|string $userID
     * @access public
     * @return void
     */
    public function post()
    {
        /* Check authorize. */
        $header = getallheaders();
        $token  = isset($header['Authorization']) ? substr($header['Authorization'], 7) : '';
        $secret = isset($this->requestBody->secret) ? $this->requestBody->secret : '';
        if(!$secret and !$token) return $this->sendError(401, 'Unauthorized');

        /* Check param. */
        $status = $this->requestBody->status;
        $vms    = $this->requestBody->Vms;
        $zap    = $this->requestBody->port;
        $now    = helper::now();
        if(!$status) return $this->sendError(400, 'Params error.');

        $conditionField = $secret ? 'secret' : 'tokenSN';
        $conditionValue = $secret ? $secret  : $token;

        $this->dao = $this->loadModel('common')->dao;
        $hostInfo = $this->dao->select('id,tokenSN')->from(TABLE_ZAHOST)
            ->beginIF($secret)->where('secret')->eq($secret)->fi()
            ->beginIF(!$secret)->where('tokenSN')->eq($token)
            ->andWhere('tokenTime')->gt($now)->fi()
            ->fetch();
        if(empty($hostInfo->id))
        {
            if(empty($token)) return $this->sendError(400, 'Secret error.');
            $hostInfo = $this->dao->select('id,tokenSN')->from(TABLE_ZAHOST)
                ->where('oldTokenSN')->eq($token)
                ->andWhere('tokenTime')->gt(date(DT_DATETIME1, strtotime($now) - 30))->fi()
                ->fetch();
            if(empty($hostInfo->id)) return $this->sendError(400, 'Secret error.');
        }

        $host = new stdclass();
        $host->status = $status;
        if($secret)
        {
            $host->tokenSN    = md5($secret . $now);
            $host->tokenTime  = date('Y-m-d H:i:s', time() + 7200);
            $host->oldTokenSN = $hostInfo->tokenSN;
        }
        $host->heartbeat = $now;
        $host->zap       = $zap;
        $this->dao->update(TABLE_ZAHOST)->data($host)->where($conditionField)->eq($conditionValue)->exec();

        if($vms)
        {
            foreach($vms as $vm)
            {
                if(!empty($vm->vncPortOnHost))
                {
                    $this->dao->update(TABLE_ZAHOST)
                        ->set('vnc')->eq($vm->vncPortOnHost)
                        ->set('zap')->eq($vm->agentPortOnHost)
                        ->set('ztf')->eq($vm->ztfPortOnHost)
                        ->set('zd')->eq($vm->zdPortOnHost)
                        ->set('ssh')->eq($vm->sshPortOnHost)
                        ->set('status')->eq($vm->status)
                        ->set('extranet')->eq($vm->ip)
                        ->where('mac')->eq($vm->macAddress)->exec();
                }
            }
        }

        if(!$secret) return $this->sendSuccess(200, 'success');

        $host->tokenTimeUnix = strtotime($host->tokenTime);
        unset($host->status);
        unset($host->tokenTime);
        unset($host->oldTokenSN);
        return $this->send(200, $host);
    }
}
