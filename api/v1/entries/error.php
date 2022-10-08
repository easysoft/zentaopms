<?php
/**
 * The error entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class errorEntry extends Entry 
{
    /**
     * 404 Not Found.
     *
     * @access public
     * @return void
     */
    public function notFound()
    {
        $this->send(404, array('error' => 'not found'));
    }
}
