<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class buildLinkStoryTester extends tester
{
    /**
     * Projectbuild link story.
     * 项目版本关联需求
     *
     * @access public
     * @return object
     */
    public function linkStory()
    {
        $form = $this->initForm('projectbuild', 'view', array('buildID' => 1), 'appIframe-project');
        $form->dom->linkStoryBtn->click();
        $form->wait(2);
        $form->dom->searchBtn->click();
        $form->dom->selectAllStory->click(); // 点击全选按钮
        $form->dom->linkStoryBtnBottom->click();
        // 断言检查版本关联需求数量是否成功
