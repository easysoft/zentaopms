<?php
global $config;

/* 方法列表。*/
$lang->my->index           = '首页';
$lang->my->data            = '我的数据';
$lang->my->todo            = '我的待办';
$lang->my->todoAction      = '日程列表';
$lang->my->calendar        = '日程';
$lang->my->work            = '待处理';
$lang->my->contribute      = '贡献';
$lang->my->task            = '我的任务';
$lang->my->bug             = '我的Bug';
$lang->my->myTestTask      = '我的测试单';
$lang->my->myTestCase      = '我的用例';
$lang->my->story           = "我的{$lang->SRCommon}";
$lang->my->doc             = "我的文档";
$lang->my->createProgram   = '添加' . $lang->projectCommon;
$lang->my->project         = '我的' . $lang->projectCommon;
$lang->my->execution       = "我的{$lang->execution->common}";
$lang->my->audit           = '审批';
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
$lang->my->createContacts  = '创建联系人';
$lang->my->deleteContacts  = '删除联系人';
$lang->my->viewContacts    = '查看联系人';
$lang->my->shareContacts   = '公共联系人';
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
$lang->my->myContact       = '我的联系人';
$lang->my->publicContact   = '公共联系人';
$lang->my->manageSelf      = '仅可维护自己创建的联系人';

$lang->my->indexAction      = '地盘仪表盘';
$lang->my->calendarAction   = '我的日程';
$lang->my->workAction       = '我的待处理';
$lang->my->contributeAction = '我的贡献';
$lang->my->profileAction    = '个人档案';
$lang->my->dynamicAction    = '动态';

$lang->my->myExecutions = "我参与的阶段/冲刺/迭代";
$lang->my->name         = '名称';
$lang->my->code         = '代号';
$lang->my->projects     = '所属' . $lang->projectCommon;
$lang->my->executions   = "所属{$lang->executionCommon}";

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

$lang->my->auditField = new stdclass();
$lang->my->auditField->title  = '评审标题';
$lang->my->auditField->time   = '提交时间';
$lang->my->auditField->type   = '评审对象';
$lang->my->auditField->result = '评审结果';
$lang->my->auditField->status = '状态';

$lang->my->auditField->oaTitle['attend']   = '%s的考勤申请：%s';
$lang->my->auditField->oaTitle['leave']    = '%s的请假申请：%s';
$lang->my->auditField->oaTitle['makeup']   = '%s的补班申请：%s';
$lang->my->auditField->oaTitle['overtime'] = '%s的加班申请：%s';
$lang->my->auditField->oaTitle['lieu']     = '%s的调休申请：%s';

$lang->my->form = new stdclass();
$lang->my->form->lblBasic   = '基本信息';
$lang->my->form->lblContact = '联系信息';
$lang->my->form->lblAccount = '帐号信息';

$lang->my->programLink   = '项目集默认着陆页';
$lang->my->productLink   = $lang->productCommon .'默认着陆页';
$lang->my->projectLink   = $lang->projectCommon . '默认着陆页';
$lang->my->executionLink = '执行默认着陆页';

$lang->my->programLinkList = array();
$lang->my->programLinkList['program-browse']  = '项目集列表/可以查看所有的项目集';
$lang->my->programLinkList['program-kanban']  = '项目集看板/可以可视化的查看到所有项目集的进展情况';
$lang->my->programLinkList['program-project'] = "最近一个项目集的{$lang->projectCommon}列表/可以查看当前项目集下所有{$lang->projectCommon}";

$lang->my->productLinkList = array();
$lang->my->productLinkList['product-all']       = "{$lang->productCommon}列表/可以查看所有{$lang->productCommon}";
$lang->my->productLinkList['product-kanban']    = "{$lang->productCommon}看板/以可视化的方式查看到所有{$lang->productCommon}的整体情况";
$lang->my->productLinkList['product-index']     = "所有{$lang->productCommon}仪表盘/可以查看所有{$lang->productCommon}的统计,概况，总览等";
$lang->my->productLinkList['product-dashboard'] = "最近一个{$lang->productCommon}仪表盘/可以查看最近查看过的一个{$lang->productCommon}仪表盘";
$lang->my->productLinkList['product-browse']    = "最近一个{$lang->productCommon}的需求列表/可以进入最近查看过的一个{$lang->productCommon}下的研发需求列表";

$lang->my->projectLinkList = array();
$lang->my->projectLinkList['project-browse']    = "{$lang->projectCommon}列表/可以查看所有{$lang->projectCommon}";
$lang->my->projectLinkList['project-kanban']    = "{$lang->projectCommon}看板/以可视化的查看到所有{$lang->projectCommon}的整体情况";
$lang->my->projectLinkList['project-execution'] = "最近一个{$lang->projectCommon}执行列表/可以查看{$lang->projectCommon}下所有的执行列表";
$lang->my->projectLinkList['project-index']     = "最近一个{$lang->projectCommon}仪表盘/可以进入最近查看过的一个{$lang->projectCommon}的仪表盘";

$lang->my->executionLinkList = array();
$lang->my->executionLinkList['execution-all']             = '执行列表/可以查看所有执行';
$lang->my->executionLinkList['execution-executionkanban'] = '执行看板/以可视化的方式查看所有执行的整体情况';
$lang->my->executionLinkList['execution-task']            = '最近一个执行的任务列表/可以查看最近创建的一个执行下的任务';

$lang->my->confirmReview['pass'] = '您确定要执行通过操作吗？';
$lang->my->guideChangeTheme = <<<EOT
<p class='theme-title'>全新<span style='color: #0c60e1'>“青春蓝”</span>主题上线了！</p>
<div>
  <p>只需一步，您就可以体验全新的“青春蓝”主题了，赶紧去设置吧！</p>
  <p>鼠标经过<span style='color: #0c60e1'>【头像-主题-青春蓝】</span>，点击青春蓝，设置成功！</p>
</div>
EOT;

$lang->my->featureBar['todo']['all']       = '指派给我';
$lang->my->featureBar['todo']['before']    = '未完成';
$lang->my->featureBar['todo']['future']    = '待定';
$lang->my->featureBar['todo']['today']     = '今日';
$lang->my->featureBar['todo']['thisWeek']  = '本周';
$lang->my->featureBar['todo']['thisMonth'] = '本月';
$lang->my->featureBar['todo']['more']      = '更多';

$lang->my->moreSelects['todo']['more']['thisYear']        = '本年';
$lang->my->moreSelects['todo']['more']['assignedToOther'] = '指派他人';
$lang->my->moreSelects['todo']['more']['cycle']           = '周期';

$lang->my->featureBar['audit']['all']      = '全部';
$lang->my->featureBar['audit']['demand']   = '需求池需求';
$lang->my->featureBar['audit']['story']    = '需求';
$lang->my->featureBar['audit']['testcase'] = '用例';
if(in_array($config->edition, array('max', 'ipd')) and (helper::hasFeature('waterfall') or helper::hasFeature('waterfallplus'))) $lang->my->featureBar['audit']['project'] = $lang->projectCommon;
if($config->edition != 'open') $lang->my->featureBar['audit']['feedback'] = '反馈';
if($config->edition != 'open' and helper::hasFeature('OA')) $lang->my->featureBar['audit']['oa'] = '办公';

$lang->my->featureBar['project']['doing']      = '进行中';
$lang->my->featureBar['project']['wait']       = '未开始';
$lang->my->featureBar['project']['suspended']  = '已挂起';
$lang->my->featureBar['project']['closed']     = '已关闭';
$lang->my->featureBar['project']['openedbyme'] = '由我创建';

$lang->my->featureBar['execution']['undone'] = '未完成';
$lang->my->featureBar['execution']['done']   = '已完成';

$lang->my->featureBar['dynamic']['all']       = '全部';
$lang->my->featureBar['dynamic']['today']     = '今天';
$lang->my->featureBar['dynamic']['yesterday'] = '昨天';
$lang->my->featureBar['dynamic']['thisWeek']  = '本周';
$lang->my->featureBar['dynamic']['lastWeek']  = '上周';
$lang->my->featureBar['dynamic']['thisMonth'] = '本月';
$lang->my->featureBar['dynamic']['lastMonth'] = '上月';

$lang->my->featureBar['work']['task']['assignedTo']     = $lang->my->assignedToMe;
$lang->my->featureBar['work']['testcase']['assigntome'] = $lang->my->assignedToMe;
$lang->my->featureBar['work']['testtask']['assignedTo'] = '由我负责';

$lang->my->featureBar['work']['requirement'] = $lang->my->featureBar['work']['task'];
$lang->my->featureBar['work']['requirement']['reviewBy'] = '待我评审';

$lang->my->featureBar['work']['story'] = $lang->my->featureBar['work']['requirement'];
$lang->my->featureBar['work']['bug']   = $lang->my->featureBar['work']['task'];

$lang->my->featureBar['contribute']['task']['openedBy']   = '由我创建';
$lang->my->featureBar['contribute']['task']['finishedBy'] = '由我完成';
$lang->my->featureBar['contribute']['task']['closedBy']   = '由我关闭';
$lang->my->featureBar['contribute']['task']['canceledBy'] = '由我取消';
$lang->my->featureBar['contribute']['task']['assignedBy'] = '由我指派';

$lang->my->featureBar['contribute']['requirement']['openedBy']   = '由我创建';
$lang->my->featureBar['contribute']['requirement']['reviewedBy'] = '我评审过';
$lang->my->featureBar['contribute']['requirement']['closedBy']   = '由我关闭';
$lang->my->featureBar['contribute']['requirement']['assignedBy'] = '由我指派';

$lang->my->featureBar['contribute']['bug']['openedBy']   = '由我创建';
$lang->my->featureBar['contribute']['bug']['resolvedBy'] = '由我解决';
$lang->my->featureBar['contribute']['bug']['closedBy']   = '由我关闭';
$lang->my->featureBar['contribute']['bug']['assignedBy'] = '由我指派';

$lang->my->featureBar['contribute']['story'] = $lang->my->featureBar['contribute']['requirement'];

$lang->my->featureBar['contribute']['testcase']['openedbyme'] = '我建的用例';

$lang->my->featureBar['contribute']['testtask']['done'] = '已测测试单';

$lang->my->featureBar['contribute']['audit']['reviewedbyme'] = '由我评审';
$lang->my->featureBar['contribute']['audit']['createdbyme']  = '由我发起';

$lang->my->featureBar['contribute']['doc']['openedbyme'] = '由我创建';
$lang->my->featureBar['contribute']['doc']['editedbyme'] = '由我编辑';

$lang->my->featureBar['score']['all'] = '我的积分';
