<?php
/**
 * The control file of subject currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     subject
 * @version     $Id: control.php 5107 2013-07-12 01:46:12Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class subject extends control
{
    /**
     * Browse subjects
     *
     * @param  int    $currentModuleID
     * @access public
     * @return void
     */
    public function browse($currentModuleID = 0)
    {
        $this->view->title           = $this->lang->subject->manage;
        $this->view->position[]      = $this->lang->subject->manage;
        $this->view->modules         = $this->loadModel('tree')->getTreeMenu(0, 'subject', 0, array('treeModel', 'createManageLink'));
        $this->view->sons            = $this->tree->getSons(0, $currentModuleID, 'subject');
        $this->view->currentModuleID = $currentModuleID;
        $this->view->tree            = $this->tree->getProductStructure(0, 'subject');
        $this->view->parentModules   = $this->tree->getParents($currentModuleID);
        $this->display();
    }
}
