<?php
$lang->branch->common = '分支';
$lang->branch->manage = '分支管理';
$lang->branch->sort   = '分支排序';
$lang->branch->delete = '分支删除';
$lang->branch->add    = '添加';

$lang->branch->manageTitle = '%s管理';
$lang->branch->all         = '所有';
$lang->branch->main        = '主干';

$lang->branch->edit              = '编辑%s';
$lang->branch->editAction        = '编辑分支';
$lang->branch->activate          = '激活';
$lang->branch->activateAction    = '激活分支';
$lang->branch->close             = '关闭';
$lang->branch->closeAction       = '关闭分支';
$lang->branch->create            = '新建%s';
$lang->branch->createAction      = '新建分支';
$lang->branch->merge             = '合并';
$lang->branch->batchEdit         = '批量编辑';
$lang->branch->defaultBranch     = '默认分支';
$lang->branch->setDefault        = '设为默认分支';
$lang->branch->setDefaultAction  = '设置默认分支';
$lang->branch->mergeTo           = '合并@branch@到';
$lang->branch->mergeBranch       = '合并@branch@';
$lang->branch->mergeBranchAction = '合并分支';

$lang->branch->id          = 'ID';
$lang->branch->product     = "所属{$lang->productCommon}";
$lang->branch->name        = '%s名称';
$lang->branch->status      = '状态';
$lang->branch->createdDate = '创建时间';
$lang->branch->closedDate  = '关闭时间';
$lang->branch->desc        = '%s描述';
$lang->branch->order       = '排序';
$lang->branch->deleted     = '已删除';
$lang->branch->closed      = '已关闭';
$lang->branch->default     = '默认';

$lang->branch->confirmDelete     = '是否删除该@branch@？';
$lang->branch->confirmSetDefault = '请确认是否需要将该@branch@设置为默认@branch@，设置成功后计划和发布列表将默认选中默认@branch@。';
$lang->branch->canNotDelete      = '该@branch@下已经有数据，不能删除！';
$lang->branch->nameNotEmpty      = '名称不能为空！';
$lang->branch->confirmClose      = '是否关闭该@branch@？';
$lang->branch->confirmActivate   = '是否激活该@branch@？';
$lang->branch->existName         = '@branch@名称已存在';
$lang->branch->mergedMain        = '主干不支持被合并。';
$lang->branch->mergeTips         = '合并@branch@后，会将@branch@下面对应的发布、计划、版本、模块、需求、Bug、用例都合并到新的@branch@下。';
$lang->branch->targetBranchTips  = '您可以将其合并到已有的一个@branch@，也可以合并到主干，也可以新创建一个@branch@。';
$lang->branch->confirmMerge      = '"mergedBranch"的数据将被合并到"targetBranch",请确认是否要执行分支合并操作，合并后数据将不可再恢复！';

$lang->branch->noData     = '暂时没有分支。';
$lang->branch->mainBranch = "{$lang->productCommon}默认主干%s。";

$lang->branch->statusList = array();
$lang->branch->statusList['active'] = '激活';
$lang->branch->statusList['closed'] = '已关闭';

$lang->branch->featureBar['manage']['all']    = '全部';
$lang->branch->featureBar['manage']['active'] = '激活';
$lang->branch->featureBar['manage']['closed'] = '已关闭';
