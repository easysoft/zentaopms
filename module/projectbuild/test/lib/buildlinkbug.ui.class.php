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
