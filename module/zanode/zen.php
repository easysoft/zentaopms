<?php
declare(strict_types=1);
/**
 * The zen file of zanode module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     admin
 * @link        https://www.zentao.net
 */
class zanodeZen extends zanode
{
    /**
     * 操作执行节点。
     * Handle node.
     *
     * @param  int    $nodeID
     * @param  string $type boot|destroy|suspend|reboot|resume
     * @return void
     */
    protected function handleNode(int $nodeID, string $type): void
    {
        $node = $this->getNodeByID($nodeID);

        if(in_array($node->status, array('restoring', 'creating_img', 'creating_snap')))
        {
            return $this->sendError(sprintf($this->lang->zanode->busy, $this->lang->zanode->statusList[$node->status]), true);
        }

        $url    = 'http://' . $node->ip . ':' . $node->hzap . '/api/v1/kvm/' . $node->name . '/' . $type;
        $param  = array('vmUniqueName' => $node->name);
        $result = commonModel::http($url, $param, array(), array("Authorization:$node->tokenSN"), 'data', 'POST', 10);
        $result = json_decode($result, true);

        if(empty($result)) return $this->sendError($this->lang->zanode->notFoundAgent, true);

        if($result['code'] != 'success') return $this->sendError(zget($this->lang->zanode->apiError, $result['code'], $result['msg']), true);

        if($type != 'reboot')
        {
            $status = $type == 'suspend' ? 'suspend' : 'running';
            if($type == 'destroy') $status = 'shutoff';

            $this->dao->update(TABLE_ZAHOST)->set('status')->eq($status)->where('id')->eq($nodeID)->exec();
        }
        $this->loadModel('action')->create('zanode', $nodeID, ucfirst($type));
        return $this->sendSuccess(array('message' => $this->lang->zanode->actionSuccess, 'load' => true));
    }
}
