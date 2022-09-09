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
class hostHeartbeatEntry extends Entry
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
        $secret = $this->requestBody->secret;
        $status = $this->requestBody->status;
        $now    = helper::now();
        if(!$secret or !$status) return $this->sendError(400, 'Params error.');

        $this->dao->update(TABLE_HOST)->set('status')->eq($status)->where('secret')->eq($secret)->exec();

        $assetID = $this->dao->select('assetID')->from(TABLE_HOST)->where('secret')->eq($secret)->fetch('assetID');
        $this->dao->update(TABLE_ASSET)->set('registerDate')->eq($now)->where('id')->eq($assetID)->exec();

        $this->sendSuccess(200, 'success');
    }
}
