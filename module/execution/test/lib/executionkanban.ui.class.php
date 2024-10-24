<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class executionkanbanTester extends tester
{
    /*
     * 检查执行看板下数据。
     * Check data in executionkanban.
     *
     * @param  array  $nums
     * @access public
     * @return object
     */
    public function checkNums($nums)
    {
        $form = $this->initForm('execution', 'executionkanban', '', 'appIframe-execution');
        if($form->dom->wait->getText() != $nums[0])     return $this->failed('未开始的执行列统计错误');
        if($form->dom->doing->getText(2) != $nums[1])   return $this->failed('进行中的执行列统计错误');
        if($form->dom->suspend->getText(3) != $nums[2]) return $this->failed('已挂起的执行列统计错误');
        if($form->dom->closed->getText(4) != $nums[3])  return $this->failed('已关闭的执行列统计错误');
        return $this->success('执行看板各列数据统计正确');
    }
}
