<?php
/**
* The config file of zentaophp.  Don't modify this file directly, copy the item to my.php and change it.
*
* @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
* @license     ZPL (http://zpl.pub/page/zplv12.html)
* @author      Chunsheng Wang <chunsheng@cnezsoft.com>
* @package     config
* @version     $Id: zentaopms.php 5068 2017-06-20 15:35:22Z pengjx $
* @link        http://www.zentao.net
*/

/* Product common list. */
$config->productCommonList['zh-cn'][0] = '产品';
$config->productCommonList['zh-tw'][0] = '產品';
$config->productCommonList['en'][0]    = 'Product';
$config->productCommonList['de'][0]    = 'Produkt';
$config->productCommonList['fr'][0]    = 'Product';
$config->productCommonList['vi'][0]    = 'Sản phẩm';

/* Project common list. */
$config->projectCommonList['zh-cn'][0] = '项目';
$config->projectCommonList['zh-cn'][1] = '迭代';
$config->projectCommonList['zh-cn'][2] = '冲刺';

$config->projectCommonList['zh-tw'][0] = '項目';
$config->projectCommonList['zh-tw'][1] = '迭代';
$config->projectCommonList['zh-tw'][2] = '冲刺';

$config->projectCommonList['en'][0] = 'Project';
$config->projectCommonList['en'][1] = 'Iteration';
$config->projectCommonList['en'][2] = 'Sprint';

$config->projectCommonList['de'][0] = 'Project';
$config->projectCommonList['de'][1] = 'Iteration';
$config->projectCommonList['de'][2] = 'Sprint';

$config->projectCommonList['fr'][0] = 'Project';
$config->projectCommonList['fr'][1] = 'Iteration';
$config->projectCommonList['fr'][2] = 'Sprint';

$config->projectCommonList['vi'][0] = 'Project';
$config->projectCommonList['vi'][1] = 'Iteration';
$config->projectCommonList['vi'][2] = 'Sprint';

$config->executionCommonList['zh-cn'][0] = '迭代';
$config->executionCommonList['zh-cn'][1] = '冲刺';
$config->executionCommonList['zh-cn'][2] = '阶段';

$config->executionCommonList['zh-tw'][0] = '迭代';
$config->executionCommonList['zh-tw'][1] = '冲刺';
$config->executionCommonList['zh-tw'][2] = '階段';

$config->executionCommonList['en'][0] = 'Iteration';
$config->executionCommonList['en'][1] = 'Sprint';
$config->executionCommonList['en'][2] = 'Stage';

$config->executionCommonList['de'][0] = 'Iteration';
$config->executionCommonList['de'][1] = 'Sprint';
$config->executionCommonList['de'][2] = 'Bühne';

$config->executionCommonList['fr'][0] = 'Iteration';
$config->executionCommonList['fr'][1] = 'Sprint';
$config->executionCommonList['fr'][2] = 'Phase';

$config->executionCommonList['vi'][0] = 'Lặp lại';
$config->executionCommonList['vi'][1] = 'Sprint';
$config->executionCommonList['vi'][2] = 'Giai đoạn';

/* Story common list. */
$config->hourPointCommonList['zh-cn'][0] = '工时';
$config->hourPointCommonList['zh-cn'][1] = '故事点';
$config->hourPointCommonList['zh-cn'][2] = '功能点';

$config->hourPointCommonList['zh-tw'][0] = '工时';
$config->hourPointCommonList['zh-tw'][1] = '故事点';
$config->hourPointCommonList['zh-tw'][2] = '功能点';

$config->hourPointCommonList['en'][0] = 'Hours';
$config->hourPointCommonList['en'][1] = 'story point';
$config->hourPointCommonList['en'][2] = 'function point';

$config->hourPointCommonList['de'][0] = 'Stunde';
$config->hourPointCommonList['de'][1] = 'story point';
$config->hourPointCommonList['de'][2] = 'function point';

$config->hourPointCommonList['fr'][0] = 'Heures';
$config->hourPointCommonList['fr'][1] = 'story point';
$config->hourPointCommonList['fr'][2] = 'function point';

$config->hourPointCommonList['vi'][0] = 'giờ';
$config->hourPointCommonList['vi'][1] = 'điểm';
$config->hourPointCommonList['vi'][2] = 'function point';

$config->manualUrl['home'] = 'https://www.zentao.net/book/zentaopmshelp.html?fullScreen=zentao';
$config->manualUrl['int']  = 'https://www.zentao.pm/book/zentaomanual/zentao-installation-11.html?fullScreen=zentao';

/* Supported charsets. */
$config->charsets['zh-cn']['utf-8'] = 'UTF-8';
$config->charsets['zh-cn']['gbk']   = 'GBK';
$config->charsets['zh-tw']['utf-8'] = 'UTF-8';
$config->charsets['zh-tw']['big5']  = 'BIG5';
$config->charsets['en']['utf-8']    = 'UTF-8';
$config->charsets['en']['GBK']      = 'GBK';
$config->charsets['de']['utf-8']    = 'UTF-8';
$config->charsets['de']['GBK']      = 'GBK';
$config->charsets['fr']['utf-8']    = 'UTF-8';
$config->charsets['fr']['GBK']      = 'GBK';
$config->charsets['vi']['utf-8']    = 'UTF-8';
$config->charsets['vi']['GBK']      = 'GBK';

/* Define the tables. */
define('TABLE_COMPANY',       '`' . $config->db->prefix . 'company`');
define('TABLE_DEPT',          '`' . $config->db->prefix . 'dept`');
define('TABLE_CONFIG',        '`' . $config->db->prefix . 'config`');
define('TABLE_USER',          '`' . $config->db->prefix . 'user`');
define('TABLE_TODO',          '`' . $config->db->prefix . 'todo`');
define('TABLE_GROUP',         '`' . $config->db->prefix . 'group`');
define('TABLE_GROUPPRIV',     '`' . $config->db->prefix . 'grouppriv`');
define('TABLE_USERGROUP',     '`' . $config->db->prefix . 'usergroup`');
define('TABLE_USERQUERY',     '`' . $config->db->prefix . 'userquery`');
define('TABLE_USERCONTACT',   '`' . $config->db->prefix . 'usercontact`');
define('TABLE_USERVIEW',      '`' . $config->db->prefix . 'userview`');

define('TABLE_BUG',           '`' . $config->db->prefix . 'bug`');
define('TABLE_CASE',          '`' . $config->db->prefix . 'case`');
define('TABLE_CASESTEP',      '`' . $config->db->prefix . 'casestep`');
define('TABLE_TESTTASK',      '`' . $config->db->prefix . 'testtask`');
define('TABLE_TESTRUN',       '`' . $config->db->prefix . 'testrun`');
define('TABLE_TESTRESULT',    '`' . $config->db->prefix . 'testresult`');
define('TABLE_USERTPL',       '`' . $config->db->prefix . 'usertpl`');

define('TABLE_PRODUCT',       '`' . $config->db->prefix . 'product`');
define('TABLE_BRANCH',        '`' . $config->db->prefix . 'branch`');
define('TABLE_EXPECT',        '`' . $config->db->prefix . 'expect`');
define('TABLE_STAKEHOLDER',   '`' . $config->db->prefix . 'stakeholder`');
define('TABLE_STORY',         '`' . $config->db->prefix . 'story`');
define('TABLE_STORYSPEC',     '`' . $config->db->prefix . 'storyspec`');
define('TABLE_STORYREVIEW',   '`' . $config->db->prefix . 'storyreview`');
define('TABLE_STORYSTAGE',    '`' . $config->db->prefix . 'storystage`');
define('TABLE_STORYESTIMATE', '`' . $config->db->prefix . 'storyestimate`');
define('TABLE_PRODUCTPLAN',   '`' . $config->db->prefix . 'productplan`');
define('TABLE_PLANSTORY',     '`' . $config->db->prefix . 'planstory`');
define('TABLE_RELEASE',       '`' . $config->db->prefix . 'release`');

define('TABLE_PROGRAM',       '`' . $config->db->prefix . 'project`');
define('TABLE_PROJECT',       '`' . $config->db->prefix . 'project`');
define('TABLE_EXECUTION',     '`' . $config->db->prefix . 'project`');
define('TABLE_TASK',          '`' . $config->db->prefix . 'task`');
define('TABLE_TASKSPEC',      '`' . $config->db->prefix . 'taskspec`');
define('TABLE_TEAM',          '`' . $config->db->prefix . 'team`');
define('TABLE_PROJECTPRODUCT','`' . $config->db->prefix . 'projectproduct`');
define('TABLE_PROJECTSTORY',  '`' . $config->db->prefix . 'projectstory`');
define('TABLE_PROJECTCASE',   '`' . $config->db->prefix . 'projectcase`');
define('TABLE_TASKESTIMATE',  '`' . $config->db->prefix . 'taskestimate`');
define('TABLE_EFFORT',        '`' . $config->db->prefix . 'effort`');
define('TABLE_BURN',          '`' . $config->db->prefix . 'burn`');
define('TABLE_BUILD',         '`' . $config->db->prefix . 'build`');
define('TABLE_ACL',           '`' . $config->db->prefix . 'acl`');

define('TABLE_DOCLIB',        '`' . $config->db->prefix . 'doclib`');
define('TABLE_DOC',           '`' . $config->db->prefix . 'doc`');

define('TABLE_MODULE',        '`' . $config->db->prefix . 'module`');
define('TABLE_ACTION',        '`' . $config->db->prefix . 'action`');
define('TABLE_FILE',          '`' . $config->db->prefix . 'file`');
define('TABLE_HISTORY',       '`' . $config->db->prefix . 'history`');
define('TABLE_EXTENSION',     '`' . $config->db->prefix . 'extension`');
define('TABLE_CRON',          '`' . $config->db->prefix . 'cron`');
define('TABLE_BLOCK',         '`' . $config->db->prefix . 'block`');
define('TABLE_DOCCONTENT',    '`' . $config->db->prefix . 'doccontent`');
define('TABLE_TESTSUITE',     '`' . $config->db->prefix . 'testsuite`');
define('TABLE_SUITECASE',     '`' . $config->db->prefix . 'suitecase`');
define('TABLE_TESTREPORT',    '`' . $config->db->prefix . 'testreport`');

define('TABLE_ENTRY',         '`' . $config->db->prefix . 'entry`');
define('TABLE_WEBHOOK',       '`' . $config->db->prefix . 'webhook`');
define('TABLE_LOG',           '`' . $config->db->prefix . 'log`');
define('TABLE_SCORE',         '`' . $config->db->prefix . 'score`');
define('TABLE_NOTIFY',        '`' . $config->db->prefix . 'notify`');
define('TABLE_OAUTH',         '`' . $config->db->prefix . 'oauth`');
define('TABLE_JENKINS',       '`' . $config->db->prefix . 'jenkins`');
define('TABLE_JOB',           '`' . $config->db->prefix . 'job`');
define('TABLE_COMPILE',       '`' . $config->db->prefix . 'compile`');

define('TABLE_REPO',        '`' . $config->db->prefix . 'repo`');
define('TABLE_RELATION',    '`' . $config->db->prefix . 'relation`');
define('TABLE_REPOHISTORY', '`' . $config->db->prefix . 'repohistory`');
define('TABLE_REPOFILES',   '`' . $config->db->prefix . 'repofiles`');
define('TABLE_REPOBRANCH',  '`' . $config->db->prefix . 'repobranch`');
if(!defined('TABLE_LANG'))               define('TABLE_LANG', '`' . $config->db->prefix . 'lang`');
if(!defined('TABLE_PROJECTSPEC'))        define('TABLE_PROJECTSPEC', '`' . $config->db->prefix . 'projectspec`');

if(!defined('TABLE_SEARCHINDEX')) define('TABLE_SEARCHINDEX', $config->db->prefix . 'searchindex');
if(!defined('TABLE_SEARCHDICT'))  define('TABLE_SEARCHDICT',  $config->db->prefix . 'searchdict');

$config->objectTables['product']     = TABLE_PRODUCT;
$config->objectTables['productplan'] = TABLE_PRODUCTPLAN;
$config->objectTables['story']       = TABLE_STORY;
$config->objectTables['release']     = TABLE_RELEASE;
$config->objectTables['program']     = TABLE_PROJECT;
$config->objectTables['project']     = TABLE_PROJECT;
$config->objectTables['execution']   = TABLE_PROJECT;
$config->objectTables['task']        = TABLE_TASK;
$config->objectTables['build']       = TABLE_BUILD;
$config->objectTables['bug']         = TABLE_BUG;
$config->objectTables['case']        = TABLE_CASE;
$config->objectTables['testcase']    = TABLE_CASE;
$config->objectTables['testtask']    = TABLE_TESTTASK;
$config->objectTables['testsuite']   = TABLE_TESTSUITE;
$config->objectTables['testreport']  = TABLE_TESTREPORT;
$config->objectTables['user']        = TABLE_USER;
$config->objectTables['doc']         = TABLE_DOC;
$config->objectTables['doclib']      = TABLE_DOCLIB;
$config->objectTables['todo']        = TABLE_TODO;
$config->objectTables['custom']      = TABLE_LANG;
$config->objectTables['branch']      = TABLE_BRANCH;
$config->objectTables['module']      = TABLE_MODULE;
$config->objectTables['caselib']     = TABLE_TESTSUITE;
$config->objectTables['entry']       = TABLE_ENTRY;
$config->objectTables['webhook']     = TABLE_WEBHOOK;
$config->objectTables['stakeholder'] = TABLE_STAKEHOLDER;
$config->objectTables['job']         = TABLE_JOB;
$config->objectTables['team']        = TABLE_TEAM;

/* Program privs.*/
$config->programPriv = new stdclass();
$config->programPriv->scrum     = array('product', 'story', 'productplan', 'release', 'project', 'task', 'build', 'qa', 'bug', 'testcase', 'testsuite', 'testreport', 'caselib', 'doc', 'report', 'repo', 'svn', 'git', 'search', 'tree', 'file', 'jenkins', 'job', 'ci', 'branch');
$config->programPriv->waterfall = $config->programPriv->scrum + array('workestimation', 'durationestimation', 'budget', 'programplan', 'review', 'reviewissue', 'weekly', 'milestone', 'design', 'issue', 'risk', 'auditplan', 'nc', 'cm', 'pssp');
