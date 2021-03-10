<?php
$lang->my->common = 'Dashboard';

/* Method List。*/
$lang->my->index           = 'Home';
$lang->my->todo            = 'Meine ToDos';
$lang->my->calendar        = 'Kalender';
$lang->my->work            = 'Work';
$lang->my->contribute      = 'Contribute';
$lang->my->task            = 'Aufgaben';
$lang->my->bug             = 'Bugs';
$lang->my->testTask        = 'Builds';
$lang->my->testCase        = 'Meine Fälle';
$lang->my->story           = 'Meine Storys';
$lang->my->createProgram   = 'Create Program';
$lang->my->project         = "My Projects";
$lang->my->execution       = "My {$lang->executionCommon}s";
$lang->my->issue           = 'My Issues';
$lang->my->risk            = 'My Risks';
$lang->my->profile         = 'Meine Profil';
$lang->my->dynamic         = 'Meine Verlauf';
$lang->my->team            = 'My Team';
$lang->my->editProfile     = 'Profil bearbeiten';
$lang->my->changePassword  = 'Passwort ändern';
$lang->my->preference      = 'Preference';
$lang->my->unbind          = 'Unbind Ranger';
$lang->my->manageContacts  = 'Kontakt pflegen';
$lang->my->deleteContacts  = 'Kontakt löschen';
$lang->my->shareContacts   = 'Liste teilen';
$lang->my->limited         = 'Eingeschränkte Möglichkeiten (Bearbeiten nur eigener Inhalte möglich)';
$lang->my->storyConcept    = 'Story Concept';
$lang->my->score           = 'Meine Wertung';
$lang->my->scoreRule       = 'Wertungs regeln';
$lang->my->noTodo          = 'Keine toDos. ';
$lang->my->noData          = 'No %s yet. ';
$lang->my->storyChanged    = "Story Changed";
$lang->my->hours           = 'Stunde/Tag';
$lang->my->uploadAvatar    = 'Upload Avatar';
$lang->my->requirement     = "My {$lang->URCommon}";
$lang->my->testtask        = 'My Test Task';
$lang->my->testcase        = 'My Case';

$lang->my->myExecutions = 'My Stage/Sprint/Iteration';
$lang->my->name         = 'Name';
$lang->my->code         = 'Code';
$lang->my->projects     = 'Project';
$lang->my->executions   = "{$lang->executionCommon}";

$lang->my->executionMenu = new stdclass();
$lang->my->executionMenu->undone = 'Undone';
$lang->my->executionMenu->done   = 'Done';

$lang->my->taskMenu = new stdclass();
$lang->my->taskMenu->assignedToMe = 'Mir zuweisen';
$lang->my->taskMenu->openedByMe   = 'Von mir angelegt';
$lang->my->taskMenu->finishedByMe = 'Von mir abgeschlossen';
$lang->my->taskMenu->closedByMe   = 'Von mir geschlossen';
$lang->my->taskMenu->canceledByMe = 'Von mir abgebrochen';

$lang->my->storyMenu = new stdclass();
$lang->my->storyMenu->assignedToMe = 'Mir zugewiesen';
$lang->my->storyMenu->openedByMe   = 'Von mir erstellt';
$lang->my->storyMenu->reviewedByMe = 'Von mir überprüft';
$lang->my->storyMenu->closedByMe   = 'Von mir geschlossen';

$lang->my->projectMenu = new stdclass();
$lang->my->projectMenu->doing      = 'Doing';
$lang->my->projectMenu->wait       = 'Waiting';
$lang->my->projectMenu->suspended  = 'Suspended';
$lang->my->projectMenu->closed     = 'Closed';
$lang->my->projectMenu->openedbyme = 'CreatedByMe';

$lang->my->form = new stdclass();
$lang->my->form->lblBasic   = 'Basis Info';
$lang->my->form->lblContact = 'Kontakt Info';
$lang->my->form->lblAccount = 'Konto Info';

$lang->my->programLink = 'Program Default Page';
$lang->my->productLink = 'Product Default Page';
$lang->my->projectLink = 'Project Default Page';

$lang->my->programLinkList = array();
//$lang->my->programLinkList['program-home']  = 'The default access to the program home page, you can understand the company’s overall strategic planning status';
$lang->my->programLinkList['program-browse']  = 'By default, you go to the program list, where you can view all of the programs';
//$lang->my->programLinkList['program-index'] = 'By default, you go to the most recent program dashboard to see the current program overview';
$lang->my->programLinkList['program-project'] = 'By default, you go to the list of items in the most recent program, and you can view all items under the current program';

$lang->my->productLinkList = array();
$lang->my->productLinkList['product-index']     = 'The default access to the product home page, you can understand the company’s overall product status';
$lang->my->productLinkList['product-all']       = 'By default, you go to the product list, where you can view all the products';
$lang->my->productLinkList['product-dashboard'] = 'By default, go to the latest product dashboard to see the current product overview';
$lang->my->productLinkList['product-browse']    = 'By default, go to the list of requirements for the most recent product and see the requirements under the current product';

global $config;
$lang->my->projectLinkList = array();
//$lang->my->projectLinkList['project-home'] = 'The default access to the project home page, you can understand the overall project status of the company';
$lang->my->projectLinkList['project-browse']    = 'By default, you go to the project list, where you can view all the projects';
$lang->my->projectLinkList['project-execution'] = 'Go to Project-Exection by default. You can check all information in Execution';
if($config->systemMode == 'new') $lang->my->projectLinkList['project-index'] = 'By default, go to the most recent project dashboard to see the current project overview';
