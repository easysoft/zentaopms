<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class editProjectReleaseTester extends tester
{
    /**
     * Check the page input when editing the project release.
     * 编辑项目发布时检查页面输入
     *
     * @param  array $release
     * @access public
     */
    public function editRelease($release)
    {
        $browseForm = $this->initForm('projectrelease', 'browse', array('project' => 1), 'appIframe-project');
        $browseForm->dom->editBtn->click();
        $form = $this->initForm('projectrelease', 'edit', array('releaseID' => 1), 'appIframe-project');

        if(isset($release['systemname'])) $form->dom->systemName->picker($release['systemname']);
        if(isset($release['name']))       $form->dom->name->setValue($release['name']);
        if(isset($release['status']))     $form->dom->status->picker($release['status']);
        $form->wait(2);
        if(isset($release['plandate']))    $form->dom->date->datepicker($release['plandate']);
        if(isset($release['releasedate'])) $form->dom->releasedDate->datepicker($release['releasedate']);

        $form->dom->btn($this->lang->save)->click();
        $form->wait(2);

        /* 跳转到发布概况页面，点击基本信息标签，查看信息是否正确 */
        $viewPage = $this->loadPage('projectrelease', 'view');
        $viewPage->dom->basic->click();
        $viewPage->wait(2);

        //断言检查应用名称、发布名称、状态是否正确
        if($viewPage->dom->basicSystemName->getText() != $release['systemname']) return $this->failed('应用名称错误');
        if($viewPage->dom->basicreleasename->getText() != $release['name'])      return $this->failed('项目发布名称错误');
        if($viewPage->dom->basicstatus->getText() != $release['status'])         return $this->failed('项目发布状态错误');

        return $this->success('编辑项目发布成功');
    }
}
