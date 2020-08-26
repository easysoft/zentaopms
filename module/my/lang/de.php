<?php
$lang->my->common = 'Dashboard';

/* Method List。*/
$lang->my->index          = 'Home';
$lang->my->todo           = 'Meine ToDos';
$lang->my->calendar       = 'Kalender';
$lang->my->task           = 'Aufgaben';
$lang->my->bug            = 'Bugs';
$lang->my->testTask       = 'Builds';
$lang->my->testCase       = 'Meine Fälle';
$lang->my->story          = 'Meine Storys';
$lang->my->myProject      = "Meine {$lang->projectCommon}";
$lang->my->profile        = 'Meine Profil';
$lang->my->dynamic        = 'Meine Verlauf';
$lang->my->editProfile    = 'Profil bearbeiten';
$lang->my->changePassword = 'Passwort ändern';
$lang->my->unbind         = 'Unbind Ranger';
$lang->my->manageContacts = 'Kontakt pflegen';
$lang->my->deleteContacts = 'Kontakt löschen';
$lang->my->shareContacts  = 'Liste teilen';
$lang->my->limited        = 'Eingeschränkte Möglichkeiten (Bearbeiten nur eigener Inhalte möglich)';
$lang->my->score          = 'Meine Wertung';
$lang->my->scoreRule      = 'Wertungs regeln';
$lang->my->noTodo         = 'Keine toDos. ';

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

$lang->my->home = new stdclass();
$lang->my->home->latest        = 'Verlauf';
$lang->my->home->action        = "%s, %s <em>%s</em> %s <a href='%s'>%s</a>.";
$lang->my->home->projects      = $lang->projectCommon;
$lang->my->home->products      = $lang->productCommon;
$lang->my->home->createProject = "Erstelle {$lang->projectCommon}";
$lang->my->home->createProduct = "Erstelle {$lang->productCommon}";
$lang->my->home->help          = "<a href='http://www.zentao.net/help-read-79236.html' target='_blank'>Hilfe</a>";
$lang->my->home->noProductsTip = "Kein {$lang->productCommon} gefunden.";

$lang->my->form = new stdclass();
$lang->my->form->lblBasic   = 'Basis Info';
$lang->my->form->lblContact = 'Kontakt Info';
$lang->my->form->lblAccount = 'Konto Info';
