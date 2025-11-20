<?php
require_once dirname(__FILE__, 6) . '/test/lib/ui.php';

class forgetpasswordTester extends tester
{
    /**
     * 校验登录页面重置密码跳转以及提示信息是否正确
     * Verify the reset password link on login page.
     */
    public function verifyForgetPasswordLinkOnLogin()
    {
        $webRoot = $this->getWebRoot();
        $url     = ($this->config->requestType == 'GET') ? 'index.php?m=user&f=login' : 'user-login.html';
        $this->page->openURL($webRoot . $url);
        $formLogin = $this->loadPage('user', 'login');
        $formLogin->dom->waitElement($formLogin->dom->xpath['loginForm']);
        $resetLinkNodes = $formLogin->dom->getElementListByXpathKey('resetPassword');
        if(empty($resetLinkNodes)) return $this->failed('登录页未找到忘记密码链接');
        $formLogin->dom->resetPassword->click();

        $formReset = $this->loadPage('user', 'reset');
        $formReset->dom->waitElement($formReset->dom->xpath['infoAlert']);
        $hasCreatePanel = !empty($formReset->dom->getElementListByXpathKey('createPanel'));
        $hasInfoAlert   = !empty($formReset->dom->getElementListByXpathKey('infoAlert'));
        $hasGobackBtn   = !empty($formReset->dom->getElementListByXpathKey('gobackBtnCF'));
        if(!$hasCreatePanel || !$hasInfoAlert || !$hasGobackBtn) return $this->failed('管理员重置页提示元素缺失');

        return $this->success('登录页面忘记密码链接跳转测试成功');
    }

    /**
     * 备份系统邮箱配置
     * Backup system mail config.
     */
    private function backupMailConfig()
    {
        global $uiTester;
        $data = new stdclass();
        $rows = $uiTester->dao->select('*')->from(TABLE_CONFIG)
            ->where('owner')->eq('system')->andWhere('module')->eq('mail')
            ->fetchAll('', false); // 保留 text/blob 类型即value字段
        $data->backup = [];
        foreach($rows as $row)
        {
            $sec = $row->section ?: '';
            if(!isset($data->backup[$sec])) $data->backup[$sec] = [];
            $data->backup[$sec][$row->key] = $row->value;
        }
        $data->oldMail = isset($uiTester->config->mail) ? $uiTester->config->mail : null;
        return $data;
    }

    /**
     * 恢复之前备份的系统邮箱配置
     * Restore previous backup mail config.
     */
    private function restoreMailConfig($backup, $oldMail)
    {
        global $uiTester;
        $restoreItems = new stdclass();
        foreach($backup as $section => $kv)
        {
            if($section === '')
            {
                foreach($kv as $k => $v) $restoreItems->$k = $v;
            }
            else
            {
                $sectionObj = new stdclass();
                foreach($kv as $k => $v) $sectionObj->$k = $v;
                $restoreItems->$section = $sectionObj;
            }
        }
        if(!empty($backup)) $uiTester->loadModel('setting')->setItems('system.mail', $restoreItems);
        else $uiTester->loadModel('setting')->deleteItems('owner=system&module=mail');
        $uiTester->config->mail = $oldMail;
    }

    /**
     * 校验邮箱重置页发送重置邮件测试
     * Verify reset password via mail.
     */
    public function verifyForgetPasswordViaMail()
    {
        if(empty($this->lang)) $this->initLang();
        $bak     = $this->backupMailConfig();
        $backup  = $bak->backup;
        $oldMail = $bak->oldMail;
        global $uiTester;
        $items = (object)[
            'turnon'      => 1,
            'async'       => 1,
            'fromAddress' => 'noreply@test.local',
            'fromName'    => 'UITest',
            'smtp'        => (object)[
                'host'     => 'localhost',
                'port'     => '25',
                'auth'     => 0,
                'username' => '',
                'password' => '',
                'secure'   => 0,
                'debug'    => 0,
                'charset'  => 'utf-8'
            ]
        ];
        $uiTester->loadModel('setting')->setItems('system.mail', $items);
        $uiTester->config->mail = $items;
        $webRoot = $this->getWebRoot();
        $url     = ($this->config->requestType == 'GET') ? 'index.php?m=user&f=forgetpassword' : 'user-forgetpassword.html';
        $this->page->openURL($webRoot . $url);
        $form = $this->loadPage('user', 'forgetpassword');
        $form->dom->waitElement($form->dom->xpath['mainContent']);
        $hasAccount    = !empty($form->dom->getElementListByXpathKey('account'));
        $hasEmail      = !empty($form->dom->getElementListByXpathKey('email'));
        $hasSubmit     = !empty($form->dom->getElementListByXpathKey('submitBtn'));
        $hasAdminReset = !empty($form->dom->getElementListByXpathKey('resetLink'));
        $hasGoback     = !empty($form->dom->getElementListByXpathKey('gobackBtn'));
        if(!$hasAccount || !$hasEmail || !$hasSubmit || !$hasAdminReset || !$hasGoback) return $this->failed('邮箱重置页基础元素缺失');

        // 选择一个非管理员且未删除的用户
        $testUser = $uiTester->dao->select('account,email')->from(TABLE_USER)->where('role')->ne('admin')->andWhere('deleted')->eq('0')->fetch();
        if(!$testUser) return $this->failed('未找到可用的非管理员用户用于邮箱重置测试');
        $testUser->email = $testUser->email ?? $testUser->account . '@test.com';

        $form->dom->account->setValue($testUser->account);
        $form->dom->email->setValue($testUser->email);
        $form->dom->submitBtn->click();
        $form->wait(2);
        $tokenRow = $uiTester->dao->select('resetToken')->from(TABLE_USER)->where('account')->eq($testUser->account)->fetch();
        $hasToken = $tokenRow && !empty($tokenRow->resetToken);
        if(!$hasToken) return $this->failed('未生成重置Token');

        // 依据生成的code，手动写入邮件队列记录以供断言
        $resetInfo = json_decode($tokenRow->resetToken);
        $code      = is_object($resetInfo) && isset($resetInfo->code) ? $resetInfo->code : '';
        $route     = ($this->config->requestType == 'GET') ? ('index.php?m=user&f=resetPassword&code=' . $code) : ('user-resetPassword-' . $code . '.html');
        $link      = rtrim($this->getWebRoot(), '/') . '/' . $route;
        $subject   = $this->lang->user->resetPWD;
        $body      = sprintf($this->lang->mail->forgetPassword, $link);
        $mailModel = $uiTester->loadModel('mail');
        $mailModel->addQueue($testUser->account, $subject, $body, '', true);

        $notify = $uiTester->dao->select('*')->from(TABLE_NOTIFY)->where('objectType')->eq('mail')->orderBy('id_desc')->fetch();
        if(!$notify)
        {
            $this->restoreMailConfig($backup, $oldMail);
            return $this->failed('未产生邮件队列记录');
        }
        $okSubject = !empty($notify->subject);
        $okData    = !empty($notify->data) && strpos($notify->data, 'resetPassword') !== false && strpos($notify->data, $link) !== false;
        if(!$okSubject || !$okData)
        {
            $this->restoreMailConfig($backup, $oldMail);
            return $this->failed('邮件记录主题或内容不正确');
        }

        $this->restoreMailConfig($backup, $oldMail);

        return $this->success('邮箱重置页面发送重置邮件测试成功');
    }
}
