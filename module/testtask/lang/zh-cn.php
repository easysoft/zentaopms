<?php
/**
 * The testtask module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->testtask->index          = "测试任务首页";
$lang->testtask->create         = "提交测试";
$lang->testtask->delete         = "删除测试任务";
$lang->testtask->view           = "详情";
$lang->testtask->edit           = "编辑测试任务";
$lang->testtask->browse         = "待测列表";
$lang->testtask->linkCase       = "关联用例";
$lang->testtask->linkCaseAB     = "关联";
$lang->testtask->unlinkCase     = "移除";
$lang->testtask->batchAssign    = "批量指派";
$lang->testtask->runCase        = "执行";
$lang->testtask->batchRun       = "批量执行";
$lang->testtask->results        = "结果";
$lang->testtask->createBug      = "提Bug";
$lang->testtask->assign         = '指派';
$lang->testtask->cases          = '用例';

$lang->testtask->common         = '测试任务';
$lang->testtask->id             = '任务编号';
$lang->testtask->product        = '所属产品';
$lang->testtask->project        = '所属项目';
$lang->testtask->build          = '版本';
$lang->testtask->owner          = '负责人';
$lang->testtask->pri            = '优先级';
$lang->testtask->name           = '任务名称';
$lang->testtask->begin          = '开始日期';
$lang->testtask->end            = '结束日期';
$lang->testtask->desc           = '任务描述';
$lang->testtask->status         = '当前状态';
$lang->testtask->assignedTo     = '指派给';
$lang->testtask->linkVersion    = '版本';
$lang->testtask->lastRunAccount = '执行人';
$lang->testtask->lastRunTime    = '执行时间';
$lang->testtask->lastRunResult  = '结果';

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
$lang->testtask->linkedCases   = '已关联';
$lang->testtask->linkByStory   = '按需求关联';
$lang->testtask->linkByBug     = '按Bug关联';
$lang->testtask->confirmDelete = '您确认要删除该测试任务吗？';
$lang->testtask->passAll       = '全部通过';
$lang->testtask->pass          = '通过';
$lang->testtask->fail          = '失败';

$lang->testtask->byModule      = '按模块';
$lang->testtask->assignedToMe  = '指派给我';
$lang->testtask->allCases      = '所有Case';

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
$lang->testtask->mail->create->title = "%s创建了测试任务 #%s:%s";
$lang->testtask->mail->edit->title   = "%s编辑了测试任务 #%s:%s";
