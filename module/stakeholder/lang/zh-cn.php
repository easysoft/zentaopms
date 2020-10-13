<?php
/* Action. */
$lang->stakeholder->common       = '干系人';
$lang->stakeholder->browse       = '干系人列表';
$lang->stakeholder->batchCreate  = '批量添加';
$lang->stakeholder->create       = '添加干系人';
$lang->stakeholder->edit         = '编辑干系人';
$lang->stakeholder->view         = '干系人详情';
$lang->stakeholder->plan         = '介入计划表';
$lang->stakeholder->delete       = '删除干系人';
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
$lang->stakeholder->expectation  = '期望管理';
$lang->stakeholder->createExpect = '添加期望';
$lang->stakeholder->deleteExpect = '删除期望';
$lang->stakeholder->editExpect   = '编辑期望';
$lang->stakeholder->viewExpect   = '期望信息';
$lang->stakeholder->issue        = '问题管理';
$lang->stakeholder->viewIssue    = '活动问题列表';
$lang->stakeholder->userIssue    = '干系人问题列表';

/* Fields. */
$lang->stakeholder->id        = '编号';
$lang->stakeholder->user      = '用户';
$lang->stakeholder->type      = '类型';
$lang->stakeholder->name      = '姓名';
$lang->stakeholder->phone     = '手机';
$lang->stakeholder->qq        = 'QQ';
$lang->stakeholder->weixin    = '微信';
$lang->stakeholder->email     = '邮箱';
$lang->stakeholder->isKey     = '关键干系人';
$lang->stakeholder->inside    = '内部干系人';
$lang->stakeholder->outside   = '外部干系人';
$lang->stakeholder->from      = '类型';
$lang->stakeholder->company   = '所属公司';
$lang->stakeholder->nature    = '性格特征';
$lang->stakeholder->analysis  = '影响分析';
$lang->stakeholder->strategy  = '应对策略';
$lang->stakeholder->expect    = '期望内容';
$lang->stakeholder->progress  = '达成进展';
$lang->stakeholder->createdBy = '创建者';
$lang->stakeholder->createdDate  = '创建日期';
$lang->stakeholder->noPlan       = '暂无活动，请先进行过程裁剪。';
$lang->stakeholder->emptyTip  = '暂无问题。';

/* Plan */
$lang->stakeholder->planField = new stdclass();
$lang->stakeholder->planField->common    = '干系人介入计划表';
$lang->stakeholder->planField->process   = '过程活动';
$lang->stakeholder->planField->begin     = '计划开展日期';
$lang->stakeholder->planField->realBegin = '实际开展日期';
$lang->stakeholder->planField->status    = '状况跟踪';
$lang->stakeholder->planField->inside    = '内部干系人';
$lang->stakeholder->planField->outside   = '外部干系人';
$lang->stakeholder->planField->comment   = '备注';
$lang->stakeholder->planField->situation = '实际参与情况';
$lang->stakeholder->planField->issue     = '问题列表';
$lang->stakeholder->planField->statusTips  = '状况跟踪：已获得承诺、已确认';
$lang->stakeholder->planField->partakeTips = '参与类型： P（Principal：主要负责），I(Involved：参与)，O(Optional：可选参与)';

$lang->stakeholder->planField->stautsList['no']  = 'N';
$lang->stakeholder->planField->stautsList['yes'] = 'Y';

$lang->stakeholder->planField->partakeList[''] = '';
$lang->stakeholder->planField->partakeList['principal'] = 'P';
$lang->stakeholder->planField->partakeList['involved']  = 'I';
$lang->stakeholder->planField->partakeList['optional']  = 'O';

$lang->stakeholder->keyList[0] = '否';
$lang->stakeholder->keyList[1] = '是';

$lang->stakeholder->typeList['inside']  = '内部';
$lang->stakeholder->typeList['outside'] = '外部';

$lang->stakeholder->fromList['team']    = '项目团队成员';
$lang->stakeholder->fromList['company'] = '公司同事';
$lang->stakeholder->fromList['outside'] = '外部人员';

$lang->stakeholder->situationList['all']  = '全部参与';
$lang->stakeholder->situationList['part'] = '部分参与';
$lang->stakeholder->situationList['wait'] = '未开始';

$lang->stakeholder->userEmpty     = '用户不能为空！';
$lang->stakeholder->nameEmpty     = '姓名不能为空！';
$lang->stakeholder->companyEmpty  = '所属公司不能为空！';
$lang->stakeholder->confirmDelete = "您确定删除该干系人吗？";
$lang->stakeholder->confirmDeleteExpect = "您确定删除该干系人期望吗？";
$lang->stakeholder->createCommunicate = '<i class="icon icon-chat-line"></i>添加沟通记录';

$lang->stakeholder->action = new stdclass();
$lang->stakeholder->action->communicate = array('main' => '$date, 由 <strong>$actor</strong> 沟通。');
