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
     * @return object
     */
    public function checkTotalNum($num)
    {
        $form = $this->initForm('execution', 'dynamic', array('execution' => '3'), 'appIframe-execution');
        if($form->dom->num->getText() != $num)  return $this->failed('执行动态数量不正确');
        return $this->success('执行动态数量正确');
    }

    /**
     * 按用户筛选执行动态。
     * Check the execution dynamic groupby user.
     *
     * @param  string $user
     * @param  string $num
     * @access public
     * @return object
     */
    public function checkNumByUser($user, $num)
    {
        $form = $this->initForm('execution', 'dynamic', array('execution' => '3', 'user' => $user), 'appIframe-execution');
        $form->dom->user->picker($user);
        $form->wait(1);
        if(count($form->dom->getElementList($form->dom->xpath['detailNum'])->element) != $num) return $this->failed('按用户筛选动态数据错误');
        return $this->success('按用户筛选动态数据正确');
    }
}
