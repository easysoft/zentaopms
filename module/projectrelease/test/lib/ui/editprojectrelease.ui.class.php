<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class editProjectReleaseTester extends tester
{
    /**
     * Edit project release.
     * 编辑项目发布
     *
     * @param  array $release
     * @access public
     */
    public function editRelease($release)
    {
        $browseForm = $this->initForm('projectrelease', 'browse', array('project' => 1), 'appIframe-project');
        $browseForm->dom->editBtn->click();
        $form = $this->initForm('projectrelease', 'edit', array('releaseID' => 1), 'appIframe-project');

        if(isset($release['systemname'])) $form->dom->system->picker($release['systemname']);
        if(isset($release['name']))       $form->dom->name->setValue($release['name']);
        if(isset($release['status']))     $form->dom->status->picker($release['status']);
        $form->wait(2);
        if(isset($release['plandate']))    $form->dom->date->datepicker($release['plandate']);
        if(isset($release['releasedate'])) $form->dom->releasedDate->datepicker($release['releasedate']);

        $form->dom->btn($this->lang->save)->click();
        $form->wait(2);

        /* 断言检查必填提示信息 */
        if($this->response('method') == 'edit')
        {
            $nameTipForm = $form->dom->nameTip->getText();
            if($release['name'] == '')
            {
                return ($nameTipForm == '『应用版本号』不能为空。')
                    ? $this->success('编辑项目发布表单页必填提示信息正确')
                    : $this->failed('编辑项目发布表单页必填提示信息不正确');
            }
            else
            {
                return ($nameTipForm == sprintf($this->lang->error->unique, $this->lang->release->name, $form->dom->name->getValue()))
                    ? $this->success('发布名称重复时提示信息正确')
                    : $this->failed('发布名称重复时提示信息不正确');
            }
        }

        /* 跳转到发布概况页面，点击基本信息标签，查看信息是否正确 */
        else
        {
            $viewPage = $this->loadPage('projectrelease', 'view');
            $viewPage->dom->basic->click();
            $viewPage->wait(2);

            //断言检查应用名称、发布名称、状态是否正确
            if(isset($release['systemname']) && $viewPage->dom->basicSystemName->getText() != $release['systemname']) return $this->failed('应用名称错误');
            if(isset($release['name']) &&$viewPage->dom->basicreleasename->getText() != $release['name'])             return $this->failed('项目发布名称错误');
            if(isset($release['status']) &&$viewPage->dom->basicstatus->getText() != $release['status'])              return $this->failed('项目发布状态错误');

            return $this->success('编辑项目发布成功');
        }
    }
}
