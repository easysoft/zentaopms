<?php
/**
 * The search module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     search
 * @version     $Id: zh-cn.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
$lang->search->common        = '搜索';
$lang->search->id            = '编号';
$lang->search->editedDate    = '编辑时间';
$lang->search->key           = '键';
$lang->search->value         = '值';
$lang->search->reset         = '重置';
$lang->search->saveQuery     = '保存查询';
$lang->search->myQuery       = '我的查询';
$lang->search->group1        = '第一组';
$lang->search->group2        = '第二组';
$lang->search->buildForm     = '搜索表单';
$lang->search->buildQuery    = '执行搜索';
$lang->search->savedQuery    = '已保存的查询条件';
$lang->search->deleteQuery   = '删除查询';
$lang->search->setQueryTitle = '请输入查询标题（保存之前请先查询）：';
$lang->search->select        = "{$lang->SRCommon}/任务筛选";
$lang->search->me            = '自己';
$lang->search->noQuery       = '还没有保存查询！';
$lang->search->onMenuBar     = '显示在菜单栏';
$lang->search->custom        = '自定义';
$lang->search->setCommon     = '设为公共查询条件';
$lang->search->saveCondition = '保存搜索条件';
$lang->search->setCondName   = '请输入保存条件名称';

$lang->search->account  = '用户名';
$lang->search->module   = '模块';
$lang->search->title    = '名称';
$lang->search->form     = '表单字段';
$lang->search->sql      = 'SQL条件';
$lang->search->shortcut = $lang->search->onMenuBar;

$lang->search->operators['=']          = '=';
$lang->search->operators['!=']         = '!=';
$lang->search->operators['>']          = '>';
$lang->search->operators['>=']         = '>=';
$lang->search->operators['<']          = '<';
$lang->search->operators['<=']         = '<=';
$lang->search->operators['include']    = '包含';
$lang->search->operators['between']    = '介于';
$lang->search->operators['notinclude'] = '不包含';
$lang->search->operators['belong']     = '从属于';

$lang->search->andor['and']         = '并且';
$lang->search->andor['or']          = '或者';

$lang->search->null = '空';

$lang->userquery        = new stdclass();
$lang->userquery->title = '标题';

$lang->searchObjects['todo']      = '待办';
$lang->searchObjects['effort']    = '日志';
$lang->searchObjects['testsuite'] = '套件';

$lang->search->objectType = '对象类型';
$lang->search->objectID   = '对象编号';
$lang->search->content    = '内容';
$lang->search->addedDate  = '添加时间';

$lang->search->index      = '全文检索';
$lang->search->buildIndex = '重建索引';
$lang->search->preview    = '预览';

$lang->search->inputWords        = '请输入检索内容';
$lang->search->result            = '搜索结果';
$lang->search->resultCount       = '共计 <strong>%s</strong> 条';
$lang->search->buildSuccessfully = '初始化搜索索引成功';
$lang->search->executeInfo       = '为您找到相关结果%s个，耗时%s秒';
$lang->search->buildResult       = "创建 %s 索引, 已创建  <strong class='%scount'>%s</strong> 条记录；";
$lang->search->queryTips         = "多个id可用英文逗号分隔";
$lang->search->confirmDelete     = '是否确认删除该记录';

$lang->search->modules['all']         = '全部';
$lang->search->modules['task']        = '任务';
$lang->search->modules['bug']         = 'Bug';
$lang->search->modules['case']        = '用例';
$lang->search->modules['doc']         = '文档';
$lang->search->modules['todo']        = '待办';
$lang->search->modules['build']       = '版本';
$lang->search->modules['effort']      = '日志';
$lang->search->modules['caselib']     = '测试库';
$lang->search->modules['product']     = $lang->productCommon;
$lang->search->modules['release']     = '发布';
$lang->search->modules['testtask']    = '测试单';
$lang->search->modules['testsuite']   = '测试套件';
$lang->search->modules['testreport']  = '测试报告';
$lang->search->modules['productplan'] = '计划';
$lang->search->modules['program']     = '项目集';
$lang->search->modules['project']     = $lang->projectCommon;
$lang->search->modules['execution']   = $lang->execution->common;
$lang->search->modules['story']       = $lang->SRCommon;
$lang->search->modules['requirement'] = $lang->URCommon;

$lang->search->objectTypeList['story']            = $lang->SRCommon;
$lang->search->objectTypeList['requirement']      = $lang->URCommon;
$lang->search->objectTypeList['stage']            = '阶段';
$lang->search->objectTypeList['sprint']           = $lang->execution->common;
$lang->search->objectTypeList['kanban']           = '看板';
$lang->search->objectTypeList['commonIssue']      = '问题';
$lang->search->objectTypeList['stakeholderIssue'] = '干系人问题';
