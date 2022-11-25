<?php
$config->mr = new stdclass();

$config->mr->create = new stdclass();
$config->mr->create->skippedFields  = 'projectID,compile';
$config->mr->create->requiredFields = 'gitlabID,sourceProject,sourceBranch,targetProject,targetBranch,title,repoID';

$config->mr->edit = new stdclass;
$config->mr->edit->skippedFields  = 'projectID,compile';
$config->mr->edit->requiredFields = 'gitlabID,sourceProject,sourceBranch,targetProject,targetBranch,title,repoID';

$config->mr->editor = new stdclass();
$config->mr->editor->diff = array('id' => 'commentText', 'tools' => 'simpleTools');

$config->mr->apicreate = new stdclass();
$config->mr->apicreate->requiredFields = 'repoID,sourceBranch,targetBranch,mergeStatus,jobID';

$config->mr->maps = new stdclass;
$config->mr->maps->sync = array();
$config->mr->maps->sync['title']         = 'title|field|';
$config->mr->maps->sync['description']   = 'description|field|';
$config->mr->maps->sync['assignee']      = 'assignees|userPairs|id';
$config->mr->maps->sync['reviewer']      = 'reviewers|userPairs|id';
$config->mr->maps->sync['targetBranch']  = 'target_branch|field|';
$config->mr->maps->sync['sourceBranch']  = 'source_branch|field|';
$config->mr->maps->sync['sourceProject'] = 'source_project_id|field|';
$config->mr->maps->sync['targetProject'] = 'target_project_id|field|';
$config->mr->maps->sync['status']        = 'state|field|';
$config->mr->maps->sync['mergeStatus']   = 'merge_status|field|';

$config->mrapproval = new stdclass();
$config->mrapproval->create = new stdclass();
$config->mrapproval->create->skippedFields  = '';
$config->mrapproval->create->requiredFields = 'mrID,account,date,action';

$config->mr->gitServiceList = array('gitlab', 'gitea', 'gogs');
