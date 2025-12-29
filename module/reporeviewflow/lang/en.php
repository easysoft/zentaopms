<?php
$lang->reporeviewflow->browse       = 'Browse Review Flow';
$lang->reporeviewflow->create       = 'Create Review Flow';
$lang->reporeviewflow->edit         = 'Edit Review Flow';
$lang->reporeviewflow->changeStatus = 'Enable/Disable Review Flow';
$lang->reporeviewflow->delete       = 'Delete Review Flow';

$lang->reporeviewflow->name                  = 'Name';
$lang->reporeviewflow->desc                  = 'Description';
$lang->reporeviewflow->flowName              = 'Flow Name';
$lang->reporeviewflow->branchType            = 'Branch Type';
$lang->reporeviewflow->enableFlow            = 'Enable';
$lang->reporeviewflow->disableFlow           = 'Disable';
$lang->reporeviewflow->basicInfo             = 'Basic Information';
$lang->reporeviewflow->applicableBranchTypes = 'Target Branch Types';
$lang->reporeviewflow->allBranchTypes        = 'All Branch Types';
$lang->reporeviewflow->aiReview              = 'AI Review';
$lang->reporeviewflow->aiAssistedReview      = 'AI Assisted Review';
$lang->reporeviewflow->aiReviewScores        = 'Can Pass AI Review with Minimum Score';
$lang->reporeviewflow->manualReview          = 'Manual Review';
$lang->reporeviewflow->defaultReviewers      = 'Default Reviewers';
$lang->reporeviewflow->specifiedReviewers    = 'Reviewers Must Contain Specified Members';
$lang->reporeviewflow->minReviewers          = 'Minimum Reviewers';
$lang->reporeviewflow->solveIssues           = 'Solve Issues';
$lang->reporeviewflow->addressOption         = 'How to address issues';
$lang->reporeviewflow->newCommits            = 'How to address new commits';
$lang->reporeviewflow->mergeStrategy         = 'Merge Strategy';
$lang->reporeviewflow->mergeOptions          = 'Merge Options';
$lang->reporeviewflow->autoArchive           = 'Auto Archive';
$lang->reporeviewflow->autoArchiveNotice     = 'Only when the branch archive is enabled, it can be merged into the source branch';
$lang->reporeviewflow->allBranchTypesNotice  = 'All Branch Type review flow is exists';
$lang->reporeviewflow->enableSuccess         = 'The review flow enable Success';
$lang->reporeviewflow->disableSuccess        = 'The review flow disable Success';
$lang->reporeviewflow->aiScoreTips           = 'Code with an AI score above this threshold passes the AI review.';
$lang->reporeviewflow->status                = 'Status';

$lang->reporeviewflow->flowStatusList = array();
$lang->reporeviewflow->flowStatusList['enable']  = 'Enable';
$lang->reporeviewflow->flowStatusList['disable'] = 'Disable';

$lang->reporeviewflow->aiReviewList = array();
$lang->reporeviewflow->aiReviewList['enable']  = 'Enable';
$lang->reporeviewflow->aiReviewList['disable'] = 'Disable';

$lang->reporeviewflow->addressOptionList = array();
$lang->reporeviewflow->addressOptionList['noNeedToSolve']        = 'No Need To Solve';
$lang->reporeviewflow->addressOptionList['allMustBeSolved']      = 'All Must Be Solved';
$lang->reporeviewflow->addressOptionList['specificMustBeSolved'] = 'Specific Type Must Be Solved';

$lang->reporeviewflow->newCommitsAddressOptionList = array();
$lang->reporeviewflow->newCommitsAddressOptionList['defaultApproval'] = 'Default Approval';
$lang->reporeviewflow->newCommitsAddressOptionList['requireReReview'] = 'Require Re-Review';

$lang->reporeviewflow->mergeOptionList = array();
$lang->reporeviewflow->mergeOptionList['merge']  = 'Merge';
$lang->reporeviewflow->mergeOptionList['squash'] = 'Squash';
$lang->reporeviewflow->mergeOptionList['rebase'] = 'Rebase';
$lang->reporeviewflow->mergeOptionList['fast']   = 'Fast-Forward';

$lang->reporeviewflow->autoArchiveStatusList = array();
$lang->reporeviewflow->autoArchiveStatusList['enable']  = 'Enable';
$lang->reporeviewflow->autoArchiveStatusList['disable'] = 'Disable';

$lang->reporeviewflow->notice = new stdclass();
$lang->reporeviewflow->notice->deleteReviewFlow = "Do you want to delete '%s' review flow?";
