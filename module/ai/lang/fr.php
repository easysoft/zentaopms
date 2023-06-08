<?php
/**
 * The ai module fr lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
$lang->ai->common = 'AI';

$lang->ai->prompts = new stdclass();
$lang->ai->prompts->common    = 'Prompts';
$lang->ai->prompts->emptyList = 'There is no prompts yet.';
$lang->ai->prompts->create    = 'Create a prompt';

$lang->ai->prompts->statuses = array();
$lang->ai->prompts->statuses[''] = 'All';
// $lang->ai->prompts->statuses['draft'] = 'Draft';

$lang->ai->prompts->modules = array();
$lang->ai->prompts->modules['']            = 'All';
$lang->ai->prompts->modules['my']          = 'My';
$lang->ai->prompts->modules['product']     = 'Product';
$lang->ai->prompts->modules['project']     = 'Project';
$lang->ai->prompts->modules['story']       = 'Story';
$lang->ai->prompts->modules['productplan'] = 'Product Plan';
$lang->ai->prompts->modules['release']     = 'Release';
$lang->ai->prompts->modules['execution']   = 'Execution';
$lang->ai->prompts->modules['task']        = 'Task';
$lang->ai->prompts->modules['case']        = 'Test Case';
$lang->ai->prompts->modules['bug']         = 'Bug';
$lang->ai->prompts->modules['doc']         = 'Document';

$lang->ai->conversations = new stdclass();
$lang->ai->conversations->common = 'Conversations';

$lang->ai->models = new stdclass();
$lang->ai->models->title          = 'Language Model Configuration';
$lang->ai->models->common         = 'Language Model';
$lang->ai->models->type           = 'Model';
$lang->ai->models->apiKey         = 'API Key';
$lang->ai->models->proxyType      = 'Proxy Type';
$lang->ai->models->proxyAddr      = 'Proxy Address';
$lang->ai->models->description    = 'Description';
$lang->ai->models->testConnection = 'Test Connection';
$lang->ai->models->unconfigured   = 'Unconfigured';
$lang->ai->models->edit           = 'Edit Parameters';
$lang->ai->models->concealTip     = 'Visible when editing';

$lang->ai->models->testConnectionResult = new stdclass();
$lang->ai->models->testConnectionResult->success = 'Successfully connected';
$lang->ai->models->testConnectionResult->fail    = 'Failed to connect';

$lang->ai->models->statusList = array();
$lang->ai->models->statusList['on']  = 'Enable';
$lang->ai->models->statusList['off'] = 'Disable';

$lang->ai->models->typeList = array();
$lang->ai->models->typeList['openai-gpt35'] = 'OpenAI / GPT-3.5';
// $lang->ai->models->typeList['azure-gpt35']  = 'Azure / GPT-3.5';

$lang->ai->models->proxyTypes = array();
$lang->ai->models->proxyTypes['']       = 'No Proxy';
$lang->ai->models->proxyTypes['socks5'] = 'SOCKS5';
