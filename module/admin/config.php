<?php
$config->admin->log = new stdclass();
$config->admin->log->saveDays = 30;

if(!isset($config->safe))       $config->safe = new stdclass();
if(!isset($config->safe->weak)) $config->safe->weak = '123456,password,12345,12345678,qwerty,123456789,1234,1234567,abc123,111111,123123';

$config->admin->menuGroup['system']    = array('custom|mode', 'backup', 'cron', 'action|trash', 'admin|xuanxuan', 'setting|xuanxuan', 'admin|license', 'admin|checkweak', 'admin|resetpwdsetting', 'admin|safe', 'custom|timezone', 'search|buildindex', 'admin|tableengine', 'ldap', 'custom|libreoffice', 'conference', 'client', 'system|browsebackup', 'system|restorebackup');
$config->admin->menuGroup['company']   = array('dept', 'company', 'user', 'group', 'tutorial');
$config->admin->menuGroup['switch']    = array('admin|setmodule');
$config->admin->menuGroup['model']     = array('auditcl', 'stage', 'design', 'cmcl', 'reviewcl', 'custom|required', 'custom|set', 'custom|flow', 'custom|code', 'custom|percent','custom|estimate', 'custom|hours', 'subject', 'process', 'activity', 'zoutput', 'classify', 'holiday', 'reviewsetting');
$config->admin->menuGroup['feature']   = array('custom|set', 'custom|product', 'custom|execution', 'custom|required', 'custom|kanban', 'approvalflow', 'meetingroom', 'custom|browsestoryconcept', 'custom|kanban', 'sqlbuilder', 'report', 'custom|limittaskdate');
$config->admin->menuGroup['template']  = array('custom|set', 'baseline');
$config->admin->menuGroup['message']   = array('mail', 'webhook', 'sms', 'message');
$config->admin->menuGroup['dev']       = array('dev', 'entry', 'editor');
$config->admin->menuGroup['extension'] = array('extension');
$config->admin->menuGroup['convert']   = array('convert');
$config->admin->menuGroup['platform']  = array('system', 'repo', 'account', 'host', 'serverroom', 'ops', 'tree', 'domain', 'deploy', 'service');
$config->admin->menuGroup['ai']        = array('ai|adminindex', 'ai|prompts', 'ai|promptview', 'ai|conversations', 'ai|models', 'ai|editmodel', 'ai|promptassignrole', 'ai|promptselectdatasource', 'ai|promptsetpurpose', 'ai|promptsettargetform', 'ai|promptfinalize', 'ai|promptedit');

$config->admin->menuModuleGroup['model']['custom|set']        = array('project', 'issue', 'risk', 'opportunity', 'nc');
$config->admin->menuModuleGroup['model']['custom|required']   = array('project', 'build');
$config->admin->menuModuleGroup['feature']['custom|set']      = array('todo', 'block', 'story', 'task', 'bug', 'testcase', 'testtask', 'feedback', 'user', 'ticket');
$config->admin->menuModuleGroup['feature']['custom|required'] = array('bug', 'doc', 'product', 'story', 'productplan', 'release', 'task', 'testcase', 'testsuite', 'testtask', 'testreport', 'caselib', 'doc', 'feedback', 'user', 'execution');
$config->admin->menuModuleGroup['template']['custom|set']     = array('baseline');
if($config->vision == 'lite')
{
    $config->admin->menuModuleGroup['model']['custom|set']        = array();
    $config->admin->menuModuleGroup['model']['custom|required']   = array('build');
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

$config->admin->plugins[27] = new stdClass();
$config->admin->plugins[27]->name     = 'Excel导出/导入';
$config->admin->plugins[27]->abstract = '安装该插件可以支持Excel导出和任务、需求、Bug、用例导入功能。更新提示：新增任务、需求、Bug的excel导入功能。';
$config->admin->plugins[27]->viewLink = 'https://www.zentao.net/extension-viewExt-27.html';

$config->admin->plugins[26] = new stdClass();
$config->admin->plugins[26]->name     = '甘特图';
$config->admin->plugins[26]->abstract = '禅道甘特图插件 运用此插件可以查看甘特图，维护任务关系。';
$config->admin->plugins[26]->viewLink = 'https://www.zentao.net/extension-viewExt-26.html';

$config->admin->plugins[30] = new stdClass();
$config->admin->plugins[30]->name     = '日志日历';
$config->admin->plugins[30]->abstract = '运用此插件可以实现工作日志的添加、编辑、查看、删除、导出等功能，方便用户管理工作日志。';
$config->admin->plugins[30]->viewLink = 'https://www.zentao.net/extension-viewExt-30.html';

$config->admin->plugins[194] = new stdClass();
$config->admin->plugins[194]->name     = '应用巡检报告';
$config->admin->plugins[194]->abstract = '每日生成公司级禅道应用巡检报告，促进项目管理持续改进。';
$config->admin->plugins[194]->viewLink = 'https://www.zentao.net/extension-viewExt-194.html';

$config->admin->apiRoot        = 'https://www.zentao.net';
$config->admin->cdnRoot        = 'https://cdn.easycorp.cn/web';
$config->admin->classURL       = 'https://www.zentao.net/publicclass.html';
$config->admin->dynamicURL     = 'https://www.zentao.net/dynamic.html';
$config->admin->downloadURL    = 'https://www.zentao.net/download.html';
$config->admin->extensionURL   = 'https://www.zentao.net/extension-browse.html';
$config->admin->patchURL       = 'https://www.zentao.net/extension-browse-1218.html';
$config->admin->apiSite        = 'https://api.zentao.net/';
$config->admin->extAPIURL      = $config->admin->apiSite . 'extension-apiGetExtensions-';
$config->admin->patchAPIURL    = $config->admin->apiSite . 'extension-apiGetExtensions-bymodule-MTIxOA==-0-100-1.json';
$config->admin->downloadAPIURL = $config->admin->apiSite . 'download.json';
$config->admin->dynamicAPIURL  = $config->admin->apiSite . 'dynamic-activities.json';
$config->admin->videoAPIURL    = $config->admin->apiSite . 'publicclass.json';
$config->admin->liteMenuList   = array('system', 'company', 'feature', 'message', 'extension', 'dev', 'ai');

$config->admin->helpURL['system']    = 'https://www.zentao.net/book/zentaopms/538.html';
$config->admin->helpURL['company']   = 'https://www.zentao.net/book/zentaopms/38.html';
$config->admin->helpURL['switch']    = 'https://www.zentao.net/book/zentaopms/38.html';
$config->admin->helpURL['model']     = 'https://www.zentao.net/book/zentaopms/533.html';
$config->admin->helpURL['feature']   = 'https://www.zentao.net/book/zentaopms/38.html';
$config->admin->helpURL['template']  = 'https://www.zentao.net/book/zentaopms/38.html';
$config->admin->helpURL['message']   = 'https://www.zentao.net/book/zentaopms/email-notification-541.html';
$config->admin->helpURL['extension'] = 'https://www.zentao.net/book/zentaopms/536.html';
$config->admin->helpURL['dev']       = 'https://www.zentao.net/book/zentaopms/537.html';
$config->admin->helpURL['convert']   = 'https://www.zentao.net/book/zentaopms/656.html';
$config->admin->helpURL['platform']  = 'https://www.zentao.net/book/devops/1072.html';
$config->admin->helpURL['ai']        = 'https://www.zentao.net/book/zentaopms/1097.html';

$config->admin->navsGroup['feature']['my']        = ',todo,block,';
$config->admin->navsGroup['feature']['product']   = ',product,story,productplan,release,';
$config->admin->navsGroup['feature']['project']   = ',project,';
$config->admin->navsGroup['feature']['execution'] = ',execution,task,';
$config->admin->navsGroup['feature']['project']   = ',project,story,';
$config->admin->navsGroup['feature']['qa']        = ',bug,testcase,testsuite,testtask,testreport,caselib,';
$config->admin->navsGroup['model']['common']      = ',project,build,issue,risk,opportunity,nc,';
$config->admin->navsGroup['template']['type']     = ',baseline,';
if($config->vision == 'lite') $config->admin->navsGroup['feature']['my'] .= 'task,';

global $lang;
$config->admin->checkWeak = new stdclass();
$config->admin->checkWeak->actionList = array();
$config->admin->checkWeak->actionList['edit']['icon']        = 'edit';
$config->admin->checkWeak->actionList['edit']['text']        = $lang->user->edit;
$config->admin->checkWeak->actionList['edit']['hint']        = $lang->user->edit;
$config->admin->checkWeak->actionList['edit']['url']         = array('module' => 'user', 'method' => 'edit', 'params' => 'userID={id}');
