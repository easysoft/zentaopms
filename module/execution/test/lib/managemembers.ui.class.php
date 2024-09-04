<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class manageMembersTester extends tester
{
    /**
     * @var string
     */
    public function add($execution)
    {
        $form = $this->initForm('execution', 'managemembers', array('execution' => $execution['id']), 'appIframe-execution');
        $form->dom->account->picker($execution['account']);
        $form->dom->btn($this->lang->save)->click();

        $form = $this->loadPage();
        if($form->dom->user->getText() != $execution['account']) return $this->failed('添加团队成员失败');
        return $this->success('添加团队成员成功');
    }
}
