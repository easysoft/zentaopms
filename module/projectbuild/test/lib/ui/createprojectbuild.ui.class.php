<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class createProjectBuildTester extends tester
{
    /**
     * 创建项目版本时名称必填提示检查。
     * Check for no name input when creating the project build.
     *
     * @param  array $build
     * @access public
     */
    public function checkNoNameInfo($build)
    {
        $form = $this->initForm('projectbuild', 'create', array('projectID' => 1), 'appIframe-project');
        $form->dom->btn($this->lang->save)->click();
        $form->wait(5);

        /*断言检查提示信息*/
        if($this->response('method') != 'view')
        {
            if($this->checkFormTips('projectbuild')) return $this->success('创建项目版本表单页提示信息正确');
            return $this->failed('创建项目版本表单页提示信息不正确');
        }
    }

    /**
     * 创建项目版本。
     * Creat the project build.
     *
     * @param  array $build
     * @access public
     */
    public function createProjectBuild($build)
    {
        $form = $this->initForm('projectbuild', 'create', array('projectID' => 1), 'appIframe-project');

        /* 如果用例中设置了应用名称，就勾选新建应用，填写已设置的应用名称。没有设置应用名称，就自动使用数据库中的应用 */
        if(isset($build['systemname']))
        {
            $form->dom->newSystem->click();
            $form->wait(2);
            $form->dom->systemName->setValue($build['systemname']);
        }
        if(isset($build['execution'])) $form->dom->execution->picker($build['execution']);
        if(isset($build['name']))      $form->dom->name->setValue($build['name']);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(2);

        /* 跳转到版本概况页面，点击基本信息标签，查看信息是否正确 */
        $viewPage = $this->loadPage('projectbuild', 'view');
        $viewPage->dom->basic->click();
        $viewPage->wait(5);

        //断言检查版本名称、所属执行是否正确
        if($viewPage->dom->basicBuildName->getText() != $build['name'])      return $this->failed('项目版本名称错误');
        if(isset($build['execution'])  && $viewPage->dom->basicExecution->getText() != $build['execution'])   return $this->failed('项目版本所属执行错误');
        if(isset($build['systemname']) && $viewPage->dom->basicSystemName->getText() != $build['systemname']) return $this->failed('所属应用错误');

        return $this->success('项目版本创建成功');
    }
}
