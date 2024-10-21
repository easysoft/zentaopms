<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class viewCommitTester extends tester
{
    /**
     * 检查查看提交页面数据。
     * Check the data of viewcommit page.
     *
     * @param  string
     * @param  string $expectNum
     * @access public
     * @return object
     */
    public function viewCommit($expectNum)
    {
        $form = $this->initForm('design', 'browse', array('projecID' => 1), 'appIframe-project');
        $form->dom->viewCommitBtn->click();
        $form->wait(1);
        $string    = $form->dom->commitNum->getText();
        $commitNum = preg_replace('/\D/', '', $string); //从字符串中提取数字部分
        if($commitNum == $expectNum) return $this->success('查看提交页显示数据正确');
        return $this->failed('查看提交页显示数据不正确');
    }
}
