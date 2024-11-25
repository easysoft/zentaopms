<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class unLinkStoryTester extends tester
{
    /**
     * Unlink a story of project.
     * 项目单个移除需求
     *
     * @access public
     * @return object
     */
    public function unlinkStory()
    {
        $form = $this->initForm('projectstory', 'story', array('projectID' => 1), 'appIframe-project');
        $form->dom->allTab->click();
        $unlinkNumBefore = $form->dom->allTabNum->getText(); // 记录移除需求前项目下的需求数量
        $form->dom->unlinkFirBtn->click(); // 点击第一行的单个移除按钮
        $form->wait(1);
        $form->dom->alertModal(); // 模态框中点击确定
        $form->wait(2);
