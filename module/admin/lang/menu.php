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

$lang->admin->menuList->model['name']  = $lang->admin->menuSetting['model']['name'];
$lang->admin->menuList->model['desc']  = $lang->admin->menuSetting['model']['desc'];
$lang->admin->menuList->model['order'] = 20;

$lang->admin->menuList->feature['name']  = $lang->admin->menuSetting['feature']['name'];
$lang->admin->menuList->feature['desc']  = $lang->admin->menuSetting['feature']['desc'];
$lang->admin->menuList->feature['order'] = 25;

$lang->admin->menuList->template['name']  = $lang->admin->menuSetting['template']['name'];
$lang->admin->menuList->template['desc']  = $lang->admin->menuSetting['template']['desc'];
$lang->admin->menuList->template['order'] = 30;

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
$lang->admin->menuList->convert['link']  = 'convert|convertjira';
$lang->admin->menuList->convert['order'] = 50;

$lang->admin->menuList->system['subMenu']['mode']        = array('link' => "{$lang->custom->mode}|custom|mode|");
$lang->admin->menuList->system['subMenu']['trash']       = array('link' => "{$lang->action->trash}|action|trash|");
$lang->admin->menuList->system['subMenu']['safe']        = array('link' => "{$lang->security}|admin|safe|", 'alias' => 'checkweak,resetpwdsetting', 'links' => array('admin|resetpwdsetting|', 'admin|checkweak|'));
$lang->admin->menuList->system['subMenu']['cron']        = array('link' => "{$lang->admin->cron}|cron|index|", 'subModule' => 'cron');
$lang->admin->menuList->system['subMenu']['timezone']    = array('link' => "{$lang->timezone}|custom|timezone|");
$lang->admin->menuList->system['subMenu']['buildindex']  = array('link' => "{$lang->admin->buildIndex}|search|buildindex|");
$lang->admin->menuList->system['subMenu']['tableengine'] = array('link' => "{$lang->admin->tableEngine}|admin|tableengine|");
if($this->config->db->driver == 'mysql') $lang->admin->menuList->system['subMenu']['backup']      = array('link' => "{$lang->backup->common}|backup|index|");

$lang->admin->menuList->system['menuOrder']['5']  = 'mode';
if($this->config->db->driver == 'mysql') $lang->admin->menuList->system['menuOrder']['10'] = 'backup';
$lang->admin->menuList->system['menuOrder']['15'] = 'trash';
$lang->admin->menuList->system['menuOrder']['30'] = 'safe';
$lang->admin->menuList->system['menuOrder']['35'] = 'cron';
$lang->admin->menuList->system['menuOrder']['40'] = 'timezone';
$lang->admin->menuList->system['menuOrder']['45'] = 'buildindex';
$lang->admin->menuList->system['menuOrder']['50'] = 'tableengine';

$lang->admin->menuList->system['dividerMenu'] = ',safe,';

$lang->admin->menuList->company['subMenu']['dept']       = array('link' => "{$lang->dept->common}|dept|browse|", 'subModule' => 'dept');
$lang->admin->menuList->company['subMenu']['browseUser'] = array('link' => "{$lang->user->common}|company|browse|", 'subModule' => 'user,tutorial');
$lang->admin->menuList->company['subMenu']['group']      = array('link' => "{$lang->priv}|group|browse|", 'subModule' => 'group');

$lang->admin->menuList->company['menuOrder']['5']  = 'dept';
$lang->admin->menuList->company['menuOrder']['10'] = 'browseUser';
$lang->admin->menuList->company['menuOrder']['15'] = 'group';

$lang->admin->menuList->model['subMenu']['common']        = array('link' => "{$lang->globalSetting}|custom|required|module=project", 'subModule' => 'custom,subject,holiday,stage', 'exclude' => 'stage-browse,stage-plusbrowse,stage-create,stage-edit,stage-batchcreate');
$lang->admin->menuList->model['subMenu']['scrum']         = array('link' => "{$lang->scrumModel}|auditcl|scrumbrowse|", 'subModule' => 'auditcl');
$lang->admin->menuList->model['subMenu']['waterfall']     = array('link' => "{$lang->waterfallModel}|stage|browse|", 'subModule' => 'stage', 'exclude' => 'stage-settype,stage-plusbrowse');
$lang->admin->menuList->model['subMenu']['agileplus']     = array('link' => "{$lang->agilePlusModel}|auditcl|agileplusbrowse|", 'subModule' => 'auditcl');
$lang->admin->menuList->model['subMenu']['waterfallplus'] = array('link' => "{$lang->waterfallPlusModel}|stage|plusbrowse|", 'subModule' => 'stage', 'exclude' => 'stage-settype,stage-browse');

$lang->admin->menuList->model['menuOrder']['5']  = 'common';
$lang->admin->menuList->model['menuOrder']['10'] = 'scrum';
$lang->admin->menuList->model['menuOrder']['15'] = 'waterfall';
$lang->admin->menuList->model['menuOrder']['20'] = 'agileplus';
$lang->admin->menuList->model['menuOrder']['25'] = 'waterfallplus';

$lang->admin->menuList->model['tabMenu']['common']['project']      = array('link' => "{$lang->project->common}|custom|required|module=project", 'alias' => 'set', 'exclude' => 'custom', 'links' => array('custom|set|module=project&field=unitList'));
if(helper::hasFeature('waterfall') or helper::hasFeature('waterfallplus')) $lang->admin->menuList->model['tabMenu']['common']['stage'] = array('link' => "{$lang->stage->type}|stage|settype|", 'subModule' => 'stage');
$lang->admin->menuList->model['tabMenu']['common']['build']        = array('link' => "{$lang->build->common}|custom|required|module=build", 'alias' => 'set', 'exclude' => 'custom');
$lang->admin->menuList->model['tabMenu']['common']['flow']         = array('link' => "{$lang->custom->flow}|custom|flow|", 'divider' => true);
$lang->admin->menuList->model['tabMenu']['common']['code']         = array('link' => "{$lang->code}|custom|code|");

$lang->admin->menuList->model['tabMenu']['common']['percent']      = array('link' => "{$lang->stage->percent}|custom|percent|");
$lang->admin->menuList->model['tabMenu']['common']['hours']        = array('link' => "{$lang->workingHour}|custom|hours|", 'subModule' => 'holiday', 'links' => array('holiday|browse|', 'custom|hours|'));
if(helper::hasFeature('waterfall')) $lang->admin->menuList->model['tabMenu']['waterfall']['stage'] = array('link' => "{$lang->stage->list}|stage|browse|", 'subModule' => 'stage', 'exclude' => 'stage-plusbrowse');
if(helper::hasFeature('waterfallplus')) $lang->admin->menuList->model['tabMenu']['waterfallplus']['stage'] = array('link' => "{$lang->stage->list}|stage|plusbrowse|", 'subModule' => 'stage', 'exclude' => 'stage-browse');
$lang->admin->menuList->model['tabMenu']['menuOrder']['common']['5']        = 'project';
if(helper::hasFeature('waterfall') or helper::hasFeature('waterfallplus')) $lang->admin->menuList->model['tabMenu']['menuOrder']['common']['7']        = 'stage';
$lang->admin->menuList->model['tabMenu']['menuOrder']['common']['10']       = 'build';
$lang->admin->menuList->model['tabMenu']['menuOrder']['common']['35']       = 'flow';
$lang->admin->menuList->model['tabMenu']['menuOrder']['common']['40']       = 'code';
$lang->admin->menuList->model['tabMenu']['menuOrder']['common']['43']       = 'percent';
$lang->admin->menuList->model['tabMenu']['menuOrder']['common']['45']       = 'hours';
if(helper::hasFeature('waterfall')) $lang->admin->menuList->model['tabMenu']['menuOrder']['waterfall']['5']     = 'stage';
if(helper::hasFeature('waterfallplus')) $lang->admin->menuList->model['tabMenu']['menuOrder']['waterfallplus']['5'] = 'stage';

$lang->admin->menuList->feature['subMenu']['my']          = array('link' => "{$lang->my->common}|custom|set|module=todo&field=priList", 'exclude' => 'set,required');
$lang->admin->menuList->feature['subMenu']['product']     = array('link' => "{$lang->productCommon}|custom|required|module=product", 'exclude' => 'set,required', 'alias' => 'browsestoryconcept,product');
$lang->admin->menuList->feature['subMenu']['execution']   = array('link' => "{$lang->execution->common}|custom|required|module=execution", 'exclude' => 'required,set', 'alias' => 'execution,limittaskdate');
$lang->admin->menuList->feature['subMenu']['qa']          = array('link' => "{$lang->qa->common}|custom|required|module=bug", 'exclude' => 'set,required');
$lang->admin->menuList->feature['subMenu']['kanban']      = array('link' => "{$lang->kanban->common}|custom|kanban|");
$lang->admin->menuList->feature['subMenu']['doc']         = array('link' => "{$lang->doc->common}|custom|required|module=doc", 'exclude' => 'required');
$lang->admin->menuList->feature['subMenu']['user']        = array('link' => "{$lang->user->common}|custom|required|module=user", 'links' => array('custom|set|module=user&field=roleList'), 'exclude' => 'set,required');

$lang->admin->menuList->feature['tabMenu']['my']['todo']  = array('link' => "{$lang->todo->common}|custom|set|module=todo&field=priList", 'exclude' => 'custom-set');
$lang->admin->menuList->feature['tabMenu']['my']['block'] = array('link' => "{$lang->block->common}|custom|set|module=block&field=closed", 'exclude' => 'custom-set');

$lang->admin->menuList->feature['tabMenu']['product']['product']     = array('link' => "{$lang->productCommon}|custom|required|module=product", 'links' => array("custom|browsestoryconcept|", 'custom|product|'), 'alias' => 'browsestoryconcept,product', 'exclude' => 'custom-required');
$lang->admin->menuList->feature['tabMenu']['product']['story']       = array('link' => "{$lang->SRCommon}|custom|required|module=story", 'links' => array("custom|set|module=story&field=categoryList"), 'exclude' => 'custom-required,custom-set');
$lang->admin->menuList->feature['tabMenu']['product']['productplan'] = array('link' => "{$lang->productplan->shortCommon}|custom|required|module=productplan", 'exclude' => 'custom-required');
$lang->admin->menuList->feature['tabMenu']['product']['release']     = array('link' => "{$lang->release->common}|custom|required|module=release", 'exclude' => 'custom-required');

$lang->admin->menuList->feature['tabMenu']['execution']['execution'] = array('link' => "{$lang->execution->common}|custom|required|module=execution", 'links' => array("custom|execution|"), 'alias' => 'execution', 'exclude' => 'custom-required');
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
$lang->admin->menuList->feature['tabMenu']['menuOrder']['product']['10']   = 'story';
$lang->admin->menuList->feature['tabMenu']['menuOrder']['product']['15']   = 'productplan';
$lang->admin->menuList->feature['tabMenu']['menuOrder']['product']['20']   = 'release';
$lang->admin->menuList->feature['tabMenu']['menuOrder']['execution']['5']  = 'execution';
$lang->admin->menuList->feature['tabMenu']['menuOrder']['execution']['10'] = 'task';
$lang->admin->menuList->feature['tabMenu']['menuOrder']['qa']['5']         = 'bug';
$lang->admin->menuList->feature['tabMenu']['menuOrder']['qa']['10']        = 'testcase';
$lang->admin->menuList->feature['tabMenu']['menuOrder']['qa']['15']        = 'testsuite';
$lang->admin->menuList->feature['tabMenu']['menuOrder']['qa']['20']        = 'testtask';
$lang->admin->menuList->feature['tabMenu']['menuOrder']['qa']['25']        = 'testreport';
$lang->admin->menuList->feature['tabMenu']['menuOrder']['qa']['30']        = 'caselib';

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

if(helper::hasFeature('devops'))
{
    $lang->admin->menuList->platform['name']  = $lang->admin->menuSetting['platform']['name'];
    $lang->admin->menuList->platform['desc']  = $lang->admin->menuSetting['platform']['desc'];
    $lang->admin->menuList->platform['order'] = 55;

    //$lang->admin->menuList->platform['subMenu']['environment'] = array('link' => "{$lang->devops->environment}|gitlab|browse|", 'subModule' => 'gitlab,jenkins,sonarqube,gitea,gogs', 'alias' => 'create,edit,import');
    $lang->admin->menuList->platform['subMenu']['resource']    = array('link' => "{$lang->devops->resource}|host|browse|", 'subModule' => 'host,account,serverroom,ops,tree,domain,service');
    $lang->admin->menuList->platform['subMenu']['setrules']    = array('link' => "{$lang->devops->rules}|repo|setrules|");

    $lang->admin->menuList->platform['tabMenu']['resource']['host']       = array('link' => "{$lang->devops->host}|host|browse|", 'alias' => 'create,edit,browse,view,treemap,changestatus,group', 'subModule' => 'tree');
    $lang->admin->menuList->platform['tabMenu']['resource']['serverroom'] = array('link' => "{$lang->devops->serverroom}|serverroom|browse|", 'alias' => 'create,edit,view,browse');
    $lang->admin->menuList->platform['tabMenu']['resource']['service']    = array('link' => "{$lang->devops->service}|service|browse|", 'alias' => 'create,edit,view,browse,manage');
    $lang->admin->menuList->platform['tabMenu']['resource']['account']    = array('link' => "{$lang->devops->account}|account|browse|", 'alias' => 'create,edit,view,browse');
    $lang->admin->menuList->platform['tabMenu']['resource']['domain']     = array('link' => "{$lang->devops->domain}|domain|browse|", 'alias' => 'create,edit,view,browse');
    $lang->admin->menuList->platform['tabMenu']['resource']['provider']   = array('link' => "{$lang->devops->provider}|ops|provider|", 'alias' => 'provider');
    $lang->admin->menuList->platform['tabMenu']['resource']['city']       = array('link' => "{$lang->devops->city}|ops|city|", 'alias' => 'city');
    $lang->admin->menuList->platform['tabMenu']['resource']['cpuBrand']   = array('link' => "{$lang->devops->cpuBrand}|ops|cpuBrand|", 'alias' => 'cpubrand');
    $lang->admin->menuList->platform['tabMenu']['resource']['os']         = array('link' => "{$lang->devops->os}|ops|os|", 'alias' => 'os');
    $lang->admin->menuList->platform['tabMenu']['resource']['stage']      = array('link' => "{$lang->devops->stage}|ops|stage|", 'alias' => 'stage');

    $lang->admin->menuList->platform['tabMenu']['menuOrder']['resource']['10']  = 'host';
    $lang->admin->menuList->platform['tabMenu']['menuOrder']['resource']['15'] = 'serverroom';
    $lang->admin->menuList->platform['tabMenu']['menuOrder']['resource']['20'] = 'service';
    $lang->admin->menuList->platform['tabMenu']['menuOrder']['resource']['25'] = 'account';
    $lang->admin->menuList->platform['tabMenu']['menuOrder']['resource']['30'] = 'domain';
    $lang->admin->menuList->platform['tabMenu']['menuOrder']['resource']['35'] = 'provider';
    $lang->admin->menuList->platform['tabMenu']['menuOrder']['resource']['40'] = 'city';
    $lang->admin->menuList->platform['tabMenu']['menuOrder']['resource']['45'] = 'cpuBrand';
    $lang->admin->menuList->platform['tabMenu']['menuOrder']['resource']['50'] = 'os';
    $lang->admin->menuList->platform['tabMenu']['menuOrder']['resource']['55'] = 'stage';

    if($config->edition === 'open')
    {
        unset($lang->admin->menuList->platform['tabMenu']['resource']['domain']);
        unset($lang->admin->menuList->platform['tabMenu']['resource']['service']);
        unset($lang->admin->menuList->platform['tabMenu']['resource']['stage']);
        unset($lang->admin->menuList->platform['tabMenu']['menuOrder']['resource']['20']);
        unset($lang->admin->menuList->platform['tabMenu']['menuOrder']['resource']['30']);
        unset($lang->admin->menuList->platform['tabMenu']['menuOrder']['resource']['55']);
    }

    //$lang->admin->menuList->platform['menuOrder']['15'] = 'environment';
    $lang->admin->menuList->platform['menuOrder']['20'] = 'resource';
    $lang->admin->menuList->platform['menuOrder']['25'] = 'setrules';

    $lang->admin->menuList->platform['dividerMenu'] = ',plat,setrules,';
}

if($config->vision != 'or')
{
    $lang->admin->menuList->ai['name']  = $lang->admin->menuSetting['ai']['name'];
    $lang->admin->menuList->ai['desc']  = $lang->admin->menuSetting['ai']['desc'];
    $lang->admin->menuList->ai['link']  = 'ai|adminindex';
    $lang->admin->menuList->ai['order'] = 60;

    $lang->admin->menuList->ai['subMenu']['prompts']       = array('link' => "{$lang->admin->ai->prompt}|ai|prompts|", 'alias' => 'promptview,promptassignrole,promptselectdatasource,promptsetpurpose,promptsettargetform,promptfinalize,promptedit');
    // $lang->admin->menuList->ai['subMenu']['conversations'] = array('link' => "{$lang->admin->ai->conversation}|ai|conversations|");
    $lang->admin->menuList->ai['subMenu']['models']        = array('link' => "{$lang->admin->ai->model}|ai|models|", 'alias' => 'editmodel');

    $lang->admin->menuList->ai['menuOrder']['5']  = 'prompts';
    // $lang->admin->menuList->ai['menuOrder']['10'] = 'conversations';
    $lang->admin->menuList->ai['menuOrder']['15'] = 'models';
}

if($config->edition != 'max' and $config->edition != 'ipd')
{
    unset($lang->admin->menuList->model['subMenu']['scrum']);
    unset($lang->admin->menuList->model['subMenu']['agileplus']);
    unset($lang->admin->menuList->model['menuOrder']['10']);
    unset($lang->admin->menuList->model['menuOrder']['20']);
    unset($lang->admin->menuList->template);
}
if(!helper::hasFeature('waterfall'))
{
    unset($lang->admin->menuList->model['subMenu']['waterfall']);
    unset($lang->admin->menuList->model['menuOrder']['15']);
}
if(!helper::hasFeature('waterfallplus'))
{
    unset($lang->admin->menuList->model['subMenu']['waterfallplus']);
    unset($lang->admin->menuList->model['menuOrder']['25']);
}
if($config->edition == 'max' or $config->edition == 'ipd')
{
    if(!helper::hasFeature('scrum_auditplan') and !helper::hasFeature('scrum_process')) unset($lang->admin->menuList->model['subMenu']['scrum'], $lang->admin->menuList->model['menuOrder']['10']);
    if(!helper::hasFeature('agileplus_auditplan') and !helper::hasFeature('agileplus_process')) unset($lang->admin->menuList->model['subMenu']['agileplus'], $lang->admin->menuList->model['menuOrder']['20']);
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
    unset($lang->admin->menuList->model['subMenu']['agileplus']);
    unset($lang->admin->menuList->model['subMenu']['waterfallplus']);
    unset($lang->admin->menuList->model['menuOrder']['10']);
    unset($lang->admin->menuList->model['menuOrder']['15']);
    unset($lang->admin->menuList->model['menuOrder']['20']);
    unset($lang->admin->menuList->model['menuOrder']['25']);

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
    $lang->admin->menuList->feature['tabMenu']['project']['execution'] = array('link' => "{$lang->execution->common}|custom|execution|");
    $lang->admin->menuList->feature['tabMenu']['project']['story']     = array('link' => "{$lang->SRCommon}|custom|required|module=story", 'links' => array("custom|set|module=story&field=priList"), 'exclude' => 'custom-required,custom-set');
    $lang->admin->menuList->feature['tabMenu']['menuOrder']['my']['15']      = 'task';
    $lang->admin->menuList->feature['tabMenu']['menuOrder']['project']['5']  = 'project';
    $lang->admin->menuList->feature['tabMenu']['menuOrder']['project']['10'] = 'execution';
    $lang->admin->menuList->feature['tabMenu']['menuOrder']['project']['15'] = 'story';

    $lang->admin->menuList->feature['menuOrder']['15'] = 'project';
}

if($config->inQuickon && helper::hasFeature('devops'))
{
    $dashboard = is_object($lang->dashboard) ? $lang->dashboard->common : $lang->dashboard;
    $lang->admin->menuList->platform['subMenu']['dashboard'] = array('link' => "{$dashboard}|system|dashboard|");
    $lang->admin->menuList->platform['menuOrder']['5']       = 'dashboard';

    $lang->admin->menuList->system['subMenu']['backup'] = array('link' => "{$lang->backup->common}|system|browsebackup|", 'alias' => 'restorebackup');

    $lang->admin->menuList->platform['subMenu']['plat'] = array('link' => "{$lang->devops->platform}|system|dblist|", 'subModule' => 'system');
    $lang->admin->menuList->platform['menuOrder']['10'] = 'plat';

    $lang->admin->menuList->platform['tabMenu']['plat']['dblist'] = array('link' => "{$lang->devops->dblist}|system|dblist|");
    $lang->admin->menuList->platform['tabMenu']['plat']['domain'] = array('link' => "{$lang->devops->domain}|system|configdomain|", 'alias' => 'editdomain,domainview');
    $lang->admin->menuList->platform['tabMenu']['plat']['oss']    = array('link' => "{$lang->devops->oss}|system|ossview|");

    $lang->admin->menuList->platform['tabMenu']['menuOrder']['plat']['10'] = 'dblist';
    $lang->admin->menuList->platform['tabMenu']['menuOrder']['plat']['15'] = 'domain';
    $lang->admin->menuList->platform['tabMenu']['menuOrder']['plat']['20'] = 'oss';
}
