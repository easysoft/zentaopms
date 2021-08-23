<?php
$config->MR                         = new stdclass();
$config->MR->create                 = new stdclass();
$config->MR->create->requiredFields = 'gitlabID,projectID,mrID,title';
$config->MR->create->skippedFields  = 'sourceProject,sourceBranch,targetProject,targetBranch';

$config->MR->update                 = new stdclass();
$config->MR->update->requiredFields = 'title';
