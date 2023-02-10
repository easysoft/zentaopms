<?php
global $config;
$lang->admin->menuList = new stdclass();
$lang->admin->menuList->system['name']  = $lang->admin->menuSetting['system']['name'];
$lang->admin->menuList->system['desc']  = $lang->admin->menuSetting['system']['desc'];
$lang->admin->menuList->system['order'] = 1;

$lang->admin->menuList->user['name']  = $lang->admin->menuSetting['user']['name'];
$lang->admin->menuList->user['desc']  = $lang->admin->menuSetting['user']['desc'];
$lang->admin->menuList->user['order'] = 2;

$lang->admin->menuList->switch['name']  = $lang->admin->menuSetting['switch']['name'];
$lang->admin->menuList->switch['desc']  = $lang->admin->menuSetting['switch']['desc'];
$lang->admin->menuList->switch['order'] = 3;

$lang->admin->menuList->model['name']  = $lang->admin->menuSetting['model']['name'];
$lang->admin->menuList->model['desc']  = $lang->admin->menuSetting['model']['desc'];
$lang->admin->menuList->model['order'] = 4;

$lang->admin->menuList->feature['name']  = $lang->admin->menuSetting['feature']['name'];
$lang->admin->menuList->feature['desc']  = $lang->admin->menuSetting['feature']['desc'];
$lang->admin->menuList->feature['order'] = 5;

$lang->admin->menuList->template['name']  = $lang->admin->menuSetting['template']['name'];
$lang->admin->menuList->template['desc']  = $lang->admin->menuSetting['template']['desc'];
$lang->admin->menuList->template['order'] = 6;

$lang->admin->menuList->message['name']  = $lang->admin->menuSetting['message']['name'];
$lang->admin->menuList->message['desc']  = $lang->admin->menuSetting['message']['desc'];
$lang->admin->menuList->message['order'] = 7;

$lang->admin->menuList->extension['name']  = $lang->admin->menuSetting['extension']['name'];
$lang->admin->menuList->extension['desc']  = $lang->admin->menuSetting['extension']['desc'];
$lang->admin->menuList->extension['link']  = 'extension|browse';
$lang->admin->menuList->extension['order'] = 8;

$lang->admin->menuList->dev['name']  = $lang->admin->menuSetting['dev']['name'];
$lang->admin->menuList->dev['desc']  = $lang->admin->menuSetting['dev']['desc'];
$lang->admin->menuList->dev['order'] = 9;

$lang->admin->menuList->convert['name']  = $lang->admin->menuSetting['convert']['name'];
$lang->admin->menuList->convert['desc']  = $lang->admin->menuSetting['convert']['desc'];
$lang->admin->menuList->convert['link']  = 'convert|convertjira';
$lang->admin->menuList->convert['order'] = 10;

$lang->admin->menuList->system['subMenu']['mode']        = array('link' => "{$lang->custom->mode}|custom|mode|");
$lang->admin->menuList->system['subMenu']['backup']      = array('link' => "{$lang->backup->common}|backup|index|");
$lang->admin->menuList->system['subMenu']['trash']       = array('link' => "{$lang->action->trash}|action|trash|");
$lang->admin->menuList->system['subMenu']['safe']        = array('link' => "{$lang->security}|admin|safe|", 'alias' => 'checkweak,resetpwdsetting', 'links' => array('admin|resetpwdsetting|', 'admin|checkweak|'));
$lang->admin->menuList->system['subMenu']['cron']        = array('link' => "{$lang->admin->cron}|cron|index|", 'subModule' => 'cron');
$lang->admin->menuList->system['subMenu']['timezone']    = array('link' => "{$lang->timezone}|custom|timezone|");
$lang->admin->menuList->system['subMenu']['buildindex']  = array('link' => "{$lang->admin->buildIndex}|search|buildindex|");
$lang->admin->menuList->system['subMenu']['tableengine'] = array('link' => "{$lang->admin->tableEngine}|admin|tableengine|");

$lang->admin->menuList->system['menuOrder']['5']  = 'mode';
$lang->admin->menuList->system['menuOrder']['10'] = 'backup';
$lang->admin->menuList->system['menuOrder']['15'] = 'trash';
$lang->admin->menuList->system['menuOrder']['30'] = 'safe';
$lang->admin->menuList->system['menuOrder']['35'] = 'cron';
$lang->admin->menuList->system['menuOrder']['40'] = 'timezone';
$lang->admin->menuList->system['menuOrder']['45'] = 'buildindex';
$lang->admin->menuList->system['menuOrder']['50'] = 'tableengine';

$lang->admin->menuList->system['dividerMenu'] = ',safe,';

$lang->admin->menuList->user['subMenu']['dept']  = array('link' => "{$lang->dept->common}|dept|browse|", 'subModule' => 'dept');
$lang->admin->menuList->user['subMenu']['user']  = array('link' => "{$lang->user->common}|company|browse|", 'subModule' => 'user');
$lang->admin->menuList->user['subMenu']['group'] = array('link' => "{$lang->priv}|group|browse|", 'subModule' => 'group');

$lang->admin->menuList->user['menuOrder']['5']  = 'dept';
$lang->admin->menuList->user['menuOrder']['10'] = 'user';
$lang->admin->menuList->user['menuOrder']['15'] = 'group';

$lang->admin->menuList->switch['subMenu']['setmodule'] = array('link' => "{$lang->admin->module}|admin|setmodule|");

$lang->admin->menuList->switch['menuOrder']['5'] = 'setmodule';

$lang->admin->menuList->model['subMenu']['common']    = array('link' => "{$lang->globalSetting}|custom|required|module=project", 'subModule' => 'custom');
$lang->admin->menuList->model['subMenu']['scrum']     = array('link' => "{$lang->scrumModel}|auditcl|scrumbrowse|");
$lang->admin->menuList->model['subMenu']['waterfall'] = array('link' => "{$lang->waterfallModel}|stage|settype|", 'subModule' => 'stage');

$lang->admin->menuList->model['menuOrder']['5']  = 'common';
$lang->admin->menuList->model['menuOrder']['10'] = 'scrum';
$lang->admin->menuList->model['menuOrder']['15'] = 'waterfall';

$lang->admin->menuList->model['tabMenu']['waterfall']['stage'] = array('link' => "{$lang->stage->common}|stage|settype|", 'subModule' => 'stage');

$lang->admin->menuList->feature['subMenu']['my']          = array('link' => "{$lang->my->common}|custom|set|module=todo&field=priList", 'exclude' => 'set,required');
$lang->admin->menuList->feature['subMenu']['product']     = array('link' => "{$lang->productCommon}|custom|required|module=product", 'exclude' => 'set,required', 'alias' => 'browsestoryconcept,product');
$lang->admin->menuList->feature['subMenu']['execution']   = array('link' => "{$lang->execution->common}|custom|execution|");
$lang->admin->menuList->feature['subMenu']['qa']          = array('link' => "{$lang->qa->common}|custom|required|module=bug", 'exclude' => 'set,required');
$lang->admin->menuList->feature['subMenu']['kanban']      = array('link' => "{$lang->kanban->common}|custom|kanban|");
$lang->admin->menuList->feature['subMenu']['doc']         = array('link' => "{$lang->doc->common}|custom|required|module=doc", 'exclude' => 'required');
$lang->admin->menuList->feature['subMenu']['user']        = array('link' => "{$lang->user->common}|custom|required|module=user", 'links' => array('custom|set|module=user&field=roleList'), 'exclude' => 'set,required');

$lang->admin->menuList->feature['tabMenu']['my']['todo']  = array('link' => "{$lang->todo->common}|custom|set|module=todo&field=priList", 'exclude' => 'custom-set');
$lang->admin->menuList->feature['tabMenu']['my']['block'] = array('link' => "{$lang->block->common}|custom|set|module=block&field=closed", 'exclude' => 'custom-set');
if($config->vision == 'lite') $lang->admin->menuList->feature['tabMenu']['my']['task'] = array('link' => "{$lang->task->common}|custom|required|module=task", 'links' => array('custom|set|module=task&field=priList'), 'exclude' => 'custom-required,custom-set');

$lang->admin->menuList->feature['tabMenu']['product']['product']     = array('link' => "{$lang->productCommon}|custom|required|module=product", 'links' => array("custom|browsestoryconcept|", 'custom|product|'), 'alias' => 'browsestoryconcept,product', 'exclude' => 'custom-required');
$lang->admin->menuList->feature['tabMenu']['product']['story']       = array('link' => "{$lang->SRCommon}|custom|required|module=story", 'links' => array("custom|set|module=story&field=categoryList"), 'exclude' => 'custom-required,custom-set');
$lang->admin->menuList->feature['tabMenu']['product']['productplan'] = array('link' => "{$lang->productplan->shortCommon}|custom|required|module=productplan", 'exclude' => 'custom-required');
$lang->admin->menuList->feature['tabMenu']['product']['release']     = array('link' => "{$lang->release->common}|custom|required|module=release", 'exclude' => 'custom-required');

$lang->admin->menuList->feature['tabMenu']['execution']['execution'] = array('link' => "{$lang->execution->common}|custom|execution|");
$lang->admin->menuList->feature['tabMenu']['execution']['task']      = array('link' => "{$lang->task->common}|custom|required|module=task", 'links' => array('custom|set|module=task&field=priList'));

$lang->admin->menuList->feature['tabMenu']['qa']['bug']        = array('link' => "{$lang->bug->common}|custom|required|module=bug", 'links' => array("custom|set|module=bug&field=priList"), 'exclude' => 'custom-required,custom-set');
$lang->admin->menuList->feature['tabMenu']['qa']['testcase']   = array('link' => "{$lang->testcase->common}|custom|required|module=testcase", 'links' => array("custom|set|module=testcase&field=priList"), 'exclude' => 'custom-required,custom-set');
$lang->admin->menuList->feature['tabMenu']['qa']['testsuite']  = array('link' => "{$lang->testcase->testsuite}|custom|required|module=testsuite", 'exclude' => 'custom-required');
$lang->admin->menuList->feature['tabMenu']['qa']['testtask']   = array('link' => "{$lang->testtask->common}|custom|required|module=testtask", 'links' => array('custom|set|module=testtask&field=statusList'), 'exclude' => 'custom-required,custom-set');
$lang->admin->menuList->feature['tabMenu']['qa']['testreport'] = array('link' => "{$lang->testreport->common}|custom|required|module=testreport", 'exclude' => 'custom-required');
$lang->admin->menuList->feature['tabMenu']['qa']['caselib']    = array('link' => "{$lang->testcase->caselib}|custom|required|module=caselib", 'exclude' => 'custom-required');

$lang->admin->menuList->feature['menuOrder']['5']  = 'my';
$lang->admin->menuList->feature['menuOrder']['10'] = 'product';
$lang->admin->menuList->feature['menuOrder']['15'] = 'execution';
$lang->admin->menuList->feature['menuOrder']['20'] = 'qa';
$lang->admin->menuList->feature['menuOrder']['25'] = 'kanban';
$lang->admin->menuList->feature['menuOrder']['30'] = 'doc';
$lang->admin->menuList->feature['menuOrder']['50'] = 'user';

$lang->admin->menuList->feature['dividerMenu'] = ',user,';

$lang->admin->menuList->message['subMenu']['mail']    = array('link' => "{$lang->mail->common}|mail|edit|", 'subModule' => 'mail');
$lang->admin->menuList->message['subMenu']['webhook'] = array('link' => "Webhook|webhook|browse|", 'subModule' => 'webhook');
$lang->admin->menuList->message['subMenu']['browser'] = array('link' => "{$lang->browser}|message|browser|");
$lang->admin->menuList->message['subMenu']['setting'] = array('link' => "{$lang->settings}|message|setting|");

$lang->admin->menuList->message['menuOrder']['5']  = 'mail';
$lang->admin->menuList->message['menuOrder']['10'] = 'webhook';
$lang->admin->menuList->message['menuOrder']['20'] = 'browser';
$lang->admin->menuList->message['menuOrder']['25'] = 'setting';

$lang->admin->menuList->dev['subMenu']['api']    = array('link' => "API|dev|api|module=index");
$lang->admin->menuList->dev['subMenu']['db']     = array('link' => "{$lang->database}|dev|db|table=" . trim(TABLE_EFFORT, '`'));
$lang->admin->menuList->dev['subMenu']['editor'] = array('link' => "{$lang->editor}|dev|editor|");
$lang->admin->menuList->dev['subMenu']['entry']  = array('link' => "{$lang->admin->entry}|entry|browse|", 'subModule' => 'entry');

$lang->admin->menuList->dev['menuOrder']['5']  = 'api';
$lang->admin->menuList->dev['menuOrder']['10'] = 'db';
$lang->admin->menuList->dev['menuOrder']['15'] = 'editor';
$lang->admin->menuList->dev['menuOrder']['20'] = 'entry';

if($config->edition != 'max') unset($lang->admin->menuList->model['subMenu']['scrum'], $lang->admin->menuList->model['menuOrder']['10'], $lang->admin->menuList->template);
if(!helper::hasFeature('waterfall'))
{
    unset($lang->admin->menuList->model['subMenu']['waterfall']);
    unset($lang->admin->menuList->model['menuOrder']['15']);
}
if($config->edition == 'max' and !helper::hasFeature('scrum_auditplan'))
{
    if(helper::hasFeature('scrum_process')) unset($lang->admin->menuList->model['subMenu']['scrum'], $lang->admin->menuList->model['menuOrder']['10']);
}

if($config->vision == 'lite')
{
    unset($lang->admin->menuList->system['subMenu']['mode']);
    unset($lang->admin->menuList->system['subMenu']['buildindex']);
    unset($lang->admin->menuList->system['subMenu']['tableengine']);
    unset($lang->admin->menuList->system['menuOrder']['5']);
    unset($lang->admin->menuList->system['menuOrder']['45']);
    unset($lang->admin->menuList->system['menuOrder']['50']);

    unset($lang->admin->menuList->model['subMenu']['scrum']);
    unset($lang->admin->menuList->model['subMenu']['waterfall']);
    unset($lang->admin->menuList->model['menuOrder']['10']);
    unset($lang->admin->menuList->model['menuOrder']['15']);

    unset($lang->admin->menuList->feature['subMenu']['product']);
    unset($lang->admin->menuList->feature['subMenu']['execution']);
    unset($lang->admin->menuList->feature['subMenu']['qa']);
    unset($lang->admin->menuList->feature['subMenu']['kanban']);
    unset($lang->admin->menuList->feature['menuOrder']['10']);
    unset($lang->admin->menuList->feature['menuOrder']['15']);
    unset($lang->admin->menuList->feature['menuOrder']['20']);
    unset($lang->admin->menuList->feature['menuOrder']['25']);

    $lang->admin->menuList->feature['subMenu']['project'] = array('link' => "{$lang->projectCommon}|custom|execution|", 'exclude' => 'set,required', 'alias' => 'execution');
    $lang->admin->menuList->feature['tabMenu']['project']['project']   = array('link' => "{$lang->projectCommon}|custom|required|module=project", 'exclude' => 'custom-required');
    $lang->admin->menuList->feature['tabMenu']['project']['execution'] = array('link' => "{$lang->execution->common}|custom|execution|");
    $lang->admin->menuList->feature['tabMenu']['project']['story']     = array('link' => "{$lang->SRCommon}|custom|required|module=story", 'links' => array("custom|set|module=story&field=priList"), 'exclude' => 'custom-required,custom-set');
    $lang->admin->menuList->feature['menuOrder']['15'] = 'project';
}
