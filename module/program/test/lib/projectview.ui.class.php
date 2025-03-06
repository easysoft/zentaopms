<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createProgramTester extends tester
{
    /**
     * 项目视角下创建项目。
     * Create a project in the module of program.
     *
     * @param  string $programName
     * @access public
     * @return void
     */
    public function createProgramProject($programs, $projects)
    {
        /*提交表单*/
        $this->openUrl('program', 'browse');
        $form = $this->loadPage('program', 'browse');
        $form->dom->addProject->click();
        $form->wait(1);
        $form = $this->initForm('project', 'create', array('model' => 'scrum'));

        /*创建项目集下的项目*/
        $form->dom->parent->picker($programs->program);
        $form->dom->hasProduct0->click();
        $form->dom->name->setValue($projects->programProject);
        $form->dom->longTime->click();
        $form->dom->btn($this->lang->save)->click();

        /*检查项目集下的项目是否创建成功*/
        $this->openUrl('program', 'browse');
        $form = $this->loadPage('program', 'browse');
        $form->wait(1);
        $form->dom->programName->click();
        $form->wait(1);

        $form->dom->switchToIframe('');
        $form->dom->switchToIframe('appIframe-project');
        $form->dom->allTab->click();
        if($form->dom->fstProgram->getText() != $programs->programProject) return $this->failed('项目视角下创建项目失败');
        $this->openUrl('program', 'project');
        return $this->success('项目视角下创建项目成功');
    }
}
