<?php
require_once dirname(__FILE__, 6) . '/test/lib/ui.php';

class deleteTester extends tester
{
    public $userID;

    public function __construct(string $account)
    {
        parent::__construct();
        global $uiTester;
        $this->userID = $uiTester->dao->select('id')->from(TABLE_USER)->where('account')->eq($account)->fetch('id');
    }

    /**
     * 直接打开删除页并返回页面对象，避免 initForm 在检查 iframe 时出错。
     */
    private function openDeleteForm()
    {
        $webRoot = $this->getWebRoot();
        $url     = $webRoot . "index.php?m=user&f=delete&userID={$this->userID}";

        $this->page->openURL($url)->wait(1);
        $this->page->dom->switchToIframe('appIframe-admin');

        return $this->loadPage('user', 'delete');
    }

    /**
     * 验证使用正确密码删除用户
     * Verify that deleting a user with correct password successfully deletes the user.
     */
    public function verifyDeleteWithCorrectPassword()
    {
        $this->login();

        $form = $this->openDeleteForm();
        $form->wait(1);

        $form->dom->verifyPassword->setValue($this->config->uitest->defaultPassword);
        $form->wait(1);

        $form->dom->submitBtn->click();
        $form->wait(2);

        global $uiTester;
        $deleted = $uiTester->dao->select('deleted')->from(TABLE_USER)->where('id')->eq($this->userID)->fetch('deleted');
        if($deleted === '1') return $this->success('使用正确密码删除用户测试通过');
        return $this->failed('使用正确密码删除用户测试失败');
    }

    /**
     * 验证使用错误密码删除用户
     * Verify that deleting a user with wrong password does not delete the user.
     */
    public function verifyDeleteWithWrongPassword()
    {
        $this->login();

        $form = $this->openDeleteForm();
        $form->wait(1);

        $form->dom->verifyPassword->setValue('wrong-password');
        $form->wait(1);

        $form->dom->submitBtn->click();
        $form->wait(2);

        global $uiTester;
        $deleted = $uiTester->dao->select('deleted')->from(TABLE_USER)->where('id')->eq($this->userID)->fetch('deleted');
        if($deleted === '0')
        {
            $tip = $form->dom->verifyPasswordTip->getText();
            if($tip === $this->lang->user->error->verifyPassword) return $this->success('使用错误密码删除用户测试通过');
            return $this->failed('使用错误密码删除用户测试失败,提示错误');
        }

        return $this->failed('使用错误密码删除用户测试失败');
    }
}
