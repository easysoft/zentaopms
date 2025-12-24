<?php
require_once dirname(__FILE__, 6) . '/test/lib/ui.php';

define('PROFILE_ITEMS', ['realname', 'gender', 'account', 'email', 'dept', 'role', 'join', 'group',
                         'mobile', 'weixin', 'phone', 'qq', 'zipcode', 'address',
                         'commiter', 'skype', 'visits', 'whatsapp', 'last', 'slack', 'ip', 'dingding']);

class profileTester extends tester
{
    public $users;

    public function __construct()
    {
        parent::__construct();
        $this->login();
        global $uiTester;
        $records = $uiTester->dao->select('u.*, g.name as `group`, d.name as dept')
            ->from(TABLE_USER)->alias('u')
            ->leftJoin(TABLE_USERGROUP)->alias('ug')->on('u.account = ug.account')
            ->leftJoin(TABLE_GROUP)->alias('g')->on('ug.group = g.id')
            ->leftJoin(TABLE_DEPT)->alias('d')->on('u.dept = d.id')
            ->fetchAll();
        $this->users = array();
        foreach($records as $record)
        {
            $this->users[$record->account] = $record;
        }
    }

    /**
     * 校验user模块profile视图的基本信息展示
     * Verify user profile basic info display
     *
     * @access public
     * @return object 成功或失败对象
     */
    public function verifyUserProfile()
    {
        if(empty($this->users) || empty($this->users['admin'])) return $this->failed('用户对象或ID不能为空');

        $form = $this->initForm('user', 'profile', array('userID' => $this->users['admin']->id), 'appIframe-system');
        $form->wait(2);

        foreach($this->users as $account => $user)
        {
            if($account != 'admin')
            {
                $form->dom->userPicker->picker($user->realname);
                $form->wait(2);
                if($form->dom->userPicker->getText() != $user->realname) return $this->failed("档案页面切换用户{$user->realname}显示失败");
            }
            foreach(PROFILE_ITEMS as $item)
            {
                $itemName = isset($this->lang->user->{$item}) ? $this->lang->user->{$item} : $item;
                switch($item)
                {
                    case 'gender':
                        $expected = $this->lang->user->genderList->{$user->gender} ?? $user->gender;
                        break;
                    case 'role':
                        $expected = $this->lang->user->roleList->{$user->role} ?? '';
                        break;
                    case 'last':
                        // last字段现在是datetime类型，直接使用或格式化
                        $expected = is_numeric($user->last) ? date('Y-m-d H:i:s', $user->last) : substr($user->last, 0, 19);
                        break;
                    default:
                        $expected = $user->{$item};
                        break;
                }
                $displayed = $form->dom->{$item}->getText();
                if($displayed != $expected) return $this->failed("档案页面'{$itemName}'显示失败，期望：{$expected}，实际：{$displayed}");
            }
        }

        return $this->success('开源版m=user&f=profile测试成功');
    }
}
