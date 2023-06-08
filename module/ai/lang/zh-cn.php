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

$lang->ai->prompts = new stdclass();
$lang->ai->prompts->common    = '提词';
$lang->ai->prompts->emptyList = '暂时没有提词。';
$lang->ai->prompts->create    = '创建提词';

$lang->ai->prompts->statuses = array();
$lang->ai->prompts->statuses[''] = '全部';
// $lang->ai->prompts->statuses['draft'] = '草稿';

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
