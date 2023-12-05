<?php
/**
 * The doclibs entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class doclibsEntry extends entry
{
    /**
     * GET method.
     *
     * @access public
     * @return string
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
