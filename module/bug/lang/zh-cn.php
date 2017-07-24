<?php
/**
 * The bug module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: zh-cn.php 4536 2013-03-02 13:39:37Z wwccss $
 * @link        http://www.zentao.net
 */
/* 字段列表。*/
$lang->bug->common           = 'Bug';
$lang->bug->id               = 'Bug编号';
$lang->bug->product          = '所属' . $lang->productCommon;
$lang->bug->branch           = '分支/平台';
$lang->bug->productplan      = '所属计划';
$lang->bug->module           = '所属模块';
$lang->bug->moduleAB         = '模块';
$lang->bug->project          = '所属' . $lang->projectCommon;
$lang->bug->story            = '相关需求';
$lang->bug->task             = '相关任务';
$lang->bug->title            = 'Bug标题';
$lang->bug->severity         = '严重程度';
$lang->bug->severityAB       = '级别';
$lang->bug->pri              = '优先级';
$lang->bug->type             = 'Bug类型';
$lang->bug->os               = '操作系统';
$lang->bug->browser          = '浏览器';
$lang->bug->steps            = '重现步骤';
$lang->bug->status           = 'Bug状态';
$lang->bug->statusAB         = '状态';
$lang->bug->activatedCount   = '激活次数';
$lang->bug->activatedCountAB = '激活次数';
$lang->bug->confirmed        = '是否确认';
$lang->bug->toTask           = '转任务';
$lang->bug->toStory          = '转需求';
$lang->bug->mailto           = '抄送给';
$lang->bug->openedBy         = '由谁创建';
$lang->bug->openedDate       = '创建日期';
$lang->bug->openedDateAB     = '创建日期';
$lang->bug->openedBuild      = '影响版本';
$lang->bug->assignedTo       = '指派给';
$lang->bug->assignedDate     = '指派日期';
$lang->bug->resolvedBy       = '解决者';
$lang->bug->resolvedByAB     = '解决';
$lang->bug->resolution       = '解决方案';
$lang->bug->resolutionAB     = '方案';
$lang->bug->resolvedBuild    = '解决版本';
$lang->bug->resolvedDate     = '解决日期';
$lang->bug->resolvedDateAB   = '解决日期';
$lang->bug->deadline         = '截止日期';
$lang->bug->closedBy         = '由谁关闭';
$lang->bug->closedDate       = '关闭日期';
$lang->bug->duplicateBug     = '重复ID';
$lang->bug->lastEditedBy     = '最后修改者';
$lang->bug->linkBug          = '相关Bug';
$lang->bug->linkBugs         = '关联相关Bug';
$lang->bug->unlinkBug        = '移除相关Bug';
$lang->bug->case             = '相关用例';
$lang->bug->files            = '附件';
$lang->bug->keywords         = '关键词';
$lang->bug->lastEditedByAB   = '修改者';
$lang->bug->lastEditedDateAB = '修改日期';
$lang->bug->lastEditedDate   = '修改日期';
$lang->bug->fromCase         = '来源用例';
$lang->bug->toCase           = '生成用例';
$lang->bug->colorTag         = '颜色标签';

/* 方法列表。*/
$lang->bug->index              = '首页';
$lang->bug->create             = '提Bug';
$lang->bug->batchCreate        = '批量添加';
$lang->bug->confirmBug         = '确认';
$lang->bug->batchConfirm       = '批量确认';
$lang->bug->edit               = '编辑';
$lang->bug->batchEdit          = '批量编辑';
$lang->bug->batchChangeModule  = '批量修改模块';
$lang->bug->batchClose         = '批量关闭';
$lang->bug->assignTo           = '指派';
$lang->bug->batchAssignTo      = '批量指派';
$lang->bug->browse             = 'Bug列表';
$lang->bug->view               = 'Bug详情';
$lang->bug->resolve            = '解决';
$lang->bug->batchResolve       = '批量解决';
$lang->bug->close              = '关闭';
$lang->bug->activate           = '激活';
$lang->bug->reportChart        = '报表统计';
$lang->bug->export             = '导出数据';
$lang->bug->delete             = '删除';
$lang->bug->deleted            = '已删除';
$lang->bug->saveTemplate       = '保存模板';
$lang->bug->setPublic          = '设为公共模板';
$lang->bug->deleteTemplate     = '删除模板';
$lang->bug->confirmStoryChange = '确认需求变动';
$lang->bug->copy               = '复制Bug';

/* 查询条件列表。*/
$lang->bug->assignToMe     = '指派给我';
$lang->bug->openedByMe     = '由我创建';
$lang->bug->resolvedByMe   = '由我解决';
$lang->bug->closedByMe     = '由我关闭';
$lang->bug->assignToNull   = '未指派';
$lang->bug->unResolved     = '未解决';
$lang->bug->toClosed       = '待关闭';
$lang->bug->unclosed       = '未关闭';
$lang->bug->longLifeBugs   = '久未处理';
$lang->bug->postponedBugs  = '被延期';
$lang->bug->overdueBugs    = '过期Bug';
$lang->bug->allBugs        = '所有';
$lang->bug->byQuery        = '搜索';
$lang->bug->needConfirm    = '需求变动';
$lang->bug->allProduct     = '所有' . $lang->productCommon;

$lang->bug->ditto       = '同上';
$lang->bug->dittoNotice = '该bug与上一bug不属于同一产品！';

/* 页面标签。*/
$lang->bug->lblAssignedTo               = '当前指派';
$lang->bug->lblMailto                   = '抄送给';
$lang->bug->lblLastEdited               = '最后修改';
$lang->bug->lblResolved                 = '由谁解决';
$lang->bug->allUsers                    = '所有用户';
$lang->bug->allBuilds                   = '所有';
$lang->bug->createBuild                 = '新建';

/* legend列表。*/
$lang->bug->legendBasicInfo             = '基本信息';
$lang->bug->legendAttatch               = '附件';
$lang->bug->legendPrjStoryTask          = $lang->projectCommon . '/需求/任务';
$lang->bug->lblTypeAndSeverity          = '类型/严重程度';
$lang->bug->lblSystemBrowserAndHardware = '系统/浏览器';
$lang->bug->legendSteps                 = '重现步骤';
$lang->bug->legendComment               = '备注';
$lang->bug->legendLife                  = 'BUG的一生';
$lang->bug->legendMisc                  = '其他相关';
$lang->bug->legendRelated               = '其他信息';

/* 功能按钮。*/
$lang->bug->buttonConfirm        = '确认';

/* 交互提示。*/
$lang->bug->confirmChangeProduct  = "修改{$lang->productCommon}会导致相应的{$lang->projectCommon}、需求和任务发生变化，确定吗？";
$lang->bug->confirmDelete         = '您确认要删除该Bug吗？';
$lang->bug->setTemplateTitle      = '请输入bug模板标题';
$lang->bug->remindTask            = '该Bug已经转化为任务，是否更新任务(编号:%s)状态 ?';
$lang->bug->skipClose             = 'Bug %s 不是已解决状态，不能关闭。';
$lang->bug->applyTemplate         = '应用模板';
$lang->bug->confirmDeleteTemplate = '您确认要删除该模板吗？';

/* 模板。*/
$lang->bug->tplStep   = "<p>[步骤]</p>";
$lang->bug->tplResult = "</br><p>[结果]</p>";
$lang->bug->tplExpect = "</br><p>[期望]</p>";

/* 各个字段取值列表。*/
$lang->bug->severityList[3] = '3';
$lang->bug->severityList[1] = '1';
$lang->bug->severityList[2] = '2';
$lang->bug->severityList[4] = '4';

$lang->bug->priList[0] = '';
$lang->bug->priList[3] = '3';
$lang->bug->priList[1] = '1';
$lang->bug->priList[2] = '2';
$lang->bug->priList[4] = '4';

$lang->bug->osList['']        = '';
$lang->bug->osList['all']     = '全部';
$lang->bug->osList['windows'] = 'Windows';
$lang->bug->osList['win8']    = 'Windows 8';
$lang->bug->osList['win7']    = 'Windows 7';
$lang->bug->osList['vista']   = 'Windows Vista';
$lang->bug->osList['winxp']   = 'Windows XP';
$lang->bug->osList['win2012'] = 'Windows 2012';
$lang->bug->osList['win2008'] = 'Windows 2008';
$lang->bug->osList['win2003'] = 'Windows 2003';
$lang->bug->osList['win2000'] = 'Windows 2000';
$lang->bug->osList['android'] = 'Android';
$lang->bug->osList['ios']     = 'IOS';
$lang->bug->osList['wp8']     = 'WP8';
$lang->bug->osList['wp7']     = 'WP7';
$lang->bug->osList['symbian'] = 'Symbian';
$lang->bug->osList['linux']   = 'Linux';
$lang->bug->osList['freebsd'] = 'FreeBSD';
$lang->bug->osList['osx']     = 'OS X';
$lang->bug->osList['unix']    = 'Unix';
$lang->bug->osList['others']  = '其他';

$lang->bug->browserList['']         = '';
$lang->bug->browserList['all']      = '全部';
$lang->bug->browserList['ie']       = 'IE系列';
$lang->bug->browserList['ie11']     = 'IE11';
$lang->bug->browserList['ie10']     = 'IE10';
$lang->bug->browserList['ie9']      = 'IE9';
$lang->bug->browserList['ie8']      = 'IE8';
$lang->bug->browserList['ie7']      = 'IE7';
$lang->bug->browserList['ie6']      = 'IE6';
$lang->bug->browserList['chrome']   = 'chrome';
$lang->bug->browserList['firefox']  = 'firefox系列';
$lang->bug->browserList['firefox4'] = 'firefox4';
$lang->bug->browserList['firefox3'] = 'firefox3';
$lang->bug->browserList['firefox2'] = 'firefox2';
$lang->bug->browserList['opera']    = 'opera系列';
$lang->bug->browserList['oprea11']  = 'opera11';
$lang->bug->browserList['oprea10']  = 'opera10';
$lang->bug->browserList['opera9']   = 'opera9';
$lang->bug->browserList['safari']   = 'safari';
$lang->bug->browserList['maxthon']  = '傲游';
$lang->bug->browserList['uc']       = 'UC';
$lang->bug->browserList['other']    = '其他';

$lang->bug->typeList['']             = '';
$lang->bug->typeList['codeerror']    = '代码错误';
$lang->bug->typeList['interface']    = '界面优化';
$lang->bug->typeList['designchange'] = '设计变更';
$lang->bug->typeList['newfeature']   = '新增需求';
$lang->bug->typeList['designdefect'] = '设计缺陷';
$lang->bug->typeList['config']       = '配置相关';
$lang->bug->typeList['install']      = '安装部署';
$lang->bug->typeList['security']     = '安全相关';
$lang->bug->typeList['performance']  = '性能问题';
$lang->bug->typeList['standard']     = '标准规范';
$lang->bug->typeList['automation']   = '测试脚本';
$lang->bug->typeList['trackthings']  = '事务跟踪';
$lang->bug->typeList['others']       = '其他';

$lang->bug->statusList['']         = '';
$lang->bug->statusList['active']   = '激活';
$lang->bug->statusList['resolved'] = '已解决';
$lang->bug->statusList['closed']   = '已关闭';

$lang->bug->confirmedList[1] = '已确认';
$lang->bug->confirmedList[0] = '未确认';

$lang->bug->resolutionList['']           = '';
$lang->bug->resolutionList['bydesign']   = '设计如此';
$lang->bug->resolutionList['duplicate']  = '重复Bug';
$lang->bug->resolutionList['external']   = '外部原因';
$lang->bug->resolutionList['fixed']      = '已解决';
$lang->bug->resolutionList['notrepro']   = '无法重现';
$lang->bug->resolutionList['postponed']  = '延期处理';
$lang->bug->resolutionList['willnotfix'] = "不予解决";
$lang->bug->resolutionList['tostory']    = '转为需求';

/* 统计报表。*/
$lang->bug->report = new stdclass();
$lang->bug->report->common = '报表';
$lang->bug->report->select = '请选择报表类型';
$lang->bug->report->create = '生成报表';

$lang->bug->report->charts['bugsPerProject']        = $lang->projectCommon . 'Bug数量';
$lang->bug->report->charts['bugsPerBuild']          = '版本Bug数量';
$lang->bug->report->charts['bugsPerModule']         = '模块Bug数量';
$lang->bug->report->charts['openedBugsPerDay']      = '每天新增Bug数';
$lang->bug->report->charts['resolvedBugsPerDay']    = '每天解决Bug数';
$lang->bug->report->charts['closedBugsPerDay']      = '每天关闭的Bug数';
$lang->bug->report->charts['openedBugsPerUser']     = '每人提交的Bug数';
$lang->bug->report->charts['resolvedBugsPerUser']   = '每人解决的Bug数';
$lang->bug->report->charts['closedBugsPerUser']     = '每人关闭的Bug数';
$lang->bug->report->charts['bugsPerSeverity']       = 'Bug严重程度统计';
$lang->bug->report->charts['bugsPerResolution']     = 'Bug解决方案统计';
$lang->bug->report->charts['bugsPerStatus']         = 'Bug状态统计';
$lang->bug->report->charts['bugsPerActivatedCount'] = 'Bug激活次数统计';
$lang->bug->report->charts['bugsPerPri']            = 'Bug优先级统计';
$lang->bug->report->charts['bugsPerType']           = 'Bug类型统计';
$lang->bug->report->charts['bugsPerAssignedTo']     = '指派给统计';
//$lang->bug->report->charts['bugLiveDays']        = 'Bug处理时间统计';
//$lang->bug->report->charts['bugHistories']       = 'Bug处理步骤统计';

$lang->bug->report->options = new stdclass();
$lang->bug->report->options->graph  = new stdclass();
$lang->bug->report->options->type   = 'pie';
$lang->bug->report->options->width  = 500;
$lang->bug->report->options->height = 140;

$lang->bug->report->bugsPerProject        = new stdclass();
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

$lang->bug->report->bugsPerProject->graph        = new stdclass();
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
$lang->bug->report->bugsPerPri->graph           = new stdclass();
$lang->bug->report->bugsPerAssignedTo->graph     = new stdclass();
$lang->bug->report->bugLiveDays->graph           = new stdclass();
$lang->bug->report->bugHistories->graph          = new stdclass();

$lang->bug->report->bugsPerProject->graph->xAxisName     = $lang->projectCommon;
$lang->bug->report->bugsPerBuild->graph->xAxisName       = '版本';
$lang->bug->report->bugsPerModule->graph->xAxisName      = '模块';

$lang->bug->report->openedBugsPerDay->type                = 'bar';
$lang->bug->report->openedBugsPerDay->graph->xAxisName   = '日期';

$lang->bug->report->resolvedBugsPerDay->type              = 'bar';
$lang->bug->report->resolvedBugsPerDay->graph->xAxisName = '日期';

$lang->bug->report->closedBugsPerDay->type                = 'bar';
$lang->bug->report->closedBugsPerDay->graph->xAxisName   = '日期';

$lang->bug->report->openedBugsPerUser->graph->xAxisName  = '用户';
$lang->bug->report->resolvedBugsPerUser->graph->xAxisName= '用户';
$lang->bug->report->closedBugsPerUser->graph->xAxisName  = '用户';

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
$lang->bug->action->resolved          = array('main' => '$date, 由 <strong>$actor</strong> 解决，方案为 <strong>$extra</strong> $appendLink。', 'extra' => 'resolutionList');
$lang->bug->action->tostory           = array('main' => '$date, 由 <strong>$actor</strong> 转为<strong>需求</strong>，编号为 <strong>$extra</strong>。');
$lang->bug->action->totask            = array('main' => '$date, 由 <strong>$actor</strong> 导入为<strong>任务</strong>，编号为 <strong>$extra</strong>。');
$lang->bug->action->linked2plan         = array('main' => '$date, 由 <strong>$actor</strong> 关联到计划 <strong>$extra</strong>。');
$lang->bug->action->unlinkedfromplan    = array('main' => '$date, 由 <strong>$actor</strong> 从计划 <strong>$extra</strong> 移除。');
$lang->bug->action->linked2build        = array('main' => '$date, 由 <strong>$actor</strong> 关联到版本 <strong>$extra</strong>。');
$lang->bug->action->unlinkedfrombuild   = array('main' => '$date, 由 <strong>$actor</strong> 从版本 <strong>$extra</strong> 移除。');
$lang->bug->action->linked2release      = array('main' => '$date, 由 <strong>$actor</strong> 关联到发布 <strong>$extra</strong>。');
$lang->bug->action->unlinkedfromrelease = array('main' => '$date, 由 <strong>$actor</strong> 从发布 <strong>$extra</strong> 移除。');
$lang->bug->action->linkrelatedbug      = array('main' => '$date, 由 <strong>$actor</strong> 关联相关Bug <strong>$extra</strong>。');
$lang->bug->action->unlinkrelatedbug    = array('main' => '$date, 由 <strong>$actor</strong> 移除相关Bug <strong>$extra</strong>。');

$lang->bug->placeholder = new stdclass();
$lang->bug->placeholder->chooseBuilds = '选择相关版本...';
$lang->bug->placeholder->newBuildName = '新版本名称';

$lang->bug->featureBar['browse']['unclosed']      = $lang->bug->unclosed;
$lang->bug->featureBar['browse']['all']           = $lang->bug->allBugs;
$lang->bug->featureBar['browse']['assigntome']    = $lang->bug->assignToMe;
$lang->bug->featureBar['browse']['openedbyme']    = $lang->bug->openedByMe;
$lang->bug->featureBar['browse']['resolvedbyme']  = $lang->bug->resolvedByMe;
$lang->bug->featureBar['browse']['unconfirmed']   = $lang->bug->confirmedList[0];
$lang->bug->featureBar['browse']['assigntonull']  = $lang->bug->assignToNull;
$lang->bug->featureBar['browse']['unresolved']    = $lang->bug->unResolved;
$lang->bug->featureBar['browse']['toclosed']      = $lang->bug->toClosed;
$lang->bug->featureBar['browse']['longlifebugs']  = $lang->bug->longLifeBugs;
$lang->bug->featureBar['browse']['postponedbugs'] = $lang->bug->postponedBugs;
$lang->bug->featureBar['browse']['overduebugs']   = $lang->bug->overdueBugs;
$lang->bug->featureBar['browse']['needconfirm']   = $lang->bug->needConfirm;
