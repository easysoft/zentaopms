<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createUserTester extends tester
{
    /**
     * Check the page jump after created a normal user.
     *
     * @param  string    $user
     * @access public
     * @return object
     */
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

        if($this->response('method') != 'browse')
        {
            if($this->checkFormTips('user')) return $this->success('创建重名用户提示正确');
            return $this->failed('创建重名用户提示错误');
        }

        return $this->success('创建用户成功');
    }
}
