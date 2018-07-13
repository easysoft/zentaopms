<?php
/**
 * The action module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     action
 * @version     $Id: en.php 4729 2013-05-03 07:53:55Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->action->common     = 'Log';
$lang->action->product    = $lang->productCommon;
$lang->action->project    = $lang->projectCommon;
$lang->action->objectType = 'Object Type';
$lang->action->objectID   = 'ID';
$lang->action->objectName = 'Object Name';
$lang->action->actor      = 'Handler';
$lang->action->action     = 'Action';
$lang->action->actionID   = 'Action ID';
$lang->action->date       = 'Date';

$lang->action->trash       = 'Recycle';
$lang->action->undelete    = 'Restore';
$lang->action->hideOne     = 'Hide';
$lang->action->hideAll     = 'Hide All';
$lang->action->editComment = 'Edit';
$lang->action->create      = 'Add Comment';
$lang->action->comment     = 'Comment';

$lang->action->trashTips      = 'Note: All Deletion in ZenTao are logical.';
$lang->action->textDiff       = 'Text Format';
$lang->action->original       = 'Original Format';
$lang->action->confirmHideAll = 'Do you want to hide all the records?';
$lang->action->needEdit       = '%s that you want to restore exists. Please edit it.';
$lang->action->historyEdit    = 'The history editor cannot be empty.';
$lang->action->noDynamic      = 'No dynamics. ';

$lang->action->history = new stdclass();
$lang->action->history->action = 'Link';
$lang->action->history->field  = 'Field';
$lang->action->history->old    = 'Old value';
$lang->action->history->new    = 'New value';
$lang->action->history->diff   = 'Diff';

$lang->action->dynamic = new stdclass();
$lang->action->dynamic->today      = 'Today';
$lang->action->dynamic->yesterday  = 'Yesterday';
$lang->action->dynamic->twoDaysAgo = '2 Days Ago';
$lang->action->dynamic->thisWeek   = 'This Week';
$lang->action->dynamic->lastWeek   = 'Last Week';
$lang->action->dynamic->thisMonth  = 'This Month';
$lang->action->dynamic->lastMonth  = 'Last Month';
$lang->action->dynamic->all        = 'All';
$lang->action->dynamic->hidden     = 'Hidden';
$lang->action->dynamic->search     = 'Search';

$lang->action->periods['all']       = $lang->action->dynamic->all;
$lang->action->periods['today']     = $lang->action->dynamic->today;
$lang->action->periods['yesterday'] = $lang->action->dynamic->yesterday;
$lang->action->periods['thisweek']  = $lang->action->dynamic->thisWeek;
$lang->action->periods['lastweek']  = $lang->action->dynamic->lastWeek;
$lang->action->periods['thismonth'] = $lang->action->dynamic->thisMonth;
$lang->action->periods['lastmonth'] = $lang->action->dynamic->lastMonth;

$lang->action->objectTypes['product']     = $lang->productCommon;
$lang->action->objectTypes['story']       = 'Story';
$lang->action->objectTypes['productplan'] = 'Plan';
$lang->action->objectTypes['release']     = 'Release';
$lang->action->objectTypes['project']     = $lang->projectCommon;
$lang->action->objectTypes['task']        = 'Task';
$lang->action->objectTypes['build']       = 'Build';
$lang->action->objectTypes['bug']         = 'Bug';
$lang->action->objectTypes['case']        = 'Case';
$lang->action->objectTypes['caseresult']  = 'Case Result';
$lang->action->objectTypes['stepresult']  = 'Case Steps';
$lang->action->objectTypes['testtask']    = 'Test Build';
$lang->action->objectTypes['user']        = 'User';
$lang->action->objectTypes['doc']         = 'Doc';
$lang->action->objectTypes['doclib']      = 'Doc Lib';
$lang->action->objectTypes['todo']        = 'Todo';
$lang->action->objectTypes['branch']      = 'Branch';
$lang->action->objectTypes['module']      = 'Module';
$lang->action->objectTypes['testsuite']   = 'Suite';
$lang->action->objectTypes['caselib']     = 'Library';
$lang->action->objectTypes['testreport']  = 'Report';
$lang->action->objectTypes['entry']       = 'Entry';
$lang->action->objectTypes['webhook']     = 'Webhook';

/* 用来描述操作历史记录。*/
$lang->action->desc = new stdclass();
$lang->action->desc->common         = '$date, <strong>$action</strong> by <strong>$actor</strong>.' . "\n";
$lang->action->desc->extra          = '$date, <strong>$action</strong> as <strong>$extra</strong> by <strong>$actor</strong>.' . "\n";
$lang->action->desc->opened         = '$date, created by <strong>$actor</strong> .' . "\n";
$lang->action->desc->created        = '$date, created by  <strong>$actor</strong> .' . "\n";
$lang->action->desc->changed        = '$date, changed by <strong>$actor</strong> .' . "\n";
$lang->action->desc->edited         = '$date, edited by <strong>$actor</strong> .' . "\n";
$lang->action->desc->assigned       = '$date, <strong>$actor</strong> assigned to <strong>$extra</strong>.' . "\n";
$lang->action->desc->closed         = '$date, closed by <strong>$actor</strong> .' . "\n";
$lang->action->desc->deleted        = '$date, deleted by <strong>$actor</strong> .' . "\n";
$lang->action->desc->deletedfile    = '$date, <strong>$actor</strong> deleted <strong><i>$extra</i></strong>.' . "\n";
$lang->action->desc->editfile       = '$date, <strong>$actor</strong> edited <strong><i>$extra</i></strong>.' . "\n";
$lang->action->desc->erased         = '$date, deleted by <strong>$actor</strong> .' . "\n";
$lang->action->desc->undeleted      = '$date, restored by <strong>$actor</strong> .' . "\n";
$lang->action->desc->hidden         = '$date, hidden by <strong>$actor</strong> .' . "\n";
$lang->action->desc->commented      = '$date, added by <strong>$actor</strong>.' . "\n";
$lang->action->desc->activated      = '$date, activated by <strong>$actor</strong> .' . "\n";
$lang->action->desc->blocked        = '$date, blocked by <strong>$actor</strong> .' . "\n";
$lang->action->desc->moved          = '$date, moved by <strong>$actor</strong> , which was "$extra".' . "\n";
$lang->action->desc->confirmed      = '$date, <strong>$actor</strong> confirmed the story change. The latest build is <strong>#$extra</strong>.' . "\n";
$lang->action->desc->caseconfirmed  = '$date, <strong>$actor</strong> confirmed the case change. The latest build is <strong>#$extra</strong>' . "\n";
$lang->action->desc->bugconfirmed   = '$date, <strong>$actor</strong> confirmed Bug.' . "\n";
$lang->action->desc->frombug        = '$date, converted from <strong>$actor</strong>. Its ID was <strong>$extra</strong>.';
$lang->action->desc->started        = '$date, started by <strong>$actor</strong>.' . "\n";
$lang->action->desc->restarted      = '$date, continued by <strong>$actor</strong>.' . "\n";
$lang->action->desc->delayed        = '$date, postponed by <strong>$actor</strong>.' . "\n";
$lang->action->desc->suspended      = '$date, suspended by <strong>$actor</strong>.' . "\n";
$lang->action->desc->recordestimate = '$date, recorded by <strong>$actor</strong> and it consumed <strong>$extra</strong> hours.';
$lang->action->desc->editestimate   = '$date, <strong>$actor</strong> edited Hour.';
$lang->action->desc->deleteestimate = '$date, <strong>$actor</strong> deleted Hour.';
$lang->action->desc->canceled       = '$date, cancelled by <strong>$actor</strong>.' . "\n";
$lang->action->desc->svncommited    = '$date, <strong>$actor</strong> committed and the build is <strong>#$extra</strong>.' . "\n";
$lang->action->desc->gitcommited    = '$date, <strong>$actor</strong> committed and the build is <strong>#$extra</strong>.' . "\n";
$lang->action->desc->finished       = '$date, finished by <strong>$actor</strong>.' . "\n";
$lang->action->desc->paused         = '$date, paused by <strong>$actor</strong>.' . "\n";
$lang->action->desc->verified       = '$date, verified by <strong>$actor</strong>.' . "\n";
$lang->action->desc->diff1          = '<strong><i>%s</i></strong> has been changed. It was "%s" and it is "%s".<br />' . "\n";
$lang->action->desc->diff2          = '<strong><i>%s</i></strong> has been changed. The difference is ' . "\n" . "<blockquote class='textdiff'>%s</blockquote>" . "\n<blockquote class='original'>%s</blockquote>";
$lang->action->desc->diff3          = 'File Name %s was changed to %s .' . "\n";
$lang->action->desc->linked2bug     = '$date Linked to <strong>$extra</strong> by <strong>$actor</strong>';

/* 关联用例和移除用例时的历史操作记录。*/
$lang->action->desc->linkrelatedcase   = '$date, <strong>$actor</strong> linked relevant use case <strong>$extra</strong>.' . "\n";
$lang->action->desc->unlinkrelatedcase = '$date, <strong>$actor</strong> unlinked relevant use case <strong>$extra</strong>.' . "\n";

/* 用来显示动态信息。*/
$lang->action->label = new stdclass();
$lang->action->label->created             = 'created ';
$lang->action->label->opened              = 'opened ';
$lang->action->label->changed             = 'changed ';
$lang->action->label->edited              = 'edited ';
$lang->action->label->assigned            = 'assigned ';
$lang->action->label->closed              = 'closed ';
$lang->action->label->deleted             = 'deleted ';
$lang->action->label->deletedfile         = 'deleted ';
$lang->action->label->editfile            = 'edit ';
$lang->action->label->erased              = 'erased ';
$lang->action->label->undeleted           = 'restored ';
$lang->action->label->hidden              = 'hid ';
$lang->action->label->commented           = 'commented ';
$lang->action->label->activated           = 'activated ';
$lang->action->label->blocked             = 'blocked ';
$lang->action->label->resolved            = 'resolved ';
$lang->action->label->reviewed            = 'reviewed ';
$lang->action->label->moved               = 'moved ';
$lang->action->label->confirmed           = 'Confirm Story';
$lang->action->label->bugconfirmed        = 'Confirmed';
$lang->action->label->tostory             = 'Convert to Story';
$lang->action->label->frombug             = 'Converted from Bug';
$lang->action->label->fromlib             = 'Import from library';
$lang->action->label->totask              = 'Convert to Task';
$lang->action->label->svncommited         = 'SVN Commit';
$lang->action->label->gitcommited         = 'Git Commit';
$lang->action->label->linked2plan         = 'Link to Plan';
$lang->action->label->unlinkedfromplan    = 'Unlink';
$lang->action->label->changestatus        = 'Change Status';
$lang->action->label->marked              = 'marked';
$lang->action->label->linked2project      = "Link {$lang->projectCommon}";
$lang->action->label->unlinkedfromproject = "Unlink {$lang->projectCommon}";
$lang->action->label->unlinkedfrombuild   = "Unlink Build";
$lang->action->label->linked2release      = "Link Release";
$lang->action->label->unlinkedfromrelease = "Unlink Release";
$lang->action->label->linkrelatedbug      = "Link to Bug";
$lang->action->label->unlinkrelatedbug    = "Unlink";
$lang->action->label->linkrelatedcase     = "Link to Case";
$lang->action->label->unlinkrelatedcase   = "Unlink";
$lang->action->label->linkrelatedstory    = "Link to Story";
$lang->action->label->unlinkrelatedstory  = "Unlink";
$lang->action->label->subdividestory      = "Decompose Story";
$lang->action->label->unlinkchildstory    = "Unlink";
$lang->action->label->started             = 'started ';
$lang->action->label->restarted           = 'continued ';
$lang->action->label->recordestimate      = 'recorded ';
$lang->action->label->editestimate        = 'edited ';
$lang->action->label->canceled            = 'cancelled ';
$lang->action->label->finished            = 'finished ';
$lang->action->label->paused              = 'paused ';
$lang->action->label->verified            = 'verified ';
$lang->action->label->delayed             = 'delayed ';
$lang->action->label->suspended           = 'suspended ';
$lang->action->label->login               = 'Login';
$lang->action->label->logout              = "Logout";
$lang->action->label->deleteestimate      = "deleted ";
$lang->action->label->linked2build        = "linked ";
$lang->action->label->linked2bug          = "linked ";

/* 用来生成相应对象的链接。*/
$lang->action->label->product     = $lang->productCommon . '|product|view|productID=%s';
$lang->action->label->productplan = 'Plan|productplan|view|productID=%s';
$lang->action->label->release     = 'Release|release|view|productID=%s';
$lang->action->label->story       = 'Story|story|view|storyID=%s';
$lang->action->label->project     = "{$lang->projectCommon}|project|view|projectID=%s";
$lang->action->label->task        = 'Task|task|view|taskID=%s';
$lang->action->label->build       = 'Build|build|view|buildID=%s';
$lang->action->label->bug         = 'Bug|bug|view|bugID=%s';
$lang->action->label->case        = 'Case|testcase|view|caseID=%s';
$lang->action->label->testtask    = 'Test Task|testtask|view|caseID=%s';
$lang->action->label->testsuite   = 'Test Suite|testsuite|view|suiteID=%s';
$lang->action->label->caselib     = 'Case Library|testsuite|libview|libID=%s';
$lang->action->label->todo        = 'Todo|todo|view|todoID=%s';
$lang->action->label->doclib      = 'Doc Lib|doc|browse|libID=%s';
$lang->action->label->doc         = 'Doc|doc|view|docID=%s';
$lang->action->label->user        = 'User|user|view|account=%s';
$lang->action->label->testreport  = 'Report|testreport|view|report=%s';
$lang->action->label->entry       = 'Entry|entry|browse|';
$lang->action->label->webhook     = 'Webhook|webhook|browse|';
$lang->action->label->space       = ' ';

/* Object type. */
$lang->action->search->objectTypeList['']            = '';    
$lang->action->search->objectTypeList['product']     = $lang->productCommon;
$lang->action->search->objectTypeList['project']     = $lang->projectCommon;
$lang->action->search->objectTypeList['bug']         = 'Bug';
$lang->action->search->objectTypeList['case']        = 'Case'; 
$lang->action->search->objectTypeList['caseresult']  = 'Case Results';
$lang->action->search->objectTypeList['stepresult']  = 'Case Steps';
$lang->action->search->objectTypeList['story']       = 'Story';  
$lang->action->search->objectTypeList['task']        = 'Task'; 
$lang->action->search->objectTypeList['testtask']    = 'Test Task';     
$lang->action->search->objectTypeList['user']        = 'User'; 
$lang->action->search->objectTypeList['doc']         = 'Doc';
$lang->action->search->objectTypeList['doclib']      = 'Doc Lib';
$lang->action->search->objectTypeList['todo']        = 'Todo';
$lang->action->search->objectTypeList['build']       = 'Build';
$lang->action->search->objectTypeList['release']     = 'Release';
$lang->action->search->objectTypeList['productplan'] = 'Plan';
$lang->action->search->objectTypeList['branch']      = 'Branch';
$lang->action->search->objectTypeList['testsuite']   = 'Suite';
$lang->action->search->objectTypeList['caselib']     = 'Library';
$lang->action->search->objectTypeList['testreport']  = 'Report';

/* 用来在动态显示中显示动作 */
$lang->action->search->label['']                    = '';
$lang->action->search->label['created']             = $lang->action->label->created;
$lang->action->search->label['opened']              = $lang->action->label->opened;
$lang->action->search->label['changed']             = $lang->action->label->changed;
$lang->action->search->label['edited']              = $lang->action->label->edited;
$lang->action->search->label['assigned']            = $lang->action->label->assigned;
$lang->action->search->label['closed']              = $lang->action->label->closed;
$lang->action->search->label['deleted']             = $lang->action->label->deleted;
$lang->action->search->label['deletedfile']         = $lang->action->label->deletedfile;
$lang->action->search->label['editfile']            = $lang->action->label->editfile;
$lang->action->search->label['erased']              = $lang->action->label->erased;
$lang->action->search->label['undeleted']           = $lang->action->label->undeleted;
$lang->action->search->label['hidden']              = $lang->action->label->hidden;
$lang->action->search->label['commented']           = $lang->action->label->commented;
$lang->action->search->label['activated']           = $lang->action->label->activated;
$lang->action->search->label['blocked']             = $lang->action->label->blocked;
$lang->action->search->label['resolved']            = $lang->action->label->resolved;
$lang->action->search->label['reviewed']            = $lang->action->label->reviewed;
$lang->action->search->label['moved']               = $lang->action->label->moved;
$lang->action->search->label['confirmed']           = $lang->action->label->confirmed;
$lang->action->search->label['bugconfirmed']        = $lang->action->label->bugconfirmed;
$lang->action->search->label['tostory']             = $lang->action->label->tostory;
$lang->action->search->label['frombug']             = $lang->action->label->frombug;
$lang->action->search->label['totask']              = $lang->action->label->totask;
$lang->action->search->label['svncommited']         = $lang->action->label->svncommited;
$lang->action->search->label['gitcommited']         = $lang->action->label->gitcommited;
$lang->action->search->label['linked2plan']         = $lang->action->label->linked2plan;
$lang->action->search->label['unlinkedfromplan']    = $lang->action->label->unlinkedfromplan;
$lang->action->search->label['changestatus']        = $lang->action->label->changestatus;
$lang->action->search->label['marked']              = $lang->action->label->marked;
$lang->action->search->label['linked2project']      = $lang->action->label->linked2project;
$lang->action->search->label['unlinkedfromproject'] = $lang->action->label->unlinkedfromproject;
$lang->action->search->label['started']             = $lang->action->label->started;
$lang->action->search->label['restarted']           = $lang->action->label->restarted;
$lang->action->search->label['recordestimate']      = $lang->action->label->recordestimate;
$lang->action->search->label['editestimate']        = $lang->action->label->editestimate;
$lang->action->search->label['canceled']            = $lang->action->label->canceled;
$lang->action->search->label['finished']            = $lang->action->label->finished;
$lang->action->search->label['paused']              = $lang->action->label->paused;
$lang->action->search->label['verified']            = $lang->action->label->verified;
$lang->action->search->label['login']               = $lang->action->label->login;
$lang->action->search->label['logout']              = $lang->action->label->logout;
