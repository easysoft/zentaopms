<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class editProjectBuildTester extends tester
{
    /**
     * 编辑项目版本时名称必填提示检查。
     * Check for no name input when editing the project build.
     *
     * @param  array $build
     * @access public
     */
    public function editNoNameInfo($build)
    {
        $form = $this->initForm('projectbuild', 'edit', array('projectID' => 1), 'appIframe-project');
        if(isset($build['name'])) $form->dom->name->setValue($build['name']);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(2);

        /* 断言检查提示信息 */
        if($this->response('method') != 'view')
        {
            if($this->checkFormTips('projectbuild')) return $this->success('编辑项目版本表单页提示信息正确');
            return $this->failed('编辑项目版本表单页提示信息不正确');
        }
    }

    /**
     * 编辑项目版本。
     * Edit the project build.
     *
     * @param  array $build
     * @access public
     */
    public function editProjectBuild($build)
    {
        $form = $this->initForm('projectbuild', 'edit', array('projectID' => 1), 'appIframe-project');

        if(isset($build['name'])) $form->dom->name->setValue($build['name']);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(2);

        /* 跳转到版本概况页面，点击基本信息标签，查看信息是否正确 */
        $viewPage = $this->loadPage('projectbuild', 'view');
        $viewPage->dom->basic->click();
        $viewPage->wait(5);

        /* 断言检查版本名称、所属执行是否正确 */
        if($viewPage->dom->basicBuildName->getText() != $build['name']) return $this->failed('项目版本名称错误');

        return $this->success('项目版本编辑成功');
    }
}
