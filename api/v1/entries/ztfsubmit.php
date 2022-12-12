<?php
/**
 * The host entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao <zhaoke@easycorp.ltd>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class ztfSubmitEntry extends baseEntry
{
    /**
     * Listen ztf task finish submit.
     *
     * @access public
     * @return void
     */
    public function post()
    {
        /* Check authorize. */
        $header = getallheaders();
        $token  = isset($header['Authorization']) ? substr($header['Authorization'], 7) : '';
        if(!$token) return $this->sendError(401, 'Unauthorized');

        $now    = helper::now();

        $this->dao = $this->loadModel('common')->dao;
        $id = $this->dao->select('id')->from(TABLE_ZAHOST)
            ->where('tokenSN')->eq($token)
            ->andWhere('tokenTime')->gt($now)->fi()
            ->fetch('id');
            
        if(!$id) return $this->sendError(400, 'Secret error.');

        $post = file_get_contents('php://input');
        $post = json_decode($post);
        $resultID = $post->taskId;

        $this->loadModel('testresult');

        if($resultID) $this->dao->update(TABLE_TESTRESULT)->set('ZTFResult')->eq(json_encode($post))->where('id')->eq($resultID)->exec();

        return $this->sendSuccess(200, 'success');
    }
}
