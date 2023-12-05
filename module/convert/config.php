<?php
define('JIRA_USER', '`app_user`');
define('JIRA_USERINFO', '`cwd_user`');
define('JIRA_PROJECT', '`project`');
define('JIRA_ISSUE', '`jiraissue`');
define('JIRA_ISSUETYPE', '`issuetype`');
define('JIRA_ISSUELINK', '`issuelink`');
define('JIRA_ISSUELINKTYPE', '`issuelinktype`');
define('JIRA_ISSUESTATUS', '`issuestatus`');
define('JIRA_RESOLUTION', '`resolution`');
define('JIRA_BUILD', '`projectversion`');
define('JIRA_ACTION', '`jiraaction`');
define('JIRA_NODEASSOCIATION', '`nodeassociation`');
define('JIRA_FILE', '`fileattachment`');
define('JIRA_TMPRELATION', '`jiratmprelation`');

$config->convert = new stdClass();
$config->convert->objectTables = array();
$config->convert->objectTables['user']      = JIRA_USER;
$config->convert->objectTables['project']   = JIRA_PROJECT;
$config->convert->objectTables['issue']     = JIRA_ISSUE;
$config->convert->objectTables['build']     = JIRA_BUILD;
$config->convert->objectTables['issuelink'] = JIRA_ISSUELINK;
$config->convert->objectTables['action']    = JIRA_ACTION;
$config->convert->objectTables['file']      = JIRA_FILE;
