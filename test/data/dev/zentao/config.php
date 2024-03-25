<?php
$builder = new stdclass();

$builder->company        = array('rows' => 2,    'extends' => array('company'));
$builder->user           = array('rows' => 10000, 'extends' => array('user'));
$builder->dept           = array('rows' => 100,  'extends' => array('dept'));
$builder->product        = array('rows' => 1000,  'extends' => array('product'));
$builder->productplan    = array('rows' => 1000,  'extends' => array('productplan'));
$builder->story          = array('rows' => 50000,  'extends' => array('story'));
$builder->program        = array('rows' => 1000, 'extends' => array('project', 'program'));
$builder->project        = array('rows' => 19000, 'extends' => array('project'));
$builder->projectproduct = array('rows' => 19000, 'extends' => array('projectproduct'));
$builder->projectstory   = array('rows' => 50000, 'extends' => array('projectstory'));
$builder->sprint         = array('rows' => 30000, 'extends' => array('project', 'execution'));
$builder->task           = array('rows' => 500000, 'extends' => array('task'));
$builder->bug            = array('rows' => 500000, 'extends' => array('bug'));
$builder->todo        = array('rows' => 2000, 'extends' => array('todo'));
$builder->effort      = array('rows' => 100, 'extends' => array('effort'));
$builder->usergroup   = array('rows' => 600, 'extends' => array('usergroup'));
$builder->usercontact = array('rows' => 61, 'extends' => array('usercontact'));
$builder->userview    = array('rows' => 4000, 'extends' => array('userview'));
$builder->action      = array('rows' => 654000,  'extends' => array('action'));
$builder->history     = array('rows' => 500000,  'extends' => array('history'));
$builder->file        = array('rows' => 100,  'extends' => array('file'));
$builder->score       = array('rows' => 100,  'extends' => array('score'));
$builder->extension   = array('rows' => 10,  'extends' => array('extension'));

$builder->pipeline     = array('rows' => 5,   'extends' => array('pipeline'));
$builder->repo         = array('rows' => 1,   'extends' => array('repo'));
$builder->job          = array('rows' => 2,   'extends' => array('job'));
$builder->mr           = array('rows' => 1,   'extends' => array('mr'));

$builder->storyreview       = array('rows' => 1000, 'extends' => array('storyreview'));
$builder->storymodule       = array('rows' => 800, 'extends' => array('module','storymodule'));
$builder->storymoduleson    = array('rows' => 400, 'extends' => array('module','storymoduleson'));
$builder->storyplan         = array('rows' => 10000, 'extends' => array('planstory'));
$builder->storystage        = array('rows' => 50000, 'extends' => array('storystage'));
$builder->storyspec         = array('rows' => 570, 'extends' => array('storyspec'));
$builder->storyestimate     = array('rows' => 6, 'extends' => array('storyestimate'));
$builder->relation          = array('rows' => 12, 'extends' => array('relation'));
$builder->taskspec          = array('rows' => 600, 'extends' => array('taskspec'));
$builder->taskmodule        = array('rows' => 1800, 'extends' => array('module','taskmodule'));
$builder->taskmoduleson     = array('rows' => 600, 'extends' => array('module','taskmoduleson'));
$builder->taskestimate      = array('rows' => 100000, 'extends' => array('taskestimate'));
$builder->case              = array('rows' => 100000, 'extends' => array('case'));
$builder->casestep          = array('rows' => 400, 'extends' => array('casestep'));
$builder->casemodule        = array('rows' => 100, 'extends' => array('module', 'casemodule'));
$builder->casemoduleson     = array('rows' => 200, 'extends' => array('module', 'casemoduleson'));
$builder->bugmodule         = array('rows' => 100, 'extends' => array('module','bugmodule'));
$builder->bugmoduleson      = array('rows' => 200, 'extends' => array('module','bugmoduleson'));
$builder->feedback          = array('rows' => 100, 'extends' => array('feedback'));
$builder->feedbackmodule    = array('rows' => 100, 'extends' => array('module','feedbackmodule'));
$builder->feedbackmoduleson = array('rows' => 200, 'extends' => array('module','feedbackmoduleson'));

$builder->testtask   = array('rows' => 1000, 'extends' => array('testtask'));
$builder->testresult = array('rows' => 70, 'extends' => array('testresult'));
$builder->testrun    = array('rows' => 70, 'extends' => array('testrun'));
$builder->testreport = array('rows' => 1000, 'extends' => array('testreport'));
$builder->testsuite  = array('rows' => 201, 'extends' => array('testsuite'));
$builder->suitecase  = array('rows' => 400, 'extends' => array('suitecase'));
//
$builder->productline         = array('rows' => 20,  'extends' => array('module', 'productline'));
$builder->branch              = array('rows' => 240, 'extends' => array('branch'));
$builder->projectcase         = array('rows' => 4000, 'extends' => array('projectcase'));
$builder->executionstory      = array('rows' => 50000, 'extends' => array('projectstory','executionstory'));
//
$builder->kanbanspace  = array('rows' => 50, 'extends' => array('kanbanspace'));
$builder->kanban       = array('rows' => 100, 'extends' => array('kanban'));
$builder->kanbanregion = array('rows' => 100, 'extends' => array('kanbanregion','kanbanregion'));
$builder->kanbangroup  = array('rows' => 100, 'extends' => array('kanbangroup','kanbangroup'));
$builder->kanbanlane   = array('rows' => 100, 'extends' => array('kanbanlane','kanbanlane'));
$builder->kanbancolumn = array('rows' => 400, 'extends' => array('kanbancolumn','kanbancolumn'));
$builder->kanbancard   = array('rows' => 1000, 'extends' => array('kanbancard','kanbancard'));
$builder->kanbancell   = array('rows' => 400, 'extends' => array('kanbancell','kanbancell'));

$builder->team        = array('rows' => 100000, 'extends' => array('team'));
$builder->expect      = array('rows' => 270, 'extends' => array('expect'));

$builder->doclib      = array('rows' => 910, 'extends' => array('doclib'));
$builder->doc         = array('rows' => 900, 'extends' => array('doc'));
$builder->doccontent  = array('rows' => 910, 'extends' => array('doccontent'));
$builder->docmodule   = array('rows' => 100, 'extends' => array('module','docmodule'));
$builder->docmoduleon = array('rows' => 200, 'extends' => array('module','docmoduleson'));

$builder->build      = array('rows' => 1000, 'extends' => array('build'));
$builder->release    = array('rows' => 1000, 'extends' => array('release'));
$builder->webhook      = array('rows' => 7, 'extends' => array('webhook'));
$builder->entry        = array('rows' => 1, 'extends' => array('entry'));
$builder->log          = array('rows' => 10, 'extends' => array('log'));
$builder->weeklyreport = array('rows' => 10, 'extends' => array('weeklyreport'));
$builder->metting      = array('rows' => 10, 'extends' => array('meeting'));
$builder->usertpl      = array('rows' => 10, 'extends' => array('usertpl'));
//
$builder->holiday  = array('rows' => 100, 'extends' => array('holiday'));
$builder->oauth    = array('rows' => 100, 'extends' => array('oauth'));
$builder->notify   = array('rows' => 10,  'extends' => array('notify'));
//
//$builder->kanbanregionproject = array('rows' => 180, 'extends' => array('kanbanregion','kanbanregionproject'));
//$builder->kanbangroupproject  = array('rows' => 540, 'extends' => array('kanbangroup','kanbangroupproject'));
//$builder->kanbanlaneproject   = array('rows' => 540, 'extends' => array('kanbanlane','kanbanlaneproject'));
//$builder->kanbancolumnproject = array('rows' => 4860, 'extends' => array('kanbancolumn','kanbancolumnproject'));
//$builder->kanbancellproject   = array('rows' => 4860, 'extends' => array('kanbancell','kanbancellproject'));
