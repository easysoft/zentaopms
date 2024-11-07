<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class dynamicTester extends tester
{
    /**
     * 检查执行动态。
     * Check the dynamic of execution.
     *
     * @param  string $num
     * @access public
     * @return void
     */
    public function checkTotalNum($num)
    {
        $form = $this->initForm('execution', 'dynamic', array('execution' => '3'), 'appIframe-execution');
        if($form->dom->num->getText !== $num)  return $this->failed('执行动态数量不正确');
        return $this->success('执行动态数量正确');
    }
