<?php
/**
 * The file entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class fileEntry extends Entry
{
    /**
     * PUT method.
     *
     * @access public
     * @return void
     */
    public function put($fileID)
    {
        $uid = $this->param('uid', '');
        $action = $this->param('action', '');
        if($action == 'remove') unset($_SESSION['album']['used'][$uid][$fileID]);

        $this->send(200, array('id' => $fileID));
    }
}
