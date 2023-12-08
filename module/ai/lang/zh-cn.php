<?php
/**
 * The ai module zh-cn lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
$lang->ai->common = 'AI';

/* Definitions of table columns, used to sprintf error messages to dao::$errors. */
$lang->prompt = new stdclass();
$lang->prompt->name             = '名称';
$lang->prompt->desc             = '描述';
$lang->prompt->model            = '语言模型';
$lang->prompt->module           = '所属分组';
$lang->prompt->source           = '对象数据';
$lang->prompt->targetForm       = '目标表单';
$lang->prompt->purpose          = '操作';
$lang->prompt->elaboration      = '补充要求';
$lang->prompt->role             = '角色';
$lang->prompt->characterization = '角色描述';
$lang->prompt->status           = '阶段';
$lang->prompt->createdBy        = '由谁创建';
$lang->prompt->createdDate      = '创建时间';
$lang->prompt->editedBy         = '最后编辑';
$lang->prompt->editedDate       = '编辑时间';
$lang->prompt->deleted          = '是否已删除';

/* Lang for privs, keys are paired with privlang items. */
$lang->ai->modelBrowse            = '浏览语言模型';
$lang->ai->modelEdit              = '编辑语言模型';
$lang->ai->modelTestConnection    = '测试连接';
$lang->ai->promptCreate           = '创建提词';
$lang->ai->promptEdit             = '编辑提词';
$lang->ai->promptDelete           = '删除提词';
$lang->ai->promptAssignRole       = '指定角色';
$lang->ai->promptSelectDataSource = '选择对象';
$lang->ai->promptSetPurpose       = '确认操作';
$lang->ai->promptSetTargetForm    = '结果处理';
$lang->ai->promptFinalize         = '准备发布';
$lang->ai->promptAudit            = '调试提词';
$lang->ai->promptPublish          = '发布提词';
$lang->ai->promptUnpublish        = '取消发布提词';
$lang->ai->promptBrowse           = '浏览提词列表';
$lang->ai->promptView             = '查看提词详情';
$lang->ai->promptExecute          = '执行提词';
$lang->ai->promptExecutionReset   = '重置调试';
$lang->ai->roleTemplates          = '管理角色模板';
$lang->ai->chat                   = '聊天';

$lang->ai->chatPlaceholderMessage = 'Hi，我是 AI 助手阿道，您可以问我任何问题。';
$lang->ai->chatPlaceholderInput   = '问问阿道…';
$lang->ai->chatSystemMessage      = '你叫阿道，是禅道的 AI 助手兼吉祥物，你可以回答用户的问题和与用户聊天。你当前所处的环境是禅道项目管理软件。';
$lang->ai->chatSend               = '发送';
$lang->ai->chatReset              = '清空';
$lang->ai->chatNoResponse         = '会话发生了错误，<a id="retry" class="text-blue">点击这里重试</a>。';

$lang->ai->nextStep  = '下一步';
$lang->ai->goTesting = '去调试';

$lang->ai->validate = new stdclass();
$lang->ai->validate->noEmpty       = '%s不能为空。';
$lang->ai->validate->dirtyForm     = '%s的参数配置已变动，是否保存并返回？';
$lang->ai->validate->nameNotUnique = '该名称已使用，请尝试其他名称。';

$lang->ai->prompts = new stdclass();
$lang->ai->prompts->common       = '提词';
$lang->ai->prompts->emptyList    = '暂时没有提词。';
$lang->ai->prompts->create       = '创建提词';
$lang->ai->prompts->edit         = '编辑提词';
$lang->ai->prompts->id           = 'ID';
$lang->ai->prompts->name         = '名称';
$lang->ai->prompts->description  = '描述';
$lang->ai->prompts->createdBy    = '创建者';
$lang->ai->prompts->createdDate  = '创建时间';
$lang->ai->prompts->targetForm   = '表单';
$lang->ai->prompts->funcDesc     = '功能描述';
$lang->ai->prompts->deleted      = '已删除';
$lang->ai->prompts->stage        = '阶段';
$lang->ai->prompts->basicInfo    = '基本信息';
$lang->ai->prompts->editInfo     = '创建编辑';
$lang->ai->prompts->createdBy    = '由谁创建';
$lang->ai->prompts->publishedBy  = '由谁发布';
$lang->ai->prompts->draftedBy    = '由谁下架';
$lang->ai->prompts->lastEditor   = '最后编辑';
$lang->ai->prompts->modelNeutral = '通用';

$lang->ai->prompts->summary = '本页共 %s 个提词。';

$lang->ai->prompts->action = new stdclass();
$lang->ai->prompts->action->goDesignConfirm = '当前提词未完成，是否继续设计？';
$lang->ai->prompts->action->goDesign        = '去设计';
$lang->ai->prompts->action->draftConfirm    = '下架后，提词将不能继续使用，您确定要下架吗？';
$lang->ai->prompts->action->design          = '设计';
$lang->ai->prompts->action->test            = '调试';
$lang->ai->prompts->action->edit            = '编辑';
$lang->ai->prompts->action->publish         = '发布';
$lang->ai->prompts->action->unpublish       = '下架';
$lang->ai->prompts->action->delete          = '删除';
$lang->ai->prompts->action->deleteConfirm   = '删除后，提词将不能继续使用，您确定要删除吗？';
$lang->ai->prompts->action->publishSuccess  = '发布成功';

/* Steps of prompt creation. */
$lang->ai->prompts->assignRole       = '指定角色';
$lang->ai->prompts->selectDataSource = '选择对象';
$lang->ai->prompts->setPurpose       = '确认操作';
$lang->ai->prompts->setTargetForm    = '结果处理';
$lang->ai->prompts->finalize         = '准备发布';

/* Role assigning. */
$lang->ai->prompts->model               = '语言模型';
$lang->ai->prompts->role                = '角色';
$lang->ai->prompts->characterization    = '角色描述';
$lang->ai->prompts->rolePlaceholder     = '“你来扮演 <一个什么角色>”';
$lang->ai->prompts->charPlaceholder     = '该角色的具体描述信息';
$lang->ai->prompts->roleTemplate        = '角色模版';
$lang->ai->prompts->roleTemplateTip     = '引用模板后，修改角色、角色描述不会对模板造成影响。';
$lang->ai->prompts->addRoleTemplate     = '添加角色模板';
$lang->ai->prompts->editRoleTemplate    = '编辑角色模板';
$lang->ai->prompts->editRoleTemplateTip = '本次编辑不会影响已使用该模版的提词';
$lang->ai->prompts->roleAddedSuccess    = '角色模版保存成功';
$lang->ai->prompts->roleDelConfirm      = '删除不会影响已用角色模版的提词，是否删除？';
$lang->ai->prompts->roleDelSuccess      = '角色模板已删除';
$lang->ai->prompts->roleTemplateSave    = '存为角色模板';
$lang->ai->prompts->roleTemplateSaveList = array();
$lang->ai->prompts->roleTemplateSaveList['save']    = '保存';
$lang->ai->prompts->roleTemplateSaveList['discard'] = '不保存';

/* Data source selecting. */
$lang->ai->prompts->selectData       = '选择字段';
$lang->ai->prompts->selectDataTip    = '选择对象后，此处会展示已选对象的字段。';
$lang->ai->prompts->selectedFormat   = '已选对象为{0}，已选 {1} 条字段';
$lang->ai->prompts->nonSelected      = '暂无所选字段。';
$lang->ai->prompts->sortTip          = '可根据重要性给数据字段排序。';
$lang->ai->prompts->object           = '对象';
$lang->ai->prompts->field            = '字段';

/* Purpose setting. */
$lang->ai->prompts->purpose        = '操作';
$lang->ai->prompts->purposeTip     = '“我希望<它能完成什么事情，以便于达到什么样的目标>”';
$lang->ai->prompts->elaboration    = '补充要求';
$lang->ai->prompts->elaborationTip = '“我希望<它的回答请注意一些补充要求>”';
$lang->ai->prompts->inputPreview   = '输入预览';
$lang->ai->prompts->dataPreview    = '对象数据预览';
$lang->ai->prompts->rolePreview    = '角色提词预览';
$lang->ai->prompts->promptPreview  = '操作提词预览';

/* Target form selecting. */
$lang->ai->prompts->selectTargetForm    = '选择表单';
$lang->ai->prompts->selectTargetFormTip = '选择后，可以将大语言模型返回的结果直接录入到禅道对应的表单中。';
$lang->ai->prompts->goingTesting        = '即将跳转至调试页面';
$lang->ai->prompts->goingTestingFail    = '无法去调试，找不到合适的对象';

/* Finalize page. */
$lang->ai->moduleDisableTip = '系统根据所选对象自动关联分组';

/* Data source definition. */
$lang->ai->dataSource = array();

$lang->ai->dataSource['my']['common']          = '地盘';
$lang->ai->dataSource['product']['common']     = '产品';
$lang->ai->dataSource['story']['common']       = '需求';
$lang->ai->dataSource['productplan']['common'] = '计划';
$lang->ai->dataSource['release']['common']     = '发布';
$lang->ai->dataSource['project']['common']     = '项目';
$lang->ai->dataSource['execution']['common']   = '执行';
$lang->ai->dataSource['task']['common']        = '任务';
$lang->ai->dataSource['bug']['common']         = 'Bug';
$lang->ai->dataSource['case']['common']        = '用例';
$lang->ai->dataSource['doc']['common']         = '文档';

$lang->ai->dataSource['my']['efforts']['common']    = '日志列表';
$lang->ai->dataSource['my']['efforts']['date']      = '日期';
$lang->ai->dataSource['my']['efforts']['work']      = '工作内容';
$lang->ai->dataSource['my']['efforts']['account']   = '记录人';
$lang->ai->dataSource['my']['efforts']['consumed']  = '耗时';
$lang->ai->dataSource['my']['efforts']['left']      = '剩余';
$lang->ai->dataSource['my']['efforts']['objectID']  = '对象';
$lang->ai->dataSource['my']['efforts']['product']   = '产品';
$lang->ai->dataSource['my']['efforts']['project']   = '项目';
$lang->ai->dataSource['my']['efforts']['execution'] = '执行';

$lang->ai->dataSource['product']['product']['common']  = '产品';
$lang->ai->dataSource['product']['product']['name']    = '产品名称';
$lang->ai->dataSource['product']['product']['desc']    = '产品描述';
$lang->ai->dataSource['product']['modules']['common']  = '产品模块列表';
$lang->ai->dataSource['product']['modules']['name']    = '模块名称';
$lang->ai->dataSource['product']['modules']['modules'] = '子模块';

$lang->ai->dataSource['productplan']['productplan']['common'] = '计划';
$lang->ai->dataSource['productplan']['productplan']['title']  = '计划名称';
$lang->ai->dataSource['productplan']['productplan']['desc']   = '计划描述';
$lang->ai->dataSource['productplan']['productplan']['begin']  = '开始时间';
$lang->ai->dataSource['productplan']['productplan']['end']    = '结束时间';

$lang->ai->dataSource['productplan']['stories']['common']   = '需求列表';
$lang->ai->dataSource['productplan']['stories']['title']    = '需求名称';
$lang->ai->dataSource['productplan']['stories']['module']   = '所属模块';
$lang->ai->dataSource['productplan']['stories']['pri']      = '优先级';
$lang->ai->dataSource['productplan']['stories']['estimate'] = '预计故事点';
$lang->ai->dataSource['productplan']['stories']['status']   = '状态';
$lang->ai->dataSource['productplan']['stories']['stage']    = '阶段';

$lang->ai->dataSource['productplan']['bugs']['common'] = 'Bug列表';
$lang->ai->dataSource['productplan']['bugs']['title']  = 'Bug标题';
$lang->ai->dataSource['productplan']['bugs']['pri']    = '优先级';
$lang->ai->dataSource['productplan']['bugs']['status'] = '状态';

$lang->ai->dataSource['release']['release']['common']  = '发布';
$lang->ai->dataSource['release']['release']['product'] = '所属产品';
$lang->ai->dataSource['release']['release']['name']    = '发布名称';
$lang->ai->dataSource['release']['release']['desc']    = '发布描述';
$lang->ai->dataSource['release']['release']['date']    = '发布日期';

$lang->ai->dataSource['release']['stories']['common']   = '需求列表';
$lang->ai->dataSource['release']['stories']['title']    = '需求名称';
$lang->ai->dataSource['release']['stories']['estimate'] = '预估故事点';

$lang->ai->dataSource['release']['bugs']['common'] = 'Bug列表';
$lang->ai->dataSource['release']['bugs']['title']  = 'Bug标题';

$lang->ai->dataSource['project']['project']['common']   = '项目';
$lang->ai->dataSource['project']['project']['name']     = '项目名称';
$lang->ai->dataSource['project']['project']['type']     = '项目类型';
$lang->ai->dataSource['project']['project']['desc']     = '项目描述';
$lang->ai->dataSource['project']['project']['begin']    = '计划开始';
$lang->ai->dataSource['project']['project']['end']      = '计划结束';
$lang->ai->dataSource['project']['project']['estimate'] = '预计工时';

$lang->ai->dataSource['project']['programplans']['common']       = '阶段列表';
$lang->ai->dataSource['project']['programplans']['name']         = '阶段名称';
$lang->ai->dataSource['project']['programplans']['desc']         = '阶段描述';
$lang->ai->dataSource['project']['programplans']['status']       = '阶段状态';
$lang->ai->dataSource['project']['programplans']['begin']        = '计划开始';
$lang->ai->dataSource['project']['programplans']['end']          = '计划完成';
$lang->ai->dataSource['project']['programplans']['realBegan']    = '实际开始';
$lang->ai->dataSource['project']['programplans']['realEnd']      = '实际完成';
$lang->ai->dataSource['project']['programplans']['planDuration'] = '工期';
$lang->ai->dataSource['project']['programplans']['progress']     = '任务进度';
$lang->ai->dataSource['project']['programplans']['estimate']     = '预计工时';
$lang->ai->dataSource['project']['programplans']['consumed']     = '消耗工时';
$lang->ai->dataSource['project']['programplans']['left']         = '剩余工时';

$lang->ai->dataSource['project']['executions']['common']    = '迭代列表';
$lang->ai->dataSource['project']['executions']['name']      = '执行名称';
$lang->ai->dataSource['project']['executions']['desc']      = '执行描述';
$lang->ai->dataSource['project']['executions']['status']    = '执行状态';
$lang->ai->dataSource['project']['executions']['begin']     = '计划开始';
$lang->ai->dataSource['project']['executions']['end']       = '计划完成';
$lang->ai->dataSource['project']['executions']['realBegan'] = '实际开始';
$lang->ai->dataSource['project']['executions']['realEnd']   = '实际完成';
$lang->ai->dataSource['project']['executions']['estimate']  = '预计工时';
$lang->ai->dataSource['project']['executions']['consumed']  = '消耗工时';
$lang->ai->dataSource['project']['executions']['left']      = '剩余工时';
$lang->ai->dataSource['project']['executions']['progress']  = '进度';

$lang->ai->dataSource['story']['story']['common']   = '需求';
$lang->ai->dataSource['story']['story']['title']    = '需求标题';
$lang->ai->dataSource['story']['story']['spec']     = '需求描述';
$lang->ai->dataSource['story']['story']['verify']   = '验收标准';
$lang->ai->dataSource['story']['story']['product']  = '产品';
$lang->ai->dataSource['story']['story']['module']   = '模块';
$lang->ai->dataSource['story']['story']['pri']      = '优先级';
$lang->ai->dataSource['story']['story']['category'] = '需求类型';
$lang->ai->dataSource['story']['story']['estimate'] = '预计工时';

$lang->ai->dataSource['execution']['execution']['common']   = '执行';
$lang->ai->dataSource['execution']['execution']['name']     = '执行名称';
$lang->ai->dataSource['execution']['execution']['desc']     = '执行描述';
$lang->ai->dataSource['execution']['execution']['estimate'] = '预计工时';

$lang->ai->dataSource['execution']['tasks']['common']      = '任务列表';
$lang->ai->dataSource['execution']['tasks']['name']        = '任务名称';
$lang->ai->dataSource['execution']['tasks']['pri']         = '优先级';
$lang->ai->dataSource['execution']['tasks']['status']      = '状态';
$lang->ai->dataSource['execution']['tasks']['estimate']    = '预计工时';
$lang->ai->dataSource['execution']['tasks']['consumed']    = '已消耗';
$lang->ai->dataSource['execution']['tasks']['left']        = '剩余';
$lang->ai->dataSource['execution']['tasks']['progress']    = '进度';
$lang->ai->dataSource['execution']['tasks']['estStarted']  = '预计开始';
$lang->ai->dataSource['execution']['tasks']['realStarted'] = '实际开始';
$lang->ai->dataSource['execution']['tasks']['finishedDate']= '完成日期';
$lang->ai->dataSource['execution']['tasks']['closedReason']= '关闭原因';

$lang->ai->dataSource['task']['task']['common']      = '任务';
$lang->ai->dataSource['task']['task']['name']        = '任务名称';
$lang->ai->dataSource['task']['task']['desc']        = '任务描述';
$lang->ai->dataSource['task']['task']['pri']         = '优先级';
$lang->ai->dataSource['task']['task']['status']      = '状态';
$lang->ai->dataSource['task']['task']['estimate']    = '预计';
$lang->ai->dataSource['task']['task']['consumed']    = '消耗';
$lang->ai->dataSource['task']['task']['left']        = '剩余';
$lang->ai->dataSource['task']['task']['progress']    = '进度';
$lang->ai->dataSource['task']['task']['estStarted']  = '预计开始';
$lang->ai->dataSource['task']['task']['realStarted'] = '实际开始';

$lang->ai->dataSource['case']['case']['common']        = '用例';
$lang->ai->dataSource['case']['case']['title']         = '标题';
$lang->ai->dataSource['case']['case']['precondition']  = '前置条件';
$lang->ai->dataSource['case']['case']['scene']         = '所属场景';
$lang->ai->dataSource['case']['case']['product']       = '所属产品';
$lang->ai->dataSource['case']['case']['module']        = '所属模块';
$lang->ai->dataSource['case']['case']['pri']           = '优先级';
$lang->ai->dataSource['case']['case']['type']          = '类型';
$lang->ai->dataSource['case']['case']['lastRunResult'] = '结果';
$lang->ai->dataSource['case']['case']['status']        = '状态';

$lang->ai->dataSource['case']['steps']['common'] = '步骤列表';
$lang->ai->dataSource['case']['steps']['desc']   = '步骤描述';
$lang->ai->dataSource['case']['steps']['expect'] = '预期';

$lang->ai->dataSource['bug']['bug']['common']    = 'Bug';
$lang->ai->dataSource['bug']['bug']['title']     = 'Bug标题';
$lang->ai->dataSource['bug']['bug']['steps']     = '重现步骤';
$lang->ai->dataSource['bug']['bug']['severity']  = '级别';
$lang->ai->dataSource['bug']['bug']['pri']       = '优先级';
$lang->ai->dataSource['bug']['bug']['status']    = '状态';
$lang->ai->dataSource['bug']['bug']['confirmed'] = '确认';
$lang->ai->dataSource['bug']['bug']['type']      = 'Bug类型';

$lang->ai->dataSource['doc']['doc']['common']     = '文档';
$lang->ai->dataSource['doc']['doc']['title']      = '文档标题';
$lang->ai->dataSource['doc']['doc']['content']    = '文档正文';
$lang->ai->dataSource['doc']['doc']['addedBy']    = '创建者';
$lang->ai->dataSource['doc']['doc']['addedDate']  = '创建日期';
$lang->ai->dataSource['doc']['doc']['editedBy']   = '修改者';
$lang->ai->dataSource['doc']['doc']['editedDate'] = '修改日期';

/* Target form definition. See `$config->ai->targetForm`. */
$lang->ai->targetForm = array();
$lang->ai->targetForm['product']['common']        = '产品';
$lang->ai->targetForm['story']['common']          = '需求';
$lang->ai->targetForm['productplan']['common']    = '计划';
$lang->ai->targetForm['projectrelease']['common'] = '发布';
$lang->ai->targetForm['project']['common']        = '项目';
$lang->ai->targetForm['execution']['common']      = '执行';
$lang->ai->targetForm['task']['common']           = '任务';
$lang->ai->targetForm['testcase']['common']       = '用例';
$lang->ai->targetForm['bug']['common']            = 'Bug';
$lang->ai->targetForm['doc']['common']            = '文档';

$lang->ai->targetForm['product']['tree/managechild'] = '维护模块';
$lang->ai->targetForm['product']['doc/create']       = '创建文档';

$lang->ai->targetForm['story']['create']         = '提需求';
$lang->ai->targetForm['story']['batchcreate']    = '批量提需求';
$lang->ai->targetForm['story']['change']         = '变更需求';
$lang->ai->targetForm['story']['totask']         = '需求建任务';
$lang->ai->targetForm['story']['testcasecreate'] = '需求建用例';
$lang->ai->targetForm['story']['subdivide']      = '需求细分';

$lang->ai->targetForm['productplan']['edit']   = '编辑计划';
$lang->ai->targetForm['productplan']['create'] = '创建子计划';

$lang->ai->targetForm['projectrelease']['doc/create'] = '创建文档';

$lang->ai->targetForm['project']['risk/create']        = '创建风险';
$lang->ai->targetForm['project']['issue/create']       = '创建问题';
$lang->ai->targetForm['project']['doc/create']         = '创建文档';
$lang->ai->targetForm['project']['programplan/create'] = '设置阶段';

$lang->ai->targetForm['execution']['batchcreatetask']  = '批量创建任务';
$lang->ai->targetForm['execution']['createtestreport'] = '创建测试报告';
$lang->ai->targetForm['execution']['createqa']         = '创建 QA';
$lang->ai->targetForm['execution']['createrisk']       = '创建风险';
$lang->ai->targetForm['execution']['createissue']      = '创建问题';

$lang->ai->targetForm['task']['edit']        = '编辑任务';
$lang->ai->targetForm['task']['batchcreate'] = '批量创建子任务';

$lang->ai->targetForm['testcase']['edit']         = '编辑用例';
$lang->ai->targetForm['testcase']['createscript'] = '创建自动化脚本';

$lang->ai->targetForm['bug']['edit']            = '编辑 Bug';
$lang->ai->targetForm['bug']['story/create']    = 'Bug 转需求';
$lang->ai->targetForm['bug']['testcase/create'] = 'Bug 建用例';

$lang->ai->targetForm['doc']['create'] = '创建文档';
$lang->ai->targetForm['doc']['edit']   = '编辑文档';

$lang->ai->prompts->statuses = array();
$lang->ai->prompts->statuses['']       = '全部';
$lang->ai->prompts->statuses['draft']  = '未发布';
$lang->ai->prompts->statuses['active'] = '已发布';

$lang->ai->prompts->modules = array();
$lang->ai->prompts->modules['']            = '所有分组';
// $lang->ai->prompts->modules['my']          = '地盘';
$lang->ai->prompts->modules['product']     = '产品';
$lang->ai->prompts->modules['project']     = '项目';
$lang->ai->prompts->modules['story']       = '需求';
$lang->ai->prompts->modules['productplan'] = '计划';
$lang->ai->prompts->modules['release']     = '发布';
$lang->ai->prompts->modules['execution']   = '执行';
$lang->ai->prompts->modules['task']        = '任务';
$lang->ai->prompts->modules['case']        = '用例';
$lang->ai->prompts->modules['bug']         = 'Bug';
$lang->ai->prompts->modules['doc']         = '文档';

$lang->ai->conversations = new stdclass();
$lang->ai->conversations->common = '会话';

$lang->ai->models = new stdclass();
$lang->ai->models->title          = '语言模型配置';
$lang->ai->models->common         = '语言模型';
$lang->ai->models->type           = '语言模型';
$lang->ai->models->vendor         = '供应商';
$lang->ai->models->key            = 'API Key';
$lang->ai->models->secret         = 'Secret Key';
$lang->ai->models->resource       = 'Resource';
$lang->ai->models->deployment     = 'Deployment';
$lang->ai->models->proxyType      = '代理类型';
$lang->ai->models->proxyAddr      = '代理地址';
$lang->ai->models->description    = '描述';
$lang->ai->models->testConnection = '测试连接';
$lang->ai->models->unconfigured   = '未配置';
$lang->ai->models->edit           = '编辑模型参数';
$lang->ai->models->concealTip     = '完整信息在编辑时可见';
$lang->ai->models->upgradeBiz     = '更多AI功能，尽在<a target="_blank" href="https://www.zentao.net/page/enterprise.html" class="text-blue">企业版</a>！';
$lang->ai->models->noModelError   = '暂无可用的语言模型，请联系管理员配置。';

$lang->ai->models->testConnectionResult = new stdclass();
$lang->ai->models->testConnectionResult->success    = '连接成功';
$lang->ai->models->testConnectionResult->fail       = '连接失败';
$lang->ai->models->testConnectionResult->failFormat = '连接失败：%s';

$lang->ai->models->statusList = array();
$lang->ai->models->statusList['on']  = '启用';
$lang->ai->models->statusList['off'] = '停用';

$lang->ai->models->typeList = array();
$lang->ai->models->typeList['openai-gpt35'] = 'OpenAI / GPT-3.5';
$lang->ai->models->typeList['openai-gpt4']  = 'OpenAI / GPT-4';
$lang->ai->models->typeList['baidu-ernie']  = '百度 / 文心一言';

$lang->ai->models->vendorList = new stdclass();
$lang->ai->models->vendorList->{'openai-gpt35'} = array('openai' => 'OpenAI', 'azure' => 'Azure');
$lang->ai->models->vendorList->{'openai-gpt4'}  = array('openai' => 'OpenAI', 'azure' => 'Azure');
$lang->ai->models->vendorList->{'baidu-ernie'}  = array('baidu' => '百度千帆大模型平台');

$lang->ai->models->vendorTips = new stdclass();
$lang->ai->models->vendorTips->azure = 'Azure 中 OpenAI GPT 版本 (3.5 或 4) 需要在创建资源时指定。';

$lang->ai->models->proxyTypes = array();
$lang->ai->models->proxyTypes['']       = '不使用代理';
$lang->ai->models->proxyTypes['socks5'] = 'SOCKS5';

$lang->ai->models->promptFor = '输入给 %s';

$lang->ai->designStepNav = array();
$lang->ai->designStepNav['assignrole']       = '指定角色';
$lang->ai->designStepNav['selectdatasource'] = '选择对象';
$lang->ai->designStepNav['setpurpose']       = '确认操作';
$lang->ai->designStepNav['settargetform']    = '结果处理';
$lang->ai->designStepNav['finalize']         = '准备发布';

$lang->ai->dataTypeDesc = '%s是%s类型，%s';

$lang->ai->dataType            = new stdclass();
$lang->ai->dataType->pri       = new stdClass();
$lang->ai->dataType->pri->type = '数值';
$lang->ai->dataType->pri->desc = '1 是最高优先级，4 是最低优先级。';

$lang->ai->dataType->estimate       = new stdClass();
$lang->ai->dataType->estimate->type = '数值';
$lang->ai->dataType->estimate->desc = '单位为小时。';

$lang->ai->dataType->consumed = $lang->ai->dataType->estimate;
$lang->ai->dataType->left     = $lang->ai->dataType->estimate;

$lang->ai->dataType->progress       = new stdClass();
$lang->ai->dataType->progress->type = '百分比';
$lang->ai->dataType->progress->desc = '0 是未开始，100是已完成。';

$lang->ai->dataType->datetime       = new stdClass();
$lang->ai->dataType->datetime->type = '日期时间';
$lang->ai->dataType->datetime->desc = '格式为：1970-01-01 00:00:01，没有则留空。';

$lang->ai->dataType->estStarted   = $lang->ai->dataType->datetime;
$lang->ai->dataType->realStarted  = $lang->ai->dataType->datetime;
$lang->ai->dataType->finishedDate = $lang->ai->dataType->datetime;

$lang->ai->demoData            = new stdclass();
$lang->ai->demoData->notExist  = '暂无演示数据。';
$lang->ai->demoData->story     = array(
    'story' => array
    (
        'title'    => '开发一个在线学习平台',
        'spec'     => '我们需要开发一个在线学习平台，能够提供课程管理、学生管理、教师管理等功能。',
        'verify' => '1. 所有功能均能够正常运行，没有明显的错误和异常。2. 界面美观、易用性好。3. 平台能够满足用户需求，具有较高的用户满意度。4. 代码质量好，结构清晰、易于维护。',
        'module'   => 7,
        'pri'      => 1,
        'estimate' => 1,
        'product'  => 1,
        'category' => 'feature',
    ),
);
$lang->ai->demoData->execution = array
(
    'execution' => array(
        'name'     => '在线学习平台软件开发',
        'desc'     => '本计划旨在开发一款在线学习平台软件，该软件将提供可访问的学习资源，包括文本、视频和音频等，以及一些学习工具如考试、测试和讨论论坛等。',
        'estimate' => 7,
    ),
    'tasks'     => array(
        0 =>
            array(
                'name'         => '技术选型',
                'pri'          => 1,
                'status'       => 'done',
                'estimate'     => 1,
                'consumed'     => 1,
                'left'         => 0,
                'progress'     => 100,
                'estStarted'   => '2023-07-02 00:00:00',
                'realStarted'  => '2023-07-02 00:00:00',
                'finishedDate' => '2023-07-02 00:00:00',
                'closedReason' => '已完成',
            ),
        1 =>
            array(
                'name'         => 'UI设计',
                'pri'          => 1,
                'status'       => 'doing',
                'estimate'     => 2,
                'consumed'     => 1,
                'left'         => 1,
                'progress'     => 50,
                'estStarted'   => '2023-07-03 00:00:00',
                'realStarted'  => '2023-07-03 00:00:00',
                'finishedDate' => '',
                'closedReason' => '',
            ),
        2 =>
            array(
                'name'         => '开发',
                'pri'          => 1,
                'status'       => 'wait',
                'estimate'     => 1,
                'consumed'     => 0,
                'left'         => 1,
                'progress'     => 0,
                'estStarted'   => '',
                'realStarted'  => '',
                'finishedDate' => '',
                'closedReason' => '',
            ),
    ),
);

/* Forms as JSON Schemas. */
$lang->ai->formSchema = array();
$lang->ai->formSchema['story']['create'] = new stdclass();
$lang->ai->formSchema['story']['create']->title = '需求';
$lang->ai->formSchema['story']['create']->type  = 'object';
$lang->ai->formSchema['story']['create']->properties = new stdclass();
$lang->ai->formSchema['story']['create']->properties->title  = new stdclass();
$lang->ai->formSchema['story']['create']->properties->spec   = new stdclass();
$lang->ai->formSchema['story']['create']->properties->verify = new stdclass();
$lang->ai->formSchema['story']['create']->properties->title->type         = 'string';
$lang->ai->formSchema['story']['create']->properties->title->description  = '需求的标题';
$lang->ai->formSchema['story']['create']->properties->spec->type          = 'string';
$lang->ai->formSchema['story']['create']->properties->spec->description   = '需求的描述';
$lang->ai->formSchema['story']['create']->properties->verify->type        = 'string';
$lang->ai->formSchema['story']['create']->properties->verify->description = '需求的验收标准';
$lang->ai->formSchema['story']['create']->required = array('title', 'spec', 'verify');
$lang->ai->formSchema['story']['change'] = $lang->ai->formSchema['story']['create'];

$lang->ai->formSchema['story']['batchcreate'] = new stdclass();
$lang->ai->formSchema['story']['batchcreate']->title = '批量创建需求';
$lang->ai->formSchema['story']['batchcreate']->type  = 'object';
$lang->ai->formSchema['story']['batchcreate']->properties = new stdclass();
$lang->ai->formSchema['story']['batchcreate']->properties->stories  = new stdclass();
$lang->ai->formSchema['story']['batchcreate']->properties->stories->type        = 'array';
$lang->ai->formSchema['story']['batchcreate']->properties->stories->description = '需求列表';
$lang->ai->formSchema['story']['batchcreate']->properties->stories->items       = $lang->ai->formSchema['story']['create'];

$lang->ai->formSchema['productplan']['create'] = new stdclass();
$lang->ai->formSchema['productplan']['create']->title = '产品计划';
$lang->ai->formSchema['productplan']['create']->type  = 'object';
$lang->ai->formSchema['productplan']['create']->properties = new stdclass();
$lang->ai->formSchema['productplan']['create']->properties->title  = new stdclass();
$lang->ai->formSchema['productplan']['create']->properties->begin  = new stdclass();
$lang->ai->formSchema['productplan']['create']->properties->end    = new stdclass();
$lang->ai->formSchema['productplan']['create']->properties->desc   = new stdclass();
$lang->ai->formSchema['productplan']['create']->properties->title->type         = 'string';
$lang->ai->formSchema['productplan']['create']->properties->title->description  = '产品计划的标题';
$lang->ai->formSchema['productplan']['create']->properties->begin->type         = 'string';
$lang->ai->formSchema['productplan']['create']->properties->begin->description  = '产品计划的开始时间';
$lang->ai->formSchema['productplan']['create']->properties->end->type           = 'string';
$lang->ai->formSchema['productplan']['create']->properties->end->description    = '产品计划的结束时间';
$lang->ai->formSchema['productplan']['create']->properties->desc->type          = 'string';
$lang->ai->formSchema['productplan']['create']->properties->desc->description   = '产品计划的描述';
$lang->ai->formSchema['productplan']['create']->required = array('title', 'begin', 'end');
$lang->ai->formSchema['productplan']['edit'] = $lang->ai->formSchema['productplan']['create'];

$lang->ai->formSchema['task']['create'] = new stdclass();
$lang->ai->formSchema['task']['create']->title = '任务';
$lang->ai->formSchema['task']['create']->type  = 'object';
$lang->ai->formSchema['task']['create']->properties = new stdclass();
$lang->ai->formSchema['task']['create']->properties->type     = new stdclass();
$lang->ai->formSchema['task']['create']->properties->name     = new stdclass();
$lang->ai->formSchema['task']['create']->properties->desc     = new stdclass();
$lang->ai->formSchema['task']['create']->properties->pri      = new stdclass();
$lang->ai->formSchema['task']['create']->properties->estimate = new stdclass();
$lang->ai->formSchema['task']['create']->properties->begin    = new stdclass();
$lang->ai->formSchema['task']['create']->properties->end      = new stdclass();
$lang->ai->formSchema['task']['create']->properties->type->type            = 'string';
$lang->ai->formSchema['task']['create']->properties->type->description     = '任务的类型';
$lang->ai->formSchema['task']['create']->properties->type->enum            = array('design', 'devel', 'request', 'test', 'study', 'discuss', 'ui', 'affair', 'misc');
$lang->ai->formSchema['task']['create']->properties->name->type            = 'string';
$lang->ai->formSchema['task']['create']->properties->name->description     = '任务的名称';
$lang->ai->formSchema['task']['create']->properties->desc->type            = 'string';
$lang->ai->formSchema['task']['create']->properties->desc->description     = '任务的描述';
$lang->ai->formSchema['task']['create']->properties->pri->type             = 'string';
$lang->ai->formSchema['task']['create']->properties->pri->description      = '任务的优先级';
$lang->ai->formSchema['task']['create']->properties->pri->enum             = array('1', '2', '3', '4');
$lang->ai->formSchema['task']['create']->properties->estimate->type        = 'number';
$lang->ai->formSchema['task']['create']->properties->estimate->description = '任务的预计工时';
$lang->ai->formSchema['task']['create']->properties->begin->type           = 'string';
$lang->ai->formSchema['task']['create']->properties->begin->format         = 'date';
$lang->ai->formSchema['task']['create']->properties->begin->description    = '任务的预计开始日期';
$lang->ai->formSchema['task']['create']->properties->end->type             = 'string';
$lang->ai->formSchema['task']['create']->properties->end->format           = 'date';
$lang->ai->formSchema['task']['create']->properties->end->description      = '任务的预计结束日期';
$lang->ai->formSchema['task']['create']->required = array('type', 'name');
$lang->ai->formSchema['task']['edit'] = $lang->ai->formSchema['task']['create'];

$lang->ai->formSchema['task']['batchcreate'] = new stdclass();
$lang->ai->formSchema['task']['batchcreate']->title = '批量创建任务';
$lang->ai->formSchema['task']['batchcreate']->type  = 'object';
$lang->ai->formSchema['task']['batchcreate']->properties = new stdclass();
$lang->ai->formSchema['task']['batchcreate']->properties->tasks  = new stdclass();
$lang->ai->formSchema['task']['batchcreate']->properties->tasks->type                          = 'array';
$lang->ai->formSchema['task']['batchcreate']->properties->tasks->description                   = '任务列表';
$lang->ai->formSchema['task']['batchcreate']->properties->tasks->items                         = $lang->ai->formSchema['task']['create'];
$lang->ai->formSchema['task']['batchcreate']->properties->tasks->items->properties->estStarted = clone $lang->ai->formSchema['task']['batchcreate']->properties->tasks->items->properties->begin;
$lang->ai->formSchema['task']['batchcreate']->properties->tasks->items->properties->deadline   = clone $lang->ai->formSchema['task']['batchcreate']->properties->tasks->items->properties->end;
unset($lang->ai->formSchema['task']['batchcreate']->properties->tasks->items->properties->begin);
unset($lang->ai->formSchema['task']['batchcreate']->properties->tasks->items->properties->end);

$lang->ai->formSchema['bug']['create'] = new stdclass();
$lang->ai->formSchema['bug']['create']->title = 'Bug';
$lang->ai->formSchema['bug']['create']->type  = 'object';
$lang->ai->formSchema['bug']['create']->properties = new stdclass();
$lang->ai->formSchema['bug']['create']->properties->title       = new stdclass();
$lang->ai->formSchema['bug']['create']->properties->steps       = new stdclass();
$lang->ai->formSchema['bug']['create']->properties->severity    = new stdclass();
$lang->ai->formSchema['bug']['create']->properties->pri         = new stdclass();
$lang->ai->formSchema['bug']['create']->properties->openedBuild = new stdclass();
$lang->ai->formSchema['bug']['create']->properties->title->type              = 'string';
$lang->ai->formSchema['bug']['create']->properties->title->description       = 'Bug 的标题';
$lang->ai->formSchema['bug']['create']->properties->steps->type              = 'string';
$lang->ai->formSchema['bug']['create']->properties->steps->description       = '重现步骤';
$lang->ai->formSchema['bug']['create']->properties->severity->type           = 'string';
$lang->ai->formSchema['bug']['create']->properties->severity->description    = 'Bug 的严重程度';
$lang->ai->formSchema['bug']['create']->properties->severity->enum           = array('1', '2', '3', '4');
$lang->ai->formSchema['bug']['create']->properties->pri->type                = 'string';
$lang->ai->formSchema['bug']['create']->properties->pri->description         = 'Bug 的优先级';
$lang->ai->formSchema['bug']['create']->properties->pri->enum                = array('1', '2', '3', '4');
$lang->ai->formSchema['bug']['create']->properties->openedBuild->type        = 'string';
$lang->ai->formSchema['bug']['create']->properties->openedBuild->description = 'Bug 影响的版本';
$lang->ai->formSchema['bug']['create']->properties->openedBuild->enum        = array('trunk');
$lang->ai->formSchema['bug']['create']->required = array('title', 'steps', 'severity', 'pri', 'openedBuild');
$lang->ai->formSchema['bug']['edit'] = $lang->ai->formSchema['bug']['create'];

$lang->ai->formSchema['testcase']['create'] = new stdclass();
$lang->ai->formSchema['testcase']['create']->title = '用例';
$lang->ai->formSchema['testcase']['create']->type  = 'object';
$lang->ai->formSchema['testcase']['create']->properties = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->type         = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->stage        = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->title        = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->precondition = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->steps        = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->steps->items              = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->steps->items->properties  = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->steps->items->properties->steps   = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->steps->items->properties->expects = new stdclass();
$lang->ai->formSchema['testcase']['create']->properties->type->type                                     = 'string';
$lang->ai->formSchema['testcase']['create']->properties->type->description                              = '用例的类型';
$lang->ai->formSchema['testcase']['create']->properties->type->enum                                     = array('feature', 'performance', 'config', 'install', 'security', 'interface', 'unit', 'other');
$lang->ai->formSchema['testcase']['create']->properties->stage->type                                    = 'string';
$lang->ai->formSchema['testcase']['create']->properties->stage->description                             = '用例适用阶段';
$lang->ai->formSchema['testcase']['create']->properties->stage->enum                                    = array('unittest', 'feature', 'intergrate', 'system', 'smoke', 'bvt');
$lang->ai->formSchema['testcase']['create']->properties->title->type                                    = 'string';
$lang->ai->formSchema['testcase']['create']->properties->title->description                             = '用例的标题';
$lang->ai->formSchema['testcase']['create']->properties->precondition->type                             = 'string';
$lang->ai->formSchema['testcase']['create']->properties->precondition->description                      = '用例的前置条件';
$lang->ai->formSchema['testcase']['create']->properties->steps->type                                    = 'array';
$lang->ai->formSchema['testcase']['create']->properties->steps->description                             = '用例的步骤列表';
$lang->ai->formSchema['testcase']['create']->properties->steps->items->type                             = 'object';
$lang->ai->formSchema['testcase']['create']->properties->steps->items->properties->steps->type          = 'string';
$lang->ai->formSchema['testcase']['create']->properties->steps->items->properties->steps->description   = '步骤的描述';
$lang->ai->formSchema['testcase']['create']->properties->steps->items->properties->expects->type        = 'string';
$lang->ai->formSchema['testcase']['create']->properties->steps->items->properties->expects->description = '步骤的预期结果';
$lang->ai->formSchema['testcase']['create']->required = array('type', 'title', 'steps');
$lang->ai->formSchema['testcase']['edit'] = $lang->ai->formSchema['testcase']['create'];

$lang->ai->formSchema['testreport']['create'] = new stdclass();
$lang->ai->formSchema['testreport']['create']->title = '测试报告';
$lang->ai->formSchema['testreport']['create']->type  = 'object';
$lang->ai->formSchema['testreport']['create']->properties = new stdclass();
$lang->ai->formSchema['testreport']['create']->properties->begin  = new stdclass();
$lang->ai->formSchema['testreport']['create']->properties->end    = new stdclass();
$lang->ai->formSchema['testreport']['create']->properties->title  = new stdclass();
$lang->ai->formSchema['testreport']['create']->properties->report = new stdclass();
$lang->ai->formSchema['testreport']['create']->properties->begin->type         = 'string';
$lang->ai->formSchema['testreport']['create']->properties->begin->format       = 'date';
$lang->ai->formSchema['testreport']['create']->properties->begin->description  = '测试开始时间';
$lang->ai->formSchema['testreport']['create']->properties->end->type           = 'string';
$lang->ai->formSchema['testreport']['create']->properties->end->format         = 'date';
$lang->ai->formSchema['testreport']['create']->properties->end->description    = '测试开始时间';
$lang->ai->formSchema['testreport']['create']->properties->title->type         = 'string';
$lang->ai->formSchema['testreport']['create']->properties->title->description  = '测试报告的标题';
$lang->ai->formSchema['testreport']['create']->properties->report->type        = 'string';
$lang->ai->formSchema['testreport']['create']->properties->report->description = '测试报告的内容';
$lang->ai->formSchema['testreport']['create']->required = array('begin', 'end', 'title', 'report');
$lang->ai->formSchema['execution']['testreport'] = $lang->ai->formSchema['testreport']['create'];

$lang->ai->formSchema['doc']['edit'] = new stdclass();
$lang->ai->formSchema['doc']['edit']->title = '文档';
$lang->ai->formSchema['doc']['edit']->type  = 'object';
$lang->ai->formSchema['doc']['edit']->properties = new stdclass();
$lang->ai->formSchema['doc']['edit']->properties->title   = new stdclass();
$lang->ai->formSchema['doc']['edit']->properties->content = new stdclass();
$lang->ai->formSchema['doc']['edit']->properties->title->type          = 'string';
$lang->ai->formSchema['doc']['edit']->properties->title->description   = '文档的标题';
$lang->ai->formSchema['doc']['edit']->properties->content->type        = 'string';
$lang->ai->formSchema['doc']['edit']->properties->content->description = '文档的正文';
$lang->ai->formSchema['doc']['edit']->required = array('title', 'content');

$lang->ai->formSchema['tree']['browse'] = new stdclass();
$lang->ai->formSchema['tree']['browse']->title = '模块';
$lang->ai->formSchema['tree']['browse']->type  = 'object';
$lang->ai->formSchema['tree']['browse']->properties = new stdclass();
$lang->ai->formSchema['tree']['browse']->properties->modules = new stdclass();
$lang->ai->formSchema['tree']['browse']->properties->modules->type  = 'array';
$lang->ai->formSchema['tree']['browse']->properties->modules->title = '模块';
$lang->ai->formSchema['tree']['browse']->properties->modules->items = new stdclass();
$lang->ai->formSchema['tree']['browse']->properties->modules->items->type = 'string';
$lang->ai->formSchema['tree']['browse']->required = array('modules');

$lang->ai->formSchema['programplan']['create'] = new stdclass();
$lang->ai->formSchema['programplan']['create']->title = '计划阶段';
$lang->ai->formSchema['programplan']['create']->type  = 'object';
$lang->ai->formSchema['programplan']['create']->properties = new stdclass();
$lang->ai->formSchema['programplan']['create']->properties->stages = new stdclass();
$lang->ai->formSchema['programplan']['create']->properties->stages->type  = 'array';
$lang->ai->formSchema['programplan']['create']->properties->stages->title = '阶段列表';
$lang->ai->formSchema['programplan']['create']->properties->stages->items = new stdclass();
$lang->ai->formSchema['programplan']['create']->properties->stages->items->type = 'object';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties = new stdclass();
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->names      = new stdclass();
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->attributes = new stdclass();
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->milestone  = new stdclass();
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->begin      = new stdclass();
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->end        = new stdclass();
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->names->type             = 'string';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->names->description      = '阶段名称';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->attributes->type        = 'string';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->attributes->description = '阶段类型';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->attributes->enum        = array('request', 'design', 'dev', 'qa', 'release', 'review', 'other');
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->milestone->type         = 'boolean';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->milestone->description  = '是否为里程碑';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->begin->type             = 'string';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->begin->format           = 'date';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->begin->description      = '阶段开始时间';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->end->type               = 'string';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->end->format             = 'date';
$lang->ai->formSchema['programplan']['create']->properties->stages->items->properties->end->description        = '阶段结束时间';
$lang->ai->formSchema['programplan']['create']->required = array('stages');

$lang->ai->promptMenu = new stdclass();
$lang->ai->promptMenu->dropdownTitle = 'AI';

$lang->ai->dataInject = new stdclass();
$lang->ai->dataInject->success = '已将提词执行结果填写到表单中';
$lang->ai->dataInject->fail    = '提词执行结果填写失败';

$lang->ai->execute = new stdclass();
$lang->ai->execute->loading    = '提词执行中';
$lang->ai->execute->auditing   = '即将跳转至调试页面并执行提词';
$lang->ai->execute->success    = '提词执行成功';
$lang->ai->execute->fail       = '提词执行失败';
$lang->ai->execute->failFormat = '提词执行失败：%s。';
$lang->ai->execute->failReasons = array();
$lang->ai->execute->failReasons['noPrompt']     = '提词不存在';
$lang->ai->execute->failReasons['noObjectData'] = '对象数据获取失败';
$lang->ai->execute->failReasons['noResponse']   = '请求返回值为空';
$lang->ai->execute->failReasons['noTargetForm'] = '目标表单地址获取失败，或表单必要变量获取失败（可能原因为无法找到关联的对象，请检查对象间的关联关系）';
$lang->ai->execute->executeErrors = array();
$lang->ai->execute->executeErrors['-1'] = '提词不存在';
$lang->ai->execute->executeErrors['-2'] = '对象数据获取失败';
$lang->ai->execute->executeErrors['-3'] = '序列化对象数据失败';
$lang->ai->execute->executeErrors['-4'] = '表单结构获取失败';
$lang->ai->execute->executeErrors['-5'] = 'API 返回值为空或返回了错误';

$lang->ai->audit = new stdclass();
$lang->ai->audit->designPrompt = '提词设计';
$lang->ai->audit->afterSave    = '保存后';
$lang->ai->audit->regenerate   = '重新生成';
$lang->ai->audit->exit         = '退出调试';

$lang->ai->audit->backLocationList = array();
$lang->ai->audit->backLocationList[0] = '返回调试页面';
$lang->ai->audit->backLocationList[1] = '返回调试页面并重新生成';

$lang->ai->engineeredPrompts = new stdclass();
$lang->ai->engineeredPrompts->askForFunctionCalling = array((object)array('role' => 'user', 'content' => '请把我所发的下一条消息内容转换为 function 调用。'), (object)array('role' => 'assistant', 'content' => '好的，我会把下一条消息转换为 function 调用。'));

$lang->ai->aiResponseException = array();
$lang->ai->aiResponseException['notFunctionCalling'] = 'AI 提词执行返回值结构不正确，请重试（可能可以通过优化提词来解决）';
