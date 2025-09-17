<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class createProgramTester extends tester
{
    /**
     * 删除项目集。
     *
     * @param  string $num
     * @access public
     * @return void
     */
    public function deleteProgram($num)
    {
        $this->openUrl('program', 'browse');
        $browsePage = $this->loadPage('program', 'browse');
        $browsePage->dom->allTab->click();
        $browsePage->wait(2);
        $browsePage->dom->fstdeleteBtn->click();
        $browsePage->wait(2);
        $browsePage->dom->confirm->click();
        $browsePage->wait(2);
        $allTabNum = $browsePage->dom->allTabNum->getText();//全部tab旁的数字
        return ($num == $allTabNum)
            ? $this->success('删除项目集成功')
            : $this->failed('删除项目集失败');
    }
}
