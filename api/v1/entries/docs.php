<?php
/**
 * The docs entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class docsEntry extends Entry
{
    /**
     * GET method.
     *
     * @access public
     * @return void
     */
    public function get($libID = 0)
    {
        if(empty($libID)) $libID = $this->param('lib', 0);
        if(empty($libID)) return $this->sendError(400, 'Need lib id.');

        $docTree = $this->loadModel('doc')->getDocTree($libID);

        foreach($docTree as $i => $module)
        {
            if(empty($module->id))
            {
                unset($docTree[$i]);
                foreach($module->children as $doc) $docTree[] = $doc;
            }
        }

        return $this->send(200, array('docs' => array_values($docTree)));
    }
}
