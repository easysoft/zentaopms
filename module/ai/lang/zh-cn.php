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
$lang->prompt->name   = '名称';
$lang->prompt->desc   = '描述';
$lang->prompt->model  = '语言模型';
$lang->prompt->module = '所属分组';

$lang->ai->nextStep  = '下一步';
$lang->ai->goTesting = '去调试';

$lang->ai->validate = new stdclass();
$lang->ai->validate->noEmpty       = '%s不能为空。';
$lang->ai->validate->dirtyForm     = '%s的参数配置已变动，是否保存并返回？';
$lang->ai->validate->nameNotUnique = '该名称已使用，请尝试其他名称。';

$lang->ai->prompts = new stdclass();
$lang->ai->prompts->common      = '提词';
$lang->ai->prompts->emptyList   = '暂时没有提词。';
$lang->ai->prompts->create      = '创建提词';
$lang->ai->prompts->id          = 'ID';
$lang->ai->prompts->name        = '名称';
$lang->ai->prompts->description = '描述';
$lang->ai->prompts->createdBy   = '创建者';
$lang->ai->prompts->createdDate = '创建时间';
$lang->ai->prompts->targetForm  = '表单';
$lang->ai->prompts->funcDesc    = '功能描述';
$lang->ai->prompts->deleted     = '已删除';

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
$lang->ai->prompts->assignModel      = '指定语言模型';
$lang->ai->prompts->model            = '语言模型';
$lang->ai->prompts->role             = '角色';
$lang->ai->prompts->characterization = '角色描述';
$lang->ai->prompts->rolePlaceholder  = '“你来扮演 <一个什么角色>”';
$lang->ai->prompts->charPlaceholder  = '该角色的具体描述信息';

/* Data source selecting. */
$lang->ai->prompts->selectData       = '选择数据';
$lang->ai->prompts->selectDataTip    = '选择对象后，此处会展示已选对象的数据。';
$lang->ai->prompts->selectedFormat   = '已选对象为{0}，已选 {1} 条数据';
$lang->ai->prompts->nonSelected      = '暂无所选数据。';
$lang->ai->prompts->sortTip          = '可根据重要性给数据字段排序。';
$lang->ai->prompts->object           = '对象';
$lang->ai->prompts->field            = '数据';

/* Purpose setting. */
$lang->ai->prompts->purpose        = '操作';
$lang->ai->prompts->purposeTip     = '';
$lang->ai->prompts->elaboration    = '补充要求';
$lang->ai->prompts->elaborationTip = '';
$lang->ai->prompts->inputPreview   = '输入预览';
$lang->ai->prompts->dataPreview    = '对象数据预览';
$lang->ai->prompts->rolePreview    = '角色提词预览';
$lang->ai->prompts->promptPreview  = '操作提词预览';

/* Target form selecting. */
$lang->ai->prompts->selectTargetForm    = '选择表单';
$lang->ai->prompts->selectTargetFormTip = '选择后，可以将大语言模型返回的结果直接录入到禅道对应的表单中。';
$lang->ai->prompts->goingTesting        = '正在跳转';
$lang->ai->prompts->goingTestingFail    = '无法去调试，找不到合适的对象';

/* Finalize page. */
$lang->ai->moduleDisableTip = '系统根据所选对象自动关联分组';

/* Data source definition. */
$lang->ai->dataSource = array();
$lang->ai->dataSource['story']['common']     = '需求';
$lang->ai->dataSource['execution']['common'] = '执行';

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

/* Target form definition. */
$lang->ai->targetForm = array();
$lang->ai->targetForm['story']['common']     = '需求';
$lang->ai->targetForm['execution']['common'] = '执行';

$lang->ai->targetForm['story']['create']         = '提需求';
$lang->ai->targetForm['story']['batchcreate']    = '批量提需求';
$lang->ai->targetForm['story']['change']         = '变更需求';
$lang->ai->targetForm['story']['totask']         = '需求建任务';
$lang->ai->targetForm['story']['testcasecreate'] = '需求建用例';
$lang->ai->targetForm['story']['subdivide']      = '需求细分';

$lang->ai->targetForm['execution']['batchcreatetask']  = '批量创建任务';
$lang->ai->targetForm['execution']['createtestreport'] = '创建测试报告';
$lang->ai->targetForm['execution']['createqa']         = '创建 QA';
$lang->ai->targetForm['execution']['createrisk']       = '创建风险';
$lang->ai->targetForm['execution']['createissue']      = '创建问题';

$lang->ai->prompts->statuses = array();
$lang->ai->prompts->statuses['']       = '全部';
$lang->ai->prompts->statuses['draft']  = '未发布';
$lang->ai->prompts->statuses['active'] = '已发布';

$lang->ai->prompts->modules = array();
$lang->ai->prompts->modules['']            = '所有分组';
$lang->ai->prompts->modules['my']          = '地盘';
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
$lang->ai->models->apiKey         = 'API Key';
$lang->ai->models->proxyType      = '代理类型';
$lang->ai->models->proxyAddr      = '代理地址';
$lang->ai->models->description    = '描述';
$lang->ai->models->testConnection = '测试连接';
$lang->ai->models->unconfigured   = '未配置';
$lang->ai->models->edit           = '编辑模型参数';
$lang->ai->models->concealTip     = '完整信息在编辑时可见';

$lang->ai->models->testConnectionResult = new stdclass();
$lang->ai->models->testConnectionResult->success = '连接成功';
$lang->ai->models->testConnectionResult->fail    = '连接失败';

$lang->ai->models->statusList = array();
$lang->ai->models->statusList['on']  = '启用';
$lang->ai->models->statusList['off'] = '停用';

$lang->ai->models->typeList = array();
$lang->ai->models->typeList['openai-gpt35'] = 'OpenAI / GPT-3.5';
// $lang->ai->models->typeList['azure-gpt35']  = 'Azure / GPT-3.5';

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
$lang->ai->formSchema['story']['change'] = new stdclass();
$lang->ai->formSchema['story']['change']->title = '需求';
$lang->ai->formSchema['story']['change']->type  = 'object';
$lang->ai->formSchema['story']['change']->properties = new stdclass();
$lang->ai->formSchema['story']['change']->properties->title  = new stdclass();
$lang->ai->formSchema['story']['change']->properties->spec   = new stdclass();
$lang->ai->formSchema['story']['change']->properties->verify = new stdclass();
$lang->ai->formSchema['story']['change']->properties->title->type         = 'string';
$lang->ai->formSchema['story']['change']->properties->title->description  = '需求的标题';
$lang->ai->formSchema['story']['change']->properties->spec->type          = 'string';
$lang->ai->formSchema['story']['change']->properties->spec->description   = '需求的描述';
$lang->ai->formSchema['story']['change']->properties->verify->type        = 'string';
$lang->ai->formSchema['story']['change']->properties->verify->description = '需求的验收标准';
$lang->ai->formSchema['story']['change']->required = array('title', 'spec', 'verify');

$lang->ai->promptMenu = new stdclass();
$lang->ai->promptMenu->dropdownTitle = 'AI';

$lang->ai->dataInject = new stdclass();
$lang->ai->dataInject->success = '已将提词执行结果填写到表单中';
$lang->ai->dataInject->fail    = '提词执行结果填写失败';

$lang->ai->execute = new stdclass();
$lang->ai->execute->success = '提词执行成功';
$lang->ai->execute->fail    = '提词执行失败';

$lang->ai->audit = new stdclass();
$lang->ai->audit->designPrompt = '提词设计';
$lang->ai->audit->afterSave    = '保存后';
$lang->ai->audit->regenerate   = '重新生成';

$lang->ai->audit->backLocationList = array();
$lang->ai->audit->backLocationList[0] = '返回调试页面';
$lang->ai->audit->backLocationList[1] = '返回调试页面并重新生成';
