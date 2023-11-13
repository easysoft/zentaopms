<?php
declare(strict_types=1);
/**
 * The delete control file of client module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     client
 * @link        https://www.zentao.net
 */
class client extends control
{
    /**
     * 删除一个客户端版本。
     * Delete a client version.
     *
     * @param  int    $clientID
     * @access public
     * @return void
     */
    public function delete(int $clientID)
    {
        $this->dao->delete()->from(TABLE_IM_CLIENT)->where('id')->eq($clientID)->exec();
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success', 'load' => true));
    }
}
