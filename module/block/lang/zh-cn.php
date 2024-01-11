<?php
/**
 * The zh-cn file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        https://www.zentao.net
 */
global $config;
$lang->block->id         = '编号';
$lang->block->params     = '参数';
$lang->block->name       = '区块名称';
$lang->block->style      = '外观';
$lang->block->grid       = '位置';
$lang->block->color      = '颜色';
$lang->block->reset      = '恢复默认';
$lang->block->story      = '需求';
$lang->block->investment = '投入';
$lang->block->estimate   = '预计工时';
$lang->block->last       = '近期';
$lang->block->width      = '长度';

$lang->block->account = '所属用户';
$lang->block->title   = '区块名称';
$lang->block->module  = '所属模块';
$lang->block->code    = '区块';
$lang->block->order   = '排序';
$lang->block->height  = '高度';
$lang->block->role    = '角色';

$lang->block->lblModule       = '模块';
$lang->block->lblBlock        = '区块';
$lang->block->lblNum          = '条数';
$lang->block->lblHtml         = 'HTML内容';
$lang->block->html            = 'HTML';
$lang->block->dynamic         = '最新动态';
$lang->block->zentaoDynamic   = '禅道动态';
$lang->block->assignToMe      = '待处理';
$lang->block->wait            = '未开始';
$lang->block->doing           = '进行中';
$lang->block->done            = '已完成';
$lang->block->lblFlowchart    = '流程图';
$lang->block->lblTesttask     = '查看测试详情';
$lang->block->contribute      = '我的贡献';
$lang->block->finish          = '已完成';
$lang->block->guide           = '使用帮助';
$lang->block->teamAchievement = '团队成就';

$lang->block->leftToday           = '今天剩余工作总计';
$lang->block->myTask              = '我的任务';
$lang->block->myStory             = "我的{$lang->SRCommon}";
$lang->block->myBug               = '我的BUG';
$lang->block->myExecution         = '未关闭的' . $lang->executionCommon;
$lang->block->myProduct           = '未关闭的' . $lang->productCommon;
$lang->block->delay               = '延期';
$lang->block->delayed             = '已延期';
$lang->block->noData              = '当前统计类型下暂无数据';
$lang->block->emptyTip            = '暂无数据';
$lang->block->createdTodos        = '创建的待办数';
$lang->block->createdRequirements = '创建的' . $lang->URCommon . '数';
$lang->block->createdStories      = '创建的' . $lang->SRCommon . '数';
$lang->block->finishedTasks       = '完成的任务数';
$lang->block->createdBugs         = '提交的Bug数';
$lang->block->resolvedBugs        = '解决的Bug数';
$lang->block->createdCases        = '创建的用例数';
$lang->block->createdRisks        = '创建的风险数';
$lang->block->resolvedRisks       = '解决的风险数';
$lang->block->createdIssues       = '创建的问题数';
$lang->block->resolvedIssues      = '解决的问题数';
$lang->block->createdDocs         = '创建的文档数';
$lang->block->allExecutions       = '所有' . $lang->executionCommon;
$lang->block->doingExecution      = '进行中的' . $lang->executionCommon;
$lang->block->finishExecution     = '累积' . $lang->executionCommon;
$lang->block->estimatedHours      = '预计';
$lang->block->consumedHours       = '已消耗';
$lang->block->time                = '第';
$lang->block->week                = '周';
$lang->block->month               = '月';
$lang->block->selectProduct       = "选择{$lang->productCommon}";
$lang->block->blockTitle          = '%1$s的%2$s';
$lang->block->remain              = '剩余工时';
$lang->block->allStories          = '总需求';

$lang->block->createBlock        = '添加区块';
$lang->block->editBlock          = '编辑区块';
$lang->block->ordersSaved        = '排序已保存';
$lang->block->confirmRemoveBlock = '确定隐藏区块吗？';
$lang->block->noticeNewBlock     = '10.0版本以后各个视图主页提供了全新的视图，您要启用新的视图布局吗？';
$lang->block->confirmReset       = '是否恢复默认布局？';
$lang->block->closeForever       = '永久关闭';
$lang->block->confirmClose       = '确定永久关闭该区块吗？关闭后所有人都将无法使用该区块，可以在后台自定义中打开。';
$lang->block->remove             = '移除';
$lang->block->refresh            = '刷新';
$lang->block->nbsp               = '';
$lang->block->hidden             = '隐藏';
$lang->block->dynamicInfo        = "<span class='timeline-tag'>%s</span> <span class='timeline-text'>%s<span class='label-action'>%s</span>%s<a href='%s' title='%s'>%s</a></span>";
$lang->block->noLinkDynamic      = "<span class='timeline-tag'>%s</span> <span class='timeline-text' title='%s'>%s<span class='label-action'>%s</span>%s<span class='label-name'>%s</span></span>";
$lang->block->cannotPlaceInLeft  = '此区块无法放置在左侧。';
$lang->block->cannotPlaceInRight = '此区块无法放置在右侧。';
$lang->block->tutorial           = '进入新手教程';

$lang->block->productName   = $lang->productCommon . '名称';
$lang->block->totalStory    = '总' . $lang->SRCommon;
$lang->block->totalBug      = '总Bug';
$lang->block->totalRelease  = '发布次数';
$lang->block->totalTask     = '总' . $lang->task->common;
$lang->block->projectMember = '团队成员';
$lang->block->totalMember   = '共 %s 人';

$lang->block->totalInvestment = '已投入';
$lang->block->totalPeople     = '总人数';
$lang->block->spent           = '已花费';
$lang->block->budget          = '预算';
$lang->block->left            = '剩余';

$lang->block->summary = new stdclass();
$lang->block->summary->welcome = '禅道已陪伴您%s，<strong>昨日</strong>完成了<a href="' .  helper::createLink('my', 'contribute', 'mode=task&type=finishedBy') . '" class="text-success">%s</a>个任务、解决了<a href="' . helper::createLink('my', 'contribute', 'mode=bug&type=resolvedBy') . '" class="text-success">%s</a>个Bug，今日期待优秀的您来处理';

$lang->block->dashboard['default'] = '仪表盘';
$lang->block->dashboard['my']      = '地盘';

$lang->block->titleList['flowchart']      = '流程图';
$lang->block->titleList['guide']          = '使用帮助';
$lang->block->titleList['statistic']      = "{$lang->projectCommon}统计";
$lang->block->titleList['recentproject']  = "我近期参与的{$lang->projectCommon}";
$lang->block->titleList['assigntome']     = '待处理';
$lang->block->titleList['project']        = "{$lang->projectCommon}列表";
$lang->block->titleList['dynamic']        = '最新动态';
$lang->block->titleList['list']           = '我的待办';
$lang->block->titleList['scrumoverview']  = "{$lang->projectCommon}总览";
$lang->block->titleList['scrumtest']      = '测试单列表';
$lang->block->titleList['scrumlist']      = '迭代列表';
$lang->block->titleList['sprint']         = '迭代总览';
$lang->block->titleList['projectdynamic'] = '最新动态';
$lang->block->titleList['bug']            = '指派给我的Bug';
$lang->block->titleList['case']           = '指派给我的用例';
$lang->block->titleList['testtask']       = '测试单列表';
$lang->block->titleList['statistic']      = "{$lang->projectCommon}统计";

$lang->block->default['scrumproject'][] = array('title' => "{$lang->projectCommon}总览",   'module' => 'scrumproject', 'code' => 'scrumoverview',  'width' => '2');
$lang->block->default['scrumproject'][] = array('title' => "{$lang->executionCommon}列表", 'module' => 'scrumproject', 'code' => 'scrumlist',      'width' => '2', 'params' => array('type' => 'undone', 'count' => '20', 'orderBy' => 'id_desc'));
$lang->block->default['scrumproject'][] = array('title' => '待测测试单列表',               'module' => 'scrumproject', 'code' => 'scrumtest',      'width' => '2', 'params' => array('type' => 'wait', 'count' => '15', 'orderBy' => 'id_desc'));
$lang->block->default['scrumproject'][] = array('title' => "{$lang->executionCommon}总览", 'module' => 'scrumproject', 'code' => 'sprint',         'width' => '1');
$lang->block->default['scrumproject'][] = array('title' => '最新动态',                     'module' => 'scrumproject', 'code' => 'projectdynamic', 'width' => '1');

$lang->block->default['kanbanproject']    = $lang->block->default['scrumproject'];
$lang->block->default['agileplusproject'] = $lang->block->default['scrumproject'];

$lang->block->default['waterfallproject'][] = array('title' => "{$lang->projectCommon}计划", 'module' => 'waterfallproject', 'code' => 'waterfallgantt', 'width' => '2');
$lang->block->default['waterfallproject'][] = array('title' => '最新动态',                   'module' => 'waterfallproject', 'code' => 'projectdynamic', 'width' => '1');

$lang->block->default['waterfallplusproject'] = $lang->block->default['waterfallproject'];
$lang->block->default['ipdproject']           = $lang->block->default['waterfallproject'];

$lang->block->default['product'][] = array('title' => "{$lang->productCommon}总览",             'module' => 'product', 'code' => 'overview',         'width' => '3');
$lang->block->default['product'][] = array('title' => "未关闭的{$lang->productCommon}统计",     'module' => 'product', 'code' => 'statistic',        'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20'));
$lang->block->default['product'][] = array('title' => "未关闭{$lang->productCommon}的Bug数据",  'module' => 'product', 'code' => 'bugstatistic',     'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20'));
$lang->block->default['product'][] = array('title' => "{$lang->productCommon}月度推进分析",     'module' => 'product', 'code' => 'monthlyprogress',  'width' => '2');
$lang->block->default['product'][] = array('title' => "{$lang->productCommon}年度工作量统计",   'module' => 'product', 'code' => 'annualworkload',   'width' => '2');
$lang->block->default['product'][] = array('title' => "未关闭的{$lang->productCommon}列表",     'module' => 'product', 'code' => 'list',             'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20', 'orderBy' => 'id_desc'));
$lang->block->default['product'][] = array('title' => "未关闭{$lang->productCommon}的发布列表", 'module' => 'product', 'code' => 'release',          'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20'));
$lang->block->default['product'][] = array('title' => "未关闭{$lang->productCommon}的计划列表", 'module' => 'product', 'code' => 'plan',             'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20'));
$lang->block->default['product'][] = array('title' => "{$lang->productCommon}发布统计",         'module' => 'product', 'code' => 'releasestatistic', 'width' => '1');
$lang->block->default['product'][] = array('title' => "指派给我的{$lang->SRCommon}",            'module' => 'product', 'code' => 'story',            'width' => '1', 'params' => array('type' => 'assignedTo', 'count' => '20', 'orderBy' => 'id_desc'));

$lang->block->default['singleproduct'][] = array('title' => "{$lang->productCommon}统计",               'module' => 'singleproduct', 'code' => 'singlestatistic',        'width' => '2', 'params' => array('count' => '20'));
$lang->block->default['singleproduct'][] = array('title' => "{$lang->productCommon}的Bug数据",          'module' => 'singleproduct', 'code' => 'singlebugstatistic',     'width' => '2', 'params' => array('count' => '20'));
$lang->block->default['singleproduct'][] = array('title' => "{$lang->productCommon}路线图",             'module' => 'singleproduct', 'code' => 'roadmap',                'width' => '2');
$lang->block->default['singleproduct'][] = array('title' => "指派给我的{$lang->SRCommon}",              'module' => 'singleproduct', 'code' => 'singlestory',            'width' => '2', 'params' => array('type' => 'assignedTo', 'count' => '20', 'orderBy' => 'id_desc'));
$lang->block->default['singleproduct'][] = array('title' => "{$lang->productCommon}计划列表",           'module' => 'singleproduct', 'code' => 'singleplan',             'width' => '2', 'params' => array('count' => '20'));
$lang->block->default['singleproduct'][] = array('title' => "{$lang->productCommon}发布统计",           'module' => 'singleproduct', 'code' => 'singlerelease',          'width' => '2', 'params' => array('count' => '20'));
$lang->block->default['singleproduct'][] = array('title' => "最新动态",                                 'module' => 'singleproduct', 'code' => 'singledynamic',          'width' => '1');
$lang->block->default['singleproduct'][] = array('title' => "{$lang->productCommon}月度推进分析",       'module' => 'singleproduct', 'code' => 'singlemonthlyprogress',  'width' => '1');

$lang->block->default['qa'][] = array('title' => '测试统计',           'module' => 'qa', 'code' => 'statistic', 'width' => '2', 'params' => array('type' => 'noclosed',   'count' => '20'));
$lang->block->default['qa'][] = array('title' => '待测测试单列表',     'module' => 'qa', 'code' => 'testtask',  'width' => '2', 'params' => array('type' => 'wait',       'count' => '15', 'orderBy' => 'id_desc'));
$lang->block->default['qa'][] = array('title' => '指派给我的Bug列表',  'module' => 'qa', 'code' => 'bug',       'width' => '1', 'params' => array('type' => 'assignedTo', 'count' => '15', 'orderBy' => 'id_desc'));
$lang->block->default['qa'][] = array('title' => '指派给我的用例列表', 'module' => 'qa', 'code' => 'case',      'width' => '1', 'params' => array('type' => 'assigntome', 'count' => '15', 'orderBy' => 'id_desc'));

$lang->block->default['full']['my'][] = array('title' => '欢迎总览',                               'module' => 'welcome',         'code' => 'welcome',         'width' => '2');
$lang->block->default['full']['my'][] = array('title' => "使用帮助",                               'module' => 'guide',           'code' => 'guide',           'width' => '2');
$lang->block->default['full']['my'][] = array('title' => "我近期参与的{$lang->projectCommon}",     'module' => 'project',         'code' => 'recentproject',   'width' => '2');
$lang->block->default['full']['my'][] = array('title' => "我的待处理",                             'module' => 'assigntome',      'code' => 'assigntome',      'width' => '2', 'params' => array('todoCount' => '20',  'taskCount' => '20', 'bugCount' => '20', 'riskCount' => '20', 'issueCount' => '20', 'storyCount' => '20', 'reviewCount' => '20', 'meetingCount' => '20', 'feedbackCount' => '20'));
$lang->block->default['full']['my'][] = array('title' => "未关闭的{$lang->productCommon}统计",     'module' => 'product',         'code' => 'statistic',       'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20'));
$lang->block->default['full']['my'][] = array('title' => "未完成的{$lang->projectCommon}统计",     'module' => 'project',         'code' => 'statistic',       'width' => '2', 'params' => array('type' => 'undone',   'count' => '20'));
$lang->block->default['full']['my'][] = array('title' => "未完成的{$lang->execution->common}统计", 'module' => 'execution',       'code' => 'statistic',       'width' => '2', 'params' => array('type' => 'undone',   'count' => '20'));
$lang->block->default['full']['my'][] = array('title' => "未关闭{$lang->productCommon}的测试统计", 'module' => 'qa',              'code' => 'statistic',       'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20'));
$lang->block->default['full']['my'][] = array('title' => "未完成的{$lang->projectCommon}列表",     'module' => 'project',         'code' => 'project',         'width' => '2', 'params' => array('type' => 'undone',   'count' => '20', 'orderBy' => 'id_desc'));
$lang->block->default['full']['my'][] = array('title' => "禅道动态",                               'module' => 'zentaodynamic',   'code' => 'zentaodynamic',   'width' => '1');
$lang->block->default['full']['my'][] = array('title' => "团队成就",                               'module' => 'teamachievement', 'code' => 'teamachievement', 'width' => '1');
$lang->block->default['full']['my'][] = array('title' => "最新动态",                               'module' => 'dynamic',         'code' => 'dynamic',         'width' => '1');
$lang->block->default['full']['my'][] = array('title' => "{$lang->productCommon}总览",             'module' => 'product',         'code' => 'overview',        'width' => '1');
$lang->block->default['full']['my'][] = array('title' => "{$lang->projectCommon}总览",             'module' => 'project',         'code' => 'overview',        'width' => '1');
$lang->block->default['full']['my'][] = array('title' => "{$lang->executionCommon}总览",           'module' => 'execution',       'code' => 'overview',        'width' => '1');

$lang->block->default['doc'][] = array('title' => '文档统计',                   'module' => 'doc', 'code' => 'docstatistic',    'width' => '2');
$lang->block->default['doc'][] = array('title' => '我收藏的文档',               'module' => 'doc', 'code' => 'docmycollection', 'width' => '2');
$lang->block->default['doc'][] = array('title' => '最近更新的文档',             'module' => 'doc', 'code' => 'docrecentupdate', 'width' => '2');
$lang->block->default['doc'][] = array('title' => "{$lang->productCommon}文档", 'module' => 'doc', 'code' => 'productdoc',      'width' => '2', 'params' => array('count' => '20'));
$lang->block->default['doc'][] = array('title' => "{$lang->projectCommon}文档", 'module' => 'doc', 'code' => 'projectdoc',      'width' => '2', 'params' => array('count' => '20'));
$lang->block->default['doc'][] = array('title' => '文档动态',                   'module' => 'doc', 'code' => 'docdynamic',      'width' => '1');
$lang->block->default['doc'][] = array('title' => '浏览排行榜',                 'module' => 'doc', 'code' => 'docviewlist',     'width' => '1');
$lang->block->default['doc'][] = array('title' => '收藏排行榜',                 'module' => 'doc', 'code' => 'doccollectlist',  'width' => '1');

$lang->block->count   = '数量';
$lang->block->type    = '类型';
$lang->block->orderBy = '排序';

$lang->block->availableBlocks['todo']        = '待办';
$lang->block->availableBlocks['task']        = '任务';
$lang->block->availableBlocks['bug']         = 'Bug';
$lang->block->availableBlocks['case']        = '用例';
$lang->block->availableBlocks['story']       = "{$lang->SRCommon}";
$lang->block->availableBlocks['requirement'] = "{$lang->URCommon}";
$lang->block->availableBlocks['product']     = $lang->productCommon . '列表';
$lang->block->availableBlocks['execution']   = $lang->executionCommon . '列表';
$lang->block->availableBlocks['plan']        = "计划列表";
$lang->block->availableBlocks['release']     = '发布列表';
$lang->block->availableBlocks['build']       = '版本列表';
$lang->block->availableBlocks['testcase']    = '用例';
$lang->block->availableBlocks['testtask']    = '测试单';
$lang->block->availableBlocks['risk']        = '风险';
$lang->block->availableBlocks['issue']       = '问题';
$lang->block->availableBlocks['meeting']     = '会议';
$lang->block->availableBlocks['feedback']    = '反馈';
$lang->block->availableBlocks['ticket']      = '工单';

$lang->block->modules['project'] = new stdclass();
$lang->block->modules['project']->availableBlocks['overview']      = "{$lang->projectCommon}总览";
$lang->block->modules['project']->availableBlocks['recentproject'] = "我近期参与的{$lang->projectCommon}";
$lang->block->modules['project']->availableBlocks['statistic']     = "{$lang->projectCommon}统计";
$lang->block->modules['project']->availableBlocks['project']       = "{$lang->projectCommon}列表";

$lang->block->modules['scrumproject'] = new stdclass();
$lang->block->modules['scrumproject']->availableBlocks['scrumoverview']  = "{$lang->projectCommon}总览";
$lang->block->modules['scrumproject']->availableBlocks['scrumlist']      = $lang->executionCommon . '列表';
$lang->block->modules['scrumproject']->availableBlocks['sprint']         = $lang->executionCommon . '总览';
$lang->block->modules['scrumproject']->availableBlocks['scrumtest']      = '测试单列表';
$lang->block->modules['scrumproject']->availableBlocks['projectdynamic'] = '最新动态';

$lang->block->modules['waterfallproject'] = new stdclass();
$lang->block->modules['waterfallproject']->availableBlocks['waterfallgantt'] = "{$lang->projectCommon}计划";
$lang->block->modules['waterfallproject']->availableBlocks['projectdynamic'] = '最新动态';

$lang->block->modules['agileplus']     = $lang->block->modules['scrumproject'];
$lang->block->modules['waterfallplus'] = $lang->block->modules['waterfallproject'];

$lang->block->modules['product'] = new stdclass();
$lang->block->modules['product']->availableBlocks['overview']         = "{$lang->productCommon}总览";
$lang->block->modules['product']->availableBlocks['statistic']        = "{$lang->productCommon}统计";
$lang->block->modules['product']->availableBlocks['releasestatistic'] = "{$lang->productCommon}发布统计";
$lang->block->modules['product']->availableBlocks['bugstatistic']     = "{$lang->productCommon}Bug统计";
$lang->block->modules['product']->availableBlocks['annualworkload']   = "{$lang->productCommon}年度工作量统计";
$lang->block->modules['product']->availableBlocks['monthlyprogress']  = "{$lang->productCommon}月度推进分析";
$lang->block->modules['product']->availableBlocks['list']             = "{$lang->productCommon}列表";
$lang->block->modules['product']->availableBlocks['plan']             = "{$lang->productCommon}的计划列表";
$lang->block->modules['product']->availableBlocks['release']          = "{$lang->productCommon}的发布列表";
$lang->block->modules['product']->availableBlocks['story']            = "{$lang->SRCommon}列表";

$lang->block->modules['singleproduct'] = new stdclass();
$lang->block->modules['singleproduct']->availableBlocks['singlestatistic']       = "{$lang->productCommon}统计";
$lang->block->modules['singleproduct']->availableBlocks['singlebugstatistic']    = "{$lang->productCommon}Bug统计";
$lang->block->modules['singleproduct']->availableBlocks['roadmap']               = "{$lang->productCommon}路线图";
$lang->block->modules['singleproduct']->availableBlocks['singlestory']           = "{$lang->SRCommon}列表";
$lang->block->modules['singleproduct']->availableBlocks['singleplan']            = "{$lang->productCommon}计划列表";
$lang->block->modules['singleproduct']->availableBlocks['singlerelease']         = "{$lang->productCommon}发布列表";
$lang->block->modules['singleproduct']->availableBlocks['singledynamic']         = '最新动态';
$lang->block->modules['singleproduct']->availableBlocks['singlemonthlyprogress'] = "{$lang->productCommon}月度推进分析";

$lang->block->modules['execution'] = new stdclass();
$lang->block->modules['execution']->availableBlocks['statistic'] = $lang->execution->common . '统计';
$lang->block->modules['execution']->availableBlocks['overview']  = $lang->execution->common . '总览';
$lang->block->modules['execution']->availableBlocks['list']      = $lang->execution->common . '列表';
$lang->block->modules['execution']->availableBlocks['task']      = '任务列表';
$lang->block->modules['execution']->availableBlocks['build']     = '版本列表';

$lang->block->modules['qa'] = new stdclass();
$lang->block->modules['qa']->availableBlocks['statistic'] = "{$lang->productCommon}的测试统计";
$lang->block->modules['qa']->availableBlocks['bug']       = 'Bug列表';
$lang->block->modules['qa']->availableBlocks['case']      = '用例列表';
$lang->block->modules['qa']->availableBlocks['testtask']  = '测试单列表';

$lang->block->modules['todo'] = new stdclass();
$lang->block->modules['todo']->availableBlocks['list'] = '待办列表';

$lang->block->modules['doc'] = new stdclass();
$lang->block->modules['doc']->availableBlocks['docstatistic']    = '文档统计';
$lang->block->modules['doc']->availableBlocks['docdynamic']      = '文档动态';
$lang->block->modules['doc']->availableBlocks['docmycollection'] = '我的收藏';
$lang->block->modules['doc']->availableBlocks['docrecentupdate'] = '最近更新';
$lang->block->modules['doc']->availableBlocks['docviewlist']     = '浏览排行榜';
if($config->vision == 'rnd') $lang->block->modules['doc']->availableBlocks['productdoc'] = $lang->productCommon . '文档';
$lang->block->modules['doc']->availableBlocks['doccollectlist']  = '收藏排行榜';
$lang->block->modules['doc']->availableBlocks['projectdoc']      = $lang->projectCommon . '文档';

$lang->block->orderByList = new stdclass();
$lang->block->orderByList->product = array();
$lang->block->orderByList->product['id_asc']      = 'ID 递增';
$lang->block->orderByList->product['id_desc']     = 'ID 递减';
$lang->block->orderByList->product['status_asc']  = '状态正序';
$lang->block->orderByList->product['status_desc'] = '状态倒序';

$lang->block->orderByList->project = array();
$lang->block->orderByList->project['id_asc']      = 'ID 递增';
$lang->block->orderByList->project['id_desc']     = 'ID 递减';
$lang->block->orderByList->project['status_asc']  = '状态正序';
$lang->block->orderByList->project['status_desc'] = '状态倒序';

$lang->block->orderByList->execution = array();
$lang->block->orderByList->execution['id_asc']      = 'ID 递增';
$lang->block->orderByList->execution['id_desc']     = 'ID 递减';
$lang->block->orderByList->execution['status_asc']  = '状态正序';
$lang->block->orderByList->execution['status_desc'] = '状态倒序';

$lang->block->orderByList->task = array();
$lang->block->orderByList->task['id_asc']        = 'ID 递增';
$lang->block->orderByList->task['id_desc']       = 'ID 递减';
$lang->block->orderByList->task['pri_asc']       = '优先级递增';
$lang->block->orderByList->task['pri_desc']      = '优先级递减';
$lang->block->orderByList->task['estimate_asc']  = '预计时间递增';
$lang->block->orderByList->task['estimate_desc'] = '预计时间递减';
$lang->block->orderByList->task['status_asc']    = '状态正序';
$lang->block->orderByList->task['status_desc']   = '状态倒序';
$lang->block->orderByList->task['deadline_asc']  = '截止日期递增';
$lang->block->orderByList->task['deadline_desc'] = '截止日期递减';

$lang->block->orderByList->bug = array();
$lang->block->orderByList->bug['id_asc']        = 'ID 递增';
$lang->block->orderByList->bug['id_desc']       = 'ID 递减';
$lang->block->orderByList->bug['pri_asc']       = '优先级递增';
$lang->block->orderByList->bug['pri_desc']      = '优先级递减';
$lang->block->orderByList->bug['severity_asc']  = '级别递增';
$lang->block->orderByList->bug['severity_desc'] = '级别递减';

$lang->block->orderByList->case = array();
$lang->block->orderByList->case['id_asc']   = 'ID 递增';
$lang->block->orderByList->case['id_desc']  = 'ID 递减';
$lang->block->orderByList->case['pri_asc']  = '优先级递增';
$lang->block->orderByList->case['pri_desc'] = '优先级递减';

$lang->block->orderByList->story = array();
$lang->block->orderByList->story['id_asc']      = 'ID 递增';
$lang->block->orderByList->story['id_desc']     = 'ID 递减';
$lang->block->orderByList->story['pri_asc']     = '优先级递增';
$lang->block->orderByList->story['pri_desc']    = '优先级递减';
$lang->block->orderByList->story['status_asc']  = '状态正序';
$lang->block->orderByList->story['status_desc'] = '状态倒序';
$lang->block->orderByList->story['stage_asc']   = '阶段正序';
$lang->block->orderByList->story['stage_desc']  = '阶段倒序';

$lang->block->todoCount     = '待办数';
$lang->block->taskCount     = '任务数';
$lang->block->bugCount      = 'Bug数';
$lang->block->riskCount     = '风险数';
$lang->block->issueCount    = '问题数';
$lang->block->storyCount    = '需求数';
$lang->block->reviewCount   = '审批数';
$lang->block->meetingCount  = '会议数';
$lang->block->feedbackCount = '反馈数';
$lang->block->ticketCount   = '工单数';

$lang->block->typeList = new stdclass();
$lang->block->typeList->task['assignedTo'] = '指派给我';
$lang->block->typeList->task['openedBy']   = '由我创建';
$lang->block->typeList->task['finishedBy'] = '由我完成';
$lang->block->typeList->task['closedBy']   = '由我关闭';
$lang->block->typeList->task['canceledBy'] = '由我取消';

$lang->block->typeList->bug['assignedTo'] = '指派给我';
$lang->block->typeList->bug['openedBy']   = '由我创建';
$lang->block->typeList->bug['resolvedBy'] = '由我解决';
$lang->block->typeList->bug['closedBy']   = '由我关闭';

$lang->block->typeList->case['assigntome'] = '指派给我';
$lang->block->typeList->case['openedbyme'] = '由我创建';

$lang->block->typeList->story['assignedTo'] = '指派给我';
$lang->block->typeList->story['openedBy']   = '由我创建';
$lang->block->typeList->story['reviewBy']   = '待我评审';
$lang->block->typeList->story['reviewedBy'] = '我评审过';
$lang->block->typeList->story['closedBy']   = '由我关闭';

$lang->block->typeList->product['noclosed'] = '未关闭';
$lang->block->typeList->product['closed']   = '已关闭';
$lang->block->typeList->product['all']      = '全部';
$lang->block->typeList->product['involved'] = '我参与';

$lang->block->typeList->project['undone']   = '未完成';
$lang->block->typeList->project['doing']    = '进行中';
$lang->block->typeList->project['all']      = '全部';
$lang->block->typeList->project['involved'] = '我参与的';

$lang->block->typeList->projectAll['all']       = '全部';
$lang->block->typeList->projectAll['undone']    = '未完成';
$lang->block->typeList->projectAll['wait']      = '未开始';
$lang->block->typeList->projectAll['doing']     = '进行中';
$lang->block->typeList->projectAll['suspended'] = '已挂起';
$lang->block->typeList->projectAll['closed']    = '已关闭';

$lang->block->typeList->execution['undone']   = '未完成';
$lang->block->typeList->execution['doing']    = '进行中';
$lang->block->typeList->execution['all']      = '所有';
$lang->block->typeList->execution['involved'] = '我参与';

$lang->block->typeList->scrum['undone']   = '未完成';
$lang->block->typeList->scrum['doing']    = '进行中';
$lang->block->typeList->scrum['all']      = '全部';
$lang->block->typeList->scrum['involved'] = '我参与';

$lang->block->typeList->testtask['wait']    = '待测';
$lang->block->typeList->testtask['doing']   = '测试中';
$lang->block->typeList->testtask['blocked'] = '阻塞';
$lang->block->typeList->testtask['done']    = '已测';
$lang->block->typeList->testtask['all']     = '全部';

$lang->block->typeList->risk['all']      = '全部';
$lang->block->typeList->risk['active']   = '开放';
$lang->block->typeList->risk['assignTo'] = '指派给我';
$lang->block->typeList->risk['assignBy'] = '由我指派';
$lang->block->typeList->risk['closed']   = '已关闭';
$lang->block->typeList->risk['hangup']   = '已挂起';
$lang->block->typeList->risk['canceled'] = '已取消';

$lang->block->typeList->issue['all']      = '全部';
$lang->block->typeList->issue['open']     = '开放';
$lang->block->typeList->issue['assignto'] = '指派给我';
$lang->block->typeList->issue['assignby'] = '由我指派';
$lang->block->typeList->issue['closed']   = '已关闭';
$lang->block->typeList->issue['resolved'] = '已解决';
$lang->block->typeList->issue['canceled'] = '已取消';

$lang->block->welcomeList['06:00'] = '%s，早上好';
$lang->block->welcomeList['11:30'] = '%s，中午好';
$lang->block->welcomeList['13:30'] = '%s，下午好';
$lang->block->welcomeList['19:00'] = '%s，晚上好';

$lang->block->gridOptions[8] = '左侧';
$lang->block->gridOptions[4] = '右侧';

$lang->block->widthOptions['1'] = '短区块';
$lang->block->widthOptions['2'] = '长区块';
$lang->block->widthOptions['3'] = '超长区块';

$lang->block->flowchart            = array();
$lang->block->flowchart['admin']   = array('管理员', '维护部门', '添加用户', '维护权限');
if($config->systemMode == 'ALM') $lang->block->flowchart['program'] = array('项目集负责人', '创建项目集', "关联{$lang->productCommon}", "创建{$lang->projectCommon}", "制定预算和规划", '添加干系人');
$lang->block->flowchart['product'] = array($lang->productCommon . '经理', '创建' . $lang->productCommon, '维护模块', "维护计划", "维护需求", '创建发布');
$lang->block->flowchart['project'] = array('项目经理', "创建{$lang->productCommon}、" . $lang->execution->common, '维护团队', "关联需求", '分解任务', '跟踪进度');
$lang->block->flowchart['dev']     = array('研发人员', '领取任务和Bug', '设计实现方案', '更新状态', '完成任务和Bug', '提交代码');
$lang->block->flowchart['tester']  = array('测试人员', '撰写用例', '执行用例', '提交Bug', '验证Bug', '关闭Bug');

$lang->block->zentaoapp = new stdclass();
$lang->block->zentaoapp->common               = '禅道移动端';
$lang->block->zentaoapp->thisYearInvestment   = '今年投入';
$lang->block->zentaoapp->sinceTotalInvestment = '从使用至今，总投入';
$lang->block->zentaoapp->myStory              = '我的需求';
$lang->block->zentaoapp->allStorySum          = '需求总数';
$lang->block->zentaoapp->storyCompleteRate    = '需求完成率';
$lang->block->zentaoapp->latestExecution      = '近期执行';
$lang->block->zentaoapp->involvedExecution    = '我参与的执行';
$lang->block->zentaoapp->mangedProduct        = "负责{$lang->productCommon}";
$lang->block->zentaoapp->involvedProject      = "参与{$lang->projectCommon}";
$lang->block->zentaoapp->customIndexCard      = '定制首页卡片';
$lang->block->zentaoapp->createStory          = '提需求';
$lang->block->zentaoapp->createEffort         = '记日志';
$lang->block->zentaoapp->createDoc            = '建文档';
$lang->block->zentaoapp->createTodo           = '建待办';
$lang->block->zentaoapp->workbench            = '工作台';
$lang->block->zentaoapp->notSupportKanban     = '移动端暂不支持研发看板模式';
$lang->block->zentaoapp->notSupportVersion    = '移动端暂不支持该禅道版本';
$lang->block->zentaoapp->incompatibleVersion  = '当前禅道版本较低，请升级至最新版本后再试';
$lang->block->zentaoapp->canNotGetVersion     = '获取禅道版本失败，请确认网址是否正确';
$lang->block->zentaoapp->desc                 = "禅道移动端为您提供移动办公的环境，方便随时管理个人待办事务，跟进{$lang->projectCommon}进度，增强了{$lang->projectCommon}管理的灵活性和敏捷性。";
$lang->block->zentaoapp->downloadTip          = '扫描二维码下载';

$lang->block->zentaoclient = new stdClass();
$lang->block->zentaoclient->common = '禅道客户端';
$lang->block->zentaoclient->desc   = '您可以使用禅道桌面客户端直接使用禅道，无需频繁切换浏览器。除此之外，客户端还提供了聊天，信息通知，机器人，内嵌禅道小程序等功能，团队协作更方便。';

$lang->block->zentaoclient->edition = new stdclass();
$lang->block->zentaoclient->edition->win64   = 'Windows版';
$lang->block->zentaoclient->edition->linux64 = 'Linux版';
$lang->block->zentaoclient->edition->mac64   = 'Mac版';

$lang->block->guideTabs['flowchart']      = '流程图';
if($config->systemMode != 'PLM') $lang->block->guideTabs['systemMode']     = '运行模式';
$lang->block->guideTabs['visionSwitch']   = '界面切换';
$lang->block->guideTabs['themeSwitch']    = '主题切换';
$lang->block->guideTabs['preference']     = '个性化设置';
$lang->block->guideTabs['downloadClient'] = '客户端下载';
$lang->block->guideTabs['downloadMobile'] = '移动端下载';

$lang->block->themes['default']    = '禅道蓝';
$lang->block->themes['blue']       = '青春蓝';
$lang->block->themes['green']      = '叶兰绿';
$lang->block->themes['red']        = '赤诚红';
$lang->block->themes['pink']       = '芙蕖粉';
$lang->block->themes['blackberry'] = '露莓黑';
$lang->block->themes['classic']    = '经典蓝';
$lang->block->themes['purple']     = '玉烟紫';

$lang->block->visionTitle            = '禅道使用界面分为【研发综合界面】和【运营管理界面】。';
$lang->block->visions['rnd']         = new stdclass();
$lang->block->visions['rnd']->key    = 'rnd';
$lang->block->visions['rnd']->title  = '研发综合界面';
$lang->block->visions['rnd']->text   = "集项目集、{$lang->productCommon}、{$lang->projectCommon}、执行、测试等多维度管理于一体，提供全过程{$lang->projectCommon}管理解决方案。";
$lang->block->visions['lite']        = new stdclass();
$lang->block->visions['lite']->key   = 'lite';
$lang->block->visions['lite']->title = '运营管理界面';
$lang->block->visions['lite']->text  = "专为非研发团队打造，主要以直观、可视化的看板{$lang->projectCommon}管理模型为主。";

$lang->block->customModes['light'] = '轻量管理模式';
$lang->block->customModes['ALM']   = '全生命周期管理模式';

$lang->block->honorary = array();
$lang->block->honorary['bug']    = '消灭BUG能力者';
$lang->block->honorary['task']   = '勤劳小蜜蜂';
$lang->block->honorary['review'] = '模范评审官';

$lang->block->welcome = new stdclass();
$lang->block->welcome->common     = '欢迎总览';
$lang->block->welcome->reviewByMe = '待我评审';
$lang->block->welcome->assignToMe = '指派给我';

$lang->block->welcome->reviewList = array();
$lang->block->welcome->reviewList['story'] = '需求数';

$lang->block->welcome->assignList = array();
$lang->block->welcome->assignList['task']     = '任务数';
$lang->block->welcome->assignList['bug']      = 'BUG数';
$lang->block->welcome->assignList['story']    = '研发需求数';
$lang->block->welcome->assignList['testcase'] = '用例数';

$lang->block->customModeTip = new stdClass();
$lang->block->customModeTip->common = '禅道运行模式分为【轻量级管理模式】和【全生命周期管理模式】。';
$lang->block->customModeTip->ALM    = '适用于中大型团队的管理模式，概念更加完整、严谨，功能更丰富。';
$lang->block->customModeTip->light  = "适用于小型研发团队的管理模式，提供{$lang->projectCommon}管理的核心功能。";

$lang->block->productstatistic = new stdclass();
$lang->block->productstatistic->effectiveStory  = '有效需求';
$lang->block->productstatistic->delivered       = '已交付';
$lang->block->productstatistic->unclosed        = '未关闭';
$lang->block->productstatistic->storyStatistics = '需求统计';
$lang->block->productstatistic->monthDone       = '本月完成 <span class="text-success font-bold">%s</span>';
$lang->block->productstatistic->monthOpened     = '本月新增 <span class="text-primary font-bold">%s</span>';
$lang->block->productstatistic->opened          = '新增';
$lang->block->productstatistic->done            = '完成';
$lang->block->productstatistic->news            = '产品最新推进';
$lang->block->productstatistic->newPlan         = '最新计划';
$lang->block->productstatistic->newExecution    = '最新执行';
$lang->block->productstatistic->newRelease      = '最新发布';
$lang->block->productstatistic->deliveryRate    = '需求交付率';

$lang->block->projectoverview = new stdclass();
$lang->block->projectoverview->totalProject  = '项目总量';
$lang->block->projectoverview->thisYear      = '今年完成';
$lang->block->projectoverview->lastThreeYear = '近三年完成的项目数量分布';

$lang->block->projectstatistic = new stdclass();
$lang->block->projectstatistic->story            = '需求';
$lang->block->projectstatistic->cost             = '投入';
$lang->block->projectstatistic->task             = '任务';
$lang->block->projectstatistic->bug              = 'Bug';
$lang->block->projectstatistic->storyPoints      = '总规模';
$lang->block->projectstatistic->done             = '已完成';
$lang->block->projectstatistic->undone           = '剩余';
$lang->block->projectstatistic->costs            = '已投入';
$lang->block->projectstatistic->consumed         = '消耗工时';
$lang->block->projectstatistic->remainder        = '预计剩余';
$lang->block->projectstatistic->tasks            = '总数量';
$lang->block->projectstatistic->wait             = '未开始';
$lang->block->projectstatistic->doing            = '进行中';
$lang->block->projectstatistic->bugs             = '总数量';
$lang->block->projectstatistic->closed           = '已关闭';
$lang->block->projectstatistic->activated        = '激活';
$lang->block->projectstatistic->unit             = '个';
$lang->block->projectstatistic->SP               = 'SP';
$lang->block->projectstatistic->personDay        = '人天';
$lang->block->projectstatistic->day              = '天';
$lang->block->projectstatistic->hour             = 'h';
$lang->block->projectstatistic->leftDaysPre      = '距项目结束还剩';
$lang->block->projectstatistic->delayDaysPre     = '项目已延期';
$lang->block->projectstatistic->existRisks       = '存在风险：';
$lang->block->projectstatistic->existIssues      = '存在问题：';
$lang->block->projectstatistic->lastestExecution = '最新执行';
$lang->block->projectstatistic->projectClosed    = "{$lang->projectCommon}已关闭";
$lang->block->projectstatistic->longTimeProject  = "长期{$lang->projectCommon}";
$lang->block->projectstatistic->totalProgress    = '总进度';
$lang->block->projectstatistic->totalProgressTip = "<strong>项目总进度</strong>=按项目统计的任务消耗工时数 /（按项目统计的任务消耗工时数+按项目统计的任务剩余工时数）<br/>
<strong>按项目统计的任务消耗工时数</strong>：项目中任务的消耗工时数求和，过滤已删除的任务，过滤父任务，过滤已删除执行的任务。<br/>
<strong>按项目统计的任务剩余工时数</strong>：项目中任务的剩余工时数求和，过滤已删除的任务，过滤父任务，过滤已删除执行的任务。";
$lang->block->projectstatistic->currentCost      = '当前成本';
$lang->block->projectstatistic->sv               = '进度偏差率(SV)';
$lang->block->projectstatistic->pv               = '计划完成(PV)';
$lang->block->projectstatistic->ev               = '实际完成(EV)';
$lang->block->projectstatistic->cv               = '成本偏差率(CV)';
$lang->block->projectstatistic->ac               = '实际花费(AC)';

$lang->block->qastatistic = new stdclass();
$lang->block->qastatistic->fixBugRate        = 'Bug修复率';
$lang->block->qastatistic->closedBugRate     = 'Bug关闭率';
$lang->block->qastatistic->totalBug          = 'Bug总数';
$lang->block->qastatistic->bugStatistics     = 'Bug统计';
$lang->block->qastatistic->addYesterday      = '昨日新增';
$lang->block->qastatistic->addToday          = '今日新增';
$lang->block->qastatistic->resolvedYesterday = '昨日解决';
$lang->block->qastatistic->resolvedToday     = '今日解决';
$lang->block->qastatistic->closedYesterday   = '昨日关闭';
$lang->block->qastatistic->closedToday       = '今日关闭';
$lang->block->qastatistic->latestTesttask    = '近期测试单';
$lang->block->qastatistic->bugStatusStat     = '月度Bug变化情况';

$lang->block->bugstatistic = new stdclass();
$lang->block->bugstatistic->effective = '有效Bug';
$lang->block->bugstatistic->fixed     = '已修复';
$lang->block->bugstatistic->activated = '激活的';

$lang->block->executionstatistic = new stdclass();
$lang->block->executionstatistic->allProject        = '全部项目';
$lang->block->executionstatistic->progress          = '执行进度';
$lang->block->executionstatistic->totalEstimate     = '预计工时';
$lang->block->executionstatistic->totalConsumed     = '消耗工时';
$lang->block->executionstatistic->totalLeft         = '剩余工时';
$lang->block->executionstatistic->burn              = '执行燃尽图';
$lang->block->executionstatistic->story             = '需求';
$lang->block->executionstatistic->doneStory         = '已完成';
$lang->block->executionstatistic->totalStory        = '总数量';
$lang->block->executionstatistic->task              = '任务';
$lang->block->executionstatistic->totalTask         = '任务总数';
$lang->block->executionstatistic->undoneTask        = '未完成任务';
$lang->block->executionstatistic->yesterdayDoneTask = '昨日完成';

$lang->block->executionoverview = new stdclass();
$lang->block->executionoverview->totalExecution = "{$lang->executionCommon}总量";
$lang->block->executionoverview->thisYear       = '今年完成';
$lang->block->executionoverview->statusCount    = "未关闭{$lang->executionCommon}状态分布";

$lang->block->productoverview = new stdclass();
$lang->block->productoverview->overview                = '总览数据';
$lang->block->productoverview->yearFinished            = '产品年度推进统计';
$lang->block->productoverview->productLineCount        = '产品线总量';
$lang->block->productoverview->productCount            = '产品总量';
$lang->block->productoverview->releaseCount            = '今年发布';
$lang->block->productoverview->milestoneCount          = '发布里程碑';
$lang->block->productoverview->unfinishedPlanCount     = '未完成计划数';
$lang->block->productoverview->unclosedStoryCount      = '未关闭需求数';
$lang->block->productoverview->activeBugCount          = '激活 Bug 数';
$lang->block->productoverview->finishedReleaseCount    = '已完成发布数';
$lang->block->productoverview->finishedStoryCount      = '已完成需求数';
$lang->block->productoverview->finishedStoryPoint      = '已完成需求规模';
$lang->block->productoverview->thisWeek                = '本周';

$lang->block->productlist = new stdclass();
$lang->block->productlist->unclosedFeedback  = '未关闭反馈';
$lang->block->productlist->activatedStory    = '激活需求';
$lang->block->productlist->storyCompleteRate = '需求完成率';
$lang->block->productlist->activatedBug      = '激活Bug';

$lang->block->sprint = new stdclass();
$lang->block->sprint->totalExecution = "{$lang->executionCommon}总量";
$lang->block->sprint->thisYear       = '今年完成';
$lang->block->sprint->statusCount    = "{$lang->executionCommon}状态分布";

$lang->block->zentaodynamic = new stdclass();
$lang->block->zentaodynamic->zentaosalon  = '禅道中国行';
$lang->block->zentaodynamic->publicclass  = '禅道公开课';
$lang->block->zentaodynamic->release      = '最新发布';
$lang->block->zentaodynamic->registration = '立即报名';
$lang->block->zentaodynamic->reservation  = '立即预约';

$lang->block->monthlyprogress = new stdclass();
$lang->block->monthlyprogress->doneStoryEstimateTrendChart = '完成需求规模趋势图';
$lang->block->monthlyprogress->storyTrendChart             = '需求新增和完成趋势图';
$lang->block->monthlyprogress->bugTrendChart               = 'Bug新增和解决趋势图';

$lang->block->annualworkload = new stdclass();
$lang->block->annualworkload->doneStoryEstimate = '完成需求规模';
$lang->block->annualworkload->doneStoryCount    = '完成需求数';
$lang->block->annualworkload->resolvedBugCount  = '修复Bug数';

$lang->block->releasestatistic = new stdclass();
$lang->block->releasestatistic->monthly = '月度发布次数趋势图';
$lang->block->releasestatistic->annual  = "年度发布榜（%s年）";

$lang->block->teamachievement = new stdclass();
$lang->block->teamachievement->finishedTasks  = '完成任务数量';
$lang->block->teamachievement->createdStories = '创建需求数量';
$lang->block->teamachievement->closedBugs     = '关闭的Bug数';
$lang->block->teamachievement->runCases       = '执行的用例数';
$lang->block->teamachievement->consumedHours  = '消耗工时';
$lang->block->teamachievement->totalWorkload  = '累计工作量';
$lang->block->teamachievement->vs             = '较昨日';

$lang->block->moduleList['product']         = $lang->productCommon;
$lang->block->moduleList['project']         = $lang->projectCommon;
$lang->block->moduleList['execution']       = $lang->execution->common;
$lang->block->moduleList['qa']              = $lang->qa->common;
$lang->block->moduleList['welcome']         = $lang->block->welcome->common;
$lang->block->moduleList['guide']           = $lang->block->guide;
$lang->block->moduleList['zentaodynamic']   = $lang->block->zentaoDynamic;
$lang->block->moduleList['teamachievement'] = $lang->block->teamAchievement;
$lang->block->moduleList['assigntome']      = $lang->block->assignToMe;
$lang->block->moduleList['dynamic']         = $lang->block->dynamic;
$lang->block->moduleList['html']            = $lang->block->html;

$lang->block->tooltips = array();
$lang->block->tooltips['deliveryRate']      = "{$lang->SRCommon}交付率=按{$lang->productCommon}统计的已交付{$lang->SRCommon}数 /（按{$lang->productCommon}统计的{$lang->SRCommon}总数 - 按{$lang->productCommon}统计的无效{$lang->SRCommon}数） * 100%";
$lang->block->tooltips['resolvedRate']      = "按{$lang->productCommon}统计的Bug修复率 = 按{$lang->productCommon}统计的修复Bug数 / 按{$lang->productCommon}统计的有效Bug数";
$lang->block->tooltips['effectiveStory']    = "按{$lang->productCommon}统计的{$lang->SRCommon}总数：{$lang->productCommon}中{$lang->SRCommon}的个数求和，过滤已删除的{$lang->SRCommon}，过滤已删除的{$lang->productCommon}。";
$lang->block->tooltips['deliveredStory']    = "按{$lang->productCommon}统计的已交付{$lang->SRCommon}数：{$lang->productCommon}中{$lang->SRCommon}个数求和，所处阶段为已发布或关闭原因为已完成，过滤已删除的{$lang->SRCommon}，过滤已删除的{$lang->productCommon}。";
$lang->block->tooltips['costs']             = "已投入 = 已消耗工时 / 每日可用工时";
$lang->block->tooltips['sv']                = "进度偏差率 = (EV - PV) / PV * 100% ";
$lang->block->tooltips['ev']                = "<strong>实际完成</strong>=按{$lang->projectCommon}统计的任务预计工时数*按{$lang->projectCommon}统计的任务进度，过滤已删除的任务，过滤已取消的任务，过滤已删除{$lang->execution->common}下的任务，过滤已删除的{$lang->projectCommon}。<br/>
<strong>按{$lang->projectCommon}统计的任务预计工时数</strong>：{$lang->projectCommon}中任务的预计工时数求和，过滤已删除的任务，过滤父任务，过滤已删除{$lang->execution->common}的任务，过滤已删除的{$lang->projectCommon}。";
$lang->block->tooltips['pv']                = "计划完成:瀑布{$lang->projectCommon}中所有任务的预计工时之和，过滤已删除的任务，过滤已取消的任务，过滤已删除的{$lang->execution->common}的任务，过滤已删除的{$lang->projectCommon}。";
$lang->block->tooltips['cv']                = "成本偏差率 = (EV - AC) / AC * 100%";
$lang->block->tooltips['ac']                = "实际花费：瀑布{$lang->projectCommon}中所有日志记录的工时之和，过滤已删除的{$lang->projectCommon}。";
$lang->block->tooltips['executionProgress'] = "<strong>{$lang->execution->common}进度</strong>=按{$lang->execution->common}统计的任务消耗工时数 /（按{$lang->execution->common}统计的任务消耗工时数+按{$lang->execution->common}统计的任务剩余工时数）<br/>
<strong>按{$lang->execution->common}统计的任务消耗工时数</strong>：{$lang->execution->common}中任务的消耗工时数求和，过滤已删除的任务，过滤父任务，过滤已删除的{$lang->execution->common}，过滤已删除的{$lang->projectCommon}。<br/>
<strong>按{$lang->execution->common}统计的任务剩余工时数</strong>：{$lang->execution->common}中任务的剩余工时数求和，过滤已删除的任务，过滤父任务，过滤已删除的{$lang->execution->common}，过滤已删除的{$lang->projectCommon}。";
