<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createUserTester extends tester
{
    public function createNormalUser($user)
    {
        $form = $this->initForm('user', 'create', array(), 'appIframe-admin');
        $form->dom->account->setValue($user->account);
        $form->dom->password1->setValue($user->password);
        $form->dom->password2->setValue($user->confirmPassword);
        $form->dom->realname->setValue($user->realname);
        $form->dom->verifyPassword->setValue($user->verifyPassword);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        return $this->success('创建用户成功');
    }
}
