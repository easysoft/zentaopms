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
        $form = $this->initForm('user', 'batchCreate', array() , 'appIframe-admin');
        $form->dom->account->setValue($user->account);
        $form->dom->realname->setValue($user->realname);
        $form->dom->passwordfield->setValue($user->passwordfield);
        $form->dom->verifyPassword->setValue($user->verifyPassword);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        return $this->success('批量创建用户成功');
    }

    /**
     * Check the page jump after batchcreate a empty account user.
     *
     * @param  string    $user
     * @access public
     * @return object
     */
    public function batchCreateEmptyAccountUser($user)
    {
        $form = $this->initForm('user', 'batchCreate', array(), 'appIframe-admin');
        $form->dom->account->setValue($user->account);
        $form->dom->realname->setValue($user->realname);
        $form->dom->passwordfield->setValue($user->passwordfield);
        $form->dom->verifyPassword->setValue($user->verifyPassword);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        return $this->failed('批量创建用户失败');
    }

    /**
     * Check the page jump after batchcreate a empty realname user.
     *
     * @param  string    $user
     * @access public
     * @return object
     */
    public function batchCreateEmptyRealnameUser($user)
    {
        $form = $this->initForm('user', 'batchCreate', array(), 'appIframe-admin');
        $form->dom->account->setValue($user->account);
        $form->dom->realname->setValue($user->realname);
        $form->dom->passwordfield->setValue($user->passwordfield);
        $form->dom->verifyPassword->setValue($user->verifyPassword);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        return $this->failed('姓名不能为空');
    }

    /**
     * Check the page jump after batchcreate a empty password user.
     *
     * @param  string    $user
     * @access public
     * @return object
     */
    public function batchCreateEmptyPasswordUser($user)
    {
        $form = $this->initForm('user', 'batchCreate', array(), 'appIframe-admin');
        $form->dom->account->setValue($user->account);
        $form->dom->realname->setValue($user->realname);
        $form->dom->passwordfield->setValue($user->passwordfield);
        $form->dom->verifyPassword->setValue($user->verifyPassword);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        return $this->failed('密码不能为空');
    }


