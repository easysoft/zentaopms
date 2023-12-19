<?php
/**
 * The testcase module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testcase
 * @version     $Id: zh-cn.php 4764 2013-05-05 04:07:04Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->testcase->id               = '用例编号';
$lang->testcase->product          = "所属{$lang->productCommon}";
$lang->testcase->project          = '所属' . $lang->projectCommon;
$lang->testcase->execution        = '所属' . $lang->executionCommon;
$lang->testcase->linkStory        = '关联需求';
$lang->testcase->module           = '所属模块';
$lang->testcase->auto             = '自动化测试用例';
$lang->testcase->frame            = '自动化测试框架';
$lang->testcase->howRun           = '测试方式';
$lang->testcase->frequency        = '使用频率';
$lang->testcase->path             = '路径';
$lang->testcase->lib              = "所属库";
$lang->testcase->branch           = "平台/分支";
$lang->testcase->moduleAB         = '模块';
$lang->testcase->story            = "相关{$lang->SRCommon}";
$lang->testcase->storyVersion     = "{$lang->SRCommon}版本";
$lang->testcase->color            = '标题颜色';
$lang->testcase->order            = '排序';
$lang->testcase->title            = '用例标题';
$lang->testcase->precondition     = '前置条件';
$lang->testcase->pri              = '优先级';
$lang->testcase->type             = '用例类型';
$lang->testcase->status           = '用例状态';
$lang->testcase->statusAB         = '状态';
$lang->testcase->subStatus        = '子状态';
$lang->testcase->steps            = '用例步骤';
$lang->testcase->openedBy         = '由谁创建';
$lang->testcase->openedByAB       = '创建者';
$lang->testcase->openedDate       = '创建日期';
$lang->testcase->lastEditedBy     = '最后修改者';
$lang->testcase->result           = '测试结果';
$lang->testcase->real             = '实际情况';
$lang->testcase->keywords         = '关键词';
$lang->testcase->files            = '附件';
$lang->testcase->linkCase         = '相关用例';
$lang->testcase->linkCases        = '关联相关用例';
$lang->testcase->unlinkCase       = '移除相关用例';
$lang->testcase->linkBug          = '相关Bug';
$lang->testcase->linkBugs         = '关联相关Bug';
$lang->testcase->unlinkBug        = '移除相关Bug';
$lang->testcase->stage            = '适用阶段';
$lang->testcase->scriptedBy       = '脚本由谁创建';
$lang->testcase->scriptedDate     = '脚本创建日期';
$lang->testcase->scriptStatus     = '脚本状态';
$lang->testcase->scriptLocation   = '脚本地址';
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
$lang->testcase->parent           = '上级步骤';
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
$lang->testcase->fromCaseID       = '用例来源ID';
$lang->testcase->fromCaseVersion  = '用例来源版本';
$lang->testcase->mailto           = '抄送给';
$lang->testcase->deleted          = '是否删除';
$lang->testcase->browseUnits      = '单元测试';
$lang->testcase->suite            = '套件';
$lang->testcase->executionStatus  = '执行状态';
$lang->testcase->caseType         = '用例类型';
$lang->testcase->allType          = '所有类型';
$lang->testcase->showAutoCase     = '自动化';
$lang->testcase->automation       = '自动化设置';
$lang->testcase->autoCase         = '自动化';

$lang->case = $lang->testcase;  // 用于DAO检查时使用。因为case是系统关键字，所以无法定义该模块为case，只能使用testcase，但表还是使用的case。

$lang->testcase->stepID      = '编号';
$lang->testcase->stepDesc    = '步骤';
$lang->testcase->stepExpect  = '预期';
$lang->testcase->stepVersion = '版本';

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
$lang->testcase->groupByStories          = "{$lang->common->story}分组";
$lang->testcase->batchDelete             = "批量删除 ";
$lang->testcase->batchConfirmStoryChange = "批量确认变更";
$lang->testcase->batchCaseTypeChange     = "批量修改类型";
$lang->testcase->browse                  = "用例列表";
$lang->testcase->groupCase               = "分组浏览用例";
$lang->testcase->zeroCase                = "零用例{$lang->common->story}";
$lang->testcase->import                  = "导入";
$lang->testcase->importAction            = "导入用例";
$lang->testcase->importCaseAction        = "导入用例";
$lang->testcase->fileImport              = "导入CSV";
$lang->testcase->importFromLib           = "从用例库中导入";
$lang->testcase->showImport              = "显示导入内容";
$lang->testcase->exportTemplate          = "导出模板";
$lang->testcase->export                  = "导出数据";
$lang->testcase->exportAction            = "导出用例";
$lang->testcase->reportChart             = '报表统计';
$lang->testcase->reportAction            = '用例报表统计';
$lang->testcase->confirmChange           = '确认用例变动';
$lang->testcase->confirmStoryChange      = "确认{$lang->SRCommon}变动";
$lang->testcase->copy                    = '复制用例';
$lang->testcase->group                   = '分组';
$lang->testcase->groupName               = '分组名称';
$lang->testcase->step                    = '步骤';
$lang->testcase->stepChild               = '子步骤';
$lang->testcase->viewAll                 = '查看所有';
$lang->testcase->importToLib             = '导入用例库';
$lang->testcase->showScript              = '查看自动化脚本';
$lang->testcase->autoScript              = '自动化脚本';

$lang->testcase->new = '新增';

$lang->testcase->num = '用例记录数：';

$lang->testcase->deleteStep   = '删除';
$lang->testcase->insertBefore = '之前添加';
$lang->testcase->insertAfter  = '之后添加';

$lang->testcase->assignToMe   = '指派给我的用例';
$lang->testcase->openedByMe   = '我建的用例';
$lang->testcase->allCases     = '所有';
$lang->testcase->allTestcases = '所有用例';
$lang->testcase->needConfirm  = "{$lang->common->story}变动";
$lang->testcase->bySearch     = '搜索';
$lang->testcase->unexecuted   = '未执行';

$lang->testcase->lblStory       = "相关{$lang->SRCommon}";
$lang->testcase->lblLastEdited  = '最后编辑';
$lang->testcase->lblTypeValue   = '类型可选值列表';
$lang->testcase->lblStageValue  = '阶段可选值列表';
$lang->testcase->lblStatusValue = '状态可选值列表';

$lang->testcase->legendBasicInfo   = '基本信息';
$lang->testcase->legendAttatch     = '附件';
$lang->testcase->legendLinkBugs    = '相关Bug';
$lang->testcase->legendOpenAndEdit = '创建编辑';
$lang->testcase->legendComment     = '备注';

$lang->testcase->summary               = "本页共 <strong>%s</strong> 个用例，已执行<strong>%s</strong>个。";
$lang->testcase->confirmDelete         = '您确认要删除该测试用例吗？';
$lang->testcase->confirmBatchDelete    = '您确认要批量删除这些测试用例吗？';
$lang->testcase->ditto                 = '同上';
$lang->testcase->dittoNotice           = "该用例与上一用例不属于同一{$lang->productCommon}！";
$lang->testcase->confirmUnlinkTesttask = '用例[%s]已关联在之前所属平台/分支的测试单中，调整平台/分支后，将从之前所属平台/分支的测试单中移除，请确认是否继续修改。';

$lang->testcase->reviewList[0] = '否';
$lang->testcase->reviewList[1] = '是';

$lang->testcase->autoList['']     = '';
$lang->testcase->autoList['auto'] = '是';
$lang->testcase->autoList['no']   = '否';

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

$lang->testcase->whichLine        = '第%s行';
$lang->testcase->stepsEmpty       = '步骤%s不能为空';
$lang->testcase->errorEncode      = '无数据，请选择正确的编码重新上传！';
$lang->testcase->noFunction       = '不存在iconv和mb_convert_encoding转码方法，不能将数据转成想要的编码！';
$lang->testcase->noRequire        = "%s行的“%s”是必填字段，不能为空";
$lang->testcase->noRequireTip     = "“%s”是必填字段，不能为空";
$lang->testcase->noLibrary        = "现在还没有用例库，请先创建！";
$lang->testcase->mustChooseResult = '必须选择评审结果';
$lang->testcase->noModule         = '<div>您现在还没有模块信息</div><div>请维护测试模块</div>';
$lang->testcase->noCase           = '暂时没有用例。';
$lang->testcase->importedCases    = 'ID为 %s 的用例在相同模块已经导入，已忽略。';
$lang->testcase->importedFromLib  = '导入成功%s项：%s。';

$lang->testcase->searchStories = "键入来搜索{$lang->SRCommon}";
$lang->testcase->selectLib     = '请选择库';
$lang->testcase->selectLibAB   = '选择用例库';

$lang->testcase->action = new stdclass();
$lang->testcase->action->fromlib               = array('main' => '$date, 由 <strong>$actor</strong> 从用例库 <strong>$extra</strong>导入。');
$lang->testcase->action->reviewed              = array('main' => '$date, 由 <strong>$actor</strong> 记录评审结果，结果为 <strong>$extra</strong>。', 'extra' => 'reviewResultList');
$lang->testcase->action->linked2project        = array('main' => '$date, 由 <strong>$actor</strong> 关联到' . $lang->projectCommon . ' <strong>$extra</strong>。');
$lang->testcase->action->unlinkedfromproject   = array('main' => '$date, 由 <strong>$actor</strong> 从' . $lang->projectCommon . ' <strong>$extra</strong> 移除。');
$lang->testcase->action->linked2execution      = array('main' => '$date, 由 <strong>$actor</strong> 关联到' . $lang->executionCommon . ' <strong>$extra</strong>。');
$lang->testcase->action->unlinkedfromexecution = array('main' => '$date, 由 <strong>$actor</strong> 从' . $lang->executionCommon . ' <strong>$extra</strong> 移除。');

$lang->testcase->featureBar['browse']['casetype']    = $lang->testcase->caseType;
$lang->testcase->featureBar['browse']['all']         = $lang->testcase->allCases;
$lang->testcase->featureBar['browse']['wait']        = '待评审';
$lang->testcase->featureBar['browse']['needconfirm'] = $lang->testcase->needConfirm;
$lang->testcase->featureBar['browse']['group']       = '分组查看';
$lang->testcase->featureBar['browse']['zerocase']    = "零用例{$lang->SRCommon}";
$lang->testcase->featureBar['browse']['suite']       = '套件';
$lang->testcase->featureBar['browse']['autocase']    = $lang->testcase->showAutoCase;

$lang->testcase->importXmind     = "导入XMIND";
$lang->testcase->exportXmind     = "导出XMIND";
$lang->testcase->getXmindImport  = "获取导图";
$lang->testcase->showXMindImport = "显示导图";
$lang->testcase->saveXmindImport = "保存导图";

$lang->testcase->xmindImport           = "导入XMIND";
$lang->testcase->xmindExport           = "导出XMIND";
$lang->testcase->xmindImportEdit       = "XMIND 编辑";
$lang->testcase->errorFileNotEmpty     = '上传文件不能为空';
$lang->testcase->errorXmindUpload      = '上传失败';
$lang->testcase->errorFileFormat       = '文件格式错误';
$lang->testcase->moduleSelector        = '模块选择';
$lang->testcase->errorImportBadProduct = '产品不存在，导入错误';
$lang->testcase->errorSceneNotExist    = '场景[%d]不存在';

$lang->testcase->save  = '保存';
$lang->testcase->close = '关闭';

$lang->testcase->xmindImportSetting = '导入特征字符设置';
$lang->testcase->xmindExportSetting = '导出特征字符设置';

$lang->testcase->settingModule = '模&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;块';
$lang->testcase->settingScene  = '场&nbsp;&nbsp;&nbsp;&nbsp;景';
$lang->testcase->settingCase   = '测试用例';
$lang->testcase->settingPri    = '优先级&nbsp;';
$lang->testcase->settingGroup  = '步骤分组';

$lang->testcase->caseNotExist =  '未识别导入数据中的用例，导入失败';
$lang->testcase->saveFail     =  '保存失败';
$lang->testcase->set2Scene    =  '设为场景';
$lang->testcase->set2Testcase =  '设为测试用例';
$lang->testcase->clearSetting =  '清除设置';
$lang->testcase->setModule    =  '设置场景模块';
$lang->testcase->pickModule   =  '请选择模块';
$lang->testcase->clearBefore  =  '清除前面场景';
$lang->testcase->clearAfter   =  '清除后面场景';
$lang->testcase->clearCurrent =  '清除当前场景';
$lang->testcase->removeGroup  =  '移除分组';
$lang->testcase->set2Group    =  '设为分组';

$lang->testcase->exportTemplet = '导出模板';

$lang->testcase->createScene      = "建场景";
$lang->testcase->changeScene      = "拖动改变所属场景";
$lang->testcase->batchChangeScene = "批量改变所属场景";
$lang->testcase->updateOrder      = "拖动排序";
$lang->testcase->differentProduct = "所属产品不同";

$lang->testcase->newScene           = "建场景";
$lang->testcase->sceneTitle         = '场景标题';
$lang->testcase->parentScene        = "父场景";
$lang->testcase->scene              = "所属场景";
$lang->testcase->summary            = '本页共 %d 个顶级场景，%d 个独立用例。';
$lang->testcase->summaryScene       = '本页共 %d 个顶级场景。';
$lang->testcase->deleteScene        = '删除场景';
$lang->testcase->editScene          = '编辑场景';
$lang->testcase->hasChildren        = '该场景有子场景或测试用例存在，要全部删除吗？';
$lang->testcase->confirmDeleteScene = '您确定要删除场景：“%s”吗？';
$lang->testcase->sceneb             = "场景";
$lang->testcase->onlyScene          = '仅场景';
$lang->testcase->iScene             = '所属场景';
$lang->testcase->generalTitle       = '标题';
$lang->testcase->noScene            = '暂时没有场景';
$lang->testcase->rowIndex           = '行索引';
$lang->testcase->nestTotal          = '嵌套总数';
$lang->testcase->normal             = '正常';

/* Translation for drag modal message box. */
$lang->testcase->dragModalTitle       = '拖拽操作选择';
$lang->testcase->dragModalMessage     = '<p>当前操作有两种可能的情况: </p><p>1) 调整排序<br/> 2) 更改所属场景，所属模块同时变更为目标场景的模块</p><p>请选择您要执行的操作</p>';
$lang->testcase->dragModalChangeScene = '更改所属场景';
$lang->testcase->dragModalChangeOrder = '调整排序';

$lang->testcase->confirmBatchDeleteSceneCase = '您确认要批量删除这些场景或测试用例吗？';

$lang->scene = new stdclass();
$lang->scene->title = '场景标题';
