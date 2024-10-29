<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class bugTester extends tester
{
    /**
     * 检查Tab标签下的数据。
     * Check the data of Tab tag.
     *
     * @param  string $tab allTab|unresolvedTab
     * @param  string $expectNum
     * @access public
     * @return object
     */
    public function checkTab($tab, $expectNum)
    {
        $form = $this->initForm('project', 'bug', array('project' => 1), 'appIframe-project');
        $form->dom->$tab->click();
        $form->wait(1);
        if($form->dom->bugNum->getText() == $expectNum) return $this->success($tab . '下显示条数正确');
        return $this->failed($tab . '下显示条数不正确');
    }
    public function switchProduct($product, $expectNum)
    {
        $form = $this->initForm('project', 'bug', array('project' => 1), 'appIframe-project');
        $form->dom->dropMenu->click();
        $form->dom->$product->click();
        $form->wait(1);
        if($form->dom->bugNum->getText() == $expectNum) return $this->success('切换' . $product . '查看数据成功');
        return $this->failed('切换' . $product . '查看数据失败');
    }
}
