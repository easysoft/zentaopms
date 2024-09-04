<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
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
        $form->dom->firstNullAccount->picker($execution['account']);
        $form->dom->btn($this->lang->save)->click();

        $form = $this->loadPage();
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
        $firstAccount = $form->dom->firstAccount->attr('value');
        $form->dom->firstDelBtn->click();
        $form->dom->btn($this->lang->save)->click();

        $form = $this->loadPage();
        if($form->dom->firstUser->getText() != $firstAccount) return $this->failed('删除团队成员成功');
        return $this->success('删除团队成员失败');

    }
}
