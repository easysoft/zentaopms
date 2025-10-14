<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class createProjectReleaseTester extends tester
{
    /**
     * Check required input when creating the project release.
     * 创建项目发布时检查必填项
     *
     * @access public
     */
    public function checkRequired()
    {
        $form = $this->initForm('projectrelease', 'create', array('projectID' => 1), 'appIframe-project');
        $form->dom->newSystem->click();
        $form->dom->btn($this->lang->save)->click();
        $form->wait(2);

        /* 创建发布页面应用不能为空和应用版本号不能为空的提示 */
        $systemTipForm = $form->dom->systemNameTip->getText();
        $nameTipForm   = $form->dom->nameTip->getText();
        $systemTip     = sprintf($this->lang->error->notempty, $this->lang->product->system);
        $nameTip       = sprintf($this->lang->error->notempty, $this->lang->product->system . '版本号');

        /* 断言检查必填提示信息 */
        if($systemTipForm == $systemTip and $nameTipForm == $nameTip) return $this->success('创建项目发布表单页必填提示信息正确');
        return $this->failed('创建项目发布表单页必填提示信息不正确');
    }

    /**
     * Create the project release.
     * 创建项目发布
     *
     * @param  array $release
     * @access public
     */
    public function createProjectRelease($release)
    {
        $form = $this->initForm('projectrelease', 'create', array('projectID' => 1), 'appIframe-project');

        /* 如果用例中设置了应用名称，就勾选新建应用，填写已设置的应用名称。没有设置应用名称，就自动使用数据库中的应用 */
        if(isset($release['systemname']))
        {
            $form->dom->newSystem->click();
            $form->dom->systemName->setValue($release['systemname']);
        }
        if(isset($release['systemname'])) $form->dom->systemName->setValue($release['systemname']);
        if(isset($release['name']))       $form->dom->name->setValue($release['name']);
        if(isset($release['status']))     $form->dom->status->picker($release['status']);
        $form->wait(2);
        if(isset($release['plandate']))    $form->dom->date->datepicker($release['plandate']);
        if(isset($release['releasedate'])) $form->dom->releasedDate->datepicker($release['releasedate']);

        $form->dom->btn($this->lang->save)->click();
        $form->wait(2);

        /* 断言检查必填提示信息 */
        if($this->response('method') == 'create')
        {
            return ($form->dom->nameTip->getText() == sprintf($this->lang->error->unique, $this->lang->release->name, $form->dom->name->getValue()))
                ? $this->success('发布名称重复时提示信息正确')
                : $this->failed('发布名称重复时提示信息不正确');
        }

        /* 跳转到发布概况页面，点击基本信息标签，查看信息是否正确 */
        else
        {
            $viewPage = $this->loadPage('projectrelease', 'view');
            $viewPage->dom->basic->click();
            $viewPage->wait(2);

            //断言检查应用名称、发布名称、状态是否正确
            $basicSystemName  = $viewPage->dom->basicSystemName->getText();
            $expectSystemName = isset($release['systemname']) ? $release['systemname'] : '应用AAA';
            if($basicSystemName != $expectSystemName) return $this->failed('应用名称错误');
            if($viewPage->dom->basicreleasename->getText() != $release['name']) return $this->failed('项目发布名称错误');
            if($viewPage->dom->basicstatus->getText() != $release['status'])    return $this->failed('项目发布状态错误');

            return $this->success('创建项目发布成功');
        }
    }
}
