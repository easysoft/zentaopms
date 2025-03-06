<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createProgramTester extends tester
{
    /**
     * 创建项目集。
     * Create a default program.
     *
     * @param  string $programName
     * @access public
     * @return void
     */
    public function createDefault(string $programName)
    {
        /* 提交表单。 */
        $form = $this->initForm('program', 'create');
        $form->dom->name->setValue($programName);
        $form->dom->longTime->click();
        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);
        if($this->response('method') != 'browse')
        {
            if($this->checkFormTips('program')) return $this->success('创建项目集表单页提示信息正确');
            return $this->failed('创建项目集表单页提示信息不正确');
        }

        /* 跳转到项目集列表页面。 */
        $browsePage = $this->loadPage('program', 'browse');
        $browsePage->dom->search(array("项目集名称,=,{$programName}"));
        $form->wait(1);

        if($browsePage->dom->programName->getText() != $programName) return $this->failed('创建项目集后项目集列表页没有显示项目集名称');
        if($browsePage->dom->endDate->getText() != $this->lang->program->longTime) return $this->failed('创建项目集后计划完成日期不正确');
        $this->openUrl('program', 'browse');

        return $this->success('创建项目集成功');
    }

    /**
     * 创建私有项目集。
     *
     * @param  string $programName
     * @param  array  $whitelist
     * @access public
     * @return void
     */
    public function createPrivate(string $programName, array $whitelist = array())
    {
        /* 提交表单。 */
        $form = $this->initForm('program', 'create');
        $form->dom->name->setValue($programName);
        $form->dom->longTime->click();
        $form->dom->aclprivate->click();
        if(!empty($whitelist))
        {
            $form->scroll(500);
            $form->dom->whitelist->multiPicker($whitelist);
        }

        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();

        /* 跳转到项目集列表页面。 */
        $form->wait(1);
        $browsePage = $this->loadPage('program', 'browse');
        $browsePage->dom->search(array("项目集名称,=,{$programName}"));
        $form->wait(1);
        if($browsePage->dom->programName->getText() != $programName) return $this->failed('创建项目集后项目集列表页没有显示项目集名称');

        $browsePage->dom->programName->click();
        $browsePage->dom->personnelNav->click();
        $browsePage->dom->whitelistNav->click();
        if($browsePage->dom->whitelistUser->getText() != end($whitelist)) return $this->failed('创建项目集后白名单列表没有显示白名单用户');

        return $this->success('创建私有项目集成功');
    }

    /**
     * 添加子项目集。
     *
     * @param  string $programName
     * @param  string childProgram
     * @access public
     * @return void
     */
    public function addChildProgram($programName, $childProgram)
    {
        $browsePage = $this->initForm('program', 'browse');
        $browsePage->dom->search(array("项目集名称,=,{$programName}"));
        $browsePage->wait(1);
        $browsePage->dom->addChildBtn->click();
        $browsePage->wait(1);
        $browsePage->dom->name->setValue($childProgram->name);
        $browsePage->dom->longTime->click();
        $browsePage->wait(1);
        $browsePage->dom->btn($this->lang->save)->click();
        $browsePage->wait(1);

        $this->openUrl('program', 'browse');
        $browsePage = $this->loadPage('program', 'browse');
        $browsePage->dom->search(array("项目集名称,=,{$childProgram->name}"));
        $browsePage->wait(1);
        $browsePage->dom->fstEditBtn->click();
        $browsePage->wait(1);
        if($browsePage->dom->parent->getText() != $programName) return $this->failed('添加子项目集失败');
        return $this->success('添加子项目集成功');
    }
}
