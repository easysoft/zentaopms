<?php
/**
 * The user module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->user->common    = '用户';
$lang->user->id        = '用户编号';
$lang->user->company   = '所属公司';
$lang->user->dept      = '所属部门';
$lang->user->account   = '用户名';
$lang->user->password  = '密码';
$lang->user->password2 = '请重复密码';
$lang->user->role      = '角色';
$lang->user->realname  = '真实姓名';
$lang->user->nickname  = '昵称';
$lang->user->commiter  = '源代码帐号';
$lang->user->avatar    = '头像';
$lang->user->birthyear = '出生年';
$lang->user->gender    = '性别';
$lang->user->email     = '邮箱';
$lang->user->msn       = 'MSN';
$lang->user->qq        = 'QQ';
$lang->user->yahoo     = '雅虎通';
$lang->user->gtalk     = 'GTalk';
$lang->user->wangwang  = '旺旺';
$lang->user->mobile    = '手机';
$lang->user->phone     = '电话';
$lang->user->address   = '通讯地址';
$lang->user->zipcode   = '邮编';
$lang->user->join      = '加入日期';
$lang->user->visits    = '访问次数';
$lang->user->ip        = '最后IP';
$lang->user->last      = '最后登录';
$lang->user->status    = '状态';
$lang->user->ditto     = '同上';

$lang->user->index           = "用户视图首页";
$lang->user->view            = "用户详情";
$lang->user->create          = "添加用户";
$lang->user->batchCreate     = "批量添加用户";
$lang->user->read            = "查看用户";
$lang->user->edit            = "编辑用户";
$lang->user->batchEdit       = "批量编辑";
$lang->user->unlock          = "解锁用户";
$lang->user->update          = "编辑用户";
$lang->user->delete          = "删除用户";
$lang->user->browse          = "浏览用户";
$lang->user->login           = "用户登录";
$lang->user->userView        = "人员视图";
$lang->user->editProfile     = "修改个人信息";
$lang->user->editPassword    = "修改密码";
$lang->user->deny            = "访问受限";
$lang->user->confirmDelete   = "您确定删除该用户吗？";
$lang->user->confirmActivate = "您确定激活该用户吗？";
$lang->user->confirmUnlock   = "您确定解除该用户的锁定状态吗？";
$lang->user->relogin         = "重新登录";
$lang->user->asGuest         = "游客访问";
$lang->user->goback          = "返回前一页";
$lang->user->allUsers        = '全部用户';
$lang->user->deleted         = '(已删除)';
$lang->user->select          = '--请选择用户--';

$lang->user->profile     = '档案';
$lang->user->project     = '项目';
$lang->user->task        = '任务';
$lang->user->bug         = '缺陷';
$lang->user->todo        = '待办';
$lang->user->story       = '需求';
$lang->user->team        = '团队';
$lang->user->dynamic     = '动态';
$lang->user->ajaxGetUser = '接口:获得用户';
$lang->user->editProfile = '修改信息';

$lang->user->errorDeny   = "抱歉，您无权访问『<b>%s</b>』模块的『<b>%s</b>』功能。请联系管理员获取权限。点击后退返回上页。";
$lang->user->loginFailed = "登录失败，请检查您的用户名或密码是否填写正确。";
$lang->user->lockWarning = "您还有%s次尝试机会。";
$lang->user->loginLocked = "密码尝试次数太多，请联系管理员解锁，或%s分钟后重试。";

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

$lang->user->statusList['active'] = '正常';
$lang->user->statusList['delete'] = '删除';

$lang->user->keepLogin['on']      = '保持登录';
$lang->user->loginWithDemoUser    = '使用demo账号登录：';

$lang->user->placeholder = new stdclass();
$lang->user->placeholder->account   = '英文、数字和下划线的组合，三位以上';
$lang->user->placeholder->password1 = '六位以上';
$lang->user->placeholder->join      = '入职日期';
$lang->user->placeholder->commiter  = '版本控制系统(subversion)中的帐号';

$lang->user->error = new stdclass();
$lang->user->error->account       = "ID %s，用户名必须三位以上";
$lang->user->error->accountDupl   = "ID %s，该用户名已经存在";
$lang->user->error->realname      = "ID %s，必须填写真实姓名";
$lang->user->error->password      = "ID %s，密码必须六位以上";
$lang->user->error->mail          = "ID %s，请填写正确的邮箱地址";
$lang->user->error->role          = "ID %s，角色不能为空";

$lang->user->contacts = new stdclass();
$lang->user->contacts->common   = '联系人';
$lang->user->contacts->listName = '列表名称';
$lang->user->contacts->userList = '联系人列表';

$lang->user->contacts->manage       = '维护列表';
$lang->user->contacts->contactsList = '已有列表';
$lang->user->contacts->selectedUsers= '选择用户';
$lang->user->contacts->selectList   = '选择列表';
$lang->user->contacts->appendToList = '追加至已有列表：';
$lang->user->contacts->createList   = '创建新列表：';
$lang->user->contacts->noListYet    = '还没有创建任何列表。';
$lang->user->contacts->confirmDelete= '您确定要删除这个列表吗？';
$lang->user->contacts->or           = ' 或者 ';
