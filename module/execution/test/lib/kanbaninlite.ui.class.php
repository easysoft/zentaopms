<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class kanbanTester extends tester
{
    /**
     * 检查看板数据。
     * Check kanban.
     *
     * @param  string $col
     * @param  string $num
     * @param  string $groupId
     * @access public
     * @return object
     */
    public function checkKanban($col, $num, $groupId = '')
    {
        $this->switchVision('lite');
        $form = $this->initForm('execution', 'kanban', array('kanbanID' => '3'), 'appIframe-project');

        /* 选择泳道分组 */
        if(!empty($groupId))
        {
            $form->dom->xpath['kanbanGroup'] = "(//menu/menu/li)[{$groupId}]";
            $form->dom->groupPickerInLite->click();
            $form->dom->kanbanGroup->click();
            $form->wait(1);
        }

        /* 检查对应泳道下的卡片数量 */
        $form->dom->xpath['col'] = "//div[@z-col='{$col}']//div[@class='card']";
        if(count($form->dom->getElementList($form->dom->xpath['col'])->element) != $num) return $this->failed("数据错误");
        return $this->success("数据正确");
    }
}
