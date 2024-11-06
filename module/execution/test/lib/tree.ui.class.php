<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class treeTester extends tester
{
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
        $form->dom->detail->click();
        $form->wait(1);
        if($form->dom->detail->getText() == $form->dom->title->getText()) return $this->success('详情数据正确');
        return $this->failed('详情数据错误');
    }
}
