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
$lang->product->index            = $lang->productCommon . ' Home';
$lang->product->browse           = 'Story List';
$lang->product->requirement      = 'Requirement List';
$lang->product->dynamic          = 'Dynamics';
$lang->product->view             = "{$lang->productCommon} Detail";
$lang->product->edit             = "Edit {$lang->productCommon}";
$lang->product->batchEdit        = 'Batch Edit';
$lang->product->create           = "Create {$lang->productCommon}";
$lang->product->delete           = "Delete {$lang->productCommon}";
$lang->product->deleted          = 'Deleted';
$lang->product->close            = "Close";
$lang->product->activate         = 'Activate';
$lang->product->select           = "Select {$lang->productCommon}";
$lang->product->mine             = 'Mine';
$lang->product->other            = 'Others';
$lang->product->closed           = 'Closed';
$lang->product->closedProduct    = "Closed {$lang->productCommon}";
$lang->product->updateOrder      = 'Order';
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
$lang->product->unbindWhitelist  = 'Unbind Whitelist';
$lang->product->track            = 'View Stories Matrix';
$lang->product->checkedProducts  = "%s {$lang->productCommon}s selected";
$lang->product->pageSummary      = "Total {$lang->productCommon}s: %s.";
$lang->product->lineSummary      = "Total product lines: %s, Total {$lang->productCommon}s: %s.";

$lang->product->indexAction    = "All {$lang->productCommon}";
$lang->product->closeAction    = "Close {$lang->productCommon}";
$lang->product->activateAction = "Activate {$lang->productCommon}";
$lang->product->orderAction    = "Sort {$lang->productCommon}";
$lang->product->exportAction   = "Export {$lang->productCommon}";
$lang->product->link2Project   = "Link {$lang->projectCommon}";

$lang->product->basicInfo = 'Basic Info';
$lang->product->otherInfo = 'Other Info';

$lang->product->plans       = 'Plans';
$lang->product->releases    = 'Releases';
$lang->product->docs        = 'Doc';
$lang->product->bugs        = 'Linked Bug';
$lang->product->projects    = "Linked {$lang->projectCommon}";
$lang->product->executions  = "Linked {$lang->execution->common}";
$lang->product->cases       = 'Case';
$lang->product->builds      = 'Build';
$lang->product->roadmap     = "{$lang->productCommon} Roadmap";
$lang->product->doc         = "{$lang->productCommon} Documents";
$lang->product->project     = $lang->projectCommon . ' List';
$lang->product->moreProduct = "More {$lang->productCommon}";
$lang->product->projectInfo = "My {$lang->projectCommon}s that are linked to this {$lang->productCommon} are listed below.";
$lang->product->progress    = "Progress";

$lang->product->currentExecution      = "Current Execution";
$lang->product->activeStories         = 'Active [S]';
$lang->product->activeStoriesTitle    = 'Active Stories';
$lang->product->changedStories        = 'Changed [S]';
$lang->product->changedStoriesTitle   = 'Changed Stories';
$lang->product->draftStories          = 'Draft [S]';
$lang->product->draftStoriesTitle     = 'Draft Stories';
$lang->product->reviewingStories      = "Reviewing [S]";
$lang->product->reviewingStoriesTitle = "Reviewing Stories";
$lang->product->closedStories         = 'Closed [S]';
$lang->product->closedStoriesTitle    = 'Closed Stories';
$lang->product->storyCompleteRate     = "{$lang->SRCommon} Completion rate";
$lang->product->activeRequirements    = "Active {$lang->URCommon}";
$lang->product->changedRequirements   = "Changed {$lang->URCommon}";
$lang->product->draftRequirements     = "Draft {$lang->URCommon}";
$lang->product->closedRequirements    = "Closed {$lang->URCommon}";
$lang->product->requireCompleteRate   = "{$lang->URCommon} Completion rate";
$lang->product->unResolvedBugs        = 'Active [B]';
$lang->product->unResolvedBugsTitle   = 'Active Bugs';
$lang->product->assignToNullBugs      = 'Unassigned [B]';
$lang->product->assignToNullBugsTitle = 'Unassigned Bugs';
$lang->product->closedBugs            = 'Closed Bug';
$lang->product->bugFixedRate          = 'Repair Rate';
$lang->product->unfoldClosed          = 'Unfold Closed';
$lang->product->storyDeliveryRate     = "Story Delivery Rate";
$lang->product->storyDeliveryRateTip  = "Story Delivery Rate = The released or done stories / (Total stories - Closed reason is not done）* 100%";

$lang->product->confirmDelete        = " Do you want to delete the {$lang->productCommon}?";
$lang->product->errorNoProduct       = "No {$lang->productCommon} is created yet!";
$lang->product->accessDenied         = "You have no access to the {$lang->productCommon}.";
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
$lang->product->name           = "{$lang->productCommon} Name";
$lang->product->code           = 'Code';
$lang->product->shadow         = "Shadow {$lang->productCommon}";
$lang->product->line           = "Product Line";
$lang->product->lineName       = "Product Line Name";
$lang->product->order          = 'Rank';
$lang->product->bind           = 'In/Depedent';
$lang->product->type           = 'Type';
$lang->product->typeAB         = 'Type';
$lang->product->status         = 'Status';
$lang->product->subStatus      = 'Sub Status';
$lang->product->desc           = 'Description';
$lang->product->manager        = 'Managers';
$lang->product->PO             = "{$lang->productCommon} Owner";
$lang->product->QD             = 'QA Manager';
$lang->product->RD             = 'Release Manager';
$lang->product->feedback       = 'Feedback Manger';
$lang->product->ticket         = 'Ticket Manager';
$lang->product->acl            = 'Access Control';
$lang->product->reviewer       = 'Reviewer';
$lang->product->groups         = 'Groups';
$lang->product->users          = 'Users';
$lang->product->whitelist      = 'Whitelist';
$lang->product->branch         = '%s';
$lang->product->qa             = 'Test';
$lang->product->release        = 'Release';
$lang->product->allRelease     = 'All Releases';
$lang->product->maintain       = 'Maintenance';
$lang->product->latestDynamic  = 'Dynamics';
$lang->product->plan           = 'Plan';
$lang->product->iteration      = 'Iterations';
$lang->product->iterationInfo  = '%s Iteration';
$lang->product->iterationView  = 'More';
$lang->product->createdBy      = 'CreatedBy';
$lang->product->createdDate    = 'createdDate';
$lang->product->createdVersion = 'Created Version';
$lang->product->mailto         = 'Mailto';

$lang->product->searchStory    = 'Search';
$lang->product->assignedToMe   = 'AssignedToMe';
$lang->product->openedByMe     = 'CreatedByMe';
$lang->product->reviewedByMe   = 'ReviewedByMe';
$lang->product->reviewByMe     = 'ReviewByMe';
$lang->product->closedByMe     = 'ClosedByMe';
$lang->product->draftStory     = 'Draft';
$lang->product->activeStory    = 'Activated';
$lang->product->changingStory  = 'Changing';
$lang->product->reviewingStory = 'Reviewing';
$lang->product->willClose      = 'ToBeClosed';
$lang->product->closedStory    = 'Closed';
$lang->product->unclosed       = 'Open';
$lang->product->unplan         = 'Unplanned';
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

$lang->product->allStory             = 'All ';
$lang->product->allProduct           = 'All';
$lang->product->allProductsOfProject = 'All Linked ' . $lang->productCommon;

$lang->product->typeList['']         = '';
$lang->product->typeList['normal']   = 'Normal';
$lang->product->typeList['branch']   = 'Multi-Branch';
$lang->product->typeList['platform'] = 'Multi-Platform';

$lang->product->typeTips = array();
$lang->product->typeTips['branch']   = ' (for the customized context. e.g. outsourcing teams)';
$lang->product->typeTips['platform'] = ' (for cross-platform applications, e.g. IOS, Android, PC, etc.)';

$lang->product->branchName['normal']   = '';
$lang->product->branchName['branch']   = 'Branch';
$lang->product->branchName['platform'] = 'Platform';

$lang->product->statusList['normal'] = 'Normal';
$lang->product->statusList['closed'] = 'Closed';

global $config;
if($config->systemMode == 'ALM')
{
    $lang->product->aclList['private'] = "Private {$lang->productCommon} (Manager and Stakeholders of the respective program, team members and stakeholders of the associated {$lang->projectCommon} can access)";
}
else
{
    $lang->product->aclList['private'] = "Private {$lang->productCommon} (Team members and stakeholders of the associated {$lang->projectCommon} can access)";
}
$lang->product->aclList['open']    = "Default (Users with privileges to {$lang->productCommon} can access it.)";

$lang->product->abbr = new stdclass();
$lang->product->abbr->aclList['private'] = "Private {$lang->productCommon}";
$lang->product->abbr->aclList['open']    = 'Default';

$lang->product->aclTips['open']    = "Users with privileges to {$lang->productCommon} can access it.";
$lang->product->aclTips['private'] = "{$lang->executionCommon} team members only";

$lang->product->storySummary       = "Total <strong>%s</strong> %s on this page. Estimates: <strong>%s</strong> ({$lang->hourCommon}), and Case Coverage: <strong>%s</strong>.";
$lang->product->checkedSRSummary   = "<strong>%total%</strong> {$lang->SRCommon} selected, Esitmates: <strong>%estimate%</strong> ({$lang->hourCommon}), and Case Coverage: <strong>%rate%</strong>.";
$lang->product->requirementSummary = "Total <strong>%s</strong> %s on this page. Estimates: <strong>%s</strong> ({$lang->hourCommon}).";
$lang->product->checkedURSummary   = "<strong>%total%</strong> {$lang->URCommon} selected, Esitmates: <strong>%estimate%</strong> ({$lang->hourCommon}).";
$lang->product->noModule           = '<div>You have no modules. </div><div>Manage Now</div>';
$lang->product->noProduct          = "No {$lang->productCommon} yet. ";
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

$lang->product->featureBar['project']['all']       = 'All';
$lang->product->featureBar['project']['undone']    = 'Unfinished';
$lang->product->featureBar['project']['wait']      = 'Waiting';
$lang->product->featureBar['project']['doing']     = 'Doing';
$lang->product->featureBar['project']['suspended'] = 'Suspended';
$lang->product->featureBar['project']['closed']    = 'Closed';

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
