<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class treeTester extends tester
{
    /**
     * 检查树状图数据。
     * Check tree data.
     *
     * @param  string $level
     * @param  string $num
     * @param  bool   $click
     * @access public
     * @return object
     */
    public function checkTreeData($level, $num, $click = false)
    {
        $form = $this->initForm('execution', 'tree', array('execution' => '2'), 'appIframe-execution');
        $form->wait(2);
        if($click) $form->dom->onlyStoryBtn->click();
        $form->wait(1);
        if(count($form->dom->getElementList($form->dom->xpath[$level])->element) != $num) return $this->failed('数据错误');
        return $this->success('数据正确');
    }

    /**
     * 检查树状图中的任务、需求详情显示。
     * Check detail data.
     *
     * @access public
     * @return object
     */
    public function checkDetail()
    {
        $form = $this->initForm('execution', 'tree', array('execution' => '2'), 'appIframe-execution');
        $form->wait(1);
        $form->dom->detail->click();
        $form->wait(1);
        if($form->dom->detail->getText() == $form->dom->title->getText()) return $this->success('详情数据正确');
        return $this->failed('详情数据错误');
    }
}
