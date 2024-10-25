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
    }
}
