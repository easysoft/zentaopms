<?php
/**
 * The product module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: zh-cn.php 5091 2013-07-10 06:06:46Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->product->index           = $lang->productCommon . '主页';
$lang->product->browse          = "{$lang->SRCommon}列表";
$lang->product->dynamic         = $lang->productCommon . '动态';
$lang->product->view            = "{$lang->productCommon}概况";
$lang->product->edit            = "编辑{$lang->productCommon}";
$lang->product->batchEdit       = '批量编辑';
$lang->product->create          = "添加{$lang->productCommon}";
$lang->product->delete          = "删除{$lang->productCommon}";
$lang->product->deleted         = '已删除';
$lang->product->close           = '关闭';
$lang->product->select          = "请选择{$lang->productCommon}";
$lang->product->mine            = '我负责：';
$lang->product->other           = '其他：';
$lang->product->closed          = '已关闭';
$lang->product->updateOrder     = '排序';
$lang->product->all             = "所有{$lang->productCommon}";
$lang->product->manageLine      = "维护{$lang->productCommon}线";
$lang->product->newLine         = "新建{$lang->productCommon}线";
$lang->product->export          = '导出数据';
$lang->product->dashboard       = "{$lang->productCommon}仪表盘";
$lang->product->changeProgram   = "{$lang->productCommon}调整所属项目集影响范围确认";
$lang->product->addWhitelist    = '添加白名单';
$lang->product->unbindWhitelist = '移除白名单';

$lang->product->indexAction   = "所有{$lang->productCommon}仪表盘";
$lang->product->closeAction   = "关闭{$lang->productCommon}";
$lang->product->orderAction   = "{$lang->productCommon}排序";
$lang->product->exportAction  = "导出{$lang->productCommon}";

$lang->product->basicInfo = '基本信息';
$lang->product->otherInfo = '其他信息';

$lang->product->plans       = "计划数";
$lang->product->releases    = '发布数';
$lang->product->docs        = '文档数';
$lang->product->bugs        = '相关Bug';
$lang->product->projects    = "关联项目数";
$lang->product->executions  = "关联{$lang->execution->common}数";
$lang->product->cases       = '用例数';
$lang->product->builds      = '版本数';
$lang->product->roadmap     = "{$lang->productCommon}路线图";
$lang->product->doc         = '文档列表';
$lang->product->project     = $lang->executionCommon . '列表';
$lang->product->build       = '版本列表';
$lang->product->moreProduct = "更多产品";
$lang->product->projectInfo = "所有与此产品关联的项目";

$lang->product->currentExecution      = "当前执行";
$lang->product->activeStories         = "激活{$lang->SRCommon}";
$lang->product->activeStoriesTitle    = "激活{$lang->SRCommon}";
$lang->product->changedStories        = "已变更{$lang->SRCommon}";
$lang->product->changedStoriesTitle   = "已变更{$lang->SRCommon}";
$lang->product->draftStories          = "草稿{$lang->SRCommon}";
$lang->product->draftStoriesTitle     = "草稿{$lang->SRCommon}";
$lang->product->closedStories         = "已关闭{$lang->SRCommon}";
$lang->product->closedStoriesTitle    = "已关闭{$lang->SRCommon}";
$lang->product->unResolvedBugs        = '未解决Bug';
$lang->product->unResolvedBugsTitle   = '未解决Bug';
$lang->product->assignToNullBugs      = '未指派Bug';
$lang->product->assignToNullBugsTitle = '未指派Bug';

$lang->product->confirmDelete        = " 您确定删除该{$lang->productCommon}吗？";
$lang->product->errorNoProduct       = "还没有创建{$lang->productCommon}！";
$lang->product->accessDenied         = "您无权访问该{$lang->productCommon}";
$lang->product->programChangeTip     = "如下项目只关联了该{$lang->productCommon}， 将直接转移至新项目集下。";
$lang->product->notChangeProgramTip  = "该{$lang->productCommon}的{$lang->SRCommon}已经关联到如下项目，请取消关联后再操作";
$lang->product->confirmChangeProgram = "如下项目既关联了该{$lang->productCommon}又关联了其他{$lang->productCommon}，请确认是否继续关联该{$lang->productCommon}，勾选后将取消与其他{$lang->productCommon}的关联关系，同时转移至新项目集下。";
$lang->product->changeProgramError   = "该{$lang->productCommon}的{$lang->SRCommon}已经关联到项目，请取消关联后再操作";

$lang->product->id             = '编号';
$lang->product->program        = "所属项目集";
$lang->product->name           = "{$lang->productCommon}名称";
$lang->product->code           = "{$lang->productCommon}代号";
$lang->product->line           = "{$lang->productCommon}线";
$lang->product->lineName       = "{$lang->productCommon}线名称";
$lang->product->order          = '排序';
$lang->product->type           = "{$lang->productCommon}类型";
$lang->product->typeAB         = "类型";
$lang->product->status         = '状态';
$lang->product->subStatus      = '子状态';
$lang->product->desc           = "{$lang->productCommon}描述";
$lang->product->manager        = '负责人';
$lang->product->PO             = "{$lang->productCommon}负责人";
$lang->product->QD             = '测试负责人';
$lang->product->RD             = '发布负责人';
$lang->product->acl            = '访问控制';
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

$lang->product->searchStory  = '搜索';
$lang->product->assignedToMe = '指给我';
$lang->product->openedByMe   = '我创建';
$lang->product->reviewedByMe = '我评审';
$lang->product->closedByMe   = '我关闭';
$lang->product->draftStory   = '草稿';
$lang->product->activeStory  = '激活';
$lang->product->changedStory = '已变更';
$lang->product->willClose    = '待关闭';
$lang->product->closedStory  = '已关闭';
$lang->product->unclosed     = '未关闭';
$lang->product->unplan       = "未计划";
$lang->product->viewByUser   = '按用户查看';

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

$lang->product->statusList['']       = '';
$lang->product->statusList['normal'] = '正常';
$lang->product->statusList['closed'] = '结束';

global $config;
if($config->systemMode == 'new')
{
    $lang->product->aclList['private'] = "私有({$lang->productCommon}相关负责人、所属项目集的干系人、相关联项目的团队成员和干系人可访问)";
}
else
{
    $lang->product->aclList['private'] = "私有({$lang->productCommon}相关负责人、相关联{$lang->executionCommon}的团队成员可访问)";
}
$lang->product->aclList['open']    = "公开(有{$lang->productCommon}视图权限，即可访问)";
//$lang->product->aclList['custom']  = '自定义白名单(团队成员和白名单的成员可以访问)';

$lang->product->acls['private'] = '私有';
$lang->product->acls['open']    = "公开";

$lang->product->aclTips['open']    = "有{$lang->productCommon}视图权限，即可访问";
$lang->product->aclTips['private'] = "{$lang->productCommon}相关负责人、所属项目集的干系人、相关联项目的团队成员和干系人可访问";

$lang->product->storySummary   = "本页共 <strong>%s</strong> 个%s，预计 <strong>%s</strong> 个{$lang->hourCommon}，用例覆盖率 <strong>%s</strong>。";
$lang->product->checkedSummary = "选中 <strong>%total%</strong> 个%storyCommon%，预计 <strong>%estimate%</strong> 个{$lang->hourCommon}，用例覆盖率 <strong>%rate%</strong>。";
$lang->product->noModule       = "<div>您现在还没有模块信息</div><div>请维护{$lang->productCommon}模块</div>";
$lang->product->noProduct      = "暂时没有{$lang->productCommon}。";
$lang->product->noMatched      = '找不到包含"%s"的' . $lang->productCommon;

$lang->product->featureBar['browse']['allstory']     = $lang->product->allStory;
$lang->product->featureBar['browse']['unclosed']     = $lang->product->unclosed;
$lang->product->featureBar['browse']['assignedtome'] = $lang->product->assignedToMe;
$lang->product->featureBar['browse']['openedbyme']   = $lang->product->openedByMe;
$lang->product->featureBar['browse']['reviewedbyme'] = $lang->product->reviewedByMe;
$lang->product->featureBar['browse']['draftstory']   = $lang->product->draftStory;
$lang->product->featureBar['browse']['more']         = $lang->more;

$lang->product->featureBar['all']['all']      = '所有' . $lang->productCommon;
$lang->product->featureBar['all']['noclosed'] = $lang->product->unclosed;
$lang->product->featureBar['all']['closed']   = $lang->product->statusList['closed'];

$lang->product->moreSelects['closedbyme']   = $lang->product->closedByMe;
$lang->product->moreSelects['activestory']  = $lang->product->activeStory;
$lang->product->moreSelects['changedstory'] = $lang->product->changedStory;
$lang->product->moreSelects['willclose']    = $lang->product->willClose;
$lang->product->moreSelects['closedstory']  = $lang->product->closedStory;
