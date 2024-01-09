<?php
/**
 * The product module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: en.php 5091 2013-07-10 06:06:46Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->product->index            = 'Accueil ' . $lang->productCommon;
$lang->product->browse           = 'Liste Stories';
$lang->product->requirement      = 'Liste Requirements';
$lang->product->dynamic          = 'Historique';
$lang->product->view             = "{$lang->productCommon} Détail";
$lang->product->edit             = "Editer {$lang->productCommon}";
$lang->product->batchEdit        = 'Editer par Lot';
$lang->product->create           = "Créer {$lang->productCommon}";
$lang->product->delete           = "Supprimer {$lang->productCommon}";
$lang->product->deleted          = 'Supprimé';
$lang->product->close            = "Fermer";
$lang->product->activate         = 'Activate';
$lang->product->select           = "Choisir {$lang->productCommon}";
$lang->product->mine             = 'Les miens';
$lang->product->other            = 'Autres';
$lang->product->closed           = 'Fermés';
$lang->product->closedProduct    = "Closed {$lang->productCommon}";
$lang->product->updateOrder      = 'Ordre';
$lang->product->all              = "{$lang->productCommon} List";
$lang->product->involved         = "My Involved";
$lang->product->manageLine       = "Manage Product Line";
$lang->product->newLine          = "Create Product Line";
$lang->product->export           = 'Export';
$lang->product->dashboard        = "Dashboard";
$lang->product->changeProgram    = "{$lang->productCommon} confirmation of the scope of influence of adjustment of the program set";
$lang->product->changeProgramTip = "%s > Change Program";
$lang->product->selectProgram    = 'Filter Programs';
$lang->product->addWhitelist     = 'Add Whitelist';
$lang->product->unbindWhitelist  = 'Remove Whitelist';
$lang->product->track            = 'Consulter Stories Matrice';
$lang->product->checkedProducts  = "%s {$lang->productCommon}s selected";
$lang->product->pageSummary      = "Total {$lang->productCommon}s: %s.";
$lang->product->lineSummary      = "Total product lines: %s, Total {$lang->productCommon}s: %s.";

$lang->product->indexAction    = "All {$lang->productCommon}";
$lang->product->closeAction    = "Fermer {$lang->productCommon}";
$lang->product->activateAction = "Activate {$lang->productCommon}";
$lang->product->orderAction    = "Rang {$lang->productCommon}";
$lang->product->exportAction   = "Export {$lang->productCommon}";
$lang->product->link2Project   = "Link {$lang->projectCommon}";

$lang->product->basicInfo = 'Infos de Base';
$lang->product->otherInfo = 'Autres Infos';

$lang->product->plans       = 'Plans';
$lang->product->releases    = 'Releases';
$lang->product->docs        = 'Doc';
$lang->product->bugs        = 'Bug Liés';
$lang->product->projects    = "Linked {$lang->projectCommon}";
$lang->product->executions  = "{$lang->execution->common}s Liés";
$lang->product->cases       = 'CasTests';
$lang->product->builds      = 'Build';
$lang->product->roadmap     = "Roadmap {$lang->productCommon}";
$lang->product->doc         = "Documents {$lang->productCommon}";
$lang->product->project     = ' Liste ' . $lang->projectCommon;
$lang->product->moreProduct = "More {$lang->productCommon}";
$lang->product->projectInfo = "My {$lang->projectCommon}s that are linked to this {$lang->productCommon} are listed below.";
$lang->product->progress    = "Progress";

$lang->product->currentExecution      = "Current Execution";
$lang->product->activeStories         = 'Actives [S]';
$lang->product->activeStoriesTitle    = 'Stories Actives';
$lang->product->changedStories        = 'Changées [S]';
$lang->product->changedStoriesTitle   = 'Stories Modifiées';
$lang->product->draftStories          = 'Brouillon [S]';
$lang->product->draftStoriesTitle     = 'Stories en Analyse';
$lang->product->reviewingStories      = "Reviewing [S]";
$lang->product->reviewingStoriesTitle = "Reviewing Stories";
$lang->product->closedStories         = 'Fermées [S]';
$lang->product->closedStoriesTitle    = 'Stories Fermées';
$lang->product->storyCompleteRate     = "{$lang->SRCommon} Completion rate";
$lang->product->activeRequirements    = "Active {$lang->URCommon}";
$lang->product->changedRequirements   = "Changed {$lang->URCommon}";
$lang->product->draftRequirements     = "Draft {$lang->URCommon}";
$lang->product->closedRequirements    = "Closed {$lang->URCommon}";
$lang->product->requireCompleteRate   = "{$lang->URCommon} Completion rate";
$lang->product->unResolvedBugs        = 'Ouverts [B]';
$lang->product->unResolvedBugsTitle   = 'Bugs Ouverts';
$lang->product->assignToNullBugs      = 'Orphelins [B]';
$lang->product->assignToNullBugsTitle = 'Bugs non assignés';
$lang->product->closedBugs            = 'Closed Bug';
$lang->product->bugFixedRate          = 'Repair Rate';
$lang->product->unfoldClosed          = 'Unfold Closed';
$lang->product->storyDeliveryRate     = "Story Delivery Rate";
$lang->product->storyDeliveryRateTip  = "Story Delivery Rate = The released or done stories / (Total stories - Closed reason is not done）* 100%";

$lang->product->confirmDelete        = "Voulez-vous vraiment supprimer le {$lang->productCommon} ?";
$lang->product->errorNoProduct       = "Aucun {$lang->productCommon} n'est créé pour l'instant !";
$lang->product->accessDenied         = "Vous n'avez pas accès au {$lang->productCommon}.";
$lang->product->notExists            = "{$lang->productCommon} is not exists!";
$lang->product->programChangeTip     = "The {$lang->projectCommon}s linked with this {$lang->productCommon}: %s will be transferred to the modified program set together.";
$lang->product->notChangeProgramTip  = "The {$lang->SRCommon} of {$lang->productCommon} has been linked to the following {$lang->projectCommon}s, please cancel the link before proceeding";
$lang->product->confirmChangeProgram = "The {$lang->projectCommon}s linked with this {$lang->productCommon}: %s is also linked with other {$lang->productCommon}s, whether to transfer {$lang->projectCommon}s to the modified program set.";
$lang->product->changeProgramError   = "The {$lang->SRCommon} of this {$lang->productCommon} has been linked to the {$lang->projectCommon}, please unlink it before proceeding";
$lang->product->changeLineError      = "{$lang->productCommon}s already exist under the product line 『%s』, so the program within them cannot be modified.";
$lang->product->programEmpty         = 'Program should not be empty!';
$lang->product->nameIsDuplicate      = "『%s』 product line already exists, please reset!";
$lang->product->nameIsDuplicated     = "Product Line『%s』 exists. Go to Admin->System->Data->Recycle Bin to restore it, if you are sure it is deleted.";
$lang->product->reviewStory          = 'You are not a reviewer for needs "%s" , and cannot review. This operation has been filtered';
$lang->product->confirmDeleteLine    = "Do you want to delete this product line?";

$lang->product->id             = 'ID';
$lang->product->program        = "Program";
$lang->product->name           = "Nom du {$lang->productCommon}";
$lang->product->code           = 'Code';
$lang->product->shadow         = "Shadow {$lang->productCommon}";
$lang->product->line           = "Product Line";
$lang->product->lineName       = "Product Line Name";
$lang->product->order          = 'Rang';
$lang->product->bind           = 'In/Depedent';
$lang->product->type           = 'Type';
$lang->product->typeAB         = 'Type';
$lang->product->status         = 'Statut';
$lang->product->subStatus      = 'Sous-Statut';
$lang->product->desc           = 'Description';
$lang->product->manager        = 'Managers';
$lang->product->PO             = "{$lang->productCommon} Owner";
$lang->product->QD             = 'Quality Manager';
$lang->product->RD             = 'Release Manager';
$lang->product->feedback       = 'Feedback Manger';
$lang->product->ticket         = 'Ticket Manager';
$lang->product->acl            = "Contrôle accès";
$lang->product->reviewer       = 'Reviewer';
$lang->product->groups         = 'Groups';
$lang->product->users          = 'Users';
$lang->product->whitelist      = 'Liste Blanche';
$lang->product->branch         = '%s';
$lang->product->qa             = 'QA';
$lang->product->release        = 'Release';
$lang->product->allRelease     = 'Toutes Releases';
$lang->product->maintain       = 'Maintenance';
$lang->product->latestDynamic  = 'Historique';
$lang->product->plan           = 'Plan';
$lang->product->iteration      = 'Itérations';
$lang->product->iterationInfo  = '%s Itération';
$lang->product->iterationView  = 'Détail';
$lang->product->createdBy      = 'Créé par';
$lang->product->createdDate    = 'Créé le';
$lang->product->createdVersion = 'Created Version';
$lang->product->mailto         = 'Mailto';

$lang->product->searchStory    = 'Recherche';
$lang->product->assignedToMe   = 'Affectées à Moi';
$lang->product->openedByMe     = 'Créées par Moi';
$lang->product->reviewedByMe   = 'Validées par Moi';
$lang->product->reviewByMe     = 'ReviewByMe';
$lang->product->closedByMe     = 'Fermées par Moi';
$lang->product->draftStory     = 'A étudier';
$lang->product->activeStory    = 'Actives';
$lang->product->changingStory  = 'Changement en cours';
$lang->product->reviewingStory = 'Examen en cours';
$lang->product->willClose      = 'A Fermer';
$lang->product->closedStory    = 'Fermées';
$lang->product->unclosed       = 'Ouvertes';
$lang->product->unplan         = 'Non planifiées';
$lang->product->viewByUser     = 'Par Utilisateur';
$lang->product->assignedByMe   = 'AssignedByMe';

/* Product Kanban. */
$lang->product->myProduct             = "{$lang->productCommon}s Ownedbyme";
$lang->product->otherProduct          = "Other {$lang->productCommon}s";
$lang->product->unclosedProduct       = "Open {$lang->productCommon}s";
$lang->product->unexpiredPlan         = 'Unexpired Plans';
$lang->product->doing                 = 'Doing';
$lang->product->doingProject          = "Ongoing {$lang->projectCommon}s";
$lang->product->doingExecution        = 'Ongoing Executions';
$lang->product->doingClassicExecution = 'Ongoing ' . $lang->executionCommon;
$lang->product->normalRelease         = 'Normal Releases';
$lang->product->emptyProgram          = "Independent {$lang->productCommon}s";

$lang->product->allStory             = 'Toutes les Stories ';
$lang->product->allProduct           = 'Tous';
$lang->product->allProductsOfProject = 'Tous les ' . $lang->productCommon . ' Associés';

$lang->product->typeList['']         = '';
$lang->product->typeList['normal']   = 'Normal';
$lang->product->typeList['branch']   = 'Multi-Branche';
$lang->product->typeList['platform'] = 'Multi-Plateforme';

$lang->product->typeTips = array();
$lang->product->typeTips['branch']   = ' (pour des contextes personnalisés, ex : équipes offshore)';
$lang->product->typeTips['platform'] = ' (pour des applications multi-plateformes, ex : IOS, Android, PC, etc.)';

$lang->product->branchName['normal']   = '';
$lang->product->branchName['branch']   = 'Branche';
$lang->product->branchName['platform'] = 'Plateforme';

$lang->product->statusList['normal'] = 'Normal';
$lang->product->statusList['closed'] = 'Fermé';

global $config;
if($config->systemMode == 'ALM')
{
    $lang->product->aclList['private'] = "Private {$lang->productCommon} (Manager and Stakeholders of the respective program, team members and stakeholders of the associated {$lang->projectCommon} can access)";
}
else
{
    $lang->product->aclList['private'] = "Private {$lang->productCommon} (Team members and stakeholders of the associated {$lang->projectCommon} can access)";
}
$lang->product->aclList['open']    = "Défaut (Les utilisateurs ayant des droits sur {$lang->productCommon} peuvent accéder à ce {$lang->productCommon}.)";

$lang->product->abbr = new stdclass();
$lang->product->abbr->aclList['private'] = "{$lang->productCommon} Privé";
$lang->product->abbr->aclList['open']    = 'Défaut';

$lang->product->aclTips['open']    = "Les utilisateurs ayant des droits sur {$lang->productCommon} peuvent accéder à ce {$lang->productCommon}.";
$lang->product->aclTips['private'] = "les membres de l'équipe et les membres de la Liste blanche peuvent y accéder.";

$lang->product->storySummary       = "Total de <strong>%s</strong> %s sur cette page. Estimé: <strong>%s</strong> (h), et couverture de la recette: <strong>%s</strong>.";
$lang->product->checkedSRSummary   = "<strong>%total%</strong> %storyCommon% sélectionnées, Estimé: <strong>%estimate%</strong>, et couverture de la recette: <strong>%rate%</strong>.";
$lang->product->requirementSummary = "Total de <strong>%s</strong> %s sur cette page. Estimé: <strong>%s</strong> (h),.";
$lang->product->checkedURSummary   = "<strong>%total%</strong> %storyCommon% sélectionnées, Estimé: <strong>%estimate%</strong>,.";
$lang->product->noModule           = "<div>Vous n'avez aucun modules. </div><div>Gérer Maintenant</div>";
$lang->product->noProduct          = "No {$lang->productCommon} à ce jour. ";
$lang->product->noMatched          = '"%s" cannot be found.' . $lang->productCommon;

$lang->product->featureBar['browse']['allstory']     = $lang->product->allStory;
$lang->product->featureBar['browse']['unclosed']     = $lang->product->unclosed;
$lang->product->featureBar['browse']['assignedtome'] = $lang->product->assignedToMe;

$lang->product->featureBar['browse']['reviewbyme']   = $lang->product->reviewByMe;
$lang->product->featureBar['browse']['draftstory']   = $lang->product->draftStory;
$lang->product->featureBar['browse']['more']         = $lang->more;

$lang->product->featureBar['all']['all']      = $lang->product->allProduct;
$lang->product->featureBar['all']['noclosed'] = $lang->product->unclosed;
$lang->product->featureBar['all']['closed']   = $lang->product->statusList['closed'];

$lang->product->featureBar['project']['all']       = "Tous les {$lang->executionCommon}s";
$lang->product->featureBar['project']['undone']    = 'Non Terminé';
$lang->product->featureBar['project']['wait']      = 'En attente';
$lang->product->featureBar['project']['doing']     = 'En cours';
$lang->product->featureBar['project']['suspended'] = 'Suspendu';
$lang->product->featureBar['project']['closed']    = 'Fermé';

$lang->product->moreSelects['browse']['more']['openedbyme']     = $lang->product->openedByMe;
$lang->product->moreSelects['browse']['more']['reviewedbyme']   = $lang->product->reviewedByMe;
$lang->product->moreSelects['browse']['more']['assignedbyme']   = $lang->product->assignedByMe;
$lang->product->moreSelects['browse']['more']['closedbyme']     = $lang->product->closedByMe;
$lang->product->moreSelects['browse']['more']['activestory']    = $lang->product->activeStory;
$lang->product->moreSelects['browse']['more']['changingstory']  = $lang->product->changingStory;
$lang->product->moreSelects['browse']['more']['reviewingstory'] = $lang->product->reviewingStory;
$lang->product->moreSelects['browse']['more']['willclose']      = $lang->product->willClose;
$lang->product->moreSelects['browse']['more']['closedstory']    = $lang->product->closedStory;

$lang->product->featureBar['dynamic']['all']       = 'All';
$lang->product->featureBar['dynamic']['today']     = 'Today';
$lang->product->featureBar['dynamic']['yesterday'] = 'Yesterday';
$lang->product->featureBar['dynamic']['thisWeek']  = 'This Week';
$lang->product->featureBar['dynamic']['lastWeek']  = 'Last Week';
$lang->product->featureBar['dynamic']['thisMonth'] = 'This Month';
$lang->product->featureBar['dynamic']['lastMonth'] = 'Last Month';

$lang->product->action = new stdclass();
$lang->product->action->activate = array('main' => '$date, activated by <strong>$actor</strong>.');

$lang->product->belongingLine     = 'Product Line';
$lang->product->testCaseCoverage  = 'Case Coverage';
$lang->product->activatedBug      = 'Activated Bugs';
$lang->product->completeRate      = 'Completion Rate';
$lang->product->editLine          = 'Edit Product Line';
$lang->product->totalBugs         = 'Total Bugs';
$lang->product->totalStories      = 'Total Stories';
$lang->product->latestReleaseDate = 'Latest Release Date';
$lang->product->latestRelease     = 'Latest Release';
