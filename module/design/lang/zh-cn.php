<?php
/**
 * The zh-cn file of design module.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     design
 * @version     $Id: zh-cn.php 4729 2020-09-01 07:53:55Z tianshujie@easycorp.ltd $
 * @link        https://www.zentao.net
 */
/* 字段列表. */
$lang->design->id            = '编号';
$lang->design->name          = '设计名称';
$lang->design->story         = '需求';
$lang->design->type          = '设计类型';
$lang->design->ditto         = '同上';
$lang->design->submission    = '相关提交';
$lang->design->version       = '版本号';
$lang->design->assignedTo    = '指派给';
$lang->design->actions       = '操作';
$lang->design->byQuery       = '搜索';
$lang->design->products      = "所属{$lang->productCommon}";
$lang->design->story         = '相关需求';
$lang->design->file          = '附件';
$lang->design->desc          = '设计描述';
$lang->design->range         = '影响范围';
$lang->design->product       = "所属{$lang->productCommon}";
$lang->design->basicInfo     = '基础信息';
$lang->design->commitBy      = '由谁提交';
$lang->design->commitDate    = '提交时间';
$lang->design->affectedStory = "影响{$lang->SRCommon}";
$lang->design->affectedTasks = '影响任务';
$lang->design->reviewObject  = '评审对象';
$lang->design->createdBy     = '由谁创建';
$lang->design->createdByAB   = '创建者';
$lang->design->createdDate   = '创建日期';
$lang->design->basicInfo     = '基本信息';
$lang->design->noAssigned    = '未指派';
$lang->design->comment       = '注释';
$lang->design->more          = '更多';

/* 动作列表. */
$lang->design->common       = '设计';
$lang->design->create       = '创建设计';
$lang->design->batchCreate  = '批量创建';
$lang->design->edit         = '变更';
$lang->design->delete       = '删除';
$lang->design->view         = '设计详情';
$lang->design->browse       = '设计列表';
$lang->design->viewCommit   = '查看提交';
$lang->design->linkCommit   = '关联提交';
$lang->design->unlinkCommit = '取消关联';
$lang->design->submit       = '提交评审';
$lang->design->assignTo     = '指派';
$lang->design->assignAction = '指派设计';
$lang->design->revision     = '查看关联代码';

$lang->design->browseAction = '设计列表';

/* 字段取值. */
$lang->design->typeList         = array();
$lang->design->typeList['']     = '';
$lang->design->typeList['HLDS'] = '概要设计';
$lang->design->typeList['DDS']  = '详细设计';
$lang->design->typeList['DBDS'] = '数据库设计';
$lang->design->typeList['ADS']  = '接口设计';

$lang->design->plusTypeList = $lang->design->typeList;

$lang->design->rangeList           = array();
$lang->design->rangeList['all']    = '全部记录';
$lang->design->rangeList['assign'] = '选中记录';

/* 提示信息. */
$lang->design->errorSelection = '还没有选中记录!';
$lang->design->noDesign       = '暂时没有记录';
$lang->design->noCommit       = '暂时没有提交记录';
$lang->design->confirmDelete  = '您确定要删除这个设计吗？';
$lang->design->confirmUnlink  = '您确定要移除这个提交吗？';
$lang->design->errorDate      = '开始日期不能大于结束日期';
$lang->design->deleted        = '已删除';
