<?php
/**
 * The testtask module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: zh-cn.php 4490 2013-02-27 03:27:05Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->testtask->index            = "测试单首页";
$lang->testtask->create           = "提交测试";
$lang->testtask->reportChart      = '报表统计';
$lang->testtask->delete           = "删除测试单";
$lang->testtask->importUnitResult = "导入单元测试结果";
$lang->testtask->importUnit       = "导入单元测试"; //Fix bug custom required testtask.
$lang->testtask->browseUnits      = "单元测试列表";
$lang->testtask->unitCases        = "浏览单元测试用例列表";
$lang->testtask->view             = "概况";
$lang->testtask->edit             = "编辑测试单";
$lang->testtask->browse           = "测试单列表";
$lang->testtask->linkCase         = "关联用例";
$lang->testtask->selectVersion    = "选择测试单";
$lang->testtask->unlinkCase       = "移除";
$lang->testtask->batchUnlinkCases = "批量移除用例";
$lang->testtask->batchAssign      = "批量指派";
$lang->testtask->runCase          = "执行";
$lang->testtask->running          = "执行中";
$lang->testtask->runningLog       = "执行日志";
$lang->testtask->runNode          = "由%s，在执行节点%s上 %s";
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
$lang->testtask->wait             = "待测测试单";
$lang->testtask->waitAB           = "待测试";
$lang->testtask->block            = "阻塞";
$lang->testtask->activate         = "激活";
$lang->testtask->testing          = "测试中测试单";
$lang->testtask->testingAB        = "测试中";
$lang->testtask->blocked          = "被阻塞测试单";
$lang->testtask->done             = "已测测试单";
$lang->testtask->totalStatus      = "全部";
$lang->testtask->all              = '所有';
$lang->testtask->allTasks         = '所有测试';
$lang->testtask->collapseAll      = '全部折叠';
$lang->testtask->expandAll        = '全部展开';
$lang->testtask->auto             = '自动化';
$lang->testtask->task             = '测试单';
$lang->testtask->run              = '执行编号';
$lang->testtask->job              = '构建任务';
$lang->testtask->compile          = '构建';
$lang->testtask->duration         = '持续时间';
$lang->testtask->myInvolved       = '由我参与';

$lang->testtask->viewAction     = "测试单概况";
$lang->testtask->casesAction    = '浏览用例列表';
$lang->testtask->activateAction = "激活测试单";
$lang->testtask->blockAction    = "阻塞测试单";
$lang->testtask->closeAction    = "关闭测试单";
$lang->testtask->startAction    = "开始测试单";
$lang->testtask->resultsAction  = "用例结果";
$lang->testtask->reportAction   = '用例报表统计';

$lang->testtask->id                = '编号';
$lang->testtask->common            = '测试单';
$lang->testtask->product           = '所属' . $lang->productCommon;
$lang->testtask->project           = '所属' . $lang->projectCommon;
$lang->testtask->execution         = '所属' . $lang->execution->common;
$lang->testtask->type              = '测试类型';
$lang->testtask->build             = '版本';
$lang->testtask->owner             = '负责人';
$lang->testtask->members           = '参与人';
$lang->testtask->executor          = '执行人';
$lang->testtask->execTime          = '执行时间';
$lang->testtask->pri               = '优先级';
$lang->testtask->name              = '名称';
$lang->testtask->begin             = '开始日期';
$lang->testtask->end               = '结束日期';
$lang->testtask->realFinishedDate  = '实际完成日期';
$lang->testtask->desc              = '描述';
$lang->testtask->mailto            = '抄送给';
$lang->testtask->status            = '当前状态';
$lang->testtask->subStatus         = '子状态';
$lang->testtask->testreport        = '相关测试报告';
$lang->testtask->assignedTo        = '指派给';
$lang->testtask->linkVersion       = '版本';
$lang->testtask->lastRunAccount    = '执行人';
$lang->testtask->lastRunTime       = '执行时间';
$lang->testtask->lastRunResult     = '结果';
$lang->testtask->reportField       = '测试总结';
$lang->testtask->files             = '上传附件';
$lang->testtask->case              = '用例';
$lang->testtask->version           = '版本';
$lang->testtask->caseResult        = '测试结果';
$lang->testtask->stepResults       = '步骤结果';
$lang->testtask->lastRunner        = '最后执行人';
$lang->testtask->lastRunDate       = '最后执行时间';
$lang->testtask->date              = '测试时间';
$lang->testtask->deleted           = "已删除";
$lang->testtask->resultFile        = "结果文件";
$lang->testtask->caseCount         = '用例数';
$lang->testtask->passCount         = '成功';
$lang->testtask->failCount         = '失败';
$lang->testtask->summary           = '有%s个用例，失败%s个，耗时%s。';
$lang->testtask->stepSummary       = '共有%s个步骤，%s个通过，%s个失败。';
$lang->testtask->pageSummary       = '本页共 <strong>%s</strong> 个测试单。';
$lang->testtask->mySummary         = '本页共 <strong>%s</strong> 个测试单，待测试测试单 <strong>%s</strong>，测试中测试单 <strong>%s</strong>，被阻塞测试单 <strong>%s</strong>。';
$lang->testtask->allSummary        = '本页共 <strong>%s</strong> 个测试单，待测试测试单 <strong>%s</strong>，测试中测试单 <strong>%s</strong>，被阻塞测试单 <strong>%s</strong>，已测测试单 <strong>%s</strong>。';
$lang->testtask->checkedAllSummary = '共选中 <strong>%total%</strong> 个测试单，待测试测试单 <strong>%wait%</strong>，测试中测试单 <strong>%testing%</strong>，被阻塞测试单 <strong>%blocked%</strong>，已测测试单 <strong>%done%</strong>。';

$lang->testtask->beginAndEnd = '起止时间';
$lang->testtask->to          = '至';

$lang->testtask->legendDesc      = '测试单描述';
$lang->testtask->legendReport    = '测试总结';
$lang->testtask->legendBasicInfo = '基本信息';

$lang->testtask->statusList['wait']    = '未开始';
$lang->testtask->statusList['doing']   = '进行中';
$lang->testtask->statusList['done']    = '已关闭';
$lang->testtask->statusList['blocked'] = '被阻塞';

$lang->testtask->priList[1] = '1';
$lang->testtask->priList[2] = '2';
$lang->testtask->priList[3] = '3';
$lang->testtask->priList[4] = '4';

$lang->testtask->unlinkedCases = '未关联';
$lang->testtask->linkByBuild   = '复制测试单';
$lang->testtask->linkByStory   = "按{$lang->SRCommon}关联";
$lang->testtask->linkByBug     = '按Bug关联';
$lang->testtask->linkBySuite   = '按套件关联';
$lang->testtask->browseBySuite = '按套件查看';
$lang->testtask->passAll       = '全部通过';
$lang->testtask->pass          = '通过';
$lang->testtask->fail          = '失败';
$lang->testtask->showResult    = '共执行<span class="text-info">%s</span>次';
$lang->testtask->showFail      = '失败<span class="text-danger">%s</span>次';

$lang->testtask->confirmDelete     = '您确认要删除该测试单吗？';
$lang->testtask->confirmUnlinkCase = '您确认要移除该用例吗？';
$lang->testtask->noticeNoOther     = "该{$lang->productCommon}还没有其他测试单";
$lang->testtask->noTesttask        = '暂时没有测试单。';
$lang->testtask->checkLinked       = "请检查测试单的{$lang->productCommon}是否与{$lang->executionCommon}相关联";
$lang->testtask->noImportData      = '导入的XML没有解析出数据。';
$lang->testtask->unitXMLFormat     = '请选择Junit XML 格式的文件。';
$lang->testtask->titleOfAuto       = "%s 自动化测试";
$lang->testtask->cannotBeParsed    = '导入的XML文件内容格式错误，无法解析。';
$lang->testtask->finishedDateLess  = '实际完成日期不能小于开始日期%s';
$lang->testtask->finishedDateMore  = '实际完成日期不能大于今天';
$lang->testtask->accessDenied      = '您无权访问该测试单';

$lang->testtask->assignedToMe  = '指派给我';
$lang->testtask->allCases      = '全部用例';

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
$lang->testtask->mail->create->title = "%s创建了测试单 #%s:%s";
$lang->testtask->mail->edit->title   = "%s编辑了测试单 #%s:%s";
$lang->testtask->mail->close->title  = "%s关闭了测试单 #%s:%s";

$lang->testtask->action = new stdclass();
$lang->testtask->action->testtaskopened  = '$date, 由 <strong>$actor</strong> 创建测试单 <strong>$extra</strong>。' . "\n";
$lang->testtask->action->testtaskstarted = '$date, 由 <strong>$actor</strong> 启动测试单 <strong>$extra</strong>。' . "\n";
$lang->testtask->action->testtaskclosed  = '$date, 由 <strong>$actor</strong> 完成测试单 <strong>$extra</strong>。' . "\n";

$lang->testtask->unexecuted = '未执行';

/* 统计报表。*/
$lang->testtask->report = new stdclass();
$lang->testtask->report->common = '报表';
$lang->testtask->report->select = '请选择报表类型';
$lang->testtask->report->create = '生成报表';

$lang->testtask->report->testTaskPerRunResultTip = '共有%s个用例，通过%s个、未执行%s个、失败%s个';

$lang->testtask->report->charts['testTaskPerRunResult'] = '按用例结果统计';
$lang->testtask->report->charts['testTaskPerType']      = '按用例类型统计';
$lang->testtask->report->charts['testTaskPerModule']    = '按用例模块统计';
$lang->testtask->report->charts['testTaskPerRunner']    = '按用例执行人统计';

$lang->testtask->report->options = new stdclass();
$lang->testtask->report->options->graph  = new stdclass();
$lang->testtask->report->options->type   = 'pie';
$lang->testtask->report->options->width  = 500;
$lang->testtask->report->options->height = 140;

$lang->testtask->featureBar['browse']['totalStatus'] = $lang->testtask->totalStatus;
$lang->testtask->featureBar['browse']['myinvolved']  = $lang->testtask->myInvolved;
$lang->testtask->featureBar['browse']['wait']        = $lang->testtask->wait;
$lang->testtask->featureBar['browse']['doing']       = $lang->testtask->testing;
$lang->testtask->featureBar['browse']['blocked']     = $lang->testtask->blocked;
$lang->testtask->featureBar['browse']['done']        = $lang->testtask->done;

$lang->testtask->unitTag['all']       = '所有';
$lang->testtask->unitTag['newest']    = '最近';
$lang->testtask->unitTag['thisWeek']  = '本周';
$lang->testtask->unitTag['lastWeek']  = '上周';
$lang->testtask->unitTag['thisMonth'] = '本月';
$lang->testtask->unitTag['lastMonth'] = '上月';

$lang->testtask->typeList['integrate']   = '集成测试';
$lang->testtask->typeList['system']      = '系统测试';
$lang->testtask->typeList['acceptance']  = '验收测试';
$lang->testtask->typeList['performance'] = '性能测试';
$lang->testtask->typeList['safety']      = '安全测试';
