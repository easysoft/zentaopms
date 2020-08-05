<?php
$filter = new stdclass();
$filter->rules = new stdclass();
$filter->rules->md5        = '/^[a-z0-9]{32}$/';
$filter->rules->base64     = '/^[a-zA-Z0-9\+\/\=]+$/';
$filter->rules->checked    = '/^[0-9,\-]+$/';
$filter->rules->idList     = '/^[0-9\|]+$/';
$filter->rules->lang       = '/^[a-zA-Z_\-]+$/';
$filter->rules->any        = '/./';
$filter->rules->orderBy    = '/^\w+_(desc|asc)$/i';
$filter->rules->browseType = '/^by\w+$/i';
$filter->rules->word       = '/^\w+$/';
$filter->rules->paramName  = '/^[a-zA-Z0-9_\.]+$/';
$filter->rules->paramValue = '/^[a-zA-Z0-9=_,`#+\^\/\.%\|\x7f-\xff\-]+$/';

$filter->default = new stdclass();
$filter->default->moduleName = 'code';
$filter->default->methodName = 'code';
$filter->default->paramName  = 'reg::paramName';
$filter->default->paramValue = 'reg::paramValue';

$filter->default->get['onlybody'] = 'equal::yes';
$filter->default->get['HTTP_X_REQUESTED_WITH'] = 'equal::XMLHttpRequest';

$filter->default->cookie['lang']        = 'reg::lang';
$filter->default->cookie['theme']       = 'reg::word';
$filter->default->cookie['fingerprint'] = 'reg::word';

$filter->bug        = new stdclass();
$filter->caselib    = new stdclass();
$filter->doc        = new stdclass();
$filter->product    = new stdclass();
$filter->qa         = new stdclass();
$filter->story      = new stdclass();
$filter->task       = new stdclass();
$filter->testcase   = new stdclass();
$filter->project    = new stdclass();
$filter->testreport = new stdclass();
$filter->testsuite  = new stdclass();
$filter->todo       = new stdclass();
$filter->testtask   = new stdclass();
$filter->upgrade    = new stdclass();
$filter->sso        = new stdclass();
$filter->misc       = new stdclass();
$filter->mail       = new stdclass();
$filter->user       = new stdclass();
$filter->block      = new stdclass();
$filter->file       = new stdclass();
$filter->repo       = new stdclass();
$filter->webhook    = new stdclass();

$filter->block->default          = new stdclass();
$filter->block->main             = new stdclass();
$filter->bug->batchcreate        = new stdclass();
$filter->bug->browse             = new stdclass();
$filter->bug->default            = new stdclass();
$filter->bug->create             = new stdclass();
$filter->bug->export             = new stdclass();
$filter->caselib->create         = new stdclass();
$filter->doc->create             = new stdclass();
$filter->doc->browse             = new stdclass();
$filter->doc->showfiles          = new stdclass();
$filter->doc->default            = new stdclass();
$filter->doc->showfiles          = new stdclass();
$filter->mail->ztcloud           = new stdclass();
$filter->mail->batchdelete       = new stdclass();
$filter->misc->checkupdate       = new stdclass();
$filter->file->download          = new stdclass();
$filter->product->browse         = new stdclass();
$filter->product->default        = new stdclass();
$filter->product->index          = new stdclass();
$filter->project->default        = new stdclass();
$filter->project->story          = new stdclass();
$filter->project->task           = new stdclass();
$filter->qa->default             = new stdclass();
$filter->story->create           = new stdclass();
$filter->story->export           = new stdclass();
$filter->sso->getbindusers       = new stdclass();
$filter->sso->gettodolist        = new stdclass();
$filter->sso->getuserpairs       = new stdclass();
$filter->sso->login              = new stdclass();
$filter->sso->logout             = new stdclass();
$filter->task->create            = new stdclass();
$filter->task->export            = new stdclass();
$filter->testcase->default       = new stdclass();
$filter->testcase->create        = new stdclass();
$filter->testcase->browse        = new stdclass();
$filter->testcase->export        = new stdclass();
$filter->testcase->groupcase     = new stdclass();
$filter->testreport->default     = new stdclass();
$filter->testsuite->default      = new stdclass();
$filter->testsuite->library      = new stdclass();
$filter->testtask->default       = new stdclass();
$filter->todo->export            = new stdclass();
$filter->upgrade->license        = new stdclass();
$filter->user->login             = new stdclass();
$filter->webhook->bind           = new stdclass();
$filter->user->ajaxgetmore       = new stdclass();

$filter->bug->batchcreate->cookie['preBranch'] = 'int';
$filter->bug->browse->cookie['bugModule']      = 'int';
$filter->bug->browse->cookie['bugBranch']      = 'int';
$filter->bug->browse->cookie['treeBranch']     = 'int';
$filter->bug->browse->cookie['preBranch']      = 'int';
$filter->bug->browse->cookie['qaBugOrder']     = 'reg::orderBy';
$filter->bug->browse->cookie['windowWidth']    = 'int';
$filter->bug->default->cookie['lastProduct']   = 'int';
$filter->bug->default->cookie['preProductID']  = 'int';
$filter->bug->create->cookie['preBranch']      = 'int';
$filter->bug->create->cookie['lastBugModule']  = 'int';
$filter->bug->export->cookie['checkedItem']    = 'reg::checked';

$filter->caselib->create->cookie['lastLibCaseModule']       = 'int';

$filter->doc->create->cookie['lastDocModule']       = 'int';
$filter->doc->browse->cookie['browseType']          = 'reg::browseType';
$filter->doc->alllibs->cookie['browseType']         = 'reg::browseType';
$filter->doc->objectlibs->cookie['browseType']      = 'reg::browseType';
$filter->doc->default->cookie['from']               = 'code';
$filter->doc->default->cookie['product']            = 'int';
$filter->doc->showfiles->cookie['docFilesViewType'] = 'code';

$filter->file->download->cookie[$config->sessionVar] = 'code';

$filter->mail->ztcloud->cookie['ztCloudLicense'] = 'equal::yes';

$filter->product->browse->cookie['preBranch']         = 'int';
$filter->product->browse->cookie['preProductID']      = 'int';
$filter->product->browse->cookie['productStoryOrder'] = 'reg::orderBy';
$filter->product->browse->cookie['storyModule']       = 'int';
$filter->product->browse->cookie['storyBranch']       = 'int';
$filter->product->browse->cookie['treeBranch']        = 'int';
$filter->product->default->cookie['lastProduct']      = 'int';
$filter->product->default->cookie['preProductID']     = 'int';
$filter->product->index->cookie['preBranch']          = 'int';
$filter->product->export->cookie['checkedItem']       = 'reg::checked';

$filter->project->default->cookie['lastProject']     = 'int';
$filter->project->default->cookie['projectMode']     = 'code';
$filter->project->story->cookie['storyModuleParam']  = 'int';
$filter->project->story->cookie['storyPreProjectID'] = 'int';
$filter->project->story->cookie['storyProductParam'] = 'int';
$filter->project->story->cookie['storyBranchParam']  = 'reg::checked';
$filter->project->story->cookie['projectStoryOrder'] = 'reg::orderBy';
$filter->project->task->cookie['moduleBrowseParam']  = 'int';
$filter->project->task->cookie['preProjectID']       = 'int';
$filter->project->task->cookie['productBrowseParam'] = 'int';
$filter->project->task->cookie['projectTaskOrder']   = 'reg::orderBy';
$filter->project->task->cookie['windowWidth']        = 'int';
$filter->project->export->cookie['checkedItem']      = 'reg::checked';

$filter->qa->default->cookie['lastProduct']  = 'int';
$filter->qa->default->cookie['preBranch']    = 'int';
$filter->qa->default->cookie['preProductID'] = 'int';

$filter->story->create->cookie['lastStoryModule'] = 'int';
$filter->story->export->cookie['checkedItem'] = 'reg::checked';

$filter->task->create->cookie['lastTaskModule'] = 'int';
$filter->task->export->cookie['checkedItem']    = 'reg::checked';

$filter->testcase->browse->cookie['caseModule']     = 'int';
$filter->testcase->browse->cookie['caseSuite']      = 'int';
$filter->testcase->browse->cookie['preBranch']      = 'int';
$filter->testcase->create->cookie['lastCaseModule'] = 'int';
$filter->testcase->default->cookie['lastProduct']   = 'int';
$filter->testcase->default->cookie['preProductID']  = 'int';
$filter->testcase->export->cookie['checkedItem']    = 'reg::checked';
$filter->testcase->groupcase->cookie['preBranch']   = 'int';

$filter->testreport->default->cookie['lastProduct']  = 'int';
$filter->testreport->default->cookie['lastProject']  = 'int';
$filter->testreport->default->cookie['preProductID'] = 'int';
$filter->testreport->default->cookie['projectMode']  = 'code';

$filter->testsuite->default->cookie['lastCaseLib']   = 'int';
$filter->testsuite->default->cookie['lastProduct']   = 'int';
$filter->testsuite->default->cookie['preProductID']  = 'int';
$filter->testsuite->library->cookie['libCaseModule'] = 'int';
$filter->testsuite->library->cookie['preCaseLibID']  = 'int';

$filter->testtask->browse->cookie['preBranch']     = 'int';
$filter->testtask->cases->cookie['preTaskID']      = 'int';
$filter->testtask->cases->cookie['taskCaseModule'] = 'int';
$filter->testtask->default->cookie['lastProduct']  = 'int';
$filter->testtask->default->cookie['preProductID'] = 'int';

$filter->todo->export->cookie['checkedItem'] = 'reg::checked';

$filter->user->login->cookie['keepLogin'] = 'equal::on';

$filter->block->default->get['hash']    = 'reg::md5';
$filter->block->main->get['blockid']    = 'code';
$filter->block->main->get['blockTitle'] = 'reg::any';
$filter->block->main->get['entry']      = 'code';
$filter->block->main->get['lang']       = 'reg::lang';
$filter->block->main->get['mode']       = 'code';
$filter->block->main->get['param']      = 'reg::base64';
$filter->block->main->get['sso']        = 'reg::base64';

$filter->doc->showfiles->get['pageID']     = 'int';
$filter->doc->showfiles->get['recPerPage'] = 'int';
$filter->doc->showfiles->get['recTotal']   = 'int';
$filter->doc->showfiles->get['title']      = 'reg::any';

$filter->file->download->get['charset']         = 'reg::lang';

$filter->mail->batchdelete->get['idList'] = 'reg::idList';

$filter->misc->checkupdate->get['browser'] = 'code';
$filter->misc->checkupdate->get['note']    = 'reg::base64';

$filter->sso->getbindusers->get['hash'] = 'reg::md5';
$filter->sso->gettodolist->get['hash']  = 'reg::md5';
$filter->sso->getuserpairs->get['hash'] = 'reg::md5';
$filter->sso->login->get['data']        = 'reg::base64';
$filter->sso->login->get['md5']         = 'reg::md5';
$filter->sso->login->get['referer']     = 'reg::base64';
$filter->sso->login->get['status']      = 'code';
$filter->sso->login->get['token']       = 'reg::md5';
$filter->sso->login->get['sessionid']   = 'reg::base64';
$filter->sso->logout->get['status']     = 'code';
$filter->sso->logout->get['token']      = 'reg::md5';

$filter->upgrade->license->get['agree'] = 'equal::true';

$filter->user->login->get['account']      = 'account';
$filter->user->login->get['lang']         = 'reg::lang';
$filter->user->login->get['password']     = 'reg::any';
$filter->user->edit->get['from']          = 'reg::word';
$filter->user->ajaxgetmore->get['search'] = 'reg::any';
$filter->user->ajaxgetmore->get['limit']  = 'int';

$filter->git->cat->get['repoUrl']  = 'reg::base64';
$filter->git->diff->get['repoUrl'] = 'reg::base64';
$filter->svn->cat->get['repoUrl']  = 'reg::base64';
$filter->svn->diff->get['repoUrl'] = 'reg::base64';

$filter->repo->default = new stdclass();
$filter->repo->diff    = new stdclass();
$filter->repo->view    = new stdclass();

$filter->repo->default->get['path']  = 'reg::base64';
$filter->repo->default->get['entry'] = 'reg::base64';

$filter->repo->default->cookie['repoBranch'] = 'reg::any';
$filter->repo->diff->cookie['arrange']       = 'reg::word';
$filter->repo->diff->cookie['repoPairs']     = 'array';
$filter->repo->view->cookie['repoPairs']     = 'array';
$filter->repo->ajaxsynccommit->cookie['syncBranch'] = 'reg::any';

$filter->webhook->bind->get['selectedDepts']    = 'reg::checked';
$filter->webhook->bind->cookie['selectedDepts'] = 'reg::checked';
