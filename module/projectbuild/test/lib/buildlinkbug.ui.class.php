<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class buildLinkBugTester extends tester
{
    /**
     * Projectbuild link bug.
     * 项目版本关联bug
     *
     * @access public
     * @return object
     */
    public function linkBug()
    {
        $form = $this->initForm('projectbuild', 'view', array('buildID' => 1), 'appIframe-project');
        $form->dom->resolvedBugTab->click();
        $form->dom->linkBugBtn->click();
        $form->wait(2);
        $form->dom->searchBtn->click();
        $form->wait(2);
        $form->dom->selectAllBug->click(); // 点击全选按钮
        $form->dom->linkBugBtnBottom->click();
        // 断言检查版本关联bug数量是否成功
        $viewPage = $this->initForm('projectbuild', 'view', array('projectID' => 1), 'appIframe-project');
        $form->dom->resolvedBugTab->click();
        $form->wait(2);
        return ($viewPage->dom->resolvedBugNum === '0') ? $this->failed('版本关联bug失败') : $this->success('版本关联bug成功');
    }

    /**
     * Unlink a bug of project build.
     * 移除单个bug
     *
     * @access public
     * @return object
     */
    public function unlinkBug()
    {
        $form = $this->initForm('projectbuild', 'view', array('buildID' => 1), 'appIframe-project');
        $form->dom->resolvedBugTab->click();
