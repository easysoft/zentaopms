<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createProgramTester extends tester
{
    /**
     * 关闭项目集。
     *
     * @param  string $programName
     * @param  array  $whitelist
     * @access public
     * @return void
     */
    public function closeProgram($programName)
    {
        $this->openUrl('program', 'browse');
        $browsePage = $this->loadpage('program', 'browse');
        $browsePage->dom->fstStartBtn->click();
        $browsePage->wait(1);
        $browsePage->dom->startProgramBtn->click();
        $browsePage->wait(1);
        $browsePage->dom->fstStartBtn->click();
        $browsePage->wait(2);
        $browsePage->dom->startProgramBtn->click();
        $browsePage->wait(1);
        $browsePage->dom->search(array("项目集名称,=,{$programName->name}"));
        $browsePage->wait(1);

        if($browsePage->dom->fstStatus->getText() != '已关闭') return $this->failed('关闭项目集失败');
        return $this->success('关闭项目集成功');
    }
}
