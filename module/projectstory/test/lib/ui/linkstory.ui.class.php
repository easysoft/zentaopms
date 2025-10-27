<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
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
        $form->dom->saveBtn->click();
        $form->wait(2);

        $storyView = $this->loadPage('project', 'story');
        $storyView->dom->allTab->click();
        $storyView->wait(2);
        if($storyView->dom->allTabNum->getText() !== $numBefore) return $this->success('关联需求成功');
        return $this->failed('关联需求失败');
    }
}
