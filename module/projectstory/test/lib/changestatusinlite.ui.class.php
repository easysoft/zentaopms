<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class changeStatus extends tester
{
    /**
     * 运营界面关闭目标
     * Close story in lite
     *
     * @param array $storyUrl
     * @return mixed
     */
    public function closeStory($storyUrl)
    {
        $this->switchVision('lite');
        $this->page->wait(3);
        $form = $this->initForm('projectstory', 'view', $storyUrl, 'appIframe-project');
        $form->wait(2);
        $form->dom->closeBtn->click();
        $form->wait(2);
        $form->dom->closestoryBtn->click();
        $viewPage = $this->initForm('projectstory', 'view', $storyUrl, 'appIframe-project');
        $viewPage->wait(2);
        $status = $viewPage->dom->storyStatus->getText();
        return($status == $this->lang->story->statusList->closed)
            ? $this->success('目标关闭成功')
            : $this->failed('目标关闭失败');
    }

    /**
     * 运营界面激活目标
     * Activate story in lite
     *
     * @param array $storyUrl
     * @return mixed
     */
    public function activeStory($storyUrl)
    {
        $this->switchVision('lite');
        $this->page->wait(3);
        $form = $this->initForm('projectstory', 'view', $storyUrl, 'appIframe-project');
        $form->wait(2);
        $form->dom->activateBtn->click();
        $form->wait(2);
        $form->dom->activateStoryBtn->click();
        $viewPage = $this->initForm('projectstory', 'view', $storyUrl, 'appIframe-project');
        $viewPage->wait(2);
        $status = $viewPage->dom->storyStatus->getText();
        return($status != $this->lang->story->statusList->closed)
            ? $this->success('目标激活成功')
            : $this->failed('目标激活失败');
    }
}
