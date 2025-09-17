<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class editUserTester extends tester
{
    /**
     * Check the page jump after edited a user.
     *
     * @param  string    $user
     * @access public
     * @return object
     */
    public function checkLocating($user)
    {
        $form = $this->initForm('user', 'edit', array('userID' => '2', 'from' => 'company'), 'appIframe-admin');
        $form->dom->password1->setValue($user->password);
        $form->dom->password2->setValue($user->confirmPassword);
        $form->dom->realname->setValue($user->realname);
        $form->dom->verifyPassword->setValue($user->verifyPassword);
        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);
        return $this->response();
    }
}
