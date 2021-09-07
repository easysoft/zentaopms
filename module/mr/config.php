<?php
$config->MR                         = new stdclass();
$config->MR->create                 = new stdclass();
$config->MR->create->requiredFields = 'gitlabID,sourceProject,sourceBranch,targetProject,targetBranch,title';
$config->MR->create->skippedFields  = 'projectID';

$config->MR->maps = new stdclass;
$config->MR->maps->sync = array();
$config->MR->maps->sync['title']         = 'title|field|';
$config->MR->maps->sync['description']   = 'description|field|';
$config->MR->maps->sync['assignee']      = 'assignees|userPairs|id';
$config->MR->maps->sync['reviewer']      = 'reviewers|userPairs|id';
$config->MR->maps->sync['targetBranch']  = 'target_branch|field|';
$config->MR->maps->sync['sourceBranch']  = 'source_branch|field|';
$config->MR->maps->sync['sourceProject'] = 'source_project_id|field|';
$config->MR->maps->sync['targetProject'] = 'target_project_id|field|';
$config->MR->maps->sync['status']        = 'state|field|';
$config->MR->maps->sync['mergeStatus']   = 'merge_status|field|';

