<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class linkStoryTester extends tester
{
    /**
     * 关联需求
     * Link story
     *
     * @access public
     * @return object
     */
    public function linkStory()
    {
        $form = $this->initForm('projectstory', 'story', array('project' => '1'), 'appIframe-project');
        $form->dom->allTab->click();
        $numBefore = $form->dom->allTabNum->getText();
        $form->dom->linkStoryBtn->click();
        $form->wait(2);
        $form->dom->searchBtn->click();
        $form->dom->selectAllStory->click();
