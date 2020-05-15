<?php
/**
 * The testcase module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testcase
 * @version     $Id: zh-cn.php 4764 2013-05-05 04:07:04Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->testcase->id               = '用例编号';
$lang->testcase->product          = "所属{$lang->productCommon}";
$lang->testcase->module           = '所属模块';
$lang->testcase->lib              = "所属库";
$lang->testcase->branch           = "分支/平台";
$lang->testcase->moduleAB         = '模块';
$lang->testcase->story            = "相关{$lang->storyCommon}";
$lang->testcase->storyVersion     = "{$lang->storyCommon}版本";
$lang->testcase->color            = '标题颜色';
$lang->testcase->order            = '排序';
$lang->testcase->title            = '用例标题';
$lang->testcase->precondition     = '前置条件';
$lang->testcase->pri              = '优先级';
$lang->testcase->type             = '用例类型';
$lang->testcase->status           = '用例状态';
$lang->testcase->subStatus        = '子状态';
$lang->testcase->steps            = '用例步骤';
$lang->testcase->openedBy         = '由谁创建';
$lang->testcase->openedDate       = '创建日期';
$lang->testcase->lastEditedBy     = '最后修改者';
$lang->testcase->result           = '测试结果';
$lang->testcase->real             = '实际情况';
$lang->testcase->keywords         = '关键词';
$lang->testcase->files            = '附件';
$lang->testcase->linkCase         = '相关用例';
$lang->testcase->linkCases        = '关联相关用例';
$lang->testcase->unlinkCase       = '移除相关用例';
$lang->testcase->stage            = '适用阶段';
$lang->testcase->reviewedBy       = '由谁评审';
$lang->testcase->reviewedDate     = '评审时间';
$lang->testcase->reviewResult     = '评审结果';
$lang->testcase->reviewedByAB     = '评审人';
$lang->testcase->reviewedDateAB   = '日期';
$lang->testcase->reviewResultAB   = '结果';
$lang->testcase->forceNotReview   = '不需要评审';
$lang->testcase->lastEditedByAB   = '修改者';
$lang->testcase->lastEditedDateAB = '修改日期';
$lang->testcase->lastEditedDate   = '修改日期';
$lang->testcase->version          = '用例版本';
$lang->testcase->lastRunner       = '执行人';
$lang->testcase->lastRunDate      = '执行时间';
$lang->testcase->assignedTo       = '指派给';
$lang->testcase->colorTag         = '颜色标签';
$lang->testcase->lastRunResult    = '结果';
$lang->testcase->desc             = '步骤';
$lang->testcase->xml              = 'XML';
$lang->testcase->expect           = '预期';
$lang->testcase->allProduct       = "所有{$lang->productCommon}";
$lang->testcase->fromBug          = '来源Bug';
$lang->testcase->toBug            = '生成Bug';
$lang->testcase->changed          = '原用例更新';
$lang->testcase->bugs             = '产生Bug数';
$lang->testcase->bugsAB           = 'B';
$lang->testcase->results          = '执行结果数';
$lang->testcase->resultsAB        = 'R';
$lang->testcase->stepNumber       = '用例步骤数';
$lang->testcase->stepNumberAB     = 'S';
$lang->testcase->createBug        = '转Bug';
$lang->testcase->fromModule       = '来源模块';
$lang->testcase->fromCase         = '来源用例';
$lang->testcase->sync             = '同步';
$lang->testcase->ignore           = '忽略';
$lang->testcase->fromTesttask     = '来自测试单用例';
$lang->testcase->fromCaselib      = '来自用例库用例';
$lang->testcase->deleted          = '是否删除';
$lang->case = $lang->testcase;  // 用于DAO检查时使用。因为case是系统关键字，所以无法定义该模块为case，只能使用testcase，但表还是使用的case。

$lang->testcase->stepID      = '编号';
$lang->testcase->stepDesc    = '步骤';
$lang->testcase->stepExpect  = '预期';
$lang->testcase->stepVersion = '版本';

$lang->testcase->common                  = '用例';
$lang->testcase->index                   = "用例管理首页";
$lang->testcase->create                  = "建用例";
$lang->testcase->batchCreate             = "批量建用例";
$lang->testcase->delete                  = "删除";
$lang->testcase->deleteAction            = "删除用例";
$lang->testcase->view                    = "用例详情";
$lang->testcase->review                  = "评审";
$lang->testcase->reviewAB                = "评审";
$lang->testcase->batchReview             = "批量评审";
$lang->testcase->edit                    = "编辑用例";
$lang->testcase->batchEdit               = "批量编辑 ";
$lang->testcase->batchChangeModule       = "批量修改模块";
$lang->testcase->confirmLibcaseChange    = "同步用例库用例修改";
$lang->testcase->ignoreLibcaseChange     = "忽略用例库用例修改";
$lang->testcase->batchChangeBranch       = "批量修改分支";
$lang->testcase->groupByStories          = "{$lang->storyCommon}分组";
$lang->testcase->batchDelete             = "批量删除 ";
$lang->testcase->batchConfirmStoryChange = "批量确认变更";
$lang->testcase->batchCaseTypeChange     = "批量修改类型";
$lang->testcase->browse                  = "用例列表";
$lang->testcase->groupCase               = "分组浏览用例";
$lang->testcase->import                  = "导入";
$lang->testcase->importAction            = "导入用例";
$lang->testcase->fileImport              = "导入CSV";
$lang->testcase->importFromLib           = "从用例库中导入";
$lang->testcase->showImport              = "显示导入内容";
$lang->testcase->exportTemplet           = "导出模板";
$lang->testcase->export                  = "导出数据";
$lang->testcase->exportAction            = "导出用例";
$lang->testcase->reportChart             = '报表统计';
$lang->testcase->reportAction            = '用例报表统计';
$lang->testcase->confirmChange           = '确认用例变动';
$lang->testcase->confirmStoryChange      = "确认{$lang->storyCommon}变动";
$lang->testcase->copy                    = '复制用例';
$lang->testcase->group                   = '分组';
$lang->testcase->groupName               = '分组名称';
$lang->testcase->step                    = '步骤';
$lang->testcase->stepChild               = '子步骤';
$lang->testcase->viewAll                 = '查看所有';

$lang->testcase->new = '新增';

$lang->testcase->num = '用例记录数：';

$lang->testcase->deleteStep   = '删除';
$lang->testcase->insertBefore = '之前添加';
$lang->testcase->insertAfter  = '之后添加';

$lang->testcase->assignToMe   = '给我的用例';
$lang->testcase->openedByMe   = '我建的用例';
$lang->testcase->allCases     = '所有';
$lang->testcase->allTestcases = '所有用例';
$lang->testcase->needConfirm  = "{$lang->storyCommon}变动";
$lang->testcase->bySearch     = '搜索';
$lang->testcase->unexecuted   = '未执行';

$lang->testcase->lblStory       = "相关{$lang->storyCommon}";
$lang->testcase->lblLastEdited  = '最后编辑';
$lang->testcase->lblTypeValue   = '类型可选值列表';
$lang->testcase->lblStageValue  = '阶段可选值列表';
$lang->testcase->lblStatusValue = '状态可选值列表';

$lang->testcase->legendBasicInfo   = '基本信息';
$lang->testcase->legendAttatch     = '附件';
$lang->testcase->legendLinkBugs    = '相关Bug';
$lang->testcase->legendOpenAndEdit = '创建编辑';
$lang->testcase->legendComment     = '备注';

$lang->testcase->summary            = "本页共 <strong>%s</strong> 个用例，已执行<strong>%s</strong>个。";
$lang->testcase->confirmDelete      = '您确认要删除该测试用例吗？';
$lang->testcase->confirmBatchDelete = '您确认要批量删除这些测试用例吗？';
$lang->testcase->ditto              = '同上';
$lang->testcase->dittoNotice        = '该用例与上一用例不属于同一产品！';

$lang->testcase->reviewList[0] = '否';
$lang->testcase->reviewList[1] = '是';

$lang->testcase->priList[0] = '';
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
$lang->testcase->typeList['unit']        = '单元测试';
$lang->testcase->typeList['other']       = '其他';

$lang->testcase->stageList['']           = '';
$lang->testcase->stageList['unittest']   = '单元测试阶段';
$lang->testcase->stageList['feature']    = '功能测试阶段';
$lang->testcase->stageList['intergrate'] = '集成测试阶段';
$lang->testcase->stageList['system']     = '系统测试阶段';
$lang->testcase->stageList['smoke']      = '冒烟测试阶段';
$lang->testcase->stageList['bvt']        = '版本验证阶段';

$lang->testcase->reviewResultList['']        = '';
$lang->testcase->reviewResultList['pass']    = '确认通过';
$lang->testcase->reviewResultList['clarify'] = '继续完善';

$lang->testcase->statusList['']            = '';
$lang->testcase->statusList['wait']        = '待评审';
$lang->testcase->statusList['normal']      = '正常';
$lang->testcase->statusList['blocked']     = '被阻塞';
$lang->testcase->statusList['investigate'] = '研究中';

$lang->testcase->resultList['n/a']     = '忽略';
$lang->testcase->resultList['pass']    = '通过';
$lang->testcase->resultList['fail']    = '失败';
$lang->testcase->resultList['blocked'] = '阻塞';

$lang->testcase->buttonToList = '返回';

$lang->testcase->errorEncode      = '无数据，请选择正确的编码重新上传！';
$lang->testcase->noFunction       = '不存在iconv和mb_convert_encoding转码方法，不能将数据转成想要的编码！';
$lang->testcase->noRequire        = "%s行的“%s”是必填字段，不能为空";
$lang->testcase->noLibrary        = "现在还没有公共库，请先创建！";
$lang->testcase->mustChooseResult = '必须选择评审结果';
$lang->testcase->noModule         = '<div>您现在还没有模块信息</div><div>请维护测试模块</div>';
$lang->testcase->noCase           = '暂时没有用例。';

$lang->testcase->searchStories = "键入来搜索{$lang->storyCommon}";
$lang->testcase->selectLib     = '请选择库';

$lang->testcase->action = new stdclass();
$lang->testcase->action->fromlib  = array('main' => '$date, 由 <strong>$actor</strong> 从用例库 <strong>$extra</strong>导入。');
$lang->testcase->action->reviewed = array('main' => '$date, 由 <strong>$actor</strong> 记录评审结果，结果为 <strong>$extra</strong>。', 'extra' => 'reviewResultList');

$lang->testcase->featureBar['browse']['all']         = $lang->testcase->allCases;
$lang->testcase->featureBar['browse']['wait']        = '待评审';
$lang->testcase->featureBar['browse']['needconfirm'] = $lang->testcase->needConfirm;
$lang->testcase->featureBar['browse']['group']       = '分组查看';
$lang->testcase->featureBar['browse']['suite']       = '套件';
$lang->testcase->featureBar['browse']['zerocase']    = "零用例{$lang->storyCommon}";
$lang->testcase->featureBar['groupcase']             = $lang->testcase->featureBar['browse'];
