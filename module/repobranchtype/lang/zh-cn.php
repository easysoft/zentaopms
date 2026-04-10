<?php
$lang->repobranchtype->common        = '分支类型';
$lang->repobranchtype->browse        = '查看分支类型';
$lang->repobranchtype->create        = '创建分支类型';
$lang->repobranchtype->edit          = '编辑分支类型';
$lang->repobranchtype->delete        = '删除分支类型';
$lang->repobranchtype->import        = '导入分支类型';
$lang->repobranchtype->setBranchRule = '设置评审流程';

$lang->repobranchtype->name     = '名称';
$lang->repobranchtype->key      = '键值';
$lang->repobranchtype->prefixes = '前缀';
$lang->repobranchtype->desc     = '描述';

$lang->repobranchtype->placeholder      = new stdclass();
$lang->repobranchtype->placeholder->key = '用于分支规则的唯一标识符，仅能以字母开头';

$lang->repobranchtype->tips = new stdclass();
$lang->repobranchtype->tips->maxPrefixes    = '最多只能添加5个前缀';
$lang->repobranchtype->tips->minPrefixes    = '至少需要保留1个前缀';
$lang->repobranchtype->tips->prefixRequired = '请至少填写一个前缀';
$lang->repobranchtype->tips->createSuccess  = '分支类型创建成功';
$lang->repobranchtype->tips->updateSuccess  = '分支类型更新成功';
$lang->repobranchtype->tips->importSuccess  = '分支类型导入成功';

$lang->repobranchtype->error = new stdclass();
$lang->repobranchtype->error->keyFormat       = '键值格式不正确，仅能输入英文字母、数字与符号（/-_.），且以字母开头';
$lang->repobranchtype->error->prefixFormat    = '前缀格式不正确，仅能输入英文字母、数字与符号（/-_.），斜杠最多只能有一个';
$lang->repobranchtype->error->prefixSlash     = '前缀中斜杠最多只能有一个';
$lang->repobranchtype->error->prefixDuplicate = '前缀不能重复';
$lang->repobranchtype->error->notExists       = '分支类型不存在';

$lang->repobranchtype->notice = new stdclass();
$lang->repobranchtype->notice->delete                     = '确认删除该分支类型？';
$lang->repobranchtype->notice->noPermissionToCreateBranch = '没有创建分支的权限';
$lang->repobranchtype->notice->noPermissionToDeleteBranch = '没有删除分支的权限';
