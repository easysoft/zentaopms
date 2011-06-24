<?php
/**
 * The bug module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.net
 */
/* 字段列表。*/
$lang->bug->common         = '缺陷管理';
$lang->bug->id             = 'Bug编号';
$lang->bug->product        = '所属产品';
$lang->bug->module         = '所属模块';
$lang->bug->path           = '模块路径';
$lang->bug->project        = '所属项目';
$lang->bug->story          = '相关需求';
$lang->bug->storyVersion   = '需求版本';
$lang->bug->task           = '相关任务';
$lang->bug->title          = 'Bug标题';
$lang->bug->severity       = '严重程度';
$lang->bug->severityAB     = '级别';
$lang->bug->pri            = '优先级';
$lang->bug->type           = 'Bug类型';
$lang->bug->os             = '操作系统';
$lang->bug->hardware       = '硬件平台';
$lang->bug->browser        = '浏览器';
$lang->bug->machine        = '机器硬件';
$lang->bug->found          = '如何发现';
$lang->bug->steps          = '重现步骤';
$lang->bug->status         = 'Bug状态';
$lang->bug->mailto         = '抄送给';
$lang->bug->openedBy       = '由谁创建';
$lang->bug->openedByAB     = '创建';
$lang->bug->openedDate     = '创建日期';
$lang->bug->openedBuild    = '影响版本';
$lang->bug->assignedTo     = '指派给';
$lang->bug->assignedDate   = '指派日期';
$lang->bug->resolvedBy     = '解决者';
$lang->bug->resolvedByAB   = '解决';
$lang->bug->resolution     = '解决方案';
$lang->bug->resolutionAB   = '方案';
$lang->bug->resolvedBuild  = '解决版本';
$lang->bug->resolvedDate   = '解决日期';
$lang->bug->closedBy       = '由谁关闭';
$lang->bug->closedDate     = '关闭日期';
$lang->bug->duplicateBug   = '重复Bug';
$lang->bug->lastEditedBy   = '最后修改者';
$lang->bug->lastEditedDate = '最后修改日期';
$lang->bug->linkBug        = '相关Bug';
$lang->bug->case           = '相关用例';
$lang->bug->files          = '附件';
$lang->bug->keywords       = '关键词';
$lang->bug->lastEditedByAB   = '修改者';
$lang->bug->lastEditedDateAB = '修改日期';

/* 方法列表。*/
$lang->bug->index          = '首页';
$lang->bug->create         = '创建Bug';
$lang->bug->edit           = '编辑Bug';
$lang->bug->browse         = 'Bug列表';
$lang->bug->view           = 'Bug详情';
$lang->bug->resolve        = '解决Bug';
$lang->bug->close          = '关闭Bug';
$lang->bug->activate       = '激活Bug';
$lang->bug->reportChart    = '报表统计';
$lang->bug->export         = '导出数据';
$lang->bug->delete         = '删除Bug';
$lang->bug->saveTemplate   = '保存模板';
$lang->bug->deleteTemplate = '删除模板';
$lang->bug->customFields   = '自定义字段';
$lang->bug->restoreDefault = '恢复默认';
$lang->bug->ajaxGetUserBugs    = '接口:我的Bug';
$lang->bug->ajaxGetModuleOwner = '接口:获得模块的默认指派人';
$lang->bug->confirmStoryChange = '确认需求变动';

/* 查询条件列表。*/
$lang->bug->selectProduct  = '请选择产品';
$lang->bug->byModule       = '按模块';
$lang->bug->assignToMe     = '指派给我';
$lang->bug->openedByMe     = '由我创建';
$lang->bug->resolvedByMe   = '由我解决';
$lang->bug->closedByMe     = '由我关闭';
$lang->bug->assignToNull   = '未指派';
$lang->bug->unResolved     = '未解决';
$lang->bug->longLifeBugs   = '久未处理';
$lang->bug->postponedBugs  = '被延期';
$lang->bug->allBugs        = '所有Bug';
$lang->bug->moduleBugs     = '按模块浏览';
$lang->bug->byQuery        = '搜索';
$lang->bug->needConfirm    = '需求变动';
$lang->bug->allProduct     = '所有产品';

/* 页面标签。*/
$lang->bug->lblProductAndModule         = '产品模块';
$lang->bug->lblProjectAndTask           = '项目任务';
$lang->bug->lblStory                    = '相关需求';
$lang->bug->lblTypeAndSeverity          = '类型/严重程度';
$lang->bug->lblSystemBrowserAndHardware = '系统/浏览器';
$lang->bug->lblAssignedTo               = '当前指派';
$lang->bug->lblMailto                   = '抄送给';
$lang->bug->lblLastEdited               = '最后修改';
$lang->bug->lblResolved                 = '由谁解决';
$lang->bug->lblAllFields                = '所有字段';
$lang->bug->lblCustomFields             = '自定义字段';

/* legend列表。*/
$lang->bug->legendBasicInfo   = '基本信息';
$lang->bug->legendMailto      = '抄送给';
$lang->bug->legendAttatch     = '附件';
$lang->bug->legendLinkBugs    = '相关Bug';
$lang->bug->legendPrjStoryTask= '项目/需求/任务';
$lang->bug->legendCases       = '相关用例';
$lang->bug->legendSteps       = '重现步骤';
$lang->bug->legendAction      = '操作';
$lang->bug->legendHistory     = '历史记录';
$lang->bug->legendComment     = '备注';
$lang->bug->legendLife        = 'BUG的一生';
$lang->bug->legendMisc        = '其相关他';

/* 功能按钮。*/
$lang->bug->buttonCopy     = '复制';
$lang->bug->buttonEdit     = '编辑';
$lang->bug->buttonActivate = '激活';
$lang->bug->buttonResolve  = '解决';
$lang->bug->buttonClose    = '关闭';
$lang->bug->buttonToList   = '返回';

/* 交互提示。*/
$lang->bug->confirmChangeProduct = '修改产品会导致相应的项目、需求和任务发生变化，确定吗？';
$lang->bug->confirmDelete        = '您确认要删除该Bug吗？';
$lang->bug->setTemplateTitle     = '请输入bug模板标题（保存之前请先填写bug重现步骤）：';

/* 模板。*/
$lang->bug->tplStep        = "<p>[步骤]</p>";
$lang->bug->tplResult      = "<p>[结果]</p>";
$lang->bug->tplExpect      = "<p>[期望]</p>";

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
$lang->bug->osList['winxp']   = 'Windows XP';
$lang->bug->osList['win7']    = 'Windows 7';
$lang->bug->osList['vista']   = 'Windows Vista';
$lang->bug->osList['win2000'] = 'Windows 2000';
$lang->bug->osList['win2003'] = 'Windows 2003';
$lang->bug->osList['win2008'] = 'Windows 2008';
$lang->bug->osList['winnt']   = 'Windows NT';
$lang->bug->osList['win98']   = 'Windows 98';
$lang->bug->osList['linux']   = 'Linux';
$lang->bug->osList['freebsd'] = 'FreeBSD';
$lang->bug->osList['mac']     = 'Mac OS';
$lang->bug->osList['unix']    = 'Unix';
$lang->bug->osList['others']  = '其他';

$lang->bug->browserList['']         = '';
$lang->bug->browserList['all']      = '全部';
$lang->bug->browserList['ie']       = 'IE系列';
$lang->bug->browserList['ie6']      = 'IE6';
$lang->bug->browserList['ie7']      = 'IE7';
$lang->bug->browserList['ie8']      = 'IE8';
$lang->bug->browserList['firefox']  = 'firefox系列';
$lang->bug->browserList['firefox2'] = 'firefox2';
$lang->bug->browserList['firefox3']  = 'firefox3';
$lang->bug->browserList['firefox4']  = 'firefox4';
$lang->bug->browserList['opera']    = 'opera系列';
$lang->bug->browserList['opera9']   = 'opera9';
$lang->bug->browserList['oprea10']  = 'opera10';
$lang->bug->browserList['oprea11']  = 'opera11';
$lang->bug->browserList['safari']   = 'safari';
$lang->bug->browserList['chrome']   = 'chrome';
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
$lang->bug->typeList['Others']       = '其他';

$lang->bug->statusList['']         = '';
$lang->bug->statusList['active']   = '激活';
$lang->bug->statusList['resolved'] = '已解决';
$lang->bug->statusList['closed']   = '已关闭';

$lang->bug->resolutionList['']           = '';
$lang->bug->resolutionList['bydesign']   = '设计如此';
$lang->bug->resolutionList['duplicate']  = '重复Bug';
$lang->bug->resolutionList['external']   = '外部原因';
$lang->bug->resolutionList['fixed']      = '已解决';
$lang->bug->resolutionList['notrepro']   = '无法重现';
$lang->bug->resolutionList['postponed']  = '延期处理';
$lang->bug->resolutionList['willnotfix'] = "不予解决";

/* 统计报表。*/
$lang->bug->report->common        = '统计报表';
$lang->bug->report->select        = '请选择报表类型';
$lang->bug->report->create        = '生成报表';
$lang->bug->report->selectAll     = '全选';
$lang->bug->report->selectReverse = '反选';

$lang->bug->report->charts['bugsPerProject']     = '项目Bug数量';
$lang->bug->report->charts['bugsPerModule']      = '模块Bug数量';
$lang->bug->report->charts['openedBugsPerDay']   = '每天新增Bug数';
$lang->bug->report->charts['resolvedBugsPerDay'] = '每天解决Bug数';
$lang->bug->report->charts['closedBugsPerDay']   = '每天关闭的Bug数';
$lang->bug->report->charts['openedBugsPerUser']  = '每人提交的Bug数';
$lang->bug->report->charts['resolvedBugsPerUser']= '每人解决的Bug数';
$lang->bug->report->charts['closedBugsPerUser']  = '每人关闭的Bug数';
$lang->bug->report->charts['bugsPerSeverity']    = 'Bug严重程度统计';
$lang->bug->report->charts['bugsPerResolution']  = 'Bug解决方案统计';
$lang->bug->report->charts['bugsPerStatus']      = 'Bug状态统计';
$lang->bug->report->charts['bugsPerType']        = 'Bug类型统计';
$lang->bug->report->charts['bugsPerAssignedTo']  = '指派给统计';
//$lang->bug->report->charts['bugLiveDays']        = 'Bug处理时间统计';
//$lang->bug->report->charts['bugHistories']       = 'Bug处理步骤统计';

$lang->bug->report->options->swf                     = 'pie2d';
$lang->bug->report->options->width                   = 'auto';
$lang->bug->report->options->height                  = 300;
$lang->bug->report->options->graph->baseFontSize     = 12;
$lang->bug->report->options->graph->showNames        = 1;
$lang->bug->report->options->graph->formatNumber     = 1;
$lang->bug->report->options->graph->decimalPrecision = 0;
$lang->bug->report->options->graph->animation        = 0;
$lang->bug->report->options->graph->rotateNames      = 0;
$lang->bug->report->options->graph->yAxisName        = 'COUNT';
$lang->bug->report->options->graph->pieRadius        = 100; // 饼图直径。
$lang->bug->report->options->graph->showColumnShadow = 0;   // 是否显示柱状图阴影。

$lang->bug->report->bugsPerProject->graph->xAxisName     = '项目';
$lang->bug->report->bugsPerModule->graph->xAxisName      = '模块';

$lang->bug->report->openedBugsPerDay->swf                = 'column2d';
$lang->bug->report->openedBugsPerDay->height             = 400;
$lang->bug->report->openedBugsPerDay->graph->xAxisName   = '日期';
$lang->bug->report->openedBugsPerDay->graph->rotateNames = 1;

$lang->bug->report->resolvedBugsPerDay->swf              = 'column2d';
$lang->bug->report->resolvedBugsPerDay->height           = 400;
$lang->bug->report->resolvedBugsPerDay->graph->xAxisName = '日期';
$lang->bug->report->resolvedBugsPerDay->graph->rotateNames = 1;

$lang->bug->report->closedBugsPerDay->swf                = 'column2d';
$lang->bug->report->closedBugsPerDay->height             = 400;
$lang->bug->report->closedBugsPerDay->graph->xAxisName   = '日期';
$lang->bug->report->closedBugsPerDay->graph->rotateNames = 1;

$lang->bug->report->openedBugsPerUser->graph->xAxisName  = '用户';
$lang->bug->report->resolvedBugsPerUser->graph->xAxisName= '用户';
$lang->bug->report->closedBugsPerUser->graph->xAxisName  = '用户';

$lang->bug->report->bugsPerSeverity->graph->xAxisName    = '严重程度';
$lang->bug->report->bugsPerResolution->graph->xAxisName  = '解决方案';
$lang->bug->report->bugsPerStatus->graph->xAxisName      = '状态';
$lang->bug->report->bugsPerType->graph->xAxisName        = '类型';
$lang->bug->report->bugsPerAssignedTo->graph->xAxisName  = '指派给';
$lang->bug->report->bugLiveDays->graph->xAxisName        = '处理时间';
$lang->bug->report->bugHistories->graph->xAxisName       = '处理步骤';

/* 操作记录。*/
$lang->bug->action->resolved = array('main' => '$date, 由 <strong>$actor</strong> 解决，方案为 <strong>$extra</strong>。', 'extra' => $lang->bug->resolutionList);
