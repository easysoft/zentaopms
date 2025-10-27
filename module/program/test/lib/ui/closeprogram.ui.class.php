<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class createProgramTester extends tester
{
    /**
     * 关闭项目集。
     *
     * @access public
     * @return void
     */
    public function closeProgram()
    {
        $this->openUrl('program', 'browse');
        $browsePage = $this->loadpage('program', 'browse');
        $browsePage->dom->allTab->click();
        $browsePage->wait(2);
        $browsePage->dom->fstStartBtn->click();
        $browsePage->wait(3);
        $browsePage->dom->startProgramBtn->click();
        $browsePage->wait(3);
        if($browsePage->dom->fstStatus->getText() != '已关闭') return $this->failed('关闭项目集失败');
        return $this->success('关闭项目集成功');
    }
}
