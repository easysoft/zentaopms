<?php
/**
 * The testcase module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testcase
 * @version     $Id: zh-cn.php 4764 2013-05-05 04:07:04Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->testcase->id               = '用例编号';
$lang->testcase->product          = "所属{$lang->productcommon}";
$lang->testcase->module           = '所属模块';
$lang->testcase->story            = '相关需求';
$lang->testcase->storyVersion     = '需求版本';
$lang->testcase->title            = '用例标题';
$lang->testcase->precondition     = '前置条件';
$lang->testcase->pri              = '优先级';
$lang->testcase->type             = '用例类型';
$lang->testcase->status           = '用例状态';
$lang->testcase->steps            = '用例步骤';
$lang->testcase->frequency        = '执行频率';
$lang->testcase->order            = '排序';
$lang->testcase->openedBy         = '由谁创建 ';
$lang->testcase->openedDate       = '创建日期';
$lang->testcase->lastEditedBy     = '最后修改者';
$lang->testcase->lastEditedDate   = '最后修改日期';
$lang->testcase->version          = '用例版本';
$lang->testcase->result           = '测试结果';
$lang->testcase->lastRunResult    = '结果';
$lang->testcase->real             = '实际情况';
$lang->testcase->keywords         = '关键词';
$lang->testcase->files            = '附件';
$lang->testcase->howRun           = '执行方式';
$lang->testcase->scriptedBy       = '由谁编写';
$lang->testcase->scriptedDate     = '编写日期';
$lang->testcase->scriptedStatus   = '脚本状态';
$lang->testcase->scriptedLocation = '脚本位置';
$lang->testcase->linkCase         = '相关用例';
$lang->testcase->stage            = '适用阶段';
$lang->testcase->lastEditedByAB   = '修改者';
$lang->testcase->lastEditedDateAB = '修改日期';
$lang->testcase->allProduct       = "所有{$lang->productcommon}";
$lang->testcase->fromBug          = '来源Bug';
$lang->testcase->toBug            = '生成Bug';
$lang->testcase->changed          = '用例变更';
$lang->testcase->createBug        = '转Bug';
$lang->case = $lang->testcase;  // 用于DAO检查时使用。因为case是系统关键字，所以无法定义该模块为case，只能使用testcase，但表还是使用的case。

$lang->testcase->stepID     = '编号';
$lang->testcase->stepDesc   = '步骤';
$lang->testcase->stepExpect = '预期';

$lang->testcase->common             = '用例';
$lang->testcase->index              = "用例管理首页";
$lang->testcase->create             = "建用例";
$lang->testcase->batchCreate        = "批量添加";
$lang->testcase->delete             = "删除用例";
$lang->testcase->view               = "用例详情";
$lang->testcase->edit               = "编辑";
$lang->testcase->batchEdit          = "批量编辑 ";
$lang->testcase->delete             = "删除";
$lang->testcase->batchDelete        = "批量删除 ";
$lang->testcase->deleted            = "已删除";
$lang->testcase->browse             = "用例列表";
$lang->testcase->groupCase          = "分组浏览用例";
$lang->testcase->import             = "导入";
$lang->testcase->importID           = "行号";
$lang->testcase->showImport         = "显示导入内容";
$lang->testcase->exportTemplet      = "导出模板";
$lang->testcase->export             = "导出数据";
$lang->testcase->confirmChange      = '确认用例变动';
$lang->testcase->confirmStoryChange = '确认需求变动';

$lang->testcase->new = '新增';

$lang->testcase->num = '用例记录数：';

$lang->testcase->deleteStep   = '删除';
$lang->testcase->insertBefore = '之前添加';
$lang->testcase->insertAfter  = '之后添加';

$lang->testcase->selectProduct = "请选择{$lang->productcommon}";
$lang->testcase->byModule      = '按模块';
$lang->testcase->assignToMe    = '给我的用例';
$lang->testcase->openedByMe    = '我建的用例';
$lang->testcase->allCases      = '所有';
$lang->testcase->needConfirm   = '需求变动';
$lang->testcase->moduleCases   = '按模块';
$lang->testcase->bySearch      = '搜索';
$lang->testcase->doneByMe      = '我完成的用例';

$lang->testcase->lblProductAndModule         = "{$lang->productcommon}模块";
$lang->testcase->lblTypeAndPri               = '类型&优先级';
$lang->testcase->lblSystemBrowserAndHardware = '系统::浏览器';
$lang->testcase->lblAssignAndMail            = '指派给::抄送给';
$lang->testcase->lblStory                    = '相关需求';
$lang->testcase->lblLastEdited               = '最后编辑';

$lang->testcase->legendRelated      = '相关信息';
$lang->testcase->legendBasicInfo    = '基本信息';
$lang->testcase->legendMailto       = '抄送给';
$lang->testcase->legendAttatch      = '附件';
$lang->testcase->legendLinkBugs     = '相关Bug';
$lang->testcase->legendOpenAndEdit  = '创建编辑';
$lang->testcase->legendStoryAndTask = '需求::任务';
$lang->testcase->legendCases        = '相关用例';
$lang->testcase->legendSteps        = '用例步骤';
$lang->testcase->legendAction       = '操作';
$lang->testcase->legendHistory      = '历史记录';
$lang->testcase->legendComment      = '备注';
$lang->testcase->legendProduct      = $lang->productcommon . '模块';

$lang->testcase->confirmDelete      = '您确认要删除该测试用例吗？';
$lang->testcase->confirmBatchDelete = '您确认要批量删除这些测试用例吗？';
$lang->testcase->same               = '同上';

$lang->testcase->priList[3] = 3;
$lang->testcase->priList[1] = 1;
$lang->testcase->priList[2] = 2;
$lang->testcase->priList[4] = 4;

/* Define the types. */
$lang->testcase->typeList['']            = '';
$lang->testcase->typeList['feature']     = '功能测试';
$lang->testcase->typeList['performance'] = '性能测试';
$lang->testcase->typeList['config']      = '配置相关';
$lang->testcase->typeList['install']     = '安装部署';
$lang->testcase->typeList['security']    = '安全相关';
$lang->testcase->typeList['interface']   = '接口测试';
$lang->testcase->typeList['other']       = '其他';

$lang->testcase->stageList['']           = '';
$lang->testcase->stageList['unittest']   = '单元测试阶段';
$lang->testcase->stageList['feature']    = '功能测试阶段';
$lang->testcase->stageList['intergrate'] = '集成测试阶段';
$lang->testcase->stageList['system']     = '系统测试阶段';
$lang->testcase->stageList['smoke']      = '冒烟测试阶段';
$lang->testcase->stageList['bvt']        = '版本验证阶段';

$lang->testcase->groups['']      = '分组查看';
$lang->testcase->groups['story'] = '需求分组';

$lang->testcase->statusList['']            = '';
$lang->testcase->statusList['normal']      = '正常';
$lang->testcase->statusList['blocked']     = '被阻塞';
$lang->testcase->statusList['investigate'] = '研究中';

$lang->testcase->resultList['n/a']     = 'N/A';
$lang->testcase->resultList['pass']    = '通过';
$lang->testcase->resultList['fail']    = '失败';
$lang->testcase->resultList['blocked'] = '阻塞';

$lang->testcase->buttonEdit   = '编辑';
$lang->testcase->buttonToList = '返回';

$lang->testcase->errorEncode = '无数据，请选择正确的编码重新上传！';
$lang->testcase->noFunction  = '不存在iconv和mb_convert_encoding转码方法，不能将数据转成想要的编码！';
$lang->testcase->noRequire   = "%s行的“%s”是必填字段，不能为空";

$lang->testcase->searchStories = '键入来搜索需求';
