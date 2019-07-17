<?php
/**
 * The translate module zh-cn file of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2012 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     translate
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->translate->common       = '翻译';
$lang->translate->index        = '首页';
$lang->translate->addLang      = '新增语言';
$lang->translate->module       = '翻译模块';
$lang->translate->review       = '审校';
$lang->translate->reviewAction = '审校翻译';
$lang->translate->result       = '保存审校结果';
$lang->translate->batchPass    = '批量通过';
$lang->translate->export       = '导出新语言';
$lang->translate->setting      = '设置';
$lang->translate->chooseModule = '选择翻译模块';

$lang->translate->name        = '语言名称';
$lang->translate->code        = '代号';
$lang->translate->key         = '键';
$lang->translate->reference   = '参考语言';
$lang->translate->status      = '状态';
$lang->translate->refreshPage = '刷新';
$lang->translate->reason      = '拒绝原因';

$lang->translate->reviewTurnon = '审校流程';
$lang->translate->reviewTurnonList['1'] = '开启';
$lang->translate->reviewTurnonList['0'] = '关闭';

$lang->translate->resultList['pass']   = '通过';
$lang->translate->resultList['reject'] = '拒绝';

$lang->translate->group              = '视图';
$lang->translate->allTotal           = '总条目';
$lang->translate->translatedTotal    = '已翻译条目数';
$lang->translate->changedTotal       = '已修改条目数';
$lang->translate->reviewedTotal      = '已审校条目数';
$lang->translate->translatedProgress = '翻译进度';
$lang->translate->reviewedProgress   = '审校进度';

$lang->translate->builtIn  = '内置语言';
$lang->translate->finished = '翻译完成';
$lang->translate->progress = '完成 %s';
$lang->translate->count    = '（%s 种）';

$lang->translate->finishedLang    = '已经完成的语言';
$lang->translate->translatingLang = '正在翻译的语言';
$lang->translate->allItems        = '所有的语言条目数：%s条';

$lang->translate->statusList['waiting']    = '未翻译';
$lang->translate->statusList['translated'] = '已翻译';
$lang->translate->statusList['reviewed']   = '已审校';
$lang->translate->statusList['rejected']   = '已拒绝';
$lang->translate->statusList['changed']    = '已改变';

$lang->translate->notice = new stdclass();
$lang->translate->notice->failDirPriv  = "目录没有写入权限，请修改权限。<br /><code>%s</code>";
$lang->translate->notice->failCopyFile = "复制文件%s到%s失败，请检查权限！";
$lang->translate->notice->failUnique   = "已经有代号 %s 的记录";
$lang->translate->notice->failMaxInput = "请修改php.ini的max_input_vars参数，修改为 %s，以保证表单提交。";
$lang->translate->notice->failRuleCode = "『语言代号』应当为字母、数字或下划线的组合。";
