<?php
require_once dirname(__FILE__, 6) . '/test/lib/ui.php';

define('DYNAMIC_MENUS', array('today','yesterday','thisWeek','lastWeek', 'thisMonth','lastMonth'));

class dynamicTester extends tester
{
    /**
     * 校验user模块dynamic视图内容
     * Verify the content of user dynamic view.
     *
     * @param  array  $users      用户列表
     * @param  array  $actions    动态数据
     * @return object             成功或失败对象
     */
    public function verifyUserDynamicContent($users = array(), $actions = array())
    {
        if(empty($users))   return $this->failed('用户列表不能为空');
        if(empty($actions)) return $this->failed('动态数据不能为空');

        $this->login();
        $form = $this->initForm('user', 'dynamic', array('userID' => $users[0]->id), 'appIframe-system');
        $form->wait(2);

        foreach($users as $user)
        {
            if($user != $users[0])
            {
                $form->dom->userPicker->picker($user->realname);
                $form->wait(2);
                if($form->dom->userPicker->getText() != $user->realname) return $this->failed('动态页面切换用户显示失败');
            }

            foreach(DYNAMIC_MENUS as $menu)
            {
                $filteredActions = $this->filterActionsByMenu($actions[$user->account], $menu);
                // 对于admin用户，测试时产生一条额外 login记录
                $loginCount = ($user->account === 'admin' && in_array($menu, array('today','thisWeek', 'thisMonth'))) ? 1 : 0;
                $expectedCount  = count($filteredActions) + $loginCount;
                $form->dom->{$menu}->click();
                $form->wait(2);
                $actualCount = (int) filter_var($form->dom->{$menu . 'Count'}->getText(), FILTER_SANITIZE_NUMBER_INT);
                if($actualCount != $expectedCount) return $this->failed("用户'{$user->realname}'的{$menu}动态数量错误，期望{$expectedCount}条，实际{$actualCount}条");
            }
        }

        return $this->success("开源版m=user&f=dynamic测试成功");
    }

    /**
     * 根据时间菜单过滤动态列表。
     * Filter dynamic by given period menu.
     *
     * @param  array  $actions  动态列表
     * @param  string $menu     菜单
     * @return array            过滤后的动态列表
     */
    private function filterActionsByMenu($actions, $menu)
    {
        if(empty($actions)) return array();

        list($beginTS, $endTS) = $this->getBounds($menu);
        if($beginTS === null || $endTS === null) return $actions;

        $filtered = array();
        foreach($actions as $id => $action)
        {
            $dateStr = is_object($action) ? ($action->date ?? '') : ($action['date'] ?? '');
            if(empty($dateStr)) continue;

            $ts = strtotime($dateStr);
            if($ts !== false && $ts >= $beginTS && $ts < $endTS) $filtered[$id] = $action;
        }

        return $filtered;
    }

    /**
     * 计算菜单的日期边界，返回 [beginTS, endTS]，左闭右开。
     * Calculate the date bounds for a given menu period.
     *
     * @param  string $menu 菜单名称
     * @return array        日期边界数组 [beginTS, endTS]
     */
    private function getBounds($menu)
    {
        $key = strtolower($menu);

        switch($key)
        {
            case 'today':
                return array(strtotime('today midnight'), strtotime('tomorrow'));
            case 'yesterday':
                return array(strtotime('yesterday midnight'), strtotime('today midnight'));
            case 'thisweek':
                return array(strtotime('monday this week'), strtotime('monday next week'));
            case 'lastweek':
                return array(strtotime('monday last week'), strtotime('monday this week'));
            case 'thismonth':
                $begin = strtotime(date('Y-m-01'));
                $end   = strtotime(date('Y-m-01', strtotime('+1 month', $begin)));
                return array($begin, $end);
            case 'lastmonth':
                $end   = strtotime(date('Y-m-01'));
                $begin = strtotime(date('Y-m-01', strtotime('-1 month', $end)));
                return array($begin, $end);
            default:
                return array(null, null);
        }
    }
}
