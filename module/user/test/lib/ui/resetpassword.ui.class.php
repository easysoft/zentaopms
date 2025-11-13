<?php
require_once dirname(__FILE__, 6) . '/test/lib/ui.php';

class resetpasswordTester extends tester
{
    /**
     * 为第一个普通用户生成重置code
     * Prepare valid reset code for first non-admin user.
     */
    private function prepareValidCode()
    {
        global $uiTester;
        $target = $uiTester->dao->select('account')->from(TABLE_USER)->where('account')->ne('admin')->andWhere('deleted')->eq('0')->fetch();
        if($target && !empty($target->account)) $account = $target->account;

        // 使用尽量短的 code，避免 resetToken 列长度溢出。
        $code       = 'a';
        $endTime    = time() + 3600;
        $resetToken = json_encode(array('code' => $code, 'endTime' => $endTime));

        // 写入指定用户的 resetToken。
        $uiTester->dao->update(TABLE_USER)
            ->set('resetToken')->eq($resetToken)
            ->where('account')->eq($account)
            ->exec();

        return (object)array('code' => $code, 'account' => $account);
    }

    /**
     * 打开邮箱重置密码页面（带 code），返回 page 对象。
     * Open resetpassword page with code, return page object.
     */
    private function openResetPassword($code)
    {
        $webRoot = $this->getWebRoot();
        $url     = $webRoot . 'index.php?m=user&f=resetPassword&code=' . urlencode($code);

        // 保持登录态语言等cookie，但该页面无需已登录态。
        if(empty($this->lang)) $this->initLang();

        $this->page->openURL($url);
        return $this->loadPage('user', 'resetpassword');
    }

    /**
     * 验证密码过期提示与跳转链接是否正确展示。
     * Verify expired message and redirect link on resetpassword page.
     */
    public function verifyExpiredMessage()
    {
        // 使用伪造过期 code（数据库不存在或过期均视为 expired）。
        $form = $this->openResetPassword('expired_code_example');
        $form->wait(1);

        // 通过页面源断言过期提示与登录跳转，避免元素等待超时。
        $html      = $form->getPageSource();
        $hasExpired = (strpos($html, $this->lang->user->linkExpired) !== false);
        if(!$hasExpired) return $this->failed('过期提示信息不匹配');

        $hasLoginLink = (stripos($html, 'm=user') !== false && stripos($html, 'f=login') !== false);
        if(!$hasLoginLink) return $this->failed('过期提示中的跳转链接不指向登录页');

        return $this->success('重置密码过期提示正确');
    }

    /**
     * 验证密码重置页面的表单元素是否存在与交互正常。
     * Verify resetpassword form elements exist and interact normally.
     */
    public function verifyResetPasswordForm()
    {
        // 通过测试程序写入有效 resetToken，避免过期分支导致元素缺失。
        $info = $this->prepareValidCode('admin');
        $form = $this->openResetPassword($info->code);
        $form->wait(1);

        $html     = $form->getPageSource();
        $isExpire = (strpos($html, $this->lang->user->linkExpired) !== false);
        if($isExpire) return $this->failed('当前为过期分支，但没有效重置链接');

        $password1Exists = $form->dom->password1;
        $password2Exists = $form->dom->password2;
        $submitExists    = $form->dom->submitBtn;

        if(!$password1Exists || !$password2Exists || !$submitExists) return $this->failed('重置密码表单元素缺失');

        $password = 'StrongPass123!';
        $passwordMD5 = md5($password);
        $form->dom->password1->setValue($password);
        $form->dom->password2->setValue($password);
        $form->dom->submitBtn->click();
        $form->wait(2);

        $url  = $form->getPageUrl();
        $html = $form->getPageSource();
        $onLoginRoute = (strpos($url, 'm=user') !== false && strpos($url, 'f=login') !== false);
        $hasLoginForm = (strpos($html, 'id="loginForm"') !== false) || (strpos($html, 'id="loginForm"') !== false) || (strpos($html, 'id="loginForm"') !== false);
        $redirectOK   = $onLoginRoute || $hasLoginForm;
        if(!$redirectOK) return $this->failed('提交后未跳转到登录页');

        // 双重验证：数据库密码是否更新，且重置令牌是否清空。
        global $uiTester;
        $userInfo     = $uiTester->dao->select('password, resetToken')->from(TABLE_USER)->where('account')->eq($info->account)->fetch();
        $dbOK         = $userInfo && $userInfo->password === $passwordMD5;
        $tokenCleared = $userInfo && empty($userInfo->resetToken);
        if(!$dbOK) return $this->failed('提交后数据库密码未更新');
        if(!$tokenCleared) return $this->failed('提交后重置令牌未清空');

        return $this->success('重置密码表单元素展示与交互正确');
    }
}
