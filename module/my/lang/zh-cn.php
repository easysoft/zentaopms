<?php
global $config;

/* 方法列表。*/
$lang->my->index           = '首页';
$lang->my->data            = '我的数据';
$lang->my->todo            = '我的待办';
$lang->my->calendar        = '日程';
$lang->my->work            = '待处理';
$lang->my->contribute      = '贡献';
$lang->my->task            = '我的任务';
$lang->my->bug             = '我的Bug';
$lang->my->myTestTask      = '我的版本';
$lang->my->myTestCase      = '我的用例';
$lang->my->story           = "我的{$lang->SRCommon}";
$lang->my->doc             = "我的文档";
$lang->my->createProgram   = '添加项目';
$lang->my->project         = "我的项目";
$lang->my->execution       = "我的{$lang->executionCommon}";
$lang->my->issue           = '我的问题';
$lang->my->risk            = '我的风险';
$lang->my->profile         = '我的档案';
$lang->my->dynamic         = '我的动态';
$lang->my->team            = '团队';
$lang->my->editProfile     = '修改档案';
$lang->my->changePassword  = '修改密码';
$lang->my->preference      = '个性化设置';
$lang->my->unbind          = '解除ZDOO绑定';
$lang->my->manageContacts  = '维护联系人';
$lang->my->deleteContacts  = '删除联系人';
$lang->my->shareContacts   = '共享联系人列表';
$lang->my->limited         = '受限操作(只能编辑与自己相关的内容)';
$lang->my->score           = '我的积分';
$lang->my->scoreRule       = '积分规则';
$lang->my->noTodo          = '暂时没有待办。';
$lang->my->noData          = "暂时没有%s。";
$lang->my->storyChanged    = "需求变更";
$lang->my->hours           = '工时/天';
$lang->my->uploadAvatar    = '更换头像';
$lang->my->requirement     = "我的{$lang->URCommon}";
$lang->my->testtask        = '我的测试单';
$lang->my->testcase        = '我的用例';
$lang->my->storyConcept    = $config->URAndSR ? '默认需求概念组合' : '默认需求概念';
$lang->my->pri             = '优先级';
$lang->my->alert           = '后续您可以点击右上方的头像，选择“个性化设置”修改信息。';
$lang->my->assignedToMe    = '指派给我';
$lang->my->byQuery         = '搜索';
$lang->my->contactList     = '联系人列表';

$lang->my->indexAction      = '地盘仪表盘';
$lang->my->calendarAction   = '我的日程';
$lang->my->workAction       = '我的待处理';
$lang->my->contributeAction = '我的贡献';
$lang->my->profileAction    = '个人档案';
$lang->my->dynamicAction    = '动态';

$lang->my->myExecutions = "我参与的阶段/冲刺/迭代";
$lang->my->name         = '名称';
$lang->my->code         = '代号';
$lang->my->projects     = '所属项目';
$lang->my->executions   = "所属{$lang->executionCommon}";

$lang->my->executionMenu = new stdclass();
$lang->my->executionMenu->undone = '未完成';
$lang->my->executionMenu->done   = '已完成';

$lang->my->taskMenu = new stdclass();
$lang->my->taskMenu->assignedToMe = '指派给我';
$lang->my->taskMenu->openedByMe   = '由我创建';
$lang->my->taskMenu->finishedByMe = '由我完成';
$lang->my->taskMenu->closedByMe   = '由我关闭';
$lang->my->taskMenu->canceledByMe = '由我取消';
$lang->my->taskMenu->assignedByMe = '由我指派';

$lang->my->storyMenu = new stdclass();
$lang->my->storyMenu->assignedToMe = '指派给我';
$lang->my->storyMenu->reviewByMe   = '待我评审';
$lang->my->storyMenu->openedByMe   = '由我创建';
$lang->my->storyMenu->reviewedByMe = '由我评审';
$lang->my->storyMenu->closedByMe   = '由我关闭';
$lang->my->storyMenu->assignedByMe = '由我指派';

$lang->my->projectMenu = new stdclass();
$lang->my->projectMenu->doing      = '进行中';
$lang->my->projectMenu->wait       = '未开始';
$lang->my->projectMenu->suspended  = '已挂起';
$lang->my->projectMenu->closed     = '已关闭';
$lang->my->projectMenu->openedbyme = '由我创建';

$lang->my->form = new stdclass();
$lang->my->form->lblBasic   = '基本信息';
$lang->my->form->lblContact = '联系信息';
$lang->my->form->lblAccount = '帐号信息';

$lang->my->programLink   = '项目集默认着陆页';
$lang->my->productLink   = '产品默认着陆页';
$lang->my->projectLink   = '项目默认着陆页';
if($config->systemMode == 'classic') $lang->my->executionLink = $lang->executionCommon . '默认着陆页';
if($config->systemMode == 'new') $lang->my->executionLink = '执行默认着陆页';

$lang->my->programLinkList = array();
$lang->my->programLinkList['program-browse']  = '默认进入项目集列表，可以查看所有的项目集';
$lang->my->programLinkList['program-project'] = '默认进入最近一个项目集的项目列表，可以查看当前项目集下所有项目';
if($config->systemMode == 'new') $lang->my->programLinkList['program-kanban'] = '默认进入项目集看板，可以可视化的查看到所有项目集的进展情况';

$lang->my->productLinkList = array();
$lang->my->productLinkList['product-index']     = '默认进入产品主页，可以了解公司整体的产品状况';
$lang->my->productLinkList['product-all']       = '默认进入产品列表，可以查看所有的产品';
$lang->my->productLinkList['product-dashboard'] = '默认进入最近一个产品仪表盘，可以查看当前产品概况';
$lang->my->productLinkList['product-browse']    = '默认进入最近一个产品的需求列表，可以查看当前产品下的需求信息';
$lang->my->productLinkList['product-kanban']    = '默认进入产品看板，可以可视化的查看到所有产品的进展情况';

global $config;
$lang->my->projectLinkList = array();
$lang->my->projectLinkList['project-browse']    = '默认进入项目列表，可以查看所有的项目';
$lang->my->projectLinkList['project-execution'] = '默认进入项目下所有执行列表，查看所有执行信息';
$lang->my->projectLinkList['project-index']     = '默认进入最近一个项目仪表盘，可以查看当前项目概况';
if($config->systemMode == 'new') $lang->my->projectLinkList['project-kanban'] = '默认进入项目看板，可以可视化的查看到所有项目的进展情况';

$lang->my->executionLinkList = array();
if($config->systemMode == 'new')
{
    $lang->my->executionLinkList['execution-all']             = '默认进入执行列表，可以查看所有的执行';
    $lang->my->executionLinkList['execution-task']            = '默认进入最近一个执行的任务列表，可以查看当前迭代下的任务信息';
    $lang->my->executionLinkList['execution-executionkanban'] = '默认进入执行看板，可以查看进行中项目的执行情况';
}
if($config->systemMode == 'classic') $lang->my->executionLinkList['execution-task'] = "默认进入最近一个{$lang->executionCommon}的任务列表，可以查看当前{$lang->executionCommon}下的任务信息";

$lang->my->guideChangeTheme = <<<EOT
<p class='theme-title'>全新<span style='color: #0c60e1'>“青春蓝”</span>主题上线了！</p>
<div>
  <p>只需一步，您就可以体验全新的“青春蓝”主题了，赶紧去设置吧！</p>
  <p>鼠标经过<span style='color: #0c60e1'>【头像-主题-青春蓝】</span>，点击青春蓝，设置成功！</p>
</div>
EOT;
