<?php
/**
* The config file of zentaophp.  Don't modify this file directly, copy the item to my.php and change it.
*
* @copyright   Copyright 2009-2017 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
* @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Chunsheng Wang <chunsheng@cnezsoft.com>
* @package     config
* @version     $Id: zentaopms.php 5068 2017-06-20 15:35:22Z pengjx $
* @link        http://www.zentao.net
*/

/* Product common list. */
$tableEscape = $config->db->driver == 'dm' ? '"' : '`';

$config->productCommonList['zh-cn'][0] = '产品';
$config->productCommonList['zh-cn'][1] = '项目';
$config->productCommonList['zh-tw'][0] = '產品';
$config->productCommonList['zh-tw'][1] = '項目';
$config->productCommonList['en'][0]    = 'Product';
$config->productCommonList['en'][1]    = 'Project';
$config->productCommonList['de'][0]    = 'Produkt';
$config->productCommonList['de'][1]    = 'Projekt';
$config->productCommonList['fr'][0]    = 'Product';
$config->productCommonList['fr'][1]    = 'Project';
$config->productCommonList['vi'][0]    = 'Sản phẩm';
$config->productCommonList['vi'][1]    = 'Project';
$config->productCommonList['ru'][0]    = 'продукт';
$config->productCommonList['ru'][1]    = 'Проект';
$config->productCommonList['ja'][0]    = '製品';
$config->productCommonList['ja'][1]    = 'プロジェクト';
$config->productCommonList['es'][0]    = 'Productos';
$config->productCommonList['es'][1]    = 'Proyecto';
$config->productCommonList['pt'][0]    = 'produto';
$config->productCommonList['pt'][1]    = 'projecto';

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

$config->projectCommonList['ru'][0] = 'Проект';
$config->projectCommonList['ru'][1] = 'итерация';
$config->projectCommonList['ru'][2] = 'пробивание';

$config->projectCommonList['ja'][0] = 'プロジェクト';
$config->projectCommonList['ja'][1] = '反復';
$config->projectCommonList['ja'][2] = 'スパート';

$config->projectCommonList['es'][0] = 'Proyecto';
$config->projectCommonList['es'][1] = 'Iteración';
$config->projectCommonList['es'][2] = 'Sprint';

$config->projectCommonList['pt'][0] = 'Projecto';
$config->projectCommonList['pt'][1] = 'Iteração';
$config->projectCommonList['pt'][2] = 'Sprint';

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

$config->executionCommonList['ru'][0] = 'итерация';
$config->executionCommonList['ru'][1] = 'пробивание';
$config->executionCommonList['ru'][2] = ' этап';

$config->executionCommonList['ja'][0] = '反復';
$config->executionCommonList['ja'][1] = 'スパート';
$config->executionCommonList['ja'][2] = 'ステージ';

$config->executionCommonList['es'][0] = 'Iteración';
$config->executionCommonList['es'][1] = 'Sprint';
$config->executionCommonList['es'][2] = 'Fase';

$config->executionCommonList['pt'][0] = 'Iteração';
$config->executionCommonList['pt'][1] = 'Sprint';
$config->executionCommonList['pt'][2] = 'Fase';

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

$config->hourPointCommonList['ru'][0] = 'продолжительность рабочего времени';
$config->hourPointCommonList['ru'][1] = 'точка повести';
$config->hourPointCommonList['ru'][2] = 'функциональная точка';

$config->hourPointCommonList['ja'][0] = '工数';
$config->hourPointCommonList['ja'][1] = 'ストーリーポイント';
$config->hourPointCommonList['ja'][2] = 'きのうてん';

$config->hourPointCommonList['es'][0] = 'Horas de trabajo';
$config->hourPointCommonList['es'][1] = 'Punto de historia';
$config->hourPointCommonList['es'][2] = 'Punto de función';

$config->hourPointCommonList['pt'][0] = 'horas de trabalho';
$config->hourPointCommonList['pt'][1] = 'Ponto da história';
$config->hourPointCommonList['pt'][2] = 'Ponto de função';

$config->manualUrl['home'] = 'https://www.zentao.net/book/zentaopms/38.html?fullScreen=zentao';
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
$config->charsets['ru']['utf-8']    = 'UTF-8';
$config->charsets['ru']['GBK']      = 'GBK';
$config->charsets['ja']['utf-8']    = 'UTF-8';
$config->charsets['ja']['GBK']      = 'GBK';
$config->charsets['es']['utf-8']    = 'UTF-8';
$config->charsets['es']['GBK']      = 'GBK';
$config->charsets['pt']['utf-8']    = 'UTF-8';
$config->charsets['pt']['GBK']      = 'GBK';

$config->openMethods = array();
$config->openMethods[] = 'gitlab.webhook';
$config->openMethods[] = 'upgrade.ajaxupdatefile';
$config->openMethods[] = 'user.login';
$config->openMethods[] = 'user.logout';
$config->openMethods[] = 'user.deny';
$config->openMethods[] = 'user.reset';
$config->openMethods[] = 'user.forgetpassword';
$config->openMethods[] = 'user.refreshrandom';
$config->openMethods[] = 'user.resetpassword';
$config->openMethods[] = 'api.getsessionid';
$config->openMethods[] = 'misc.checktable';
$config->openMethods[] = 'misc.qrcode';
$config->openMethods[] = 'misc.about';
$config->openMethods[] = 'misc.checkupdate';
$config->openMethods[] = 'misc.ping';
$config->openMethods[] = 'misc.captcha';
$config->openMethods[] = 'misc.features';
$config->openMethods[] = 'sso.login';
$config->openMethods[] = 'sso.logout';
$config->openMethods[] = 'sso.bind';
$config->openMethods[] = 'sso.gettodolist';
$config->openMethods[] = 'file.read';
$config->openMethods[] = 'index.changelog';
$config->openMethods[] = 'my.preference';
$config->openMethods[] = 'my.changepassword';
$config->openMethods[] = 'my.profile';
$config->openMethods[] = 'my.settutorialconfig';
$config->openMethods[] = 'doc.selectlibtype';
$config->openMethods[] = 'sso.getfeishusso';
$config->openMethods[] = 'sso.feishuauthen';
$config->openMethods[] = 'sso.feishulogin';
$config->openMethods[] = 'kanban.importcard';
$config->openMethods[] = 'kanban.importplan';
$config->openMethods[] = 'kanban.importrelease';
$config->openMethods[] = 'kanban.importexecution';
$config->openMethods[] = 'kanban.importbuild';
$config->openMethods[] = 'kanban.importticket';
$config->openMethods[] = 'kanban.activatecard';
$config->openMethods[] = 'kanban.finishcard';
$config->openMethods[] = 'kanban.deleteobjectcard';
$config->openMethods[] = 'admin.ignore';
$config->openMethods[] = 'personnel.unbindwhitelist';
$config->openMethods[] = 'tree.viewhistory';
$config->openMethods[] = 'doc.createbasicinfo';
$config->openMethods[] = 'project.createguide';
$config->openMethods[] = 'task.editteam';
$config->openMethods[] = 'feedback.mergeproductmodule';
$config->openMethods[] = 'zanode.nodelist';
$config->openMethods[] = 'action.restoreStages';
$config->openMethods[] = 'testcase.getXmindImport';
$config->openMethods[] = 'testcase.showXMindImport';
$config->openMethods[] = 'testcase.saveXmindImport';

$config->openModules = array();
$config->openModules[] = 'install';
$config->openModules[] = 'upgrade';
$config->openModules[] = 'im';

/* Define the tables. */
define('TABLE_COMPANY',       $tableEscape . $config->db->prefix . 'company' . $tableEscape);
define('TABLE_DEPT',          $tableEscape . $config->db->prefix . 'dept' . $tableEscape);
define('TABLE_CONFIG',        $tableEscape . $config->db->prefix . 'config' . $tableEscape);
define('TABLE_USER',          $tableEscape . $config->db->prefix . 'user' . $tableEscape);
define('TABLE_TODO',          $tableEscape . $config->db->prefix . 'todo' . $tableEscape);
define('TABLE_GROUP',         $tableEscape . $config->db->prefix . 'group' . $tableEscape);
define('TABLE_GROUPPRIV',     $tableEscape . $config->db->prefix . 'grouppriv' . $tableEscape);
define('TABLE_USERGROUP',     $tableEscape . $config->db->prefix . 'usergroup' . $tableEscape);
define('TABLE_USERQUERY',     $tableEscape . $config->db->prefix . 'userquery' . $tableEscape);
define('TABLE_USERCONTACT',   $tableEscape . $config->db->prefix . 'usercontact' . $tableEscape);
define('TABLE_USERVIEW',      $tableEscape . $config->db->prefix . 'userview' . $tableEscape);

define('TABLE_BUG',           $tableEscape . $config->db->prefix . 'bug' . $tableEscape);
define('TABLE_CASE',          $tableEscape . $config->db->prefix . 'case' . $tableEscape);
define('TABLE_CASESTEP',      $tableEscape . $config->db->prefix . 'casestep' . $tableEscape);
define('TABLE_TESTTASK',      $tableEscape . $config->db->prefix . 'testtask' . $tableEscape);
define('TABLE_TESTRUN',       $tableEscape . $config->db->prefix . 'testrun' . $tableEscape);
define('TABLE_TESTRESULT',    $tableEscape . $config->db->prefix . 'testresult' . $tableEscape);
define('TABLE_USERTPL',       $tableEscape . $config->db->prefix . 'usertpl' . $tableEscape);
define('TABLE_ZAHOST',        $tableEscape . $config->db->prefix . 'host' . $tableEscape);
define('TABLE_IMAGE',         $tableEscape . $config->db->prefix . 'image' . $tableEscape);
define('TABLE_AUTOMATION',    $tableEscape . $config->db->prefix . 'automation' . $tableEscape);

if(!defined('TABLE_ASSET'))  define('TABLE_ASSET', $tableEscape . $config->db->prefix . 'asset' . $tableEscape);

define('TABLE_PRODUCT',       $tableEscape . $config->db->prefix . 'product' . $tableEscape);
define('TABLE_BRANCH',        $tableEscape . $config->db->prefix . 'branch' . $tableEscape);
define('TABLE_EXPECT',        $tableEscape . $config->db->prefix . 'expect' . $tableEscape);
define('TABLE_STAGE',         $tableEscape . $config->db->prefix . 'stage' . $tableEscape);
define('TABLE_STAKEHOLDER',   $tableEscape . $config->db->prefix . 'stakeholder' . $tableEscape);
define('TABLE_STORY',         $tableEscape . $config->db->prefix . 'story' . $tableEscape);
define('TABLE_STORYSPEC',     $tableEscape . $config->db->prefix . 'storyspec' . $tableEscape);
define('TABLE_STORYREVIEW',   $tableEscape . $config->db->prefix . 'storyreview' . $tableEscape);
define('TABLE_STORYSTAGE',    $tableEscape . $config->db->prefix . 'storystage' . $tableEscape);
define('TABLE_STORYESTIMATE', $tableEscape . $config->db->prefix . 'storyestimate' . $tableEscape);
define('TABLE_PRODUCTPLAN',   $tableEscape . $config->db->prefix . 'productplan' . $tableEscape);
define('TABLE_PLANSTORY',     $tableEscape . $config->db->prefix . 'planstory' . $tableEscape);
define('TABLE_RELEASE',       $tableEscape . $config->db->prefix . 'release' . $tableEscape);

define('TABLE_PROGRAM',       $tableEscape . $config->db->prefix . 'project' . $tableEscape);
define('TABLE_PROJECT',       $tableEscape . $config->db->prefix . 'project' . $tableEscape);
define('TABLE_EXECUTION',     $tableEscape . $config->db->prefix . 'project' . $tableEscape);
define('TABLE_TASK',          $tableEscape . $config->db->prefix . 'task' . $tableEscape);
define('TABLE_TASKSPEC',      $tableEscape . $config->db->prefix . 'taskspec' . $tableEscape);
define('TABLE_TASKTEAM',      $tableEscape . $config->db->prefix . 'taskteam' . $tableEscape);
define('TABLE_TEAM',          $tableEscape . $config->db->prefix . 'team' . $tableEscape);
define('TABLE_PROJECTADMIN',  $tableEscape . $config->db->prefix . 'projectadmin' . $tableEscape);
define('TABLE_PROJECTPRODUCT',$tableEscape . $config->db->prefix . 'projectproduct' . $tableEscape);
define('TABLE_PROJECTSTORY',  $tableEscape . $config->db->prefix . 'projectstory' . $tableEscape);
define('TABLE_PROJECTCASE',   $tableEscape . $config->db->prefix . 'projectcase' . $tableEscape);
define('TABLE_TASKESTIMATE',  $tableEscape . $config->db->prefix . 'taskestimate' . $tableEscape);
define('TABLE_EFFORT',        $tableEscape . $config->db->prefix . 'effort' . $tableEscape);
define('TABLE_BURN',          $tableEscape . $config->db->prefix . 'burn' . $tableEscape);
define('TABLE_CFD',           $tableEscape . $config->db->prefix . 'cfd' . $tableEscape);
define('TABLE_BUILD',         $tableEscape . $config->db->prefix . 'build' . $tableEscape);
define('TABLE_ACL',           $tableEscape . $config->db->prefix . 'acl' . $tableEscape);

define('TABLE_DESIGN',          $tableEscape . $config->db->prefix . 'design' . $tableEscape);
define('TABLE_DESIGNSPEC',      $tableEscape . $config->db->prefix . 'designspec' . $tableEscape);
define('TABLE_DOCLIB',          $tableEscape . $config->db->prefix . 'doclib' . $tableEscape);
define('TABLE_DOC',             $tableEscape . $config->db->prefix . 'doc' . $tableEscape);
define('TABLE_API',             $tableEscape . $config->db->prefix . 'api' . $tableEscape);
define('TABLE_API_SPEC',        $tableEscape . $config->db->prefix . 'apispec' . $tableEscape);
define('TABLE_APISTRUCT',       $tableEscape . $config->db->prefix . 'apistruct' . $tableEscape);
define('TABLE_APISTRUCT_SPEC',  $tableEscape . $config->db->prefix . 'apistruct_spec' . $tableEscape);
define('TABLE_API_LIB_RELEASE', $tableEscape . $config->db->prefix . 'api_lib_release' . $tableEscape);

define('TABLE_MODULE',        $tableEscape . $config->db->prefix . 'module' . $tableEscape);
define('TABLE_ACTION',        $tableEscape . $config->db->prefix . 'action' . $tableEscape);
define('TABLE_FILE',          $tableEscape . $config->db->prefix . 'file' . $tableEscape);
define('TABLE_HOLIDAY',       $tableEscape . $config->db->prefix . 'holiday' . $tableEscape);
define('TABLE_HISTORY',       $tableEscape . $config->db->prefix . 'history' . $tableEscape);
define('TABLE_EXTENSION',     $tableEscape . $config->db->prefix . 'extension' . $tableEscape);
define('TABLE_CRON',          $tableEscape . $config->db->prefix . 'cron' . $tableEscape);
define('TABLE_BLOCK',         $tableEscape . $config->db->prefix . 'block' . $tableEscape);
define('TABLE_DOCACTION',     $tableEscape . $config->db->prefix . 'docaction' . $tableEscape);
define('TABLE_DOCCONTENT',    $tableEscape . $config->db->prefix . 'doccontent' . $tableEscape);
define('TABLE_TESTSUITE',     $tableEscape . $config->db->prefix . 'testsuite' . $tableEscape);
define('TABLE_SUITECASE',     $tableEscape . $config->db->prefix . 'suitecase' . $tableEscape);
define('TABLE_TESTREPORT',    $tableEscape . $config->db->prefix . 'testreport' . $tableEscape);

define('TABLE_ENTRY',         $tableEscape . $config->db->prefix . 'entry' . $tableEscape);
define('TABLE_WEEKLYREPORT',  $tableEscape . $config->db->prefix . 'weeklyreport' . $tableEscape);
define('TABLE_WEBHOOK',       $tableEscape . $config->db->prefix . 'webhook' . $tableEscape);
define('TABLE_LOG',           $tableEscape . $config->db->prefix . 'log' . $tableEscape);
define('TABLE_SCORE',         $tableEscape . $config->db->prefix . 'score' . $tableEscape);
define('TABLE_NOTIFY',        $tableEscape . $config->db->prefix . 'notify' . $tableEscape);
define('TABLE_OAUTH',         $tableEscape . $config->db->prefix . 'oauth' . $tableEscape);
define('TABLE_PIPELINE',      $tableEscape . $config->db->prefix . 'pipeline' . $tableEscape);
define('TABLE_JOB',           $tableEscape . $config->db->prefix . 'job' . $tableEscape);
define('TABLE_COMPILE',       $tableEscape . $config->db->prefix . 'compile' . $tableEscape);
define('TABLE_MR',            $tableEscape . $config->db->prefix . 'mr' . $tableEscape);
define('TABLE_MRAPPROVAL',    $tableEscape . $config->db->prefix . 'mrapproval' . $tableEscape);

define('TABLE_REPO',         $tableEscape . $config->db->prefix . 'repo' . $tableEscape);
define('TABLE_RELATION',     $tableEscape . $config->db->prefix . 'relation' . $tableEscape);
define('TABLE_REPOHISTORY',  $tableEscape . $config->db->prefix . 'repohistory' . $tableEscape);
define('TABLE_REPOFILES',    $tableEscape . $config->db->prefix . 'repofiles' . $tableEscape);
define('TABLE_REPOBRANCH',   $tableEscape . $config->db->prefix . 'repobranch' . $tableEscape);
define('TABLE_KANBAN',       $tableEscape . $config->db->prefix . 'kanban' . $tableEscape);
define('TABLE_KANBANSPACE',  $tableEscape . $config->db->prefix . 'kanbanspace' . $tableEscape);
define('TABLE_KANBANREGION', $tableEscape . $config->db->prefix . 'kanbanregion' . $tableEscape);
define('TABLE_KANBANLANE',   $tableEscape . $config->db->prefix . 'kanbanlane' . $tableEscape);
define('TABLE_KANBANCOLUMN', $tableEscape . $config->db->prefix . 'kanbancolumn' . $tableEscape);
define('TABLE_KANBANORDER',  $tableEscape . $config->db->prefix . 'kanbanorder' . $tableEscape);
define('TABLE_KANBANGROUP',  $tableEscape . $config->db->prefix . 'kanbangroup' . $tableEscape);
define('TABLE_KANBANCARD',   $tableEscape . $config->db->prefix . 'kanbancard' . $tableEscape);
define('TABLE_KANBANCELL',   $tableEscape . $config->db->prefix . 'kanbancell' . $tableEscape);
if(!defined('TABLE_LANG'))        define('TABLE_LANG', $tableEscape . $config->db->prefix . 'lang' . $tableEscape);
if(!defined('TABLE_PROJECTSPEC')) define('TABLE_PROJECTSPEC', $tableEscape . $config->db->prefix . 'projectspec' . $tableEscape);

if(!defined('TABLE_SEARCHINDEX')) define('TABLE_SEARCHINDEX', $config->db->prefix . 'searchindex');
if(!defined('TABLE_SEARCHDICT'))  define('TABLE_SEARCHDICT',  $config->db->prefix . 'searchdict');

define('TABLE_SCREEN',    $tableEscape . $config->db->prefix . 'screen' . $tableEscape);
define('TABLE_CHART',     $tableEscape . $config->db->prefix . 'chart' . $tableEscape);
define('TABLE_PIVOT',     $tableEscape . $config->db->prefix . 'pivot' . $tableEscape);
define('TABLE_DASHBOARD', $tableEscape . $config->db->prefix . 'dashboard' . $tableEscape);
define('TABLE_DATASET',   $tableEscape . $config->db->prefix . 'dataset' . $tableEscape);
define('TABLE_DATAVIEW',  $tableEscape . $config->db->prefix . 'dataview' . $tableEscape);
define('TABLE_DIMENSION', $tableEscape . $config->db->prefix . 'dimension' . $tableEscape);
define('TABLE_SCENE',    $tableEscape . $config->db->prefix . 'scene' . $tableEscape);
define('VIEW_SCENECASE', '`ztv_scenecase' . $tableEscape);

define('CHANGEVALUE', 100000000);

$config->objectTables['product']      = TABLE_PRODUCT;
$config->objectTables['productplan']  = TABLE_PRODUCTPLAN;
$config->objectTables['story']        = TABLE_STORY;
$config->objectTables['requirement']  = TABLE_STORY;
$config->objectTables['release']      = TABLE_RELEASE;
$config->objectTables['program']      = TABLE_PROJECT;
$config->objectTables['project']      = TABLE_PROJECT;
$config->objectTables['execution']    = TABLE_PROJECT;
$config->objectTables['task']         = TABLE_TASK;
$config->objectTables['build']        = TABLE_BUILD;
$config->objectTables['bug']          = TABLE_BUG;
$config->objectTables['case']         = TABLE_CASE;
$config->objectTables['testcase']     = TABLE_CASE;
$config->objectTables['testtask']     = TABLE_TESTTASK;
$config->objectTables['testsuite']    = TABLE_TESTSUITE;
$config->objectTables['testreport']   = TABLE_TESTREPORT;
$config->objectTables['user']         = TABLE_USER;
$config->objectTables['api']          = TABLE_API;
$config->objectTables['doc']          = TABLE_DOC;
$config->objectTables['doclib']       = TABLE_DOCLIB;
$config->objectTables['todo']         = TABLE_TODO;
$config->objectTables['custom']       = TABLE_LANG;
$config->objectTables['branch']       = TABLE_BRANCH;
$config->objectTables['module']       = TABLE_MODULE;
$config->objectTables['caselib']      = TABLE_TESTSUITE;
$config->objectTables['entry']        = TABLE_ENTRY;
$config->objectTables['webhook']      = TABLE_WEBHOOK;
$config->objectTables['stakeholder']  = TABLE_STAKEHOLDER;
$config->objectTables['job']          = TABLE_JOB;
$config->objectTables['team']         = TABLE_TEAM;
$config->objectTables['pipeline']     = TABLE_PIPELINE;
$config->objectTables['mr']           = TABLE_MR;
$config->objectTables['kanban']       = TABLE_KANBAN;
$config->objectTables['kanbanspace']  = TABLE_KANBANSPACE;
$config->objectTables['kanbanregion'] = TABLE_KANBANREGION;
$config->objectTables['kanbancolumn'] = TABLE_KANBANCOLUMN;
$config->objectTables['kanbanlane']   = TABLE_KANBANLANE;
$config->objectTables['kanbanorder']  = TABLE_KANBANORDER;
$config->objectTables['kanbangroup']  = TABLE_KANBANGROUP;
$config->objectTables['kanbancard']   = TABLE_KANBANCARD;
$config->objectTables['sonarqube']    = TABLE_PIPELINE;
$config->objectTables['gitea']        = TABLE_PIPELINE;
$config->objectTables['gogs']         = TABLE_PIPELINE;
$config->objectTables['gitlab']       = TABLE_PIPELINE;
$config->objectTables['jebkins']      = TABLE_PIPELINE;
$config->objectTables['stage']        = TABLE_STAGE;
$config->objectTables['apistruct']    = TABLE_APISTRUCT;
$config->objectTables['repo']         = TABLE_REPO;
$config->objectTables['dataview']     = TABLE_DATAVIEW;
$config->objectTables['zahost']       = TABLE_ZAHOST;
$config->objectTables['zanode']       = TABLE_ZAHOST;
$config->objectTables['automation']   = TABLE_AUTOMATION;
$config->objectTables['stepResult']   = TABLE_TESTRUN;
$config->objectTables['scene']        = TABLE_SCENE;

$config->newFeatures      = array('introduction', 'tutorial', 'youngBlueTheme', 'visions');
$config->disabledFeatures = '';
$config->closedFeatures   = '';

$config->pipelineTypeList = array('gitlab', 'gogs', 'gitea', 'jenkins', 'sonarqube');

/* Program privs.*/
$config->programPriv = new stdclass();
$config->programPriv->noSprint      = array('task', 'story', 'tree', 'project', 'execution', 'build', 'bug', 'testcase', 'testreport', 'doc', 'repo', 'stakeholder', 'projectrelease', 'requirement');
$config->programPriv->scrum         = array('story', 'requirement', 'productplan', 'tree', 'projectplan', 'projectstory', 'projectrelease', 'project', 'execution', 'build', 'bug', 'testcase', 'testreport', 'doc', 'repo', 'meeting', 'stakeholder', 'testtask');
$config->programPriv->waterfall     = array_merge($config->programPriv->scrum, array('workestimation', 'durationestimation', 'budget', 'programplan', 'review', 'reviewissue', 'weekly', 'cm', 'milestone', 'design', 'issue', 'risk', 'opportunity', 'measrecord', 'auditplan', 'trainplan', 'gapanalysis', 'pssp', 'researchplan', 'researchreport'));
$config->programPriv->agileplus     = $config->programPriv->scrum;
$config->programPriv->waterfallplus = $config->programPriv->waterfall;

$config->safeFileTimeout  = 3600;
$config->waterfallModules = array('workestimation', 'durationestimation', 'budget', 'programplan', 'review', 'reviewissue', 'weekly', 'cm', 'milestone', 'design', 'opportunity', 'auditplan', 'trainplan', 'gapanalysis', 'pssp', 'researchplan', 'researchreport');

$config->showMainMenu = true;
$config->maxPriValue  = '256';

$config->importWhiteList = array('user', 'task', 'story', 'bug', 'testcase', 'feedback', 'ticket');

$config->featureGroup = new stdclass();
$config->featureGroup->my            = array('score');
$config->featureGroup->product       = array('roadmap', 'track', 'UR');
$config->featureGroup->scrum         = array();
$config->featureGroup->waterfall     = array();
$config->featureGroup->agileplus     = array();
$config->featureGroup->waterfallplus = array();
$config->featureGroup->assetlib      = array();
$config->featureGroup->other         = array('devops', 'kanban');

$config->bi = new stdclass();
$config->bi->pickerHeight = 150;
