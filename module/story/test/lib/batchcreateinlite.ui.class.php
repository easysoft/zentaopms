<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class batchCreateStory extends tester
{
    /**
     * 运营界面批量创建目标
     * batch create story in lite
     * @param array  $project
     * @param array  $storyUrl
     * @param object $story
     * @return mixed
     */
    public function batchCreateStory($project, $storyUrl, $story)
    {
        $this->switchVision('lite');
        $this->openURL('projectstory', 'story', $project, 'appIframe-project');
        $form = $this->initForm('story', 'batchCreate', $storyUrl, 'appIframe-project');
        $form->wait(1);
        //设置表单内容
        if (isset($story->name)) $form->dom->title_0->setValue($story->name);
        if (isset($story->reviewer)) $form->dom->{'reviewer[1][]'}->multiPicker($story->reviewer);
        $form->wait(1);
        $form->dom->btn($this->lang->story->saveDraft)->click();
        $form->wait(2);
        return $this->checkBatchCreate($project, $storyUrl, $story, $form);
    }
    /**
     * 检查批量创建目标的结果
     * Check the result of batch create story
     *
     * @param array  $project
     * @param array  $storyUrl
     * @param object $story
     * @param object $form
     * @return mixed
     */
}
