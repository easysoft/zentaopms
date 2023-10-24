<?php
global $config;

$lang->custom->common               = '自定义';
$lang->custom->id                   = '编号';
$lang->custom->set                  = '自定义配置';
$lang->custom->restore              = '恢复默认';
$lang->custom->key                  = '键';
$lang->custom->value                = '值';
$lang->custom->working              = '工作方式';
$lang->custom->hours                = '工时';
$lang->custom->select               = '请选择流程：';
$lang->custom->branch               = '多分支';
$lang->custom->owner                = '所有者';
$lang->custom->module               = '模块';
$lang->custom->section              = '附加部分';
$lang->custom->lang                 = '所属语言';
$lang->custom->setPublic            = '设为公共';
$lang->custom->required             = '必填项';
$lang->custom->score                = '积分';
$lang->custom->timezone             = '时区';
$lang->custom->scoreReset           = '重置积分';
$lang->custom->scoreTitle           = '积分功能';
$lang->custom->productName          = $lang->productCommon;
$lang->custom->convertFactor        = '换算系数';
$lang->custom->region               = '区间';
$lang->custom->tips                 = '提示语';
$lang->custom->setTips              = '设置提示语';
$lang->custom->isRange              = '是否目标控制范围';
$lang->custom->concept              = "{$lang->projectCommon}概念";
$lang->custom->URStory              = "用户需求";
$lang->custom->SRStory              = "软件需求";
$lang->custom->epic                 = "史诗";
$lang->custom->default              = "默认";
$lang->custom->scrumStory           = "故事";
$lang->custom->waterfallCommon      = "瀑布";
$lang->custom->buildin              = "系统内置";
$lang->custom->editStoryConcept     = "编辑需求概念";
$lang->custom->setStoryConcept      = "设置需求概念";
$lang->custom->setDefaultConcept    = "设置默认概念";
$lang->custom->browseStoryConcept   = "需求概念列表";
$lang->custom->deleteStoryConcept   = "删除需求概念";
$lang->custom->URConcept            = "用需概念";
$lang->custom->SRConcept            = "软需概念";
$lang->custom->reviewRule           = "评审规则";
$lang->custom->switch               = "切换";
$lang->custom->oneUnit              = "一个{$lang->hourCommon}";
$lang->custom->convertRelationTitle = "请先设置{$lang->hourCommon}转换为%s的换算系数";
$lang->custom->superReviewers       = "超级评审人";
$lang->custom->kanban               = "看板";
$lang->custom->allUsers             = '所有人员';
$lang->custom->account              = '人员';
$lang->custom->role                 = '职位';
$lang->custom->dept                 = '部门';
$lang->custom->code                 = $lang->code;
$lang->custom->setCode              = '是否启用代号';
$lang->custom->executionCommon      = '执行';
$lang->custom->selectDefaultProgram = '请选择一个默认项目集';
$lang->custom->defaultProgram       = '默认项目集';
$lang->custom->modeManagement       = '模式管理';
$lang->custom->percent              = $lang->stage->percent;
$lang->custom->setPercent           = "是否启用{$lang->stage->percent}";
$lang->custom->beginAndEndDate      = '起止日期';
$lang->custom->beginAndEndDateRange = '起止日期范围';
$lang->custom->limitTaskDateAction  = '设置起止日期必填';

$lang->custom->unitList['efficiency'] = '工时/';
$lang->custom->unitList['manhour']    = '人时/';
$lang->custom->unitList['cost']       = '元/小时';
$lang->custom->unitList['hours']      = '小时';
$lang->custom->unitList['days']       = '天';
$lang->custom->unitList['loc']        = 'KLOC';

$lang->custom->tipProgressList['SPI'] = "{$lang->projectCommon}进度绩效(SPI)";
$lang->custom->tipProgressList['SV']  = '进度偏差率(SV%)';

$lang->custom->tipCostList['CPI'] = "{$lang->projectCommon}成本绩效(CPI)";
$lang->custom->tipCostList['CV']  = '成本偏差率(CV%)';

$lang->custom->tipRangeList[0]  = '否';
$lang->custom->tipRangeList[1]  = '是';

$lang->custom->regionMustNumber    = '区间必须是数字';
$lang->custom->tipNotEmpty         = '提示语不能为空';
$lang->custom->currencyNotEmpty    = '至少选择一种货币';
$lang->custom->defaultNotEmpty     = '默认货币不能为空';
$lang->custom->convertRelationTips = "{$lang->hourCommon}转换为%s后，历史数据将被统一转换为%s";
$lang->custom->saveTips            = '点击保存后，则以当前%s为默认估算单位';

$lang->custom->numberError = '区间必须大于零';

$lang->custom->closedExecution = '已关闭' . $lang->custom->executionCommon;
$lang->custom->closedKanban    = '已关闭' . $lang->custom->kanban;
$lang->custom->closedProduct   = '已关闭' . $lang->productCommon;

$lang->custom->block = new stdclass();
$lang->custom->block->fields['closed'] = '关闭的区块';

$lang->custom->project = new stdClass();
$lang->custom->project->currencySetting    = '货币设置';
$lang->custom->project->defaultCurrency    = '默认货币';
$lang->custom->project->fields['required'] = $lang->custom->required;
$lang->custom->project->fields['unitList'] = '预算单位';

$lang->custom->execution = new stdClass();
$lang->custom->execution->fields['required']  = $lang->custom->required;
$lang->custom->execution->fields['execution'] = '关闭设置';

$lang->custom->product = new stdClass();
$lang->custom->product->fields['required']           = $lang->custom->required;
$lang->custom->product->fields['browsestoryconcept'] = '需求概念';
$lang->custom->product->fields['product']            = '关闭设置';

$lang->custom->story = new stdClass();
$lang->custom->story->fields['required']         = $lang->custom->required;
$lang->custom->story->fields['categoryList']     = '类型';
$lang->custom->story->fields['priList']          = '优先级';
$lang->custom->story->fields['sourceList']       = '来源';
$lang->custom->story->fields['reasonList']       = '关闭原因';
$lang->custom->story->fields['stageList']        = '阶段';
$lang->custom->story->fields['statusList']       = '状态';
$lang->custom->story->fields['reviewRules']      = '评审规则';
$lang->custom->story->fields['reviewResultList'] = '评审结果';
$lang->custom->story->fields['review']           = '评审流程';

$lang->custom->task = new stdClass();
$lang->custom->task->fields['required']      = $lang->custom->required;
$lang->custom->task->fields['priList']       = '优先级';
$lang->custom->task->fields['typeList']      = '类型';
$lang->custom->task->fields['reasonList']    = '关闭原因';
$lang->custom->task->fields['statusList']    = '状态';
$lang->custom->task->fields['limitTaskDate'] = '起止日期';

$lang->custom->bug = new stdClass();
$lang->custom->bug->fields['required']       = $lang->custom->required;
$lang->custom->bug->fields['priList']        = '优先级';
$lang->custom->bug->fields['severityList']   = '严重程度';
$lang->custom->bug->fields['osList']         = '操作系统';
$lang->custom->bug->fields['browserList']    = '浏览器';
$lang->custom->bug->fields['typeList']       = '类型';
$lang->custom->bug->fields['resolutionList'] = '解决方案';
$lang->custom->bug->fields['statusList']     = '状态';
$lang->custom->bug->fields['longlife']       = '久未处理天数';

$lang->custom->testcase = new stdClass();
$lang->custom->testcase->fields['required']   = $lang->custom->required;
$lang->custom->testcase->fields['priList']    = '优先级';
$lang->custom->testcase->fields['typeList']   = '类型';
$lang->custom->testcase->fields['stageList']  = '阶段';
$lang->custom->testcase->fields['resultList'] = '执行结果';
$lang->custom->testcase->fields['statusList'] = '状态';
$lang->custom->testcase->fields['review']     = '评审流程';

$lang->custom->testtask = new stdClass();
$lang->custom->testtask->fields['required']   = $lang->custom->required;
$lang->custom->testtask->fields['statusList'] = '状态';
$lang->custom->testtask->fields['typeList']   = '测试类型';
$lang->custom->testtask->fields['priList']    = '优先级';

$lang->custom->testreport = new stdClass();
$lang->custom->testreport->fields['required'] = $lang->custom->required;

$lang->custom->caselib = new stdClass();
$lang->custom->caselib->fields['required'] = $lang->custom->required;

$lang->custom->todo = new stdClass();
$lang->custom->todo->fields['priList']    = '优先级';
$lang->custom->todo->fields['typeList']   = '类型';
$lang->custom->todo->fields['statusList'] = '状态';

$lang->custom->user = new stdClass();
$lang->custom->user->fields['required']     = $lang->custom->required;
$lang->custom->user->fields['roleList']     = '职位';
$lang->custom->user->fields['statusList']   = '状态';
$lang->custom->user->fields['contactField'] = '可用联系方式';
$lang->custom->user->fields['deleted']      = '列出已删除用户';

$lang->custom->currentLang = '适用当前语言';
$lang->custom->allLang     = '适用所有语言';

$lang->custom->confirmRestore = '是否要恢复默认配置？';

$lang->custom->notice = new stdclass();
$lang->custom->notice->userFieldNotice     = '控制以上字段在用户相关页面是否显示，留空则全部显示';
$lang->custom->notice->canNotAdd           = '该项参与运算，不提供自定义添加功能';
$lang->custom->notice->forceReview         = "指定人提交的%s必须评审。";
$lang->custom->notice->forceNotReview      = "指定人提交的%s不需要评审。";
$lang->custom->notice->longlife            = 'Bug列表页面的久未处理标签中，列出设置天数之前未处理的Bug。';
$lang->custom->notice->invalidNumberKey    = '键值应为不大于255的数字';
$lang->custom->notice->invalidStringKey    = '键值应当为大小写英文字母、数字或下划线的组合';
$lang->custom->notice->cannotSetTimezone   = 'date_default_timezone_set方法不存在或禁用，不能设置时区。';
$lang->custom->notice->noClosedBlock       = '没有永久关闭的区块';
$lang->custom->notice->required            = '页面提交时，选中的字段必填';
$lang->custom->notice->conceptResult       = '我们已经根据您的选择为您设置了<b> %s-%s </b>模式，使用<b>%s</b> + <b> %s</b>。';
$lang->custom->notice->conceptPath         = '您可以在：后台 -> 自定义 -> 流程页面修改。';
$lang->custom->notice->readOnlyOfProduct   = '禁止修改后，已关闭' . $lang->productCommon . '下的' . $lang->SRCommon . '、Bug、用例、日志、发布、计划、版本都禁止修改。';
$lang->custom->notice->readOnlyOfExecution = "禁止修改后，已关闭{$lang->custom->executionCommon}下的任务、版本、日志以及关联需求都禁止修改。";
$lang->custom->notice->readOnlyOfKanban    = "禁止修改后，已关闭{$lang->custom->kanban}下的卡片以及相关设置都禁止修改。";
$lang->custom->notice->URSREmpty           = '自定义需求名称不能为空！';
$lang->custom->notice->valueEmpty          = '值不能为空！';
$lang->custom->notice->confirmDelete       = '您确定要删除吗？';
$lang->custom->notice->confirmReviewCase   = '是否将待评审的用例修改为正常状态？';
$lang->custom->notice->storyReviewTip      = '按人员、职位、部门勾选后，取所有人员的并集。';
$lang->custom->notice->selectAllTip        = '勾选所有人员后，会清空并置灰评审人员，同时隐藏职位、部门。';
$lang->custom->notice->repeatKey           = '%s键重复';
$lang->custom->notice->readOnlyOfCode      = "代号是一种管理话术，主要便于保密或作为别名存在。启用代号管理后，系统中的{$lang->productCommon}、{$lang->projectCommon}、执行在创建、编辑、详情、列表等页面均会展示代号信息。";
$lang->custom->notice->readOnlyOfPercent   = "工作量占比用于划分{$lang->projectCommon}中存在多个阶段时的工作量的占比，同一级阶段的百分比之和最高为100%。启用工作量占比后，系统中的瀑布{$lang->projectCommon}和融合瀑布{$lang->projectCommon}模型中设置阶段时需要维护阶段的工作量占比。";

$lang->custom->notice->indexPage['product'] = "从8.2版本起增加了产品主页视图，是否默认进入产品主页？";
$lang->custom->notice->indexPage['project'] = "从8.2版本起增加了{$lang->projectCommon}主页视图，是否默认进入{$lang->projectCommon}主页？";
$lang->custom->notice->indexPage['qa']      = "从8.2版本起增加了测试主页视图，是否默认进入测试主页？";

$lang->custom->notice->invalidStrlen['ten']        = '键的长度必须小于10个字符！';
$lang->custom->notice->invalidStrlen['fifteen']    = '键的长度必须小于15个字符！';
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

$lang->custom->setHours       = '工时设置';
$lang->custom->setWeekend     = '休息日设置';
$lang->custom->setHoliday     = '节假日设置';
$lang->custom->workingHours   = '每天可用工时';
$lang->custom->weekendRole    = '规则设置';
$lang->custom->weekendList[1] = '单休';
$lang->custom->weekendList[2] = '双休';
$lang->custom->restDayList[6] = '周六休息';
$lang->custom->restDayList[0] = '周天休息';

global $config;
$lang->custom->sprintConceptList[0] = "项目 产品 迭代";
$lang->custom->sprintConceptList[1] = "项目 产品 冲刺";

$lang->custom->workingList['full'] = '完整研发管理工具';

$lang->custom->menuTip           = '点击显示或隐藏导航条目，拖拽来更改显示顺序。';
$lang->custom->saveFail          = '保存失败！';
$lang->custom->page              = '页面';
$lang->custom->usage             = '使用场景';
$lang->custom->selectUsage       = '请选择使用模式';
$lang->custom->useLight          = '使用轻量管理模式';
$lang->custom->useALM            = '使用全生命周期管理模式';
$lang->custom->currentModeTips   = '您当前使用的是%s, 您可以切换到%s';
$lang->custom->changeModeTips    = '您确定要切换到%s吗？';
$lang->custom->selectProgramTips = "切换到轻量管理模式后，为确保数据结构一致，需要选择一个项目集作为默认项目集，后续新增的{$lang->productCommon}和{$lang->projectCommon}数据都关联在这个默认的项目集下。";

$lang->custom->modeList['light'] = '轻量级管理模式';
$lang->custom->modeList['ALM']   = '全生命周期管理模式';
$lang->custom->modeList['PLM']   = 'IPD集成产品开发模式';

$lang->custom->modeIntroductionList['light'] = "提供了{$lang->projectCommon}管理的核心功能，适用于小型研发团队";
$lang->custom->modeIntroductionList['ALM']   = '概念更加完整、严谨，功能更加丰富，适用于中大型研发团队';

$lang->custom->features['program']              = '项目集';
$lang->custom->features['productRR']            = "{$lang->productCommon}-研发需求";
$lang->custom->features['productUR']            = "{$lang->productCommon}-用户需求";
$lang->custom->features['productLine']          = "{$lang->productCommon}-产品线";
$lang->custom->features['projectScrum']         = "{$lang->projectCommon}-敏捷模型";
$lang->custom->features['projectWaterfall']     = "{$lang->projectCommon}-瀑布模型";
$lang->custom->features['projectKanban']        = "{$lang->projectCommon}-看板模型";
$lang->custom->features['projectAgileplus']     = "{$lang->projectCommon}-融合敏捷模型";
$lang->custom->features['projectWaterfallplus'] = "{$lang->projectCommon}-融合瀑布模型";
$lang->custom->features['execution']            = '执行';
$lang->custom->features['qa']                   = '测试';
$lang->custom->features['devops']               = 'DevOps';
$lang->custom->features['kanban']               = '看板';
$lang->custom->features['doc']                  = '文档';
$lang->custom->features['report']               = $lang->report->common;
$lang->custom->features['system']               = '组织';
$lang->custom->features['assetlib']             = '资产库';
$lang->custom->features['oa']                   = '办公';
$lang->custom->features['ops']                  = '运维';
$lang->custom->features['feedback']             = '反馈';
$lang->custom->features['traincourse']          = '学堂';
$lang->custom->features['workflow']             = '工作流';
$lang->custom->features['admin']                = '后台';
$lang->custom->features['vision']               = '研发综合界面、运营管理界面';

$lang->custom->needClosedFunctions['waterfall']     = "瀑布{$lang->projectCommon}";
$lang->custom->needClosedFunctions['waterfallplus'] = "融合瀑布{$lang->projectCommon}";
$lang->custom->needClosedFunctions['URStory']       = '用户需求';
if($config->edition == 'max') $lang->custom->needClosedFunctions['assetLib'] = '资产库';

$lang->custom->scoreStatus[1] = '开启';
$lang->custom->scoreStatus[0] = '关闭';

$lang->custom->CRProduct[1] = '允许修改';
$lang->custom->CRProduct[0] = '禁止修改';

$lang->custom->CRExecution[1] = '允许修改';
$lang->custom->CRExecution[0] = '禁止修改';

$lang->custom->CRKanban[1] = '允许修改';
$lang->custom->CRKanban[0] = '禁止修改';

$lang->custom->moduleName['product']     = $lang->productCommon;
$lang->custom->moduleName['productplan'] = '计划';
$lang->custom->moduleName['execution']   = $lang->custom->executionCommon;

$lang->custom->conceptQuestions['overview']   = "下述哪种组合方式更适合您公司的管理现状？";
$lang->custom->conceptQuestions['URAndSR']    = "是否启用{$lang->URCommon}和{$lang->SRCommon}概念？";
$lang->custom->conceptQuestions['storypoint'] = "您公司是在使用以下哪种单位来做规模估算？";

$lang->custom->conceptOptions             = new stdclass;
$lang->custom->conceptOptions->story      = array();
$lang->custom->conceptOptions->story['0'] = '需求';
$lang->custom->conceptOptions->story['1'] = '故事';

$lang->custom->conceptOptions->URAndSR = array();
$lang->custom->conceptOptions->URAndSR['1'] = '是';
$lang->custom->conceptOptions->URAndSR['0'] = '否';

$lang->custom->conceptOptions->hourPoint      = array();
$lang->custom->conceptOptions->hourPoint['0'] = '工时';
$lang->custom->conceptOptions->hourPoint['1'] = '故事点';
$lang->custom->conceptOptions->hourPoint['2'] = '功能点';

$lang->custom->scrum = new stdclass();
$lang->custom->scrum->setConcept = "设置{$lang->projectCommon}概念";

$lang->custom->reviewRules['allpass']  = '全部通过通过';
$lang->custom->reviewRules['halfpass'] = '半数以上通过通过';

$lang->custom->limitTaskDate['0'] = '不限制';
$lang->custom->limitTaskDate['1'] = '限定在所属执行起止日期范围内';
