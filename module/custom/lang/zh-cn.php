<?php
$lang->custom->common     = '自定义';
$lang->custom->index      = '首页';
$lang->custom->set        = '自定义配置';
$lang->custom->restore    = '恢复默认';
$lang->custom->key        = '键';
$lang->custom->value      = '值';
$lang->custom->flow       = '流程';
$lang->custom->working    = '工作方式';
$lang->custom->select     = '请选择流程：';
$lang->custom->branch     = '多分支';
$lang->custom->owner      = '所有者';
$lang->custom->module     = '模块';
$lang->custom->section    = '附加部分';
$lang->custom->lang       = '所属语言';
$lang->custom->setPublic  = '设为公共';
$lang->custom->required   = '必填项';
$lang->custom->score      = '积分';
$lang->custom->timezone   = '时区';
$lang->custom->scoreReset = '重置积分';
$lang->custom->scoreTitle = '积分功能';

$lang->custom->object['story']    = $lang->storyCommon;
$lang->custom->object['task']     = '任务';
$lang->custom->object['bug']      = 'Bug';
$lang->custom->object['testcase'] = '用例';
$lang->custom->object['testtask'] = '版本';
$lang->custom->object['todo']     = '待办';
$lang->custom->object['user']     = '用户';
$lang->custom->object['block']    = '区块';

$lang->custom->story = new stdClass();
$lang->custom->story->fields['priList']          = '优先级';
$lang->custom->story->fields['sourceList']       = '来源';
$lang->custom->story->fields['reasonList']       = '关闭原因';
$lang->custom->story->fields['stageList']        = '阶段';
$lang->custom->story->fields['statusList']       = '状态';
$lang->custom->story->fields['reviewResultList'] = '评审结果';
$lang->custom->story->fields['review']           = '评审流程';

$lang->custom->task = new stdClass();
$lang->custom->task->fields['priList']    = '优先级';
$lang->custom->task->fields['typeList']   = '类型';
$lang->custom->task->fields['reasonList'] = '关闭原因';
$lang->custom->task->fields['statusList'] = '状态';
$lang->custom->task->fields['hours']      = '工时';

$lang->custom->bug = new stdClass();
$lang->custom->bug->fields['priList']        = '优先级';
$lang->custom->bug->fields['severityList']   = '严重程度';
$lang->custom->bug->fields['osList']         = '操作系统';
$lang->custom->bug->fields['browserList']    = '浏览器';
$lang->custom->bug->fields['typeList']       = '类型';
$lang->custom->bug->fields['resolutionList'] = '解决方案';
$lang->custom->bug->fields['statusList']     = '状态';
$lang->custom->bug->fields['longlife']       = '久未处理天数';

$lang->custom->testcase = new stdClass();
$lang->custom->testcase->fields['priList']    = '优先级';
$lang->custom->testcase->fields['typeList']   = '类型';
$lang->custom->testcase->fields['stageList']  = '阶段';
$lang->custom->testcase->fields['resultList'] = '执行结果';
$lang->custom->testcase->fields['statusList'] = '状态';
$lang->custom->testcase->fields['review']     = '评审流程';

$lang->custom->testtask = new stdClass();
$lang->custom->testtask->fields['priList']    = '优先级';
$lang->custom->testtask->fields['statusList'] = '状态';

$lang->custom->todo = new stdClass();
$lang->custom->todo->fields['priList']    = '优先级';
$lang->custom->todo->fields['typeList']   = '类型';
$lang->custom->todo->fields['statusList'] = '状态';

$lang->custom->user = new stdClass();
$lang->custom->user->fields['roleList']     = '职位';
$lang->custom->user->fields['statusList']   = '状态';
$lang->custom->user->fields['contactField'] = '可用联系方式';
$lang->custom->user->fields['deleted']      = '列出已删除用户';

$lang->custom->system = array('flow', 'working', 'required', 'score');

$lang->custom->block->fields['closed'] = '关闭的区块';

$lang->custom->currentLang = '适用当前语言';
$lang->custom->allLang     = '适用所有语言';

$lang->custom->confirmRestore = '是否要恢复默认配置？';

$lang->custom->notice = new stdclass();
$lang->custom->notice->userFieldNotice             = '控制以上字段在用户相关页面是否显示，留空则全部显示';
$lang->custom->notice->canNotAdd                   = '该项参与运算，不提供自定义添加功能';
$lang->custom->notice->forceReview                 = "指定人提交的%s必须评审。";
$lang->custom->notice->forceNotReview              = "指定人提交的%s不需要评审。";
$lang->custom->notice->longlife                    = 'Bug列表页面的久未处理标签中，列出设置天数之前未处理的Bug。';
$lang->custom->notice->invalidNumberKey            = '键值应为不大于255的数字';
$lang->custom->notice->invalidStringKey            = '键值应当为小写英文字母、数字或下划线的组合';
$lang->custom->notice->cannotSetTimezone           = 'date_default_timezone_set方法不存在或禁用，不能设置时区。';
$lang->custom->notice->noClosedBlock               = '没有永久关闭的区块';
$lang->custom->notice->required                    = '页面提交时，选中的字段必填';
$lang->custom->notice->conceptResult               = '我们已经根据您的选择为您设置了<b> %s-%s </b>模式，使用<b>%s</b> + <b> %s</b>。';
$lang->custom->notice->conceptPath                 = '您可以在：后台 -> 自定义 -> 流程页面修改。';

$lang->custom->notice->indexPage['product']        = "从8.2版本起增加了产品主页视图，是否默认进入产品主页？";
$lang->custom->notice->indexPage['project']        = "从8.2版本起增加了项目主页视图，是否默认进入项目主页？";
$lang->custom->notice->indexPage['qa']             = "从8.2版本起增加了测试主页视图，是否默认进入测试主页？";

$lang->custom->notice->invalidStrlen['ten']        = '键的长度必须小于10个字符！';
$lang->custom->notice->invalidStrlen['twenty']     = '键的长度必须小于20个字符！';
$lang->custom->notice->invalidStrlen['thirty']     = '键的长度必须小于30个字符！';
$lang->custom->notice->invalidStrlen['twoHundred'] = '键的长度必须小于225个字符！';

$lang->custom->storyReview    = '评审流程';
$lang->custom->forceReview    = '强制评审';
$lang->custom->forceNotReview = '不需要评审';
$lang->custom->reviewList[1]  = '开启';
$lang->custom->reviewList[0]  = '关闭';

$lang->custom->deletedList[1] = '列出';
$lang->custom->deletedList[0] = '不列出';

$lang->custom->workingHours   = '每天可用工时';
$lang->custom->weekend        = '休息日';
$lang->custom->weekendList[2] = '双休';
$lang->custom->weekendList[1] = '单休';

$lang->custom->productProject = new stdclass();
$lang->custom->productProject->relation['0_0'] = '产品 - 项目';
$lang->custom->productProject->relation['0_1'] = '产品 - 迭代';
$lang->custom->productProject->relation['1_1'] = '项目 - 迭代';
$lang->custom->productProject->relation['0_2'] = '产品 - 冲刺';
$lang->custom->productProject->relation['1_2'] = '项目 - 冲刺';

$lang->custom->productProject->notice = '请根据实际情况选择适合自己团队的概念。';

$lang->custom->workingList['full']      = '完整研发管理工具';
$lang->custom->workingList['onlyTest']  = '测试管理工具';
$lang->custom->workingList['onlyStory'] = "{$lang->storyCommon}管理工具";
$lang->custom->workingList['onlyTask']  = '任务管理工具';

$lang->custom->menuTip  = '点击显示或隐藏导航条目，拖拽来更改显示顺序。';
$lang->custom->saveFail = '保存失败！';
$lang->custom->page     = '页面';

$lang->custom->scoreStatus[1] = '开启';
$lang->custom->scoreStatus[0] = '关闭';

$lang->custom->moduleName['product']     = $lang->productCommon;
$lang->custom->moduleName['productplan'] = '计划';
$lang->custom->moduleName['project']     = $lang->projectCommon;

$lang->custom->conceptQuestions['overview']         = "1. 下述哪种组合方式更适合您公司的管理现状？";
$lang->custom->conceptQuestions['story']            = "2. 您公司是在使用需求概念还是用户故事概念？";
$lang->custom->conceptQuestions['requirementpoint'] = "3. 您公司是在使用工时还是功能点来做规模估算？";
$lang->custom->conceptQuestions['storypoint']       = "3. 您公司是在使用工时还是故事点来做规模估算？";

$lang->custom->conceptOptions = new stdclass;

$lang->custom->conceptOptions->story = array();
$lang->custom->conceptOptions->story['0'] = '需求';
$lang->custom->conceptOptions->story['1'] = '故事';

$lang->custom->conceptOptions->hourPoint = array();
$lang->custom->conceptOptions->hourPoint['0'] = '工时';
$lang->custom->conceptOptions->hourPoint['1'] = '故事点';
$lang->custom->conceptOptions->hourPoint['2'] = '功能点';
