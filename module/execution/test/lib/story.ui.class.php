<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class storyTester extends tester
{
    /**
     * 检查Tab标签下的数据。
     * Check the data of the Tab tag.
     *
     * @param  string $tab       allTab|unclosedTab|draftTab|reviewingTab
     * @param  string $expectNum
     * @access public
     * @return object
     */
    public function checkTab($tab, $expectNum)
    {
        $form = $this->initForm('execution', 'story', array('execution' => '2'), 'appIframe-execution');
        $form->dom->$tab->click();
        $form->wait(1);
        if($form->dom->num->getText() == $expectNum) return $this->success($tab . '下显示条数正确');
        return $this->failed($tab . '下显示条数不正确');
    }

    /**
     * 移除单个需求。
     * Unlink story.
     *
     * @access public
     * @return object
     */
    public function unlinkStory()
    {
        $form = $this->initForm('execution', 'story', array('execution' => '2'), 'appIframe-execution');
        $name = $form->dom->firstName->getText();
        $form->dom->firstUnlinkBtn->click();
        $form->dom->alertModal();
        $form->wait(1);

        $form->dom->search(array("{$this->lang->story->name},=,{$name}"));
        if($form->dom->firstName === false) return $this->success('需求移除成功');
        return $this->failed('需求移除失败');
    }
