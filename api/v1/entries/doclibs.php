<?php
/**
 * The doclibs entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class doclibsEntry extends Entry
{
    /**
     * GET method.
     *
     * @access public
     * @return void
     */
    public function get()
    {
        $type     = $this->param('type', 0);
        $objectID = $this->param('objectID', 0);

        $libs   = $this->loadModel('doc')->getLibs($type, $this->param('extra', ''), $this->param('appendLibs', ''), $objectID);
        $result = array();
        foreach($libs as $libID => $libName)
        {
            $lib = new stdclass();
            $lib->id   = $libID;
            $lib->name = $libName;
            $result[] = $lib;
        }
        krsort($result);

        return $this->send(200, array('libs' => array_values($result)));
    }
}
