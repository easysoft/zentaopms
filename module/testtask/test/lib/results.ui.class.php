<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
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
        $form->dom->xpath['stepResult'] = "//*[@class='modal-content']//tbody/tr[" . (2 * $num) . "]/td/form/div[1]/div[2]/div/div[4]";
        $form->dom->xpath['stepReal']   = "//*[@class='modal-content']//tbody/tr[" . (2 * $num) . "]/td/form/div[1]/div[2]/div/div[5]";

        $form->dom->resultsBtn->click();
        $form->wait(1);
        /* 结果弹窗默认打开第一个结果详情，其他结构详情手动点击展开 */
        if($num !=1) $form->dom->item->click();
        $form->wait(1);

        /* 从测试结果中提取相关信息 */
        $result = str_replace('，', ' ', $form->dom->results->getText()); // 将中文逗号替换为空格
        $result = str_replace('。', ' ', $result);                        // 将中文句号替换为空格
        $param  = preg_split('/\s+/', $result);                           // 按空格拆分字符串
    }
}
