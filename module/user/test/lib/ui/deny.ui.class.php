<?php
require_once dirname(__FILE__, 6) . '/test/lib/ui.php';

class denyTester extends tester
{
    public $users;
    public $groupPrivs;

    public function __construct()
    {
        parent::__construct();
        global $uiTester;

        $users = $uiTester->dao->select('u.*, ug.`group`')
            ->from(TABLE_USER)->alias('u')
            ->leftJoin(TABLE_USERGROUP)->alias('ug')->on('u.account = ug.account')
            ->fetchAll();
        $groupPrivs = $uiTester->dao->select('*')->from(TABLE_GROUPPRIV)->fetchAll();

        foreach($users as $u) $this->users[$u->account] = $u;
        foreach($groupPrivs as $gp) $this->groupPrivs[$gp->group][$gp->module][$gp->method] = $gp;
    }

    /**
     * 校验没有组权限用户访问页面被拒
     * Verify user without group privileges accessing page fail
     *
     * @return object  成功或失败对象
     */
    public function verifyUserDenyNopriv()
    {
        foreach($this->users as $user)
        {
            $hasGroupPriv = ($user->account == 'admin') || isset($this->groupPrivs[$user->group]['my']['team']);
            if($hasGroupPriv) continue;

            $this->login($user->account);
            $form = null;
            try
            {
                $form = $this->initForm('my', 'team', [], 'appIframe-system');
                return $this->failed("用户'{$user->account}'无'{m=my&f=team}'页面访问权限却能访问页面");
            }
            catch(Exception $e)
            {
                if(!is_object($form)) $form = $this->openDenyPage('nopriv', array('module' => 'my', 'method' => 'team'));
                else $form = $this->loadPage('user', 'deny');
                $form->wait(1);

                $header   = $form->dom->denyHeader->getText();
                $denyText = $form->dom->denyAlertMsg->getText();
                if($header == $user->account . ' ' . $this->lang->user->deny) continue;
                return $this->failed("用户'{$user->account}'无'{m=my&f=team}'页面访问权限却未显示拒绝框：'{$denyText}'");
            }
        }
        return $this->success('开源版m=user&f=deny测试成功');
    }

    /**
     * 校验没有视图权限用户直接访问页面被拒
     * Verify user without view privileges accessing page fail
     *
     * @param string $module  例如 'qa' 或 'bug'
     * @param string $method  例如 'index' 或 'browse'
     * @return object         成功或失败对象
     */
    public function verifyUserDenyNoview($module = 'qa', $method = 'index')
    {
        // 使用 user1（绑定group=2）具备方法权限但缺少视图权限。
        $this->login('user1');

        // 直接打开 deny(noview) 页面；控制器根据 rights 与 acls['views'] 判断 noview。
        $form = $this->openDenyPage('noview', array('module' => $module, 'method' => $method));
        $form->wait(1);

        $displayed = strip_tags($form->dom->denyAlertMsg->getText());
        $expected  = strip_tags(sprintf($this->lang->user->errorView, $this->lang->{$module}->common));
        if($displayed != $expected) return $this->failed("报错信息, 期望：|{$expected}|，实际：|{$displayed}|");
        return $this->success('开源版m=user&f=deny(noview)测试成功');
    }

    /**
     * 校验通过路由触发 noview
     * Verify user without view privileges accessing page fail by route
     *
     * @param string $module  例如 'qa' 或 'bug'
     * @param string $method  例如 'index' 或 'browse'
     * @return object         成功或失败对象
     */
    public function verifyUserDenyNoviewByRoute(string $module = 'qa', string $method = 'index')
    {
        $loginAccount = 'user1';
        $this->login($loginAccount);

        try
        {
            // 访问真实路由，预期由于视图不可见导致切换 iframe 失败并进入拒绝页。
            $form = $this->initForm($module, $method, array(), 'appIframe-system');
            $form->wait(1);
            return $this->failed("预期视图不可见触发拒绝访问，但实际可进入 {$module}/{$method}");
        }
        catch(Exception $e)
        {
            $form = $this->openDenyPage('noview', array('module' => $module, 'method' => $method));
            $form->wait(1);

            $header = $form->dom->denyHeader->getText();
            if(strpos($header, $loginAccount) === false) return $this->failed('拒绝框标题未包含账户名');

            $displayed = strip_tags($form->dom->denyAlertMsg->getText());
            $expected  = strip_tags(sprintf($this->lang->user->errorView, $this->lang->user->roleList->{$module}));
            if($displayed != $expected) return $this->failed("报错信息，期望：|{$expected}|，实际：|{$displayed}|");
        }

        return $this->success('开源版m=user&f=deny(noview 路由)测试成功');
    }

    /**
     * 以 GET 方式直接打开 deny 页面，避免 initForm 的 iframe 检查。
     * Open deny page by GET method, avoid iframe check in initForm.
     *
     * @param string $denyType  拒绝类型，例如 'noview' 或 'nopriv'
     * @param array  $params    路由参数，例如 ['module' => 'qa', 'method' => 'index']
     * @return object           成功或失败对象
     */
    private function openDenyPage($denyType, $params = array())
    {
        $webRoot = $this->getWebRoot();
        $url = "index.php?m=user&f=deny";
        // 按控制器签名顺序传参，避免 denyType 被当成第一个未命名参数覆盖 module。
        if(isset($params['module']))  $url .= "&module="  . $params['module'];
        if(isset($params['method']))  $url .= "&method="  . $params['method'];
        if(isset($params['referer'])) $url .= "&referer=" . $params['referer'];
        $url .= "&denyType=" . $denyType;

        $this->page->openURL($webRoot . $url);
        return $this->loadPage('user', 'deny');
    }
}
