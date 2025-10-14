<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class resultsTester extends tester
{
    /**
     * 检查测试单下测试用例结果。
     * Check results of testcase.
     *
     * @param  int    $num  第几个结果
     * @Param  array  $task 测试单相关信息
     * @access public
     * @return void
     */
    public function checkResults($num = 1, $task)
    {
        $form = $this->initForm('testtask', 'cases', array('taskID'=> '1'), 'appIframe-qa');
        /* 测试单下第一个用例后的结果按钮 */
        $form->dom->xpath['resultsBtn']= "(//*[@id='table-testtask-cases']//nav[@class='toolbar'])[1]/a[@title='{$this->lang->testtask->results}']";
        /* 测试结果弹窗中结果和具体信息 */
        $form->dom->xpath['item']       = "//*[@class='modal-content']//tbody/tr[" . (2 * $num - 1) . "]";
        $form->dom->xpath['results']    = $form->dom->xpath['item'] . "/td";
        $form->dom->xpath['detail']     = "//*[@class='modal-content']//tbody/tr[" . (2 * $num) . "]/td/form/div[1]";
        $form->dom->xpath['stepResult'] = $form->dom->xpath['detail'] . "/div[2]/div/div[4]";
        $form->dom->xpath['stepReal']   = $form->dom->xpath['detail'] . "/div[2]/div/div[5]";
        $form->dom->xpath['bug']        = $form->dom->xpath['detail'] . "//button/span";

        $form->dom->resultsBtn->click();
        $form->wait(1);
        /* 结果弹窗默认打开第一个结果详情，其他结构详情手动点击展开 */
        if($num !=1) $form->dom->item->click();
        $form->wait(1);

        /* 从测试结果中提取相关信息 */
        $result = str_replace('，', ' ', $form->dom->results->getText()); // 将中文逗号替换为空格
        $result = str_replace('。', ' ', $result);                        // 将中文句号替换为空格
        $param  = preg_split('/\s+/', $result);                           // 按空格拆分字符串
        /* 根据系统语言选择测试结果和信息 */
        if($this->config->default->lang == 'zh-cn')
        {
            $taskResult = $task['resultcn'];
            $stepResult = $task['sResultcn'];
        }
        else
        {
            $taskResult = $task['resulten'];
            $stepResult = $task['sResulten'];
        }
        /* 校验测试结果 */
        if($param[4] != $task['user'])  return $this->failed('用例执行人错误');
        if($param[6] != $task['name'])  return $this->failed('测试单名称错误');
        if($param[8] != $task['build']) return $this->failed('构建错误');
        if($param[10] != $taskResult)   return $this->failed('测试结果错误');
        /* 校验测试步骤结果 */
        if($form->dom->stepResult->getText() != $stepResult)     return $this->failed('测试步骤结果错误');
        if($form->dom->stepReal->getText() != $task['stepReal']) return $this->failed('测试步骤实际情况错误');
        if($stepResult == 'fail')
        {
            if(!is_object($form->dom->bug) || $form->dom->bug->getText() != $this->lang->testtask->createBug) return $this->failed('失败的用例没有显示提bug按钮或按钮名称错误');
        }
        return $this->success('测试结果正确');
    }
}
