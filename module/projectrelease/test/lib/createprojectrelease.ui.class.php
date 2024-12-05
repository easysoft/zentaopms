<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
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
