<?php
require_once dirname(__FILE__, 6) . '/test/lib/ui.php';

define('COLUMNS', ['realname', 'account', 'gender', 'role', 'phone', 'qq', 'email', 'last', 'visits']);
define('PAGESIZE', array(5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 100, 200, 500, 1000, 2000));

class myTeamTester extends tester
{
    public $users;
    public $groupPriv;
    public $pageTitle;

    public function __construct()
    {
        parent::__construct();
        global $uiTester;
        $users = $uiTester->dao->select('u.*, g.`group` as `group`')
                ->from(TABLE_USER)->alias('u')
                ->leftJoin(TABLE_USERGROUP)->alias('g')->on('u.account = g.account')
                ->fetchAll();
        $groupPriv = $uiTester->dao->select('*')->from(TABLE_GROUPPRIV)->fetchAll();

        $this->pageTitle = $this->lang->my->team ?? '团队';
        $this->users     = array();
        $this->groupPriv = array();
        foreach($users as $u) $this->users[$u->account] = $u;
        foreach($groupPriv as $gp) $this->groupPriv[$gp->group][$gp->module][$gp->method] = $gp;
    }

    /**
     * 访问我的团队页并做基础断言。
     *
     * @access public
     * @return object 成功或失败对象
     */
    public function verifyTeamPage()
    {
        foreach($this->users as $user)
        {
            $this->login($user->account);
            // 用户登录后手动把访问次数加1, 因为我们没有再进行数据库查询
            $this->users[$user->account]->visits++;

            $hasPermission = ($user->account == 'admin') || isset($this->groupPriv[$user->group]['my']['team']);

            if($hasPermission){
                $form = $this->initForm('my', 'team', [], 'appIframe-system');
                $form->wait(2);
                $ret = $this->verifyContent($form, $user->account);
                if($ret) return $ret;
                $ret = $this->verifyPagination($form, count($this->users), 5);
                if($ret) return $ret;
            }
            else
            {
                try
                {
                    $form = $this->initForm('my', 'team', [], 'appIframe-system');
                    return $this->failed("用户'{$user->account}'无'{$this->pageTitle}'页面访问权限却能访问页面");
                }
                catch(Exception $e)
                {
                    $form->wait(2);
                    // 没有权限的情况要在异常中检查
                    $msg = $form->dom->denied->getText();
                    // 用户没有team权限应该显示拒绝框
                    if($msg == $user->account . " " . $this->lang->user->deny) continue;
                    return $this->failed("用户'{$user->account}'无'{$this->pageTitle}'页面访问权限却未显示拒绝框");
                }
            }
        }
        return $this->success('开源版m=my&f=team测试成功');
    }

    /**
     * 验证页面内容
     * verify content
     *
     * @param  object $form 页面对象
     * @param  string $user 用户account
     * @return object       成功返回null或失败返回对象
     */
    private function verifyContent($form, $user)
    {
        $currentPageSize = (int) filter_var($form->dom->pagerSizeMenu->getText(), FILTER_SANITIZE_NUMBER_INT);
        if(count($this->users) > $currentPageSize)
        {
            $form->dom->pagerSizeMenu->click();
            $form->wait(1);
            $form->dom->dropdownPicker(max(PAGESIZE));
            $form->wait(1);
        }

        $display = array();
        foreach(COLUMNS as $c) $display[$c] = $form->dom->getElementListByXpathKey($c, true);

        if(count($display['account']) != count($this->users)) return $this->failed("用户'{$user}'的'{$this->pageTitle}'页面显示数量不符: 期望" . count($this->users) . "条，显示" . count($display) . "条");
        foreach($display['account'] as $i => $account)
        {
            if(!isset($this->users[$account])) return $this->failed("用户'{$user}'的'{$this->pageTitle}'页面显示了不应该显示的账号: {$account}");
            foreach(COLUMNS as $c)
            {
                $header = $this->lang->user->{$c} ?? $c;
                $displayed = $display[$c][$i] ?? '';
                $expected  =  $this->users[$account]->{$c} ?? '';
                switch($c)
                {
                    case 'gender':
                        $expected = $this->lang->user->genderList->{$expected} ?? $expected;
                        break;
                    case 'role':
                        $expected = $this->lang->user->roleList->{$expected} ?? $expected;
                        break;
                    case 'last':
                        $expected = date('Y-m-d', $expected);
                        break;
                }
                if($displayed != $expected) return $this->failed("用户'{$user}'的'{$this->pageTitle}'页面'{$account}'的'{$header}'不匹配，期望'{$expected}'，实际'{$displayed}'");
            }
        }
        return null;
    }

    /**
     * 验证分页
     * verify pagination
     *
     * @param  object $form     页面对象
     * @param  int    $total    期望的总数量
     * @param  int    $pageSize 每页数量
     * @return object           成功返回null或失败返回对象
     */
    private function verifyPagination($form, $total, $pageSize = 5)
    {
        $pages = (int) ceil($total / $pageSize);

        if($total > $pageSize)
        {
            $form->dom->pagerSizeMenu->click();
            $form->wait(1);
            $form->dom->dropdownPicker($pageSize);
            $form->wait(1);
            $form->dom->firstPage->click();
            $form->wait(1);
        }

        for($i = 1; $i <= $pages; $i++)
        {
            $totalActual   = count($form->dom->getElementListByXpathKey('id' ));
            $totalExpected = ($pages === 1) ? $total : ($i < $pages ? $pageSize : ($total - $pageSize * ($pages - 1)));

            if($totalActual !== $totalExpected) return $this->failed("分页第{$i}页数量不匹配：期望{$totalExpected}，实际{$totalActual}");

            if($i < $pages && $form->dom->nextPage)
            {
                $form->dom->nextPage->click();
                $form->wait(1);
            }
        }
        return null;
    }
}
