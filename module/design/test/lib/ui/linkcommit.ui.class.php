<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class linkCommitTester extends tester
{
    /**
     * 检查关联提交。
     * Check link commit.
     *
     * @param  string
     * @param  string $expectNum
     * @param  array  $design
     * @access public
     * @return object
     */
    public function linkCommit($expectNum, $design)
    {
        $form = $this->initForm('design', 'browse', array('projecID' => 1), 'appIframe-project');
        $form->dom->viewCommitBtn->click();
        $form->wait(1);
        $form->dom->linkCommitBtn->click();
        if(isset($design['begin'])) $form->dom->begin->datePicker($design['begin']);
        if(isset($design['end']))   $form->dom->end->datePicker($design['end']);
        $form->wait(1);
        $form->dom->selectAllBtn->click();
        $form->dom->saveBtn->click();
        $form->wait(3);
        $form->dom->viewCommitBtn->click();
        $form->wait(3);
        $string    = $form->dom->commitNum->getText();
        $commitNum = preg_replace('/\D/', '', $string); //从字符串中提取数字部分
        if($commitNum == $expectNum) return $this->success('关联提交成功');
        return $this->failed('关联提交失败');
    }
}
