<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class editProjectTester extends tester
{
 /**
     * Edit a project.
     *
     * @param  array  $project
     * @access public
     * @return object
     */
    public function editProject(array $project)
    {
        $form = $this->initForm('project', 'edit', array('projecID' => 1), 'appIframe-project');
        $form->dom->name->setValue($project['name']);
        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        $viewPage = $this->loadPage('project', 'view');
        if($viewPage->dom->projectName->getText() != $project['name']) return $this->failed('名称错误');
        $categoryLang = (array)$this->lang->project->projectTypeList;
        if($viewPage->dom->category->getText() != $categoryLang['1']) return $this->failed('类型错误');
        if($viewPage->dom->acl->getText() != $this->lang->project->shortAclList->open) return $this->failed('权限错误');

        return $this->success('编辑项目成功');
    }

}
