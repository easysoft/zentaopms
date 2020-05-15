<?php
$lang->repo->common          = 'Repo';
$lang->repo->browse          = 'View';
$lang->repo->viewRevision    = 'View Revision';
$lang->repo->create          = 'Create';
$lang->repo->createAction    = 'Create Repo';
$lang->repo->maintain        = 'Repo List';
$lang->repo->edit            = 'Edit';
$lang->repo->editAction      = 'Edit Repo';
$lang->repo->delete          = 'Delete Repo';
$lang->repo->showSyncCommit  = 'Display Sync';
$lang->repo->ajaxSyncCommit  = 'Interface: Ajax Sync Note';
$lang->repo->setRules        = 'Set Rules';
$lang->repo->download        = 'Download File';
$lang->repo->downloadDiff    = 'Download Diff';
$lang->repo->diffAction      = 'Revision Diff';
$lang->repo->revisionAction  = 'Revision Detail';
$lang->repo->blameAction     = 'Repo Blame';
$lang->repo->addBug          = 'Add Review';
$lang->repo->editBug         = 'Edit Bug';
$lang->repo->deleteBug       = 'Delete Bug';
$lang->repo->addComment      = 'Add Comment';
$lang->repo->editComment     = 'Edit Comment';
$lang->repo->deleteComment   = 'Delete Comment';

$lang->repo->submit     = 'Submit';
$lang->repo->cancel     = 'Cancel';
$lang->repo->addComment = 'Add Comment';

$lang->repo->product  = $lang->productCommon;
$lang->repo->module   = 'Module';
$lang->repo->project  = $lang->projectCommon;
$lang->repo->type     = 'Type';
$lang->repo->assign   = 'AssignTo';
$lang->repo->title    = 'Title';
$lang->repo->detile   = 'Detail';
$lang->repo->lines    = 'Lines';
$lang->repo->line     = 'Line';
$lang->repo->expand   = 'Unfold';
$lang->repo->collapse = 'Fold';

$lang->repo->id        = 'ID';
$lang->repo->SCM       = 'Type';
$lang->repo->name      = 'Name';
$lang->repo->path      = 'Path';
$lang->repo->prefix    = 'Prefix';
$lang->repo->config    = 'Config';
$lang->repo->desc      = 'Description';
$lang->repo->account   = 'Username';
$lang->repo->password  = 'Password';
$lang->repo->encoding  = 'Encoding';
$lang->repo->client    = 'Client Path';
$lang->repo->size      = 'Size';
$lang->repo->revision  = 'Revision';
$lang->repo->revisionA = 'Revision';
$lang->repo->revisions = 'Revision';
$lang->repo->time      = 'Date';
$lang->repo->committer = 'Committer';
$lang->repo->commits   = 'Commits';
$lang->repo->synced    = 'Initialize Sync';
$lang->repo->lastSync  = 'Last Sync';
$lang->repo->deleted   = 'Deleted';
$lang->repo->commit    = 'Commit';
$lang->repo->comment   = 'Comment';
$lang->repo->view      = 'View File';
$lang->repo->viewA     = 'View';
$lang->repo->log       = 'Revision Log';
$lang->repo->blame     = 'Blame';
$lang->repo->date      = 'Date';
$lang->repo->diff      = 'Diff';
$lang->repo->diffAB    = 'Diff';
$lang->repo->diffAll   = 'Diff All';
$lang->repo->viewDiff  = 'View diff';
$lang->repo->allLog    = 'All Revisions';
$lang->repo->location  = 'Location';
$lang->repo->file      = 'File';
$lang->repo->action    = 'Action';
$lang->repo->code      = 'Code';
$lang->repo->review    = 'Repo Review';
$lang->repo->acl       = 'Privilege';
$lang->repo->group     = 'Group';
$lang->repo->user      = 'User';
$lang->repo->info      = 'Version Info';

$lang->repo->title      = 'Title';
$lang->repo->status     = 'Status';
$lang->repo->openedBy   = 'CreatedBy';
$lang->repo->assignedTo = 'AssignedTo';
$lang->repo->openedDate = 'CreatedDate';

$lang->repo->latestRevision = 'Latest Revision';
$lang->repo->actionInfo     = "Add by %s in %s";
$lang->repo->changes        = "Change Log";
$lang->repo->reviewLocation = "File: %s@%s, Line: %s - %s";
$lang->repo->commentEdit    = '<i class="icon-pencil"></i>';
$lang->repo->commentDelete  = '<i class="icon-remove"></i>';
$lang->repo->allChanges     = "Other Changes";
$lang->repo->commitTitle    = "The %sth Commit";
$lang->repo->mark           = "Mark Tag";
$lang->repo->split          = "Split Mark";

$lang->repo->objectRule   = 'Object Rule';
$lang->repo->objectIdRule = 'Object ID Rule';
$lang->repo->actionRule   = 'Action Rule';
$lang->repo->manHourRule  = 'Man-hour Rule';
$lang->repo->ruleUnit     = "Unit";
$lang->repo->ruleSplit    = "Multiple keywords are divided by ';', e.g. task multiple keywords: Task;task";

$lang->repo->viewDiffList['inline'] = 'Inline';
$lang->repo->viewDiffList['appose'] = 'Parallel';

$lang->repo->encryptList['plain']  = 'No encryption';
$lang->repo->encryptList['base64'] = 'BASE64';

$lang->repo->logStyles['A'] = 'Add';
$lang->repo->logStyles['M'] = 'Modification';
$lang->repo->logStyles['D'] = 'Delete';

$lang->repo->encodingList['utf_8'] = 'UTF-8';
$lang->repo->encodingList['gbk']   = 'GBK';

$lang->repo->scmList['Git']        = 'Git';
$lang->repo->scmList['Subversion'] = 'SVN';

$lang->repo->notice                 = new stdclass();
$lang->repo->notice->syncing        = 'Synchronizing. Please wait ...';
$lang->repo->notice->syncComplete   = 'Synchronized. Now redirecting ...';
$lang->repo->notice->syncedCount    = 'The number of records synchronized is ';
$lang->repo->notice->delete         = 'Do you want to delete this repo?';
$lang->repo->notice->successDelete  = 'Repository is removed.';
$lang->repo->notice->commentContent = 'Comment';
$lang->repo->notice->deleteBug      = 'Do you want to delete this bug?';
$lang->repo->notice->deleteComment  = 'Do you want to delete this comment?';
$lang->repo->notice->lastSyncTime   = 'Last Sync:';

$lang->repo->rules = new stdclass();
$lang->repo->rules->exampleLabel = "Comment Example";
$lang->repo->rules->example['task']['start']  = "%start% %task% %id%1%split%2 %cost%%consumedmark%1%cunit% %left%%leftmark%3%lunit%";
$lang->repo->rules->example['task']['finish'] = "%finish% %task% %id%1%split%2 %cost%%consumedmark%10%cunit%";
$lang->repo->rules->example['task']['effort'] = "%effort% %task% %id%1%split%2 %cost%%consumedmark%1%cunit% %left%%leftmark%3%lunit%";
$lang->repo->rules->example['bug']['resolve'] = "%resolve% %bug% %id%1%split%2";

$lang->repo->error                = new stdclass();
$lang->repo->error->useless       = 'Your server disabled exec and shell_exec, so it cannot be applied.';
$lang->repo->error->connect       = 'Connection to the repo failed. Please enter username, password and repo address correctly!';
$lang->repo->error->version       = 'Version 1.8+ of https and svn protocol is required. Please update to latest version! Go to http://subversion.apache.org/';
$lang->repo->error->path          = 'Repo address is the file path, e.g. /home/test.';
$lang->repo->error->cmd           = 'Client Error!';
$lang->repo->error->diff          = 'Two versions must be selected.';
$lang->repo->error->product       = "Please select {$lang->productCommon}!";
$lang->repo->error->commentText   = 'Please enter content for review!';
$lang->repo->error->comment       = 'Please enter content!';
$lang->repo->error->title         = 'Please enter title!';
$lang->repo->error->accessDenied  = 'You do not have the privilege to access the repository.';
$lang->repo->error->noFound       = 'The repo is not found.';
$lang->repo->error->noFile        = '%s does not exist.';
$lang->repo->error->noPriv        = 'The program does not have the privilege  to switch to %s';
$lang->repo->error->output        = "The command is: %s\nThe error is(%s): %s\n";
$lang->repo->error->clientVersion = "Client version is too low, please upgrade or change SVN client";
$lang->repo->error->encoding      = "The encoding might be wrong. Please change the encoding and try again.";

$lang->repo->syncTips      = '<strong>You may find the reference about how to set Git sync from <a target="_blank" href="https://www.zentao.pm/book/zentaomanual/free-open-source-project-management-software-git-105.html">here</a>.</strong>';
$lang->repo->encodingsTips = "The encodings of comments can be comma separated values, e.g. utf-8.";

$lang->repo->example              = new stdclass();
$lang->repo->example->client      = new stdclass();
$lang->repo->example->path        = new stdclass();
$lang->repo->example->client->git = "e.g. /usr/bin/git";
$lang->repo->example->client->svn = "e.g. /usr/bin/svn";
$lang->repo->example->path->git   = "e.g. /home/user/myproject";
$lang->repo->example->path->svn   = "e.g. http://example.googlecode.com/svn/trunk/myproject";
$lang->repo->example->config      = "Config directory is required in https. Use '--config-dir' to generate config dir.";
$lang->repo->example->encoding    = "input encoding of files";

$lang->repo->typeList['standard']    = 'Standard';
$lang->repo->typeList['performance'] = 'Performance';
$lang->repo->typeList['security']    = 'Security';
$lang->repo->typeList['redundancy']  = 'Redundancy';
$lang->repo->typeList['logicError']  = 'Logic Error';
