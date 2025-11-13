<?php
global $config;
$lang->admin->menuList = new stdclass();
$lang->admin->menuList->system['name']  = $lang->admin->menuSetting['system']['name'];
$lang->admin->menuList->system['desc']  = $lang->admin->menuSetting['system']['desc'];
$lang->admin->menuList->system['order'] = 5;

$lang->admin->menuList->switch['name']  = $lang->admin->menuSetting['switch']['name'];
$lang->admin->menuList->switch['desc']  = $lang->admin->menuSetting['switch']['desc'];
$lang->admin->menuList->switch['link']  = 'admin|setmodule';
$lang->admin->menuList->switch['order'] = 10;

$lang->admin->menuList->company['name']  = $lang->admin->menuSetting['user']['name'];
$lang->admin->menuList->company['desc']  = $lang->admin->menuSetting['user']['desc'];
$lang->admin->menuList->company['order'] = 15;

$lang->admin->menuList->feature['name']  = $lang->admin->menuSetting['feature']['name'];
$lang->admin->menuList->feature['desc']  = $lang->admin->menuSetting['feature']['desc'];
$lang->admin->menuList->feature['order'] = 25;

$lang->admin->menuList->message['name']  = $lang->admin->menuSetting['message']['name'];
$lang->admin->menuList->message['desc']  = $lang->admin->menuSetting['message']['desc'];
$lang->admin->menuList->message['order'] = 35;

$lang->admin->menuList->extension['name']  = $lang->admin->menuSetting['extension']['name'];
$lang->admin->menuList->extension['desc']  = $lang->admin->menuSetting['extension']['desc'];
$lang->admin->menuList->extension['link']  = 'extension|browse';
$lang->admin->menuList->extension['order'] = 40;

$lang->admin->menuList->dev['name']  = $lang->admin->menuSetting['dev']['name'];
$lang->admin->menuList->dev['desc']  = $lang->admin->menuSetting['dev']['desc'];
$lang->admin->menuList->dev['order'] = 45;

$lang->admin->menuList->convert['name']  = $lang->admin->menuSetting['convert']['name'];
$lang->admin->menuList->convert['desc']  = $lang->admin->menuSetting['convert']['desc'];
$lang->admin->menuList->convert['link']  = 'convert|index|mode=restore';
$lang->admin->menuList->convert['order'] = 50;

$lang->admin->menuList->system['subMenu']['mode']        = array('link' => "{$lang->custom->mode}|custom|mode|");
$lang->admin->menuList->system['subMenu']['trash']       = array('link' => "{$lang->action->trash}|action|trash|");
$lang->admin->menuList->system['subMenu']['safe']        = array('link' => "{$lang->security}|admin|safe|", 'alias' => 'checkweak,resetpwdsetting', 'links' => array('admin|resetpwdsetting|', 'admin|checkweak|'));
$lang->admin->menuList->system['subMenu']['cache']       = array('link' => "{$lang->cache->common}|cache|setting|");
$lang->admin->menuList->system['subMenu']['cron']        = array('link' => "{$lang->admin->cron}|cron|index|", 'subModule' => 'cron');
$lang->admin->menuList->system['subMenu']['timezone']    = array('link' => "{$lang->timezone}|custom|timezone|");
$lang->admin->menuList->system['subMenu']['buildindex']  = array('link' => "{$lang->admin->buildIndex}|search|buildindex|");
$lang->admin->menuList->system['subMenu']['tableengine'] = array('link' => "{$lang->admin->tableEngine}|admin|tableengine|");
if($config->edition != 'open' && $config->vision == 'rnd') $lang->admin->menuList->system['subMenu']['metriclib'] = array('link' => "{$lang->metriclib->common}|admin|metriclib|");
if(in_array($this->config->db->driver, $this->config->mysqlDriverList)) $lang->admin->menuList->system['subMenu']['backup'] = array('link' => "{$lang->backup->common}|backup|index|");

$lang->admin->menuList->system['menuOrder']['5']  = 'mode';
if(in_array($this->config->db->driver, $this->config->mysqlDriverList)) $lang->admin->menuList->system['menuOrder']['10'] = 'backup';
$lang->admin->menuList->system['menuOrder']['15'] = 'trash';
$lang->admin->menuList->system['menuOrder']['30'] = 'safe';
$lang->admin->menuList->system['menuOrder']['35'] = 'cache';
$lang->admin->menuList->system['menuOrder']['40'] = 'cron';
$lang->admin->menuList->system['menuOrder']['45'] = 'timezone';
$lang->admin->menuList->system['menuOrder']['50'] = 'buildindex';
$lang->admin->menuList->system['menuOrder']['65'] = 'tableengine';  // The order 51-64 is reserved for extension.

$lang->admin->menuList->system['dividerMenu'] = ',safe,';

$lang->admin->menuList->company['subMenu']['dept']       = array('link' => "{$lang->dept->common}|dept|browse|", 'subModule' => 'dept');
$lang->admin->menuList->company['subMenu']['browseUser'] = array('link' => "{$lang->user->common}|company|browse|", 'subModule' => 'user,tutorial');
$lang->admin->menuList->company['subMenu']['group']      = array('link' => "{$lang->priv}|group|browse|", 'subModule' => 'group');

$lang->admin->menuList->company['menuOrder']['5']  = 'dept';
$lang->admin->menuList->company['menuOrder']['10'] = 'browseUser';
$lang->admin->menuList->company['menuOrder']['15'] = 'group';

$lang->admin->menuList->feature['subMenu']['my']          = array('link' => "{$lang->my->common}|custom|set|module=todo&field=priList", 'exclude' => 'set,required');
$lang->admin->menuList->feature['subMenu']['product']     = array('link' => "{$lang->productCommon}|custom|required|module=product", 'exclude' => 'set,required', 'alias' => 'browsestoryconcept,product');
$lang->admin->menuList->feature['subMenu']['project']     = array('link' => "{$lang->projectCommon}|custom|required|module=project", 'alias' => 'flow,percent,hours,estimate,project', 'subModule' => 'subject,holiday,baseline,design,reviewcl', 'exclude' => 'set,required');
$lang->admin->menuList->feature['subMenu']['execution']   = array('link' => "{$lang->execution->common}|custom|required|module=execution", 'exclude' => 'required,set', 'alias' => 'execution,limittaskdate', 'subModule' => 'stage');
$lang->admin->menuList->feature['subMenu']['qa']          = array('link' => "{$lang->qa->common}|custom|required|module=bug", 'exclude' => 'set,required');
$lang->admin->menuList->feature['subMenu']['kanban']      = array('link' => "{$lang->kanban->common}|custom|kanban|");
$lang->admin->menuList->feature['subMenu']['user']        = array('link' => "{$lang->user->common}|custom|required|module=user", 'links' => array('custom|set|module=user&field=roleList'), 'exclude' => 'set,required');

$lang->admin->menuList->feature['tabMenu']['my']['todo']  = array('link' => "{$lang->todo->common}|custom|set|module=todo&field=priList", 'exclude' => 'custom-set');
$lang->admin->menuList->feature['tabMenu']['my']['block'] = array('link' => "{$lang->block->common}|custom|set|module=block&field=closed", 'exclude' => 'custom-set');

$lang->admin->menuList->feature['tabMenu']['product']['product'] = array('link' => "{$lang->productCommon}|custom|required|module=product", 'links' => array("custom|browsestoryconcept|", 'custom|product|'), 'alias' => 'browsestoryconcept,product', 'exclude' => 'custom-required');
if($config->enableER) $lang->admin->menuList->feature['tabMenu']['product']['epic']        = array('link' => "{$lang->epic->common}|custom|required|module=epic", 'links' => array("custom|set|module=epic&field=categoryList"), 'exclude' => 'custom-required,custom-set');
if($config->URAndSR)  $lang->admin->menuList->feature['tabMenu']['product']['requirement'] = array('link' => "{$lang->URCommon}|custom|required|module=requirement", 'links' => array("custom|set|module=requirement&field=categoryList"), 'exclude' => 'custom-required,custom-set');
$lang->admin->menuList->feature['tabMenu']['product']['story']       = array('link' => "{$lang->SRCommon}|custom|required|module=story", 'links' => array("custom|set|module=story&field=categoryList"), 'exclude' => 'custom-required,custom-set');
$lang->admin->menuList->feature['tabMenu']['product']['productplan'] = array('link' => "{$lang->productplan->shortCommon}|custom|required|module=productplan", 'exclude' => 'custom-required');
$lang->admin->menuList->feature['tabMenu']['product']['release']     = array('link' => "{$lang->release->common}|custom|required|module=release", 'exclude' => 'custom-required');

$lang->admin->menuList->feature['tabMenu']['project']['project'] = array('link' => "{$lang->project->common}|custom|required|module=project", 'alias' => 'project', 'exclude' => 'custom-required');
$lang->admin->menuList->feature['tabMenu']['project']['build']   = array('link' => "{$lang->build->common}|custom|required|module=build", 'exclude' => 'custom-required');
$lang->admin->menuList->feature['tabMenu']['project']['flow']    = array('link' => "{$lang->custom->flow}|custom|flow|");
$lang->admin->menuList->feature['tabMenu']['project']['percent'] = array('link' => "{$lang->stage->percent}|custom|percent|");
$lang->admin->menuList->feature['tabMenu']['project']['hours']   = array('link' => "{$lang->workingHour}|custom|hours|", 'subModule' => 'holiday', 'links' => array('holiday|browse|', 'custom|hours|'));

$lang->admin->menuList->feature['tabMenu']['execution']['execution'] = array('link' => "{$lang->execution->common}|custom|required|module=execution", 'links' => array("custom|execution|"), 'alias' => 'execution', 'exclude' => 'custom-required');
$lang->admin->menuList->feature['tabMenu']['execution']['stage']     = array('link' => "{$lang->stage->common}|stage|settype|", 'subModule' => 'stage', 'links' => array('stage|browse|'));
$lang->admin->menuList->feature['tabMenu']['execution']['task']      = array('link' => "{$lang->task->common}|custom|required|module=task", 'links' => array('custom|set|module=task&field=priList', 'custom|limittaskdate|'), 'alias' => 'limittaskdate','exclude' => 'custom-required');

$lang->admin->menuList->feature['tabMenu']['qa']['bug']        = array('link' => "{$lang->bug->common}|custom|required|module=bug", 'links' => array("custom|set|module=bug&field=priList"), 'exclude' => 'custom-required,custom-set');
$lang->admin->menuList->feature['tabMenu']['qa']['testcase']   = array('link' => "{$lang->testcase->common}|custom|required|module=testcase", 'links' => array("custom|set|module=testcase&field=priList"), 'exclude' => 'custom-required,custom-set');
$lang->admin->menuList->feature['tabMenu']['qa']['testsuite']  = array('link' => "{$lang->testcase->testsuite}|custom|required|module=testsuite", 'exclude' => 'custom-required');
$lang->admin->menuList->feature['tabMenu']['qa']['testtask']   = array('link' => "{$lang->testtask->common}|custom|required|module=testtask", 'links' => array('custom|set|module=testtask&field=statusList'), 'exclude' => 'custom-required,custom-set');
$lang->admin->menuList->feature['tabMenu']['qa']['testreport'] = array('link' => "{$lang->testreport->common}|custom|required|module=testreport", 'exclude' => 'custom-required');
$lang->admin->menuList->feature['tabMenu']['qa']['caselib']    = array('link' => "{$lang->testcase->caselib}|custom|required|module=caselib", 'exclude' => 'custom-required');

$lang->admin->menuList->feature['tabMenu']['menuOrder']['my']['5']         = 'todo';
$lang->admin->menuList->feature['tabMenu']['menuOrder']['my']['10']        = 'block';
$lang->admin->menuList->feature['tabMenu']['menuOrder']['product']['5']    = 'product';
if($config->enableER) $lang->admin->menuList->feature['tabMenu']['menuOrder']['product']['10'] = 'epic';
if($config->URAndSR)  $lang->admin->menuList->feature['tabMenu']['menuOrder']['product']['15'] = 'requirement';
$lang->admin->menuList->feature['tabMenu']['menuOrder']['product']['20']   = 'story';
$lang->admin->menuList->feature['tabMenu']['menuOrder']['product']['25']   = 'productplan';
$lang->admin->menuList->feature['tabMenu']['menuOrder']['product']['30']   = 'release';

$lang->admin->menuList->feature['tabMenu']['menuOrder']['project']['5']   = 'project';
$lang->admin->menuList->feature['tabMenu']['menuOrder']['project']['10']  = 'build';
$lang->admin->menuList->feature['tabMenu']['menuOrder']['project']['35']  = 'flow';
$lang->admin->menuList->feature['tabMenu']['menuOrder']['project']['40']  = 'percent';
$lang->admin->menuList->feature['tabMenu']['menuOrder']['project']['45']  = 'hours';

$lang->admin->menuList->feature['tabMenu']['menuOrder']['execution']['5']  = 'execution';
$lang->admin->menuList->feature['tabMenu']['menuOrder']['execution']['10'] = 'stage';
$lang->admin->menuList->feature['tabMenu']['menuOrder']['execution']['15'] = 'task';
$lang->admin->menuList->feature['tabMenu']['menuOrder']['qa']['5']         = 'bug';
$lang->admin->menuList->feature['tabMenu']['menuOrder']['qa']['10']        = 'testcase';
$lang->admin->menuList->feature['tabMenu']['menuOrder']['qa']['15']        = 'testsuite';
$lang->admin->menuList->feature['tabMenu']['menuOrder']['qa']['20']        = 'testtask';
$lang->admin->menuList->feature['tabMenu']['menuOrder']['qa']['25']        = 'testreport';
$lang->admin->menuList->feature['tabMenu']['menuOrder']['qa']['30']        = 'caselib';

$lang->admin->menuList->feature['menuOrder']['5']  = 'my';
$lang->admin->menuList->feature['menuOrder']['10'] = 'product';
$lang->admin->menuList->feature['menuOrder']['15'] = 'project';
$lang->admin->menuList->feature['menuOrder']['20'] = 'execution';
$lang->admin->menuList->feature['menuOrder']['25'] = 'qa';
$lang->admin->menuList->feature['menuOrder']['30'] = 'kanban';
$lang->admin->menuList->feature['menuOrder']['35'] = 'doc';
$lang->admin->menuList->feature['menuOrder']['40'] = 'user';

$lang->admin->menuList->feature['dividerMenu'] = ',user,';

$lang->admin->menuList->message['subMenu']['mail']    = array('link' => "{$lang->mail->common}|mail|edit|", 'subModule' => 'mail');
$lang->admin->menuList->message['subMenu']['webhook'] = array('link' => "Webhook|webhook|browse|", 'subModule' => 'webhook');
$lang->admin->menuList->message['subMenu']['browser'] = array('link' => "{$lang->message->common}|message|browser|");
$lang->admin->menuList->message['subMenu']['setting'] = array('link' => "{$lang->settings}|message|setting|");

$lang->admin->menuList->message['menuOrder']['5']  = 'mail';
$lang->admin->menuList->message['menuOrder']['10'] = 'webhook';
$lang->admin->menuList->message['menuOrder']['20'] = 'browser';
$lang->admin->menuList->message['menuOrder']['25'] = 'setting';

$lang->admin->menuList->dev['subMenu']['api']      = array('link' => "{$lang->api->doc}|dev|api|module=restapi");
$lang->admin->menuList->dev['subMenu']['db']       = array('link' => "{$lang->database}|dev|db|table=zt_todo");
$lang->admin->menuList->dev['subMenu']['langItem'] = array('link' => "{$lang->langItem}|dev|langitem|");
$lang->admin->menuList->dev['subMenu']['editor']   = array('link' => "{$lang->editor->common}|dev|editor|", 'subModule' => 'editor');
$lang->admin->menuList->dev['subMenu']['entry']    = array('link' => "{$lang->admin->entry}|entry|browse|", 'subModule' => 'entry');

$lang->admin->menuList->dev['menuOrder']['5']  = 'api';
$lang->admin->menuList->dev['menuOrder']['10'] = 'db';
$lang->admin->menuList->dev['menuOrder']['15'] = 'langItem';
$lang->admin->menuList->dev['menuOrder']['20'] = 'editor';
$lang->admin->menuList->dev['menuOrder']['25'] = 'entry';

$lang->admin->menuList->adminregister['name']  = $lang->admin->menuSetting['adminregister']['name'];
$lang->admin->menuList->adminregister['desc']  = $lang->admin->menuSetting['adminregister']['desc'];
$lang->admin->menuList->adminregister['link']  = 'admin|register';
$lang->admin->menuList->adminregister['order'] = 61;

if($config->vision == 'lite')
{
    unset($lang->admin->menuList->system['subMenu']['mode']);
    unset($lang->admin->menuList->system['subMenu']['buildindex']);
    unset($lang->admin->menuList->system['subMenu']['tableengine']);
    unset($lang->admin->menuList->system['menuOrder']['5']);
    unset($lang->admin->menuList->system['menuOrder']['45']);
    unset($lang->admin->menuList->system['menuOrder']['50']);

    unset($lang->admin->menuList->feature['subMenu']['product']);
    unset($lang->admin->menuList->feature['subMenu']['execution']);
    unset($lang->admin->menuList->feature['subMenu']['qa']);
    unset($lang->admin->menuList->feature['subMenu']['kanban']);
    unset($lang->admin->menuList->feature['menuOrder']['10']);
    unset($lang->admin->menuList->feature['menuOrder']['15']);
    unset($lang->admin->menuList->feature['menuOrder']['20']);
    unset($lang->admin->menuList->feature['menuOrder']['25']);

    $lang->admin->menuList->feature['subMenu']['project'] = array('link' => "{$lang->projectCommon}|custom|execution|", 'exclude' => 'set,required', 'alias' => 'execution');
    $lang->admin->menuList->feature['tabMenu']['my']['task']           = array('link' => "{$lang->task->common}|custom|required|module=task", 'links' => array('custom|set|module=task&field=priList'), 'exclude' => 'custom-required,custom-set');
    $lang->admin->menuList->feature['tabMenu']['project']['project']   = array('link' => "{$lang->projectCommon}|custom|required|module=project", 'exclude' => 'custom-required');
    $lang->admin->menuList->feature['tabMenu']['project']['execution'] = array('link' => "{$lang->executionCommon}|custom|execution|");
    $lang->admin->menuList->feature['tabMenu']['project']['story']     = array('link' => "{$lang->SRCommon}|custom|required|module=story", 'links' => array("custom|set|module=story&field=priList"), 'exclude' => 'custom-required,custom-set');
    $lang->admin->menuList->feature['tabMenu']['menuOrder']['my']['15']      = 'task';
    $lang->admin->menuList->feature['tabMenu']['menuOrder']['project']['5']  = 'project';
    $lang->admin->menuList->feature['tabMenu']['menuOrder']['project']['10'] = 'execution';
    $lang->admin->menuList->feature['tabMenu']['menuOrder']['project']['15'] = 'story';

    $lang->admin->menuList->feature['menuOrder']['15'] = 'project';
}
