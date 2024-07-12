<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createWaterfallTester extends tester
{
    /**
     * Check the page jump after creating the project.
     *
     * @param  array    $waterfall
     * @access public
     * @return object
     */
   public function checkLocating(array $waterfall)
    {
        $form         = $this->initForm('project', 'create', array('model' => 'waterfall'));
        $categoryLang = (array)$this->lang->project->projectTypeList;
        $form->dom->btn($categoryLang[0])->click();
        $form->dom->name->setValue($waterfall['name']);
        $form->dom->end->datePicker($waterfall['end']);
        $form->dom->PM->picker($waterfall['PM']);
        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);
        return $this->response();
    }

 /**
     * Create a default project.
     *
     * @param  arrary    $waterfall
     * @access public
     * @return object
     */
    public function createDefault(array $waterfall)
    {
        $form = $this->initForm('project', 'create', array('model' => 'waterfall'));
        $form->dom->name->setValue($waterfall['name']);
        $form->dom->longTime->click();
        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        if($this->response('module') != 'programplan')
        {
            if($this->checkFormTips('project')) return $this->success('创建瀑布项目表单页提示信息正确');
            return $this->failed('创建瀑布项目表单页提示信息不正确');
        }

       /* 跳转到项目设置页面，点击设置菜单。 */
        $programplanPage = $this->loadPage('programplan', 'create');
        $programplanPage->dom->settings->click();

        $viewPage = $this->loadPage('project', 'view');
        if($viewPage->dom->projectName->getText() != $waterfall['name']) return $this->failed('名称错误');
        $categoryLang = (array)$this->lang->project->projectTypeList;
        if($viewPage->dom->category->getText() != $categoryLang['1']) return $this->failed('类型错误');
        if($viewPage->dom->acl->getText() != $this->lang->project->shortAclList->open) return $this->failed('权限错误');

        return $this->success();
    }

}
