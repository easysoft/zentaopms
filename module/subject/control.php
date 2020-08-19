<?php
class subject extends control
{
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
