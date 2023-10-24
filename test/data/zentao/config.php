<?php
$builder = new stdclass();

$builder->company     = array('rows' => 2,    'extends' => array('company'));
$builder->user        = array('rows' => 1000, 'extends' => array('user'));
$builder->dept        = array('rows' => 100,  'extends' => array('dept'));
//$builder->todo        = array('rows' => 2000, 'extends' => array('todo'));
//$builder->todocycle   = array('rows' => 5, 'extends' => array('todo','todocycle'));
//$builder->effort      = array('rows' => 100, 'extends' => array('effort'));
//$builder->usergroup   = array('rows' => 600, 'extends' => array('usergroup'));
//$builder->usercontact = array('rows' => 61, 'extends' => array('usercontact'));
//$builder->userview    = array('rows' => 400, 'extends' => array('userview'));
//$builder->action      = array('rows' => 100,  'extends' => array('action'));
//$builder->history     = array('rows' => 100,  'extends' => array('history'));
//$builder->file        = array('rows' => 100,  'extends' => array('file'));
//$builder->score       = array('rows' => 100,  'extends' => array('score'));
//$builder->extension   = array('rows' => 10,  'extends' => array('extension'));

$builder->program      = array('rows' => 10, 'extends' => array('project', 'program'));
$builder->project      = array('rows' => 90, 'extends' => array('project', 'project'));
$builder->sprint       = array('rows' => 600, 'extends' => array('project', 'execution'));
$builder->projectalone = array('rows' => 20, 'extends' => array('project', 'projectalone'));
$builder->pipeline     = array('rows' => 5,   'extends' => array('pipeline'));
$builder->repo         = array('rows' => 1,   'extends' => array('repo'));
$builder->job          = array('rows' => 2,   'extends' => array('job'));
$builder->mr           = array('rows' => 1,   'extends' => array('mr'));

//$builder->todo        = array('rows' => 2000, 'extends' => array('todo'));
//$builder->todocycle   = array('rows' => 5, 'extends' => array('todo','todocycle'));
//$builder->effort      = array('rows' => 100, 'extends' => array('effort'));
//$builder->usergroup   = array('rows' => 600, 'extends' => array('usergroup'));
//$builder->usercontact = array('rows' => 61, 'extends' => array('usercontact'));
//$builder->userview    = array('rows' => 400, 'extends' => array('userview'));
//$builder->action      = array('rows' => 100,  'extends' => array('action'));
//$builder->history     = array('rows' => 100,  'extends' => array('history'));
//$builder->file        = array('rows' => 100,  'extends' => array('file'));
//$builder->score       = array('rows' => 100,  'extends' => array('score'));
//$builder->extension   = array('rows' => 10,  'extends' => array('extension'));
//$builder->story             = array('rows' => 400, 'extends' => array('story'));
//$builder->childstory        = array('rows' => 50, 'extends' => array('story','childstory'));
//$builder->storyreview       = array('rows' => 100, 'extends' => array('storyreview'));
//$builder->storymodule       = array('rows' => 800, 'extends' => array('module','storymodule'));
//$builder->storymoduleson    = array('rows' => 400, 'extends' => array('module','storymoduleson'));
//$builder->storyplan         = array('rows' => 400, 'extends' => array('planstory'));
//$builder->storystage        = array('rows' => 450, 'extends' => array('storystage'));
//$builder->storyspec         = array('rows' => 570, 'extends' => array('storyspec'));
//$builder->storyestimate     = array('rows' => 6, 'extends' => array('storyestimate'));
//$builder->relation          = array('rows' => 12, 'extends' => array('relation'));
//$builder->task              = array('rows' => 600, 'extends' => array('task','task'));
//$builder->taskmore          = array('rows' => 300, 'extends' => array('task','moretask'));
//$builder->taskspec          = array('rows' => 600, 'extends' => array('taskspec'));
//$builder->taskmodule        = array('rows' => 1800, 'extends' => array('module','taskmodule'));
//$builder->taskmoduleson     = array('rows' => 600, 'extends' => array('module','taskmoduleson'));
//$builder->taskestimate      = array('rows' => 600, 'extends' => array('taskestimate'));
//$builder->taskson           = array('rows' => 10,  'extends' => array('task', 'taskson'));
//$builder->case              = array('rows' => 400, 'extends' => array('case'));
//$builder->libcase           = array('rows' => 10, 'extends' => array('case','libcase'));
//$builder->unitcase          = array('rows' => 150, 'extends' => array('case','unitcase'));
//$builder->casestep          = array('rows' => 400, 'extends' => array('casestep'));
//$builder->casemodule        = array('rows' => 100, 'extends' => array('module', 'casemodule'));
//$builder->casemoduleson     = array('rows' => 200, 'extends' => array('module', 'casemoduleson'));
//$builder->bug               = array('rows' => 300, 'extends' => array('bug'));
//$builder->morebug           = array('rows' => 15, 'extends' => array('bug','morebug'));
//$builder->bugmodule         = array('rows' => 100, 'extends' => array('module','bugmodule'));
//$builder->bugmoduleson      = array('rows' => 200, 'extends' => array('module','bugmoduleson'));
//$builder->feedback          = array('rows' => 100, 'extends' => array('feedback'));
//$builder->feedbackmodule    = array('rows' => 100, 'extends' => array('module','feedbackmodule'));
//$builder->feedbackmoduleson = array('rows' => 200, 'extends' => array('module','feedbackmoduleson'));
//
//$builder->testtask   = array('rows' => 100, 'extends' => array('testtask'));
//$builder->testresult = array('rows' => 70, 'extends' => array('testresult'));
//$builder->testrun    = array('rows' => 70, 'extends' => array('testrun'));
//$builder->testreport = array('rows' => 10, 'extends' => array('testreport'));
//$builder->testsuite  = array('rows' => 201, 'extends' => array('testsuite'));
//$builder->suitecase  = array('rows' => 400, 'extends' => array('suitecase'));
//
//$builder->product             = array('rows' => 100, 'extends' => array('product'));
//$builder->productalone        = array('rows' => 20, 'extends' => array('product','productalone'));
//$builder->productline         = array('rows' => 20,  'extends' => array('module', 'productline'));
//$builder->productplan         = array('rows' => 70, 'extends' => array('productplan'));
//$builder->productsonplan      = array('rows' => 10, 'extends' => array('productplan', 'productsonplan'));
//$builder->branch              = array('rows' => 240, 'extends' => array('branch'));
//$builder->projectproduct      = array('rows' => 200, 'extends' => array('projectproduct'));
//$builder->projectproductalone = array('rows' => 28, 'extends' => array('projectproduct','projectproductalone'));
//$builder->projectstory        = array('rows' => 200, 'extends' => array('projectstory'));
//$builder->projectcase         = array('rows' => 400, 'extends' => array('projectcase'));
//$builder->executionstory      = array('rows' => 180, 'extends' => array('projectstory','executionstory'));
//
//$builder->kanbanspace  = array('rows' => 50, 'extends' => array('kanbanspace'));
//$builder->kanban       = array('rows' => 100, 'extends' => array('kanban'));
//$builder->kanbanregion = array('rows' => 100, 'extends' => array('kanbanregion','kanbanregion'));
//$builder->kanbangroup  = array('rows' => 100, 'extends' => array('kanbangroup','kanbangroup'));
//$builder->kanbanlane   = array('rows' => 100, 'extends' => array('kanbanlane','kanbanlane'));
//$builder->kanbancolumn = array('rows' => 400, 'extends' => array('kanbancolumn','kanbancolumn'));
//$builder->kanbancard   = array('rows' => 1000, 'extends' => array('kanbancard','kanbancard'));
//$builder->kanbancell   = array('rows' => 400, 'extends' => array('kanbancell','kanbancell'));
//
//$builder->kanbanregionproject = array('rows' => 180, 'extends' => array('kanbanregion','kanbanregionproject'));
//$builder->kanbangroupproject  = array('rows' => 540, 'extends' => array('kanbangroup','kanbangroupproject'));
//$builder->kanbanlaneproject   = array('rows' => 540, 'extends' => array('kanbanlane','kanbanlaneproject'));
//$builder->kanbancolumnproject = array('rows' => 4860, 'extends' => array('kanbancolumn','kanbancolumnproject'));
//$builder->kanbancellproject   = array('rows' => 4860, 'extends' => array('kanbancell','kanbancellproject'));
//
//
//$builder->team        = array('rows' => 400, 'extends' => array('team'));
//$builder->teamtask    = array('rows' => 20, 'extends' => array('team', 'teamtask'));
//$builder->stakeholder = array('rows' => 1, 'extends' => array('stakeholder'));
//$builder->expect      = array('rows' => 270, 'extends' => array('expect'));
//$builder->stageson    = array('rows' => 30, 'extends' => array('project', 'executionson'));
//
//$builder->doclib      = array('rows' => 910, 'extends' => array('doclib'));
//$builder->doc         = array('rows' => 900, 'extends' => array('doc'));
//$builder->doccontent  = array('rows' => 910, 'extends' => array('doccontent'));
//$builder->docmodule   = array('rows' => 100, 'extends' => array('module','docmodule'));
//$builder->docmoduleon = array('rows' => 200, 'extends' => array('module','docmoduleson'));
//
//$builder->build      = array('rows' => 20, 'extends' => array('build'));
//$builder->release    = array('rows' => 10, 'extends' => array('release'));
//$builder->design     = array('rows' => 120, 'extends' => array('design'));
//$builder->designspec = array('rows' => 120, 'extends' => array('designspec'));
//
//$builder->stage        = array('rows' => 6, 'extends' => array('stage'));
//$builder->webhook      = array('rows' => 7, 'extends' => array('webhook'));
//$builder->entry        = array('rows' => 1, 'extends' => array('entry'));
//$builder->log          = array('rows' => 10, 'extends' => array('log'));
//$builder->weeklyreport = array('rows' => 10, 'extends' => array('weeklyreport'));
//$builder->metting      = array('rows' => 10, 'extends' => array('meeting'));
//$builder->usertpl      = array('rows' => 10, 'extends' => array('usertpl'));
//
//$builder->holiday  = array('rows' => 100, 'extends' => array('holiday'));
//$builder->oauth    = array('rows' => 100, 'extends' => array('oauth'));
//$builder->notify   = array('rows' => 10,  'extends' => array('notify'));
