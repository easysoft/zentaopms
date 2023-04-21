<?php
/* Action. */
$lang->stakeholder->common       = '干系人';
$lang->stakeholder->browse       = '干系人列表';
$lang->stakeholder->batchCreate  = '批量添加';
$lang->stakeholder->create       = '添加干系人';
$lang->stakeholder->edit         = '编辑干系人';
$lang->stakeholder->view         = '干系人详情';
$lang->stakeholder->delete       = '移除干系人';
$lang->stakeholder->createdBy    = '创建者';
$lang->stakeholder->createdDate  = '创建时间';
$lang->stakeholder->search       = '搜索';
$lang->stakeholder->browse       = '浏览列表';
$lang->stakeholder->view         = '用户信息';
$lang->stakeholder->basicInfo    = '基本信息';
$lang->stakeholder->add          = '新建';
$lang->stakeholder->communicate  = '沟通记录';
$lang->stakeholder->expect       = '期望内容';
$lang->stakeholder->progress     = '达成进展';
$lang->stakeholder->userIssue    = '干系人问题列表';
$lang->stakeholder->deleted      = '已删除';

$lang->stakeholder->viewAction = '干系人详情';

/* Fields. */
$lang->stakeholder->id          = '编号';
$lang->stakeholder->user        = '用户';
$lang->stakeholder->type        = '用户类型';
$lang->stakeholder->name        = '姓名';
$lang->stakeholder->phone       = '手机';
$lang->stakeholder->qq          = 'QQ';
$lang->stakeholder->weixin      = '微信';
$lang->stakeholder->email       = '邮箱';
$lang->stakeholder->isKey       = '关键干系人';
$lang->stakeholder->inside      = '内部干系人';
$lang->stakeholder->outside     = '外部干系人';
$lang->stakeholder->from        = '干系人类型';
$lang->stakeholder->company     = '所属公司';
$lang->stakeholder->nature      = '性格特征';
$lang->stakeholder->analysis    = '影响分析';
$lang->stakeholder->strategy    = '应对策略';
$lang->stakeholder->expect      = '期望内容';
$lang->stakeholder->progress    = '达成进展';
$lang->stakeholder->createdBy   = '创建者';
$lang->stakeholder->createdDate = '创建日期';
$lang->stakeholder->emptyTip    = '暂无问题。';

$lang->stakeholder->keyList[0] = '否';
$lang->stakeholder->keyList[1] = '是';

$lang->stakeholder->typeList['inside']  = '内部';
$lang->stakeholder->typeList['outside'] = '外部';

$lang->stakeholder->fromList['team']    = $lang->projectCommon . '团队成员';
$lang->stakeholder->fromList['company'] = '公司同事';
$lang->stakeholder->fromList['outside'] = '外部人员';

$lang->stakeholder->userEmpty           = '用户不能为空！';
$lang->stakeholder->nameEmpty           = '姓名不能为空！';
$lang->stakeholder->companyEmpty        = '所属公司不能为空！';
$lang->stakeholder->confirmDelete       = "您确定删除该干系人吗？";
$lang->stakeholder->confirmDeleteExpect = "您确定删除该干系人期望吗？";
$lang->stakeholder->createCommunicate   = '<i class="icon icon-chat-line"></i>添加沟通记录';

$lang->stakeholder->action = new stdclass();
$lang->stakeholder->action->communicate = array('main' => '$date, 由 <strong>$actor</strong> 沟通。');
