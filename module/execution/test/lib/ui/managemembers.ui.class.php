<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class manageMembersTester extends tester
{
    /**
     * 添加团队成员
     * Add team members
     *
     * @param  array  $execution
     * @access public
     * @return object
     */
    public function add($execution)
    {
        $form = $this->initForm('execution', 'managemembers', array('execution' => $execution['id']), 'appIframe-execution');
        $form->wait(1);
        $form->dom->firstNullAccount->picker($execution['account']);
        $form->dom->btn($this->lang->save)->click();

        $form = $this->loadPage('execution', 'team');
        if($form->dom->lastUser->getText() != $execution['account']) return $this->failed('添加团队成员失败');
        return $this->success('添加团队成员成功');
    }

    /**
     * 删除团队成员
     * Delete team members
     *
     * @param  array  $execution
     * @access public
     * @return object
     */
    public function delete($execution)
    {
        $form = $this->initForm('execution', 'managemembers', array('execution' => $execution['id']), 'appIframe-execution');
        $form->wait(1);
        $firstAccount = $form->dom->firstAccount->attr('value');
        $form->dom->firstDelBtn->click();
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        $form = $this->loadPage('execution', 'team');
        if($form->dom->firstUser->getText() != $firstAccount) return $this->success('删除团队成员成功');
        return $this->failed('删除团队成员失败');
    }

    /**
     * 移除团队成员。
     * Remove team members.
     *
     * @param  array  $execution
     * @access public
     * @return object
     */
    public function remove($execution)
    {
        $form = $this->initForm('execution', 'team', array('execution' => $execution['id']), 'appIframe-execution');
        $form->wait(1);
        $numBefore = $form->dom->num->getText();
        $form->dom->firstRemoveBtn->click();
        $form->wait(1);
        $form->dom->alertModal();
        $form->wait(2);
        $numAfter = $form->dom->num->getText();
        if($numBefore == $numAfter + 1) return $this->success('移除团队成员成功');
        return $this->failed('移除团队成员失败');
    }

    /**
     * 复制部门成员。
     * Copy department members.
     *
     * @param  array  $execution
     * @access public
     * @return object
     */
    public function copyDeptMembers($execution)
    {
        $form = $this->initForm('execution', 'managemembers', array('execution' => $execution['id']), 'appIframe-execution');
        $form->wait(1);
        $form->dom->dept->picker($execution['dept']);
        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();
        $form = $this->loadPage('execution', 'team');
        if($form->dom->num->getText() == $execution['membersExpect']) return $this->success('复制部门成员成功');
        return $this->failed('复制部门成员失败');
    }

    /**
     * 复制团队。
     * Copy team.
     *
     * @param  array  $execution
     * @access public
     * @return object
     */
    public function copyTeamMembers($execution)
    {
        $form = $this->initForm('execution', 'managemembers', array('execution' => $execution['id']), 'appIframe-execution');
        $form->dom->execution->picker($execution['team']);
        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();
        $form = $this->loadPage('execution', 'team');
        if($form->dom->num->getText() == $execution['membersExpect']) return $this->success('复制团队成员成功');
        return $this->failed('复制团队成员失败');
    }
}
