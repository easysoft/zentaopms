<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createProjectReleaseTester extends tester
{
    /**
     * Check the page input when creating the project release.
     * 创建项目发布时检查页面输入
     *
     * @param  array $release
     * @access public
     */
    public function checkInput($release)
    {
        $form = $this->initForm('projectrelease', 'create', array('projectID' => 1), 'appIframe-project');
        if(isset($release['name']))   $form->dom->name->setValue($release['name']);
        if(isset($release['status'])) $form->dom->status->picker($release['status']);
        $form->wait(2);
        if(isset($release['plandate']))    $form->dom->date->datepicker($release['plandate']);
        if(isset($release['releasedate'])) $form->dom->releasedDate->datepicker($release['releasedate']);

        $form->dom->btn($this->lang->save)->click();
        $form->wait(5);
        return $this->checkResult($release);
    }

    /**
     * Check the result after creating the project release.
     * 创建项目发布后检查结果
     *
     * @param  array $release
     * @access public
     * @return object
     */
    public function checkResult($release)
    {
        //检查创建页面时的提示信息
        if($this->response('method') != 'view')
        {
            if($this->checkFormTips('projectrelease')) return $this->success('创建项目发布表单页提示信息正确');
            return $this->failed('创建项目发布表单页提示信息不正确');
        }
        /* 跳转到发布概况页面，点击基本信息标签，查看信息是否正确 */
        $viewPage = $this->loadPage('projectrelease', 'view');
        $viewPage->dom->basic->click();
        $viewPage->wait(5);

        //断言检查发布名称、状态是否正确
        if($viewPage->dom->basicreleasename->getText() != $release['name']) return $this->failed('项目发布名称错误');
        if($viewPage->dom->basicstatus->getText() != $release['status'])    return $this->failed('项目发布状态错误');

        return $this->success();
    }
}
