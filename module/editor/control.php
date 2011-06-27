<?php
/**
 * The control file of editor of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     editor
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class editor extends control
{
    /**
     * Show module files and edit them. 
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        $allModules = $this->editor->getModuleFiles();
        $this->view->tree = $this->editor->printTree($allModules);
        $this->display();
    }
}

