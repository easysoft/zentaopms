<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class assign extends tester
{
    /**
     * 运营界面指派目标
     * Assign story in lite
     *
     * @param  array  $storyUrl
     * @param  string $user
     * @return mixed
     */
    public function assignStory($storyUrl, $user)
    {
        $this->switchVision('lite', 5);
        $form = $this->initForm('projectstory', 'view', $storyUrl, 'appIframe-project');
        $form->wait(2);
        $form->dom->assignBtn->click();
        $form->wait(2);
        $form->dom->assignedTo->picker($user);
        $form->dom->assignSubmitBtn->click();
        $form->wait(2);
        $form->dom->targetLife->click();
        $form->wait(2);
        $assignTo = strstr($form->dom->assignTo->getText(), ' ', true);
        return($assignTo == $user)
            ? $this->success('目标指派成功')
            : $this->failed('目标指派失败');
    }
}
