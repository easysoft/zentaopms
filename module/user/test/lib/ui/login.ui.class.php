<?php
require_once dirname(__FILE__, 6) . '/test/lib/ui.php';

class loginTester extends tester
{

    /**
     * 验证使用正确的用户名/密码登录
     * Verify login with correct account and password
     */
    public function verifyLoginCorrectCredentials()
    {
        $this->login();
        $form = $this->initForm('user', 'login', [], 'appIframe-my');
        $form->wait(1);
        $userInfo = $form->dom->avatar->getText();
        if(empty($userInfo)) return $this->failed('登录后用户信息为空');
        return $this->success('使用正确账号密码登录测试通过');
    }

    /**
     * 验证使用错误的登录信息登录系统
     * Verify login with incorrect account or password
     */
    public function verifyLoginIncorrectCredentials($account = 'admin', $password = 'wrongpass')
    {
        $webRoot = $this->getWebRoot();
        // 确保干净会话，避免前序用例的登录态影响。
        $this->page->openURL($webRoot)->deleteCookie()->refresh();
        $form = $this->loadPage('user', 'login');
        $form->wait(1);

        if($form->dom->account)  $form->dom->account->setValue($account);
        if($form->dom->password) $form->dom->password->setValue($password);
        if($form->dom->submit)   $form->dom->submit->click();

        $form->wait(1);

        $alertText = $form->dom->alertModal('text');
        if(empty($this->lang)) $this->initLang();
        if($alertText != $this->lang->user->loginFailed) return $this->failed('登录失败提示与预期不符', $alertText);
        // 关闭提示框，避免遮挡后续元素。
        $form->dom->alertModal();

        // 再次检查当前仍停留在登录页（未发生成功跳转）。
        $html = $form->getPageSource();
        $stillLogin = (strpos($html, 'id=\"loginForm\"') !== false) || (strpos($html, 'm=user') !== false && strpos($html, 'f=login') !== false);
        if(!$stillLogin) return $this->failed('错误凭据却跳转到了其他页面');

        if($account == 'admin') return $this->success('使用错误密码登录测试通过');
        return $this->success('不存在的用户登录测试通过');
    }
}