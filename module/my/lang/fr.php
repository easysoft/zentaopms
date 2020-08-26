<?php
$lang->my->common = 'Tableau de Bord';

/* Method List。*/
$lang->my->index          = 'Accueil';
$lang->my->todo           = 'Mon Agenda';
$lang->my->calendar       = 'Calendrier';
$lang->my->task           = 'Mes Tâches';
$lang->my->bug            = 'Mes Bugs';
$lang->my->testTask       = 'Mes Builds';
$lang->my->testCase       = 'Mes CasTests';
$lang->my->story          = 'Mes Stories';
$lang->my->myProject      = "Mes {$lang->projectCommon}s";
$lang->my->profile        = 'Mon Profil';
$lang->my->dynamic        = 'Mon Historique';
$lang->my->editProfile    = 'Edit Profil';
$lang->my->changePassword = 'Changer le Mot de Passe';
$lang->my->unbind         = 'Dissocier de Zdoo';
$lang->my->manageContacts = 'Gérer Contact';
$lang->my->deleteContacts = 'Supprimer Contact';
$lang->my->shareContacts  = 'Public';
$lang->my->limited        = 'Actions Limitées (Les utilisateurs peuvent seulement éditer le contenu de ce qui leur est affecté.)';
$lang->my->score          = 'Mes Points';
$lang->my->scoreRule      = 'Règles Point';
$lang->my->noTodo         = "Je n'ai rien à faire pour l'instant.";

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

$lang->my->home = new stdclass();
$lang->my->home->latest        = 'Historique';
$lang->my->home->action        = "%s, %s <em>%s</em> %s <a href='%s'>%s</a>.";
$lang->my->home->projects      = $lang->projectCommon;
$lang->my->home->products      = $lang->productCommon;
$lang->my->home->createProject = "Créer un {$lang->projectCommon}";
$lang->my->home->createProduct = "Créer un {$lang->productCommon}";
$lang->my->home->help          = "<a href='https://www.zentao.pm/book/zentaomanual/free-open-source-project-management-software-workflow-46.html' target='_blank'>Aide</a>";
$lang->my->home->noProductsTip = "Aucun {$lang->productCommon} ici.";

$lang->my->form = new stdclass();
$lang->my->form->lblBasic   = 'Infos Basiques';
$lang->my->form->lblContact = 'Infos Contact';
$lang->my->form->lblAccount = 'Infos Compte';
