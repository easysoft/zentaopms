<?php
/**
 * The product module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: en.php 5091 2013-07-10 06:06:46Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->product->index            = $lang->productCommon . 'Home';
$lang->product->browse           = 'Story Liste';
$lang->product->requirement      = 'Requirement Liste';
$lang->product->dynamic          = 'Verlauf';
$lang->product->view             = 'Übersicht';
$lang->product->edit             = "{$lang->productCommon} bearbeiten";
$lang->product->batchEdit        = 'Mehrere bearbeiten';
$lang->product->create           = "Erstelle {$lang->productCommon}";
$lang->product->delete           = "Lösche {$lang->productCommon}";
$lang->product->deleted          = 'Gelöscht';
$lang->product->close            = 'Schließen';
$lang->product->activate         = 'Activate';
$lang->product->select           = "Auswahl {$lang->productCommon}";
$lang->product->mine             = 'Meine Zuständigkeiten';
$lang->product->other            = 'Andere';
$lang->product->closed           = 'Geschlossen';
$lang->product->closedProduct    = "Closed {$lang->productCommon}";
$lang->product->updateOrder      = 'Ranking';
$lang->product->all              = "{$lang->productCommon} List";
$lang->product->involved         = "My Involved";
$lang->product->manageLine       = "Manage Product Line";
$lang->product->newLine          = "Create Product Line";
$lang->product->export           = 'Exportiere Daten';
$lang->product->dashboard        = 'Dashboard';
$lang->product->changeProgram    = "{$lang->productCommon} confirmation of the scope of influence of adjustment of the program set";
$lang->product->changeProgramTip = "%s > Change Program";
$lang->product->addWhitelist     = 'Add Whitelist';
$lang->product->unbindWhitelist  = 'Remove Whitelist';
$lang->product->track            = 'View Stories Matrix';
$lang->product->checkedProducts  = "%s {$lang->productCommon}s selected";
$lang->product->pageSummary      = "Total {$lang->productCommon}s: %s.";
$lang->product->lineSummary      = "Total product lines: %s, Total {$lang->productCommon}s: %s.";

$lang->product->indexAction    = "All {$lang->productCommon}";
$lang->product->closeAction    = "Close {$lang->productCommon}";
$lang->product->activateAction = "Activate {$lang->productCommon}";
$lang->product->orderAction    = "Rank {$lang->productCommon}";
$lang->product->exportAction   = "Export {$lang->productCommon}";
$lang->product->link2Project   = "Link {$lang->projectCommon}";

$lang->product->basicInfo = 'Basis Info';
$lang->product->otherInfo = 'Andere Info';

$lang->product->plans       = 'Plan';
$lang->product->releases    = 'Release';
$lang->product->docs        = 'Dok';
$lang->product->bugs        = 'Verknüpfte Bugs';
$lang->product->projects    = "Linked {$lang->projectCommon}";
$lang->product->executions  = "Verknüpfte {$lang->execution->common}";
$lang->product->cases       = 'Fälle';
$lang->product->builds      = 'Builds';
$lang->product->roadmap     = 'Roadmap';
$lang->product->doc         = 'Dok';
$lang->product->project     = $lang->projectCommon . 'Liste';
$lang->product->moreProduct = "More {$lang->productCommon}";
$lang->product->projectInfo = "My {$lang->projectCommon}s that are linked to this {$lang->productCommon} are listed below.";
$lang->product->progress    = "Progress";

$lang->product->currentExecution      = "Aktuelle Execution";
$lang->product->activeStories         = 'Aktivierte [S]';
$lang->product->activeStoriesTitle    = 'Active Stories';
$lang->product->changedStories        = 'Geänderte [S]';
$lang->product->changedStoriesTitle   = 'Changed Stories';
$lang->product->draftStories          = 'Entwurf [S]';
$lang->product->draftStoriesTitle     = 'Draft Stories';
$lang->product->reviewingStories      = "Reviewing [S]";
$lang->product->reviewingStoriesTitle = "Reviewing Stories";
$lang->product->closedStories         = 'Geschlossene [S]';
$lang->product->closedStoriesTitle    = 'Closed Stories';
$lang->product->storyCompleteRate     = "{$lang->SRCommon} Completion rate";
$lang->product->activeRequirements    = "Active {$lang->URCommon}";
$lang->product->changedRequirements   = "Changed {$lang->URCommon}";
$lang->product->draftRequirements     = "Draft {$lang->URCommon}";
$lang->product->closedRequirements    = "Closed {$lang->URCommon}";
$lang->product->requireCompleteRate   = "{$lang->URCommon} Completion rate";
$lang->product->unResolvedBugs        = 'Ungelöste [B]';
$lang->product->unResolvedBugsTitle   = 'Active Bugs';
$lang->product->assignToNullBugs      = 'Nicht zugewiesene [B]';
$lang->product->assignToNullBugsTitle = 'Unassigned Bugs';
$lang->product->closedBugs            = 'Closed Bug';
$lang->product->bugFixedRate          = 'Bug Repair rate';
$lang->product->unfoldClosed          = 'Unfold Closed';

$lang->product->confirmDelete        = " Möchten Sie {$lang->productCommon} löschen?";
$lang->product->errorNoProduct       = "Kein {$lang->productCommon} erstellt!";
$lang->product->accessDenied         = "Sie haben keinen Zugriff auf {$lang->productCommon}.";
$lang->product->notExists            = "{$lang->productCommon} is not exists!";
$lang->product->programChangeTip     = "The {$lang->projectCommon}s linked with this {$lang->productCommon}: %s will be transferred to the modified program set together.";
$lang->product->notChangeProgramTip  = "The {$lang->SRCommon} of {$lang->productCommon} has been linked to the following {$lang->projectCommon}s, please cancel the link before proceeding";
$lang->product->confirmChangeProgram = "The {$lang->projectCommon}s linked with this {$lang->productCommon}: %s is also linked with other {$lang->productCommon}s, whether to transfer {$lang->projectCommon}s to the modified program set.";
$lang->product->changeProgramError   = "The {$lang->SRCommon} of this {$lang->productCommon} has been linked to the {$lang->projectCommon}, please unlink it before proceeding";
$lang->product->changeLineError      = "{$lang->productCommon}s already exist under the product line, so the program within them cannot be modified.";
$lang->product->programEmpty         = 'Program should not be empty!';
$lang->product->nameIsDuplicate      = "『%s』 product line already exists, please reset!";
$lang->product->nameIsDuplicated     = "Product Line『%s』 exists. Go to Admin->System->Data->Recycle Bin to restore it, if you are sure it is deleted.";
$lang->product->reviewStory          = 'You are not a reviewer for needs "%s" , and cannot review. This operation has been filtered';

$lang->product->id             = 'ID';
$lang->product->program        = "Program";
$lang->product->name           = 'Name';
$lang->product->code           = 'Alias';
$lang->product->shadow         = "Shadow {$lang->productCommon}";
$lang->product->line           = "Product Line";
$lang->product->lineName       = "Product Line Name";
$lang->product->order          = 'Sortierung';
$lang->product->bind           = 'In/Depedent';
$lang->product->type           = 'Typ';
$lang->product->typeAB         = 'Typ';
$lang->product->status         = 'Status';
$lang->product->subStatus      = 'Sub Status';
$lang->product->desc           = 'Beschreibung';
$lang->product->manager        = 'Manager';
$lang->product->PO             = 'PO';
$lang->product->QD             = 'QS Manager';
$lang->product->RD             = 'Release Manager';
$lang->product->feedback       = 'Feedback Manger';
$lang->product->acl            = 'Zugriffskontrolle';
$lang->product->reviewer       = 'Reviewer';
$lang->product->whitelist      = 'Whitelist';
$lang->product->branch         = '%s';
$lang->product->qa             = 'QA';
$lang->product->release        = 'Release';
$lang->product->allRelease     = 'All Releases';
$lang->product->maintain       = 'Maintenance';
$lang->product->latestDynamic  = 'Verlauf';
$lang->product->plan           = 'Plan';
$lang->product->iteration      = 'Version Iteration';
$lang->product->iterationInfo  = '%s Iterations';
$lang->product->iterationView  = 'Details';
$lang->product->createdBy      = 'Created By';
$lang->product->createdDate    = 'Created Date';
$lang->product->createdVersion = 'Created Version';
$lang->product->mailto         = 'Mailto';

$lang->product->searchStory    = 'Suche';
$lang->product->assignedToMe   = 'Mir zuweisen';
$lang->product->openedByMe     = 'Von mir erstellt';
$lang->product->reviewedByMe   = 'Von mir geprüft';
$lang->product->reviewByMe     = 'ReviewByMe';
$lang->product->closedByMe     = 'Von mir geschlossen';
$lang->product->draftStory     = 'Entwurf';
$lang->product->activeStory    = 'Aktiviert';
$lang->product->changingStory  = 'Ändern';
$lang->product->reviewingStory = 'Wird geprüft';
$lang->product->willClose      = 'Zu schließen';
$lang->product->closedStory    = 'Geschlossen';
$lang->product->unclosed       = 'Offen';
$lang->product->unplan         = 'Warten';
$lang->product->viewByUser     = 'By User';
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

$lang->product->allStory             = 'Alle';
$lang->product->allProduct           = 'Alle' . $lang->productCommon;
$lang->product->allProductsOfProject = 'Alle verknüpften' . $lang->productCommon;

$lang->product->typeList['']         = '';
$lang->product->typeList['normal']   = 'Normal';
$lang->product->typeList['branch']   = 'Multi Branch';
$lang->product->typeList['platform'] = 'Multi Platform';

$lang->product->typeTips = array();
$lang->product->typeTips['branch']   = '(für eigene Inhalte)';
$lang->product->typeTips['platform'] = '(für IOS, Android, PC, etc.)';

$lang->product->branchName['normal']   = '';
$lang->product->branchName['branch']   = 'Branch';
$lang->product->branchName['platform'] = 'Platform';

$lang->product->statusList['normal'] = 'Normal';
$lang->product->statusList['closed'] = 'Geschlossen';

global $config;
if($config->systemMode == 'Lite')
{
    $lang->product->aclList['private'] = "Private {$lang->productCommon} (Team members and stakeholders of the associated {$lang->projectCommon} can access)";
}
else
{
    $lang->product->aclList['private'] = "Private {$lang->productCommon} (Manager and Stakeholders of the respective program, team members and stakeholders of the associated {$lang->projectCommon} can access)";
}
$lang->product->aclList['open']    = "Standard (Benutzer mit Rechten für {$lang->productCommon} können zugreifen.)";
//$lang->product->aclList['custom']  = 'Benutzerdefiniert (Teammitglieder und Whitelist Benutzer haben Zugriff.)';

$lang->product->acls['private'] = "Privat {$lang->productCommon}";
$lang->product->acls['open']    = "Standard";

$lang->product->aclTips['open']    = "Benutzer mit Rechten für {$lang->productCommon} können zugreifen.";
$lang->product->aclTips['private'] = "{$lang->executionCommon} Nur Teammitglieder";

$lang->product->storySummary   = " <strong>%s</strong> %s, <strong>%s</strong> Stunde(n) geplant, Fallabdeckung ist <strong>%s</strong> auf dieser Seite.";
$lang->product->checkedSummary = " <strong>%total%</strong> geprüft, <strong>%estimate%</strong> Stunde(n) geplant, Fallabdeckung ist <strong>%rate%</strong>.";
$lang->product->noModule       = '<div>Kein Modul</div><div>Jetzt verwalten</div>';
$lang->product->noProduct      = 'Kein Produkt. ';
$lang->product->noMatched      = '"%s" kann nicht gefunden werden.' . $lang->productCommon;

$lang->product->featureBar['browse']['allstory']     = $lang->product->allStory;
$lang->product->featureBar['browse']['unclosed']     = $lang->product->unclosed;
$lang->product->featureBar['browse']['assignedtome'] = $lang->product->assignedToMe;

$lang->product->featureBar['browse']['reviewbyme']   = $lang->product->reviewByMe;
$lang->product->featureBar['browse']['draftstory']   = $lang->product->draftStory;
$lang->product->featureBar['browse']['more']         = $lang->more;

$lang->product->featureBar['all']['all']      = $lang->product->allProduct;
$lang->product->featureBar['all']['noclosed'] = $lang->product->unclosed;
$lang->product->featureBar['all']['closed']   = $lang->product->statusList['closed'];

$lang->product->featureBar['project']['all']       = 'Alle';
$lang->product->featureBar['project']['undone']    = 'Geschlossen';
$lang->product->featureBar['project']['wait']      = 'Wartend';
$lang->product->featureBar['project']['doing']     = 'In Arbeit';
$lang->product->featureBar['project']['suspended'] = 'Ausgesetzt';
$lang->product->featureBar['project']['closed']    = 'Geschlossen';

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
