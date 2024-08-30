<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class batchEditUserTester extends tester
{
    /**
     * Check the page jump after batch edited a user's realname.
     *
     * @param  string    $user
     * @access public
     * @return object
     */
    public function batchEditUser($user)
    {
        $form = $this->initForm('user', 'batchEdit', '', 'appIframe-admin');

        // 在用户列表点击第二个用户并点击编辑按钮,进入编辑页面
        $form->dom->getElement('//*[@id="userList"]/div[2]/div[1]/div/div[3]/div/div')->click();
        $form->wait(1);
        $form->dom->getElement('//*[@id="userList"]/div[3]/nav[1]/button')->click();

        $form->dom->realname->setValue($user->realname);
        $form->dom->verifyPassword->setValue($user->verifyPassword);
        $form->wait(1);
        $form->dom->getElement('//*[@id="zin_user_batchedit_formBatch"]/div[3]/button[1]')->click();
        $form->wait(1);

        return $this->success('保存成功');
    }

    /**
     * Check the page jump after remove a user's realname.
     *
     * @param  string    $user
     * @access public
     * @return object
     */
    public function emptyRealname($user)
    {
        $form = $this->initForm('user', 'batchEdit', '', 'appIframe-admin');

        // 在用户列表勾选所有用户并点击编辑按钮,进入编辑页面
        $form->dom->getElement('//*[@id="userList"]/div[3]/div[1]/div')->click();
        $form->wait(1);
        $form->dom->getElement('//*[@id="userList"]/div[3]/nav[1]/button')->click();

