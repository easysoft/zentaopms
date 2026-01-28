<?php
$config->admin->log = new stdclass();
$config->admin->log->saveDays = 30;

if(!isset($config->safe))       $config->safe = new stdclass();
if(!isset($config->safe->weak)) $config->safe->weak = '123456,password,12345,12345678,qwerty,123456789,1234,1234567,abc123,111111,123123';

$config->admin->menuGroup['system']        = array('custom|mode', 'backup', 'cron', 'action|trash', 'admin|xuanxuan', 'setting|xuanxuan', 'admin|license', 'admin|checkweak', 'admin|resetpwdsetting', 'admin|safe', 'cache|setting', 'custom|timezone', 'search|buildindex', 'admin|tableengine', 'admin|metriclib', 'ldap', 'custom|libreoffice', 'conference', 'watermark', 'client', 'system|browsebackup', 'system|restorebackup');
$config->admin->menuGroup['company']       = array('dept', 'company', 'user', 'group', 'tutorial');
$config->admin->menuGroup['switch']        = array('admin|setmodule');
$config->admin->menuGroup['feature']       = array('stage|settype', 'baseline', 'design', 'custom|flow', 'custom|code', 'custom|percent','custom|estimate', 'custom|hours', 'subject', 'holiday', 'reviewsetting', 'custom|project', 'custom|set', 'custom|product', 'custom|execution', 'custom|required', 'custom|kanban', 'measurement', 'meetingroom', 'custom|browsestoryconcept', 'custom|kanban', 'sqlbuilder', 'report', 'custom|limittaskdate', 'measurement');
$config->admin->menuGroup['message']       = array('mail', 'webhook', 'sms', 'message');
$config->admin->menuGroup['dev']           = array('dev', 'entry', 'editor');
$config->admin->menuGroup['extension']     = array('extension');
$config->admin->menuGroup['convert']       = array('convert');
$config->admin->menuGroup['adminregister'] = array('admin|register');

$config->admin->menuModuleGroup['feature']['custom|set']      = array('project', 'issue', 'risk', 'opportunity', 'nc', 'todo', 'block', 'epic', 'requirement', 'story', 'task', 'bug', 'testcase', 'testtask', 'feedback', 'user', 'ticket', 'projectchange');
$config->admin->menuModuleGroup['feature']['custom|required'] = array('project', 'build', 'bug', 'doc', 'product', 'epic', 'requirement', 'story', 'productplan', 'release', 'task', 'testcase', 'testsuite', 'testtask', 'testreport', 'caselib', 'doc', 'feedback', 'user', 'execution');
if($config->vision == 'lite')
{
    $config->admin->menuModuleGroup['feature']['custom|set']      = array('todo', 'block', 'task', 'story', 'user');
    $config->admin->menuModuleGroup['feature']['custom|required'] = array('project', 'task', 'story', 'doc', 'user');
}

$config->admin->plugins[203] = new stdClass();
$config->admin->plugins[203]->name     = '人力资源日历';
$config->admin->plugins[203]->abstract = '禅道人力资源日历插件可以帮助项目管理者查看不同维度下的消耗工时、未完成工作量、并行工作量及负载率，同时组织维度待处理状态下实现了模拟负载的功能，帮助管理者详细了解成员的工作负载情况，更好的进行项目人力资源调度。';
$config->admin->plugins[203]->viewLink = 'https://www.zentao.net/extension-viewExt-203.html';

$config->admin->plugins[198] = new stdClass();
$config->admin->plugins[198]->name     = '需求池插件';
$config->admin->plugins[198]->abstract = '本插件为禅道需求池管理插件，包括：创建需求池 需求池权限管理 收集需求并向需求池录入需求 记录需求提出人信息 评审需求 拆分用户需求、研发需求 需求跟踪矩阵';
$config->admin->plugins[198]->viewLink = 'https://www.zentao.net/extension-viewExt-198.html';

$config->admin->plugins[191] = new stdClass();
$config->admin->plugins[191]->name     = 'Bug归因';
$config->admin->plugins[191]->abstract = '通过Bug归因功能，可以确定Bug产生的根本原因，并追溯产生人员的责任比例，然后从根本上来解决问题。';
$config->admin->plugins[191]->viewLink = 'https://www.zentao.net/extension-viewExt-191.html';

$config->admin->plugins[250] = new stdClass();
$config->admin->plugins[250]->name     = '项目集甘特图';
$config->admin->plugins[250]->abstract = '禅道甘特图插件 运用此插件可以查看甘特图，维护任务关系。';
$config->admin->plugins[250]->viewLink = 'https://www.zentao.net/extension-viewExt-250.html';

$config->admin->plugins[196] = new stdClass();
$config->admin->plugins[196]->name     = '限制每天每人最大任务工时';
$config->admin->plugins[196]->abstract = '限制每天录入录入工时上限。';
$config->admin->plugins[196]->viewLink = 'https://www.zentao.net/extension-viewExt-196.html';

$config->admin->plugins[194] = new stdClass();
$config->admin->plugins[194]->name     = '应用巡检报告';
$config->admin->plugins[194]->abstract = '每日生成公司级禅道应用巡检报告，促进项目管理持续改进。';
$config->admin->plugins[194]->viewLink = 'https://www.zentao.net/extension-viewExt-194.html';

$config->admin->apiRoot        = 'https://www.zentao.net';
$config->admin->cdnRoot        = 'https://static.zentao.net/web';
$config->admin->classURL       = 'https://www.zentao.net/zentao.html';
$config->admin->dynamicURL     = 'https://www.zentao.net/dynamic.html';
$config->admin->downloadURL    = 'https://www.zentao.net/download.html';
$config->admin->extensionURL   = 'https://www.zentao.net/extension-browse.html';
$config->admin->patchURL       = 'https://www.zentao.net/extension-browse-1218.html';
$config->admin->apiSite        = 'https://api.zentao.net/';
$config->admin->extAPIURL      = $config->admin->apiSite . 'extension-apiGetExtensions-';
$config->admin->patchAPIURL    = $config->admin->apiSite . 'extension-apiGetExtensions-bymodule-MTIxOA==-0-100-1.json';
$config->admin->downloadAPIURL = $config->admin->apiSite . 'download.json';
$config->admin->dynamicAPIURL  = $config->admin->apiSite . 'dynamic-activities-%s.json';
$config->admin->videoAPIURL    = $config->admin->apiSite . 'zentao.json';
$config->admin->liteMenuList   = array('system', 'company', 'feature', 'message', 'extension', 'dev', 'workflow', 'approvalFlow');

$config->admin->helpURL['system']    = 'https://www.zentao.net/book/zentaopms/538.html';
$config->admin->helpURL['company']   = 'https://www.zentao.net/book/zentaopms/38.html';
$config->admin->helpURL['switch']    = 'https://www.zentao.net/book/zentaopms/38.html';
$config->admin->helpURL['model']     = 'https://www.zentao.net/book/zentaopms/533.html';
$config->admin->helpURL['feature']   = 'https://www.zentao.net/book/zentaopms/38.html';
$config->admin->helpURL['message']   = 'https://www.zentao.net/book/zentaopms/email-notification-541.html';
$config->admin->helpURL['extension'] = 'https://www.zentao.net/book/zentaopms/536.html';
$config->admin->helpURL['dev']       = 'https://www.zentao.net/book/zentaopms/537.html';
$config->admin->helpURL['convert']   = 'https://www.zentao.net/book/zentaopms/656.html';

$config->admin->navsGroup['feature']['my']        = ',todo,block,';
$config->admin->navsGroup['feature']['product']   = ',product,epic,requirement,story,productplan,release,';
$config->admin->navsGroup['feature']['project']   = ',project,build,issue,risk,opportunity,nc,projectchange,';
$config->admin->navsGroup['feature']['execution'] = ',execution,task,';
$config->admin->navsGroup['feature']['qa']        = ',bug,testcase,testsuite,testtask,testreport,caselib,';
if($config->vision == 'lite') $config->admin->navsGroup['feature']['my'] .= 'task,';

global $lang, $app;
$app->loadLang('user');
$config->admin->checkWeak = new stdclass();
$config->admin->checkWeak->actionList = array();
$config->admin->checkWeak->actionList['edit']['icon']        = 'edit';
$config->admin->checkWeak->actionList['edit']['text']        = $lang->user->edit;
$config->admin->checkWeak->actionList['edit']['hint']        = $lang->user->edit;
$config->admin->checkWeak->actionList['edit']['url']         = array('module' => 'user', 'method' => 'edit', 'params' => 'userID={id}');
