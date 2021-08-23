<?php
$config->MR                         = new stdclass();
$config->MR->create                 = new stdclass();
$config->MR->create->requiredFields = 'gitlabID,sourceProject,sourceBranch,targetProject,targetBranch,title';
$config->MR->create->skippedFields  = 'projectID';

