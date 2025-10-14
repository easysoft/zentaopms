<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class createProgramTester extends tester
{
    /**
     * 激活项目集。
     *
     * @access public
     * @return void
     */
    public function activateProgram()
    {
        $this->openUrl('program', 'browse');
        $browsePage = $this->loadPage('program', 'browse');
        $browsePage->dom->allTab->click();
        $browsePage->wait(2);
        $browsePage->dom->fstStartBtn->click();
        $browsePage->wait(2);
        $browsePage->dom->startProgramBtn->click();
        $browsePage->wait(2);
        if($browsePage->dom->fstStatus->getText() != '进行中') return $this->failed('激活项目集失败');
        return $this->success('激活项目集成功');
    }
}
