<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class taskkanbanTester extends tester
{
    /**
     * 检查看板数据。
     * Check kanban.
     *
     * @param  string $col
     * @param  string $num
     * @param  string $nameId
     * @param  string $groupId
     * @access public
     * @return void
     */
    public function checkKanban($col, $num, $nameId = '', $groupId = '')
    {
        $form = $this->initForm('execution', 'taskkanban', array('execution' => '2'), 'appIframe-execution');

        if(!empty($nameId))
        {
            $form->dom->xpath['kanbanName'] = "(//menu/menu/li)[{$nameId}]";
            $form->dom->namePicker->click();
            $form->dom->kanbanName->click();
            $form->wait(1);
        }
        if(!empty($groupId))
        {
            $form->dom->xpath['kanbanGroup'] = "(//menu/menu/li)[{$groupId}]";
            $form->dom->groupPicker->click();
            $form->dom->kanbanGroup->click();
            $form->wait(1);
        }

        $form->dom->xpath['col'] = "//div[@z-col='{$col}']//div[@class='card']";
        if(count($form->dom->getElementList($form->dom->xpath['col'])->element) != $num) return $this->failed("数据错误");
        return $this->success("数据正确");
    }
}
