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
        $form->wait(2);
        $linkNumBefore = $form->dom->resolvedBugNum->getText(); // 记录移除bug前版本关联的bug数量
        $form->dom->unlinkFirBugBtn->click(); // 点击第一行的单个移除按钮
        $form->wait(1);
        $form->dom->alertModal(); // 模态框中点击确定
        $form->wait(2);
        $linkNumAfter = $form->dom->resolvedBugNum->getText(); // 记录移除bug后版本关联的bug数量
        // 断言检查单个移除bug是否成功
        return ($linkNumAfter == $linkNumBefore - 1) ? $this->success('单个移除bug成功') : $this->failed('单个移除bug失败');
    }

    /**
     * unlink all bug of project build.
     * 移除全部bug
     *
     * @return object
     */
    public function batchUnlinkBug()
    {
        $form = $this->initForm('projectbuild', 'view', array('buildID' => 1), 'appIframe-project');
