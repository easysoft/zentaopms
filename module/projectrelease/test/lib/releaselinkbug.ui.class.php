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
