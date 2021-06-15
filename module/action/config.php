<?php
$config->action->objectNameFields['product']     = 'name';
$config->action->objectNameFields['story']       = 'title';
$config->action->objectNameFields['productplan'] = 'title';
$config->action->objectNameFields['release']     = 'name';
$config->action->objectNameFields['program']     = 'name';
$config->action->objectNameFields['project']     = 'name';
$config->action->objectNameFields['execution']   = 'name';
$config->action->objectNameFields['task']        = 'name';
$config->action->objectNameFields['build']       = 'name';
$config->action->objectNameFields['bug']         = 'title';
$config->action->objectNameFields['testcase']    = 'title';
$config->action->objectNameFields['case']        = 'title';
$config->action->objectNameFields['testtask']    = 'name';
$config->action->objectNameFields['user']        = 'account';
$config->action->objectNameFields['doc']         = 'title';
$config->action->objectNameFields['doclib']      = 'name';
$config->action->objectNameFields['todo']        = 'name';
$config->action->objectNameFields['branch']      = 'name';
$config->action->objectNameFields['module']      = 'name';
$config->action->objectNameFields['testsuite']   = 'name';
$config->action->objectNameFields['caselib']     = 'name';
$config->action->objectNameFields['testreport']  = 'title';
$config->action->objectNameFields['entry']       = 'name';
$config->action->objectNameFields['webhook']     = 'name';
$config->action->objectNameFields['risk']        = 'name';
$config->action->objectNameFields['issue']       = 'title';
$config->action->objectNameFields['design']      = 'name';
$config->action->objectNameFields['stakeholder'] = 'user';
$config->action->objectNameFields['budget']      = 'name';
$config->action->objectNameFields['job']         = 'name';
$config->action->objectNameFields['team']        = 'name';

$config->action->commonImgSize = 870;

$config->action->majorList = array();
$config->action->majorList['task']      = array('assigned', 'finished', 'activated');
$config->action->majorList['bug']       = array('assigned', 'resolved');
$config->action->majorList['release']   = array('opened');
$config->action->majorList['build']     = array('opened');
$config->action->majorList['product']   = array('opened', 'edited');
$config->action->majorList['program']   = array('opened', 'edited');
$config->action->majorList['project']   = array('opened', 'edited');
$config->action->majorList['execution'] = array('opened', 'edited');

$config->action->needGetProjectType = 'build,task,bug,case,testcase,caselib,testtask,testsuite,testreport,doc,issue,release,risk,design,opportunity,trainplan,gapanalysis,researchplan,researchreport,';
$config->action->needGetRelateField = ',story,productplan,release,task,build,bug,case,testtask,testreport,doc,doclib,issue,risk,opportunity,trainplan,gapanalysis,team,whitelist,researchplan,researchreport,';
