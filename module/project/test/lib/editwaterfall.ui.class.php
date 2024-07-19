<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class editWaterfallTester extends tester
{
 /**
     * Edit a waterfall project.
     *
     * @param  array    $waterfall
     * @access public
     * @return object
     */
    public function editWaterfall(array $waterfall)
    {
        $form = $this->initForm('project', 'edit', array('projecID' => 5), 'appIframe-project');
        $form->dom->name->setValue($waterfall['name']);
        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        $viewPage = $this->loadPage('project', 'view');
        if($viewPage->dom->projectName->getText() != $waterfall['name']) return $this->failed('名称错误');
        $categoryLang = (array)$this->lang->project->projectTypeList;
        if($viewPage->dom->category->getText() != $categoryLang['1']) return $this->failed('类型错误');
        if($viewPage->dom->acl->getText() != $this->lang->project->shortAclList->open) return $this->failed('权限错误');

        return $this->success('编辑项目成功');
    }

}
