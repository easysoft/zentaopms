<?php
$lang->my->common = 'Tableau de Bord';

/* Method List。*/
$lang->my->index           = 'Accueil';
$lang->my->todo            = 'Mon Agenda';
$lang->my->calendar        = 'Calendrier';
$lang->my->work            = 'Work';
$lang->my->contribute      = 'Contribute';
$lang->my->task            = 'Mes Tâches';
$lang->my->bug             = 'Mes Bugs';
$lang->my->testTask        = 'Mes Builds';
$lang->my->testCase        = 'Mes CasTests';
$lang->my->story           = 'Mes Stories';
$lang->my->createProgram   = 'Create Program';
$lang->my->project         = "My Projects";
$lang->my->execution       = "My {$lang->execution->common}s";
$lang->my->issue           = 'My Issues';
$lang->my->risk            = 'My Risks';
$lang->my->profile         = 'Mon Profil';
$lang->my->dynamic         = 'Mon Historique';
$lang->my->team            = 'My Team';
$lang->my->editProfile     = 'Edit Profil';
$lang->my->changePassword  = 'Changer le Mot de Passe';
$lang->my->preference      = 'Preference';
$lang->my->unbind          = 'Dissocier de Zdoo';
$lang->my->manageContacts  = 'Gérer Contact';
$lang->my->deleteContacts  = 'Supprimer Contact';
$lang->my->shareContacts   = 'Public';
$lang->my->limited         = 'Actions Limitées (Les utilisateurs peuvent seulement éditer le contenu de ce qui leur est affecté.)';
$lang->my->storyConcept    = 'Story Concept';
$lang->my->score           = 'Mes Points';
$lang->my->scoreRule       = 'Règles Point';
$lang->my->noTodo          = "Je n'ai rien à faire pour l'instant.";
$lang->my->noData          = 'No %s yet. ';
$lang->my->storyChanged    = "Story Changed";
$lang->my->hours           = 'Heure/jour';
$lang->my->uploadAvatar    = 'Upload Avatar';
$lang->my->requirement     = "My {$lang->URCommon}";
$lang->my->testtask        = 'My Test Task';
$lang->my->testcase        = 'My Case';

$lang->my->myExecutions = 'My Stage/Sprint/Iteration';
$lang->my->name         = 'Name';
$lang->my->code         = 'Code';
$lang->my->projects     = 'Project';
$lang->my->executions   = $lang->execution->common;

$lang->my->executionMenu = new stdclass();
$lang->my->executionMenu->undone = 'Undone';
$lang->my->executionMenu->done   = 'Done';

$lang->my->taskMenu = new stdclass();
$lang->my->taskMenu->assignedToMe = "Tâches qui me sont affectées";
$lang->my->taskMenu->openedByMe   = "Tâches que j'ai créées";
$lang->my->taskMenu->finishedByMe = "Tâches que j'ai terminées";
$lang->my->taskMenu->closedByMe   = "Tâches que j'ai fermées";
$lang->my->taskMenu->canceledByMe = "Tâches que j'ai annulées";

$lang->my->storyMenu = new stdclass();
$lang->my->storyMenu->assignedToMe = 'Stories qui me sont affectées';
$lang->my->storyMenu->openedByMe   = "Stories que j'ai créées";
$lang->my->storyMenu->reviewedByMe = "Stories que j'ai acceptées";
$lang->my->storyMenu->closedByMe   = "Stories que j'ai fermées";

$lang->my->projectMenu = new stdclass();
$lang->my->projectMenu->doing      = 'Doing';
$lang->my->projectMenu->wait       = 'Waiting';
$lang->my->projectMenu->suspended  = 'Suspended';
$lang->my->projectMenu->closed     = 'Closed';
$lang->my->projectMenu->openedbyme = 'CreatedByMe';

$lang->my->home = new stdclass();
$lang->my->home->latest        = 'Historique';
$lang->my->home->action        = "%s, %s <em>%s</em> %s <a href='%s'>%s</a>.";
$lang->my->home->projects      = $lang->executionCommon;
$lang->my->home->products      = $lang->productCommon;
$lang->my->home->createProject = "Créer un {$lang->executionCommon}";
$lang->my->home->createProduct = "Créer un {$lang->productCommon}";
$lang->my->home->help          = "<a href='https://www.zentao.pm/book/zentaomanual/free-open-source-project-management-software-workflow-46.html' target='_blank'>Aide</a>";
$lang->my->home->noProductsTip = "Aucun {$lang->productCommon} ici.";

$lang->my->form = new stdclass();
$lang->my->form->lblBasic   = 'Infos Basiques';
$lang->my->form->lblContact = 'Infos Contact';
$lang->my->form->lblAccount = 'Infos Compte';

$lang->my->programLink = 'Program Default Page';
$lang->my->productLink = 'Product Default Page'; 
$lang->my->projectLink = 'Project Default Page';

$lang->my->programLinkList = array();
//$lang->my->programLinkList['program-home']     = 'The default access to the program home page, you can understand the company’s overall strategic planning status';
$lang->my->programLinkList['program-pgmbrowse']  = 'By default, you go to the program list, where you can view all of the programs';
//$lang->my->programLinkList['program-pgmindex'] = 'By default, you go to the most recent program dashboard to see the current program overview';
$lang->my->programLinkList['program-pgmproject'] = 'By default, you go to the list of items in the most recent program, and you can view all items under the current program';

$lang->my->productLinkList = array();
$lang->my->productLinkList['product-index']     = 'The default access to the product home page, you can understand the company’s overall product status';
$lang->my->productLinkList['product-all']       = 'By default, you go to the product list, where you can view all the products';
$lang->my->productLinkList['product-dashboard'] = 'By default, go to the latest product dashboard to see the current product overview';
$lang->my->productLinkList['product-browse']    = 'By default, go to the list of requirements for the most recent product and see the requirements under the current product';

global $config;
$lang->my->projectLinkList = array();
//$lang->my->projectLinkList['program-home']    = 'The default access to the project home page, you can understand the overall project status of the company';
$lang->my->projectLinkList['program-prjbrowse'] = 'By default, you go to the project list, where you can view all the projects';
if($config->systemMode == 'new') $lang->my->projectLinkList['program-index']     = 'By default, go to the most recent project dashboard to see the current project overview';
$lang->my->projectLinkList['project-task']      = 'By default, you go to the task list for the most recent project iteration to see the task information for the current iteration';
