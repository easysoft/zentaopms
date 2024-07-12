<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createScrumTester extends tester
{
    /**
     * Check the page jump after creating the product.
     *
     * @param  string $scrum
     * @access public
     * @return object
     */
    public function checkLocating($scrum = array())
    {
        $form = $this->initForm('project', 'create', array('model' => 'scrum'));
        $form->dom->noproduct->click();
        $form->dom->name->setValue($scrum['noproduct']);
        $form->dom->end->datePicker($scrum['end']);
        $form->dom->PM->picker($scrum['PM']);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);
        return $this->response();
    }

    /**
     * Create the scrum project.
     *
     * @param  string $scrum
     * @access public
     * @return object
     */
    public function createScrum($scrum = array())
    {
        $form = $this->initForm('project', 'create', array('model' => 'scrum'));
        $form->dom->name->setValue($scrum);
        $form->dom->longTime->click();
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);
        if($this->response('method') != 'browse')
        {
            if($this->checkFormTips('project')) return $this->success('创建敏捷项目表单页提示信息正确');
            return $this->failed('创建敏捷项目表单页提示信息不正确');
        }

        /* 跳转到项目列表页面，点击列表页项目名称进入项目，再点击设置菜单查看项目概况页面。 */
        $browsePage = $this->loadPage('project', 'browse');
        $browsePage->dom->scrumName->click();
        $browsePage->dom->settings->click();
        $viewPage = $this->loadPage('project', 'view');
        $form->wait(1);

        if($viewPage->dom->viewProjectName->getText() != $scrum) return $this->failed('名称错误');
        $categoryLang = (array)$this->lang->project->projectTypeList;
        if($viewPage->dom->category->getText() != $categoryLang['1']) return $this->failed('类型错误');

        return $this->success();
    }
}
