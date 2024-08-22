<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class batchCreateUserTester extends tester
{
    /**
     * Check the page jump after batchcreate a normal user.
     *
     * @param  string    $user
     * @access public
     * @return object
     */
    public function batchCreateNormalUser($user)
    {
        $form = $this->initForm('user', 'batchCreate', array(), 'appIframe-admin');
        $form->dom->account->setValue($user->account);
        $form->dom->realname->setValue($user->realname);
        $form->dom->passwordfield->setValue($user->passwordfield);
        $form->dom->verifyPassword->setValue($user->verifyPassword);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);
