<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class batchCreateStory extends tester
{
    /**
     * 运营界面批量创建目标
     * batch create story in lite
     *
     * @param array  $project
     * @param array  $storyUrl
     * @param object $story
     *
     * @return mixed
     */
    public function batchCreateStory($project, $storyUrl, $story)
    {
        $this->switchVision('lite', 5);
        $this->page->waitElement('//*[@id="app-project"]', 5);
        $this->openURL('projectstory', 'story', $project, 'appIframe-project');
        $this->page->waitElement('//*[@id="app-project"]', 5);
        $form = $this->initForm('story', 'batchCreate', $storyUrl, 'appIframe-project');
        $form->wait(2);
        //设置表单内容
        if (isset($story->name))     $form->dom->title_0->setValue($story->name);
        if (isset($story->reviewer)) $form->dom->reviewerPick->multiPicker($story->reviewer);
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
     *
     * @return mixed
     */
    public function checkBatchCreate($project, $storyUrl, $story, $form)
    {
        //创建失败时的校验
        if ($this->response('method') == 'batchCreate')
        {
            if (!isset($story->name) || $story->name == '')
            {
                $nameTip = $this->lang->story->errorEmptyStory;
                return ($form->dom->alertModal('text') == $nameTip)
                    ? $this->success('目标名称必填提示信息正确')
                    : $this->failed('目标名称必填提示信息不正确');
            }
            if (!isset($story->reviewer) || $story->reviewer == '')
            {
                $reviewerTip = sprintf($this->lang->error->notempty, $this->lang->story->reviewer);
                return ($reviewerTip == $form->dom->reviewerTip->getText())
                    ? $this->success('评审人必填提示信息正确')
                    : $this->failed('评审人必填提示信息不正确');
            }
        }
        else
        {
            $storyList = $this->initForm('projectstory', 'story', $project, 'appIframe-project');
            $storyList->dom->search(array("{$this->lang->story->name},=,{$story->name}"));
            return ($storyList->dom->firstStory->getText() == $story->name)
                ? $this->success('批量创建目标成功')
                : $this->failed('批量创建目标失败');
        }
    }
}
