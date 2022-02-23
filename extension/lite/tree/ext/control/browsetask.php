<?php
helper::importControl('tree');
class myTree extends tree
{
    public function browseTask($rootID, $productID = 0, $currentModuleID = 0)
    {
        $this->lang->kanban->menu->execution['subModule'] = 'tree';
        $this->lang->kanban->menu->story['subModule']     = '';
        return parent::browseTask($rootID, $productID, $currentModuleID);
    }
}
