<?php
/**
 * The product module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: zh-cn.php 5091 2013-07-10 06:06:46Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->product->index            = $lang->productCommon . '主页';
$lang->product->browse           = "{$lang->SRCommon}列表";
$lang->product->requirement      = "{$lang->URCommon}列表";
$lang->product->dynamic          = $lang->productCommon . '动态';
$lang->product->view             = "{$lang->productCommon}概况";
$lang->product->edit             = "编辑{$lang->productCommon}";
$lang->product->batchEdit        = '批量编辑';
$lang->product->create           = "添加{$lang->productCommon}";
$lang->product->delete           = "删除{$lang->productCommon}";
$lang->product->deleted          = '已删除';
$lang->product->close            = '关闭';
$lang->product->activate         = '激活';
$lang->product->select           = "请选择{$lang->productCommon}";
$lang->product->mine             = '我负责';
$lang->product->other            = '其他';
$lang->product->closed           = '已关闭';
$lang->product->closedProduct    = "已关闭的{$lang->productCommon}";
$lang->product->updateOrder      = '排序';
$lang->product->all              = "所有{$lang->productCommon}";
$lang->product->involved         = "我参与的{$lang->productCommon}";
$lang->product->manageLine       = "维护产品线";
$lang->product->newLine          = "新建产品线";
$lang->product->export           = '导出数据';
$lang->product->dashboard        = "{$lang->productCommon}仪表盘";
$lang->product->changeProgram    = "{$lang->productCommon}调整所属项目集影响范围确认";
$lang->product->changeProgramTip = "%s > 修改项目集";
$lang->product->selectProgram    = '筛选项目集';
$lang->product->addWhitelist     = '添加白名单';
$lang->product->unbindWhitelist  = '移除白名单';
$lang->product->track            = "查看需求矩阵";
$lang->product->checkedProducts  = "已选择%s项{$lang->productCommon}";
$lang->product->pageSummary      = "本页共%s个{$lang->productCommon}。";
$lang->product->lineSummary      = "本页共%s个产品线，%s个{$lang->productCommon}。";

$lang->product->indexAction    = "所有{$lang->productCommon}仪表盘";
$lang->product->closeAction    = "关闭{$lang->productCommon}";
$lang->product->activateAction = "激活{$lang->productCommon}";
$lang->product->orderAction    = "{$lang->productCommon}排序";
$lang->product->exportAction   = "导出{$lang->productCommon}";
$lang->product->link2Project   = "关联{$lang->projectCommon}";

$lang->product->basicInfo = '基本信息';
$lang->product->otherInfo = '其他信息';

$lang->product->plans       = "计划数";
$lang->product->releases    = '发布数';
$lang->product->docs        = '文档数';
$lang->product->bugs        = '相关Bug';
$lang->product->projects    = "关联{$lang->projectCommon}数";
$lang->product->executions  = "关联{$lang->execution->common}数";
$lang->product->cases       = '用例数';
$lang->product->builds      = '版本数';
$lang->product->roadmap     = "{$lang->productCommon}路线图";
$lang->product->doc         = '文档列表';
$lang->product->project     = $lang->projectCommon . '列表';
$lang->product->moreProduct = "更多{$lang->productCommon}";
$lang->product->projectInfo = "所有与此{$lang->productCommon}关联的我参与的{$lang->projectCommon}";
$lang->product->progress    = "{$lang->productCommon}完成度";

$lang->product->currentExecution      = "当前执行";
$lang->product->activeStories         = "激活{$lang->SRCommon}";
$lang->product->activeStoriesTitle    = "激活{$lang->SRCommon}";
$lang->product->changedStories        = "已变更{$lang->SRCommon}";
$lang->product->changedStoriesTitle   = "已变更{$lang->SRCommon}";
$lang->product->draftStories          = "草稿{$lang->SRCommon}";
$lang->product->draftStoriesTitle     = "草稿{$lang->SRCommon}";
$lang->product->reviewingStories      = "评审中{$lang->SRCommon}";
$lang->product->reviewingStoriesTitle = "评审中{$lang->SRCommon}";
$lang->product->closedStories         = "已关闭{$lang->SRCommon}";
$lang->product->closedStoriesTitle    = "已关闭{$lang->SRCommon}";
$lang->product->storyCompleteRate     = "{$lang->SRCommon}完成率";
$lang->product->activeRequirements    = "激活{$lang->URCommon}";
$lang->product->changedRequirements   = "已变更{$lang->URCommon}";
$lang->product->draftRequirements     = "草稿{$lang->URCommon}";
$lang->product->closedRequirements    = "已关闭{$lang->URCommon}";
$lang->product->requireCompleteRate   = "{$lang->URCommon}完成率";
$lang->product->unResolvedBugs        = '未解决Bug';
$lang->product->unResolvedBugsTitle   = '未解决Bug';
$lang->product->assignToNullBugs      = '未指派Bug';
$lang->product->assignToNullBugsTitle = '未指派Bug';
$lang->product->closedBugs            = '关闭Bug';
$lang->product->bugFixedRate          = '修复率';
$lang->product->unfoldClosed          = '展开已关闭';
$lang->product->storyDeliveryRate     = "需求交付率";
$lang->product->storyDeliveryRateTip  = "需求交付率 = 阶段为已发布或关闭原因是已完成需求数 /（研发需求总数 - 关闭原因不是已完成的需求数）* 100%";

$lang->product->confirmDelete        = " 您确定删除该{$lang->productCommon}吗？";
$lang->product->errorNoProduct       = "还没有创建{$lang->productCommon}！";
$lang->product->accessDenied         = "您无权访问该{$lang->productCommon}";
$lang->product->notExists            = "该{$lang->productCommon}不存在！";
$lang->product->programChangeTip     = "如下{$lang->projectCommon}只关联了该{$lang->productCommon}， 将直接转移至新项目集下。";
$lang->product->notChangeProgramTip  = "该{$lang->productCommon}的{$lang->SRCommon}已经关联到如下{$lang->projectCommon}，请点击{$lang->projectCommon}名称进入{$lang->SRCommon}列表取消关联后再操作";
$lang->product->confirmChangeProgram = "如下{$lang->projectCommon}既关联了该{$lang->productCommon}又关联了其他{$lang->productCommon}，请确认是否继续关联该{$lang->productCommon}，勾选后将取消与其他{$lang->productCommon}的关联关系，同时转移至新项目集下。";
$lang->product->changeProgramError   = "该{$lang->productCommon}的{$lang->SRCommon}已经关联到{$lang->projectCommon}，请取消关联后再操作";
$lang->product->changeLineError      = "产品线『%s』下已有{$lang->productCommon}，不可修改所属项目集。";
$lang->product->programEmpty         = '项目集不能为空';
$lang->product->nameIsDuplicate      = "『%s』产品线已经存在，请重新设置！";
$lang->product->nameIsDuplicated     = "产品线已经有『%s』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。";
$lang->product->reviewStory          = '您不是研发需求 "%s" 的评审人，无法评审，本次操作已被过滤。';
$lang->product->confirmDeleteLine    = "您确定删除该产品线吗？";

$lang->product->id             = '编号';
$lang->product->program        = "所属项目集";
$lang->product->name           = "{$lang->productCommon}名称";
$lang->product->code           = "{$lang->productCommon}代号";
$lang->product->shadow         = "是否影子{$lang->productCommon}";
$lang->product->line           = "产品线";
$lang->product->lineName       = "产品线名称";
$lang->product->order          = '排序';
$lang->product->bind           = "是否独立{$lang->productCommon}";
$lang->product->type           = "{$lang->productCommon}类型";
$lang->product->typeAB         = "类型";
$lang->product->status         = '状态';
$lang->product->subStatus      = '子状态';
$lang->product->desc           = "{$lang->productCommon}描述";
$lang->product->manager        = '负责人';
$lang->product->PO             = "{$lang->productCommon}负责人";
$lang->product->QD             = '测试负责人';
$lang->product->RD             = '发布负责人';
$lang->product->feedback       = '反馈负责人';
$lang->product->ticket         = '工单负责人';
$lang->product->acl            = '访问控制';
$lang->product->reviewer       = '评审人';
$lang->product->groups         = '权限组';
$lang->product->users          = '用户';
$lang->product->whitelist      = '白名单';
$lang->product->branch         = '所属%s';
$lang->product->qa             = '测试';
$lang->product->release        = '发布';
$lang->product->allRelease     = '所有发布';
$lang->product->maintain       = '维护中';
$lang->product->latestDynamic  = '最新动态';
$lang->product->plan           = '计划';
$lang->product->iteration      = '版本迭代';
$lang->product->iterationInfo  = '迭代 %s 次';
$lang->product->iterationView  = '查看详情';
$lang->product->createdBy      = '由谁创建';
$lang->product->createdDate    = '创建日期';
$lang->product->createdVersion = '创建版本';
$lang->product->mailto         = '抄送给';

$lang->product->searchStory    = '搜索';
$lang->product->assignedToMe   = '指给我';
$lang->product->openedByMe     = '我创建';
$lang->product->reviewedByMe   = '我评审';
$lang->product->reviewByMe     = '待我评审';
$lang->product->closedByMe     = '我关闭';
$lang->product->draftStory     = '草稿';
$lang->product->activeStory    = '激活';
$lang->product->changingStory  = '变更中';
$lang->product->reviewingStory = '评审中';
$lang->product->willClose      = '待关闭';
$lang->product->closedStory    = '已关闭';
$lang->product->unclosed       = '未关闭';
$lang->product->unplan         = "未计划";
$lang->product->viewByUser     = '按用户查看';
$lang->product->assignedByMe   = '我指派';

/* Product Kanban. */
$lang->product->myProduct             = '我负责的' . $lang->productCommon;
$lang->product->otherProduct          = '其他' . $lang->productCommon;
$lang->product->unclosedProduct       = '未关闭的' . $lang->productCommon;
$lang->product->unexpiredPlan         = '未过期的计划';
$lang->product->doing                 = '进行中';
$lang->product->doingProject          = '进行中的' . $lang->projectCommon;
$lang->product->doingExecution        = '进行中的执行';
$lang->product->doingClassicExecution = '进行中的' . $lang->executionCommon;
$lang->product->normalRelease         = '正常的发布';
$lang->product->emptyProgram          = "无项目集归属{$lang->productCommon}";

$lang->product->allStory             = '所有';
$lang->product->allProduct           = '全部' . $lang->productCommon;
$lang->product->allProductsOfProject = '全部关联' . $lang->productCommon;

$lang->product->typeList['']         = '';
$lang->product->typeList['normal']   = '正常';
$lang->product->typeList['branch']   = '多分支';
$lang->product->typeList['platform'] = '多平台';

$lang->product->typeTips = array();
$lang->product->typeTips['branch']   = '(适用于客户定制场景)';
$lang->product->typeTips['platform'] = '(适用于跨平台应用开发，比如IOS、安卓、PC端等)';

$lang->product->branchName['normal']   = '';
$lang->product->branchName['branch']   = '分支';
$lang->product->branchName['platform'] = '平台';

$lang->product->statusList['normal'] = '正常';
$lang->product->statusList['closed'] = '结束';

global $config;
if($config->systemMode == 'ALM')
{
    $lang->product->aclList['private'] = "私有({$lang->productCommon}相关负责人、所属项目集的负责人及干系人、相关联{$lang->projectCommon}的团队成员和干系人可访问)";
}
else
{
    $lang->product->aclList['private'] = "私有({$lang->productCommon}相关负责人、相关联{$lang->projectCommon}的团队成员和关系人可访问)";
}
$lang->product->aclList['open'] = "公开(有{$lang->productCommon}视图权限，即可访问)";

$lang->product->abbr = new stdclass();
$lang->product->abbr->aclList['private'] = '私有';
$lang->product->abbr->aclList['open']    = '公开';

$lang->product->aclTips['open']    = "有{$lang->productCommon}视图权限，即可访问";
$lang->product->aclTips['private'] = "{$lang->productCommon}相关负责人、所属项目集的干系人、相关联{$lang->projectCommon}的团队成员和干系人可访问";

$lang->product->storySummary       = "本页共 <strong>%s</strong> 个%s，预计 <strong>%s</strong> 个{$lang->hourCommon}，用例覆盖率 <strong>%s</strong>。";
$lang->product->checkedSRSummary   = "选中 <strong>%total%</strong> 个%storyCommon%，预计 <strong>%estimate%</strong> 个{$lang->hourCommon}，用例覆盖率 <strong>%rate%</strong>。";
$lang->product->requirementSummary = "本页共 <strong>%s</strong> 个%s，预计 <strong>%s</strong> 个{$lang->hourCommon}。";
$lang->product->checkedURSummary   = "选中 <strong>%total%</strong> 个%storyCommon%，预计 <strong>%estimate%</strong> 个{$lang->hourCommon}。";
$lang->product->noModule           = "<div>您现在还没有模块信息</div><div>请维护模块</div>";
$lang->product->noProduct          = "暂时没有{$lang->productCommon}。";
$lang->product->noMatched          = '找不到包含"%s"的' . $lang->productCommon;

$lang->product->featureBar['browse']['allstory']     = '全部';
$lang->product->featureBar['browse']['unclosed']     = $lang->product->unclosed;
$lang->product->featureBar['browse']['assignedtome'] = $lang->product->assignedToMe;
$lang->product->featureBar['browse']['openedbyme']   = $lang->product->openedByMe;
$lang->product->featureBar['browse']['reviewbyme']   = $lang->product->reviewByMe;
$lang->product->featureBar['browse']['draftstory']   = $lang->product->draftStory;
$lang->product->featureBar['browse']['more']         = $lang->more;

$lang->product->featureBar['all']['all']      = '全部' . $lang->productCommon;
$lang->product->featureBar['all']['noclosed'] = $lang->product->unclosed;
$lang->product->featureBar['all']['closed']   = $lang->product->statusList['closed'];

$lang->product->featureBar['project']['all']       = '全部';
$lang->product->featureBar['project']['undone']    = '未完成';
$lang->product->featureBar['project']['wait']      = '未开始';
$lang->product->featureBar['project']['doing']     = '进行中';
$lang->product->featureBar['project']['suspended'] = '已挂起';
$lang->product->featureBar['project']['closed']    = '已关闭';


$lang->product->moreSelects['browse']['more']['reviewedbyme']   = $lang->product->reviewedByMe;
$lang->product->moreSelects['browse']['more']['assignedbyme']   = $lang->product->assignedByMe;
$lang->product->moreSelects['browse']['more']['closedbyme']     = $lang->product->closedByMe;
$lang->product->moreSelects['browse']['more']['activestory']    = $lang->product->activeStory;
$lang->product->moreSelects['browse']['more']['changingstory']  = $lang->product->changingStory;
$lang->product->moreSelects['browse']['more']['reviewingstory'] = $lang->product->reviewingStory;
$lang->product->moreSelects['browse']['more']['willclose']      = $lang->product->willClose;
$lang->product->moreSelects['browse']['more']['closedstory']    = $lang->product->closedStory;

$lang->product->featureBar['dynamic']['all']       = '全部';
$lang->product->featureBar['dynamic']['today']     = '今天';
$lang->product->featureBar['dynamic']['yesterday'] = '昨天';
$lang->product->featureBar['dynamic']['thisWeek']  = '本周';
$lang->product->featureBar['dynamic']['lastWeek']  = '上周';
$lang->product->featureBar['dynamic']['thisMonth'] = '本月';
$lang->product->featureBar['dynamic']['lastMonth'] = '上月';

$lang->product->action = new stdclass();
$lang->product->action->activate = array('main' => '$date, 由 <strong>$actor</strong> 激活。');

$lang->product->belongingLine     = '所属产品线';
$lang->product->testCaseCoverage  = '用例覆盖率';
$lang->product->activatedBug      = 'Bug激活';
$lang->product->completeRate      = '完成率';
$lang->product->editLine          = '编辑产品线';
$lang->product->totalBugs         = 'Bug总数';
$lang->product->totalStories      = '需求总数';
$lang->product->latestReleaseDate = '最新发布时间';
$lang->product->latestRelease     = '最新发布';
