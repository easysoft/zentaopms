<?php
/**
 * The bug module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: zh-cn.php 4536 2013-03-02 13:39:37Z wwccss $
 * @link        https://www.zentao.net
 */
/* 字段列表。*/
$lang->bug->common           = 'Bug';
$lang->bug->id               = 'Bug编号';
$lang->bug->product          = '所属' . $lang->productCommon;
$lang->bug->branch           = '平台/分支';
$lang->bug->module           = '所属模块';
$lang->bug->project          = '所属' . $lang->projectCommon;
$lang->bug->execution        = '所属' . $lang->execution->common;
$lang->bug->kanban           = '所属看板';
$lang->bug->storyVersion     = "{$lang->SRCommon}版本";
$lang->bug->color            = '标题颜色';
$lang->bug->title            = 'Bug名称';
$lang->bug->severity         = '严重程度';
$lang->bug->pri              = '优先级';
$lang->bug->type             = '类型';
$lang->bug->os               = '操作系统';
$lang->bug->browser          = '浏览器';
$lang->bug->hardware         = '硬件';
$lang->bug->result           = '结果';
$lang->bug->repo             = '所属版本库';
$lang->bug->mr               = '合并请求';
$lang->bug->entry            = '代码路径';
$lang->bug->lines            = '代码行';
$lang->bug->v1               = '版本1';
$lang->bug->v2               = '版本2';
$lang->bug->issueKey         = 'Sonarqube问题键值';
$lang->bug->repoType         = '版本库类型';
$lang->bug->steps            = '重现步骤';
$lang->bug->status           = 'Bug状态';
$lang->bug->subStatus        = '子状态';
$lang->bug->activatedCount   = '激活次数';
$lang->bug->activatedDate    = '激活时间';
$lang->bug->confirmed        = '是否确认';
$lang->bug->toTask           = '转任务';
$lang->bug->toStory          = "转{$lang->SRCommon}";
$lang->bug->feedbackBy       = '反馈者';
$lang->bug->notifyEmail      = '通知邮箱';
$lang->bug->mailto           = '抄送给';
$lang->bug->openedBy         = '由谁创建';
$lang->bug->openedDate       = '创建日期';
$lang->bug->openedBuild      = '影响版本';
$lang->bug->assignedTo       = '指派给';
$lang->bug->assignedToMe     = '指派给我';
$lang->bug->assignedDate     = '指派日期';
$lang->bug->resolvedBy       = '解决者';
$lang->bug->resolution       = '解决方案';
$lang->bug->resolvedBuild    = '解决版本';
$lang->bug->resolvedDate     = '解决日期';
$lang->bug->deadline         = '截止日期';
$lang->bug->plan             = '所属计划';
$lang->bug->closedBy         = '由谁关闭';
$lang->bug->closedDate       = '关闭日期';
$lang->bug->duplicateBug     = '重复Bug';
$lang->bug->lastEditedBy     = '最后修改者';
$lang->bug->caseVersion      = '用例版本';
$lang->bug->testtask         = '测试单';
$lang->bug->files            = '附件';
$lang->bug->keywords         = '关键词';
$lang->bug->lastEditedDate   = '修改日期';
$lang->bug->fromCase         = '来源用例';
$lang->bug->toCase           = '生成用例';
$lang->bug->colorTag         = '颜色标签';
$lang->bug->fixedRate        = '修复率';
$lang->bug->noticefeedbackBy = '通知反馈者';
$lang->bug->selectProjects   = '选择' . $lang->projectCommon;
$lang->bug->nextStep         = '下一步';
$lang->bug->noProject        = "还没有选择{$lang->projectCommon}！";
$lang->bug->noExecution      = "还没有选择{$lang->execution->common}！";
$lang->bug->story            = "相关需求";
$lang->bug->task             = '相关任务';
$lang->bug->relatedBug       = '相关Bug';
$lang->bug->case             = '相关用例';
$lang->bug->linkMR           = '相关合并请求';
$lang->bug->linkCommit       = '相关代码版本';
$lang->bug->productplan      = $lang->bug->plan;

$lang->bug->abbr = new stdclass();
$lang->bug->abbr->module         = '模块';
$lang->bug->abbr->severity       = '级别';
$lang->bug->abbr->status         = '状态';
$lang->bug->abbr->activatedCount = '激活次数';
$lang->bug->abbr->confirmed      = '确认';
$lang->bug->abbr->openedBy       = '创建者';
$lang->bug->abbr->openedDate     = '创建日期';
$lang->bug->abbr->assignedTo     = '指派给';
$lang->bug->abbr->resolvedBy     = '解决';
$lang->bug->abbr->resolution     = '方案';
$lang->bug->abbr->resolvedDate   = '解决日期';
$lang->bug->abbr->deadline       = '截止';
$lang->bug->abbr->lastEditedBy   = '修改者';
$lang->bug->abbr->lastEditedDate = '修改日期';
$lang->bug->abbr->assignToMe     = '指派给我';
$lang->bug->abbr->openedByMe     = '由我创建';
$lang->bug->abbr->resolvedByMe   = '由我解决';

/* 方法列表。*/
$lang->bug->index              = '首页';
$lang->bug->browse             = 'Bug列表';
$lang->bug->create             = '提Bug';
$lang->bug->batchCreate        = '批量提Bug';
$lang->bug->createCase         = '创建用例';
$lang->bug->copy               = '复制Bug';
$lang->bug->edit               = '编辑Bug';
$lang->bug->batchEdit          = '批量编辑';
$lang->bug->view               = 'Bug详情';
$lang->bug->delete             = '删除';
$lang->bug->deleteAction       = '删除Bug';
$lang->bug->confirm            = '确认';
$lang->bug->confirmAction      = '确认Bug';
$lang->bug->batchConfirm       = '批量确认';
$lang->bug->assignTo           = '指派';
$lang->bug->assignAction       = '指派Bug';
$lang->bug->batchAssignTo      = '批量指派';
$lang->bug->resolve            = '解决';
$lang->bug->resolveAction      = '解决Bug';
$lang->bug->batchResolve       = '批量解决';
$lang->bug->createAB           = '新增';
$lang->bug->close              = '关闭';
$lang->bug->closeAction        = '关闭Bug';
$lang->bug->batchClose         = '批量关闭';
$lang->bug->activate           = '激活';
$lang->bug->activateAction     = '激活Bug';
$lang->bug->batchActivate      = '批量激活';
$lang->bug->reportChart        = '报表统计';
$lang->bug->reportAction       = 'Bug报表统计';
$lang->bug->export             = '导出数据';
$lang->bug->exportAction       = '导出Bug';
$lang->bug->confirmStoryChange = "确认{$lang->SRCommon}变动";
$lang->bug->search             = '搜索';
$lang->bug->batchChangeModule  = '批量修改模块';
$lang->bug->batchChangeBranch  = '批量修改分支';
$lang->bug->batchChangePlan    = '批量修改计划';
$lang->bug->linkBugs           = '关联相关Bug';
$lang->bug->unlinkBug          = '移除相关Bug';

/* 查询条件列表。*/
$lang->bug->assignToMe         = '指派给我';
$lang->bug->openedByMe         = '由我创建';
$lang->bug->resolvedByMe       = '由我解决';
$lang->bug->closedByMe         = '由我关闭';
$lang->bug->assignedByMe       = '由我指派';
$lang->bug->assignToNull       = '未指派';
$lang->bug->unResolved         = '未解决';
$lang->bug->toClosed           = '待关闭';
$lang->bug->unclosed           = '未关闭';
$lang->bug->unconfirmed        = '未确认';
$lang->bug->longLifeBugs       = '久未处理';
$lang->bug->postponedBugs      = '被延期';
$lang->bug->overdueBugs        = '过期Bug';
$lang->bug->allBugs            = '所有';
$lang->bug->byQuery            = '搜索';
$lang->bug->needConfirm        = "{$lang->SRCommon}变动";
$lang->bug->allProject         = '所有' . $lang->projectCommon;
$lang->bug->allProduct         = '所有' . $lang->productCommon;
$lang->bug->my                 = '我的';
$lang->bug->yesterdayResolved  = '昨天解决Bug数';
$lang->bug->yesterdayConfirmed = '昨天确认';
$lang->bug->yesterdayClosed    = '昨天关闭';

$lang->bug->deleted        = '已删除';
$lang->bug->labelConfirmed = '已确认';
$lang->bug->labelPostponed = '被延期';
$lang->bug->changed        = '已变动';
$lang->bug->storyChanged   = '需求变动';
$lang->bug->ditto          = '同上';

/* 页面标签。*/
$lang->bug->lblAssignedTo = '当前指派';
$lang->bug->lblMailto     = '抄送给';
$lang->bug->lblLastEdited = '最后修改';
$lang->bug->lblResolved   = '由谁解决';
$lang->bug->loadAll       = '加载所有';
$lang->bug->createBuild   = '创建';

global $config;
/* legend列表。*/
$lang->bug->legendBasicInfo             = '基本信息';
$lang->bug->legendAttach                = '附件';
$lang->bug->legendPRJExecStoryTask      = "{$lang->projectCommon}/{$lang->executionCommon}/{$lang->SRCommon}/任务";
$lang->bug->legendExecStoryTask         = "{$lang->projectCommon}/{$lang->SRCommon}/任务";
$lang->bug->lblTypeAndSeverity          = '类型/严重程度';
$lang->bug->lblSystemBrowserAndHardware = '系统/浏览器';
$lang->bug->legendSteps                 = '重现步骤';
$lang->bug->legendComment               = '备注';
$lang->bug->legendLife                  = 'Bug的一生';
$lang->bug->legendMisc                  = '其他相关';
$lang->bug->legendRelated               = '其他信息';
$lang->bug->legendThisWeekCreated       = '本周新增';

/* 模板。*/
$lang->bug->tplStep   = "<p>[步骤]</p><p></p>";
$lang->bug->tplResult = "<p>[结果]</p><p></p>";
$lang->bug->tplExpect = "<p>[期望]</p><p></p>";

/* 各个字段取值列表。*/
$lang->bug->severityList[0] = '';
$lang->bug->severityList[1] = '1';
$lang->bug->severityList[2] = '2';
$lang->bug->severityList[3] = '3';
$lang->bug->severityList[4] = '4';

$lang->bug->priList[0] = '';
$lang->bug->priList[1] = '1';
$lang->bug->priList[2] = '2';
$lang->bug->priList[3] = '3';
$lang->bug->priList[4] = '4';

$lang->bug->osList['']         = '';
$lang->bug->osList['all']      = '全部';
$lang->bug->osList['windows']  = 'Windows';
$lang->bug->osList['win11']    = 'Windows 11';
$lang->bug->osList['win10']    = 'Windows 10';
$lang->bug->osList['win8']     = 'Windows 8';
$lang->bug->osList['win7']     = 'Windows 7';
$lang->bug->osList['winxp']    = 'Windows XP';
$lang->bug->osList['osx']      = 'Mac OS';
$lang->bug->osList['android']  = 'Android';
$lang->bug->osList['ios']      = 'IOS';
$lang->bug->osList['linux']    = 'Linux';
$lang->bug->osList['ubuntu']   = 'Ubuntu';
$lang->bug->osList['chromeos'] = 'Chrome OS';
$lang->bug->osList['fedora']   = 'Fedora';
$lang->bug->osList['unix']     = 'Unix';
$lang->bug->osList['others']   = '其他';

$lang->bug->browserList['']        = '';
$lang->bug->browserList['all']     = '全部';
$lang->bug->browserList['chrome']  = 'Chrome';
$lang->bug->browserList['edge']    = 'Edge';
$lang->bug->browserList['ie']      = 'IE系列';
$lang->bug->browserList['ie11']    = 'IE11';
$lang->bug->browserList['ie10']    = 'IE10';
$lang->bug->browserList['ie9']     = 'IE9';
$lang->bug->browserList['ie8']     = 'IE8';
$lang->bug->browserList['firefox'] = 'firefox系列';
$lang->bug->browserList['opera']   = 'Opera系列';
$lang->bug->browserList['safari']  = 'safari';
$lang->bug->browserList['360']     = '360浏览器';
$lang->bug->browserList['qq']      = 'QQ浏览器';
$lang->bug->browserList['other']   = '其他';

$lang->bug->typeList['']             = '';
$lang->bug->typeList['codeerror']    = '代码错误';
$lang->bug->typeList['config']       = '配置相关';
$lang->bug->typeList['install']      = '安装部署';
$lang->bug->typeList['security']     = '安全相关';
$lang->bug->typeList['performance']  = '性能问题';
$lang->bug->typeList['standard']     = '标准规范';
$lang->bug->typeList['automation']   = '测试脚本';
$lang->bug->typeList['designdefect'] = '设计缺陷';
$lang->bug->typeList['others']       = '其他';

$lang->bug->statusList['']         = '';
$lang->bug->statusList['active']   = '激活';
$lang->bug->statusList['resolved'] = '已解决';
$lang->bug->statusList['closed']   = '已关闭';

$lang->bug->confirmedList[''] = '';
$lang->bug->confirmedList[1]  = '已确认';
$lang->bug->confirmedList[0]  = '未确认';

$lang->bug->resolutionList['']           = '';
$lang->bug->resolutionList['bydesign']   = '设计如此';
$lang->bug->resolutionList['duplicate']  = '重复Bug';
$lang->bug->resolutionList['external']   = '外部原因';
$lang->bug->resolutionList['fixed']      = '已解决';
$lang->bug->resolutionList['notrepro']   = '无法重现';
$lang->bug->resolutionList['postponed']  = '延期处理';
$lang->bug->resolutionList['willnotfix'] = "不予解决";
$lang->bug->resolutionList['tostory']    = "转为{$lang->SRCommon}";

/* 统计报表。*/
$lang->bug->report = new stdclass();
$lang->bug->report->common = '报表';
$lang->bug->report->select = '请选择报表类型';
$lang->bug->report->create = '生成报表';

$lang->bug->report->charts['bugsPerExecution']      = $lang->executionCommon . 'Bug数量';
$lang->bug->report->charts['bugsPerBuild']          = '版本Bug数量';
$lang->bug->report->charts['bugsPerModule']         = '模块Bug数量';
$lang->bug->report->charts['openedBugsPerDay']      = '每天新增Bug数';
$lang->bug->report->charts['resolvedBugsPerDay']    = '每天解决Bug数';
$lang->bug->report->charts['closedBugsPerDay']      = '每天关闭的Bug数';
$lang->bug->report->charts['openedBugsPerUser']     = '每人提交的Bug数';
$lang->bug->report->charts['resolvedBugsPerUser']   = '每人解决的Bug数';
$lang->bug->report->charts['closedBugsPerUser']     = '每人关闭的Bug数';
$lang->bug->report->charts['bugsPerSeverity']       = '按Bug严重程度统计';
$lang->bug->report->charts['bugsPerResolution']     = '按Bug解决方案统计';
$lang->bug->report->charts['bugsPerStatus']         = '按Bug状态统计';
$lang->bug->report->charts['bugsPerActivatedCount'] = '按Bug激活次数统计';
$lang->bug->report->charts['bugsPerPri']            = '按Bug优先级统计';
$lang->bug->report->charts['bugsPerType']           = '按Bug类型统计';
$lang->bug->report->charts['bugsPerAssignedTo']     = '按指派给统计';
//$lang->bug->report->charts['bugLiveDays']        = 'Bug处理时间统计';
//$lang->bug->report->charts['bugHistories']       = 'Bug处理步骤统计';

$lang->bug->report->options = new stdclass();
$lang->bug->report->options->graph  = new stdclass();
$lang->bug->report->options->type   = 'pie';
$lang->bug->report->options->width  = 500;
$lang->bug->report->options->height = 140;

$lang->bug->report->bugsPerExecution      = new stdclass();
$lang->bug->report->bugsPerBuild          = new stdclass();
$lang->bug->report->bugsPerModule         = new stdclass();
$lang->bug->report->openedBugsPerDay      = new stdclass();
$lang->bug->report->resolvedBugsPerDay    = new stdclass();
$lang->bug->report->closedBugsPerDay      = new stdclass();
$lang->bug->report->openedBugsPerUser     = new stdclass();
$lang->bug->report->resolvedBugsPerUser   = new stdclass();
$lang->bug->report->closedBugsPerUser     = new stdclass();
$lang->bug->report->bugsPerSeverity       = new stdclass();
$lang->bug->report->bugsPerResolution     = new stdclass();
$lang->bug->report->bugsPerStatus         = new stdclass();
$lang->bug->report->bugsPerActivatedCount = new stdclass();
$lang->bug->report->bugsPerType           = new stdclass();
$lang->bug->report->bugsPerPri            = new stdclass();
$lang->bug->report->bugsPerAssignedTo     = new stdclass();
$lang->bug->report->bugLiveDays           = new stdclass();
$lang->bug->report->bugHistories          = new stdclass();

$lang->bug->report->bugsPerExecution->graph      = new stdclass();
$lang->bug->report->bugsPerBuild->graph          = new stdclass();
$lang->bug->report->bugsPerModule->graph         = new stdclass();
$lang->bug->report->openedBugsPerDay->graph      = new stdclass();
$lang->bug->report->resolvedBugsPerDay->graph    = new stdclass();
$lang->bug->report->closedBugsPerDay->graph      = new stdclass();
$lang->bug->report->openedBugsPerUser->graph     = new stdclass();
$lang->bug->report->resolvedBugsPerUser->graph   = new stdclass();
$lang->bug->report->closedBugsPerUser->graph     = new stdclass();
$lang->bug->report->bugsPerSeverity->graph       = new stdclass();
$lang->bug->report->bugsPerResolution->graph     = new stdclass();
$lang->bug->report->bugsPerStatus->graph         = new stdclass();
$lang->bug->report->bugsPerActivatedCount->graph = new stdclass();
$lang->bug->report->bugsPerType->graph           = new stdclass();
$lang->bug->report->bugsPerPri->graph            = new stdclass();
$lang->bug->report->bugsPerAssignedTo->graph     = new stdclass();
$lang->bug->report->bugLiveDays->graph           = new stdclass();
$lang->bug->report->bugHistories->graph          = new stdclass();

$lang->bug->report->bugsPerExecution->graph->xAxisName = $lang->executionCommon;
$lang->bug->report->bugsPerBuild->graph->xAxisName     = '版本';
$lang->bug->report->bugsPerModule->graph->xAxisName    = '模块';

$lang->bug->report->openedBugsPerDay->type             = 'bar';
$lang->bug->report->openedBugsPerDay->graph->xAxisName = '日期';

$lang->bug->report->resolvedBugsPerDay->type             = 'bar';
$lang->bug->report->resolvedBugsPerDay->graph->xAxisName = '日期';

$lang->bug->report->closedBugsPerDay->type             = 'bar';
$lang->bug->report->closedBugsPerDay->graph->xAxisName = '日期';

$lang->bug->report->openedBugsPerUser->graph->xAxisName   = '用户';
$lang->bug->report->resolvedBugsPerUser->graph->xAxisName = '用户';
$lang->bug->report->closedBugsPerUser->graph->xAxisName   = '用户';

$lang->bug->report->bugsPerSeverity->graph->xAxisName       = '严重程度';
$lang->bug->report->bugsPerResolution->graph->xAxisName     = '解决方案';
$lang->bug->report->bugsPerStatus->graph->xAxisName         = '状态';
$lang->bug->report->bugsPerActivatedCount->graph->xAxisName = '激活次数';
$lang->bug->report->bugsPerPri->graph->xAxisName            = '优先级';
$lang->bug->report->bugsPerType->graph->xAxisName           = '类型';
$lang->bug->report->bugsPerAssignedTo->graph->xAxisName     = '指派给';
$lang->bug->report->bugLiveDays->graph->xAxisName           = '处理时间';
$lang->bug->report->bugHistories->graph->xAxisName          = '处理步骤';

/* 操作记录。*/
$lang->bug->action = new stdclass();
$lang->bug->action->resolved             = array('main' => '$date, 由 <strong>$actor</strong> 解决，方案为 <strong>$extra</strong> $appendLink。', 'extra' => 'resolutionList');
$lang->bug->action->tostory              = array('main' => '$date, 由 <strong>$actor</strong> 转为<strong> ' . $lang->SRCommon . '</strong>，编号为 <strong>$extra</strong>。');
$lang->bug->action->totask               = array('main' => '$date, 由 <strong>$actor</strong> 导入为<strong>任务</strong>，编号为 <strong>$extra</strong>。');
$lang->bug->action->converttotask        = array('main' => '$date, 由 <strong>$actor</strong> 转为<strong>任务</strong>，编号为 <strong>$extra</strong>。');
$lang->bug->action->linked2plan          = array('main' => '$date, 由 <strong>$actor</strong> 关联到计划 <strong>$extra</strong>。');
$lang->bug->action->unlinkedfromplan     = array('main' => '$date, 由 <strong>$actor</strong> 从计划 <strong>$extra</strong> 移除。');
$lang->bug->action->linked2build         = array('main' => '$date, 由 <strong>$actor</strong> 关联到版本 <strong>$extra</strong>。');
$lang->bug->action->unlinkedfrombuild    = array('main' => '$date, 由 <strong>$actor</strong> 从版本 <strong>$extra</strong> 移除。');
$lang->bug->action->unlinkedfromrelease  = array('main' => '$date, 由 <strong>$actor</strong> 从发布 <strong>$extra</strong> 移除。');
$lang->bug->action->linked2release       = array('main' => '$date, 由 <strong>$actor</strong> 关联到发布 <strong>$extra</strong>。');
$lang->bug->action->linked2revision      = array('main' => '$date, 由 <strong>$actor</strong> 关联到代码提交 <strong>$extra</strong>.');
$lang->bug->action->unlinkedfromrevision = array('main' => '$date, 由 <strong>$actor</strong> 取消关联到代码提交 <strong>$extra</strong>。');
$lang->bug->action->linkrelatedbug       = array('main' => '$date, 由 <strong>$actor</strong> 关联相关Bug <strong>$extra</strong>。');
$lang->bug->action->unlinkrelatedbug     = array('main' => '$date, 由 <strong>$actor</strong> 移除相关Bug <strong>$extra</strong>。');

$lang->bug->featureBar['browse']['all']          = '全部';
$lang->bug->featureBar['browse']['unclosed']     = $lang->bug->unclosed;
$lang->bug->featureBar['browse']['openedbyme']   = $lang->bug->openedByMe;
$lang->bug->featureBar['browse']['assigntome']   = $lang->bug->assignToMe;
$lang->bug->featureBar['browse']['resolvedbyme'] = $lang->bug->resolvedByMe;
$lang->bug->featureBar['browse']['assignedbyme'] = $lang->bug->assignedByMe;
$lang->bug->featureBar['browse']['more']         = $lang->more;

$lang->bug->moreSelects['browse']['more']['unresolved']    = $lang->bug->unResolved;
$lang->bug->moreSelects['browse']['more']['unconfirmed']   = $lang->bug->unconfirmed;
$lang->bug->moreSelects['browse']['more']['assigntonull']  = $lang->bug->assignToNull;
$lang->bug->moreSelects['browse']['more']['longlifebugs']  = $lang->bug->longLifeBugs;
$lang->bug->moreSelects['browse']['more']['toclosed']      = $lang->bug->toClosed;
$lang->bug->moreSelects['browse']['more']['postponedbugs'] = $lang->bug->postponedBugs;
$lang->bug->moreSelects['browse']['more']['overduebugs']   = $lang->bug->overdueBugs;
$lang->bug->moreSelects['browse']['more']['needconfirm']   = $lang->bug->needConfirm;

$lang->bug->placeholder = new stdclass();
$lang->bug->placeholder->chooseBuilds = '选择相关版本...';
$lang->bug->placeholder->newBuildName = '新版本名称';
$lang->bug->placeholder->duplicate    = '请输入关键字';

/* 交互提示。*/
$lang->bug->notice = new stdclass();
$lang->bug->notice->summary               = "本页共 <strong>%s</strong> 个Bug，未解决 <strong>%s</strong>。";
$lang->bug->notice->confirmChangeProduct  = "修改{$lang->productCommon}会导致相应的{$lang->executionCommon}、{$lang->SRCommon}和任务发生变化，确定吗？";
$lang->bug->notice->confirmDelete         = '您确认要删除该Bug吗？';
$lang->bug->notice->remindTask            = '该Bug已经转化为任务，是否更新任务(编号:%s)状态 ?';
$lang->bug->notice->skipClose             = 'Bug %s 不是已解决状态，不能关闭，将自动忽略。';
$lang->bug->notice->executionAccessDenied = "您无权访问该Bug所属的{$lang->executionCommon}！";
$lang->bug->notice->confirmUnlinkBuild    = "更换解决版本将取消与旧版本的关联，您确定取消该bug与%s的关联吗？";
$lang->bug->notice->noSwitchBranch        = 'Bug%s所属模块不在当前分支下，将自动忽略。';
$lang->bug->notice->confirmToStory        = '转需求后Bug将自动关闭，关闭原因为转为需求。';
$lang->bug->notice->productDitto          = "该 bug 与上一 bug 不属于同一{$lang->productCommon}！";
$lang->bug->notice->noBug                 = '暂时没有 Bug。';
$lang->bug->notice->noModule              = '<div>您现在还没有模块信息</div><div>请维护测试模块</div>';
$lang->bug->notice->delayWarning          = " <strong class='text-danger'> 延期%s天 </strong>";

$lang->bug->error = new stdclass();
$lang->bug->error->notExist       = 'Bug不存在。';
$lang->bug->error->cannotActivate = '状态不是已解决或已关闭的Bug不能激活。';
$lang->bug->error->stepsNotEmpty  = "重现步骤不能为空。";
