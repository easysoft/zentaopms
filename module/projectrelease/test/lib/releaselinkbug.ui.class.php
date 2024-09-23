<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class releaseLinkBugTester extends tester
{
    /**
     * Projectrelease link bug.
     * 项目发布关联bug
     *
     * @param  array $release
     * @access public
     */
    public function linkBug()
    {
        $form = $this->initForm('projectrelease', 'view', array('releaseID' => 1), 'appIframe-project');
        $form->dom->resolvedBugTab->click();
        $form->dom->linkBugBtn->click();
        $form->wait(2);
        $form->dom->searchBtn->click();
        $form->dom->selectAllBug->click(); // 点击全选按钮
        $form->dom->linkBugBtnBottom->click();
        // 断言检查发布关联bug数量是否成功
        $viewPage = $this->initForm('projectrelease', 'view', array('projectID' => 1), 'appIframe-project');
        $form->dom->resolvedBugTab->click();
        $form->wait(2);
        return ($viewPage->dom->resolvedBugNum === '0') ? $this->failed('发布关联bug失败') : $this->success('发布关联bug成功');
    }

    /**
     * Unlink a bug of project release.
     * 移除单个bug
     *
     * @access public
     * @return object
     */
    public function unlinkBug()
    {
        $form = $this->initForm('projectrelease', 'view', array('releaseID' => 1), 'appIframe-project');
        $form->dom->resolvedBugTab->click();
        $form->wait(2);
        $linkNumBefore = $form->dom->resolvedBugNum->getText(); // 记录移除bug前发布关联的bug数量
