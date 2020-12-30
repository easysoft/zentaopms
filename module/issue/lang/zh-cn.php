<?php
/**
 * The issue module lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     issue
 * @version     $Id
 * @link        http://www.zentao.net
 */
$lang->issue->common            = '问题';
$lang->issue->browse            = '问题列表';
$lang->issue->resolvedBy        = '解决者';
$lang->issue->project           = '所属项目';
$lang->issue->title             = '标题';
$lang->issue->desc              = '描述';
$lang->issue->pri               = '优先级';
$lang->issue->severity          = '严重程度';
$lang->issue->type              = '类别';
$lang->issue->effectedArea      = '受影响的活动';
$lang->issue->activity          = '活动列表';
$lang->issue->deadline          = '计划解决日期';
$lang->issue->resolution        = '解决方式';
$lang->issue->resolutionComment = '解决方案';
$lang->issue->resolvedDate      = '实际解决日期';
$lang->issue->status            = '结果';
$lang->issue->createdBy         = '由谁创建';
$lang->issue->createdDate       = '创建日期';
$lang->issue->owner             = '提出人';
$lang->issue->editedBy          = '由谁编辑';
$lang->issue->editedDate        = '编辑日期';
$lang->issue->activateBy        = '由谁激活';
$lang->issue->activateDate      = '激活日期';
$lang->issue->closedBy          = '由谁关闭';
$lang->issue->closedDate        = '关闭日期';
$lang->issue->assignedTo        = '指派给';
$lang->issue->assignedBy        = '由谁指派';
$lang->issue->assignedDate      = '指派时间';
$lang->issue->resolve           = '解决';
$lang->issue->id                = '编号';
$lang->issue->confirm           = '确认';

$lang->issue->view              = '问题详情';
$lang->issue->close             = '关闭';
$lang->issue->cancel            = '取消';
$lang->issue->delete            = '删除';
$lang->issue->search            = '搜索';
$lang->issue->basicInfo         = '基本信息';
$lang->issue->activate          = '激活';
$lang->issue->assignTo          = '指派';
$lang->issue->create            = '新建问题';
$lang->issue->edit              = '编辑';
$lang->issue->batchCreate       = '批量新建';
$lang->issue->deleted           = '已删除';

$lang->issue->labelList['all']       = '全部';
$lang->issue->labelList['open']      = '开放';
$lang->issue->labelList['assignto']  = '指派给我';
$lang->issue->labelList['closed']    = '已关闭';
$lang->issue->labelList['suspended'] = '已挂起';
$lang->issue->labelList['canceled']  = '已取消';

$lang->issue->priList[''] = '';
$lang->issue->priList['1'] = 1;
$lang->issue->priList['2'] = 2;
$lang->issue->priList['3'] = 3;
$lang->issue->priList['4'] = 4;

$lang->issue->severityList[''] = '';
$lang->issue->severityList['1'] = '严重';
$lang->issue->severityList['2'] = '较严重';
$lang->issue->severityList['3'] = '较小';
$lang->issue->severityList['4'] = '建议';

$lang->issue->typeList[''] = '';
$lang->issue->typeList['design']       = '设计问题';
$lang->issue->typeList['code']         = '程序缺陷';
$lang->issue->typeList['performance']  = '性能问题';
$lang->issue->typeList['version']      = '版本控制';
$lang->issue->typeList['storyadd']     = '需求新增';
$lang->issue->typeList['storychanged'] = '需求修改';
$lang->issue->typeList['storyremoved'] = '需求删除';
$lang->issue->typeList['data']         = '数据问题';

$lang->issue->resolutionList['resolved'] = '已解决';
$lang->issue->resolutionList['tostory']  = '转需求';
$lang->issue->resolutionList['tobug']    = '转BUG';
$lang->issue->resolutionList['torisk']   = '转风险';
$lang->issue->resolutionList['totask']   = '转任务';

$lang->issue->statusList['unconfirmed'] = '待确认';
$lang->issue->statusList['confirmed']   = '已确认';
$lang->issue->statusList['resolved']    = '已解决';
$lang->issue->statusList['canceled']    = '取消';
$lang->issue->statusList['closed']      = '已关闭';
$lang->issue->statusList['active']      = '激活';

$lang->issue->resolveMethods = array();
$lang->issue->resolveMethods['resolved'] = '已解决';
$lang->issue->resolveMethods['totask']   = '转任务';
$lang->issue->resolveMethods['tobug']    = '转BUG';
$lang->issue->resolveMethods['tostory']  = '转需求';
$lang->issue->resolveMethods['torisk']   = '转风险';

$lang->issue->confirmDelete = '您确认删除该问题？';
$lang->issue->typeEmpty     = 'ID：%s的类别不能为空。';
$lang->issue->titleEmpty    = 'ID：%s的标题不能为空。';
$lang->issue->severityEmpty = 'ID：%s的严重程度不能为空。';

$lang->issue->logComments = array();
$lang->issue->logComments['totask']  = "创建了任务：%s。";
$lang->issue->logComments['tostory'] = "创建了需求：%s。";
$lang->issue->logComments['tobug']   = "创建了BUG：%s。";
$lang->issue->logComments['torisk']  = "创建了风险：%s。";
