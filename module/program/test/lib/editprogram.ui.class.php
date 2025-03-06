<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createProgramTester extends tester
{
    /**
     * 编辑项目集。
     *
     * @param  string $editName
     * @param  array  $whitelist
     * @access public
     * @return void
     */
    public function editProgram($editName)
    {
        $form = $this->initForm('program', 'edit', array('programID' => '1'));
        $form->dom->name->setValue($editName->name);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        $this->openUrl('program', 'browse');
        $browsePage = $this->loadPage('program', 'browse');
        $browsePage->dom->search(array("项目集名称,=,{$editName->name}"));
        $browsePage->wait(1);
        if($browsePage->dom->fstProgramName->getText() != $editName->name) return $this->failed('编辑项目集失败');
        return $this->success('编辑项目集成功');
    }
}
