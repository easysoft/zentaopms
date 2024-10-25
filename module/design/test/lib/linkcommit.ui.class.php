<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class linkCommitTester extends tester
{
    /**
     * 检查关联提交。
     * Check link commit.
     *
     * @param  string
     * @param  string $expectNum
     * @access public
     * @return object
     */
    public function linkCommit($expectNum)
    {
        $form = $this->initForm('design', 'browse', array('projecID' => 1), 'appIframe-project');
        $form->dom->viewCommitBtn->click();
        $form->wait(1);
        $form->dom->linkCommitBtn->click();
        $form->dom->selectAllBtn->click();
        $form->dom->saveBtn->click();
        $form->dom->viewCommitBtn->click();
        $string    = $form->dom->commitNum->getText();
        $commitNum = preg_replace('/\D/', '', $string); //从字符串中提取数字部分
        if($commitNum == $expectNum) return $this->success('关联提交成功');
        return $this->failed('关联提交失败');
    }
}
