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
