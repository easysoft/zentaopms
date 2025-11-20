<?php
require_once dirname(__FILE__, 6) . '/test/lib/ui.php';

class resetTester extends tester
{
    public function __construct()
    {
        parent::__construct();
        $this->login();
        $webRoot = $this->getWebRoot();
        $url     = ($this->config->requestType == 'GET') ? 'index.php?m=user&f=reset' : 'user-reset.html';
        $this->page->openURL($webRoot . $url);
    }

    /**
     * 打开 reset 页面，检查是否存在创建文件提示
     * Verify create file panel exists.
     */
    public function verifyResetPageMessage()
    {
        $form = $this->loadPage('user', 'reset');
        $form->wait(2);

        $createPanelNodes = $form->dom->getElementListByXpathKey('createPanel');
        if(!empty($createPanelNodes))
        {
            // 断言提示与返回按钮存在
            $alertExists   = !empty($form->dom->getElementListByXpathKey('infoAlert'));
            $goBackExists  = !empty($form->dom->getElementListByXpathKey('gobackBtnCF'));
            if(!$alertExists)  return $this->failed('创建文件提示面板缺少信息提示');
            if(!$goBackExists) return $this->failed('创建文件提示面板缺少返回按钮');
            return $this->success('reset页面信息提示测试通过');
        }

        return $this->failed('reset页面信息提示测试失败');
    }

    /**
     * 验证管理员重置密码功能
     * Verify reset password of admin user.
     */
    public function verifyAdminResetSubmit()
    {
        $form = $this->loadPage('user', 'reset');
        $form->wait(2);

        global $uiTester;
        $base           = rtrim($uiTester->app->getBasePath(), DIRECTORY_SEPARATOR);
        $randFilename   = trim($form->dom->resetFileName->getText());
        $normalizedPath = ltrim(str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $randFilename), DIRECTORY_SEPARATOR);
        $resetFileName  = $base . DIRECTORY_SEPARATOR . $normalizedPath;
        if(empty($resetFileName)) return $this->failed('未获取到resetFileName');
        file_put_contents($resetFileName, '');

        $this->page->refresh();
        $form = $this->loadPage('user', 'reset');
        $form->wait(2);

        $accountNodes = $form->dom->getElementListByXpathKey('account');
        if(empty($accountNodes)) return $this->failed('未找到账号输入框');
        $pwd1Exists   = !empty($form->dom->getElementListByXpathKey('password1'));
        $pwd2Exists   = !empty($form->dom->getElementListByXpathKey('password2'));
        $submitExists = !empty($form->dom->getElementListByXpathKey('submitBtn'));
        $verifyRand   = !empty($form->dom->getElementListByXpathKey('verifyRand'));
        if(!$pwd1Exists || !$pwd2Exists || !$submitExists || !$verifyRand) return $this->failed('重置表单必要元素缺失');

        // 保存旧密码MD5，测试完成后恢复，避免影响其他测试
        $oldMD5 = $uiTester->dao->select('password')->from(TABLE_USER)->where('account')->eq('admin')->fetch('password');
        $newPwd = 'StrongPass123!';
        $form->dom->account->setValue('admin');
        $form->dom->password1->setValue($newPwd);
        $form->dom->password2->setValue($newPwd);
        $form->dom->submitBtn->click();
        $form->wait(3);

        try
        {
            $this->login('admin', $newPwd);
            $html = $this->page->getPageSource();

            $loginFailed = strpos($html, $this->lang->user->loginFailed) !== false;
            if($loginFailed) return $this->failed('管理员密码重置失败，登录失败');
        }
        catch(Exception $e)
        {
            return $this->failed('管理员密码重置失败' .  $e->getMessage());
        }
        // 恢复旧密码MD5
        $uiTester->dao->update(TABLE_USER)->set('password')->eq($oldMD5)->where('account')->eq('admin')->exec();
        return $this->success('reset页面重置密码测试成功');
    }
}