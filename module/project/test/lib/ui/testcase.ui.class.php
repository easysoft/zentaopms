<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class testcaseTester extends tester
{
    /**
     * 检查Tab标签下的数据。
     * Check the data of Tab tag.
     *
     * @param  string $tab allTab|waitingTab|storyChangedTab|storyNoCaseTab
     * @param  string $expectNum
     * @access public
     * @return object
     */
    public function checkTab($tab, $expectNum)
    {
        $form = $this->initForm('project', 'testcase', array('project' => 1), 'appIframe-project');
        $form->dom->$tab->click();
        $form->wait(1);
        $target = ($tab == "storyNoCaseTab") ? $form->dom->zerocaseNum : $form->dom->testcaseNum;
        $actualNum = preg_replace('/\D/', '', $target->getText());
        if ($actualNum == $expectNum) return $this->success($tab . '下显示用例数正确');
        return $this->failed($tab . '下显示用例数不正确');
    }

    /**
     * 切换产品查看用例数据。
     * Switch product to view testcase data.
     *
     * @param  string $product firstProduct|secondProduct
     * @param  string $expectNum
     * @access public
     * @return object
     */
    public function switchProduct($product, $expectNum)
    {
        $form = $this->initForm('project', 'testcase', array('project' => 1), 'appIframe-project');
        $form->dom->dropMenu->click();
        $form->dom->$product->click();
        $form->wait(1);
        $string      = $form->dom->testcaseNum->getText();
        $testCaseNum = preg_replace('/\D/', '', $string); //从字符串中提取数字部分
        if($testCaseNum == $expectNum) return $this->success('切换' . $product . '查看用例数据成功');
        return $this->failed('切换' . $product . '查看用例数据失败');
    }
}
