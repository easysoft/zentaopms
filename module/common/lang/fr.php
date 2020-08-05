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

/* Main menu. */
$lang->menu = new stdclass();
$lang->menu->my      = '<span>Dashboard</span>|my|index';
$lang->menu->product = $lang->productCommon . '|product|index|locate=no';
$lang->menu->project = $lang->projectCommon . '|project|index|locate=no';
$lang->menu->qa      = 'Test|qa|index';
$lang->menu->ci      = 'CI|repo|browse';
$lang->menu->doc     = 'Doc|doc|index';
$lang->menu->report  = 'Rapports|report|index';
$lang->menu->company = 'Entreprise|company|index';
$lang->menu->admin   = 'Admin|admin|index';

$lang->dividerMenu = ',qa,report,';

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

$lang->my->menu->index          = 'Accueil|my|index';
$lang->my->menu->calendar       = array('link' => 'Agenda|my|calendar|', 'subModule' => 'todo', 'alias' => 'todo');
$lang->my->menu->task           = array('link' => 'Tâche|my|task|', 'subModule' => 'task');
$lang->my->menu->bug            = array('link' => 'Bug|my|bug|',   'subModule' => 'bug');
$lang->my->menu->testtask       = array('link' => 'Recette|my|testtask|', 'subModule' => 'testcase,testtask', 'alias' => 'testcase');
$lang->my->menu->story          = array('link' => 'Story|my|story|',   'subModule' => 'story');
$lang->my->menu->myProject      = "{$lang->projectCommon}|my|project|";
$lang->my->menu->dynamic        = 'Historique|my|dynamic|';
$lang->my->menu->profile        = array('link' => 'Profil|my|profile', 'alias' => 'editprofile');
$lang->my->menu->changePassword = 'Password|my|changepassword';
$lang->my->menu->manageContacts = 'Contact|my|managecontacts';
$lang->my->menu->score          = array('link' => 'Points|my|score', 'subModule' => 'score');

$lang->my->dividerMenu = ',task,myProject,profile,';

$lang->todo       = new stdclass();
$lang->todo->menu = $lang->my->menu;

$lang->score       = new stdclass();
$lang->score->menu = $lang->my->menu;

/* Product menu settings. */
$lang->product = new stdclass();
$lang->product->menu = new stdclass();

$lang->product->menu->story    = array('link' => 'Story|product|browse|productID=%s', 'alias' => 'batchedit', 'subModule' => 'story');
$lang->product->menu->plan     = array('link' => 'Plan|productplan|browse|productID=%s', 'subModule' => 'productplan');
$lang->product->menu->release  = array('link' => 'Release|release|browse|productID=%s',     'subModule' => 'release');
$lang->product->menu->roadmap  = 'Roadmap|product|roadmap|productID=%s';
$lang->product->menu->project  = "{$lang->projectCommon}|product|project|status=all&productID=%s";
$lang->product->menu->dynamic  = 'Historique|product|dynamic|productID=%s';
$lang->product->menu->doc      = array('link' => 'Doc|doc|objectLibs|type=product&objectID=%s&from=product', 'subModule' => 'doc');
$lang->product->menu->branch   = '@branch@|branch|manage|productID=%s';
$lang->product->menu->module   = 'Module|tree|browse|productID=%s&view=story';
$lang->product->menu->view     = array('link' => "Vue d'ensemble|product|view|productID=%s", 'alias' => 'edit');

$lang->product->dividerMenu = ',plan,project,doc,';

$lang->story       = new stdclass();
$lang->productplan = new stdclass();
$lang->release     = new stdclass();
$lang->branch      = new stdclass();

$lang->branch->menu      = $lang->product->menu;
$lang->story->menu       = $lang->product->menu;
$lang->productplan->menu = $lang->product->menu;
$lang->release->menu     = $lang->product->menu;

/* Project menu settings. */
$lang->project = new stdclass();
$lang->project->menu = new stdclass();

$lang->project->menu->task     = array('link' => 'Tâche|project|task|projectID=%s', 'subModule' => 'task,tree', 'alias' => 'importtask,importbug');
$lang->project->menu->kanban   = array('link' => 'Kanban|project|kanban|projectID=%s');
$lang->project->menu->burn     = array('link' => 'Atterrissage|project|burn|projectID=%s');
$lang->project->menu->list     = array('link' => 'Plus|project|grouptask|projectID=%s', 'alias' => 'grouptask,tree', 'class' => 'dropdown dropdown-hover');
$lang->project->menu->story    = array('link' => 'Story|project|story|projectID=%s', 'subModule' => 'story', 'alias' => 'linkstory,storykanban');
$lang->project->menu->qa       = array('link' => 'Test|project|bug|projectID=%s', 'subModule' => 'bug,build,testtask', 'alias' => 'build,testtask', 'class' => 'dropdown dropdown-hover');
$lang->project->menu->doc      = array('link' => 'Doc|doc|objectLibs|type=project&objectID=%s&from=project', 'subModule' => 'doc');
$lang->project->menu->action   = array('link' => 'Historique|project|dynamic|projectID=%s', 'subModule' => 'dynamic', 'class' => 'dropdown dropdown-hover');
$lang->project->menu->product  = $lang->productCommon . '|project|manageproducts|projectID=%s';
$lang->project->menu->team     = array('link' => 'Equipe|project|team|projectID=%s', 'alias' => 'managemembers');
$lang->project->menu->view     = array('link' => "Vue d'ensemble|project|view|projectID=%s", 'alias' => 'edit,start,suspend,putoff,close');

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

$lang->qa->menu->bug       = array('link' => 'Bug|bug|browse|productID=%s');
$lang->qa->menu->testcase  = array('link' => 'CasTest|testcase|browse|productID=%s', 'class' => 'dropdown dropdown-hover');
$lang->qa->menu->testtask  = array('link' => 'Recette|testtask|browse|productID=%s');
$lang->qa->menu->testsuite = array('link' => 'Cahier Recette|testsuite|browse|productID=%s');
$lang->qa->menu->report    = array('link' => 'Rapport|testreport|browse|productID=%s');
$lang->qa->menu->caselib   = array('link' => 'Library Recette|caselib|browse');

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
//$lang->doc->menu->createLib = array('link' => '<i class="icon icon-folder-plus"></i>&nbsp;Add Library|doc|createLib', 'float' => 'right');

$lang->svn = new stdclass();
$lang->git = new stdclass();

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
$lang->company->menu = new stdclass();
$lang->company->menu->browseUser  = array('link' => 'Utilisateurs|company|browse', 'subModule' => 'user');
$lang->company->menu->dept        = array('link' => 'Compartiments|dept|browse', 'subModule' => 'dept');
$lang->company->menu->browseGroup = array('link' => 'Privilèges|group|browse', 'subModule' => 'group');
$lang->company->menu->dynamic     = 'Historique|company|dynamic|';
$lang->company->menu->view        = array('link' => 'Entreprise|company|view');

$lang->dept  = new stdclass();
$lang->group = new stdclass();
$lang->user  = new stdclass();

$lang->dept->menu  = $lang->company->menu;
$lang->group->menu = $lang->company->menu;
$lang->user->menu  = $lang->company->menu;

/* Admin menu settings. */
$lang->admin = new stdclass();
$lang->admin->menu = new stdclass();
$lang->admin->menu->index     = array('link' => 'Accueil|admin|index', 'alias' => 'register,certifytemail,certifyztmobile,ztcompany');
$lang->admin->menu->message   = array('link' => 'Notification|message|index', 'subModule' => 'message,mail,webhook');
$lang->admin->menu->custom    = array('link' => 'Personnalisation|custom|set', 'subModule' => 'custom');
$lang->admin->menu->sso       = array('link' => 'Intégration|admin|sso');
$lang->admin->menu->extension = array('link' => 'Extension|extension|browse', 'subModule' => 'extension');
$lang->admin->menu->dev       = array('link' => 'Dévelop.|dev|api', 'alias' => 'db', 'subModule' => 'dev,entry');
$lang->admin->menu->translate = array('link' => 'Traduire|dev|translate');
$lang->admin->menu->data      = array('link' => 'Données|backup|index', 'subModule' => 'backup,action');
$lang->admin->menu->safe      = array('link' => 'Sécurité|admin|safe', 'alias' => 'checkweak');
$lang->admin->menu->system    = array('link' => 'Système|cron|index', 'subModule' => 'cron');

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

$lang->convert->menu   = $lang->admin->menu;
$lang->upgrade->menu   = $lang->admin->menu;
$lang->action->menu    = $lang->admin->menu;
$lang->backup->menu    = $lang->admin->menu;
$lang->cron->menu      = $lang->admin->menu;
$lang->extension->menu = $lang->admin->menu;
$lang->custom->menu    = $lang->admin->menu;
$lang->mail->menu      = $lang->admin->menu;
$lang->dev->menu       = $lang->admin->menu;
$lang->entry->menu     = $lang->admin->menu;
$lang->webhook->menu   = $lang->admin->menu;
$lang->message->menu   = $lang->admin->menu;

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
$lang->menugroup->doclib      = 'doc';
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

include (dirname(__FILE__) . '/menuOrder.php');

global $config;
if(isset($config->global->flow) and $config->global->flow == 'onlyStory')
{
    /* Remove project, report and qa module. */
    unset($lang->menu->project);
    unset($lang->menu->report);
    unset($lang->menu->qa);

    unset($lang->menuOrder[15]);
    unset($lang->menuOrder[20]);
    unset($lang->menuOrder[35]);

    /* Adjust sub menu of my dashboard. */
    unset($lang->my->menu->bug);
    unset($lang->my->menu->testtask);
    unset($lang->my->menu->task);
    unset($lang->my->menu->myProject);

    /* Adjust sub menu of product module. */
    unset($lang->product->menu->project);
    unset($lang->product->menu->doc);

    /* Rename product module. */
    $lang->menu->product = "{$lang->productCommon}|product|index";

    /* Adjust search items. */
    unset($lang->searchObjects['bug']);
    unset($lang->searchObjects['task']);
    unset($lang->searchObjects['testcase']);
    unset($lang->searchObjects['project']);
    unset($lang->searchObjects['build']);
    unset($lang->searchObjects['testtask']);
    unset($lang->searchObjects['testsuite']);
    unset($lang->searchObjects['caselib']);
    unset($lang->searchObjects['testreport']);
}

if(isset($config->global->flow) and $config->global->flow == 'onlyTask')
{
    /* Remove product, report and qa module. */
    unset($lang->menu->product);
    unset($lang->menu->report);
    unset($lang->menu->qa);

    unset($lang->menuOrder[10]);
    unset($lang->menuOrder[20]);
    unset($lang->menuOrder[35]);

    /* Adjust sub menu of my dashboard. */
    unset($lang->my->menu->bug);
    unset($lang->my->menu->testtask);
    unset($lang->my->menu->story);

    /* Adjust sub menu of project  module. */
    unset($lang->project->menu->story);
    unset($lang->project->menu->build);
    unset($lang->project->menu->qa);
    unset($lang->project->menu->product);
    unset($lang->project->menu->doc);

    /* Remove sub menu of product module. */
    unset($lang->product->menu);
    unset($lang->product->menuOrder);

    unset($lang->searchObjects['story']);
    unset($lang->searchObjects['product']);
    unset($lang->searchObjects['testcase']);
    unset($lang->searchObjects['release']);
    unset($lang->searchObjects['productplan']);
    unset($lang->searchObjects['testsuite']);
    unset($lang->searchObjects['caselib']);
    unset($lang->searchObjects['testreport']);
}

if(isset($config->global->flow) and $config->global->flow == 'onlyTest')
{
    /* Remove project and test module. */
    unset($lang->menu->project);
    unset($lang->menu->qa);
    unset($lang->menu->report);

    unset($lang->menuOrder[15]);
    unset($lang->menuOrder[20]);
    unset($lang->menuOrder[35]);

    /* Rename product module. */
    $lang->menu->product = "{$lang->productCommon}|product|index";

    /* Adjust sub menu of my dashboard. */
    unset($lang->my->menu->task);
    unset($lang->my->menu->myProject);
    unset($lang->my->menu->story);

    /* Remove sub menu of project module. */
    unset($lang->project->menu);
    unset($lang->project->menuOrder);
    $lang->project->menu = new stdclass();
    $lang->project->menu->list = array('alias' => '');

    /* Add bug, testcase and testtask module. */
    $lang->menu->bug       = 'Bug|bug|index';
    $lang->menu->testcase  = 'Tests Fonctionnels|testcase|index';
    $lang->menu->unit      = 'Unité de Test|testtask|browseUnits';
    $lang->menu->testsuite = 'Cahier de Recette|testsuite|index';
    $lang->menu->testtask  = 'Recette|testtask|index';
    $lang->menu->caselib   = 'Library Recette|caselib|browse';

    $lang->menuOrder[6]  = 'bug';
    $lang->menuOrder[7]  = 'testcase';
    $lang->menuOrder[8]  = 'unit';
    $lang->menuOrder[9]  = 'testsuite';
    $lang->menuOrder[10] = 'testtask';
    $lang->menuOrder[11] = 'caselib';
    $lang->menuOrder[12] = 'product';

    /* Adjust sub menu of bug module. */
    $lang->bug->menu = new stdclass();
    $lang->bug->menu->all           = 'Tous|bug|browse|productID=%s&branch=%s&browseType=all&param=%s';
    $lang->bug->menu->unclosed      = 'Non Fermés|bug|browse|productID=%s&branch=%s&browseType=unclosed&param=%s';
    $lang->bug->menu->openedbyme    = 'Détectés par Moi|bug|browse|productID=%s&branch=%s&browseType=openedbyme&param=%s';
    $lang->bug->menu->assigntome    = 'Affectés à Moi|bug|browse|productID=%s&branch=%s&browseType=assigntome&param=%s';
    $lang->bug->menu->resolvedbyme  = 'Résolus par Moi|bug|browse|productID=%s&branch=%s&browseType=resolvedbyme&param=%s';
    $lang->bug->menu->toclosed      = 'A Fermer|bug|browse|productID=%s&branch=%s&browseType=toclosed&param=%s';
    $lang->bug->menu->unresolved    = 'Actifs|bug|browse|productID=%s&branch=%s&browseType=unresolved&param=%s';
    $lang->bug->menu->more          = array('link' => 'More|bug|browse|productID=%s&branch=%s&browseType=unconfirmed&param=%s', 'class' => 'dropdown dropdown-hover');

    $lang->bug->subMenu = new stdclass();
    $lang->bug->subMenu->more = new stdclass();
    $lang->bug->subMenu->more->unconfirmed   = 'Non confirmés|bug|browse|productID=%s&branch=%s&browseType=unconfirmed&param=%s';
    $lang->bug->subMenu->more->assigntonull  = 'Non affectés|bug|browse|productID=%s&branch=%s&browseType=assigntonull&param=%s';
    $lang->bug->subMenu->more->longlifebugs  = 'Persistants|bug|browse|productID=%s&branch=%s&browseType=longlifebugs&param=%s';
    $lang->bug->subMenu->more->postponedbugs = 'Reportés|bug|browse|productID=%s&branch=%s&browseType=postponedbugs&param=%s';
    $lang->bug->subMenu->more->overduebugs   = 'Retard|bug|browse|productID=%s&branch=%s&browseType=overduebugs&param=%s';
    $lang->bug->subMenu->more->needconfirm   = 'A Confirmer|bug|browse|productID=%s&branch=%s&browseType=needconfirm&param=%s';

    $lang->bug->menuOrder[5]  = 'product';
    $lang->bug->menuOrder[10] = 'all';
    $lang->bug->menuOrder[15] = 'unclosed';
    $lang->bug->menuOrder[20] = 'openedbyme';
    $lang->bug->menuOrder[25] = 'assigntome';
    $lang->bug->menuOrder[30] = 'resolvedbyme';
    $lang->bug->menuOrder[35] = 'toclosed';
    $lang->bug->menuOrder[40] = 'unresolved';
    $lang->bug->menuOrder[45] = 'unconfirmed';
    $lang->bug->menuOrder[50] = 'assigntonull';
    $lang->bug->menuOrder[55] = 'longlifebugs';
    $lang->bug->menuOrder[60] = 'postponedbugs';
    $lang->bug->menuOrder[65] = 'overduebugs';
    $lang->bug->menuOrder[70] = 'needconfirm';

    /* Adjust sub menu of testcase. */
    $lang->testcase->menu = new stdclass();
    $lang->testcase->menu->all     = 'Tous|testcase|browse|productID=%s&branch=%s&browseType=all';
    $lang->testcase->menu->wait    = 'En Attente|testcase|browse|productID=%s&branch=%s&browseType=wait';
    $lang->testcase->menu->bysuite = array('link' => 'Cahier Recette|testsuite|create|productID=%s', 'class' => 'dropdown dropdown-hover');

    $lang->testcase->subMenu = new stdclass();
    $lang->testcase->subMenu->bysuite = new stdclass();
    $lang->testcase->subMenu->bysuite->create = 'Rédiger Cahier de Recette|testsuite|create|productID=%s';

    $lang->testcase->menuOrder[5]  = 'product';
    $lang->testcase->menuOrder[10] = 'all';
    $lang->testcase->menuOrder[15] = 'wait';
    $lang->testcase->menuOrder[20] = 'suite';

    /* Adjust sub menu of bug module. */
    $lang->testsuite->menu = new stdclass();

    $lang->testsuite->menuOrder[5]  = 'product';

    /* Adjust sub menu of testtask. */
    $lang->testtask->menu = new stdclass();
    $lang->testtask->menu->totalStatus = 'Toutes|testtask|browse|productID=%s&branch=%s&type=%s,totalStatus';
    $lang->testtask->menu->wait        = 'En Attente|testtask|browse|productID=%s&branch=%s&type=%s,wait';
    $lang->testtask->menu->doing       = 'En Déroulement|testtask|browse|productID=%s&branch=%s&type=%s,doing';
    $lang->testtask->menu->blocked     = 'Bloquées|testtask|browse|productID=%s&branch=%s&type=%s,blocked';
    $lang->testtask->menu->done        = 'Jouées|testtask|browse|productID=%s&branch=%s&type=%s,done';
    $lang->testtask->menu->report      = array('link' => 'Rapports|testreport|browse');

    $lang->testtask->menuOrder[5]  = 'product';
    $lang->testtask->menuOrder[10] = 'scope';
    $lang->testtask->menuOrder[15] = 'totalStatus';
    $lang->testtask->menuOrder[20] = 'wait';
    $lang->testtask->menuOrder[25] = 'doing';
    $lang->testtask->menuOrder[30] = 'blocked';
    $lang->testtask->menuOrder[35] = 'done';
    $lang->testtask->menuOrder[40] = 'report';

    $lang->testreport->menu      = $lang->testtask->menu;
    $lang->testreport->menuOrder = $lang->testtask->menuOrder;

    /* Adjust sub menu of caselib module. */
    $lang->caselib->menu = new stdclass();
    $lang->caselib->menu->all  = 'Toutes|caselib|browse|libID=%s&browseType=all';
    $lang->caselib->menu->wait = 'En Attente|caselib|browse|libID=%s&browseType=wait';
    $lang->caselib->menu->view = 'Vues|caselib|view|libID=%s';

    $lang->caselib->menuOrder[5]  = 'lib';
    $lang->caselib->menuOrder[10] = 'all';
    $lang->caselib->menuOrder[15] = 'wait';
    $lang->caselib->menuOrder[20] = 'view';

    /* Adjust sub menu of product module. */
    unset($lang->product->menu->story);
    unset($lang->product->menu->project);
    unset($lang->product->menu->release);
    unset($lang->product->menu->dynamic);
    unset($lang->product->menu->plan);
    unset($lang->product->menu->roadmap);
    unset($lang->product->menu->doc);
    unset($lang->product->menu->module);
    unset($lang->product->menu->index);

    $lang->product->menu->build = array('link' => 'Build|product|build', 'subModule' => 'build');

    $lang->product->menuOrder[5]  = 'build';
    $lang->product->menuOrder[10] = 'view';
    $lang->product->menuOrder[15] = 'order';

    $lang->build->menu      = $lang->product->menu;
    $lang->build->menuOrder = $lang->product->menuOrder;

    /* Adjust menu group. */
    $lang->menugroup->bug        = 'bug';
    $lang->menugroup->testcase   = 'testcase';
    $lang->menugroup->case       = 'testcase';
    $lang->menugroup->testtask   = 'testtask';
    $lang->menugroup->testsuite  = 'testsuite';
    $lang->menugroup->caselib    = 'caselib';
    $lang->menugroup->testreport = 'testtask';
    $lang->menugroup->build      = 'product';

    /* Adjust search objects. */
    unset($lang->searchObjects['story']);
    unset($lang->searchObjects['task']);
    unset($lang->searchObjects['release']);
    unset($lang->searchObjects['project']);
    unset($lang->searchObjects['productplan']);
}
