<?php
$lang->space->browse     = '空间列表';
$lang->space->create     = '创建空间';
$lang->space->edit       = '编辑空间';
$lang->space->view       = '空间详情';
$lang->space->delete     = '删除空间';
$lang->space->members    = '成员';
$lang->space->memberList = '成员列表';

$lang->space->group             = '权限';
$lang->space->groupList         = '权限列表';
$lang->space->createGroup       = '添加分组';
$lang->space->importGroup       = '导入分组';
$lang->space->managePriv        = '分配权限';
$lang->space->editGroup         = '编辑分组';
$lang->space->deleteGroup       = '删除分组';
$lang->space->manageMembers     = '管理成员';
$lang->space->removeMember      = '解绑成员';
$lang->space->manageGroupMember = '管理分组成员';

$lang->space->name         = '名称';
$lang->space->code         = '唯一标识';
$lang->space->manager      = '管理员';
$lang->space->createdDate  = '创建时间';
$lang->space->desc         = '描述';
$lang->space->repo         = '代码库';
$lang->space->artifactrepo = '制品库';
$lang->space->pipeline     = '流水线';
$lang->space->system       = '应用';
$lang->space->acl          = '访问控制';
$lang->space->deleted      = '已删除';
$lang->space->account      = '姓名';
$lang->space->team         = '团队';
$lang->space->auth         = '权限控制';
$lang->space->role         = '角色';
$lang->space->defaultSpace = '默认空间';

$lang->space->memberGroup    = '分组';
$lang->space->accessRepo     = '可访问的代码库';
$lang->space->accessArtifact = '可访问的制品库';
$lang->space->sourceSpace    = '源分组空间';
$lang->space->sourceGroup    = '源分组';

$lang->space->aclList = array();
$lang->space->aclList['open']    = '公开';
$lang->space->aclList['private'] = '私有';

$lang->space->aclNoticeList = array();
$lang->space->aclNoticeList['open']    = '公开(有空间视图的权限即可访问该空间)';
$lang->space->aclNoticeList['private'] = '私有(仅成员、空间管理员可访问该空间)';

$lang->space->authList = array();
$lang->space->authList['extend'] = '继承';
$lang->space->authList['reset']  = '重新定义';

$lang->space->authNoticeList = array();
$lang->space->authNoticeList['extend'] = '继承(取系统权限与空间权限合集)';
$lang->space->authNoticeList['reset']  = '重新定义(只取空间权限)';

$lang->space->roleList = array();
$lang->space->roleList['manager'] = '管理员';
$lang->space->roleList['member']  = '成员';

$lang->space->notice = new stdclass();
$lang->space->notice->noSpaces              = '暂无任何空间';
$lang->space->notice->confirmDeleteSpace    = '您确定要删除该空间吗？';
$lang->space->notice->deleteFail            = '空间下存在代码库或制品库, 无法删除。';
$lang->space->notice->apiCreateFail         = '创建空间失败。';
$lang->space->notice->accessRepo            = '仅展示用户可访问的非公开代码库';
$lang->space->notice->accessArtifact        = '仅展示用户可访问的非公开制品库';
$lang->space->notice->confirmRemoveMember   = '您确定从该空间中移除该用户吗？';
$lang->space->notice->confirmDelete         = '您确定删除“ %s” 权限分组吗？';
$lang->space->notice->managerMemberConflict = '%s为空间用户，如需配置为管理员可先移除用户。';

$lang->space->placeholder       = new stdclass();
$lang->space->placeholder->desc = '请输入空间描述';

$lang->space->tips      = '提示';
$lang->space->afterInfo = "空间添加成功，您现在可以进行以下操作：";
$lang->space->setMember = '设置成员';
$lang->space->setACL    = '设置权限';
$lang->space->goback    = "返回空间列表";
