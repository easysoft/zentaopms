<?php
global $config;

$lang->repo->common          = 'Repo';
$lang->repo->codeRepo        = 'Code Library';
$lang->repo->browse          = 'View';
$lang->repo->viewRevision    = 'View Revision';
$lang->repo->product         = $lang->productCommon;
$lang->repo->projects        = $lang->projectCommon;
$lang->repo->execution       = $lang->execution->common;
$lang->repo->create          = 'Create';
$lang->repo->maintain        = 'Repo List';
$lang->repo->edit            = 'Edit';
$lang->repo->delete          = 'Delete Repo';
$lang->repo->showSyncCommit  = 'Display Sync';
$lang->repo->ajaxSyncCommit  = 'Interface: Ajax Sync Note';
$lang->repo->setRules        = 'Set Rules';
$lang->repo->download        = 'Download File';
$lang->repo->downloadDiff    = 'Download Diff';
$lang->repo->addBug          = 'Add Review';
$lang->repo->editBug         = 'Edit Bug';
$lang->repo->deleteBug       = 'Delete Bug';
$lang->repo->addComment      = 'Add Comment';
$lang->repo->editComment     = 'Edit Comment';
$lang->repo->deleteComment   = 'Delete Comment';
$lang->repo->encrypt         = 'Encrypt';
$lang->repo->repo            = 'Repository';
$lang->repo->parent          = 'Parent File';
$lang->repo->branch          = 'Branch';
$lang->repo->tag             = 'Tag';
$lang->repo->addWebHook      = 'Add Webhook';
$lang->repo->apiGetRepoByUrl = 'API: Get repo by URL';
$lang->repo->blameTmpl       = 'Code for line <strong>%line</strong>: %name commited at %time, %version %comment';
$lang->repo->notRelated      = 'There is currently no related ZenTao object';
$lang->repo->source          = 'Criterion';
$lang->repo->target          = 'Contrast';
$lang->repo->descPlaceholder = 'One sentence description';
$lang->repo->namespace       = 'Namespace';
$lang->repo->branchName      = 'Branch Name';
$lang->repo->branchFrom      = 'Create from';

$lang->repo->createBranchAction = 'Create Branch';
$lang->repo->browseAction       = 'Browse Repo';
$lang->repo->createAction       = 'Link Repo';
$lang->repo->editAction         = 'Edit Repo';
$lang->repo->diffAction         = 'Compare Code';
$lang->repo->downloadAction     = 'Download File';
$lang->repo->revisionAction     = 'Revision Detail';
$lang->repo->blameAction        = 'Blame';
$lang->repo->reviewAction       = 'Review List';
$lang->repo->downloadCode       = 'Download Code';
$lang->repo->downloadZip        = 'Download Zip file';
$lang->repo->sshClone           = 'Clone with SSH';
$lang->repo->httpClone          = 'Clone with HTTP';
$lang->repo->cloneUrl           = 'Clone URL';
$lang->repo->linkTask           = 'Link Task';
$lang->repo->unlinkedTasks      = 'Unlinked Tasks';
$lang->repo->importAction       = 'Import Repo';
$lang->repo->import             = 'Import';
$lang->repo->importName         = 'Name after import';
$lang->repo->importServer       = 'Please select a server';
$lang->repo->gitlabList         = 'Gitlab Repo';
$lang->repo->batchCreate        = 'Batch link repo';

$lang->repo->createRepoAction = 'Create repository';

$lang->repo->submit     = 'Submit';
$lang->repo->cancel     = 'Cancel';
$lang->repo->addComment = 'Add Comment';
$lang->repo->addIssue   = 'Add Issue';
$lang->repo->compare    = 'Compare';

$lang->repo->copy     = 'Click to copy';
$lang->repo->copied   = 'Copy successful';
$lang->repo->module   = 'Module';
$lang->repo->type     = 'Type';
$lang->repo->assign   = 'AssignTo';
$lang->repo->title    = 'Title';
$lang->repo->detile   = 'Detail';
$lang->repo->lines    = 'Lines';
$lang->repo->line     = 'Line';
$lang->repo->expand   = 'Unfold';
$lang->repo->collapse = 'Fold';

$lang->repo->id                 = 'ID';
$lang->repo->SCM                = 'Type';
$lang->repo->name               = 'Name';
$lang->repo->path               = 'Path';
$lang->repo->prefix             = 'Prefix';
$lang->repo->config             = 'Config';
$lang->repo->desc               = 'Description';
$lang->repo->account            = 'Username';
$lang->repo->password           = 'Password';
$lang->repo->encoding           = 'Encoding';
$lang->repo->client             = 'Client Path';
$lang->repo->size               = 'Size';
$lang->repo->revision           = 'Revision';
$lang->repo->revisionA          = 'Revision';
$lang->repo->revisions          = 'Revision';
$lang->repo->time               = 'Date';
$lang->repo->committer          = 'Committer';
$lang->repo->commits            = 'Commits';
$lang->repo->synced             = 'Initialize Sync';
$lang->repo->lastSync           = 'Last Sync';
$lang->repo->deleted            = 'Deleted';
$lang->repo->commit             = 'Commit';
$lang->repo->comment            = 'Comment';
$lang->repo->view               = 'View File';
$lang->repo->viewA              = 'View';
$lang->repo->log                = 'Revision Log';
$lang->repo->blame              = 'Blame';
$lang->repo->date               = 'Date';
$lang->repo->diff               = 'Diff';
$lang->repo->diffAB             = 'Diff';
$lang->repo->diffAll            = 'Diff All';
$lang->repo->viewDiff           = 'View diff';
$lang->repo->allLog             = 'All Commits';
$lang->repo->location           = 'Location';
$lang->repo->file               = 'File';
$lang->repo->action             = 'Action';
$lang->repo->code               = 'Code';
$lang->repo->review             = 'Repo Review';
$lang->repo->acl                = 'ACL';
$lang->repo->group              = 'Group';
$lang->repo->user               = 'User';
$lang->repo->info               = 'Version Info';
$lang->repo->job                = 'Job';
$lang->repo->fileServerUrl      = 'File Server Url';
$lang->repo->fileServerAccount  = 'File Server Account';
$lang->repo->fileServerPassword = 'File Server Password';
$lang->repo->linkStory          = 'Link ' . $lang->SRCommon;
$lang->repo->linkBug            = 'Link Bug';
$lang->repo->linkTask           = 'Link Task';
$lang->repo->unlink             = 'Unlink';
$lang->repo->viewBugs           = 'View Bugs';
$lang->repo->lastSubmitTime     = 'Final submission time';

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

$lang->repo->scmList['Gitlab']     = 'GitLab';
$lang->repo->scmList['Gogs']       = 'Gogs';
if(!$config->inQuickon) $lang->repo->scmList['Gitea']      = 'Gitea';
$lang->repo->scmList['Git']        = 'Git';
$lang->repo->scmList['Subversion'] = 'SVN';

$lang->repo->aclList['private'] = 'Private(The product and related project personnel can access it)';
$lang->repo->aclList['open']    = 'Open(Users with privileges to DevOps can access it)';
$lang->repo->aclList['custom']  = 'Custom';

$lang->repo->gitlabHost    = 'GitLab Host';
$lang->repo->gitlabToken   = 'GitLab Token';
$lang->repo->gitlabProject = 'Project';

$lang->repo->serviceHost    = 'Host';
$lang->repo->serviceProject = 'Project';

$lang->repo->placeholder = new stdclass;
$lang->repo->placeholder->gitlabHost = 'Input url of gitlab';

$lang->repo->notice                 = new stdclass();
$lang->repo->notice->syncing        = 'Synchronizing. Please wait ...';
$lang->repo->notice->syncComplete   = 'Synchronized. Now redirecting ...';
$lang->repo->notice->syncFailed     = 'Synchronized failed.';
$lang->repo->notice->syncedCount    = 'The number of records synchronized is ';
$lang->repo->notice->delete         = 'Do you want to delete this repo?';
$lang->repo->notice->successDelete  = 'Repository is removed.';
$lang->repo->notice->commentContent = 'Comment';
$lang->repo->notice->deleteReview   = 'Do you want to delete this review?';
$lang->repo->notice->deleteBug      = 'Do you want to delete this bug?';
$lang->repo->notice->deleteComment  = 'Do you want to delete this comment?';
$lang->repo->notice->lastSyncTime   = 'Last Sync:';

$lang->repo->rules = new stdclass();
$lang->repo->rules->exampleLabel = "Comment Example";
$lang->repo->rules->example['task']['start']  = "%start% %task% %id%1%split%2 %cost%%consumedmark%1%cunit% %left%%leftmark%3%lunit%";
$lang->repo->rules->example['task']['finish'] = "%finish% %task% %id%1%split%2 %cost%%consumedmark%10%cunit%";
$lang->repo->rules->example['task']['effort'] = "%effort% %task% %id%1%split%2 %cost%%consumedmark%1%cunit% %left%%leftmark%3%lunit%";
$lang->repo->rules->example['bug']['resolve'] = "%resolve% %bug% %id%1%split%2";

$lang->repo->error = new stdclass();
$lang->repo->error->useless           = 'Your server disabled exec and shell_exec, so it cannot be applied.';
$lang->repo->error->connect           = 'Connection to the repo failed. Please enter username, password and repo address correctly!';
$lang->repo->error->version           = 'Version 1.8+ of https and svn protocol is required. Please update to latest version! Go to http://subversion.apache.org/';
$lang->repo->error->path              = 'Repo address is the file path, e.g. /home/test.';
$lang->repo->error->cmd               = 'Client Error!';
$lang->repo->error->diff              = 'Two versions must be selected.';
$lang->repo->error->safe              = "For security reasons, the client version needs to be detected. Please write the version to the file %s. \n Execute command: %s";
$lang->repo->error->product           = "Please select {$lang->productCommon}!";
$lang->repo->error->commentText       = 'Please enter content for review!';
$lang->repo->error->comment           = 'Please enter content!';
$lang->repo->error->title             = 'Please enter title!';
$lang->repo->error->accessDenied      = 'You do not have the privilege to access the repository.';
$lang->repo->error->noFound           = 'The repo is not found.';
$lang->repo->error->noFile            = '%s does not exist.';
$lang->repo->error->noPriv            = 'The program does not have the privilege  to switch to %s';
$lang->repo->error->output            = "The command is: %s\nThe error is(%s): %s\n";
$lang->repo->error->clientVersion     = "Client version is too low, please upgrade or change SVN client";
$lang->repo->error->encoding          = "The encoding might be wrong. Please change the encoding and try again.";
$lang->repo->error->deleted           = "Deletion of the repository failed. The current repository has a commit record associated with the design.";
$lang->repo->error->linkedJob         = "Deletion of the repository failed. The current repository has associated with the Compile.";
$lang->repo->error->clientPath        = "The client installation directory cannot have spaces!";
$lang->repo->error->notFound          = "The repository %s’s URL %s does not exist. Please confirm if this repository has been deleted from the local server.";
$lang->repo->error->noWritable        = '%s is not writable! Please check the privilege, or download will not be done.';
$lang->repo->error->noCloneAddr       = 'The repository clone address was not found';
$lang->repo->error->differentVersions = 'The criterion and contrast cannot be the same';
$lang->repo->error->needTwoVersion    = 'Two branches or tags must be selected.';
$lang->repo->error->emptyVersion      = 'Version cannot be empty';
$lang->repo->error->versionError      = 'Wrong version format!';
$lang->repo->error->projectUnique     = $lang->repo->serviceProject . " exists. Go to Admin->System->Data->Recycle Bin to restore it, if you are sure it is deleted.";
$lang->repo->error->repoNameInvalid   = 'The name should contain only alphanumeric numbers, dashes, underscores, and dots.';
$lang->repo->error->createdFail       = 'Create failed';
$lang->repo->error->noProduct         = 'Please associate the product corresponding to the project before starting to associate the code repository.';

$lang->repo->syncTips          = '<strong>You may find the reference about how to set Git sync from <a target="_blank" href="https://www.zentao.pm/book/zentaomanual/free-open-source-project-management-software-git-105.html">here</a>.</strong>';
$lang->repo->encodingsTips     = "The encodings of comments can be comma separated values, e.g. utf-8.";
$lang->repo->pathTipsForGitlab = "GitLab Project URL";

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

$lang->repo->featureBar['maintain']['all'] = 'All';
