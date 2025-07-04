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
        $form = $this->initForm('projectstory', 'view', $storyUrl, 'appIframe-project');
        $form->wait(1);
        $form->dom->closeBtn->click();
        $form->wait(1);
        $form->dom->closestoryBtn->click();
        $viewPage = $this->initForm('projectstory', 'view', $storyUrl, 'appIframe-project');
        $form->wait(1);
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
        $form = $this->initForm('projectstory', 'view', $storyUrl, 'appIframe-project');
        $form->wait(1);
        $form->dom->activateBtn->click();
        $form->wait(1);
        $form->dom->activateStoryBtn->click();
        $viewPage = $this->initForm('projectstory', 'view', $storyUrl, 'appIframe-project');
        $form->wait(1);
        $status = $viewPage->dom->storyStatus->getText();
        return($status != $this->lang->story->statusList->closed)
            ? $this->success('目标激活成功')
            : $this->failed('目标激活失败');
    }
}
