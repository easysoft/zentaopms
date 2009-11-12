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
 * @copyright   Copyright: 2009 Chunsheng Wang
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
$lang->bug->ajaxGetUserBugs = 'ajax:我的Bug';

$lang->bug->selectProduct  = '请选择产品';
$lang->bug->byModule       = '按模块';
$lang->bug->assignToMe     = '指派给我';
$lang->bug->openedByMe     = '由我创建';
$lang->bug->resolvedByMe   = '由我解决';
$lang->bug->assignToNull   = '未指派';
$lang->bug->longLifeBugs   = '久未处理';
$lang->bug->postponedBugs  = '被延期';
$lang->bug->allBugs        = '所有Bug';
$lang->bug->moduleBugs     = '%s';

$lang->bug->labProductAndModule         = '所属产品::模块';
$lang->bug->labProjectAndTask           = '相关项目::任务';
$lang->bug->labStory                    = '相关需求';
$lang->bug->labBuild                    = '程序编译版本';
$lang->bug->labTypeAndSeverity          = '类型::严重程度';
$lang->bug->labSystemBrowserAndHardware = '系统::浏览器';
$lang->bug->labAssignedTo               = '指派给';
$lang->bug->labMailto                   = '抄送给';

$lang->bug->confirmChangeProduct = '修改产品会导致相应的项目、需求和任务发生变化，确定吗？';

$lang->bug->legendRelated     = '相关信息';
$lang->bug->legendBasicInfo   = '基本信息';
$lang->bug->legendMailto      = '抄送给';
$lang->bug->legendAttatch     = '附件';
$lang->bug->legendLinkBugs    = '相关Bug';
$lang->bug->legendOpenInfo    = '创建信息';
$lang->bug->legendResolveInfo = '解决信息';
$lang->bug->legendCloseInfo   = '关闭信息';
$lang->bug->legendPrjStoryTask= '项目::需求::任务';
$lang->bug->legendCases       = '相关用例';
$lang->bug->legendSteps       = '重现步骤';
$lang->bug->legendAction      = '操作';
$lang->bug->legendHistory     = '历史记录';
$lang->bug->legendComment     = '备注';

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
$lang->bug->osList->all     = '全部';
$lang->bug->osList->winxp   = 'Windows XP';
$lang->bug->osList->win2000 = 'Windows 2000';
$lang->bug->osList->winnt   = 'Windows NT';
$lang->bug->osList->win98   = 'Windows 98';
$lang->bug->osList->linux   = 'Linux';
$lang->bug->osList->unix    = 'Unix';
$lang->bug->osList->others  = '其他';

/* Define the OS list. */
$lang->bug->browserList->all      = '全部';
$lang->bug->browserList->ie6      = 'IE6';
$lang->bug->browserList->ie7      = 'IE7';
$lang->bug->browserList->ie8      = 'IE8';
$lang->bug->browserList->firefox2 = 'firefox2';
$lang->bug->browserList->firefx3  = 'firefox3';
$lang->bug->browserList->opera9   = 'opera9';
$lang->bug->browserList->oprea10  = '其他';

/* Define the types. */
$lang->bug->typeList->codeerror    = '代码错误';
$lang->bug->typeList->interface    = '界面优化';
$lang->bug->typeList->designchange = '设计变更';
$lang->bug->typeList->Others       = '其他';

$lang->bug->statusList->active   = 'active';
$lang->bug->statusList->resolved = 'resolved';
$lang->bug->statusList->closed   = 'closed';

$lang->bug->resolutionList['']           = '';
$lang->bug->resolutionList['bydesign']   = 'By Design';
$lang->bug->resolutionList['duplicate']  = 'Duplicate';
$lang->bug->resolutionList['external']   = 'External';
$lang->bug->resolutionList['fixed']      = 'Fixed';
$lang->bug->resolutionList['notrepro']   = 'Not Repro';
$lang->bug->resolutionList['postponed']  = 'Postponed';
$lang->bug->resolutionList['willnotfix'] = "Won't Fix";

$lang->bug->id             = 'Bug编号';
$lang->bug->product        = '所属产品';
$lang->bug->module         = '所属模块';
$lang->bug->path           = '模块路径';
$lang->bug->project        = '所属项目';
$lang->bug->story          = '相关需求';
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
$lang->bug->openedBuild    = '创建Build';
$lang->bug->assignedTo     = '指派给';
$lang->bug->assignedDate   = '指派日期';
$lang->bug->resolvedBy     = '解决者';
$lang->bug->resolution     = '解决方案';
$lang->bug->resolvedBuild  = '解决Build';
$lang->bug->resolvedDate   = '解决日期';
$lang->bug->closedBy       = '由谁关闭';
$lang->bug->closedDate     = '关闭日期';
$lang->bug->duplicateBug   = '重复Bug';
$lang->bug->lastEditedBy   = '最后修改者';
$lang->bug->lastEditedDate = '最后修改日期';
$lang->bug->files          = '附件';
$lang->bug->field1         = 'field1';
$lang->bug->field2         = 'field2';
$lang->bug->feild3         = 'feild3';
