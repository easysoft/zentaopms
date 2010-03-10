<?php
/**
 * The bug module zh-cn file of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
$lang->bug->common         = '缺陷管理';
$lang->bug->index          = '首页';
$lang->bug->create         = '创建Bug';
$lang->bug->edit           = '编辑Bug';
$lang->bug->browse         = 'Bug列表';
$lang->bug->view           = 'Bug详情';
$lang->bug->resolve        = '解决Bug';
$lang->bug->close          = '关闭Bug';
$lang->bug->activate       = '激活Bug';
$lang->bug->reportChart    = '报表统计';
$lang->bug->ajaxGetUserBugs = '接口:我的Bug';

$lang->bug->selectProduct  = '请选择产品';
$lang->bug->byModule       = '按模块';
$lang->bug->assignToMe     = '指派给我';
$lang->bug->openedByMe     = '由我创建';
$lang->bug->resolvedByMe   = '由我解决';
$lang->bug->assignToNull   = '未指派';
$lang->bug->longLifeBugs   = '久未处理';
$lang->bug->postponedBugs  = '被延期';
$lang->bug->allBugs        = '所有Bug';
$lang->bug->moduleBugs     = '按模块浏览';
$lang->bug->byQuery        = '搜索';
$lang->bug->allProduct     = '所有产品';

$lang->bug->lblProductAndModule         = '产品模块';
$lang->bug->lblProjectAndTask           = '项目任务';
$lang->bug->lblStory                    = '相关需求';
$lang->bug->lblTypeAndSeverity          = '类型/严重程度';
$lang->bug->lblSystemBrowserAndHardware = '系统/浏览器';
$lang->bug->lblAssignedTo               = '当前指派';
$lang->bug->lblMailto                   = '抄送给';
$lang->bug->lblLastEdited               = '最后修改';
$lang->bug->lblResolved                 = '由谁解决';

$lang->bug->confirmChangeProduct = '修改产品会导致相应的项目、需求和任务发生变化，确定吗？';

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

$lang->bug->buttonEdit     = '编辑';
$lang->bug->buttonActivate = '激活';
$lang->bug->buttonResolve  = '解决';
$lang->bug->buttonClose    = '关闭';
$lang->bug->buttonToList   = '返回';

$lang->bug->severityList[3] = 3;
$lang->bug->severityList[1] = 1;
$lang->bug->severityList[2] = 2;
$lang->bug->severityList[4] = 4;

/* Define the OS list. */
$lang->bug->osList['all']     = '全部';
$lang->bug->osList['winxp']   = 'Windows XP';
$lang->bug->osList['win7']    = 'Windows 7';
$lang->bug->osList['vista']   = 'Windows Vista';
$lang->bug->osList['win2000'] = 'Windows 2000';
$lang->bug->osList['winnt']   = 'Windows NT';
$lang->bug->osList['win98']   = 'Windows 98';
$lang->bug->osList['linux']   = 'Linux';
$lang->bug->osList['unix']    = 'Unix';
$lang->bug->osList['others']  = '其他';

/* Define the OS list. */
$lang->bug->browserList['all']      = '全部';
$lang->bug->browserList['ie6']      = 'IE6';
$lang->bug->browserList['ie7']      = 'IE7';
$lang->bug->browserList['ie8']      = 'IE8';
$lang->bug->browserList['firefox2'] = 'firefox2';
$lang->bug->browserList['firefx3']  = 'firefox3';
$lang->bug->browserList['opera9']   = 'opera9';
$lang->bug->browserList['oprea10']  = 'opera10';
$lang->bug->browserList['safari']   = 'safari';
$lang->bug->browserList['chrome']   = 'chrome';
$lang->bug->browserList['other']    = '其他';

/* Define the types. */
$lang->bug->typeList['']             = '';
$lang->bug->typeList['codeerror']    = '代码错误';
$lang->bug->typeList['interface']    = '界面优化';
$lang->bug->typeList['designchange'] = '设计变更';
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
$lang->bug->type           = 'Bug类型';
$lang->bug->os             = '操作系统';
$lang->bug->browser        = '浏览器';
$lang->bug->machine        = '机器硬件';
$lang->bug->found          = '如何发现';
$lang->bug->steps          = '重现步骤';
$lang->bug->status         = 'Bug状态';
$lang->bug->mailto         = '抄送给';
$lang->bug->openedBy       = '由谁创建';
$lang->bug->openedDate     = '创建日期';
$lang->bug->openedBuild    = '影响版本';
$lang->bug->assignedTo     = '指派给';
$lang->bug->assignedDate   = '指派日期';
$lang->bug->resolvedBy     = '解决者';
$lang->bug->resolution     = '解决方案';
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

$lang->bug->tblStep        = "[步骤]\n";
$lang->bug->tblResult      = "[结果]\n";
$lang->bug->tblExpect      = "[期望]\n";

$lang->bug->action->resolved = array('main' => '$date, 由 <strong>$actor</strong> 解决，方案为 <strong>$extra</strong>。', 'extra' => $lang->bug->resolutionList);

$lang->bug->report->common = '统计报表';
$lang->bug->report->select = '请选择报表类型';
$lang->bug->report->create = '生成报表';

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
$lang->bug->report->bugLiveDays->graph->xAxisName        = '处理时间';
$lang->bug->report->bugHistories->graph->xAxisName       = '处理步骤';
