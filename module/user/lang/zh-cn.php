<?php
/**
 * The user module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: zh-cn.php 5053 2013-07-06 08:17:37Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->user->common      = '用户';
$lang->user->id          = '用户编号';
$lang->user->company     = '所属公司';
$lang->user->dept        = '所属部门';
$lang->user->account     = '用户名';
$lang->user->password    = '密码';
$lang->user->password2   = '请重复密码';
$lang->user->role        = '职位';
$lang->user->group       = '分组';
$lang->user->realname    = '真实姓名';
$lang->user->nickname    = '昵称';
$lang->user->commiter    = '源代码帐号';
$lang->user->birthyear   = '出生年';
$lang->user->gender      = '性别';
$lang->user->email       = '邮箱';
$lang->user->basicInfo   = '基本信息';
$lang->user->accountInfo = '帐号信息';
$lang->user->verify      = '安全验证';
$lang->user->contactInfo = '联系信息';
$lang->user->skype       = 'Skype';
$lang->user->qq          = 'QQ';
$lang->user->yahoo       = '雅虎通';
$lang->user->gtalk       = 'GTalk';
$lang->user->wangwang    = '旺旺';
$lang->user->mobile      = '手机';
$lang->user->phone       = '电话';
$lang->user->address     = '通讯地址';
$lang->user->zipcode     = '邮编';
$lang->user->join        = '入职日期';
$lang->user->visits      = '访问次数';
$lang->user->ip          = '最后IP';
$lang->user->last        = '最后登录';
$lang->user->ranzhi      = '然之帐号';
$lang->user->ditto       = '同上';
$lang->user->originalPassword = '原密码';
$lang->user->verifyPassword   = '请输入你的密码';
$lang->user->resetPassword    = '忘记密码';

$lang->user->index           = "用户视图首页";
$lang->user->view            = "用户详情";
$lang->user->create          = "添加用户";
$lang->user->batchCreate     = "批量添加用户";
$lang->user->edit            = "编辑用户";
$lang->user->batchEdit       = "批量编辑";
$lang->user->unlock          = "解锁用户";
$lang->user->delete          = "删除用户";
$lang->user->unbind          = "解除然之绑定";
$lang->user->login           = "用户登录";
$lang->user->mobileLogin     = "手机访问";
$lang->user->editProfile     = "修改档案";
$lang->user->deny            = "访问受限";
$lang->user->confirmDelete   = "您确定删除该用户吗？";
$lang->user->confirmUnlock   = "您确定解除该用户的锁定状态吗？";
$lang->user->confirmUnbind   = "您确定解除该用户跟然之的绑定吗？";
$lang->user->relogin         = "重新登录";
$lang->user->asGuest         = "游客访问";
$lang->user->goback          = "返回前一页";
$lang->user->deleted         = '(已删除)';

$lang->user->profile      = '档案';
$lang->user->project      = $lang->projectCommon;
$lang->user->task         = '任务';
$lang->user->bug          = '缺陷';
$lang->user->test         = '测试';
$lang->user->testTask     = '测试任务';
$lang->user->testCase     = '测试用例';
$lang->user->todo         = '待办';
$lang->user->story        = '需求';
$lang->user->dynamic      = '动态';

$lang->user->openedBy    = '由他创建';
$lang->user->assignedTo  = '指派给他';
$lang->user->finishedBy  = '由他完成';
$lang->user->resolvedBy  = '由他解决';
$lang->user->closedBy    = '由他关闭';
$lang->user->reviewedBy  = '由他评审';
$lang->user->canceledBy  = '由他取消';

$lang->user->testTask2Him = '负责版本';
$lang->user->case2Him     = '给他的用例';
$lang->user->caseByHim    = '他建的用例';

$lang->user->errorDeny    = "抱歉，您无权访问『<b>%s</b>』模块的『<b>%s</b>』功能。请联系管理员获取权限。点击后退返回上页。";
$lang->user->loginFailed  = "登录失败，请检查您的用户名或密码是否填写正确。";
$lang->user->lockWarning  = "您还有%s次尝试机会。";
$lang->user->loginLocked  = "密码尝试次数太多，请联系管理员解锁，或%s分钟后重试。";
$lang->user->weakPassword = "您的密码强度小于系统设定。";

$lang->user->roleList['']       = '';
$lang->user->roleList['dev']    = '研发';
$lang->user->roleList['qa']     = '测试';
$lang->user->roleList['pm']     = '项目经理';
$lang->user->roleList['po']     = '产品经理';
$lang->user->roleList['td']     = '研发主管';
$lang->user->roleList['pd']     = '产品主管';
$lang->user->roleList['qd']     = '测试主管';
$lang->user->roleList['top']    = '高层管理';
$lang->user->roleList['others'] = '其他';

$lang->user->genderList['m'] = '男';
$lang->user->genderList['f'] = '女';

$lang->user->passwordStrengthList[0] = "<span style='color:red'>弱</span>";
$lang->user->passwordStrengthList[1] = "<span style='color:#000'>中</span>";
$lang->user->passwordStrengthList[2] = "<span style='color:green'>强</span>";

$lang->user->statusList['active'] = '正常';
$lang->user->statusList['delete'] = '删除';

$lang->user->keepLogin['on']      = '保持登录';
$lang->user->loginWithDemoUser    = '使用demo帐号登录：';

$lang->user->placeholder = new stdclass();
$lang->user->placeholder->account   = '英文、数字和下划线的组合，三位以上';
$lang->user->placeholder->password1 = '六位以上';
$lang->user->placeholder->role      = '职位影响内容和用户列表的顺序。';
$lang->user->placeholder->group     = '分组决定用户的权限列表。';
$lang->user->placeholder->commiter  = '版本控制系统(subversion)中的帐号';
$lang->user->placeholder->verify    = '需要输入你的密码加以验证';

$lang->user->placeholder->passwordStrength[1] = '6位以上，包含大小写字母，数字。';
$lang->user->placeholder->passwordStrength[2] = '10位以上，包含大小写字母，数字，特殊字符。';

$lang->user->error = new stdclass();
$lang->user->error->account       = "ID %s，英文、数字和下划线的组合，三位以上";
$lang->user->error->accountDupl   = "ID %s，该用户名已经存在";
$lang->user->error->realname      = "ID %s，必须填写真实姓名";
$lang->user->error->password      = "ID %s，密码必须六位以上";
$lang->user->error->mail          = "ID %s，请填写正确的邮箱地址";
$lang->user->error->role          = "ID %s，职位不能为空";

$lang->user->error->verifyPassword   = "安全验证密码错误，请输入你的登录密码";
$lang->user->error->originalPassword = "原密码不正确";

$lang->user->contacts = new stdclass();
$lang->user->contacts->common   = '联系人';
$lang->user->contacts->listName = '列表名称';

$lang->user->contacts->manage        = '维护列表';
$lang->user->contacts->contactsList  = '已有列表';
$lang->user->contacts->selectedUsers = '选择用户';
$lang->user->contacts->selectList    = '选择列表';
$lang->user->contacts->createList    = '创建新列表';
$lang->user->contacts->noListYet     = '还没有创建任何列表，请先创建联系人列表。';
$lang->user->contacts->confirmDelete = '您确定要删除这个列表吗？';
$lang->user->contacts->or            = ' 或者 ';

$lang->user->resetFail       = "重置密码失败，检查用户名是否存在！";
$lang->user->resetSuccess    = "重置密码成功，请用新密码登录。";
$lang->user->noticeResetFile = "<h5>普通用户请联系管理员重置密码</h5>
    <h5>管理员请登录禅道所在的服务器，创建<span>%s</span>文件。</h5>
    <p>注意：</p>
    <ol>
    <li>文件内容为空。</li>
    <li>如果之前文件存在，删除之后重新创建。</li>
    </ol>"; 
