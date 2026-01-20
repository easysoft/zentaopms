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

$config->contactUs['phone']  = '4006-8899-23';
$config->contactUs['email']  = 'co@zentao.net';
$config->contactUs['qq']     = '1492153927';
$config->contactUs['wechat'] = '13730922971';

/* Product common list. */
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
$config->storyCommonList['zh-cn']['epic']        = '业务需求';
$config->storyCommonList['zh-cn']['requirement'] = '用户需求';
$config->storyCommonList['zh-cn']['story']       = '软件需求';

$config->storyCommonList['zh-tw']['epic']        = '業務需求';
$config->storyCommonList['zh-tw']['requirement'] = '用戶需求';
$config->storyCommonList['zh-tw']['story']       = '軟件需求';

$config->storyCommonList['en']['epic']        = 'Epic';
$config->storyCommonList['en']['requirement'] = 'Requirement';
$config->storyCommonList['en']['story']       = 'Story';

$config->storyCommonList['de']['epic']        = 'Epic';
$config->storyCommonList['de']['requirement'] = 'Requirement';
$config->storyCommonList['de']['story']       = 'Story';

$config->storyCommonList['fr']['epic']        = 'Epic';
$config->storyCommonList['fr']['requirement'] = 'Requirement';
$config->storyCommonList['fr']['story']       = 'Story';

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

/* 未登录用户可以使用的方法。The methods that can be used by the unlogged users. */
$config->openMethods = array();
$config->openMethods[] = 'api.getsessionid';
$config->openMethods[] = 'misc.captcha';
$config->openMethods[] = 'misc.checkupdate';
$config->openMethods[] = 'misc.checknetconnect';
$config->openMethods[] = 'misc.ajaxsendevent';
$config->openMethods[] = 'misc.installevent';
$config->openMethods[] = 'sso.bind';
$config->openMethods[] = 'sso.feishuauthen';
$config->openMethods[] = 'sso.feishulogin';
$config->openMethods[] = 'sso.getfeishusso';
$config->openMethods[] = 'sso.gettodolist';
$config->openMethods[] = 'sso.login';
$config->openMethods[] = 'sso.logout';
$config->openMethods[] = 'upgrade.ajaxupdatefile';
$config->openMethods[] = 'upgrade.safedelete';
$config->openMethods[] = 'user.forgetpassword';
$config->openMethods[] = 'user.login';
$config->openMethods[] = 'user.refreshrandom';
$config->openMethods[] = 'user.reset';
$config->openMethods[] = 'user.resetpassword';
$config->openMethods[] = 'admin.register';
$config->openMethods[] = 'admin.getcaptcha';
$config->openMethods[] = 'admin.sendcode';
$config->openMethods[] = 'admin.planmodal';
$config->openMethods[] = 'im.authorize';

/* 登录用户可以使用的方法。The methods that can be used by the logged users. */
$config->logonMethods = array();
$config->logonMethods[] = 'action.commentzin';
$config->logonMethods[] = 'action.editcommentzin';
$config->logonMethods[] = 'action.restorestages';
$config->logonMethods[] = 'admin.ignore';
$config->logonMethods[] = 'caselib.index';
$config->logonMethods[] = 'company.index';
$config->logonMethods[] = 'cron.consume';
$config->logonMethods[] = 'cron.schedule';
$config->logonMethods[] = 'custom.index';
$config->logonMethods[] = 'doc.createbasicinfo';
$config->logonMethods[] = 'doc.selectlibtype';
$config->logonMethods[] = 'doc.uploaddocs';
$config->logonMethods[] = 'doc.setdocbasic';
$config->logonMethods[] = 'doc.commoneditaction';
$config->logonMethods[] = 'doc.commondeleteaction';
$config->logonMethods[] = 'doc.buildzentaolist';
$config->logonMethods[] = 'doc.zentaolist';
$config->logonMethods[] = 'doc.lastviewedspacehome';
$config->logonMethods[] = 'doc.lastviewedspace';
$config->logonMethods[] = 'doc.lastviewedlib';

$config->logonMethods[] = 'execution.browse';
$config->logonMethods[] = 'feedback.mergeproductmodule';
$config->logonMethods[] = 'file.buildoldform';
$config->logonMethods[] = 'file.read';
$config->logonMethods[] = 'gitea.binduser';
$config->logonMethods[] = 'gitea.create';
$config->logonMethods[] = 'gitea.edit';
$config->logonMethods[] = 'gitlab.binduser';
$config->logonMethods[] = 'gitlab.browsebranch';
$config->logonMethods[] = 'gitlab.browsegroup';
$config->logonMethods[] = 'gitlab.browseproject';
$config->logonMethods[] = 'gitlab.browsetag';
$config->logonMethods[] = 'gitlab.browseuser';
$config->logonMethods[] = 'gitlab.create';
$config->logonMethods[] = 'gitlab.createbranch';
$config->logonMethods[] = 'gitlab.creategroup';
$config->logonMethods[] = 'gitlab.createproject';
$config->logonMethods[] = 'gitlab.createtag';
$config->logonMethods[] = 'gitlab.createuser';
$config->logonMethods[] = 'gitlab.createwebhook';
$config->logonMethods[] = 'gitlab.deletegroup';
$config->logonMethods[] = 'gitlab.deleteproject';
$config->logonMethods[] = 'gitlab.deletetag';
$config->logonMethods[] = 'gitlab.deleteuser';
$config->logonMethods[] = 'gitlab.edit';
$config->logonMethods[] = 'gitlab.editgroup';
$config->logonMethods[] = 'gitlab.editproject';
$config->logonMethods[] = 'gitlab.edituser';
$config->logonMethods[] = 'gitlab.importissue';
$config->logonMethods[] = 'gitlab.managebranchpriv';
$config->logonMethods[] = 'gitlab.managegroupmembers';
$config->logonMethods[] = 'gitlab.manageprojectmembers';
$config->logonMethods[] = 'gitlab.managetagpriv';
$config->logonMethods[] = 'gitlab.webhook';
$config->logonMethods[] = 'gitlab.webhook';
$config->logonMethods[] = 'gogs.binduser';
$config->logonMethods[] = 'gogs.create';
$config->logonMethods[] = 'gogs.edit';
$config->logonMethods[] = 'index.app';
$config->logonMethods[] = 'index.changelog';
$config->logonMethods[] = 'instance.binduser';
$config->logonMethods[] = 'instance.createexternalapp';
$config->logonMethods[] = 'instance.deleteexternalapp';
$config->logonMethods[] = 'instance.edit';
$config->logonMethods[] = 'instance.editexternalapp';
$config->logonMethods[] = 'instance.install';
$config->logonMethods[] = 'instance.setting';
$config->logonMethods[] = 'instance.upgrade';
$config->logonMethods[] = 'instance.view';
$config->logonMethods[] = 'instance.backuplist';
$config->logonMethods[] = 'instance.visit';
$config->logonMethods[] = 'instance.manualbackup';
$config->logonMethods[] = 'instance.backupsettings';
$config->logonMethods[] = 'instance.autobackup';
$config->logonMethods[] = 'instance.showlogs';
$config->logonMethods[] = 'instance.events';
$config->logonMethods[] = 'instance.showevents';
$config->logonMethods[] = 'jenkins.create';
$config->logonMethods[] = 'jenkins.edit';
$config->logonMethods[] = 'kanban.activatecard';
$config->logonMethods[] = 'kanban.deleteobjectcard';
$config->logonMethods[] = 'kanban.finishcard';
$config->logonMethods[] = 'kanban.importbuild';
$config->logonMethods[] = 'kanban.importcard';
$config->logonMethods[] = 'kanban.importexecution';
$config->logonMethods[] = 'kanban.importplan';
$config->logonMethods[] = 'kanban.importrelease';
$config->logonMethods[] = 'kanban.importticket';
$config->logonMethods[] = 'metric.recalculatesetting';
$config->logonMethods[] = 'misc.about';
$config->logonMethods[] = 'misc.features';
$config->logonMethods[] = 'misc.ping';
$config->logonMethods[] = 'misc.qrcode';
$config->logonMethods[] = 'my.changepassword';
$config->logonMethods[] = 'my.preference';
$config->logonMethods[] = 'my.profile';
$config->logonMethods[] = 'my.settutorialconfig';
$config->logonMethods[] = 'personnel.unbindwhitelist';
$config->logonMethods[] = 'project.createguide';
$config->logonMethods[] = 'pivot.drillmodal';
$config->logonMethods[] = 'pivot.versions';
$config->logonMethods[] = 'search.buildzinform';
$config->logonMethods[] = 'search.buildzinquery';
$config->logonMethods[] = 'search.deletezinquery';
$config->logonMethods[] = 'search.savezinquery';
$config->logonMethods[] = 'sonarqube.browseissue';
$config->logonMethods[] = 'sonarqube.browseproject';
$config->logonMethods[] = 'sonarqube.create';
$config->logonMethods[] = 'sonarqube.createproject';
$config->logonMethods[] = 'sonarqube.deleteproject';
$config->logonMethods[] = 'sonarqube.edit';
$config->logonMethods[] = 'sonarqube.execjob';
$config->logonMethods[] = 'sonarqube.reportview';
$config->logonMethods[] = 'space.binduser';
$config->logonMethods[] = 'space.createapplication';
$config->logonMethods[] = 'space.edit';
$config->logonMethods[] = 'space.getstoreappinfo';
$config->logonMethods[] = 'story.storyview';
$config->logonMethods[] = 'system.editdomain';
$config->logonMethods[] = 'task.editteam';
$config->logonMethods[] = 'testcase.getxmindimport';
$config->logonMethods[] = 'testcase.savexmindimport';
$config->logonMethods[] = 'testcase.showimport';
$config->logonMethods[] = 'testcase.showxmindimport';
$config->logonMethods[] = 'testsuite.index';
$config->logonMethods[] = 'tree.viewhistory';
$config->logonMethods[] = 'user.cropavatar';
$config->logonMethods[] = 'user.deny';
$config->logonMethods[] = 'user.logout';
$config->logonMethods[] = 'zanode.nodelist';
$config->logonMethods[] = 'screen.viewold';
$config->logonMethods[] = 'system.backupview';
$config->logonMethods[] = 'screen.staticdataold';
$config->logonMethods[] = 'stage.updateorder';

$config->openModules = array();
$config->openModules[] = 'install';
$config->openModules[] = 'upgrade';

/* Define the tables. */
define('TABLE_AUTOCACHE',     '`' . $config->db->prefix . 'autocache`');
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
define('TABLE_CASESPEC',      '`' . $config->db->prefix . 'casespec`');
define('TABLE_TESTTASK',      '`' . $config->db->prefix . 'testtask`');
define('TABLE_TESTRUN',       '`' . $config->db->prefix . 'testrun`');
define('TABLE_TESTRESULT',    '`' . $config->db->prefix . 'testresult`');
define('TABLE_USERTPL',       '`' . $config->db->prefix . 'usertpl`');
define('TABLE_ZAHOST',        '`' . $config->db->prefix . 'host`');
define('TABLE_IMAGE',         '`' . $config->db->prefix . 'image`');
define('TABLE_AUTOMATION',    '`' . $config->db->prefix . 'automation`');

if(!defined('TABLE_ASSET'))  define('TABLE_ASSET', '`' . $config->db->prefix . 'asset`');

define('TABLE_PRODUCT',        '`' . $config->db->prefix . 'product`');
define('TABLE_BRANCH',         '`' . $config->db->prefix . 'branch`');
define('TABLE_EXPECT',         '`' . $config->db->prefix . 'expect`');
define('TABLE_STAGE',          '`' . $config->db->prefix . 'stage`');
define('TABLE_STAKEHOLDER',    '`' . $config->db->prefix . 'stakeholder`');
define('TABLE_STORY',          '`' . $config->db->prefix . 'story`');
define('TABLE_STORYSPEC',      '`' . $config->db->prefix . 'storyspec`');
define('TABLE_STORYREVIEW',    '`' . $config->db->prefix . 'storyreview`');
define('TABLE_STORYSTAGE',     '`' . $config->db->prefix . 'storystage`');
define('TABLE_STORYESTIMATE',  '`' . $config->db->prefix . 'storyestimate`');
define('TABLE_STORYGRADE',     '`' . $config->db->prefix . 'storygrade`');
define('TABLE_PRODUCTPLAN',    '`' . $config->db->prefix . 'productplan`');
define('TABLE_PLANSTORY',      '`' . $config->db->prefix . 'planstory`');
define('TABLE_RELEASE',        '`' . $config->db->prefix . 'release`');
define('TABLE_RELEASERELATED', '`' . $config->db->prefix . 'releaserelated`');

define('TABLE_PROGRAM',           '`' . $config->db->prefix . 'project`');
define('TABLE_PROJECT',           '`' . $config->db->prefix . 'project`');
define('TABLE_EXECUTION',         '`' . $config->db->prefix . 'project`');
define('TABLE_TASK',              '`' . $config->db->prefix . 'task`');
define('TABLE_TASKSPEC',          '`' . $config->db->prefix . 'taskspec`');
define('TABLE_TASKTEAM',          '`' . $config->db->prefix . 'taskteam`');
define('TABLE_TEAM',              '`' . $config->db->prefix . 'team`');
define('TABLE_PROJECTADMIN',      '`' . $config->db->prefix . 'projectadmin`');
define('TABLE_PROJECTPRODUCT',    '`' . $config->db->prefix . 'projectproduct`');
define('TABLE_PROJECTDELIVERABLE','`' . $config->db->prefix . 'projectdeliverable`');
define('TABLE_PROJECTSTORY',      '`' . $config->db->prefix . 'projectstory`');
define('TABLE_PROJECTCASE',       '`' . $config->db->prefix . 'projectcase`');
define('TABLE_PROJECTCHANGE',     '`' . $config->db->prefix . 'projectchange`');
define('TABLE_TASKESTIMATE',      '`' . $config->db->prefix . 'taskestimate`');
define('TABLE_EFFORT',            '`' . $config->db->prefix . 'effort`');
define('TABLE_BURN',              '`' . $config->db->prefix . 'burn`');
define('TABLE_CFD',               '`' . $config->db->prefix . 'cfd`');
define('TABLE_BUILD',             '`' . $config->db->prefix . 'build`');
define('TABLE_ACL',               '`' . $config->db->prefix . 'acl`');

define('TABLE_DESIGN',          '`' . $config->db->prefix . 'design`');
define('TABLE_DESIGNSPEC',      '`' . $config->db->prefix . 'designspec`');
define('TABLE_DELIVERABLE',     '`' . $config->db->prefix . 'deliverable`');
define('TABLE_DELIVERABLESTAGE','`' . $config->db->prefix . 'deliverablestage`');
define('TABLE_DOCLIB',          '`' . $config->db->prefix . 'doclib`');
define('TABLE_DOC',             '`' . $config->db->prefix . 'doc`');
define('TABLE_DOCBLOCK',        '`' . $config->db->prefix . 'docblock`');
define('TABLE_DEMANDPOOL',      '`' . $config->db->prefix . 'demandpool`');
define('TABLE_DEMAND',          '`' . $config->db->prefix . 'demand`');
define('TABLE_DEMANDSPEC',      '`' . $config->db->prefix . 'demandspec`');
define('TABLE_DEMANDREVIEW',    '`' . $config->db->prefix . 'demandreview`');
define('TABLE_DECISION',        '`' . $config->db->prefix . 'decision`');
define('TABLE_API',             '`' . $config->db->prefix . 'api`');
define('TABLE_API_SPEC',        '`' . $config->db->prefix . 'apispec`');
define('TABLE_APISTRUCT',       '`' . $config->db->prefix . 'apistruct`');
define('TABLE_APISTRUCT_SPEC',  '`' . $config->db->prefix . 'apistruct_spec`');
define('TABLE_API_LIB_RELEASE', '`' . $config->db->prefix . 'api_lib_release`');

define('TABLE_MODULE',        '`' . $config->db->prefix . 'module`');
define('TABLE_ACTION',        '`' . $config->db->prefix . 'action`');
define('TABLE_ACTIONRECENT',  '`' . $config->db->prefix . 'actionrecent`');
define('TABLE_ACTIONPRODUCT', '`' . $config->db->prefix . 'actionproduct`');
define('TABLE_FILE',          '`' . $config->db->prefix . 'file`');
define('TABLE_HOLIDAY',       '`' . $config->db->prefix . 'holiday`');
define('TABLE_HISTORY',       '`' . $config->db->prefix . 'history`');
define('TABLE_EXTENSION',     '`' . $config->db->prefix . 'extension`');
define('TABLE_EXTUSER',       '`' . $config->db->prefix . 'extuser`');
define('TABLE_CRON',          '`' . $config->db->prefix . 'cron`');
define('TABLE_QUEUE',         '`' . $config->db->prefix . 'queue`');
define('TABLE_BLOCK',         '`' . $config->db->prefix . 'block`');
define('TABLE_DOCACTION',     '`' . $config->db->prefix . 'docaction`');
define('TABLE_DOCCONTENT',    '`' . $config->db->prefix . 'doccontent`');
define('TABLE_TESTSUITE',     '`' . $config->db->prefix . 'testsuite`');
define('TABLE_SUITECASE',     '`' . $config->db->prefix . 'suitecase`');
define('TABLE_TESTREPORT',    '`' . $config->db->prefix . 'testreport`');

define('TABLE_ENTRY',         '`' . $config->db->prefix . 'entry`');
define('TABLE_WEEKLYREPORT',  '`' . $config->db->prefix . 'weeklyreport`');
define('TABLE_WEBHOOK',       '`' . $config->db->prefix . 'webhook`');
define('TABLE_LOG',           '`' . $config->db->prefix . 'log`');
define('TABLE_SCORE',         '`' . $config->db->prefix . 'score`');
define('TABLE_NOTIFY',        '`' . $config->db->prefix . 'notify`');
define('TABLE_OAUTH',         '`' . $config->db->prefix . 'oauth`');
define('TABLE_PIPELINE',      '`' . $config->db->prefix . 'pipeline`');
define('TABLE_JOB',           '`' . $config->db->prefix . 'job`');
define('TABLE_COMPILE',       '`' . $config->db->prefix . 'compile`');
define('TABLE_MR',            '`' . $config->db->prefix . 'mr`');
define('TABLE_MRAPPROVAL',    '`' . $config->db->prefix . 'mrapproval`');
define('TABLE_MARK',          '`' . $config->db->prefix . 'mark`');

define('TABLE_SERVERROOM',   '`' . $config->db->prefix . 'serverroom`');
define('TABLE_HOST',         '`' . $config->db->prefix . 'host`');
define('TABLE_REPO',         '`' . $config->db->prefix . 'repo`');
define('TABLE_RELATION',     '`' . $config->db->prefix . 'relation`');
define('TABLE_REPOHISTORY',  '`' . $config->db->prefix . 'repohistory`');
define('TABLE_REPOFILES',    '`' . $config->db->prefix . 'repofiles`');
define('TABLE_REPOBRANCH',   '`' . $config->db->prefix . 'repobranch`');
define('TABLE_KANBAN',       '`' . $config->db->prefix . 'kanban`');
define('TABLE_KANBANSPACE',  '`' . $config->db->prefix . 'kanbanspace`');
define('TABLE_KANBANREGION', '`' . $config->db->prefix . 'kanbanregion`');
define('TABLE_KANBANLANE',   '`' . $config->db->prefix . 'kanbanlane`');
define('TABLE_KANBANCOLUMN', '`' . $config->db->prefix . 'kanbancolumn`');
define('TABLE_KANBANORDER',  '`' . $config->db->prefix . 'kanbanorder`');
define('TABLE_KANBANGROUP',  '`' . $config->db->prefix . 'kanbangroup`');
define('TABLE_KANBANCARD',   '`' . $config->db->prefix . 'kanbancard`');
define('TABLE_KANBANCELL',   '`' . $config->db->prefix . 'kanbancell`');
if(!defined('TABLE_LANG'))        define('TABLE_LANG', '`' . $config->db->prefix . 'lang`');
if(!defined('TABLE_PROJECTSPEC')) define('TABLE_PROJECTSPEC', '`' . $config->db->prefix . 'projectspec`');

if(!defined('TABLE_SEARCHINDEX')) define('TABLE_SEARCHINDEX', $config->db->prefix . 'searchindex');
if(!defined('TABLE_SEARCHDICT'))  define('TABLE_SEARCHDICT',  $config->db->prefix . 'searchdict');

define('TABLE_SCREEN',     '`' . $config->db->prefix . 'screen`');
define('TABLE_CHART',      '`' . $config->db->prefix . 'chart`');
define('TABLE_PIVOT',      '`' . $config->db->prefix . 'pivot`');
define('TABLE_PIVOTSPEC',  '`' . $config->db->prefix . 'pivotspec`');
define('TABLE_PIVOTDRILL', '`' . $config->db->prefix . 'pivotdrill`');
define('TABLE_DASHBOARD',  '`' . $config->db->prefix . 'dashboard`');
define('TABLE_DATASET',    '`' . $config->db->prefix . 'dataset`');
define('TABLE_DATAVIEW',   '`' . $config->db->prefix . 'dataview`');
define('TABLE_DIMENSION',  '`' . $config->db->prefix . 'dimension`');
define('TABLE_SCENE',      '`' . $config->db->prefix . 'scene`');
define('VIEW_SCENECASE',   '`ztv_scenecase`');

define('TABLE_ACTIVITY',               '`' . $config->db->prefix . 'activity`');
define('TABLE_APPROVAL',               '`' . $config->db->prefix . 'approval`');
define('TABLE_APPROVALFLOW',           '`' . $config->db->prefix . 'approvalflow`');
define('TABLE_APPROVALFLOWOBJECT',     '`' . $config->db->prefix . 'approvalflowobject`');
define('TABLE_APPROVALFLOWSPEC',       '`' . $config->db->prefix . 'approvalflowspec`');
define('TABLE_APPROVALNODE',           '`' . $config->db->prefix . 'approvalnode`');
define('TABLE_APPROVALOBJECT',         '`' . $config->db->prefix . 'approvalobject`');
define('TABLE_APPROVALROLE',           '`' . $config->db->prefix . 'approvalrole`');
define('TABLE_ASSETLIB',               '`' . $config->db->prefix . 'assetlib`');
define('TABLE_ATTEND',                 '`' . $config->db->prefix . 'attend`');
define('TABLE_ATTENDSTAT',             '`' . $config->db->prefix . 'attendstat`');
define('TABLE_AUDITCL',                '`' . $config->db->prefix . 'auditcl`');
define('TABLE_AUDITPLAN',              '`' . $config->db->prefix . 'auditplan`');
define('TABLE_AUDITRESULT',            '`' . $config->db->prefix . 'auditresult`');
define('TABLE_BASICMEAS',              '`' . $config->db->prefix . 'basicmeas`');
define('TABLE_BUDGET',                 '`' . $config->db->prefix . 'budget`');
define('TABLE_CMCL',                   '`' . $config->db->prefix . 'cmcl`');
define('TABLE_DEPLOY',                 '`' . $config->db->prefix . 'deploy`');
define('TABLE_DEPLOYPRODUCT',          '`' . $config->db->prefix . 'deployproduct`');
define('TABLE_DEPLOYSTEP',             '`' . $config->db->prefix . 'deploystep`');
define('TABLE_DERIVEMEAS',             '`' . $config->db->prefix . 'derivemeas`');
define('TABLE_DURATIONESTIMATION',     '`' . $config->db->prefix . 'durationestimation`');
define('TABLE_FAQ',                    '`' . $config->db->prefix . 'faq`');
define('TABLE_FEEDBACK',               '`' . $config->db->prefix . 'feedback`');
define('TABLE_FEEDBACKVIEW',           '`' . $config->db->prefix . 'feedbackview`');
define('TABLE_GAPANALYSIS',            '`' . $config->db->prefix . 'gapanalysis`');
define('TABLE_IM_CHAT',                '`' . $config->db->prefix . 'im_chat`');
define('TABLE_IM_CHATUSER',            '`' . $config->db->prefix . 'im_chatuser`');
define('TABLE_IM_CHAT_MESSAGE_INDEX',  '`' . $config->db->prefix . 'im_chat_message_index`');
define('TABLE_IM_CLIENT',              '`' . $config->db->prefix . 'im_client`');
define('TABLE_IM_CONFERENCE',          '`' . $config->db->prefix . 'im_conference`');
define('TABLE_IM_CONFERENCEACTION',    '`' . $config->db->prefix . 'im_conferenceaction`');
define('TABLE_IM_MESSAGE',             '`' . $config->db->prefix . 'im_message`');
define('TABLE_IM_MESSAGESTATUS',       '`' . $config->db->prefix . 'im_messagestatus`');
define('TABLE_IM_MESSAGE_BACKUP',      '`' . $config->db->prefix . 'im_message_backup`');
define('TABLE_IM_MESSAGE_INDEX',       '`' . $config->db->prefix . 'im_message_index`');
define('TABLE_IM_QUEUE',               '`' . $config->db->prefix . 'im_queue`');
define('TABLE_IM_USERDEVICE',          '`' . $config->db->prefix . 'im_userdevice`');
define('TABLE_INTERVENTION',           '`' . $config->db->prefix . 'intervention`');
define('TABLE_ISSUE',                  '`' . $config->db->prefix . 'issue`');
define('TABLE_LEAVE',                  '`' . $config->db->prefix . 'leave`');
define('TABLE_LIEU',                   '`' . $config->db->prefix . 'lieu`');
define('TABLE_MAKEUP',                 '`' . $config->db->prefix . 'overtime`');
define('TABLE_MEASQUEUE',              '`' . $config->db->prefix . 'measqueue`');
define('TABLE_MEASRECORDS',            '`' . $config->db->prefix . 'measrecords`');
define('TABLE_MEASTEMPLATE',           '`' . $config->db->prefix . 'meastemplate`');
define('TABLE_MEETING',                '`' . $config->db->prefix . 'meeting`');
define('TABLE_MEETINGROOM',            '`' . $config->db->prefix . 'meetingroom`');
define('TABLE_NC',                     '`' . $config->db->prefix . 'nc`');
define('TABLE_OBJECT',                 '`' . $config->db->prefix . 'object`');
define('TABLE_OPPORTUNITY',            '`' . $config->db->prefix . 'opportunity`');
define('TABLE_OVERTIME',               '`' . $config->db->prefix . 'overtime`');
define('TABLE_PROCESS',                '`' . $config->db->prefix . 'process`');
define('TABLE_PROGRAMACTIVITY',        '`' . $config->db->prefix . 'programactivity`');
define('TABLE_PROGRAMOUTPUT',          '`' . $config->db->prefix . 'programoutput`');
define('TABLE_PROGRAMPLAN',            '`' . $config->db->prefix . 'programplan`');
define('TABLE_PROGRAMPROCESS',         '`' . $config->db->prefix . 'programprocess`');
define('TABLE_PROGRAMREPORT',          '`' . $config->db->prefix . 'programreport`');
define('TABLE_RELATIONOFTASKS',        '`' . $config->db->prefix . 'relationoftasks`');
define('TABLE_REPORT',                 '`' . $config->db->prefix . 'report`');
define('TABLE_RESEARCHPLAN',           '`' . $config->db->prefix . 'researchplan`');
define('TABLE_RESEARCHREPORT',         '`' . $config->db->prefix . 'researchreport`');
define('TABLE_REVIEW',                 '`' . $config->db->prefix . 'review`');
define('TABLE_REVIEWCL',               '`' . $config->db->prefix . 'reviewcl`');
define('TABLE_REVIEWISSUE',            '`' . $config->db->prefix . 'reviewissue`');
define('TABLE_REVIEWRESULT',           '`' . $config->db->prefix . 'reviewresult`');
define('TABLE_RISK',                   '`' . $config->db->prefix . 'risk`');
define('TABLE_RISKISSUE',              '`' . $config->db->prefix . 'riskissue`');
define('TABLE_SOLUTIONS',              '`' . $config->db->prefix . 'solutions`');
define('TABLE_SQLVIEW',                '`' . $config->db->prefix . 'sqlview`');
define('TABLE_SQLBUILDER',             '`' . $config->db->prefix . 'sqlbuilder`');
define('TABLE_TICKET',                 '`' . $config->db->prefix . 'ticket`');
define('TABLE_TICKETRELATION',         '`' . $config->db->prefix . 'ticketrelation`');
define('TABLE_TICKETSOURCE',           '`' . $config->db->prefix . 'ticketsource`');
define('TABLE_TRAINCATEGORY',          '`' . $config->db->prefix . 'traincategory`');
define('TABLE_TRAINCONTENTS',          '`' . $config->db->prefix . 'traincontents`');
define('TABLE_TRAINCOURSE',            '`' . $config->db->prefix . 'traincourse`');
define('TABLE_TRAINPLAN',              '`' . $config->db->prefix . 'trainplan`');
define('TABLE_TRAINRECORDS',           '`' . $config->db->prefix . 'trainrecords`');
define('TABLE_TRIP',                   '`' . $config->db->prefix . 'trip`');
define('TABLE_WORKESTIMATION',         '`' . $config->db->prefix . 'workestimation`');
define('TABLE_WORKFLOW',               '`' . $config->db->prefix . 'workflow`');
define('TABLE_WORKFLOWGROUP',          '`' . $config->db->prefix . 'workflowgroup`');
define('TABLE_WORKFLOWACTION',         '`' . $config->db->prefix . 'workflowaction`');
define('TABLE_WORKFLOWDATASOURCE',     '`' . $config->db->prefix . 'workflowdatasource`');
define('TABLE_WORKFLOWFIELD',          '`' . $config->db->prefix . 'workflowfield`');
define('TABLE_WORKFLOWLABEL',          '`' . $config->db->prefix . 'workflowlabel`');
define('TABLE_WORKFLOWLAYOUT',         '`' . $config->db->prefix . 'workflowlayout`');
define('TABLE_WORKFLOWLINKDATA',       '`' . $config->db->prefix . 'workflowlinkdata`');
define('TABLE_WORKFLOWRELATION',       '`' . $config->db->prefix . 'workflowrelation`');
define('TABLE_WORKFLOWRELATIONLAYOUT', '`' . $config->db->prefix . 'workflowrelationlayout`');
define('TABLE_WORKFLOWREPORT',         '`' . $config->db->prefix . 'workflowreport`');
define('TABLE_WORKFLOWRULE',           '`' . $config->db->prefix . 'workflowrule`');
define('TABLE_WORKFLOWSQL',            '`' . $config->db->prefix . 'workflowsql`');
define('TABLE_WORKFLOWVERSION',        '`' . $config->db->prefix . 'workflowversion`');
define('TABLE_WORKFLOWUI',             '`' . $config->db->prefix . 'workflowui`');
define('TABLE_ZOUTPUT',                '`' . $config->db->prefix . 'zoutput`');

define('TABLE_METRIC',        '`' . $config->db->prefix . 'metric`');
define('TABLE_METRICLIB',     '`' . $config->db->prefix . 'metriclib`');
define('TABLE_METRICRECORDS', '`' . $config->db->prefix . 'metricrecords`');

define('TABLE_SPACE',        '`' . $config->db->prefix . 'space`');
define('TABLE_INSTANCE',     '`' . $config->db->prefix . 'instance`');
define('TABLE_ARTIFACTREPO', '`' . $config->db->prefix . 'artifactrepo`');

define('TABLE_AI_AGENT',            '`' . $config->db->prefix . 'ai_agent`');
define('TABLE_AI_AGENTFIELD',       '`' . $config->db->prefix . 'ai_agentfield`');
define('TABLE_AI_AGENTROLE',        '`' . $config->db->prefix . 'ai_agentrole`');
define('TABLE_AI_MINIPROGRAM',      '`' . $config->db->prefix . 'ai_miniprogram`');
define('TABLE_AI_MINIPROGRAMFIELD', '`' . $config->db->prefix . 'ai_miniprogramfield`');
define('TABLE_AI_MINIPROGRAMSTAR',  '`' . $config->db->prefix . 'ai_miniprogramstar`');
define('TABLE_AI_MESSAGE',          '`' . $config->db->prefix . 'ai_message`');
define('TABLE_AI_MODEL',            '`' . $config->db->prefix . 'ai_model`');
define('TABLE_AI_ASSISTANT',        '`' . $config->db->prefix . 'ai_assistant`');

define('TABLE_SQLITE_QUEUE', '`' . $config->db->prefix . 'sqlite_queue`');
define('TABLE_DUCKDBQUEUE', '`' . $config->db->prefix . 'duckdbqueue`');

if(!defined('TABLE_ROADMAP'))        define('TABLE_ROADMAP',        '`' . $config->db->prefix . 'roadmap`');
if(!defined('TABLE_ROADMAPSTORY'))   define('TABLE_ROADMAPSTORY',   '`' . $config->db->prefix . 'roadmapstory`');
if(!defined('TABLE_CHARTER'))        define('TABLE_CHARTER',        '`' . $config->db->prefix . 'charter`');
if(!defined('TABLE_CHARTERPRODUCT')) define('TABLE_CHARTERPRODUCT', '`' . $config->db->prefix . 'charterproduct`');
if(!defined('TABLE_MARKET'))         define('TABLE_MARKET',         '`' . $config->db->prefix . 'market`');
if(!defined('TABLE_MARKETREPORT'))   define('TABLE_MARKETREPORT',   '`' . $config->db->prefix . 'marketreport`');
if(!defined('TABLE_MARKETRESEARCH')) define('TABLE_MARKETRESEARCH', '`' . $config->db->prefix . 'project`');
if(!defined('TABLE_SYSTEM'))         define('TABLE_SYSTEM', '`' . $config->db->prefix . 'system`');

define('JIRA_TMPRELATION',       '`jiratmprelation`');
define('CONFLUENCE_TMPRELATION', '`confluencetmprelation`');

if(!defined('FIRST_RELEASE_DATE')) define('FIRST_RELEASE_DATE', '2010-05-03');

$config->objectTables['dept']           = TABLE_DEPT;
$config->objectTables['product']        = TABLE_PRODUCT;
$config->objectTables['productplan']    = TABLE_PRODUCTPLAN;
$config->objectTables['epic']           = TABLE_STORY;
$config->objectTables['story']          = TABLE_STORY;
$config->objectTables['requirement']    = TABLE_STORY;
$config->objectTables['release']        = TABLE_RELEASE;
$config->objectTables['program']        = TABLE_PROJECT;
$config->objectTables['project']        = TABLE_PROJECT;
$config->objectTables['projectstory']   = TABLE_PROJECTSTORY;
$config->objectTables['execution']      = TABLE_PROJECT;
$config->objectTables['programplan']    = TABLE_PROJECT;
$config->objectTables['task']           = TABLE_TASK;
$config->objectTables['build']          = TABLE_BUILD;
$config->objectTables['bug']            = TABLE_BUG;
$config->objectTables['case']           = TABLE_CASE;
$config->objectTables['testcase']       = TABLE_CASE;
$config->objectTables['testtask']       = TABLE_TESTTASK;
$config->objectTables['testsuite']      = TABLE_TESTSUITE;
$config->objectTables['testreport']     = TABLE_TESTREPORT;
$config->objectTables['user']           = TABLE_USER;
$config->objectTables['api']            = TABLE_API;
$config->objectTables['doc']            = TABLE_DOC;
$config->objectTables['doclib']         = TABLE_DOCLIB;
$config->objectTables['docspace']       = TABLE_DOCLIB;
$config->objectTables['doctemplate']    = TABLE_DOC;
$config->objectTables['demand']         = TABLE_DEMAND;
$config->objectTables['demandpool']     = TABLE_DEMANDPOOL;
$config->objectTables['demandspec']     = TABLE_DEMANDSPEC;
$config->objectTables['demandreview']   = TABLE_DEMANDREVIEW;
$config->objectTables['deliverable']    = TABLE_DELIVERABLE;
$config->objectTables['todo']           = TABLE_TODO;
$config->objectTables['custom']         = TABLE_LANG;
$config->objectTables['branch']         = TABLE_BRANCH;
$config->objectTables['module']         = TABLE_MODULE;
$config->objectTables['caselib']        = TABLE_TESTSUITE;
$config->objectTables['entry']          = TABLE_ENTRY;
$config->objectTables['webhook']        = TABLE_WEBHOOK;
$config->objectTables['stakeholder']    = TABLE_STAKEHOLDER;
$config->objectTables['job']            = TABLE_JOB;
$config->objectTables['team']           = TABLE_TEAM;
$config->objectTables['pipeline']       = TABLE_PIPELINE;
$config->objectTables['mr']             = TABLE_MR;
$config->objectTables['kanban']         = TABLE_KANBAN;
$config->objectTables['kanbanspace']    = TABLE_KANBANSPACE;
$config->objectTables['kanbanregion']   = TABLE_KANBANREGION;
$config->objectTables['kanbancolumn']   = TABLE_KANBANCOLUMN;
$config->objectTables['kanbanlane']     = TABLE_KANBANLANE;
$config->objectTables['kanbanorder']    = TABLE_KANBANORDER;
$config->objectTables['kanbangroup']    = TABLE_KANBANGROUP;
$config->objectTables['kanbancard']     = TABLE_KANBANCARD;
$config->objectTables['sonarqube']      = TABLE_PIPELINE;
$config->objectTables['gitea']          = TABLE_PIPELINE;
$config->objectTables['gogs']           = TABLE_PIPELINE;
$config->objectTables['gitlab']         = TABLE_PIPELINE;
$config->objectTables['jenkins']        = TABLE_PIPELINE;
$config->objectTables['nexus']          = TABLE_PIPELINE;
$config->objectTables['stage']          = TABLE_STAGE;
$config->objectTables['apistruct']      = TABLE_APISTRUCT;
$config->objectTables['repo']           = TABLE_REPO;
$config->objectTables['dataview']       = TABLE_DATAVIEW;
$config->objectTables['zahost']         = TABLE_ZAHOST;
$config->objectTables['zanode']         = TABLE_ZAHOST;
$config->objectTables['automation']     = TABLE_AUTOMATION;
$config->objectTables['stepResult']     = TABLE_TESTRUN;
$config->objectTables['scene']          = TABLE_SCENE;
$config->objectTables['chart']          = TABLE_CHART;
$config->objectTables['pivot']          = TABLE_PIVOT;
$config->objectTables['serverroom']     = TABLE_SERVERROOM;
$config->objectTables['host']           = TABLE_ZAHOST;
$config->objectTables['instance']       = TABLE_INSTANCE;
$config->objectTables['space']          = TABLE_SPACE;
$config->objectTables['artifactrepo']   = TABLE_ARTIFACTREPO;
$config->objectTables['metric']         = TABLE_METRIC;
$config->objectTables['cron']           = TABLE_CRON;
$config->objectTables['lang']           = TABLE_LANG;
$config->objectTables['review']         = TABLE_REVIEW;
$config->objectTables['effort']         = TABLE_EFFORT;
$config->objectTables['design']         = TABLE_DESIGN;
$config->objectTables['prompt']         = TABLE_AI_AGENT;
$config->objectTables['aiapp']          = TABLE_AI_MINIPROGRAM;
$config->objectTables['miniprogram']    = TABLE_AI_MINIPROGRAM;
$config->objectTables['roadmap']        = TABLE_ROADMAP;
$config->objectTables['charter']        = TABLE_CHARTER;
$config->objectTables['market']         = TABLE_MARKET;
$config->objectTables['marketreport']   = TABLE_MARKETREPORT;
$config->objectTables['marketresearch'] = TABLE_PROJECT;
$config->objectTables['researchstage']  = TABLE_PROJECT;
$config->objectTables['workflowgroup']  = TABLE_WORKFLOWGROUP;
$config->objectTables['productline']    = TABLE_MODULE;
$config->objectTables['repocommit']     = TABLE_REPOHISTORY;
$config->objectTables['system']         = TABLE_SYSTEM;
$config->objectTables['mark']           = TABLE_MARK;
$config->objectTables['cm']             = TABLE_OBJECT;
$config->objectTables['baseline']       = TABLE_OBJECT;
$config->objectTables['projectchange']  = TABLE_PROJECTCHANGE;

$config->newFeatures      = array('introduction', 'tutorial', 'youngBlueTheme', 'visions', 'aiPrompts', 'promptDesign', 'promptExec');
$config->disabledFeatures = '';
$config->closedFeatures   = '';

$config->pipelineTypeList = array('gitlab', 'gogs', 'gitea', 'jenkins', 'sonarqube');
$config->mysqlDriverList  = array('mysql', 'oceanbase');
$config->pgsqlDriverList  = array('postgres', 'highgo', 'kingbase');

/* Program privs.*/
$config->programPriv = new stdclass();
$config->programPriv->noSprint      = array('task', 'story', 'requirement', 'epic', 'tree', 'project', 'execution', 'projectbuild', 'bug', 'testcase', 'testtask', 'testreport', 'doc', 'repo', 'stakeholder', 'projectrelease', 'issue', 'risk', 'opportunity', 'meeting', 'pssp', 'auditplan', 'nc', 'review', 'reviewissue', 'weekly');
$config->programPriv->scrum         = array('story', 'requirement', 'epic', 'productplan', 'tree', 'projectplan', 'projectstory', 'projectrelease', 'project', 'execution', 'projectbuild', 'bug', 'testcase', 'testreport', 'doc', 'repo', 'meeting', 'stakeholder', 'testtask', 'issue', 'risk', 'opportunity', 'weekly', 'review', 'reviewissue', 'report', 'mr');
$config->programPriv->waterfall     = array_merge($config->programPriv->scrum, array('workestimation', 'durationestimation', 'budget', 'programplan', 'cm', 'projectchange', 'milestone', 'design', 'measrecord', 'auditplan', 'nc', 'trainplan', 'gapanalysis', 'pssp', 'researchplan', 'researchreport', 'report', 'mr'));
$config->programPriv->agileplus     = $config->programPriv->scrum;
$config->programPriv->waterfallplus = $config->programPriv->waterfall;
$config->programPriv->ipd           = $config->programPriv->waterfall;

$config->safeFileTimeout  = 3600;
$config->waterfallModules = array('workestimation', 'durationestimation', 'budget', 'programplan', 'review', 'reviewissue', 'weekly', 'cm', 'milestone', 'design', 'auditplan', 'trainplan', 'gapanalysis', 'pssp', 'researchplan', 'researchreport');

$config->showMainMenu = true;
$config->maxPriValue  = '256';

$config->importWhiteList = array('user', 'task', 'epic', 'requirement', 'story', 'bug', 'testcase', 'feedback', 'ticket');

$config->dtable = new stdclass();
$config->dtable->colVars = array('width', 'minWidth', 'type', 'flex', 'fixed', 'sortType', 'checkbox', 'nestedToggle', 'statusMap', 'actionsMap', 'group');

$config->featureGroup = new stdclass();
$config->featureGroup->my       = array('score');
$config->featureGroup->product  = array('roadmap', 'track', 'UR', 'ER');
$config->featureGroup->project  = array();
$config->featureGroup->assetlib = array();
$config->featureGroup->other    = array('devops', 'kanban', 'setCode');

$config->db->sqliteBlacklist    = array('sqlite_queue', 'cron');
$config->hasDropmenuApps        = array('program', 'project', 'product', 'execution', 'qa', 'admin', 'bi', 'feedback', 'demandpool');
$config->hasBranchMenuModules   = array('product', 'story', 'release', 'bug', 'testcase', 'testtask', 'branch', 'tree', 'workflowgroup', 'deliverable', 'process', 'activity', 'review', 'reviewcl', 'stage', 'auditcl');
$config->excludeBranchMenu      = array('product-dashboard', 'product-view', 'product-whitelist', 'product-addwhitelist', 'branch-manage', 'branch-batchedit');
$config->excludeDropmenuList    = array('program-browse', 'program-productview', 'program-kanban', 'product-all', 'product-index', 'product-kanban', 'project-kanban', 'execution-all', 'execution-executionkanban', 'project-browse', 'project-template', 'project-createtemplate', 'project-batchedit', 'product-batchedit', 'admin-index', 'product-create', 'project-create', 'execution-create', 'program-create', 'execution-batchedit', 'metric-preview', 'metric-browse', 'metric-view', 'metriclib-browse', 'qa-index', 'caselib-create', 'feedback-batchedit', 'feedback-batchclose', 'feedback-showimport', 'dimension-browse', 'dataview-browse', 'dataview-create', 'dataview-query', 'ticket-batchedit', 'ticket-batchfinish', 'ticket-batchactivate', 'ticket-showimport', 'file-download');
$config->excludeDropmenuModules = array('reporttemplate');
$config->hasSwitcherModules     = array('design');
$config->hasSwitcherMethods     = array('project-bug', 'project-testcase', 'execution-bug', 'execution-testcase', 'testtask-cases', 'testtask-view', 'testtask-report', 'testtask-groupcase', 'testtask-linkcase');
$config->excludeSwitcherList    = array();
$config->hasMainNavBar          = array();
