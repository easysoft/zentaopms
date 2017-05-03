<?php
/**
 * The testtask module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: zh-cn.php 4490 2013-02-27 03:27:05Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->testtask->index            = "版本首页";
$lang->testtask->create           = "提交测试";
$lang->testtask->reportChart      = '报表统计';
$lang->testtask->delete           = "删除版本";
$lang->testtask->view             = "概况";
$lang->testtask->edit             = "编辑版本";
$lang->testtask->browse           = "版本列表";
$lang->testtask->linkCase         = "关联用例";
$lang->testtask->selectVersion    = "选择版本";
$lang->testtask->unlinkCase       = "移除";
$lang->testtask->batchUnlinkCases = "批量移除用例";
$lang->testtask->batchAssign      = "批量指派";
$lang->testtask->runCase          = "执行";
$lang->testtask->batchRun         = "批量执行";
$lang->testtask->results          = "结果";
$lang->testtask->createBug        = "提Bug";
$lang->testtask->assign           = '指派';
$lang->testtask->cases            = '用例';
$lang->testtask->groupCase        = "分组浏览用例";
$lang->testtask->pre              = '上一个';
$lang->testtask->next             = '下一个';
$lang->testtask->start            = "开始";
$lang->testtask->close            = "关闭";
$lang->testtask->wait             = "待测版本";
$lang->testtask->block            = "阻塞";
$lang->testtask->activate         = "激活";
$lang->testtask->testing          = "测试中版本";
$lang->testtask->blocked          = "被阻塞版本";
$lang->testtask->done             = "已测版本";
$lang->testtask->totalStatus      = "全部";
$lang->testtask->all              = "全部" . $lang->productCommon;

$lang->testtask->id             = '编号';
$lang->testtask->common         = '测试版本';
$lang->testtask->product        = '所属' . $lang->productCommon;
$lang->testtask->project        = '所属' . $lang->projectCommon;
$lang->testtask->build          = '版本';
$lang->testtask->owner          = '负责人';
$lang->testtask->pri            = '优先级';
$lang->testtask->name           = '名称';
$lang->testtask->begin          = '开始日期';
$lang->testtask->end            = '结束日期';
$lang->testtask->desc           = '描述';
$lang->testtask->mailto         = '抄送给';
$lang->testtask->status         = '当前状态';
$lang->testtask->assignedTo     = '指派给';
$lang->testtask->linkVersion    = '版本';
$lang->testtask->lastRunAccount = '执行人';
$lang->testtask->lastRunTime    = '执行时间';
$lang->testtask->lastRunResult  = '结果';
$lang->testtask->reportField    = '测试总结';
$lang->testtask->files          = '上传附件';

$lang->testtask->legendDesc      = '版本描述';
$lang->testtask->legendReport    = '测试总结';
$lang->testtask->legendBasicInfo = '基本信息';

$lang->testtask->statusList['wait']    = '未开始';
$lang->testtask->statusList['doing']   = '进行中';
$lang->testtask->statusList['done']    = '已完成';
$lang->testtask->statusList['blocked'] = '被阻塞';

$lang->testtask->priList[0] = '';
$lang->testtask->priList[3] = '3';
$lang->testtask->priList[1] = '1';
$lang->testtask->priList[2] = '2';
$lang->testtask->priList[4] = '4';

$lang->testtask->unlinkedCases = '未关联';
$lang->testtask->linkByBuild   = '复制版本';
$lang->testtask->linkByStory   = '按需求关联';
$lang->testtask->linkByBug     = '按Bug关联';
$lang->testtask->linkBySuite   = '按套件关联';
$lang->testtask->passAll       = '全部通过';
$lang->testtask->pass          = '通过';
$lang->testtask->fail          = '失败';
$lang->testtask->showResult    = '共执行<span class="text-info">%s</span>次';
$lang->testtask->showFail      = '失败<span class="text-danger">%s</span>次';

$lang->testtask->confirmDelete     = '您确认要删除该版本吗？';
$lang->testtask->confirmUnlinkCase = '您确认要移除该用例吗？';
$lang->testtask->noticeNoOther     = '该产品还没有其他测试版本';

$lang->testtask->assignedToMe  = '指派给我';
$lang->testtask->allCases      = '所有用例';

$lang->testtask->lblCases      = '用例列表';
$lang->testtask->lblUnlinkCase = '移除用例';
$lang->testtask->lblRunCase    = '执行用例';
$lang->testtask->lblResults    = '执行结果';

$lang->testtask->placeholder = new stdclass();
$lang->testtask->placeholder->begin = '开始日期';
$lang->testtask->placeholder->end   = '结束日期';

$lang->testtask->mail = new stdclass();
$lang->testtask->mail->create = new stdclass();
$lang->testtask->mail->edit   = new stdclass();
$lang->testtask->mail->close  = new stdclass();
$lang->testtask->mail->create->title = "%s创建了版本 #%s:%s";
$lang->testtask->mail->edit->title   = "%s编辑了版本 #%s:%s";
$lang->testtask->mail->close->title  = "%s关闭了版本 #%s:%s";

$lang->testtask->action = new stdclass();
$lang->testtask->action->testtaskopened  = '$date, 由 <strong>$actor</strong> 创建版本 <strong>$extra</strong>。' . "\n";
$lang->testtask->action->testtaskstarted = '$date, 由 <strong>$actor</strong> 启动版本 <strong>$extra</strong>。' . "\n";
$lang->testtask->action->testtaskclosed  = '$date, 由 <strong>$actor</strong> 完成版本 <strong>$extra</strong>。' . "\n";

$lang->testtask->unexecuted = '未执行';

/* 统计报表。*/
$lang->testtask->report = new stdclass();
$lang->testtask->report->common = '报表';
$lang->testtask->report->select = '请选择报表类型';
$lang->testtask->report->create = '生成报表';
    
$lang->testtask->report->charts['testTaskPerRunResult'] = '用例结果统计';
$lang->testtask->report->charts['testTaskPerType']      = '用例类型统计';
$lang->testtask->report->charts['testTaskPerModule']    = '用例模块统计';
$lang->testtask->report->charts['testTaskPerRunner']    = '用例执行人统计';
$lang->testtask->report->charts['bugSeverityGroups']    = 'Bug严重级别分布';
$lang->testtask->report->charts['bugStatusGroups']      = 'Bug状态分布';
$lang->testtask->report->charts['bugOpenedByGroups']    = 'Bug创建者分布';
$lang->testtask->report->charts['bugResolvedByGroups']  = 'Bug解决者分布';
$lang->testtask->report->charts['bugResolutionGroups']  = 'Bug解决方案分布';
$lang->testtask->report->charts['bugModuleGroups']      = 'Bug模块分布';

$lang->testtask->report->options = new stdclass();
$lang->testtask->report->options->graph  = new stdclass();
$lang->testtask->report->options->type   = 'pie';
$lang->testtask->report->options->width  = 500;
$lang->testtask->report->options->height = 140;
