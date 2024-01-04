<?php
$filter                    = new stdclass();
$filter->rules             = new stdclass();
$filter->rules->md5        = '/^[a-z0-9]{32}$/';
$filter->rules->base64     = '/^[a-zA-Z0-9\+\/\=\.]+$/';
$filter->rules->checked    = '/^[0-9,\-]+$/';
$filter->rules->idList     = '/^[0-9\|]+$/';
$filter->rules->lang       = '/^[a-zA-Z_\-]+$/';
$filter->rules->any        = '/./';
$filter->rules->orderBy    = '/^\w+_(desc|asc)$/i';
$filter->rules->browseType = '/^by\w+$/i';
$filter->rules->word       = '/^\w+$/';
$filter->rules->paramName  = '/^[a-zA-Z0-9_\.]+$/';
$filter->rules->paramValue = '/^[a-zA-Z0-9=_,`#+\^\/\.%\*\|\x7f-\xff\-]+$/';

$filter->default             = new stdclass();
$filter->default->moduleName = 'code';
$filter->default->methodName = 'code';
$filter->default->paramName  = 'reg::paramName';
$filter->default->paramValue = 'reg::paramValue';

$filter->default->get['onlybody']              = 'equal::yes';
$filter->default->get['_single']               = 'equal::1';
$filter->default->get['_nocache']              = 'equal::1';
$filter->default->get['tid']                   = 'reg::word';
$filter->default->get['HTTP_X_REQUESTED_WITH'] = 'equal::XMLHttpRequest';

$filter->default->cookie['lang']         = 'reg::lang';
$filter->default->cookie['theme']        = 'reg::word';
$filter->default->cookie['fingerprint']  = 'reg::word';
$filter->default->cookie['hideMenu']     = 'equal::true';
$filter->default->cookie['tab']          = 'reg::word';
$filter->default->cookie['goback']       = 'reg::any';
$filter->default->cookie['maxImport']    = 'reg::any';
$filter->default->cookie['za']           = 'reg::any';
$filter->default->cookie['zp']           = 'reg::any';
$filter->default->cookie['historyOrder'] = 'reg::word';
$filter->default->cookie['vision']       = 'reg::word';

$filter->index        = new stdclass();
$filter->my           = new stdclass();
$filter->bug          = new stdclass();
$filter->caselib      = new stdclass();
$filter->doc          = new stdclass();
$filter->api          = new stdclass();
$filter->product      = new stdclass();
$filter->branch       = new stdclass();
$filter->qa           = new stdclass();
$filter->story        = new stdclass();
$filter->task         = new stdclass();
$filter->execution    = new stdclass();
$filter->testcase     = new stdclass();
$filter->program      = new stdclass();
$filter->project      = new stdclass();
$filter->projectstory = new stdclass();
$filter->testreport   = new stdclass();
$filter->testsuite    = new stdclass();
$filter->todo         = new stdclass();
$filter->testtask     = new stdclass();
$filter->upgrade      = new stdclass();
$filter->sso          = new stdclass();
$filter->misc         = new stdclass();
$filter->mail         = new stdclass();
$filter->user         = new stdclass();
$filter->block        = new stdclass();
$filter->file         = new stdclass();
$filter->repo         = new stdclass();
$filter->webhook      = new stdclass();
$filter->git          = new stdclass();
$filter->svn          = new stdclass();
$filter->search       = new stdclass();
$filter->gitlab       = new stdclass();
$filter->mr           = new stdclass();
$filter->ci           = new stdclass();
$filter->tree         = new stdclass();
$filter->productplan  = new stdclass();
$filter->projectplan  = new stdclass();
$filter->kanban       = new stdclass();
$filter->group        = new stdclass();

$filter->index->index             = new stdclass();
$filter->block->default           = new stdclass();
$filter->block->main              = new stdclass();
$filter->my->work                 = new stdclass();
$filter->my->contribute           = new stdclass();
$filter->bug->batchcreate         = new stdclass();
$filter->bug->browse              = new stdclass();
$filter->bug->default             = new stdclass();
$filter->bug->create              = new stdclass();
$filter->bug->export              = new stdclass();
$filter->bug->ajaxgetproductcases = new stdclass();
$filter->bug->ajaxgetproductbugs  = new stdclass();
$filter->caselib->create          = new stdclass();
$filter->doc->create              = new stdclass();
$filter->doc->browse              = new stdclass();
$filter->doc->view                = new stdclass();
$filter->doc->tablecontents       = new stdclass();
$filter->doc->productspace        = new stdclass();
$filter->doc->projectspace        = new stdclass();
$filter->doc->showfiles           = new stdclass();
$filter->doc->default             = new stdclass();
$filter->api->default             = new stdClass();
$filter->api->index               = new stdClass();
$filter->api->create              = new stdClass();
$filter->api->edit                = new stdClass();
$filter->mail->ztcloud            = new stdclass();
$filter->mail->batchdelete        = new stdclass();
$filter->misc->checkupdate        = new stdclass();
$filter->file->download           = new stdclass();
$filter->product->browse          = new stdclass();
$filter->product->all             = new stdclass();
$filter->product->default         = new stdclass();
$filter->product->index           = new stdclass();
$filter->product->export          = new stdclass();
$filter->product->project         = new stdclass();
$filter->product->roadmap         = new stdclass();
$filter->product->dynamic         = new stdclass();
$filter->branch->default          = new stdclass();
$filter->program->default         = new stdclass();
$filter->program->pgmproject      = new stdclass();
$filter->program->prjbrowse       = new stdclass();
$filter->program->project         = new stdclass();
$filter->program->product         = new stdclass();
$filter->program->browse          = new stdclass();
$filter->program->export          = new stdclass();
$filter->program->pgmbrowse       = new stdclass();
$filter->program->export          = new stdclass();
$filter->program->ajaxgetdropmenu = new stdclass();
$filter->project->default         = new stdclass();
$filter->project->browse          = new stdclass();
$filter->project->story           = new stdclass();
$filter->project->export          = new stdclass();
$filter->project->task            = new stdclass();
$filter->project->execution       = new stdclass();
$filter->project->testcase        = new stdclass();
$filter->projectstory->story      = new stdclass();
$filter->qa->default              = new stdclass();
$filter->story->create            = new stdclass();
$filter->story->export            = new stdclass();
$filter->story->batchcreate       = new stdclass();
$filter->story->track             = new stdclass();
$filter->sso->getbindusers        = new stdclass();
$filter->sso->gettodolist         = new stdclass();
$filter->sso->getuserpairs        = new stdclass();
$filter->sso->login               = new stdclass();
$filter->sso->logout              = new stdclass();
$filter->git->cat                 = new stdclass();
$filter->git->diff                = new stdclass();
$filter->svn->cat                 = new stdclass();
$filter->svn->diff                = new stdclass();
$filter->task->create             = new stdclass();
$filter->task->export             = new stdclass();
$filter->task->recordestimate     = new stdclass();
$filter->execution->default       = new stdclass();
$filter->execution->story         = new stdclass();
$filter->testcase->default        = new stdclass();
$filter->testcase->create         = new stdclass();
$filter->testcase->browse         = new stdclass();
$filter->testcase->export         = new stdclass();
$filter->testcase->groupcase      = new stdclass();
$filter->testreport->default      = new stdclass();
$filter->testsuite->default       = new stdclass();
$filter->testsuite->library       = new stdclass();
$filter->testtask->default        = new stdclass();
$filter->testtask->browse         = new stdclass();
$filter->testtask->create         = new stdclass();
$filter->testtask->cases          = new stdclass();
$filter->todo->export             = new stdclass();
$filter->upgrade->license         = new stdclass();
$filter->user->login              = new stdclass();
$filter->user->edit               = new stdclass();
$filter->user->export             = new stdclass();
$filter->webhook->bind            = new stdclass();
$filter->user->ajaxgetmore        = new stdclass();
$filter->user->export             = new stdclass();
$filter->repo->ajaxsynccommit     = new stdclass();
$filter->repo->apigetrepobyurl    = new stdclass();
$filter->search->index            = new stdclass();
$filter->gitlab->webhook          = new stdclass();
$filter->gitlab->importissue      = new stdclass();
$filter->mr->diff                 = new stdclass();
$filter->mr->browse               = new stdclass();
$filter->ci->checkCompileStatus   = new stdclass();
$filter->execution->export        = new stdclass();
$filter->tree->browse             = new stdclass();
$filter->productplan->browse      = new stdclass();
$filter->projectplan->browse      = new stdclass();
$filter->kanban->space            = new stdclass();
$filter->execution->kanban        = new stdclass();
$filter->execution->all           = new stdclass();
$filter->group->editmanagepriv    = new stdclass();
$filter->caselib->default         = new stdclass();

$filter->index->index->get['open'] = 'reg::base64';

$filter->my->work->cookie['pagerMyTask']        = 'int';
$filter->my->work->cookie['pagerMyRequirement'] = 'int';
$filter->my->work->cookie['pagerMyStory']       = 'int';
$filter->my->work->cookie['pagerMyBug']         = 'int';
$filter->my->work->cookie['pagerMyTestcase']    = 'int';
$filter->my->work->cookie['pagerMyTesttask']    = 'int';

$filter->my->contribute->cookie['pagerMyTask']        = 'int';
$filter->my->contribute->cookie['pagerMyRequirement'] = 'int';
$filter->my->contribute->cookie['pagerMyStory']       = 'int';
$filter->my->contribute->cookie['pagerMyBug']         = 'int';
$filter->my->contribute->cookie['pagerMyTestcase']    = 'int';
$filter->my->contribute->cookie['pagerMyTesttask']    = 'int';
$filter->my->contribute->cookie['pagerMyDoc']         = 'int';

$filter->bug->batchcreate->cookie['preBranch'] = 'reg::word';
$filter->bug->browse->cookie['bugModule']      = 'int';
$filter->bug->browse->cookie['bugBranch']      = 'int';
$filter->bug->browse->cookie['treeBranch']     = 'reg::word';
$filter->bug->browse->cookie['preBranch']      = 'reg::word';
$filter->bug->browse->cookie['qaBugOrder']     = 'reg::orderBy';
$filter->bug->browse->cookie['windowWidth']    = 'int';
$filter->bug->default->cookie['lastProduct']   = 'int';
$filter->bug->default->cookie['preProductID']  = 'int';
$filter->bug->create->cookie['preBranch']      = 'reg::word';
$filter->bug->create->cookie['lastBugModule']  = 'int';
$filter->bug->create->cookie['sonarqubeIssue'] = 'reg::any';
$filter->bug->export->cookie['checkedItem']    = 'reg::checked';

$filter->caselib->create->cookie['lastLibCaseModule'] = 'int';
$filter->caselib->default->cookie['preBranch']        = 'reg::word';

$filter->doc->create->cookie['lastDocModule']               = 'int';
$filter->doc->browse->cookie['browseType']                  = 'reg::browseType';
$filter->doc->view->cookie['browseType']                    = 'reg::browseType';
$filter->doc->view->cookie['preBranch']                     = 'reg::word';
$filter->doc->default->cookie['from']                       = 'code';
$filter->doc->default->cookie['product']                    = 'int';
$filter->doc->default->cookie['preProductID']               = 'int';
$filter->doc->default->cookie['lastProject']                = 'int';
$filter->doc->default->cookie['docSpaceParam']              = 'string';
$filter->doc->showfiles->cookie['docFilesViewType']         = 'code';
$filter->doc->tablecontents->cookie['preProductID']         = 'int';
$filter->doc->tablecontents->cookie['preBranch']            = 'reg::word';
$filter->doc->productspace->cookie['pagerDocTablecontents'] = 'int';
$filter->doc->projectspace->cookie['pagerDocTablecontents'] = 'int';

$filter->api->default->cookie['objectType']    = 'string';
$filter->api->default->cookie['objectID']      = 'int';
$filter->api->default->cookie['docSpaceParam'] = 'string';
$filter->api->index->get['libID']              = 'int';
$filter->api->index->get['module']             = 'int';
$filter->api->index->get['apiID']              = 'int';
$filter->api->index->get['version']            = 'int';
$filter->api->create->get['libID']             = 'int';
$filter->api->create->get['module']            = 'int';
$filter->api->create->get['apiID']             = 'int';
$filter->api->edit->get['libID']               = 'int';
$filter->api->edit->get['module']              = 'int';
$filter->api->edit->get['apiID']               = 'int';

$filter->file->download->cookie[$config->sessionVar] = 'code';

$filter->mail->ztcloud->cookie['ztCloudLicense'] = 'equal::yes';

$filter->product->browse->cookie['preBranch']         = 'reg::word';
$filter->product->browse->cookie['preProductID']      = 'int';
$filter->product->browse->cookie['productStoryOrder'] = 'reg::orderBy';
$filter->product->browse->cookie['storyModule']       = 'int';
$filter->product->browse->cookie['storyBranch']       = 'int';
$filter->product->browse->cookie['treeBranch']        = 'reg::word';
$filter->product->all->cookie['showProductBatchEdit'] = 'int';
$filter->product->default->cookie['lastProduct']      = 'int';
$filter->product->default->cookie['preProductID']     = 'int';
$filter->product->index->cookie['preBranch']          = 'reg::word';
$filter->product->export->cookie['checkedItem']       = 'reg::checked';
$filter->product->project->cookie['involved']         = 'code';
$filter->product->project->cookie['preBranch']        = 'reg::word';
$filter->product->roadmap->cookie['preBranch']        = 'reg::word';
$filter->product->dynamic->cookie['preBranch']        = 'reg::word';

$filter->branch->default->cookie['preBranch'] = 'reg::word';

$filter->program->default->cookie['lastPGM']              = 'int';
$filter->program->default->cookie['lastPRJ']              = 'int';
$filter->program->prjbrowse->cookie['programType']        = 'code';
$filter->program->project->cookie['involved']             = 'code';
$filter->program->project->cookie['showProjectBatchEdit'] = 'int';
$filter->program->product->cookie['showProductBatchEdit'] = 'int';
$filter->program->browse->cookie['showClosed']            = 'code';
$filter->program->export->cookie['checkedItem']           = 'reg::checked';
$filter->program->ajaxgetdropmenu->cookie['showClosed']   = 'code';

$filter->project->default->cookie['lastProject']         = 'int';
$filter->project->default->cookie['lastPRJ']             = 'int';
$filter->project->default->cookie['projectMode']         = 'code';
$filter->project->default->cookie['kanbanview']          = 'code';
$filter->project->browse->cookie['involved']             = 'code';
$filter->project->browse->cookie['showProjectBatchEdit'] = 'int';
$filter->project->export->cookie['involved']             = 'code';
$filter->project->browse->cookie['projectType']          = 'code';
$filter->project->story->cookie['storyModuleParam']      = 'int';
$filter->project->story->cookie['storyPreProjectID']     = 'int';
$filter->project->story->cookie['storyProductParam']     = 'int';
$filter->project->story->cookie['storyBranchParam']      = 'reg::checked';
$filter->project->story->cookie['projectStoryOrder']     = 'reg::orderBy';
$filter->project->task->cookie['moduleBrowseParam']      = 'int';
$filter->project->task->cookie['preProjectID']           = 'int';
$filter->project->task->cookie['productBrowseParam']     = 'int';
$filter->project->task->cookie['projectTaskOrder']       = 'reg::orderBy';
$filter->project->task->cookie['windowWidth']            = 'int';
$filter->project->export->cookie['checkedItem']          = 'reg::checked';
$filter->project->execution->cookie['pagerExecutionAll'] = 'int';
$filter->project->execution->cookie['showTask']          = 'code';
$filter->project->execution->cookie['showStage']         = 'code';
$filter->project->testcase->cookie['showAutoCase']       = 'int';
$filter->project->testcase->cookie['onlyScene']          = 'code';

$filter->projectstory->story->cookie['storyModuleParam']   = 'int';
$filter->projectstory->story->cookie['pagerProductBrowse'] = 'int';

$filter->qa->default->cookie['lastProduct']  = 'int';
$filter->qa->default->cookie['preBranch']    = 'reg::word';
$filter->qa->default->cookie['preProductID'] = 'int';

$filter->story->create->cookie['lastStoryModule']   = 'int';
$filter->story->batchcreate->cookie['preProductID'] = 'int';
$filter->story->export->cookie['checkedItem']       = 'reg::checked';
$filter->story->track->cookie['preBranch']          = 'reg::word';
$filter->story->track->cookie['preProductID']       = 'int';

$filter->productplan->browse->cookie['viewType'] = 'code';
$filter->projectplan->browse->cookie['viewType'] = 'code';

$filter->task->create->cookie['lastTaskModule']         = 'int';
$filter->task->export->cookie['checkedItem']            = 'reg::checked';
$filter->task->recordestimate->cookie['taskEffortFold'] = 'reg::checked';

$filter->execution->default->cookie['kanbanview']         = 'code';
$filter->execution->story->cookie['storyPreExecutionID']  = 'int';
$filter->execution->story->cookie['storyModuleParam']     = 'int';
$filter->execution->story->cookie['storyProductParam']    = 'int';
$filter->execution->story->cookie['storyBranchParam']     = 'int';
$filter->execution->story->cookie['executionStoryOrder']  = 'code';
$filter->execution->export->cookie['checkedItem']         = 'reg::checked';
$filter->execution->kanban->cookie['taskToOpen']          = 'int';
$filter->execution->all->cookie['showExecutionBatchEdit'] = 'int';

$filter->testcase->browse->cookie['caseModule']      = 'int';
$filter->testcase->browse->cookie['caseSuite']       = 'int';
$filter->testcase->browse->cookie['preBranch']       = 'reg::word';
$filter->testcase->browse->cookie['showAutoCase']    = 'int';
$filter->testcase->create->cookie['lastCaseModule']  = 'int';
$filter->testcase->default->cookie['lastProduct']    = 'int';
$filter->testcase->default->cookie['preProductID']   = 'int';
$filter->testcase->export->cookie['checkedItem']     = 'reg::checked';
$filter->testcase->groupcase->cookie['preBranch']    = 'reg::word';
$filter->testcase->groupcase->cookie['showAutoCase'] = 'int';

$filter->testreport->default->cookie['lastProduct']  = 'int';
$filter->testreport->default->cookie['lastProject']  = 'int';
$filter->testreport->default->cookie['preProductID'] = 'int';
$filter->testreport->default->cookie['projectMode']  = 'code';
$filter->testreport->default->cookie['preBranch']    = 'reg::word';

$filter->testsuite->default->cookie['lastCaseLib']   = 'int';
$filter->testsuite->default->cookie['lastProduct']   = 'int';
$filter->testsuite->default->cookie['preProductID']  = 'int';
$filter->testsuite->library->cookie['libCaseModule'] = 'int';
$filter->testsuite->library->cookie['preCaseLibID']  = 'int';

$filter->testtask->browse->cookie['preBranch']     = 'reg::word';
$filter->testtask->create->cookie['preBranch']     = 'reg::word';
$filter->testtask->cases->cookie['preTaskID']      = 'int';
$filter->testtask->cases->cookie['taskCaseModule'] = 'int';
$filter->testtask->default->cookie['lastProduct']  = 'int';
$filter->testtask->default->cookie['preProductID'] = 'int';
$filter->testcase->browse->cookie['onlyScene']     = 'code';

$filter->todo->export->cookie['checkedItem'] = 'reg::checked';

$filter->user->login->cookie['keepLogin']    = 'equal::on';
$filter->user->export->cookie['checkedItem'] = 'reg::any';

$filter->block->default->get['hash']    = 'reg::md5';
$filter->block->main->get['blockid']    = 'code';
$filter->block->main->get['blockTitle'] = 'reg::any';
$filter->block->main->get['entry']      = 'code';
$filter->block->main->get['lang']       = 'reg::lang';
$filter->block->main->get['mode']       = 'code';
$filter->block->main->get['dashboard']  = 'code';
$filter->block->main->get['param']      = 'reg::base64';
$filter->block->main->get['sso']        = 'reg::base64';

$filter->doc->showfiles->get['pageID']     = 'int';
$filter->doc->showfiles->get['recPerPage'] = 'int';
$filter->doc->showfiles->get['recTotal']   = 'int';
$filter->doc->showfiles->get['title']      = 'reg::any';

$filter->file->download->get['charset'] = 'reg::any';

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
$filter->sso->login->get['requestType'] = 'reg::word';
$filter->sso->login->get['sessionid']   = 'reg::base64';
$filter->sso->logout->get['status']     = 'code';
$filter->sso->logout->get['token']      = 'reg::md5';

$filter->upgrade->license->get['agree'] = 'equal::true';

$filter->user->export->cookie['checkedItem'] = 'reg::checked';
$filter->user->login->cookie['keepLogin']    = 'equal::on';
$filter->user->login->get['account']         = 'account';
$filter->user->login->get['lang']            = 'reg::lang';
$filter->user->login->get['password']        = 'reg::any';
$filter->user->edit->get['from']             = 'reg::word';
$filter->user->ajaxgetmore->get['search']    = 'reg::any';
$filter->user->ajaxgetmore->get['limit']     = 'int';
$filter->user->ajaxgetmore->get['params']    = 'reg::base64';

$filter->git->cat->get['repoUrl']  = 'reg::base64';
$filter->git->diff->get['repoUrl'] = 'reg::base64';
$filter->svn->cat->get['repoUrl']  = 'reg::base64';
$filter->svn->diff->get['repoUrl'] = 'reg::base64';

$filter->repo->default = new stdclass();
$filter->repo->diff    = new stdclass();
$filter->repo->view    = new stdclass();

$filter->repo->default->get['repoPath'] = 'reg::base64';
$filter->repo->default->get['path']     = 'reg::base64';
$filter->repo->default->get['entry']    = 'reg::base64';

$filter->repo->apigetrepobyurl->get['url'] = 'reg::any';

$filter->repo->default->cookie['repoBranch']        = 'reg::any';
$filter->repo->default->cookie['repoCodePath']      = 'reg::any';
$filter->repo->diff->cookie['arrange']              = 'reg::word';
$filter->repo->diff->cookie['repoPairs']            = 'array';
$filter->repo->view->cookie['repoPairs']            = 'array';
$filter->repo->ajaxsynccommit->cookie['syncBranch'] = 'reg::any';

$filter->webhook->bind->get['selectedDepts']    = 'reg::any';
$filter->webhook->bind->cookie['selectedDepts'] = 'reg::any';

$filter->search->index->get['words'] = 'reg::any';
$filter->search->index->get['type']  = 'code';

$filter->gitlab->webhook->get['gitlab']  = 'int';
$filter->gitlab->webhook->get['product'] = 'int';
$filter->gitlab->webhook->get['project'] = 'int';
$filter->gitlab->webhook->get['token']   = 'reg::any';

$filter->gitlab->importissue->get['gitlab']  = 'int';
$filter->gitlab->importissue->get['product'] = 'int';
$filter->gitlab->importissue->get['product'] = 'string';
$filter->gitlab->importissue->get['project'] = 'int';
$filter->gitlab->importissue->get['repo']    = 'int';

$filter->mr->diff->cookie['arrange'] = 'reg::word';

$filter->mr->browse->get['mode']  = 'string';
$filter->mr->browse->get['param'] = 'string';

$filter->ci->checkCompileStatus->get['gitlabOnly'] = 'string';

$filter->tree->browse->cookie['preProductID'] = 'int';
$filter->tree->browse->cookie['preBranch']    = 'reg::word';

$filter->kanban->space->cookie['showClosed'] = 'code';

$filter->group->editmanagepriv->cookie['managePrivEditType'] = 'string';

$filter->bug->ajaxgetproductcases->get['search']    = 'reg::any';
$filter->bug->ajaxgetproductcases->get['limit']     = 'int';

$filter->bug->ajaxgetproductbugs->get['search']      = 'reg::any';
$filter->bug->ajaxgetproductbugs->get['limit']       = 'int';
