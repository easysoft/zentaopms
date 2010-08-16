<?php
/**
 * The story module English file of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *                                                                             
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentaoms.com
 */
$lang->story->browse    = "Browse";
$lang->story->create    = "Create";
$lang->story->change    = "Change";
$lang->story->changed   = 'Changed';
$lang->story->review    = 'Review';
$lang->story->edit      = "Edit";
$lang->story->close     = 'Close';
$lang->story->activate  = 'Activate';
$lang->story->delete    = "Delete";
$lang->story->view      = "Info";
$lang->story->tasks     = "Tasks";
$lang->story->taskCount = 'Tasks count';
$lang->story->bugs      = "Bug";
$lang->story->linkStory = 'Related story';

$lang->story->common         = 'Story';
$lang->story->id             = 'ID';
$lang->story->product        = 'Product';
$lang->story->module         = 'Module';
$lang->story->release        = 'Release';
$lang->story->bug            = 'Related Bug';
$lang->story->title          = 'Title';
$lang->story->spec           = 'Spec';
$lang->story->type           = 'Type ';
$lang->story->pri            = 'Priority';
$lang->story->estimate       = 'Estimate';
$lang->story->estimateAB     = 'Estimate';
$lang->story->status         = 'Status';
$lang->story->stage          = 'Stage';
$lang->story->stageAB        = 'Stage';
$lang->story->mailto         = 'Mailto';
$lang->story->openedBy       = 'Opened by';
$lang->story->openedDate     = 'Opened date';
$lang->story->assignedTo     = 'Assigned to';
$lang->story->assignedDate   = 'Assigned date';
$lang->story->lastEditedBy   = 'Last edited by';
$lang->story->lastEditedDate = 'Last edited date';
$lang->story->lastEdited     = 'Last edited';
$lang->story->closedBy       = 'Closed by';
$lang->story->closedDate     = 'Closed date';
$lang->story->closedReason   = 'Closed reason';
$lang->story->rejectedReason = 'Reject reason';
$lang->story->reviewedBy     = 'Reviewed by';
$lang->story->reviewedDate   = 'Reviewed date';
$lang->story->version        = 'Version';
$lang->story->project        = 'Project';
$lang->story->plan           = 'Plan';
$lang->story->planAB         = 'Plan';
$lang->story->comment        = 'Comment';
$lang->story->linkStories    = 'Related story';
$lang->story->childStories   = 'Child story';
$lang->story->duplicateStory = 'Duplicate story';
$lang->story->reviewResult   = 'Reviewed result';
$lang->story->preVersion     = 'Pre version';
$lang->story->keywords       = 'Keyword';

$lang->story->statusList['']          = '';
$lang->story->statusList['draft']     = 'Draft';
$lang->story->statusList['active']    = 'Active';
$lang->story->statusList['closed']    = 'Closed';
$lang->story->statusList['changed']   = 'Changed';

$lang->story->stageList['']           = '';
$lang->story->stageList['wait']       = 'Waitting';
$lang->story->stageList['planned']    = 'Planned';
$lang->story->stageList['projected']  = 'Projected';
$lang->story->stageList['developing'] = 'Developing';
$lang->story->stageList['developed']  = 'Developed';
$lang->story->stageList['testing']    = 'Testing';
$lang->story->stageList['tested']     = 'Tested';
$lang->story->stageList['verified']   = 'Verified';
$lang->story->stageList['released']   = 'Released';

$lang->story->reasonList['']           = '';
$lang->story->reasonList['done']       = 'Done';
$lang->story->reasonList['subdivided'] = 'Subdivided';
$lang->story->reasonList['duplicate']  = 'Duplicate';
$lang->story->reasonList['postponed']  = 'Postponed';
$lang->story->reasonList['willnotdo']  = "Won't do";
$lang->story->reasonList['cancel']     = 'Canceled';
$lang->story->reasonList['bydesign']   = 'By design';
//$lang->story->reasonList['isbug']      = '是个Bug';

$lang->story->reviewResultList['']       = '';
$lang->story->reviewResultList['pass']   = 'Pass';
$lang->story->reviewResultList['revert'] = 'Revert';
$lang->story->reviewResultList['clarify']= 'Clarify';
$lang->story->reviewResultList['reject'] = 'Reject';

$lang->story->priList[]   = '';
$lang->story->priList[3]  = '3';
$lang->story->priList[1]  = '1';
$lang->story->priList[2]  = '2';
$lang->story->priList[4]  = '4';

$lang->story->legendBasicInfo      = 'Basic info';
$lang->story->legendLifeTime       = 'Life time';
$lang->story->legendRelated        = 'Related info';
$lang->story->legendMailto         = 'Maitto';
$lang->story->legendAttatch        = 'Files';
$lang->story->legendProjectAndTask = 'Project & task';
$lang->story->legendLinkStories    = 'Related story';
$lang->story->legendChildStories   = 'Child story';
$lang->story->legendSpec           = 'Spec';
$lang->story->legendHistory        = 'History';
$lang->story->legendVersion        = 'Versions';
$lang->story->legendMisc           = 'Misc';

$lang->story->lblChange            = 'Change';
$lang->story->lblReview            = 'Review';
$lang->story->lblActivate          = 'Activate';
$lang->story->lblClose             = 'Close';

$lang->story->affectedProjects     = 'Affected projects';
$lang->story->affectedBugs         = 'Affected bugs';
$lang->story->affectedCases        = 'Affected cases';

$lang->story->specTemplate          = "Recommend template:：As <<i class='red'>a type of user</i>>,I want <<i class='red'>some goals</i>>,so that <<i class='red'>some reason</i>>.";
$lang->story->needNotReview         = "needn't review";
$lang->story->confirmDelete         = "Are you sure to delete this story?";
$lang->story->errorFormat           = 'Error format';
$lang->story->errorEmptyTitle       = "Title can't be empty";
$lang->story->mustChooseResult      = 'Must choose s result';
$lang->story->mustChoosePreVersion  = 'Must select an version to revert';
$lang->story->ajaxGetProjectStories = 'API:Project stories';
$lang->story->ajaxGetProductStories = 'API:Product stories';

$lang->story->action->reviewed            = array('main' => '$date, Reviewed by <strong>$actor</strong>, result is <strong>$extra</strong>.', 'extra' => $lang->story->reviewResultList);
$lang->story->action->closed              = array('main' => '$date, Closed by <strong>$actor</strong>, reason is <strong>$extra</strong>.', 'extra' => $lang->story->reasonList);
$lang->story->action->linked2plan         = array('main' => '$date, Linked to plan <strong>$extra</strong> by <strong>$actor</strong>.'); 
$lang->story->action->unlinkedfromplan    = array('main' => '$date, Removed from <stong>$extra></strong> by <strong>$actor</strong>'); 
$lang->story->action->linked2project      = array('main' => '$date, Linked to project <strong>$extra</strong> by <strong>$actor</strong>.'); 
$lang->story->action->unlinkedfromproject = array('main' => '$date, Removed from project <strontg>$extra</strong> by <strong>$actor</strong>.'); 
