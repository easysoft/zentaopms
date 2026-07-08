<?php
$config->codescan->form = new stdclass();

$now = time();

$config->codescan->form->createRuleset['name'] = array('type' => 'string', 'required' => true);
$config->codescan->form->createRuleset['lang'] = array('type' => 'array',  'required' => true,  'default' => array(), 'filter' => 'join');
$config->codescan->form->createRuleset['desc'] = array('type' => 'string', 'required' => false, 'default' => '');

$config->codescan->form->editRuleset['name'] = array('type' => 'string', 'required' => true);
$config->codescan->form->editRuleset['lang'] = array('type' => 'array',  'required' => true,  'default' => array(), 'filter' => 'join');
$config->codescan->form->editRuleset['desc'] = array('type' => 'string', 'required' => false, 'default' => '');

$config->codescan->form->createSolution['name']        = array('type' => 'string', 'required' => true);
$config->codescan->form->createSolution['rulesets']    = array('type' => 'array',  'required' => true,  'default' => array());
$config->codescan->form->createSolution['status']      = array('type' => 'string', 'required' => false, 'default' => 'enabled');
$config->codescan->form->createSolution['desc']        = array('type' => 'string', 'required' => false, 'default' => '');
$config->codescan->form->createSolution['createdDate'] = array('type' => 'string', 'required' => false, 'default' => $now);

$config->codescan->form->editSolution['name']       = array('type' => 'string', 'required' => true);
$config->codescan->form->editSolution['rulesets']   = array('type' => 'array',  'required' => true,  'default' => array());
$config->codescan->form->editSolution['desc']       = array('type' => 'string', 'required' => false, 'default' => '');
$config->codescan->form->editSolution['editedDate'] = array('type' => 'string', 'required' => false, 'default' => $now);

$config->codescan->form->createPlan['name']        = array('type' => 'string', 'required' => true);
$config->codescan->form->createPlan['repo']        = array('type' => 'int',    'required' => true);
$config->codescan->form->createPlan['branch']      = array('type' => 'array',  'required' => false, 'default' => array(), 'filter' => 'join');
$config->codescan->form->createPlan['branchReg']   = array('type' => 'string', 'required' => false, 'default' => '');
$config->codescan->form->createPlan['excludePath'] = array('type' => 'string', 'required' => false, 'default' => '');
$config->codescan->form->createPlan['excludeFile'] = array('type' => 'string', 'required' => false, 'default' => '');
$config->codescan->form->createPlan['scope']       = array('type' => 'string', 'required' => true);
$config->codescan->form->createPlan['solutions']   = array('type' => 'array',  'required' => true,  'filter' => 'join');
$config->codescan->form->createPlan['severity']    = array('type' => 'array',  'required' => false, 'default' => array());
$config->codescan->form->createPlan['type']        = array('type' => 'array',  'required' => false, 'default' => array());
$config->codescan->form->createPlan['metric']      = array('type' => 'array',  'required' => false, 'default' => array());
$config->codescan->form->createPlan['threshold']   = array('type' => 'array',  'required' => false, 'default' => array());
$config->codescan->form->createPlan['createdBy']   = array('type' => 'string', 'required' => false, 'default' => $now);

$config->codescan->form->editPlan = $config->codescan->form->createPlan;
$config->codescan->form->editPlan['editedBy'] = array('type' => 'string', 'required' => false, 'default' => $now);
unset($config->codescan->form->editPlan['createdBy']);

$config->codescan->form->createTrigger['name']        = array('type' => 'string', 'required' => true);
$config->codescan->form->createTrigger['triggerType'] = array('type' => 'string', 'required' => true);
$config->codescan->form->createTrigger['operation']   = array('type' => 'string', 'required' => false, 'default' => '');
$config->codescan->form->createTrigger['keywords']    = array('type' => 'string', 'required' => false, 'default' => '');
$config->codescan->form->createTrigger['minute']      = array('type' => 'string', 'required' => false, 'default' => '');
$config->codescan->form->createTrigger['hour']        = array('type' => 'string', 'required' => false, 'default' => '');
$config->codescan->form->createTrigger['day']         = array('type' => 'string', 'required' => false, 'default' => '');
$config->codescan->form->createTrigger['month']       = array('type' => 'string', 'required' => false, 'default' => '');
$config->codescan->form->createTrigger['week']        = array('type' => 'string', 'required' => false, 'default' => '');
$config->codescan->form->createTrigger['cronBranch']  = array('type' => 'string', 'required' => false, 'default' => '');
$config->codescan->form->createTrigger['scanType']    = array('type' => 'string', 'required' => true);
$config->codescan->form->createTrigger['solutionIDs'] = array('type' => 'array',  'required' => true);
$config->codescan->form->createTrigger['severity']    = array('type' => 'array',  'required' => false, 'default' => array());
$config->codescan->form->createTrigger['type']        = array('type' => 'array',  'required' => false, 'default' => array());
$config->codescan->form->createTrigger['metric']      = array('type' => 'array',  'required' => false, 'default' => array());
$config->codescan->form->createTrigger['threshold']   = array('type' => 'array',  'required' => false, 'default' => array());

$config->codescan->form->editTrigger = $config->codescan->form->createTrigger;
$config->codescan->form->editTrigger['updatedDate'] = array('type' => 'string', 'required' => false, 'default' => $now);
