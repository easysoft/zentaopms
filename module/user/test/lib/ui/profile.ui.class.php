<?php
require_once dirname(__FILE__, 6) . '/test/lib/ui.php';

define('PROFILE_ITEMS', ['realname', 'gender', 'account', 'email', 'dept', 'role', 'join', 'group',
                         'mobile', 'weixin', 'phone', 'qq', 'zipcode', 'address',
                         'commiter', 'skype', 'visits', 'whatsapp', 'last', 'slack', 'ip', 'dingding']);

class profileTester extends tester
{
    /**
     * 校验user模块profile视图的基本信息展示
     * Verify user profile basic info display
     *
     * @param  object $user 用户对象（包含id/account/realname/role/gender等）
     * @access public
     * @return object 成功或失败对象
     */
    public function verifyUserProfile($users)
    {
        if(empty($users) || empty($users['admin'])) return $this->failed('用户对象或ID不能为空');

        $this->login();
        $form = $this->initForm('user', 'profile', array('userID' => $users['admin']->id), 'appIframe-system');
        $form->wait(2);

        foreach($users as $account => $user)
        {
            if($account != 'admin')
            {
                $form->dom->userPicker->picker($user->realname);
                $form->wait(2);
                if($form->dom->userPicker->getText() != $user->realname) return $this->failed("档案页面切换用户{$user->realname}显示失败");
            }
            else
            {
                //对于admin用户，我们需要获取最新的last/ip/visits喜喜，因为它测试登录的时候，改变了这些字段
                global $uiTester;
                $adminUpdate = $uiTester->dao->select('last,ip,visits')->from(TABLE_USER)->where('account')->eq('admin')->fetch();
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
                    case 'visits':
                        $expected = ($user->account == 'admin') ? $adminUpdate->visits : $user->visits;
                        break;
                    case 'last':
                        if($user->account == 'admin') $expected = date('Y-m-d H:i:s', $adminUpdate->last);
                        else $expected = date('Y-m-d H:i:s', $user->last);
                        break;
                    case 'ip':
                        $expected = ($user->account == 'admin') ? $adminUpdate->ip : $user->ip;
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