<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
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
        $this->switchVision('lite', 5);
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

    /**
     * 运营界面评审目标
     * Review story in lite
     *
     * @param
     * @return mixed
     */
    public function reviewStory( $storyID, $result, $status)
    {
        $this->switchVision('lite');
        $storyURL = array(
            'storyID'   => $storyID,
            'form'      => 'project',
            'storyType' => 'story'
        );
        $form = $this->initForm('projectstory', 'story', array('projectID' => '1'), 'appIframe-project');
        $form->wait(3);
        $form = $this->initForm('story', 'review', $storyURL, 'appIframe-project');  //进入研发评审页面

        $resultOptions = array(
                'pass'    => $this->lang->story->reviewResultList->pass, //通过
                'revert'  => $this->lang->story->reviewResultList->revert,//撤销变更
                'clarify' => $this->lang->story->reviewResultList->clarify,//有待明确
                'reject'  => $this->lang->story->reviewResultList->reject//拒绝
        );
        $form->dom->result->picker($resultOptions[$result]); //选择目标评审结果
        if($result != 'reject') $form->dom->assignedTo->picker('admin'); //指派人选择admin
        $form->dom->btn($this->lang->save)->click();

        $viewPage = $this->loadPage('projectstory', 'view');
        $viewPage->wait(3);

        $statusOptions  = array(
            'active' => $this->lang->story->statusList->active,
            'draft'  => $this->lang->story->statusList->draft,
            'closed' => $this->lang->story->statusList->closed
        );
        if($viewPage->dom->storyStatus->getText() != $statusOptions[$status]) return $this->failed('目标状态错误');
        if($result != 'revert')
        {
            $viewPage->dom->TargetLife->click();
            if($viewPage->dom->storyReviwer->getText() != 'admin') return $this->failed('目标评审人错误');
        }else
        {
            if($viewPage->dom->storyName->getText() != '目标4') return $this->failed('目标名称错误');
        }

        return $this->success('评审目标成功');
    }

    /**
     * 运营界面撤销评审目标
     * Revoke story in lite
     *
     * @param  array $storyUrl
     * @return mixed
     */
    public function revokeStory($storyUrl)
    {
        $this->switchVision('lite', 5);
        $form = $this->initForm('projectstory', 'view', $storyUrl, 'appIframe-project');
        $form->wait(2);
        $form->dom->revokeBtn->click();
        $form->wait(2);
        $form->dom->confirmBtn->click();
        $viewPage = $this->initForm('projectstory', 'view', $storyUrl, 'appIframe-project');
        $viewPage->wait(2);
        $status = $viewPage->dom->storyStatus->getText();
        return($status == $this->lang->story->statusList->draft)
            ? $this->success('目标撤销评审成功')
            : $this->failed('目标撤销评审失败');
    }

    /**
     * 运营界面提交评审目标
     * Submit review story in lite
     *
     * @param  array $storyUrl
     * @return mixed
     */
    public function submitReview($storyUrl)
    {
        $this->switchVision('lite', 5);
        $form = $this->initForm('projectstory', 'view', $storyUrl, 'appIframe-project');
        $form->wait(2);
        $form->dom->revokeBtn->click();
        $form->wait(2);
        $form->dom->submitReviewBtn->click();
        $viewPage = $this->initForm('projectstory', 'view', $storyUrl, 'appIframe-project');
        $viewPage->wait(2);
        $status = $viewPage->dom->storyStatus->getText();
        return($status == $this->lang->story->statusList->reviewing)
            ? $this->success('目标提交评审成功')
            : $this->failed('目标提交评审失败');
    }
}
