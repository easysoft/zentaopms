<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createProgramTester extends tester
{
    /**
     * 删除项目集。
     *
     * @param  string $programName
     * @param  array  $whitelist
     * @access public
     * @return void
     */
    public function deleteProgram($programName)
    {
        /*删除列表第一个项目集*/
        $this->openUrl('program', 'browse');
        $browsePage = $this->loadPage('program', 'browse');
        $browsePage->dom->fstdeleteBtn->click();
        $browsePage->dom->undeleteConfirm->click();
        $browsePage->dom->fstdeleteBtn->click();
        $browsePage->dom->deleteConfirm->click();
        $browsePage->wait(1);
        $browsePage->dom->search(array("项目集名称,=,{$programName->name}"));
        $browsePage->wait(1);

        if($browsePage->dom->formText->getText() != '暂时没有项目集') return $this->failed('删除项目集失败');
        return $this->success('删除项目集成功');
    }
}
