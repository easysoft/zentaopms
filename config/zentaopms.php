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

/* Framework settings. */
$config->framework->autoRepairTable = true;
$config->framework->autoLang        = false;
$config->framework->filterCSRF      = false;

/* Upload settings. */
$config->allowedTags = '<p><span><h1><h2><h3><h4><h5><em><u><strong><br><ol><ul><li><img><a><b><font><hr><pre><div><table><td><th><tr><tbody><embed><style>';
$config->accountRule = '|^[a-zA-Z0-9_]{1}[a-zA-Z0-9_\.]{1,}[a-zA-Z0-9_]{1}$|';
$config->checkVersion = true;              // Auto check for new version or not.

/* Set the wide window size and timeout(ms) and duplicate interval time(s). */
$config->wideSize      = 1400;
$config->timeout       = 30000;
$config->duplicateTime = 30;
$config->maxCount      = 500;
$config->moreLinks     = array();

/* Product common list. */
$config->productCommonList['zh-cn'][0] = '产品';
$config->productCommonList['zh-cn'][1] = '项目';

$config->productCommonList['zh-tw'][0] = '產品';
$config->productCommonList['zh-tw'][1] = '項目';

$config->productCommonList['en'][0] = 'Product';
$config->productCommonList['en'][1] = 'Project';

$config->productCommonList['de'][0] = 'Produkt';
$config->productCommonList['de'][1] = 'Projekt';

$config->productCommonList['fr'][0] = 'Product';
$config->productCommonList['fr'][1] = 'Projet';

$config->productCommonList['vi'][0] = 'Sản phẩm';
$config->productCommonList['vi'][1] = 'Dự án';

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

$config->projectCommonList['de'][0] = 'Projekt';
$config->projectCommonList['de'][1] = 'Iteration';
$config->projectCommonList['de'][2] = 'Sprint';

$config->projectCommonList['fr'][0] = 'Projet';
$config->projectCommonList['fr'][1] = 'Iteration';
$config->projectCommonList['fr'][2] = 'Sprint';

$config->projectCommonList['vi'][0] = 'Dự án';
$config->projectCommonList['vi'][1] = 'Lặp lại';
$config->projectCommonList['vi'][2] = 'Sprint';

/* Story common list. */
$config->storyCommonList['zh-cn'][0] = '需求';
$config->storyCommonList['zh-cn'][1] = '故事';

$config->storyCommonList['zh-tw'][0] = '需求';
$config->storyCommonList['zh-tw'][1] = '故事';

$config->storyCommonList['en'][0] = 'Story';
$config->storyCommonList['en'][1] = 'Story';

$config->storyCommonList['de'][0] = 'Story';
$config->storyCommonList['de'][1] = 'Story';

$config->storyCommonList['fr'][0] = 'Story';
$config->storyCommonList['fr'][1] = 'Story';

$config->storyCommonList['vi'][0] = 'Câu chuyện';
$config->storyCommonList['vi'][1] = 'Câu chuyện';

/* Story common list. */
$config->hourPointCommonList['zh-cn'][0] = '工时';
$config->hourPointCommonList['zh-cn'][1] = '故事点';
$config->hourPointCommonList['zh-cn'][2] = '功能点';

$config->hourPointCommonList['zh-tw'][0] = '工时';
$config->hourPointCommonList['zh-tw'][1] = '故事点';
$config->hourPointCommonList['zh-tw'][2] = '功能点';

$config->hourPointCommonList['en'][0] = 'hour';
$config->hourPointCommonList['en'][1] = 'story point';
$config->hourPointCommonList['en'][2] = 'function point';

$config->hourPointCommonList['de'][0] = 'hour';
$config->hourPointCommonList['de'][1] = 'story point';
$config->hourPointCommonList['de'][2] = 'function point';

$config->hourPointCommonList['fr'][0] = 'hour';
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

/* IP white list settings.*/
$config->ipWhiteList = '*';

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
define('TABLE_STORY',         '`' . $config->db->prefix . 'story`');
define('TABLE_STORYSPEC',     '`' . $config->db->prefix . 'storyspec`');
define('TABLE_STORYSTAGE',    '`' . $config->db->prefix . 'storystage`');
define('TABLE_PRODUCTPLAN',   '`' . $config->db->prefix . 'productplan`');
define('TABLE_RELEASE',       '`' . $config->db->prefix . 'release`');

define('TABLE_PROJECT',       '`' . $config->db->prefix . 'project`');
define('TABLE_TASK',          '`' . $config->db->prefix . 'task`');
define('TABLE_TEAM',          '`' . $config->db->prefix . 'team`');
define('TABLE_PROJECTPRODUCT','`' . $config->db->prefix . 'projectproduct`');
define('TABLE_PROJECTSTORY',  '`' . $config->db->prefix . 'projectstory`');
define('TABLE_TASKESTIMATE',  '`' . $config->db->prefix . 'taskestimate`');
define('TABLE_EFFORT',        '`' . $config->db->prefix . 'effort`');
define('TABLE_BURN',          '`' . $config->db->prefix . 'burn`');
define('TABLE_BUILD',         '`' . $config->db->prefix . 'build`');

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
if(!defined('TABLE_LANG')) define('TABLE_LANG', '`' . $config->db->prefix . 'lang`');

$config->objectTables['product']     = TABLE_PRODUCT;
$config->objectTables['story']       = TABLE_STORY;
$config->objectTables['productplan'] = TABLE_PRODUCTPLAN;
$config->objectTables['release']     = TABLE_RELEASE;
$config->objectTables['project']     = TABLE_PROJECT;
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
