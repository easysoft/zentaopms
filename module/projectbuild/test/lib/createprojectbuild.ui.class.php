<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
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
        $form->wait(2);

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

        if(isset($build['execution'])) $form->dom->execution->picker($build['execution']);
        if(isset($build['name']))      $form->dom->name->setValue($build['name']);
        $form->dom->btn($this->lang->save)->click();
