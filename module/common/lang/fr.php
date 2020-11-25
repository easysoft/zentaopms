<?php
/**
 * The common simplified chinese file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: en.php 5116 2013-07-12 06:37:48Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.pm
 */
$lang->arrow     = '&nbsp;<i class="icon-angle-right"></i>&nbsp;';
$lang->colon     = '-';
$lang->comma     = ',';
$lang->dot       = '.';
$lang->at        = ' à ';
$lang->downArrow = '↓';
$lang->null      = 'Null';
$lang->ellipsis  = '…';
$lang->percent   = '%';
$lang->dash      = '-';

$lang->zentaoPMS      = 'ZenTao';
$lang->logoImg        = 'zt-logo-en.png';
$lang->welcome        = "%s ALM";
$lang->logout         = 'Déconnexion';
$lang->login          = 'Connexion';
$lang->help           = 'Aide';
$lang->aboutZenTao    = 'A Propos';
$lang->profile        = 'Profil';
$lang->changePassword = 'Mot de Passe';
$lang->runInfo        = "<div class='row'><div class='u-1 a-center' id='debugbar'>Time %s MS, Memory %s KB, Query %s.  </div></div>";
$lang->agreement      = "J'ai lu et j'accepte les termes et conditions de la <a href='http://zpl.pub/page/zplv12.html' target='_blank'> Z PUBLIC LICENSE 1.2 </a>. <span class='text-danger'>Sans autorisation, je ne dois pas supprimer, masquer ou couvrir les logos / liens de ZenTao.</span>";
$lang->designedByAIUX = "<a href='https://api.zentao.pm/goto.php?item=aiux' class='link-aiux' target='_blank'>Designed by <strong>AIUX</strong></a>";

$lang->reset        = 'Réinitialiser';
$lang->cancel       = 'Annuler';
$lang->refresh      = 'Rafraichir';
$lang->edit         = 'Editer';
$lang->delete       = 'Supprimer';
$lang->close        = 'Fermer';
$lang->unlink       = 'Dissocier';
$lang->import       = 'Importer';
$lang->export       = 'Exporter';
$lang->setFileName  = 'Nom du Fichier';
$lang->submitting   = 'Enregistrement...';
$lang->save         = 'Sauvegarde';
$lang->saveSuccess  = 'Sauvegardé';
$lang->confirm      = 'Confirmer';
$lang->preview      = 'Consulter';
$lang->goback       = 'Retour';
$lang->goPC         = 'PC';
$lang->more         = 'Plus';
$lang->day          = 'Jour';
$lang->customConfig = 'Personnalisation';
$lang->public       = 'Public';
$lang->trunk        = 'Tronc';
$lang->sort         = 'Ordre';
$lang->required     = 'Obligatoire';
$lang->noData       = 'No data.';
$lang->fullscreen   = 'Plein Ecran';
$lang->retrack      = 'Réduire';
$lang->recent       = 'Récent';
$lang->whitelist    = 'Liste blanche';

$lang->actions         = 'Action';
$lang->restore         = 'Réinitialiser';
$lang->comment         = 'Note';
$lang->history         = 'Historique';
$lang->attatch         = 'Fichiers';
$lang->reverse         = 'Inverser';
$lang->switchDisplay   = 'Basculer';
$lang->expand          = 'Déplier';
$lang->collapse        = 'Replier';
$lang->saveSuccess     = 'Sauvé';
$lang->fail            = 'Echec';
$lang->addFiles        = 'Fichiers ajoutés ';
$lang->files           = 'Fichiers ';
$lang->pasteText       = 'Collage Multi-lignes';
$lang->uploadImages    = 'Upload Multi-images';
$lang->timeout         = 'Timeout. Vérifier votre connexion réseau, ou réessayez !';
$lang->repairTable     = 'La Base de données est peut-être endommagée. Exécutez phpmyadmin ou myisamchk pour corriger.';
$lang->duplicate       = '%s a le même titre que le fichier existant.';
$lang->ipLimited       = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head><body>Sorry, your current IP is restricted. PLease contact your Administer to grant your permissions.</body></html>";
$lang->unfold          = '+';
$lang->fold            = '-';
$lang->homepage        = 'Accueil';
$lang->noviceTutorial  = 'Tutoriel ZenTao';
$lang->changeLog       = 'Change Log';
$lang->manual          = 'Manuel Utilisateur';
$lang->customMenu      = 'Personnalisation Menu';
$lang->customField     = 'Personnalisation Champ';
$lang->lineNumber      = 'Ligne No.';
$lang->tutorialConfirm = 'Vous utilisez le didacticiel ZenTao. Voulez-vous quitter maintenant ？';

$lang->preShortcutKey  = '[Shortcut:←]';
$lang->nextShortcutKey = '[Shortcut:→]';
$lang->backShortcutKey = '[Shortcut:Alt+↑]';

$lang->select        = 'Sélectionner';
$lang->selectAll     = 'Tout sélectionner';
$lang->selectReverse = "Sélectionner l'inverse";
$lang->loading       = 'Chargement...';
$lang->notFound      = 'Non trouvé !';
$lang->notPage       = 'Désolé, la fonctionnalité que vous souhaitez utiliser est encore en développement !';
$lang->showAll       = '[[Voir Tout]]';
$lang->selectedItems = '<strong>{0}</strong> items sélectionnés';

$lang->future       = 'En Attente';
$lang->year         = 'Année';
$lang->workingHour  = 'Heures';

$lang->idAB         = 'ID';
$lang->priAB        = 'P';
$lang->statusAB     = 'Statut';
$lang->openedByAB   = 'Créé par';
$lang->assignedToAB = 'Affecté à';
$lang->typeAB       = 'Type';

$lang->common = new stdclass();
$lang->common->common = 'Module Commun';

global $config;
if($config->URAndSR)
{
    $URCommon = zget($lang, 'URCommon', "UR");
    $SRCommon = zget($lang, 'SRCommon', "SR");
}

/* Main menu. */
$lang->mainNav = new stdclass();
$lang->mainNav->my      = '<i class="icon icon-menu-my"></i> My|my|index|';
$lang->mainNav->program = '<i class="icon icon-folder-open-o"></i> Program|program|pgmbrowse|';
$lang->mainNav->product = '<i class="icon icon-menu-project"></i> Product|product|index|';
$lang->mainNav->project = '<i class="icon icon-file"></i> Project|program|prjbrowse|';
$lang->mainNav->system  = '<i class="icon icon-menu-users"></i> System|custom|estimate|';
$lang->mainNav->admin   = '<i class="icon icon-menu-backend"></i> Admin|admin|index|';

$lang->reporting = new stdclass();
$lang->dividerMenu = ',admin,';

/* Program set menu. */
$lang->program = new stdclass();
$lang->program->menu = new stdclass();
$lang->program->menu->index   = 'Home|program|pgmindex|';
$lang->program->menu->browse  = array('link' => 'Program|program|pgmbrowse|', 'alias' => 'pgmcreate,pgmedit,pgmgroup,pgmmanagepriv,pgmmanageview,pgmmanagemembers');

$lang->program->viewMenu = new stdclass();
$lang->program->viewMenu->view        = array('link' => 'View|program|pgmview|program=%s');
$lang->program->viewMenu->product     = array('link' => 'Product|program|pgmproduct|program=%s', 'alias' => 'view');
$lang->program->viewMenu->project     = array('link' => "Project|program|pgmproject|program=%s");
$lang->program->viewMenu->personnel   = array('link' => "Member|personnel|accessible|program=%s");
$lang->program->viewMenu->stakeholder = array('link' => "Stakeholder|program|pgmstakeholder|program=%s", 'alias' => 'createstakeholder');

$lang->personnel = new stdClass();
$lang->personnel->menu = new stdClass();
$lang->personnel->menu->accessible = array('link' => "Accessible|personnel|accessible|program=%s");
$lang->personnel->menu->whitelist  = array('link' => "Whitelist|personnel|whitelist|program=%s", 'alias' => 'addwhitelist');
$lang->personnel->menu->putinto    = array('link' => "Investment|personnel|putinto|program=%s");

/* Scrum menu. */
$lang->product = new stdclass();
$lang->product->menu = new stdclass();
$lang->product->menu->home = 'Home|product|index|';
$lang->product->menu->list = array('link' => $lang->productCommon . '|product|all|', 'alias' => 'create,batchedit');

$lang->product->viewMenu = new stdclass();
$lang->product->viewMenu->requirement = array('link' => "Requirement|product|browse|productID=%s&branch=&browseType=unclosed&param=0&storyType=requirement", 'alias' => 'batchedit', 'subModule' => 'story');
$lang->product->viewMenu->story       = array('link' => "Story|product|browse|productID=%s", 'alias' => 'batchedit', 'subModule' => 'story');
$lang->product->viewMenu->plan        = array('link' => "Plan|productplan|browse|productID=%s", 'subModule' => 'productplan');
$lang->product->viewMenu->release     = array('link' => "Release|release|browse|productID=%s",     'subModule' => 'release');
$lang->product->viewMenu->roadmap     = 'Roadmap|product|roadmap|productID=%s';
$lang->product->viewMenu->branch      = '@branch@|branch|manage|productID=%s';
$lang->product->viewMenu->module      = 'Module|tree|browse|productID=%s&view=story';
$lang->product->viewMenu->view        = array('link' => 'Overview|product|view|productID=%s', 'alias' => 'edit');
$lang->product->viewMenu->whitelist   = array('link' => 'Whitelist|product|whitelist|productID=%s', 'alias' => 'addwhitelist');

$lang->release     = new stdclass();
$lang->branch      = new stdclass();
$lang->productplan = new stdclass();

$lang->release->menu     = $lang->product->viewMenu;
$lang->branch->menu      = $lang->product->menu;
$lang->productplan->menu = $lang->product->menu;

/* System menu. */
$lang->system = new stdclass();
$lang->system->menu = new stdclass();
$lang->system->menu->estimate = array('link' => 'Estimate|custom|estimate|');
$lang->system->menu->stage    = array('link' => 'Stage|stage|browse|', 'subModule' => 'stage');
$lang->system->menu->subject  = array('link' => 'Subject|subject|browse|');
$lang->system->menu->holiday  = array('link' => 'Holiday|holiday|browse|');
$lang->system->menu->custom   = array('link' => 'Custom|custom|configurewaterfall|');
$lang->system->dividerMenu = ',auditcl,subject,';

if(isset($_COOKIE['systemModel']) and $_COOKIE['systemModel'] == 'scrum')
{
    $lang->system->menu = new stdclass();
    $lang->system->menu->subject = array('link' => 'Subject|subject|browse|');
    $lang->system->menu->holiday = array('link' => 'Holiday|holiday|browse|');
    $lang->system->menu->custom  = array('link' => 'Custom|custom|configurescrum|');

    $lang->mainNav->system = '<i class="icon icon-menu-users"></i> System|subject|browse|';
    unset($lang->system->dividerMenu);
}

$lang->stage = new stdclass();
$lang->stage->menu = new stdclass();
$lang->stage->menu->browse  = array('link' => 'Stage List|stage|browse|', 'alias' => 'create,edit,batchcreate');
$lang->stage->menu->settype = 'Stage Type|stage|settype|';

$lang->measurement = new stdclass();
$lang->measurement->menu = new stdclass();

/* Object list in search form. */
$lang->searchObjects['bug']         = 'Bug';
$lang->searchObjects['story']       = 'Story';
$lang->searchObjects['task']        = 'Tâche';
$lang->searchObjects['testcase']    = 'CasTest';
$lang->searchObjects['project']     = $lang->projectCommon;
$lang->searchObjects['product']     = $lang->productCommon;
$lang->searchObjects['user']        = 'Utilisateur';
$lang->searchObjects['build']       = 'Build';
$lang->searchObjects['release']     = 'Release';
$lang->searchObjects['productplan'] = $lang->productCommon . 'Plan';
$lang->searchObjects['testtask']    = 'Recette';
$lang->searchObjects['doc']         = 'Document';
$lang->searchObjects['caselib']     = 'Case Library';
$lang->searchObjects['testreport']  = 'CR de Test';
$lang->searchObjects['program']     = 'Program';
$lang->searchTips                   = 'ID (ctrl+g)';

/* Code formats for import. */
$lang->importEncodeList['gbk']   = 'GBK';
$lang->importEncodeList['big5']  = 'BIG5';
$lang->importEncodeList['utf-8'] = 'UTF-8';

/* File type list for export. */
$lang->exportFileTypeList['csv']  = 'csv';
$lang->exportFileTypeList['xml']  = 'xml';
$lang->exportFileTypeList['html'] = 'html';

$lang->exportTypeList['all']      = 'Toutes les Données';
$lang->exportTypeList['selected'] = 'Données sélectionnées';

/* Language. */
$lang->lang = 'Langue';

/* Theme style. */
$lang->theme                = 'Theme';
$lang->themes['default']    = 'Default';
$lang->themes['green']      = 'Green';
$lang->themes['red']        = 'Red';
$lang->themes['purple']     = 'Purple';
$lang->themes['pink']       = 'Pink';
$lang->themes['blackberry'] = 'Blackberry';
$lang->themes['classic']    = 'Classic';

/* Index menu settings. */
$lang->index = new stdclass();
$lang->index->menu = new stdclass();

$lang->index->menu->product = "{$lang->productCommon}|product|browse";
$lang->index->menu->project = "{$lang->projectCommon}|project|browse";

/* my dashboard menu settings. */
$lang->my = new stdclass();
$lang->my->menu = new stdclass();

$lang->my->menu->index       = 'Accueil|my|index';
$lang->my->menu->calendar    = 'Todo|my|todo|';
$lang->my->menu->myProject   = array('link' => 'Project|my|project|');
$lang->my->menu->task        = array('link' => 'Tâche|my|task|', 'subModule' => 'task');
$lang->my->menu->bug         = array('link' => 'Bug|my|bug|',   'subModule' => 'bug');
$lang->my->menu->testtask    = array('link' => 'Recette|my|testtask|', 'subModule' => 'testcase,testtask', 'alias' => 'testcase');
$lang->my->menu->story       = array('link' => 'Story|my|story|',   'subModule' => 'story');
$lang->my->menu->myExecution = "Execution|my|execution|";
$lang->my->menu->dynamic     = 'Historique|my|dynamic|';

if($config->URAndSR)
{
    $lang->my->menu->requirement = array('link' => "{$URCommon}|my|requirement|", 'subModule' => 'story');
    $lang->my->menu->story       = array('link' => "{$SRCommon}|my|story|", 'subModule' => 'story');
}

$lang->my->dividerMenu = ',program,requirement,dynamic,';

$lang->todo       = new stdclass();
$lang->todo->menu = $lang->my->menu;

/* Product menu settings. */
$lang->scrumproduct = new stdclass();
$lang->scrumproduct->menu = new stdclass();

$lang->scrumproduct->menu->story    = array('link' => 'Story|product|browse|productID=%s', 'alias' => 'batchedit', 'subModule' => 'story');
$lang->scrumproduct->menu->plan     = array('link' => 'Plan|productplan|browse|productID=%s', 'subModule' => 'productplan');
$lang->scrumproduct->menu->release  = array('link' => 'Release|release|browse|productID=%s',     'subModule' => 'release');
$lang->scrumproduct->menu->roadmap  = 'Roadmap|product|roadmap|productID=%s';
$lang->scrumproduct->menu->project  = "{$lang->projectCommon}|product|project|status=all&productID=%s";
$lang->scrumproduct->menu->dynamic  = 'Dynamics|product|dynamic|productID=%s';
$lang->scrumproduct->menu->doc      = array('link' => 'Doc|doc|objectLibs|type=product&objectID=%s&from=product', 'subModule' => 'doc');
$lang->scrumproduct->menu->branch   = '@branch@|branch|manage|productID=%s';
$lang->scrumproduct->menu->module   = 'Module|tree|browse|productID=%s&view=story';
$lang->scrumproduct->menu->view     = array('link' => 'Overview|product|view|productID=%s', 'alias' => 'edit');

if($config->URAndSR)
{
    $lang->product->menu->requirement = array('link' => "{$URCommon}|product|browse|productID=%s&branch=&browseType=unclosed&param=0&storyType=requirement", 'alias' => 'batchedit', 'subModule' => 'story');
    $lang->product->menu->story       = array('link' => "{$SRCommon}|product|browse|productID=%s", 'alias' => 'batchedit', 'subModule' => 'story');
}

$lang->product->dividerMenu = ',project,doc,';

$lang->story = new stdclass();

$lang->story->menu = $lang->product->menu;

/* Project menu settings. */
$lang->project = new stdclass();
$lang->project->menu = new stdclass();

$lang->project->menu->task      = array('link' => 'Tâche|project|task|projectID=%s', 'subModule' => 'task,tree', 'alias' => 'importtask,importbug');
$lang->project->menu->kanban    = array('link' => 'Kanban|project|kanban|projectID=%s');
$lang->project->menu->burn      = array('link' => 'Atterrissage|project|burn|projectID=%s');
$lang->project->menu->list      = array('link' => 'Plus|project|grouptask|projectID=%s', 'alias' => 'grouptask,tree', 'class' => 'dropdown dropdown-hover');
$lang->project->menu->story     = array('link' => 'Story|project|story|projectID=%s', 'subModule' => 'story', 'alias' => 'linkstory,storykanban');
$lang->project->menu->qa        = array('link' => 'Test|project|bug|projectID=%s', 'subModule' => 'bug,build,testtask', 'alias' => 'build,testtask', 'class' => 'dropdown dropdown-hover');
$lang->project->menu->doc       = array('link' => 'Doc|doc|objectLibs|type=project&objectID=%s&from=project', 'subModule' => 'doc');
$lang->project->menu->action    = array('link' => 'Historique|project|dynamic|projectID=%s', 'subModule' => 'dynamic', 'class' => 'dropdown dropdown-hover');
$lang->project->menu->product   = $lang->productCommon . '|project|manageproducts|projectID=%s';
$lang->project->menu->team      = array('link' => 'Equipe|project|team|projectID=%s', 'alias' => 'managemembers');
$lang->project->menu->view      = array('link' => "Vue d'ensemble|project|view|projectID=%s", 'alias' => 'edit,start,suspend,putoff,close');
$lang->project->menu->whitelist = array('link' => 'Whitelist|project|whitelist|projectID=%s', 'alias' => 'addwhitelist', 'subModule' => 'personnel');

$lang->project->subMenu = new stdclass();
$lang->project->subMenu->list = new stdclass();
$lang->project->subMenu->list->groupTask = 'Vision Groupée|project|groupTask|projectID=%s';
$lang->project->subMenu->list->tree      = 'Arborescence|project|tree|projectID=%s';

$lang->project->subMenu->qa = new stdclass();
$lang->project->subMenu->qa->bug      = 'Bug|project|bug|projectID=%s';
$lang->project->subMenu->qa->build    = array('link' => 'Build|project|build|projectID=%s', 'subModule' => 'build');
$lang->project->subMenu->qa->testtask = array('link' => 'Recette|project|testtask|projectID=%s', 'subModule' => 'testreport,testtask');

$lang->project->dividerMenu = ',story,team,product,';

$lang->task  = new stdclass();
$lang->build = new stdclass();
$lang->task->menu  = $lang->project->menu;
$lang->build->menu = $lang->project->menu;

/* QA menu settings. */
$lang->qa = new stdclass();
$lang->qa->menu = new stdclass();

$lang->qa->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,resolve,close,activate,report,batchedit,batchactivate,confirmbug,assignto');
$lang->qa->menu->testcase  = array('link' => 'CasTest|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase,importfromlib');
$lang->qa->menu->testtask  = array('link' => 'Recette|testtask|browse|productID=%s', 'alias' => 'view,create,edit,linkcase,cases,start,close,batchrun,groupcase,report');
$lang->qa->menu->testsuite = array('link' => 'Cahier Recette|testsuite|browse|productID=%s', 'alias' => 'view,create,edit,linkcase');
$lang->qa->menu->report    = array('link' => 'Rapport|testreport|browse|productID=%s', 'alias' => 'view,create,edit');
$lang->qa->menu->caselib   = array('link' => 'Library Recette|caselib|browse', 'alias' => 'create,createcase,view,edit,batchcreatecase,showimport');

$lang->qa->subMenu = new stdclass();
$lang->qa->subMenu->testcase = new stdclass();
$lang->qa->subMenu->testcase->feature = array('link' => 'Functional Test|testcase|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase,importfromlib', 'subModule' => 'tree,story');
$lang->qa->subMenu->testcase->unit    = array('link' => 'Unit Test|testtask|browseUnits|productID=%s');

$lang->bug = new stdclass();
$lang->bug->menu = new stdclass();
$lang->bug->subMenu = $lang->qa->subMenu;

$lang->bug->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,resolve,close,activate,report,batchedit,batchactivate,confirmbug,assignto', 'subModule' => 'tree');
$lang->bug->menu->testcase  = array('link' => 'CasTest|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->bug->menu->testtask  = array('link' => 'Recette|testtask|browse|productID=%s');
$lang->bug->menu->testsuite = array('link' => 'Cahier Recette|testsuite|browse|productID=%s');
$lang->bug->menu->report    = array('link' => 'Rapport|testreport|browse|productID=%s');
$lang->bug->menu->caselib   = array('link' => 'Library Recette|caselib|browse');

$lang->testcase = new stdclass();
$lang->testcase->menu = new stdclass();
$lang->testcase->subMenu = $lang->qa->subMenu;
$lang->testcase->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testcase->menu->testcase  = array('link' => 'CasTest|testcase|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase,importfromlib', 'subModule' => 'tree', 'class' => 'dropdown dropdown-hover');
$lang->testcase->menu->testtask  = array('link' => 'Recette|testtask|browse|productID=%s');
$lang->testcase->menu->testsuite = array('link' => 'Cahier Recette|testsuite|browse|productID=%s');
$lang->testcase->menu->report    = array('link' => 'Rapport|testreport|browse|productID=%s');
$lang->testcase->menu->caselib   = array('link' => 'Library Recette|caselib|browse');

$lang->testtask = new stdclass();
$lang->testtask->menu = new stdclass();
$lang->testtask->subMenu = $lang->qa->subMenu;
$lang->testtask->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testtask->menu->testcase  = array('link' => 'CasTest|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->testtask->menu->testtask  = array('link' => 'Recette|testtask|browse|productID=%s', 'alias' => 'view,create,edit,linkcase,cases,start,close,batchrun,groupcase,report');
$lang->testtask->menu->testsuite = array('link' => 'Cahier Recette|testsuite|browse|productID=%s');
$lang->testtask->menu->report    = array('link' => 'Rapport|testreport|browse|productID=%s');
$lang->testtask->menu->caselib   = array('link' => 'Library Recette|caselib|browse');

$lang->testsuite = new stdclass();
$lang->testsuite->menu = new stdclass();
$lang->testsuite->subMenu = $lang->qa->subMenu;
$lang->testsuite->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testsuite->menu->testcase  = array('link' => 'CasTest|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->testsuite->menu->testtask  = array('link' => 'Recette|testtask|browse|productID=%s');
$lang->testsuite->menu->testsuite = array('link' => 'Cahier Recette|testsuite|browse|productID=%s', 'alias' => 'view,create,edit,linkcase');
$lang->testsuite->menu->report    = array('link' => 'Rapport|testreport|browse|productID=%s');
$lang->testsuite->menu->caselib   = array('link' => 'Library Recette|caselib|browse');

$lang->testreport = new stdclass();
$lang->testreport->menu = new stdclass();
$lang->testreport->subMenu = $lang->qa->subMenu;
$lang->testreport->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testreport->menu->testcase  = array('link' => 'CasTest|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->testreport->menu->testtask  = array('link' => 'Recette|testtask|browse|productID=%s');
$lang->testreport->menu->testsuite = array('link' => 'Cahier Recette|testsuite|browse|productID=%s');
$lang->testreport->menu->report    = array('link' => 'Rapport|testreport|browse|productID=%s', 'alias' => 'view,create,edit');
$lang->testreport->menu->caselib   = array('link' => 'Library Recette|caselib|browse');

$lang->caselib = new stdclass();
$lang->caselib->menu = new stdclass();
$lang->caselib->menu->bug       = array('link' => 'Bug|bug|browse|');
$lang->caselib->menu->testcase  = array('link' => 'CasTest|testcase|browse|', 'class' => 'dropdown dropdown-hover');
$lang->caselib->menu->testtask  = array('link' => 'Recette|testtask|browse|');
$lang->caselib->menu->testsuite = array('link' => 'Cahier Recette|testsuite|browse|');
$lang->caselib->menu->report    = array('link' => 'Rapport|testreport|browse|');
$lang->caselib->menu->caselib   = array('link' => 'Library Recette|caselib|browse|libID=%s', 'alias' => 'create,createcase,view,edit,batchcreatecase,showimport', 'subModule' => 'tree,testcase');

$lang->caselib->subMenu = new stdclass();
$lang->caselib->subMenu->testcase = new stdclass();
$lang->caselib->subMenu->testcase->feature = array('link' => 'Functional Test|testcase|browse|', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase,importfromlib', 'subModule' => 'tree,story');
$lang->caselib->subMenu->testcase->unit    = array('link' => 'Unit Test|testtask|browseUnits|');

$lang->ci = new stdclass();
$lang->ci->menu = new stdclass();
$lang->ci->menu->code     = array('link' => 'Code|repo|browse|repoID=%s', 'alias' => 'diff,view,revision,log,blame,showsynccomment');
$lang->ci->menu->build    = array('link' => 'Build|job|browse', 'subModule' => 'compile,job');
$lang->ci->menu->jenkins  = array('link' => 'Jenkins|jenkins|browse', 'alias' => 'create,edit');
$lang->ci->menu->maintain = array('link' => 'Repo|repo|maintain', 'alias' => 'create,edit');
$lang->ci->menu->rules    = array('link' => 'Rule|repo|setrules');

$lang->repo          = new stdclass();
$lang->jenkins       = new stdclass();
$lang->compile       = new stdclass();
$lang->job           = new stdclass();
$lang->repo->menu    = $lang->ci->menu;
$lang->jenkins->menu = $lang->ci->menu;
$lang->compile->menu = $lang->ci->menu;
$lang->job->menu     = $lang->ci->menu;

/* Doc menu settings. */
$lang->doc = new stdclass();
$lang->doc->menu = new stdclass();

$lang->svn = new stdclass();
$lang->git = new stdclass();

/* Project release menu settings. */
$lang->projectrelease = new stdclass();
$lang->projectrelease->menu = new stdclass();

/* Report menu settings. */
$lang->report = new stdclass();
$lang->report->menu = new stdclass();

$lang->report->menu->annual  = array('link' => 'Annual Summary|report|annualData', 'target' => '_blank');
$lang->report->menu->product = array('link' => $lang->productCommon . '|report|productsummary');
$lang->report->menu->prj     = array('link' => $lang->projectCommon . '|report|projectdeviation');
$lang->report->menu->test    = array('link' => 'Recette|report|bugcreate', 'alias' => 'bugassign');
$lang->report->menu->staff   = array('link' => 'Entreprise|report|workload');

$lang->report->notice = new stdclass();
$lang->report->notice->help = 'Note : Le rapport est généré à partir des résultats de la liste consultée. Par exemple, cliquez sur AssignedToMe, puis Générer Rapport pour obtenir un rapport basé sur la liste de ce qui vous est assigné.';

/* Company menu settings. */
$lang->company = new stdclass();
$lang->dept    = new stdclass();
$lang->group   = new stdclass();
$lang->user    = new stdclass();
$lang->company->menu = new stdclass();
$lang->dept->menu    = new stdclass();
$lang->group->menu   = new stdclass();
$lang->user->menu    = new stdclass();

$lang->company = new stdclass();
$lang->company->menu = new stdclass();
$lang->company->menu->browseUser  = array('link' => 'Utilisateurs|company|browse', 'subModule' => 'user');
$lang->company->menu->dept        = array('link' => 'Compartiments|dept|browse', 'subModule' => 'dept');
$lang->company->menu->browseGroup = array('link' => 'Privilèges|group|browse', 'subModule' => 'group');
$lang->company->menu->dynamic     = 'Historique|company|dynamic|';
$lang->company->menu->view        = array('link' => 'Entreprise|company|view');

/* Admin menu settings. */
$lang->admin = new stdclass();
$lang->admin->menu = new stdclass();
$lang->admin->menu->index   = array('link' => 'Accueil|admin|index', 'alias' => 'register,certifytemail,certifyztmobile,ztcompany');
$lang->admin->menu->company = array('link' => 'Personnel|company|browse|', 'subModule' => ',user,dept,group,', 'alias' => ',dynamic,view,');
$lang->admin->menu->message = array('link' => 'Notification|message|index', 'subModule' => 'message,mail,webhook');
$lang->admin->menu->data    = array('link' => 'Données|backup|index', 'subModule' => 'backup,action');
$lang->admin->menu->safe    = array('link' => 'Sécurité|admin|safe', 'alias' => 'checkweak');
$lang->admin->menu->system  = array('link' => 'Système|cron|index', 'subModule' => 'cron');

$lang->company->menu = $lang->company->menu;
$lang->dept->menu    = $lang->company->menu;
$lang->group->menu   = $lang->company->menu;
$lang->user->menu    = $lang->company->menu;

$lang->admin->subMenu = new stdclass();
$lang->admin->subMenu->message = new stdclass();
$lang->admin->subMenu->message->mail    = array('link' => 'Mail|mail|index', 'subModule' => 'mail');
$lang->admin->subMenu->message->webhook = array('link' => 'Webhook|webhook|browse', 'subModule' => 'webhook');
$lang->admin->subMenu->message->browser = array('link' => 'Browser|message|browser');
$lang->admin->subMenu->message->setting = array('link' => 'Paramètrage|message|setting', 'subModule' => 'message');

$lang->admin->subMenu->sso = new stdclass();
$lang->admin->subMenu->sso->ranzhi = 'Zdoo|admin|sso';

$lang->admin->subMenu->dev = new stdclass();
$lang->admin->subMenu->dev->api    = array('link' => 'API|dev|api');
$lang->admin->subMenu->dev->db     = array('link' => 'Database|dev|db');
$lang->admin->subMenu->dev->editor = array('link' => 'Editor|dev|editor');
$lang->admin->subMenu->dev->entry  = array('link' => 'Application|entry|browse', 'subModule' => 'entry');

$lang->admin->subMenu->data = new stdclass();
$lang->admin->subMenu->data->backup = array('link' => 'Backup|backup|index', 'subModule' => 'backup');
$lang->admin->subMenu->data->trash  = 'Corbeille|action|trash';

$lang->admin->subMenu->system = new stdclass();
$lang->admin->subMenu->system->cron     = array('link' => 'Cron|cron|index', 'subModule' => 'cron');
$lang->admin->subMenu->system->timezone = array('link' => 'Timezone|custom|timezone', 'subModule' => 'custom');

$lang->convert   = new stdclass();
$lang->upgrade   = new stdclass();
$lang->action    = new stdclass();
$lang->backup    = new stdclass();
$lang->extension = new stdclass();
$lang->custom    = new stdclass();
$lang->mail      = new stdclass();
$lang->cron      = new stdclass();
$lang->dev       = new stdclass();
$lang->entry     = new stdclass();
$lang->webhook   = new stdclass();
$lang->message   = new stdclass();
$lang->search    = new stdclass();

/* Menu group. */
$lang->menugroup = new stdclass();
$lang->menugroup->release     = 'product';
$lang->menugroup->story       = 'product';
$lang->menugroup->branch      = 'product';
$lang->menugroup->productplan = 'product';
$lang->menugroup->task        = 'project';
$lang->menugroup->build       = 'project';
$lang->menugroup->convert     = 'admin';
$lang->menugroup->upgrade     = 'admin';
$lang->menugroup->user        = 'company';
$lang->menugroup->group       = 'company';
$lang->menugroup->bug         = 'qa';
$lang->menugroup->testcase    = 'qa';
$lang->menugroup->case        = 'qa';
$lang->menugroup->testtask    = 'qa';
$lang->menugroup->testsuite   = 'qa';
$lang->menugroup->caselib     = 'qa';
$lang->menugroup->testreport  = 'qa';
$lang->menugroup->report      = 'reporting';
$lang->menugroup->people      = 'company';
$lang->menugroup->dept        = 'company';
$lang->menugroup->todo        = 'my';
$lang->menugroup->score       = 'my';
$lang->menugroup->action      = 'admin';
$lang->menugroup->backup      = 'admin';
$lang->menugroup->cron        = 'admin';
$lang->menugroup->extension   = 'admin';
$lang->menugroup->custom      = 'admin';
$lang->menugroup->mail        = 'admin';
$lang->menugroup->dev         = 'admin';
$lang->menugroup->entry       = 'admin';
$lang->menugroup->webhook     = 'admin';
$lang->menugroup->message     = 'admin';

$lang->menugroup->repo    = 'ci';
$lang->menugroup->jenkins = 'ci';
$lang->menugroup->compile = 'ci';
$lang->menugroup->job     = 'ci';

/* Nav group.*/
$lang->navGroup = new stdclass();
$lang->navGroup->my     = 'my';
$lang->navGroup->todo   = 'my';
$lang->navGroup->effort = 'my';

$lang->navGroup->personnel = 'program';

$lang->navGroup->productplan = 'product';
$lang->navGroup->release     = 'product';
$lang->navGroup->branch      = 'product';
$lang->navGroup->story       = 'product';

$lang->navGroup->project     = 'project';
$lang->navGroup->tree        = 'project';
$lang->navGroup->task        = 'project';
$lang->navGroup->qa          = 'project';
$lang->navGroup->bug         = 'project';
$lang->navGroup->doc         = 'project';
$lang->navGroup->testcase    = 'project';
$lang->navGroup->testtask    = 'project';
$lang->navGroup->testreport  = 'project';
$lang->navGroup->testsuite   = 'project';
$lang->navGroup->caselib     = 'project';
$lang->navGroup->feedback    = 'project';
$lang->navGroup->deploy      = 'project';
$lang->navGroup->stakeholder = 'project';

$lang->navGroup->programplan    = 'project';
$lang->navGroup->workestimation = 'project';
$lang->navGroup->budget         = 'project';
$lang->navGroup->review         = 'project';
$lang->navGroup->reviewissue    = 'project';
$lang->navGroup->weekly         = 'project';
$lang->navGroup->milestone      = 'project';
$lang->navGroup->pssp           = 'project';
$lang->navGroup->design         = 'project';
$lang->navGroup->repo           = 'project';
$lang->navGroup->issue          = 'project';
$lang->navGroup->risk           = 'project';
$lang->navGroup->auditplan      = 'project';
$lang->navGroup->cm             = 'project';
$lang->navGroup->nc             = 'project';
$lang->navGroup->job            = 'project';
$lang->navGroup->jenkins        = 'project';
$lang->navGroup->compile        = 'project';
$lang->navGroup->build          = 'project';
$lang->navGroup->projectrelease = 'project';

$lang->navGroup->durationestimation = 'project';

$lang->navGroup->stage         = 'system';
$lang->navGroup->measurement   = 'system';
$lang->navGroup->report        = 'system';
$lang->navGroup->sqlbuilder    = 'system';
$lang->navGroup->auditcl       = 'system';
$lang->navGroup->cmcl          = 'system';
$lang->navGroup->process       = 'system';
$lang->navGroup->activity      = 'system';
$lang->navGroup->zoutput       = 'system';
$lang->navGroup->classify      = 'system';
$lang->navGroup->subject       = 'system';
$lang->navGroup->baseline      = 'system';
$lang->navGroup->reviewcl      = 'system';
$lang->navGroup->reviewsetting = 'system';
$lang->navGroup->holiday       = 'system';

$lang->navGroup->attend   = 'attend';
$lang->navGroup->leave    = 'attend';
$lang->navGroup->makeup   = 'attend';
$lang->navGroup->overtime = 'attend';
$lang->navGroup->lieu     = 'attend';

$lang->navGroup->admin     = 'admin';
$lang->navGroup->company   = 'admin';
$lang->navGroup->dept      = 'admin';
$lang->navGroup->ldap      = 'admin';
$lang->navGroup->group     = 'admin';
$lang->navGroup->webhook   = 'admin';
$lang->navGroup->sms       = 'admin';
$lang->navGroup->message   = 'admin';
$lang->navGroup->user      = 'admin';
$lang->navGroup->custom    = 'admin';
$lang->navGroup->cron      = 'admin';
$lang->navGroup->backup    = 'admin';
$lang->navGroup->mail      = 'admin';
$lang->navGroup->dev       = 'admin';
$lang->navGroup->extension = 'admin';
$lang->navGroup->action    = 'admin';
$lang->navGroup->search    = 'admin';

/* Error info. */
$lang->error = new stdclass();
$lang->error->companyNotFound = "Le domaine %s ne peut être trouvé !";
$lang->error->length          = array("『 %s 』erreur de longueur. Il devrait être『%s』", "La longueur de 『%s』devrait être <=『%s』et >『%s』.");
$lang->error->reg             = "『 %s 』erreur de format. Il devrait être『%s』.";
$lang->error->unique          = "『 %s 』『 %s 』existes. Allez à Admin->Recycle Bin pour le restaurer, si vous êtes sûr qu'il est supprimé.";
$lang->error->gt              = "『 %s 』devrait être > 『 %s 』.";
$lang->error->ge              = "『 %s 』devrait être >= 『 %s 』.";
$lang->error->notempty        = "『 %s 』ne devrait pas être à blanc.";
$lang->error->empty           = "『 %s 』devrait être nul.";
$lang->error->equal           = "『 %s 』doit être 『 %s 』.";
$lang->error->int             = array("『 %s 』devrait être des nombres", "『 %s 』devrait être 『 %s-%s 』.");
$lang->error->float           = "『 %s 』devrait avoir des nombres ou des décimales.";
$lang->error->email           = "『 %s 』doit être une adresse mail valide.";
$lang->error->URL             = "『 %s 』doit être une url.";
$lang->error->date            = "『%s』doit être une date valide.";
$lang->error->datetime        = "『 %s 』doit être une date valide.";
$lang->error->code            = "『 %s 』doit être des lettres ou des chiffres.";
$lang->error->account         = "『 %s 』doit être >= 3 lettres ou chiffres.";
$lang->error->passwordsame    = "Les mots de passe doivent être cohérents.";
$lang->error->passwordrule    = "Le mot de passe doit être conforme aux règles. Il devrait être >= 6 caractères.";
$lang->error->accessDenied    = 'Accès refusé.';
$lang->error->pasteImg        = 'Les images ne peuvent pas être collées dans votre navigateur !';
$lang->error->noData          = 'No data.';
$lang->error->editedByOther   = 'Cet enregistrement a peut-être été modifié. Veuillez actualiser et réessayer !';
$lang->error->tutorialData    = "Aucune donnée ne peut être importée en mode tutoriel. Veuillez d'abord quitter le didacticiel !";
$lang->error->noCurlExt       = 'Aucun module Curl installé';

/* Page info. */
$lang->pager = new stdclass();
$lang->pager->noRecord     = "Pas d'enregistrement.";
$lang->pager->digest       = "Total: <strong>%s</strong>. %s <strong>%s/%s</strong> &nbsp; ";
$lang->pager->recPerPage   = " <strong>%s</strong> par page";
$lang->pager->first        = "<i class='icon-step-backward' title='Première Page'></i>";
$lang->pager->pre          = "<i class='icon-play icon-flip-horizontal' title='Page Précédente'></i>";
$lang->pager->next         = "<i class='icon-play' title='Page Suivante'></i>";
$lang->pager->last         = "<i class='icon-step-forward' title='Dernière Page'></i>";
$lang->pager->locate       = "Go!";
$lang->pager->previousPage = "Préc";
$lang->pager->nextPage     = "Suiv";
$lang->pager->summery      = "<strong>%s-%s</strong> sur <strong>%s</strong>.";
$lang->pager->pageOfText   = "Page {0}";
$lang->pager->firstPage    = "Première";
$lang->pager->lastPage     = "Dernière";
$lang->pager->goto         = "Aller à";
$lang->pager->pageOf       = "Page <strong>{page}</strong>";
$lang->pager->totalPage    = "<strong>{totalPage}</strong> pages";
$lang->pager->totalCount   = "Total: <strong>{recTotal}</strong> lignes";
$lang->pager->pageSize     = "<strong>{recPerPage}</strong> par page";
$lang->pager->itemsRange   = "De <strong>{start}</strong> à <strong>{end}</strong>";
$lang->pager->pageOfTotal  = "Page <strong>{page}</strong> sur <strong>{totalPage}</strong>";

$lang->colorPicker = new stdclass();
$lang->colorPicker->errorTip = "Ce n'est pas une valeur de couleur valide";

$lang->proVersion     = "<a href='https://www.zentao.pm/book/zentaopromanual/free-open-source-project-management-software-zentaopro-127.html' target='_blank' id='proLink' class='text-important'>ZenTao Pro <i class='text-danger icon-pro-version'></i></a> &nbsp; ";
$lang->downNotify     = "Télécharger la notification sur le bureau";
$lang->downloadClient = "Télécharger ZenTao Desktop";
$lang->clientHelp     = "Aide Client";
$lang->clientHelpLink = "https://www.zentao.pm/book/zentaomanual/scrum-tool-im-integration-206.html";
$lang->website        = "https://www.zentao.pm";

$lang->suhosinInfo     = "Avertissement ! Les données atteignent la limite. Veuillez changer <font color=red>sohusin.post.max_vars</font> et <font color=red>sohusin.request.max_vars</font> (set larger %s value) dans php.ini, puis relancez Apache ou php-fpm, ou des données ne seront pas sauvegardées.";
$lang->maxVarsInfo     = "Avertissement ! Les données atteignent la limite. Veuillez changer <font color=red>max_input_vars</font> (set larger %s value) dans php.ini, puis relancez Apache ou php-fpm, ou des données ne seront pas sauvegardées.";
$lang->pasteTextInfo   = "Collez le texte ici. Chaque ligne sera un titre. ";
$lang->noticeImport    = "Les données importées contiennent des données qui existent déjà dans le système. Confirmez s'il vous plait cette action.";
$lang->importConfirm   = "Importer";
$lang->importAndCover  = "Ecraser";
$lang->importAndInsert = "Inserer";

$lang->noResultsMatch     = "Aucun résultat trouvé !";
$lang->searchMore         = "Plus de résultats ：";
$lang->chooseUsersToMail  = "Choisissez les utilisateurs à avertir.";
$lang->noticePasteImg     = "Vous pouvez déposer des images dans l'éditeur.";
$lang->pasteImgFail       = "Echec lors de la dépose des images. Essayez plus tard.";
$lang->pasteImgUploading  = "Chargement...";

/* Time formats settings. */
if(!defined('DT_DATETIME1')) define('DT_DATETIME1',  'Y-m-d H:i:s');
if(!defined('DT_DATETIME2')) define('DT_DATETIME2',  'y-m-d H:i');
if(!defined('DT_MONTHTIME1'))define('DT_MONTHTIME1', 'n/d H:i');
if(!defined('DT_MONTHTIME2'))define('DT_MONTHTIME2', 'n/d H:i');
if(!defined('DT_DATE1'))     define('DT_DATE1',     'Y-m-d');
if(!defined('DT_DATE2'))     define('DT_DATE2',     'Ymd');
if(!defined('DT_DATE3'))     define('DT_DATE3',     'Y/m/d');
if(!defined('DT_DATE4'))     define('DT_DATE4',     'n/j');
if(!defined('DT_DATE5'))     define('DT_DATE5',     'j/n');
if(!defined('DT_TIME1'))     define('DT_TIME1',     'H:i:s');
if(!defined('DT_TIME2'))     define('DT_TIME2',     'H:i');

/* Datepicker. */
$lang->datepicker = new stdclass();

$lang->datepicker->dpText = new stdclass();
$lang->datepicker->dpText->TEXT_OR          = 'ou ';
$lang->datepicker->dpText->TEXT_PREV_YEAR   = 'Année Dernière';
$lang->datepicker->dpText->TEXT_PREV_MONTH  = 'Mois Dernier';
$lang->datepicker->dpText->TEXT_PREV_WEEK   = 'Semaine Dernière';
$lang->datepicker->dpText->TEXT_YESTERDAY   = 'Hier';
$lang->datepicker->dpText->TEXT_THIS_MONTH  = 'Ce Mois';
$lang->datepicker->dpText->TEXT_THIS_WEEK   = 'Cette Semaine';
$lang->datepicker->dpText->TEXT_TODAY       = "Aujourd'hui";
$lang->datepicker->dpText->TEXT_NEXT_YEAR   = 'Année Prochaine';
$lang->datepicker->dpText->TEXT_NEXT_MONTH  = 'Mois Prochain';
$lang->datepicker->dpText->TEXT_CLOSE       = 'Fermer';
$lang->datepicker->dpText->TEXT_DATE        = '';
$lang->datepicker->dpText->TEXT_CHOOSE_DATE = 'Choisir une Date';

$lang->datepicker->dayNames     = array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
$lang->datepicker->abbrDayNames = array('Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam');
$lang->datepicker->monthNames   = array('Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc');

/* Common action icons. */
$lang->icons['todo']      = 'check';
$lang->icons['product']   = 'cube';
$lang->icons['bug']       = 'bug';
$lang->icons['task']      = 'check-sign';
$lang->icons['tasks']     = 'tasks';
$lang->icons['project']   = 'stack';
$lang->icons['doc']       = 'file-text';
$lang->icons['doclib']    = 'folder-close';
$lang->icons['story']     = 'lightbulb';
$lang->icons['release']   = 'tags';
$lang->icons['roadmap']   = 'code-fork';
$lang->icons['plan']      = 'flag';
$lang->icons['dynamic']   = 'volume-up';
$lang->icons['build']     = 'tag';
$lang->icons['test']      = 'check';
$lang->icons['testtask']  = 'check';
$lang->icons['group']     = 'group';
$lang->icons['team']      = 'group';
$lang->icons['company']   = 'sitemap';
$lang->icons['user']      = 'user';
$lang->icons['dept']      = 'sitemap';
$lang->icons['tree']      = 'sitemap';
$lang->icons['usecase']   = 'sitemap';
$lang->icons['testcase']  = 'sitemap';
$lang->icons['result']    = 'list-alt';
$lang->icons['mail']      = 'envelope';
$lang->icons['trash']     = 'trash';
$lang->icons['extension'] = 'th-large';
$lang->icons['app']       = 'th-large';

$lang->icons['results']            = 'list-alt';
$lang->icons['create']             = 'plus';
$lang->icons['post']               = 'edit';
$lang->icons['batchCreate']        = 'plus-sign';
$lang->icons['batchEdit']          = 'edit-sign';
$lang->icons['batchClose']         = 'off';
$lang->icons['edit']               = 'edit';
$lang->icons['delete']             = 'close';
$lang->icons['copy']               = 'copy';
$lang->icons['report']             = 'bar-chart';
$lang->icons['export']             = 'export';
$lang->icons['report-file']        = 'file-powerpoint';
$lang->icons['import']             = 'import';
$lang->icons['finish']             = 'checked';
$lang->icons['resolve']            = 'check';
$lang->icons['start']              = 'play';
$lang->icons['restart']            = 'play';
$lang->icons['run']                = 'play';
$lang->icons['runCase']            = 'play';
$lang->icons['batchRun']           = 'play-sign';
$lang->icons['assign']             = 'hand-right';
$lang->icons['assignTo']           = 'hand-right';
$lang->icons['change']             = 'fork';
$lang->icons['link']               = 'link';
$lang->icons['close']              = 'off';
$lang->icons['activate']           = 'magic';
$lang->icons['review']             = 'glasses';
$lang->icons['confirm']            = 'search';
$lang->icons['confirmBug']         = 'search';
$lang->icons['putoff']             = 'calendar';
$lang->icons['suspend']            = 'pause';
$lang->icons['pause']              = 'pause';
$lang->icons['cancel']             = 'ban-circle';
$lang->icons['recordEstimate']     = 'time';
$lang->icons['customFields']       = 'cogs';
$lang->icons['manage']             = 'cog';
$lang->icons['unlock']             = 'unlock-alt';
$lang->icons['confirmStoryChange'] = 'search';
$lang->icons['score']              = 'tint';

/* Scrum menu. */
$lang->menu = new stdclass();
$lang->menu->scrum = new stdclass();
$lang->menu->scrum->program     = 'Index|program|index|';
$lang->menu->scrum->product     = $lang->productCommon . '|product|index|locate=no';
$lang->menu->scrum->project     = "$lang->projectCommon|project|index|locate=no";
$lang->menu->scrum->doc         = 'Doc|doc|index|';
$lang->menu->scrum->qa          = 'QA|qa|index';
$lang->menu->scrum->stakeholder = 'Stakeholder|stakeholder|browse';

/* Waterfall menu. */
$lang->menu->waterfall = new stdclass();
$lang->menu->waterfall->programindex   = array('link' => 'Index|program|index|program={PROGRAM}');
$lang->menu->waterfall->programplan    = array('link' => 'Plan|programplan|browse|program={PROGRAM}', 'subModule' => 'programplan');
$lang->menu->waterfall->project        = array('link' => $lang->projectCommon . '|project|task|projectID={PROJECT}', 'subModule' => ',project,task,');
$lang->menu->waterfall->weekly         = array('link' => 'Weekly|weekly|index|program={PROGRAM}', 'subModule' => ',milestone,');
$lang->menu->waterfall->doc            = array('link' => 'Doc|doc|index|program={PROGRAM}');
$lang->menu->waterfall->product        = array('link' => 'Story|product|browse|product={PRODUCT}', 'subModule' => ',story,');
$lang->menu->waterfall->design         = 'Design|design|browse|product={PRODUCT}';
$lang->menu->waterfall->ci             = 'Code|repo|browse|';
$lang->menu->waterfall->qa             = array('link' => 'QA|bug|browse|product={PRODUCT}', 'subModule' => ',testcase,testtask,testsuite,testreport,caselib,');
$lang->menu->waterfall->projectrelease = array('link' => '发布|projectrelease|browse|product={PRODUCT}');
$lang->menu->waterfall->issue          = 'Issue|issue|browse|';
$lang->menu->waterfall->risk           = 'Risk|risk|browse|';
$lang->menu->waterfall->list           = array('link' => 'More|workestimation|index|program={PROGRAM}', 'class' => 'dropdown dropdown-hover waterfall-list', 'subModule' => 'stakeholder,workestimation,durationestimation,budget,pssp,stakeholder');

$lang->waterfall = new stdclass();
$lang->waterfall->subMenu = new stdclass();
$lang->waterfall->subMenu->list = new stdclass();
$lang->waterfall->subMenu->list->workestimation = array('link' => 'Workestimation|workestimation|index|program=%s', 'subModule' => 'durationestimation,budget');
$lang->waterfall->subMenu->list->stakeholder    = array('link' => 'Stakeholder|stakeholder|browse|', 'subModule' => 'stakeholder');
$lang->waterfall->subMenu->list->program        = 'Project|program|PRJEdit|';

$lang->waterfallproduct   = new stdclass();
$lang->workestimation     = new stdclass();
$lang->budget             = new stdclass();
$lang->programplan        = new stdclass();
$lang->review             = new stdclass();
$lang->weekly             = new stdclass();
$lang->milestone          = new stdclass();
$lang->design             = new stdclass();
$lang->auditplan          = new stdclass();
$lang->cm                 = new stdclass();
$lang->nc                 = new stdclass();
$lang->pssp               = new stdclass();
$lang->issue              = new stdclass();
$lang->risk               = new stdclass();
$lang->stakeholder        = new stdclass();
$lang->durationestimation = new stdclass();

$lang->workestimation->menu     = new stdclass();
$lang->budget->menu             = new stdclass();
$lang->programplan->menu        = new stdclass();
$lang->review->menu             = new stdclass();
$lang->weekly->menu             = new stdclass();
$lang->milestone->menu          = new stdclass();
$lang->design->menu             = new stdclass();
$lang->auditplan->menu          = new stdclass();
$lang->cm->menu                 = new stdclass();
$lang->pssp->menu               = new stdclass();
$lang->issue->menu              = new stdclass();
$lang->risk->menu               = new stdclass();
$lang->stakeholder->menu        = new stdclass();
$lang->waterfallproduct->menu   = new stdclass();
$lang->durationestimation->menu = new stdclass();

$lang->stakeholder->menu->list  = array('link' => 'Stakeholder List|stakeholder|browse|', 'alias' => 'create,edit,view,batchcreate');
$lang->stakeholder->menu->issue = array('link' => 'Issue|stakeholder|issue|');

$lang->workestimation->menu->index    = 'Workload|workestimation|index|program={PROGRAM}';
$lang->workestimation->menu->duration = array('link' => 'Duration|durationestimation|index|program={PROGRAM}', 'subModule' => 'durationestimation');
$lang->workestimation->menu->budget   = array('link' => 'Budget|budget|summary|', 'subModule' => 'budget');

$lang->durationestimation->menu = $lang->workestimation->menu;
$lang->budget->menu = $lang->workestimation->menu;

$lang->programplan->menu->gantt = array('link' => 'Gantt|programplan|browse|programID={PROGRAM}&productID={PRODUCT}&type=gantt');
$lang->programplan->menu->lists = array('link' => 'Stage|programplan|browse|programID={PROGRAM}&productID={PRODUCT}&type=lists', 'alias' => 'create');

$lang->waterfallproduct->menu->plan  = array('link' => "{$lang->planCommon}|productplan|browse|productID={PRODUCT}", 'subModule' => 'productplan');
$lang->waterfallproduct->menu->story = 'Story|product|browse|product={PRODUCT}';
$lang->waterfallproduct->menu->track = 'Track|story|track|product={PRODUCT}';

if($config->URAndSR)
{
    $lang->waterfallproduct->menu->requirement = array('link' => "{$URCommon}|product|browse|productID={PRODUCT}&branch=&browseType=unclosed&param=0&storyType=requirement");
    $lang->waterfallproduct->menu->story       = array('link' => "{$SRCommon}|product|browse|productID={PRODUCT}");
}

$lang->nc->menu = $lang->auditplan->menu;
$lang->noMenuModule = array('my', 'todo', 'effort', 'program', 'product', 'productplan', 'story', 'branch', 'release', 'attend', 'leave', 'makeup', 'overtime', 'lieu', 'holiday', 'custom', 'auditcl', 'subject', 'admin', 'mail', 'extension', 'dev', 'backup', 'action', 'cron', 'issue', 'risk', 'pssp', 'sms', 'message', 'webhook', 'search');

include (dirname(__FILE__) . '/menuOrder.php');
