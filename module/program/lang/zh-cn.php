<?php
/* Fields. */
$lang->program->name      = '项目集名称';
$lang->program->template  = '项目集模板';
$lang->program->category  = '项目集类型';
$lang->program->desc      = '项目集描述';
$lang->program->copy      = '复制项目集';
$lang->program->status    = '状态';
$lang->program->PM        = '负责人';
$lang->program->budget    = '预算';
$lang->program->progress  = '项目进度';
$lang->program->children  = '子项目集';
$lang->program->parent    = '父项目集';
$lang->program->allInput  = '项目集总投入';
$lang->program->teamCount = '总人数';
$lang->program->longTime  = '长期';
$lang->program->view      = '项目集详情';

/* Actions. */
$lang->program->common                  = '项目集';
$lang->program->index                   = '项目集主页';
$lang->program->create                  = '添加项目集';
$lang->program->createGuide             = '选择项目模板';
$lang->program->edit                    = '编辑项目集';
$lang->program->browse                  = '项目集列表';
$lang->program->product                 = '产品列表';
$lang->program->project                 = '项目集项目列表';
$lang->program->all                     = '所有项目集';
$lang->program->start                   = '启动项目集';
$lang->program->finish                  = '完成项目集';
$lang->program->suspend                 = '挂起项目集';
$lang->program->delete                  = '删除项目集';
$lang->program->close                   = '关闭项目集';
$lang->program->activate                = '激活项目集';
$lang->program->export                  = '导出';
$lang->program->stakeholder             = '干系人列表';
$lang->program->createStakeholder       = '添加干系人';
$lang->program->unlinkStakeholder       = '移除干系人';
$lang->program->batchUnlinkStakeholders = '批量移除干系人';
$lang->program->unlink                  = '移除';
$lang->program->moreProgram             = '更多项目集';
$lang->program->confirmBatchUnlink      = "您确定要批量移除这些干系人吗？";
$lang->program->stakeholderType         = '干系人类型';
$lang->program->isStakeholderKey        = '关键干系人';
$lang->program->importStakeholder       = '从父项目集导入';
$lang->program->unbindWhitelist         = '移除白名单';
$lang->program->importStakeholder       = '从父项目集导入';
$lang->program->manageMembers           = '项目集团队';
$lang->program->beyondParentBudget      = '已超出所属项目集的剩余预算';
$lang->program->parentBudget            = '所属项目集剩余预算：';
$lang->program->beginLetterParent       = "父项目集的开始日期：%s，开始日期不能小于父项目集的开始日期";
$lang->program->endGreaterParent        = "父项目集的完成日期：%s，完成日期不能大于父项目集的完成日期";
$lang->program->beginGreateChild        = "子项目集的最小开始日期：%s，父项目集的开始日期不能大于子项目集的最小开始日期";
$lang->program->endLetterChild          = "子项目的最大完成日期：%s，父项目的完成日期不能小于子项目的最大完成日期";
$lang->program->closeErrorMessage       = '存在子项目集或项目为未关闭状态';
$lang->program->hasChildren             = '该项目集有子项目集或项目存在，不能删除。';
$lang->program->confirmDelete           = "您确定要删除吗？";
$lang->program->readjustTime            = '重新调整项目集起止时间';

$lang->program->stakeholderTypeList['inside']  = '内部';
$lang->program->stakeholderTypeList['outside'] = '外部';

$lang->program->noProgram  = '暂时没有项目集';
$lang->program->showClosed = '显示已关闭';
$lang->program->tips       = '选择了父项目集，则可关联该父项目集下的产品。如果项目未选择任何项目集，则系统会默认创建一个和该项目同名的产品并关联该项目。';

$lang->program->endList[31]  = '一个月';
$lang->program->endList[93]  = '三个月';
$lang->program->endList[186] = '半年';
$lang->program->endList[365] = '一年';
$lang->program->endList[999] = '长期';

$lang->program->aclList['private'] = "私有（项目集负责人和干系人可访问，干系人可后续维护）";
$lang->program->aclList['open']    = "公开（有项目集视图权限，即可访问）";

$lang->program->subAclList['private'] = "私有（本项目集负责人和干系人可访问，干系人可后续维护）";
$lang->program->subAclList['open']    = "全部公开（有项目集视图权限，即可访问）";
$lang->program->subAclList['program'] = "项目集内公开（所有上级项目集负责人和干系人、本项目集负责人和干系人可访问）";

$lang->program->subAcls['private'] = '私有';
$lang->program->subAcls['open']    = '全部公开';
$lang->program->subAcls['program'] = '项目集内公开';

$lang->program->authList['extend'] = '继承 (取项目权限与组织权限的并集)';
$lang->program->authList['reset']  = '重新定义 (只取项目权限)';

$lang->program->statusList['wait']      = '未开始';
$lang->program->statusList['doing']     = '进行中';
$lang->program->statusList['suspended'] = '已挂起';
$lang->program->statusList['closed']    = '已关闭';

$lang->program->featureBar['all'] = '所有';
