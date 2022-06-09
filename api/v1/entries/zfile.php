<?php
/**
 * The file entry point for yueku.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class zfileEntry extends entry
{
    /**
     * GET method.
     *
     * @param  string $fileID
     * @access public
     * @return void
     */
    public function get($fileID)
    {
        $now = gmdate("Y-m-d\TH:i:s\Z");

        $info = new stdclass();
        $info->id           = $fileID;
        $info->name         = '';
        $info->type         = 'file';
        $info->parentID     = '';
        $info->size         = 1000000;
        $info->createdTime  = $now;
        $info->accessedTime = $now;
        $info->editedTime   = $now;
        $info->modifiedTime = $now;

        $this->send(200, $info);
    }
}
