<?php
$config->upgrade->migrateDevOpsPrivs = array();
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('mr-browse', 'pullreq-browse'),       'to' => array('ppm-browse'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('mr-view', 'pullreq-view'),           'to' => array('ppm-view'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('mr-create'),                         'to' => array('ppm-create'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('mr-edit', 'pullreq-edit'),           'to' => array('ppm-edit'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('mr-accept', 'pullreq-accept'),       'to' => array('ppm-merge'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('mr-linkStory', 'pullreq-linkStory'), 'to' => array('repo-linkStory'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('mr-linkBug', 'pullreq-linkBug'),     'to' => array('repo-linkBug'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('mr-linkTask', 'pullreq-linkTask'),   'to' => array('repo-linkTask'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('mr-unlink', 'pullreq-unlink'),       'to' => array('repo-unlink'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('mr-approval', 'pullreq-approval'),   'to' => array('ppm-review'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('mr-close', 'pullreq-close'),         'to' => array('ppm-close'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('mr-reopen', 'pullreq-reopen'),       'to' => array('ppm-reopen'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('mr-delete', 'pullreq-delete'),       'to' => array('ppm-delete'));

$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('repo-showSyncCommit'),  'to' => array());
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('repo-revision'),        'to' => array('repo-revision', 'repo-log'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('repo-visit'),           'to' => array());
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('repo-craete'),          'to' => array('repo-import'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('repo-apiGetRepoByUrl'), 'to' => array());
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('repo-addBug'),          'to' => array('repo-addBug'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('repo-editBug'),         'to' => array('repo-addBug'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('repo-deleteBug'),       'to' => array('repo-addBug'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('repo-setCMrule'),       'to' => array('repobranchrule-setBranchRule'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('repo-setPRrule'),       'to' => array('reporeviewflow-browse', 'reporeviewflow-create', 'reporeviewflow-edit', 'reporeviewflow-changeStatus', 'reporeviewflow-delete'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('repo-setSafeRule'),     'to' => array());
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('repo-setOwnerRule'),    'to' => array());
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('repo-setStrategyRule'), 'to' => array('repo-setArchive'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('repo-browseRule'),      'to' => array('repobranchtype-browse', 'repobranchtype-create', 'repobranchtype-import', 'repobranchtype-edit', 'repobranchtype-delete'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('repo-createRule'),      'to' => array('repobranchrule-setBranchRule'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('repo-editRule'),        'to' => array('repobranchrule-setBranchRule'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('repo-enableRule'),      'to' => array('repobranchrule-setBranchRule'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('repo-deleteRule'),      'to' => array('repobranchrule-setBranchRule'));

$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('repo-createBrnach', 'task-craeteBranch', 'bug-createBranch', 'story-createBranch'), 'to' => array('repo-createBranch'));

$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('space-browse'),         'to' => array('provider-browse'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('space-monitorSetting'), 'to' => array());

$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('instance-manage'), 'to' => array('provider-create', 'provider-edit', 'provider-delete'));

$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('job-delete'),  'to' => array('pipeline-delete'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('job-browse'),  'to' => array('pipeline-browse'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('job-view'),    'to' => array('pipeline-execView'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('job-create'),  'to' => array('pipeline-create'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('job-edit'),    'to' => array('pipeline-edit'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('job-trigger'), 'to' => array());
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('job-exec'),    'to' => array('pipeline-exec'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('job-create'),  'to' => array('pipeline-create'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('job-arrange'), 'to' => array('pipeline-arrange'));

$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('compile-browse'), 'to' => array('pipeline-execution'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('compile-logs'),   'to' => array('pipeline-execView'));

$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('git-diff'),    'to' => array());
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('git-cat'),     'to' => array());
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('git-apiSync'), 'to' => array());

$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('svn-diff'),    'to' => array());
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('svn-cat'),     'to' => array());
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('svn-apiSync'), 'to' => array());

$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('artifactrepo-browse'),                  'to' => array('artifact-browse'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('artifactrepo-ajaxGetArtifactRepos'),    'to' => array());
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('artifactrepo-view'),                    'to' => array('artifact-view'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('artifactrepo-create'),                  'to' => array('artifact-create', 'artifact-createDir', 'artifact-editDir', 'artifact-deleteDir', 'artifact-uploadArtifact', 'artifact-deleteArtifact', 'artifact-editArtifact'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('artifactrepo-edit'),                    'to' => array('artifact-edit', 'artifact-createDir', 'artifact-editDir', 'artifact-deleteDir', 'artifact-uploadArtifact', 'artifact-deleteArtifact', 'artifact-editArtifact'));
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('artifactrepo-ajaxUpdateArtifactRepos'), 'to' => array());
$config->upgrade->migrateDevOpsPrivs[] = array('from' => array('artifactrepo-delete'),                  'to' => array('artifact-delete'));
