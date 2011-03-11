<?php
$help->file->labels = 'Attatch file name|You can specify the file name manually. If empty, use the file name.';

$help->bug->product       = 'Product|Set product of the bug.';
$help->bug->module        = 'Module|Note: the modules of bug, testcase and stories are seperated.';
$help->bug->project       = 'Project| The project this bug belongs to.';
$help->bug->story         = 'Story|The related story for this bug.';
$help->bug->task          = 'Task|The related task.';
$help->bug->title         = 'Title|The most important field for bug, should be clean and meaing.';
$help->bug->severity      = 'Severity|The severity of the bug, 1 is most severity. Also the team can define the severity themselves.';
$help->bug->pri           = "Priority|The priority of the bug, it's should be defind by the project manager or the QA manager.";
$help->bug->type          = 'Bug type|';
$help->bug->os            = 'OS|';
$help->bug->browser       = 'Browser';
$help->bug->steps         = 'Reproduce steps|Very important, should list the steps clean thus the developer can reproduct it.。';
$help->bug->status        = 'Bug status';
$help->bug->mailto        = 'Mailto|You can input the user account to select users to mail to.';
$help->bug->openedby      = 'OpenedBy|The opener of the bug.';
$help->bug->openedbuild   = 'Opened build|If the build is empty, should add them in project view.';
$help->bug->assignedto    = 'AssignedTo|';
$help->bug->resolvedby    = 'ResolvedBy|';
$help->bug->resolution    = 'Resolution|';
$help->bug->resolvedbuild = 'Resolved build|In which build this bug was fixed.';
$help->bug->closedby      = 'Closed by|';
$help->bug->closeddate    = 'Closed date|';
$help->bug->duplicatebug  = 'Duplicated bug|';
$help->bug->linkbug       = 'Related bug| You can input more bugs, use "," to join theme.';
$help->bug->case          = 'Related testcase|';
$help->bug->keywords      = 'Keywords|';

$help->build->product  = 'Product|The product which the build belongs to.';
$help->build->project  = 'Project|The project whcic the build belongs to';
$help->build->name     = 'Build name|The build name, for example zentaopms.1.5.beta1.20110315';
$help->build->date     = 'Build date|The building date';
$help->build->builder  = 'Builder|Builder';
$help->build->scmpath  = 'Source url|If the team is useing subversion and so on, can set the tag url path.';
$help->build->filepath = 'Package path|The file path of the package.';
$help->build->desc     = 'Description|Finished tasks, stories or fixed bugs';

$help->company->name     = 'Company name';
$help->company->phone    = 'Phone';
$help->company->fax      = 'Fax';
$help->company->address  = 'Address';
$help->company->zipcode  = 'zipcode';
$help->company->website  = 'The web site|The web site of the company, appears at the top left menu. With http://.';
$help->company->backyard = 'The intranet site|The intranet site of the company, with http://.';
$help->company->pms      = 'The zentao site.|The domain of the zentao pms, just domain, no http.';
$help->company->guest    = 'Allow guest user login?|If set to true, you should create a user group named guest and grant priviledges to it.';

$help->convert->dbhost     = 'Database host|The source database host.';
$help->convert->dbport     = 'Database port|In most case it is 3306.';
$help->convert->dbuser     = 'Database user';
$help->convert->dbpassword = 'Database password|';
$help->convert->dbname     = 'Database name|。';
$help->convert->dbprefix   = 'The prefix of the table';
$help->convert->installpath= 'The install path of the source system.';

$help->dept->depts  = 'Sub departments';
$help->dept->orders = 'Order nubmer';

$help->group->name = 'Group name|If the company allowes guest login, you should create a group named "guest"';
$help->group->desc = 'Group description';

$help->install->webroot     = 'The web root of zentao|When installing, the system will compute the root auto. You can change it in config/my.php.';
$help->install->requesttype = 'The request type|GET or PATH_INFO, the GET type can work in any case. But PATH_INFO needs you to set up the rewrite feature in apache.';
$help->install->defaultlang = 'Default language|The default language';
$help->install->dbhost      = 'Database host|In most case is localhost, also you can try 127.0.0.1';
$help->install->dbport      = 'Database port|In most case it is 3306.';
$help->install->dbuser      = 'The database user';
$help->install->dbpassword  = 'The database password|。';
$help->install->dbname      = 'The database name zentao to use';
$help->install->dbprefix    = 'The prefix of tables|To avoid conflics with other systems with same table name.';
$help->install->cleardb     = 'Clear data|If there is alread a database with the same name, you can try clear data to reinstall.';
$help->install->company     = 'Company|Company name.';
$help->install->pms         = 'The pms site.|The system will compute it auto, don not change it if you are not sure.';
$help->install->account     = 'Admina account|This admin user ia the super admin';
$help->install->password    = 'Admin password';

$help->product->name   = 'Product name';
$help->product->code   = 'Product code';
$help->product->po     = 'Product owner|Responsible for stories of this product.';
$help->product->rm     = 'Release manager|The people resonsible for building packages and release them.';
$help->product->qm     = 'Test manager';
$help->product->status = 'Status';
$help->product->desc   = 'Description';

$help->productplan->product = 'Product';
$help->productplan->title   = 'Title';
$help->productplan->desc    = 'Desc';
$help->productplan->begin   = 'Begin date';
$help->productplan->end     = 'End date';

$help->project->name   = 'Project name';
$help->project->code   = 'Project code';
$help->project->begin  = 'Begin date';
$help->project->end    = "End date|If you are using scrum it should no more than 30 days";
$help->project->team   = 'Team name';
$help->project->status = 'Project status|Only runing projects can appears in the home page';
$help->project->desc   = 'Description';
$help->project->goal   = 'The goal of the project';

$help->release->product = 'Product';
$help->release->build   = 'Build|The related build.';
$help->release->name    = 'Release name|For example, zentaopms1.5 stable';
$help->release->date    = 'Release date';
$help->release->desc    = 'Release description|Can be change log, install help and so on';

$help->story->product        = 'Product';
$help->story->module         = 'Module|Can use module to manage story.';
$help->story->plan           = 'Product plan|Through plan, can give the team a overview of the product';
$help->story->title          = 'Title';
$help->story->spec           = 'The story spec';
$help->story->pri            = 'Priority|When selected to project, the story should be ordered by the priority field first.';
$help->story->estimate       = 'Estimate|Estimate the story point.';
$help->story->status         = 'Status|Only the active story can be added to a project. The default status is draft, so should be reviewed.';
$help->story->stage          = 'Developping stage';
$help->story->mailto         = 'Mail to';
$help->story->openedby       = 'Opened by';
$help->story->openeddate     = 'Opened date';
$help->story->assignedto     = 'Assigned to|Who ownes to this story now';
$help->story->assigneddate   = 'Assgigned date';
$help->story->closedby       = 'Closed by';
$help->story->closeddate     = 'Closed date';
$help->story->closedreason   = 'Closed reason|When a story closed, should give the closed reason';
$help->story->rejectedreason = 'Rejected reason|When a story is rejected, should give the rejected reason';
$help->story->reviewedby     = 'Reviewed by|Who reviewed the story, can be some peoples in a meeting';
$help->story->revieweddate   = 'Reviewed date';
$help->story->comment        = 'Comment';
$help->story->linkstories    = 'Related stories|can input the story id, seperated by ","';
$help->story->childstories   = 'Child stories|If the story is to huge, it can be divided into some child stories.';
$help->story->duplicatestory = 'Duplicated story';
$help->story->reviewresult   = 'Review result';
$help->story->keywords       = 'Keywords';
$help->story->neednotreview  = 'Need not review|If you are the owner of the proudct, can check it.';

$help->task->project    = 'Project';
$help->task->story      = 'Related Story';
$help->task->name       = 'Task name';
$help->task->type       = 'Task type';
$help->task->pri        = 'Task priority';
$help->task->assignedto = 'Assigned to|The task owner.';
$help->task->estimate   = 'Estimate|The time estimated for this task';
$help->task->left       = 'Left hour|The left hours estimated. Should updated every day, thus to draw the burndown chart.';
$help->task->consumed   = 'Consumed|The consumed times for this task';
$help->task->deadline   = 'Deadline|the deadline of this task';
$help->task->status     = 'Status';
$help->task->desc       = 'Description';

$help->testcase->product    = 'Product';
$help->testcase->module     = 'Module|The modules of testcase is seperated from story modules.';
$help->testcase->story      = 'Story|The related story。';
$help->testcase->title      = 'Title|The title of the test case.';
$help->testcase->pri        = 'Priority';
$help->testcase->type       = 'Test case type|';
$help->testcase->status     = 'Status';
$help->testcase->steps      = 'Case steps|';
$help->testcase->openedby   = 'OpenedBy';
$help->testcase->openeddate = 'OpenedDate';
$help->testcase->result     = 'Test result|';
$help->testcase->real       = 'The real|The real result of the test case.';
$help->testcase->keywords   = 'Keywords';
$help->testcase->linkcase   = 'Related cases.';
$help->testcase->stage      = 'Applicative stage';

$help->testtask->product     = 'Product|Belongs to which product.';
$help->testtask->project     = 'Project|Belongs to which project.';
$help->testtask->build       = 'Build|The build to test.';
$help->testtask->name        = 'Task name';
$help->testtask->begin       = 'Begin date';
$help->testtask->end         = 'End date';
$help->testtask->desc        = 'Task description';
$help->testtask->status      = 'Test task status';
$help->testtask->assignedto  = 'Assigned to|Who in charge of the testcase.';
$help->testtask->linkversion = 'Link version|The version of the case to run.';
$help->testtask->lastrun     = 'Last runed by';
$help->testtask->lastresult  = 'Last result';

$help->todo->date        = 'Date|';
$help->todo->begin       = 'Begin time|';
$help->todo->end         = 'End time|';
$help->todo->type        = 'Type|Custom, bug or task. You can link a task or bug assigned to you and add it as a todo.';
$help->todo->pri         = 'Priority';
$help->todo->name        = 'Todo name';
$help->todo->status      = 'Status';
$help->todo->desc        = 'Description';
$help->todo->private     = 'Is private|If set as private, nobody can see it';

$help->user->account   = 'Account|Should contain letters, underline or numbers, three above.';
$help->user->password  = 'Password|Six above';
$help->user->password2 = 'Repeat password to confirm';
$help->user->realname  = 'Realname';
$help->user->email     = 'Email|Very important field, it is the default notify tool in zentao..';
$help->user->join      = 'Join date|The date the employee join the company.';
$help->user->visits    = 'Visit counts';
$help->user->ip        = 'Last login ip';
$help->user->last      = 'Last login time';

$help->my->date        = 'Select date|Select the data of todoes.';
$help->user->date      = 'Select date|Select the data of todoes.';

$help->doc->product    = 'Product';
$help->doc->project    = 'Project';
$help->doc->library    = 'Library';
$help->doc->module     = 'Doc category';
$help->doc->type       = 'Doc type';
$help->doc->title      = 'Doc title';
$help->doc->digest     = 'Doc digest';
$help->doc->url        = 'The url';
