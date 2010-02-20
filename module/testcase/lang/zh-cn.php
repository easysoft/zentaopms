<?php
/**
 * The testcase module zh-cn file of ZenTaoMS.
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
 * @package     testcase
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
$lang->testcase->id             = '用例编号';
$lang->testcase->product        = '所属产品';
$lang->testcase->module         = '所属模块';
$lang->testcase->story          = '相关需求';
$lang->testcase->storyVersion   = '需求版本';
$lang->testcase->title          = '用例标题';
$lang->testcase->pri            = '优先级';
$lang->testcase->type           = '用例类型';
$lang->testcase->status         = '用例状态';
$lang->testcase->steps          = '用例步骤';
$lang->testcase->frequency      = '执行频率';
$lang->testcase->order          = '排序';
$lang->testcase->openedBy       = '由谁创建 ';
$lang->testcase->openedDate     = '创建日期';
$lang->testcase->lastEditedBy   = '最后修改者';
$lang->testcase->lastEditedDate = '最后修改日期';
$lang->testcase->version        = '用例版本';
$lang->testcase->result         = '测试结果';
$lang->testcase->real           = '实际情况';
$lang->case = $lang->testcase;  // 用于DAO检查时使用。因为case是系统关键字，所以无法定义该模块为case，只能使用testcase，但表还是使用的case。

$lang->testcase->stepID     = '编号';
$lang->testcase->stepDesc   = '步骤';
$lang->testcase->stepExpect = '预期';

$lang->testcase->common         = '用例管理';
$lang->testcase->index          = "用例管理首页";
$lang->testcase->create         = "创建用例";
$lang->testcase->delete         = "删除用例";
$lang->testcase->view           = "用例详情";
$lang->testcase->edit           = "编辑用例";
$lang->testcase->browse         = "用例列表";

$lang->testcase->deleteStep     = 'ｘ';
$lang->testcase->insertBefore   = '＋↑';
$lang->testcase->insertAfter    = '＋↓';

$lang->testcase->selectProduct  = '请选择产品';
$lang->testcase->byModule       = '按模块';
$lang->testcase->assignToMe     = '指派给我';
$lang->testcase->openedByMe     = '由我创建';
$lang->testcase->allCases       = '所有Case';
$lang->testcase->moduleCases    = '按模块浏览';
$lang->testcase->bySearch       = '搜索';

$lang->testcase->lblProductAndModule         = '产品模块';
$lang->testcase->lblTypeAndPri               = '类型&优先级';
$lang->testcase->lblSystemBrowserAndHardware = '系统::浏览器';
$lang->testcase->lblAssignAndMail            = '指派给::抄送给';
$lang->testcase->lblStory                    = '相关需求';
$lang->testcase->lblLastEdited               = '最后编辑';

$lang->testcase->legendRelated     = '相关信息';
$lang->testcase->legendBasicInfo   = '基本信息';
$lang->testcase->legendMailto      = '抄送给';
$lang->testcase->legendAttatch     = '附件';
$lang->testcase->legendLinkBugs    = '相关Bug';
$lang->testcase->legendOpenAndEdit = '创建编辑';
$lang->testcase->legendStoryAndTask= '需求::任务';
$lang->testcase->legendCases       = '相关用例';
$lang->testcase->legendSteps       = '用例步骤';
$lang->testcase->legendAction      = '操作';
$lang->testcase->legendHistory     = '历史记录';
$lang->testcase->legendComment     = '备注';
$lang->testcase->legendProduct     = '产品模块';
$lang->testcase->legendVersion     = '版本历史';

$lang->testcase->priList[3] = 3;
$lang->testcase->priList[1] = 1;
$lang->testcase->priList[2] = 2;
$lang->testcase->priList[4] = 4;

/* Define the types. */
$lang->testcase->typeList['']            = '';
$lang->testcase->typeList['feature']     = '功能测试';
$lang->testcase->typeList['performance'] = '性能测试';

$lang->testcase->statusList['']         = '';
$lang->testcase->statusList['normal']   = '正常';
$lang->testcase->statusList['blocked']  = 'Blocked';

$lang->testcase->resultList['n/a']     = 'N/A';
$lang->testcase->resultList['pass']    = '通过';
$lang->testcase->resultList['fail']    = '失败';
$lang->testcase->resultList['blocked'] = '阻塞';

$lang->testcase->buttonEdit     = '编辑';
$lang->testcase->buttonToList   = '返回';
