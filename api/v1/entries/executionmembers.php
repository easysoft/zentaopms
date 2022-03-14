<?php
/**
 * The execution cases entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class executionMembersEntry extends entry
{
    public function get($executionID = 0)
    {
        if(!$executionID) $executionID = $this->param('execution', 0);
        if(empty($executionID)) return $this->sendError(400, 'Need execution id.');
        $members = $this->loadModel('execution')->getTeamMembers($executionID);
        if(isset($members))
        {
            foreach($members as $member) $result[] = $this->format($member, 'join:date');
        }
        return $this->send(200, array('members' => $result));
    }
}
