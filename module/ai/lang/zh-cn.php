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
$lang->prompt->name  = '名称';
$lang->prompt->desc  = '描述';
$lang->prompt->model = '语言模型';

$lang->ai->nextStep = '下一步';

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
$lang->ai->prompts->selectedFormat   = '已选对象为%s，已选 %d 条数据';
$lang->ai->prompts->nonSelected      = '暂无所选数据。';
$lang->ai->prompts->sortTip          = '可根据重要性给数据字段排序。';

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

$lang->ai->prompts->statuses = array();
$lang->ai->prompts->statuses[''] = '全部';
$lang->ai->prompts->statuses['draft'] = '草稿';

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
