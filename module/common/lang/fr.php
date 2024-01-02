<?php
/**
 * The common simplified chinese file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: en.php 5116 2013-07-12 06:37:48Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */

include (dirname(__FILE__) . '/common.php');

global $config;

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
$lang->and       = 'and';
$lang->separater = ',';

$lang->zentaoPMS      = 'ZenTao';
$lang->pmsName        = 'ALM';
$lang->proName        = 'Pro';
$lang->bizName        = 'Biz';
$lang->maxName        = 'Max';
$lang->liteName       = 'Lite';
$lang->devopsPrefix   = 'DevOps ';
$lang->logoImg        = 'zt-logo-en.png';
$lang->welcome        = "%s ALM";
$lang->logout         = 'Déconnexion';
$lang->login          = 'Connexion';
$lang->help           = 'Aide';
$lang->aboutZenTao    = 'A Propos';
$lang->ztWebsite      = 'ZenTao Address';
$lang->profile        = 'Profil';
$lang->changePassword = 'Mot de Passe';
$lang->unfoldMenu     = 'Unfold Menu';
$lang->collapseMenu   = 'Collapse Menu';
$lang->preference     = 'Preference';
$lang->tutorialAB     = 'Tutorial';
$lang->runInfo        = "<div class='row'><div class='u-1 a-center' id='debugbar'>Time %s MS, Memory %s KB, Query %s.  </div></div>";
$lang->agreement      = "J'ai lu et j'accepte les termes et conditions de la <a href='http://zpl.pub/page/zplv12.html' target='_blank'> Z PUBLIC LICENSE 1.2 </a>. <span class='text-danger'>Sans autorisation, je ne dois pas supprimer, masquer ou couvrir les logos / liens de ZenTao.</span>";
$lang->designedByAIUX = "<a href='https://api.zentao.net/goto.php?item=aiux' class='link-aiux' target='_blank'><i class='icon icon-aiux'></i> AIUX</a>";
$lang->bizVersion     = '<a href="https://www.zentao.net/page/enterprise.html" target="_blank">Essayez ZenTao Biz pour en savoir plus!</a>';
$lang->bizVersionINT  = '<a href="https://www.zentao.pm/page/vs.html" target="_blank">Essayez ZenTao Biz pour en savoir plus!</a>';

$lang->reset              = 'Réinitialiser';
$lang->cancel             = 'Annuler';
$lang->refresh            = 'Rafraichir';
$lang->refreshIcon        = "<i title='$lang->refresh' class='icon icon-refresh'></i>";
$lang->create             = 'Create';
$lang->edit               = 'Editer';
$lang->delete             = 'Supprimer';
$lang->activate           = 'Activate';
$lang->close              = 'Fermer';
$lang->unlink             = 'Dissocier';
$lang->import             = 'Importer';
$lang->export             = 'Exporter';
$lang->setFileName        = 'Nom du Fichier';
$lang->submitting         = 'Enregistrement...';
$lang->save               = 'Sauvegarde';
$lang->confirm            = 'Confirmer';
$lang->preview            = 'Consulter';
$lang->goback             = 'Retour';
$lang->goPC               = 'PC';
$lang->more               = 'Plus';
$lang->moreLink           = 'MORE';
$lang->day                = 'Jour';
$lang->customConfig       = 'Personnalisation';
$lang->public             = 'Public';
$lang->trunk              = 'Tronc';
$lang->sort               = 'Ordre';
$lang->required           = 'Obligatoire';
$lang->noData             = 'No data.';
$lang->fullscreen         = 'Plein Ecran';
$lang->retrack            = 'Réduire';
$lang->whitelist          = 'Liste blanche';
$lang->globalSetting      = 'Common';
$lang->waterfallModel     = 'Waterfall';
$lang->scrumModel         = 'Scrum';
$lang->agilePlusModel     = 'Agile Plus';
$lang->waterfallPlusModel = 'Waterfall Plus';
$lang->all                = 'All';
$lang->viewDetails        = 'View Details';

$lang->actions         = 'Action';
$lang->restore         = 'Réinitialiser';
$lang->confirmDraft    = 'Unsaved form is found. Do you want to restore it?';
$lang->resume          = 'resume';
$lang->comment         = 'Note';
$lang->history         = 'Historique';
$lang->attatch         = 'Fichiers';
$lang->reverse         = 'Inverser';
$lang->switchDisplay   = 'Basculer';
$lang->switchTo        = 'Switch To';
$lang->expand          = 'Déplier';
$lang->collapse        = 'Replier';
$lang->saveSuccess     = 'Sauvegardé';
$lang->importSuccess   = 'Sauvé';
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
$lang->levelExceeded   = 'The level has exceeded the display range. For more information, please go to the web page or view it through search.';
$lang->noticeOkFile    = 'Pour des raisons de s?curit?, votre compte administrateur doit ?tre confirm?. \n Please login ZenTao server and create %s File.\n Note:\n 1. File is blank.\n 2. If the file existed, delete it and then create a new one.';
$lang->noticeDrag      = 'Click to add or drag to upload, no more than %s';

$lang->serviceAgreement = "Service Agreement";
$lang->privacyPolicy    = "Privacy Policy";
$lang->needAgreePrivacy = "Please read the service agreement and privacy policy first";
$lang->iAgreedPrivacy   = "I have read and agree";

$lang->preShortcutKey    = '[Shortcut:←]';
$lang->nextShortcutKey   = '[Shortcut:→]';
$lang->backShortcutKey   = '[Shortcut:Alt+↑]';
$lang->shortcutOperation = 'Quick Start';

$lang->select        = 'Sélectionner';
$lang->selectAll     = 'Tout sélectionner';
$lang->selectReverse = "Sélectionner l'inverse";
$lang->loading       = 'Chargement...';
$lang->notFound      = 'Non trouvé !';
$lang->notPage       = 'Désolé, la fonctionnalité que vous souhaitez utiliser est encore en développement !';
$lang->showAll       = '[[Voir Tout]]';
$lang->selectedItems = '<strong>{0}</strong> items sélectionnés';

$lang->future      = 'En Attente';
$lang->year        = 'Année';
$lang->month       = 'Month';
$lang->hour        = 'Hour';
$lang->minute      = 'Minute';
$lang->second      = 'Second';
$lang->workingHour = 'Heures';

$lang->idAB         = 'ID';
$lang->priAB        = 'P';
$lang->statusAB     = 'Statut';
$lang->openedByAB   = 'Créé par';
$lang->assignedToAB = 'Affecté à';
$lang->typeAB       = 'Type';
$lang->nameAB       = 'Name';
$lang->code         = 'Code';

$lang->pri     = 'Priority';
$lang->delayed = 'Delayed';

$lang->common->common     = 'Module Commun';
$lang->common->story      = 'Story';
$lang->my->common         = 'My';
$lang->todo->common       = 'Agenda';
$lang->block->common      = 'Bloc';
$lang->program->common    = 'Program';
$lang->product->common    = $lang->productCommon;
$lang->project->common    = $lang->projectCommon;
$lang->execution->common  = 'Execution';
$lang->kanban->common     = 'Kanban';
$lang->qa->common         = 'QA';
$lang->devops->common     = 'DevOps';
$lang->doc->common        = 'Doc';
$lang->repo->common       = 'Code Repo';
$lang->repo->codeRepo     = 'Code Repo';
$lang->bi->common         = 'BI';
$lang->screen->common     = 'Screen';
$lang->pivot->common      = 'Pivot Table';
$lang->chart->common      = 'Chart';
$lang->metric->common       = 'Metric';
$lang->report->common     = 'Report';
$lang->system->common     = 'System';
$lang->admin->common      = 'Admin';
$lang->story->common      = 'Story';
$lang->task->common       = 'Task';
$lang->bug->common        = 'Bug';
$lang->testcase->common   = 'Testcase';
$lang->testtask->common   = 'Request';
$lang->score->common      = 'Score';
$lang->build->common      = 'Build';
$lang->testreport->common = 'Report';
$lang->automation->common = 'Automation';
$lang->team->common       = 'Team';
$lang->user->common       = 'User';
$lang->custom->common     = 'Custom';
$lang->custom->mode       = 'Mode';
$lang->custom->flow       = 'Concept';
$lang->extension->common  = 'Extension';
$lang->company->common    = 'Company';
$lang->dept->common       = 'Dept';
$lang->upgrade->common    = 'Update';
$lang->editor->common     = 'Editor';
$lang->program->list      = 'Program List';
$lang->program->kanban    = 'Program Kanban';
$lang->design->common     = 'Design';
$lang->design->HLDS       = 'HLDS';
$lang->design->DDS        = 'DDS';
$lang->design->DBDS       = 'DBDS';
$lang->design->ADS        = 'ADS';
$lang->stage->common      = 'Stage';
$lang->stage->type        = 'Stage Type';
$lang->stage->list        = 'Stage List';
$lang->stage->percent     = 'Workload Ratio';
$lang->execution->list    = "{$lang->executionCommon} List";
$lang->execution->CFD     = "Cumulative Flow Diagrams";
$lang->kanban->common     = 'Kanban';
$lang->backup->common     = 'Backup';
$lang->action->trash      = 'Recycle';
$lang->app->common        = 'APP';
$lang->app->serverLink    = 'Server Link';
$lang->review->common     = 'Review';
$lang->zahost->common     = 'ZAhost';
$lang->zanode->common     = 'ZAnode';
$lang->dimension->common  = 'Dimension';
$lang->contact->common    = 'Contacts';
$lang->space->common      = 'Service';
$lang->store->common      = 'Store';
$lang->instance->common   = 'Instance';

$lang->programstakeholder->common = 'Stakeholder';
$lang->featureswitch->common      = 'Features On/Off';
$lang->importdata->common         = 'Import data';
$lang->systemsetting->common      = 'System setting';
$lang->staffmanage->common        = 'User management';
$lang->modelconfig->common        = 'Pattern setting';
$lang->featureconfig->common      = 'Features config';
$lang->doctemplate->common        = 'Doc template';
$lang->notifysetting->common      = 'Notification';
$lang->bidesign->common           = 'BI design';
$lang->personalsettings->common   = 'Personal setting ';
$lang->projectsettings->common    = 'Setting';
$lang->dataaccess->common         = 'Data permission';
$lang->executiongantt->common     = 'Gantt chart';
$lang->executionkanban->common    = 'Kanban';
$lang->executionburn->common      = 'Burndown chart';
$lang->executioncfd->common       = 'Cumulative Flow Diagram';
$lang->executionstory->common     = 'Story';
$lang->executionqa->common        = 'QA';
$lang->executionsettings->common  = 'Setting';
$lang->generalcomment->common     = 'Comment';
$lang->generalping->common        = 'Timeout prevention';
$lang->generaltemplate->common    = 'Template';
$lang->generaleffort->common      = 'General log';
$lang->productsettings->common    = 'Product setting';
$lang->projectreview->common      = 'Review';
$lang->projecttrack->common       = 'Matrix';
$lang->projectqa->common          = 'QA';
$lang->holidayseason->common      = 'Holiday';
$lang->codereview->common         = 'Review';
$lang->repocode->common           = 'Code';

$lang->personnel->common     = 'Member';
$lang->personnel->invest     = 'Investment';
$lang->personnel->accessible = 'Accessible';

$lang->stakeholder->common = 'Stakeholder';
$lang->release->common     = 'Release';
$lang->message->common     = 'Message';
$lang->mail->common        = 'Mail';

$lang->my->shortCommon          = 'My';
$lang->testcase->shortCommon    = 'Case';
$lang->productplan->shortCommon = 'Plan';
$lang->score->shortCommon       = 'Score';
$lang->testreport->shortCommon  = 'Report';
$lang->qa->shortCommon          = 'QA';
$lang->researchplan->common     = 'Research';
$lang->workestimation->common   = 'Estimation';
$lang->gapanalysis->common      = 'Training';
$lang->executionview->common    = 'View';
$lang->managespace->common      = 'Space management';
$lang->systemteam->common       = 'System team';
$lang->systemschedule->common   = 'System calendar';
$lang->systemeffort->common     = 'System effort';
$lang->systemdynamic->common    = 'System dynamic';
$lang->systemcompany->common    = 'System company';
$lang->pipeline->common         = 'Pipeline';
$lang->devopssetting->common    = 'Setting';

$lang->dashboard       = 'Dashboard';
$lang->contribute      = 'Contribute';
$lang->dynamic         = 'Dynamic';
$lang->whitelist       = 'Liste blanche';
$lang->roadmap         = 'Roadmap';
$lang->track           = 'Track';
$lang->settings        = 'Settings';
$lang->overview        = 'Overview';
$lang->module          = 'Module';
$lang->priv            = 'Privilege';
$lang->other           = 'Other';
$lang->estimation      = 'Estimation';
$lang->measure         = 'Report';
$lang->treeView        = 'Tree View';
$lang->groupView       = 'Group View';
$lang->executionKanban = 'Kanban';
$lang->burn            = 'Burndown';
$lang->view            = 'View';
$lang->intro           = 'Introduction';
$lang->indexPage       = 'Index';
$lang->model           = 'Model';
$lang->redev           = 'Develop';
$lang->browser         = 'Browser';
$lang->db              = 'Database';
$lang->langItem        = 'Lang Item';
$lang->api->doc        = 'API Document';
$lang->database        = 'Data Dictionary';
$lang->timezone        = 'Timezone';
$lang->security        = 'Security';
$lang->calendar        = 'Calendar';

$lang->my->work = 'Work';

$lang->project->list   = $lang->projectCommon . ' List';
$lang->project->kanban = $lang->projectCommon . ' Kanban';

$lang->execution->executionKanban = "{$lang->execution->common} Kanban";
$lang->execution->all             = "{$lang->execution->common} List";

$lang->doc->recent        = 'Recent';
$lang->doc->my            = 'My';
$lang->doc->favorite      = 'Favorite';
$lang->doc->product       = $lang->productCommon;
$lang->doc->project       = $lang->projectCommon;
$lang->doc->api           = 'API';
$lang->doc->execution     = $lang->execution->common;
$lang->doc->custom        = 'Custom';
$lang->doc->wiki          = 'Wiki';
$lang->doc->apiDoc        = 'API Docuemnt';
$lang->doc->apiStruct     = 'Data Structure';
$lang->doc->mySpace       = 'My Space';
$lang->doc->productSpace  = "{$lang->productCommon} Space";
$lang->doc->projectSpace  = "{$lang->projectCommon} Space";
$lang->doc->apiSpace      = 'API Space';
$lang->doc->teamSpace     = 'Team Space';

$lang->product->list   = $lang->productCommon . ' List';
$lang->product->kanban = $lang->productCommon . ' Kanban';

$lang->project->report = 'Report';

$lang->report->weekly       = 'Weekly';
$lang->report->notice       = new stdclass();
$lang->report->notice->help = 'Note : Le rapport est généré à partir des résultats de la liste consultée. Par exemple, cliquez sur AssignedToMe, puis Générer Rapport pour obtenir un rapport basé sur la liste de ce qui vous est assigné.';

$lang->testcase->case      = 'Test Case';
$lang->testcase->testsuite = 'Test Suite';
$lang->testcase->caselib   = 'Case Library';

$lang->devops->compile      = 'Pipelines';
$lang->devops->mr           = 'Merge Request';
$lang->devops->repo         = 'Repo';
$lang->devops->rules        = 'Rule';
$lang->devops->settings     = 'Setting Merge Request';
$lang->devops->platform     = 'Platform';
$lang->devops->set          = 'Set';
$lang->devops->artifactrepo = 'Artifact Repo';
$lang->devops->environment  = 'Environment';
$lang->devops->resource     = 'Resource';
$lang->devops->dblist       = 'Database';
$lang->devops->domain       = 'Domain';
$lang->devops->oss          = 'Oss';
$lang->devops->host         = 'Host';
$lang->devops->account      = 'Account';
$lang->devops->serverroom   = 'IDC';
$lang->devops->deploy       = 'Deploy';
$lang->devops->provider     = 'IDC Provider';
$lang->devops->cpuBrand     = 'CPU Brand';
$lang->devops->city         = 'IDC Location';
$lang->devops->os           = 'OS Version';
$lang->devops->stage        = 'Stage';
$lang->devops->service      = 'Service';

$lang->admin->module      = 'Module';
$lang->admin->system      = 'System';
$lang->admin->entry       = 'Access ZenTao';
$lang->admin->data        = 'Data';
$lang->admin->cron        = 'Cron';
$lang->admin->buildIndex  = 'Full Text Search';
$lang->admin->tableEngine = 'Table Engine';

$lang->convert->importJira = 'Import Jira';

$lang->storyConcept = 'Story Concpet';

$lang->searchTips = '';
$lang->searchAB   = 'Search';

/* Object list in search form. */
$lang->searchObjects['all']         = 'All';
$lang->searchObjects['bug']         = 'Bug';
$lang->searchObjects['story']       = 'Story';
$lang->searchObjects['task']        = 'Tâche';
$lang->searchObjects['testcase']    = 'CasTest';
$lang->searchObjects['product']     = $lang->productCommon;
$lang->searchObjects['build']       = 'Build';
$lang->searchObjects['release']     = 'Release';
$lang->searchObjects['productplan'] = $lang->productCommon . 'Plan';
$lang->searchObjects['testtask']    = 'Recette';
$lang->searchObjects['doc']         = 'Document';
$lang->searchObjects['caselib']     = 'Case Library';
$lang->searchObjects['testreport']  = 'CR de Test';
$lang->searchObjects['program']     = 'Program';
$lang->searchObjects['project']     = $lang->projectCommon;
$lang->searchObjects['execution']   = $lang->execution->common;
$lang->searchObjects['user']        = 'User';
$lang->searchTips                   = '';

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

$lang->visionList = array();
$lang->visionList['rnd']  = 'Full Feature Interface';
$lang->visionList['lite'] = 'Operation Management Interface';

if($config->edition == 'ipd')
{
    $lang->visionList['or']   = 'OR & MM Interface';
    $lang->visionList['rnd']  = 'IPD Interface';
}

$lang->createObjects['todo']        = 'Todo';
$lang->createObjects['effort']      = 'Effort';
$lang->createObjects['bug']         = 'Bug';
$lang->createObjects['story']       = $lang->SRCommon;
$lang->createObjects['task']        = 'Task';
$lang->createObjects['testcase']    = 'Case';
$lang->createObjects['execution']   = $lang->execution->common;
$lang->createObjects['project']     = $lang->projectCommon;
$lang->createObjects['product']     = $lang->productCommon;
$lang->createObjects['program']     = 'Program';
$lang->createObjects['doc']         = 'Doc';
$lang->createObjects['kanbanspace'] = 'Space';
$lang->createObjects['kanban']      = 'Kanban';

/* Language. */
$lang->lang    = 'Langue';
$lang->setLang = 'Language Setting';

/* Theme style. */
$lang->theme                = 'Theme';
$lang->themes['default']    = 'Default';
$lang->themes['blue']       = 'Young Blue';
$lang->themes['green']      = 'Green';
$lang->themes['red']        = 'Red';
$lang->themes['purple']     = 'Purple';
$lang->themes['pink']       = 'Pink';
$lang->themes['blackberry'] = 'Blackberry';
$lang->themes['classic']    = 'Classic';

/* Error info. */
$lang->error = new stdclass();
$lang->error->companyNotFound = "Le domaine %s ne peut être trouvé !";
$lang->error->length          = array("『 %s 』erreur de longueur. Il devrait être『%s』", "La longueur de 『%s』devrait être <=『%s』et >『%s』.");
$lang->error->reg             = "『 %s 』erreur de format. Il devrait être『%s』.";
$lang->error->unique          = "『 %s 』『 %s 』existes. Allez à Admin->System->Data->Recycle Bin pour le restaurer, si vous êtes sûr qu'il est supprimé.";
$lang->error->repeat          = "『 %s 』『 %s 』existes.";
$lang->error->gt              = "『 %s 』devrait être > 『 %s 』.";
$lang->error->ge              = "『 %s 』devrait être >= 『 %s 』.";
$lang->error->lt              = "『%s』should be <『%s』。";
$lang->error->le              = "『%s』should be <=『%s』。";
$lang->error->notempty        = "『 %s 』ne devrait pas être à blanc.";
$lang->error->empty           = "『 %s 』devrait être nul.";
$lang->error->equal           = "『 %s 』doit être 『 %s 』.";
$lang->error->int             = array("『 %s 』devrait être des nombres", "『 %s 』devrait être 『 %s-%s 』.");
$lang->error->float           = "『 %s 』devrait avoir des nombres ou des décimales.";
$lang->error->email           = "『 %s 』doit être une adresse mail valide.";
$lang->error->phone           = "『%s』should be valid phone number.";
$lang->error->mobile          = "『%s』should be valid mobile number.";
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
$lang->error->loginTimeout    = 'Login has timed out, please login again!';
$lang->error->httpServerError = 'Server error';

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
$lang->pager->totalCountAB = "Total: {recTotal} lignes";
$lang->pager->pageSizeAB   = "{recPerPage} par page";

$lang->pager->shortPageSize = '<strong>{recPerPage}</strong> / Page';

$lang->colorPicker = new stdclass();
$lang->colorPicker->errorTip = "Ce n'est pas une valeur de couleur valide";

$lang->downNotify     = "Télécharger la notification sur le bureau";
$lang->clientName     = "Desktop";
$lang->downloadClient = "Télécharger ZenTao Desktop";
$lang->downloadMobile = "Download Mobile Terminal";
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
if(!defined('DT_DATETIME1'))      define('DT_DATETIME1',  'Y-m-d H:i:s');
if(!defined('DT_DATETIME2'))      define('DT_DATETIME2',  'y-m-d H:i');
if(!defined('DT_MONTHTIME1'))     define('DT_MONTHTIME1', 'n/d H:i');
if(!defined('DT_MONTHTIME2'))     define('DT_MONTHTIME2', 'n/d H:i');
if(!defined('DT_DATE1'))          define('DT_DATE1',     'Y-m-d');
if(!defined('DT_DATE2'))          define('DT_DATE2',     'Ymd');
if(!defined('DT_DATE3'))          define('DT_DATE3',     'Y/m/d');
if(!defined('DT_DATE4'))          define('DT_DATE4',     'M d');
if(!defined('DT_DATE5'))          define('DT_DATE5',     'j/n');
if(!defined('DT_TIME1'))          define('DT_TIME1',     'H:i:s');
if(!defined('DT_TIME2'))          define('DT_TIME2',     'H:i');
if(!defined('LONG_TIME'))         define('LONG_TIME',    '2059-12-31');
if(!defined('BRANCH_MAIN'))       define('BRANCH_MAIN', '0');
if(!defined('DEFAULT_CARDCOUNT')) define('DEFAULT_CARDCOUNT', '2');
if(!defined('MAX_CARDCOUNT'))     define('MAX_CARDCOUNT', '100');

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

include (dirname(__FILE__) . '/menu.php');
