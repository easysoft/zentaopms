<?php
global $config;

$lang->repo->common          = 'Repo';
$lang->repo->repo            = 'Repo';
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

$lang->repo->mirror = new stdclass();
$lang->repo->mirror->syncing             = 'Syncing...';
$lang->repo->mirror->refreshSync         = 'Refresh Sync Status';
$lang->repo->mirror->failedTitle         = 'Sync Failed';
$lang->repo->mirror->detail              = 'View Detail';
$lang->repo->mirror->syncCode            = 'Sync Repository';
$lang->repo->mirror->syncTriggered       = 'Sync job triggered';
$lang->repo->mirror->syncFailed          = 'Sync failed';
$lang->repo->mirror->syncRequestFailed   = 'Sync request failed';
$lang->repo->mirror->queryFailed         = 'Query failed';
$lang->repo->mirror->queryRequestFailed  = 'Query request failed';
$lang->repo->mirror->statusUpdated       = 'Sync status updated';
$lang->repo->mirror->stillRunning        = 'Still syncing...';
$lang->repo->mirror->done                = 'Sync finished';
$lang->repo->mirror->failureTitle        = 'Sync Failure Detail';
$lang->repo->mirror->noDetail            = 'No detail';

$lang->repo->downloadDiff    = 'Download Diff';
$lang->repo->addBug          = 'Add Review';
$lang->repo->editBug         = 'Edit Bug';
$lang->repo->deleteBug       = 'Delete Bug';
$lang->repo->addComment      = 'Add Comment';
$lang->repo->editComment     = 'Edit Comment';
$lang->repo->deleteComment   = 'Delete Comment';
$lang->repo->encrypt         = 'Encrypt';
$lang->repo->addWebHook      = 'Add Webhook';
$lang->repo->apiGetRepoByUrl = 'API: Get repo by URL';
$lang->repo->blameTmpl       = 'Code for line <strong>%line</strong>: %name commited at %time, %version %comment';
$lang->repo->notRelated      = 'There is currently no related ZenTao object';
$lang->repo->source          = 'Criterion';
$lang->repo->target          = 'Contrast';
$lang->repo->descPlaceholder = 'One sentence description';
$lang->repo->namespace       = 'Namespace';
$lang->repo->branchName      = 'Branch Name';
$lang->repo->branchFrom      = 'Create From';
$lang->repo->codeBranch      = 'Code branch';
$lang->repo->createdBranch   = 'Created branch';
$lang->repo->unlink          = 'Unlink';
$lang->repo->visit           = 'Visit';
$lang->repo->space           = 'Space';
$lang->repo->allSpace        = 'All Spaces';
$lang->repo->members         = 'Members';
$lang->repo->sshManager      = 'SSH Key Manager';
$lang->repo->defaultArtifact = 'Default';
$lang->repo->origin          = 'Origin';
$lang->repo->originRepo      = 'Origin Repo';
$lang->repo->provider        = 'Server';
$lang->repo->providerID      = 'Server';
$lang->repo->organize        = 'Organization';
$lang->repo->targetRepo      = 'Target Repo';
$lang->repo->afterImport     = 'After Import';
$lang->repo->repoPath        = 'Repo Path';
$lang->repo->slug            = 'Repo Path';
$lang->repo->tips            = 'Tips';

$lang->repo->createBranchAction = 'Create Branch';
$lang->repo->createTagAction    = 'Create Tag';
$lang->repo->browseAction       = 'Browse Repo';
$lang->repo->createAction       = 'Import Repo';
$lang->repo->editAction         = 'Edit Repo';
$lang->repo->diffAction         = 'Compare Code';
$lang->repo->downloadAction     = 'Download File';
$lang->repo->revisionAction     = 'Revision Detail';
$lang->repo->blameAction        = 'Blame';
$lang->repo->reviewAction       = 'Code Issue List';
$lang->repo->downloadCode       = 'Download Code';
$lang->repo->downloadZip        = 'Download Package';
$lang->repo->sshClone           = 'Clone with SSH';
$lang->repo->httpClone          = 'Clone with HTTP';
$lang->repo->cloneUrl           = 'Clone URL';
$lang->repo->linkTask           = 'Link Task';
$lang->repo->unlinkedTasks      = 'Unlinked Tasks';
$lang->repo->importAction       = 'Import Repo';
$lang->repo->import             = 'Import Repo';
$lang->repo->importName         = 'Name after import';
$lang->repo->importServer       = 'Please select a server';
$lang->repo->hide               = 'hide';
$lang->repo->show               = 'show';
$lang->repo->showHidden         = 'Show hidden repositories';
$lang->repo->gitlabList         = 'Gitlab Repo';
$lang->repo->batchCreate        = 'Batch import repo';
$lang->repo->browseTag          = 'Browse Tag';
$lang->repo->browseBranch       = 'Browse Branch';
$lang->repo->showImportProgress = 'Show Import Progress';
$lang->repo->showImportResult   = 'Show Import Result';

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
$lang->repo->identifier         = 'Name';
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
$lang->repo->commitList         = 'View Commit List';
$lang->repo->blame              = 'Blame';
$lang->repo->date               = 'Date';
$lang->repo->diff               = 'Diff';
$lang->repo->diffAB             = 'Diff';
$lang->repo->diffAll            = 'Diff All';
$lang->repo->viewDiff           = 'View diff';
$lang->repo->allLog             = 'Commits';
$lang->repo->codeLocation       = 'Code Location';
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
$lang->repo->lastCommitter      = 'Committer';
$lang->repo->lastUpdateTime     = 'Last update time';
$lang->repo->createdBy          = 'Creator';
$lang->repo->sourceCommit       = 'Commit';
$lang->repo->relations          = 'Relations';
$lang->repo->story              = 'story';
$lang->repo->searchTips         = 'Search by %s';
$lang->repo->design             = 'Design';
$lang->repo->bug                = 'Bug';
$lang->repo->task               = 'Task';

$lang->repo->title      = 'Title';
$lang->repo->status     = 'Status';
$lang->repo->openedBy   = 'Created by';
$lang->repo->assignedTo = 'Assigned to';
$lang->repo->openedDate = 'Created Date';

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

$lang->repo->scmList['Gitlab'] = 'GitLab';
if(!$config->inQuickon && !$config->inCompose)
{
    $lang->repo->scmList['Gitea']      = 'Gitea';
    $lang->repo->scmList['Gogs']       = 'Gogs';
    $lang->repo->scmList['Git']        = 'Git';
    $lang->repo->scmList['Subversion'] = 'SVN';
}

$lang->repo->aclList['open']    = 'Open (Anyone with access to the space the repository belongs to can access the repository)';
$lang->repo->aclList['private'] = 'Private (Only repository members can access the repository)';

$lang->repo->showAclList['open']    = 'Open';
$lang->repo->showAclList['private'] = 'Private';

$lang->repo->gitlabHost    = 'GitLab Host';
$lang->repo->gitlabToken   = 'GitLab Token';
$lang->repo->gitlabProject = 'Project';

$lang->repo->serviceHost    = 'Host';
$lang->repo->serviceProject = 'Project';

$lang->repo->placeholder = new stdclass;
$lang->repo->placeholder->gitlabHost = 'Input url of gitlab';

$lang->repo->notice                   = new stdclass();
$lang->repo->notice->syncing          = 'Synchronizing. Please wait ...';
$lang->repo->notice->syncComplete     = 'Synchronized. Now redirecting ...';
$lang->repo->notice->syncFailed       = 'Synchronized failed.';
$lang->repo->notice->syncedCount      = 'The number of records synchronized is ';
$lang->repo->notice->delete           = 'Do you want to unlink the code library?';
$lang->repo->notice->deleteConfirm    = 'Do you want to delete the library? This operation will permanently remove the library and all content and history records, and cannot be recovered.';
$lang->repo->notice->successDelete    = 'Successfully disassociated code repository.';
$lang->repo->notice->commentContent   = 'Comment';
$lang->repo->notice->deleteReview     = 'Do you want to delete this review?';
$lang->repo->notice->deleteBug        = 'Do you want to delete this bug?';
$lang->repo->notice->deleteComment    = 'Do you want to delete this comment?';
$lang->repo->notice->lastSyncTime     = 'Last Sync:';
$lang->repo->notice->unlinkBranch     = 'Are you sure to disassociate the branch from %s?';
$lang->repo->notice->noRepoLeft       = 'All repositories has been associated to ZenTaoPMS, please choose another server.';
$lang->repo->notice->noChanges        = 'No Changes';
$lang->repo->notice->storyNotActive   = 'Story is not active, cannot create branch.';
$lang->repo->notice->taskNotActive    = 'Task is not waiting or doing, cannot create branch.';
$lang->repo->notice->bugNotActive     = 'Bug is not active, cannot create branch.';

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
$lang->repo->error->empty             = 'Repo is empty, cannot sync logs.';
$lang->repo->error->noFile            = '%s does not exist or has no permission.';
$lang->repo->error->noPriv            = 'The program does not have the privilege  to switch to %s';
$lang->repo->error->output            = "The command is: %s\nThe error is(%s): %s\n";
$lang->repo->error->clientVersion     = "Client version is too low, please upgrade or change SVN client";
$lang->repo->error->encoding          = "The encoding might be wrong. Please change the encoding and try again.";
$lang->repo->error->deleted           = "Release failed, submission record associated with design, design numbers ( %s ).<br/>";
$lang->repo->error->linkedBranch      = "Release failed, code repository associated with branch, branch types are ( %s ) branches are ( %s ).<br/>";
$lang->repo->error->linkedJob         = "Release failed, code library is associated with pipeline, pipeline numbers are ( %s ).<br/>";
$lang->repo->error->linkedArtifact    = "Release failed, code library is associated with artifact repo, artifact repo numbers are ( %s ).<br/>";
$lang->repo->error->clientPath        = "The client installation directory cannot have spaces!";
$lang->repo->error->notFound          = "The repository %s’s URL %s does not exist. Please confirm if this repository has been deleted from the local server.";
$lang->repo->error->noWritable        = '%s is not writable! Please check the privilege, or download will not be done.';
$lang->repo->error->noCloneAddr       = 'The repository clone address was not found';
$lang->repo->error->differentVersions = 'The criterion and contrast cannot be the same';
$lang->repo->error->needTwoVersion    = 'Two branches or tags must be selected.';
$lang->repo->error->projectUnique     = $lang->repo->serviceProject . " exists. Go to Admin->System->Data->Recycle Bin to restore it, if you are sure it is deleted.";
$lang->repo->error->repoNameInvalid   = 'The name should contain only alphanumeric numbers, dashes, connector, and dots.';
$lang->repo->error->createdFail       = 'Create failed';
$lang->repo->error->branchNameTooLong = 'Branch name cannot exceed 30 characters';
$lang->repo->error->noProduct         = 'Please associate the product before starting to export the code repository.';
$lang->repo->error->emptyVersion      = 'Version cannot be empty';
$lang->repo->error->versionError      = 'Wrong version format!';

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

$lang->repo->errorLang[0] = "Can contain only letters, digits, '_', '-' and '.'. Cannot start with '-', end in '.git' or end in '.atom'";
$lang->repo->errorLang[1] = 'Branch is exists';
$lang->repo->errorLang[2] = 'Branch already exists';
$lang->repo->errorLang[3] = 'Forbidden';
$lang->repo->errorLang[4] = 'Cannot have ASCII control characters';
$lang->repo->errorLang[5] = 'Created fail';
$lang->repo->errorLang[6] = 'Forbidden';

$lang->repo->apiError[0] = "can contain only letters, digits, '_', '-' and '.'. Cannot start with '-', end in '.git' or end in '.atom'";
$lang->repo->apiError[1] = 'Branch is exists';
$lang->repo->apiError[2] = 'branch.* already exists';
$lang->repo->apiError[3] = 'Forbidden';
$lang->repo->apiError[4] = 'cannot have ASCII control characters';
$lang->repo->apiError[5] = 'Created fail';
$lang->repo->apiError[6] = 'Project Not Found';

$lang->repo->branchType            = 'Branch Type';
$lang->repo->applicableBranchTypes = 'Applicable Branch Types';
$lang->repo->allBranchTypes        = 'All Branch Types';

$lang->repo->branchRuleMode = array();
$lang->repo->branchRuleMode['inheritance']  = 'Inheritance';
$lang->repo->branchRuleMode['redefinition'] = 'Redefinition';

$lang->repo->branchTypeRule = new stdClass();
$lang->repo->branchTypeRule->allowCreatedBy     = 'Allowed create users';
$lang->repo->branchTypeRule->allowDeletedBy     = 'Allowed delete users';
$lang->repo->branchTypeRule->allowUpdatedBy     = 'Allowed update users';
$lang->repo->branchTypeRule->allowForcePushedBy = 'Allowed force push users';
$lang->repo->branchTypeRule->allowMergeFrom     = 'Allowed merge sources';
$lang->repo->branchTypeRule->allowMergeTo       = 'Allowed merge targets';

$lang->repo->branchTypeRule->userOptionList = array();
$lang->repo->branchTypeRule->userOptionList['hasPriv'] = 'Users with permission';
$lang->repo->branchTypeRule->userOptionList['specify'] = 'Specified users only';

$lang->repo->branchTypeRule->branchTypeOptionList = array();
$lang->repo->branchTypeRule->branchTypeOptionList['all']     = 'All branches';
$lang->repo->branchTypeRule->branchTypeOptionList['specify'] = 'Specified branch types';

$lang->repo->branchRule = new stdClass();
$lang->repo->branchRule->allowDeletedBy     = 'Allowed delete users';
$lang->repo->branchRule->allowUpdatedBy     = 'Allowed update users';
$lang->repo->branchRule->allowForcePushedBy = 'Allowed force push users';
$lang->repo->branchRule->allowMergeFrom     = 'Allowed merge sources';
$lang->repo->branchRule->allowMergeTo       = 'Allowed merge targets';
$lang->repo->branchRule->delete             = 'Delete branch rule';
$lang->repo->branchRule->mode               = 'Rule control';

$lang->repo->branchRule->userOptionList = array();
$lang->repo->branchRule->userOptionList['hasPriv'] = 'Users with permission';
$lang->repo->branchRule->userOptionList['specify'] = 'Specified users only';

$lang->repo->branchRule->branchTypeOptionList = array();
$lang->repo->branchRule->branchTypeOptionList['all']     = 'All branches';
$lang->repo->branchRule->branchTypeOptionList['specify'] = 'Specified branch types';

$lang->repo->select            = 'Please choose...';
$lang->repo->searchPlaceholder = 'Filter by Git revision';
$lang->repo->svnPlaceholder    = 'Please enter the version';
$lang->repo->changeFile        = 'Change files';

$lang->repo->commitInfo   = 'Code Modification Details';
$lang->repo->linkedStory  = "Linked Stories";
$lang->repo->linkedTask   = "Linked Tasks";
$lang->repo->linkedBug    = "Linked Bugs";
$lang->repo->commited     = "Commited";
$lang->repo->commentary   = "Comment";
$lang->repo->issueTitle   = "Issue Title";
$lang->repo->issueDesc    = "Issue Detail";
$lang->repo->dateTmpl     = "Proposed at %s";
$lang->repo->commentNum   = " Comments";

$lang->repo->fileTotal  = '%d files';
$lang->repo->codeSurvey = 'Changed: <span class="add-cot">%d lines</span> of code added, <span class="delete-cot">%d lines</span> of code deleted';

$lang->repo->featureBar['review']['all']          = 'All';
$lang->repo->featureBar['review']['assigntome']   = 'AssignedToMe';
$lang->repo->featureBar['review']['openedbyme']   = 'OpenedByMe';
$lang->repo->featureBar['review']['resolvedbyme'] = 'ResolvedByMe';
$lang->repo->featureBar['review']['assigntonull'] = 'Unassigned';
$lang->repo->featureBar['review']['unresolved']   = 'Active';
$lang->repo->featureBar['review']['unclosed']     = 'Unclosed';

$lang->repo->browseSystem = 'Application List';

$lang->repo->system = new stdclass();
$lang->repo->system->product       = 'Product';
$lang->repo->system->name          = 'Application Name';
$lang->repo->system->latestRelease = 'Latest Version';
$lang->repo->system->deployStatus  = 'Latest Version Status';
$lang->repo->system->status        = 'Application Status';

$lang->repo->remark              = "Message";
$lang->repo->codeTag             = 'Code Tags';
$lang->repo->tagName             = 'Tag Name';
$lang->repo->tagFrom             = 'Crated from';
$lang->repo->createTag           = 'Create Tag';
$lang->repo->deleteTag           = 'Delete Tag';
$lang->repo->confirmTagDelete    = 'Are you sure to delete this tag?';
$lang->repo->createBranch        = 'Create Branch';
$lang->repo->deleteBranch        = 'Delete Branch';
$lang->repo->confirmBranchDelete = 'Are you sure to delete this branch?';
$lang->repo->deleteDefaultBranch = 'The default branch does not allow deletion';
$lang->repo->divergence          = 'Behind|Ahead';
$lang->repo->ahead               = 'Ahead';
$lang->repo->behind              = 'Behind';
$lang->repo->noDivergence        = 'No Divergence';
$lang->repo->noDivergenceOnHint  = 'No Divergence on %s';
$lang->repo->divergenceOnBranch  = 'On %s ';
$lang->repo->aheadHint           = 'Ahead %s times';
$lang->repo->behindHint          = 'Behind %s times';
$lang->repo->default             = 'Default';
$lang->repo->defaultBranch       = 'Default Branch';
$lang->repo->committerTip        = 'Committer has write permission on code repository';
$lang->repo->commitDetail        = '%s committed at %s by %s';
$lang->repo->hasNoProduct        = 'The project or execution does not have a product';

$lang->repo->browseWebhooks     = 'Webhook List';
$lang->repo->createWebhook      = 'Create Webhook';
$lang->repo->editWebhook        = 'Edit Webhook';
$lang->repo->logWebhook         = 'Webhook Log';
$lang->repo->viewWebhookRequest = 'Request Data';
$lang->repo->deleteWebhook      = 'Delete Webhook';
$lang->repo->targetURL          = 'Target URL';
$lang->repo->latestStatus       = 'Latest Status';
$lang->repo->enable             = 'Enable';
$lang->repo->disable            = 'Disable';
$lang->repo->enableWebhook      = 'Enable/Disable Webhook';
$lang->repo->deleteWebhook      = 'Delete Webhook';

$lang->repo->webhook = new stdclass();
$lang->repo->webhook->statusList = array();
$lang->repo->webhook->statusList['enabled']  = 'Enabled';
$lang->repo->webhook->statusList['disabled'] = 'Disabled';

$lang->repo->webhook->latestStatusList = array();
$lang->repo->webhook->latestStatusList['success'] = 'success';
$lang->repo->webhook->latestStatusList['fail']    = 'fail';
$lang->repo->webhook->latestStatusList['pending'] = 'pending';

$lang->repo->webhook->logStatusList = array();
$lang->repo->webhook->logStatusList['success'] = 'Success';
$lang->repo->webhook->logStatusList['fail']    = 'Fail';

$lang->repo->webhook->key                  = 'Key';
$lang->repo->webhook->desc                 = 'Description';
$lang->repo->webhook->SSL                  = 'Enable SSL';
$lang->repo->webhook->triggerEvent         = 'Trigger Event';
$lang->repo->webhook->customEvent          = 'Custom Event';
$lang->repo->webhook->urlError             = 'Target URL format is incorrect';
$lang->repo->webhook->customEventError     = 'Custom Event can not be empty';
$lang->repo->webhook->nameExists           = 'Name %s already exists';
$lang->repo->webhook->defaultShowSecret    = '******';
$lang->repo->webhook->enabledSuccess       = 'Enabled successfully';
$lang->repo->webhook->disabledSuccess      = 'Disabled successfully';
$lang->repo->webhook->enabledFail          = 'Enabled failed';
$lang->repo->webhook->disabledFail         = 'Disabled failed';
$lang->repo->webhook->requestData          = 'Request Data';
$lang->repo->webhook->requestDate          = 'Request Date';
$lang->repo->webhook->triggerType          = 'Trigger Type';
$lang->repo->webhook->requestURL           = 'Request URL';
$lang->repo->webhook->requestHeaders       = 'Request Headers';
$lang->repo->webhook->requestBody          = 'Request Data';
$lang->repo->webhook->responseHeaders      = 'Response Headers';
$lang->repo->webhook->responseBody         = 'Response Data';
$lang->repo->webhook->emptyData            = 'No data';
$lang->repo->webhook->deleteSuccess        = 'Delete successfully';
$lang->repo->webhook->confirmWebhookDelete = "Are you sure to delete '%s', it will not be able to recover?";
$lang->repo->webhook->lengthError          = "『%s』length should be <=『%s』";
$lang->repo->webhook->deleteFail           = 'Webhook has been used, it can not be deleted';

$lang->repo->webhook->triggerEventList = array();
$lang->repo->webhook->triggerEventList[0] = 'All events';
$lang->repo->webhook->triggerEventList[1] = 'Custom Event';

$lang->repo->webhook->customEventList = array();
$lang->repo->webhook->customEventList['branch_created']           = 'Branch created';
$lang->repo->webhook->customEventList['branch_updated']           = 'Branch updated';
$lang->repo->webhook->customEventList['branch_deleted']           = 'Branch deleted';
$lang->repo->webhook->customEventList['tag_created']              = 'Tag created';
$lang->repo->webhook->customEventList['tag_deleted']              = 'Tag deleted';
$lang->repo->webhook->customEventList['pullreq_created']          = 'Create Pull Request';
$lang->repo->webhook->customEventList['pullreq_reopened']         = 'Reopen Pull Request';
$lang->repo->webhook->customEventList['pullreq_branch_updated']   = 'Update Pull Request Branch';
$lang->repo->webhook->customEventList['pullreq_closed']           = 'Close Pull Request';
$lang->repo->webhook->customEventList['pullreq_merged']           = 'Merge Pull Request';

$lang->repo->sourceList = array();
$lang->repo->sourceList['GitLab']     = 'GitLab';
$lang->repo->sourceList['Gitea']      = 'Gitea';
$lang->repo->sourceList['Gogs']       = 'Gogs';
$lang->repo->sourceList['Subversion'] = 'Subversion';

$lang->repo->accessList = array();
$lang->repo->accessList['writable'] = 'Readable, Writable, Manageable';
$lang->repo->accessList['readonly'] = 'Read-only (for image import, managed via third-party code repository, automatically synced regularly by DevOps)';

$lang->repo->importProgress = new stdclass();
$lang->repo->importProgress->title        = 'Importing repository...';
$lang->repo->importProgress->desc         = 'The third-party repository is being imported into the system. Please wait. This may take a few minutes.';
$lang->repo->importProgress->notice       = 'Please wait patiently for the import to complete and do not close this page.';
$lang->repo->importProgress->leaveTip     = 'The repository is being imported. Please do not close this page. Once closed, the import progress will no longer be available.';
$lang->repo->importProgress->acknowledge  = 'I know';
$lang->repo->importProgress->importFailed = 'Import failed';
$lang->repo->importProgress->failMessage  = 'Repository import failed: %s';
$lang->repo->importProgress->successTips  = 'Repository import successful. You can now perform the following actions:';
$lang->repo->importProgress->toRepoBrowse = 'Browse repository';
$lang->repo->importProgress->toRepoList   = 'Back to repository list';
$lang->repo->importProgress->tryAgain     = 'Try again';
