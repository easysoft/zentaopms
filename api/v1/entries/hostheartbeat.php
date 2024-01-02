<?php
/**
 * The host entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
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
        $vms    = !empty($this->requestBody->Vms) ? $this->requestBody->Vms : array();
        $zap    = !empty($this->requestBody->port) ? $this->requestBody->port : 0;
        $now    = helper::now();
        if(!$status) return $this->sendError(400, 'Params error.');

        $conditionField = $secret ? 'secret' : 'tokenSN';
        $conditionValue = $secret ? $secret  : $token;

        $this->dao = $this->loadModel('common')->dao;
        $hostInfo = $this->dao->select('id,tokenSN,hostType,type')->from(TABLE_ZAHOST)
            ->beginIF($secret)->where('secret')->eq($secret)->fi()
            ->beginIF(!$secret)->where('tokenSN')->eq($token)
            ->andWhere('tokenTime')->gt($now)->fi()
            ->fetch();
        if(empty($hostInfo->id))
        {
            if(empty($token)) return $this->sendError(400, 'Secret error.');
            $hostInfo = $this->dao->select('id,tokenSN,hostType,type')->from(TABLE_ZAHOST)
                ->where('oldTokenSN')->eq($token)
                ->andWhere('tokenTime')->gt(date(DT_DATETIME1, strtotime($now) - 30))->fi()
                ->fetch();
            if(empty($hostInfo->id)) return $this->sendError(400, 'Secret error.');
        }

        $isPhysicsNode = $hostInfo->type == 'node' && $hostInfo->hostType == 'physics' ? true : false;

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
                $vmData    = array(
                    'vnc'       => $vm->vncPortOnHost,
                    'zap'       => $vm->agentPortOnHost,
                    'ztf'       => $vm->ztfPortOnHost,
                    'zd'        => $vm->zdPortOnHost,
                    'ssh'       => $vm->sshPortOnHost,
                    'status'    => $vm->status,
                    'extranet'  => $vm->ip,
                    'heartbeat' => helper::now()
                );

                if(!$vm->sshPortOnHost) unset($vmData['ssh']);
                if($isPhysicsNode)
                {
                    unset($vmData['extranet']);
                    $this->dao->update(TABLE_ZAHOST)->data($vmData)->where('id')->eq($hostInfo->id)->exec();
                }
                else
                {
                    $node = $this->loadModel('zanode')->getNodeByMac($vm->macAddress);
                    if(empty($node)) continue;

                    if(in_array($node->status, array('restoring', 'creating_img', 'creating_snap')))
                        $vmData['status'] = $vm->status = $node->status;
                    $this->dao->update(TABLE_ZAHOST)->data($vmData)->where('mac')->eq($vm->macAddress)->exec();
                }

                if($vm->status == 'running' && !$isPhysicsNode)
                {
                    if(!empty($node))
                    {
                        $snaps = $this->loadModel('zanode')->getSnapshotList($node->id);
                        if(empty($snaps))
                        {
                            if($vm->status == 'running') $this->loadModel('zanode')->createDefaultSnapshot($node->id);
                        }
                    }
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
