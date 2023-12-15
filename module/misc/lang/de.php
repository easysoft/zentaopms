<?php
/**
 * The misc module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     misc
 * @version     $Id: English.php 824 2010-05-02 15:32:06Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->misc = new stdclass();
$lang->misc->common  = 'Sonstiges';
$lang->misc->ping    = 'Ping';
$lang->misc->view    = 'Check';
$lang->misc->cancel  = 'Cancel';

$lang->misc->zentao = new stdclass();
$lang->misc->zentao->version           = 'ALM Version %s';
$lang->misc->zentao->labels['about']   = 'Über';
$lang->misc->zentao->labels['support'] = 'Support';
$lang->misc->zentao->labels['cowin']   = 'Hilf uns';
$lang->misc->zentao->labels['service'] = 'Service';
$lang->misc->zentao->labels['others']  = 'Others';

$lang->misc->zentao->icons['about']   = 'group';
$lang->misc->zentao->icons['support'] = 'question-sign';
$lang->misc->zentao->icons['cowin']   = 'hand-right';
$lang->misc->zentao->icons['service'] = 'heart';

$lang->misc->zentao->about['bizversion']   = 'ZenTao Biz';
$lang->misc->zentao->about['official']     = "Offizielle Webseite";
$lang->misc->zentao->about['changelog']    = "Change Log";
$lang->misc->zentao->about['license']      = "Lizenz";
$lang->misc->zentao->about['extension']    = "Plugin Platform";
$lang->misc->zentao->about['follow']       = "Follow Us";

$lang->misc->zentao->support['vip']        = "VIP Technischer Support";
$lang->misc->zentao->support['manual']     = "Benutzer Handbuch";
$lang->misc->zentao->support['faq']        = "Common Problem";
$lang->misc->zentao->support['ask']        = "Official Answer";
$lang->misc->zentao->support['video']      = "Use Video";
$lang->misc->zentao->support['qqgroup']    = "Official QQ Group";

$lang->misc->zentao->cowin['reportbug']    = "Report Bug";
$lang->misc->zentao->cowin['feedback']     = "Feedback";
$lang->misc->zentao->cowin['recommend']    = "More";

$lang->misc->zentao->service['zentaotrain'] = 'ZenTao Training';
$lang->misc->zentao->service['idc']         = 'ZenTao Cloud';
$lang->misc->zentao->service['custom']      = 'ZenTao Custom';

global $config;
$lang->misc->zentao->others['chanzhi']  = "<img src='{$config->webRoot}theme/default/images/main/chanzhi.ico' /> Zsite";
$lang->misc->zentao->others['zdoo']     = "<img src='{$config->webRoot}theme/default/images/main/zdoo.ico' /> ZDOO";

$lang->misc->zentao->others['ydisk']    = "<img src='{$config->webRoot}theme/default/images/main/ydisk.ico' /> Y Disk";
$lang->misc->zentao->others['meshiot' ] = "<img src='{$config->webRoot}theme/default/images/main/meshiot.ico' /> MeshioT";

$lang->misc->mobile      = "Mobiler Zugriff";
$lang->misc->noGDLib     = "Bitte benutzen Sie den Browser Ihres Telefons um die Seite <strong>%s</strong> aufzurufen";
$lang->misc->copyright   = "&copy; 2009 - " . date('Y') . " <a href='https://easysoft.ltd' target='_blank'>Nature Easy Soft Network Technology Co,LTD</a> Email <a href='mailto:Max@easysoft.ltd'>Max@easysoft.ltd</a>";
$lang->misc->checkTable  = "Prüfe Datentabellen";
$lang->misc->needRepair  = "Repariere Datentabellen";
$lang->misc->repairTable = "Datenbank íst beschädigt. Bitte brüfen und reparieren!";
$lang->misc->repairFail  = "Reparatur fehlgeschlagen. Bitte wechseln Sie in das Verzeichnis der Datenbank, und versuchen Sie folgenden Befehl <code>myisamchk -r -f %s.MYI</code> zum reparieren der DB.";
$lang->misc->connectFail = "Failed to connect to the database. Error: %s，<br/> Please check the MySQL error log and troubleshoot.";
$lang->misc->tableName   = "Tabellenname";
$lang->misc->tableStatus = "Status";
$lang->misc->novice      = "Erstes mal bei ZenTao? Möchten Sie den Beginner Modus starten?";
$lang->misc->showAnnual  = 'Add annual summary';
$lang->misc->annualDesc  = 'After version 12.0, the new annual report function can be viewed on the 『Report->Annual Summary』 page. <a href="%s" target="_blank" id="showAnnual" class="btn btn-mini btn-primary">See now</a>.';
$lang->misc->remind      = 'New feature reminders';

$lang->misc->expiredTipsTitle    = 'Dear system administrator, hello:';
$lang->misc->expiredCountTips    = 'There are <span class="expired-tips text-blue" data-toggle="tooltip" data-placement="bottom" title="%s">%s plug-ins</span> in the system that will expire soon. To avoid affecting your regular use, please get in touch with the administrator to renew or uninstall them in time.';
$lang->misc->expiredPluginTips   = 'Expired plugins are: %s. ';
$lang->misc->expiringPluginTips  = 'The plug-ins that will expire are: %s.';
$lang->misc->expiredTipsForAdmin = 'There are %s plug-ins in the current system that will expire soon. To avoid affecting the regular use of the function, please renew or uninstall them in the system background plug-in management as soon as possible.';

$lang->misc->noticeRepair = "<h5>If you are not Administrator, contact your ZenTao Administrator to repair tables.</h5>
    <h5>If you are the Administrator, login your ZenTao host and create a file named <span>%s</span>.</h5>
    <p>Note:</p>
    <ol>
    <li>Keep the file empty.</li>
    <li>If the file exists, remove it and create a new one.</li>
    </ol>";

$lang->misc->feature = new stdclass();
$lang->misc->feature->lastest           = 'Letzte Version';
$lang->misc->feature->detailed          = 'Details';
$lang->misc->feature->introduction      = 'Features';
$lang->misc->feature->tutorial          = 'Tutorial';
$lang->misc->feature->tutorialImage     = 'theme/default/images/main/tutorial_en.png';
$lang->misc->feature->youngBlueTheme    = 'Young Blue Theme';
$lang->misc->feature->youngBlueImage    = 'theme/default/images/main/new_theme_en.png';
$lang->misc->feature->visions           = "Interface switching";
$lang->misc->feature->nextStep          = 'Next step';
$lang->misc->feature->prevStep          = 'Previous step';
$lang->misc->feature->close             = 'Close';
$lang->misc->feature->learnMore         = 'Learn More';
$lang->misc->feature->downloadFile      = 'Download introduction';
$lang->misc->feature->tutorialDesc      = '<p>ZenTao 15.0 has new functions, and you know how to use it through the "<strong>Tutorial</strong>".</p><p>Click your [<span style="color: #0c60e1">Avatar-Theme-Young Blue</span>] to set it.</p>';
$lang->misc->feature->themeDesc         = '<p>ZenTao 15.0+ a new "Youth Blue" theme, the pages are more beautiful and the experience is more friendly.</p><p>Click your [<span style="color: #0c60e1">Avatar-Theme-Young Blue</span>] to set it.</p>';
$lang->misc->feature->visionsDesc       = "<p>The concept of interface has been added since 16.5. Users can deal with R&D affairs in <span style='color: #0c60e1'>[Full Feature Interface]</span> and daily office affairs in <span style='color: #0c60e1'>[Operation Management Interface]</span>.</p><p>You can view the current interface on the avatar, and click the name of the interface to view and switch other interfaces.</p>";
$lang->misc->feature->visionsImage      = 'theme/default/images/main/visions_en.png';
$lang->misc->feature->aiPrompts         = 'AI prompts';
$lang->misc->feature->aiPromptsImage    = 'theme/default/images/main/ai_prompts_en.svg';
$lang->misc->feature->aiChat            = 'AI chat';
$lang->misc->feature->aiChatImage       = 'theme/default/images/main/ai_chat_en.svg';
$lang->misc->feature->learnMoreLink     = 'https://www.zentao.net/book/zentaopms/1096.html';

/* Release Date. */
$lang->misc->releaseDate['18.9']        = '2023-11-09';
$lang->misc->releaseDate['18.8']        = '2023-09-28';
$lang->misc->releaseDate['18.7']        = '2023-08-29';
$lang->misc->releaseDate['18.6']        = '2023-08-15';
$lang->misc->releaseDate['18.5']        = '2023-07-05';
$lang->misc->releaseDate['18.4']        = '2023-06-14';
$lang->misc->releaseDate['18.4.beta1']  = '2023-05-31';
$lang->misc->releaseDate['18.4.alpha1'] = '2023-04-21';
$lang->misc->releaseDate['18.3']        = '2023-03-15';
$lang->misc->releaseDate['18.2']        = '2023-02-27';
$lang->misc->releaseDate['18.1']        = '2023-02-08';
$lang->misc->releaseDate['18.0']        = '2023-01-03';
$lang->misc->releaseDate['18.0.beta3']  = '2022-12-26';
$lang->misc->releaseDate['18.0.beta2']  = '2022-12-14';
$lang->misc->releaseDate['18.0.beta1']  = '2022-11-16';
$lang->misc->releaseDate['17.8']        = '2022-11-02';
$lang->misc->releaseDate['17.7']        = '2022-10-19';
$lang->misc->releaseDate['17.6.2']      = '2022-09-23';
$lang->misc->releaseDate['17.6.1']      = '2022-09-08';
$lang->misc->releaseDate['17.6']        = '2022-08-26';
$lang->misc->releaseDate['17.5']        = '2022-08-11';
$lang->misc->releaseDate['17.4']        = '2022-07-27';
$lang->misc->releaseDate['17.3']        = '2022-07-13';
$lang->misc->releaseDate['17.2']        = '2022-06-29';
$lang->misc->releaseDate['17.1']        = '2022-06-16';
$lang->misc->releaseDate['17.0']        = '2022-06-02';
$lang->misc->releaseDate['17.0.beta2']  = '2022-05-26';
$lang->misc->releaseDate['17.0.beta1']  = '2022-05-06';
$lang->misc->releaseDate['16.5']        = '2022-03-24';
$lang->misc->releaseDate['16.5.beta1']  = '2022-03-16';
$lang->misc->releaseDate['16.4']        = '2022-02-15';
$lang->misc->releaseDate['16.3']        = '2022-01-26';
$lang->misc->releaseDate['16.2']        = '2022-01-17';
$lang->misc->releaseDate['16.1']        = '2022-01-11';
$lang->misc->releaseDate['16.0']        = '2021-12-24';
$lang->misc->releaseDate['16.0.beta1']  = '2021-12-06';
$lang->misc->releaseDate['15.7.1']      = '2021-11-02';
$lang->misc->releaseDate['15.7']        = '2021-10-18';
$lang->misc->releaseDate['15.6']        = '2021-10-12';
$lang->misc->releaseDate['15.5']        = '2021-09-14';
$lang->misc->releaseDate['15.4']        = '2021-08-23';
$lang->misc->releaseDate['15.3']        = '2021-08-04';
$lang->misc->releaseDate['15.2']        = '2021-07-20';
$lang->misc->releaseDate['15.0.3']      = '2021-06-24';
$lang->misc->releaseDate['15.0.2']      = '2021-06-12';
$lang->misc->releaseDate['15.0.1']      = '2021-06-06';
$lang->misc->releaseDate['15.0']        = '2021-04-30';
$lang->misc->releaseDate['15.0.rc3']    = '2021-04-16';
$lang->misc->releaseDate['15.0.rc2']    = '2021-04-09';
$lang->misc->releaseDate['15.0.rc1']    = '2021-04-05';
$lang->misc->releaseDate['12.5.3']      = '2021-01-06';
$lang->misc->releaseDate['12.5.2']      = '2020-12-18';
$lang->misc->releaseDate['12.5.1']      = '2020-11-30';
$lang->misc->releaseDate['12.5.stable'] = '2020-11-19';
$lang->misc->releaseDate['20.0.alpha1'] = '2020-10-30';
$lang->misc->releaseDate['12.4.4']      = '2020-10-30';
$lang->misc->releaseDate['12.4.3']      = '2020-10-13';
$lang->misc->releaseDate['12.4.2']      = '2020-09-18';
$lang->misc->releaseDate['12.4.1']      = '2020-08-10';
$lang->misc->releaseDate['12.4.stable'] = '2020-07-28';
$lang->misc->releaseDate['12.3.3']      = '2020-07-02';
$lang->misc->releaseDate['12.3.2']      = '2020-06-01';
$lang->misc->releaseDate['12.3.1']      = '2020-05-15';
$lang->misc->releaseDate['12.3']        = '2020-04-08';
$lang->misc->releaseDate['12.2']        = '2020-03-25';
$lang->misc->releaseDate['12.1']        = '2020-03-10';
$lang->misc->releaseDate['12.0.1']      = '2020-02-12';
$lang->misc->releaseDate['12.0']        = '2020-01-03';
$lang->misc->releaseDate['11.7']        = '2019-11-28';
$lang->misc->releaseDate['11.6.5']      = '2019-11-08';
$lang->misc->releaseDate['11.6.4']      = '2019-10-17';
$lang->misc->releaseDate['11.6.3']      = '2019-09-24';
$lang->misc->releaseDate['11.6.2']      = '2019-09-06';
$lang->misc->releaseDate['11.6.1']      = '2019-08-23';
$lang->misc->releaseDate['11.6.stable'] = '2019-07-12';
$lang->misc->releaseDate['11.5.2']      = '2019-06-26';
$lang->misc->releaseDate['11.5.1']      = '2019-06-24';
$lang->misc->releaseDate['11.5.stable'] = '2019-05-08';
$lang->misc->releaseDate['11.4.1']      = '2019-04-08';
$lang->misc->releaseDate['11.4.stable'] = '2019-03-25';
$lang->misc->releaseDate['11.3.stable'] = '2019-02-27';
$lang->misc->releaseDate['11.2.stable'] = '2019-01-30';
$lang->misc->releaseDate['11.1.stable'] = '2019-01-04';
$lang->misc->releaseDate['11.0.stable'] = '2018-12-21';
$lang->misc->releaseDate['10.6.stable'] = '2018-11-20';
$lang->misc->releaseDate['10.5.stable'] = '2018-10-25';
$lang->misc->releaseDate['10.4.stable'] = '2018-09-28';
$lang->misc->releaseDate['10.3.stable'] = '2018-08-10';
$lang->misc->releaseDate['10.2.stable'] = '2018-08-02';
$lang->misc->releaseDate['10.0.stable'] = '2018-06-26';
$lang->misc->releaseDate['9.8.stable']  = '2018-01-17';
$lang->misc->releaseDate['9.7.stable']  = '2017-12-22';
$lang->misc->releaseDate['9.6.stable']  = '2017-11-06';
$lang->misc->releaseDate['9.5.1']       = '2017-09-27';
$lang->misc->releaseDate['9.3.beta']    = '2017-06-21';
$lang->misc->releaseDate['9.1.stable']  = '2017-03-23';
$lang->misc->releaseDate['9.0.beta']    = '2017-01-03';
$lang->misc->releaseDate['8.3.stable']  = '2016-11-09';
$lang->misc->releaseDate['8.2.stable']  = '2016-05-17';
$lang->misc->releaseDate['7.4.beta']    = '2015-11-13';
$lang->misc->releaseDate['7.2.stable']  = '2015-05-22';
$lang->misc->releaseDate['7.1.stable']  = '2015-03-07';
$lang->misc->releaseDate['6.3.stable']  = '2014-11-07';

/* Release Detail. */
$lang->misc->feature->all['18.9'][]        = array('title' => 'We have now implemented comprehensive integration of AI Large Language Models, introduced an upgraded client version for meetings, and improved the ability to add participants in test requests. To enhance usability, we have also implemented online preview capabilities for video attachments and increased flexibility in customizing review inspection categories.', 'desc' => '');
$lang->misc->feature->all['18.8'][]        = array('title' => 'In the BI section, we have introduced new features such as metric item functionality and an application inspection report dashboard. The DevOps platform edition now includes a configuration wizard. Additionally, we have enhanced the requitement and market management interface by adding a market management feature. New revision of client navigation and personal center.', 'desc' => '');
$lang->misc->feature->all['18.7'][]        = array('title' => 'DevOps has introduced new features such as cloud-native platform, artifact repository, and application management, while also enhancing the navigation structure and improving the UI interactions. Additionally, a new AI suggestion designer has been added, which supports integration with large language models and allows for customization of AI applications.', 'desc' => '');
$lang->misc->feature->all['18.6'][]        = array('title' => "We have made significant improvements to the performance of commonly used lists and the details of BI functionality, while also refining the functionality details of waterfall projects. Additionally, we have diligently addressed and resolved any identified bugs.", 'desc' => '');
$lang->misc->feature->all['18.5'][]        = array('title' => "Our academy's curriculum now supports cloud-based importing and the previewing of PDF files within courses. Additionally, we've optimized the loading speed of commonly used lists and addressed multiple bugs.", 'desc' => '');
$lang->misc->feature->all['18.4'][]        = array('title' => 'In this release, we have improved the performance of the core list, added compatibility with Dameng databases, and resolved multiple bugs.', 'desc' => '');
$lang->misc->feature->all['18.4.beta1'][]  = array('title' => 'Fix bugs.', 'desc' => '');
$lang->misc->feature->all['18.4.alpha1'][] = array('title' => 'We have enhanced the interaction experience of permissions and documents, while introducing the concept of test scenarios. Moreover, we now provide support for importing use cases with XMIND. Additionally, we have fully revamped the BI module by incorporating large screens, pivot tables, charts, and data capabilities.', 'desc' => '');
$lang->misc->feature->all['18.3'][]        = array('title' => 'Language item customization is added in secondary development, which supports the definition of language items for menus and search tags; Editor functionality is added in the secondary development, which allows users to turn it on and off on demand; The forms could be saved temporarily when users exits unexpectedly, and the unsaved information will be filled automatically the next time you enter.', 'desc' => '');
$lang->misc->feature->all['18.2'][]        = array('title' => 'Agile Plus and Waterfall Plus management models are newly added. Support for unlimited splitting of waterfall project stages. The UI of Admin is completely upgraded and redesigned. Fix bugs.', 'desc' => '');
$lang->misc->feature->all['18.1'][]        = array('title' => 'The automation testing solution interaction is optimized, while a new snapshot management function is newly added. ZenTao IM implemented online collaboration of PPT documents.Fix bugs.', 'desc' => '');
$lang->misc->feature->all['18.0'][]        = array('title' => "Automated test solutions are proposed. Work order related functions are added to the Operation Management Interface. Approval workflow support for adding all types of notifications. And at the same time, we have further improved the earned value calculation rules.", 'desc' => '');
$lang->misc->feature->all['18.0.beta3'][]  = array('title' => "The module Statistic is upgraded to BI, with 5 built-in large screens of macro management dimensions.", 'desc' => '');
$lang->misc->feature->all['18.0.beta2'][]  = array('title' => "We have optimized the product with multi-branches/multi-platforms. It's supported to create siblings stories. It's possible for Plan, Build, and Release to link requirements or bugs beyond their parent branch. ZenTao Client has implemented the robot chatting mechanism.", 'desc' => '');
$lang->misc->feature->all['18.0.beta1'][]  = array('title' => "Multiple core processes in ZenTao are improved: A project without any product linked in is able to be created, as well as creating projects without sprints/iterations linked in; projects are able to link products beyond the father program; switch easily between ZenTao Lite mode and Full Lifecycle Management mode.", 'desc' => '');
$lang->misc->feature->all['17.8'][]        = array('title' => "We have optimized the color of status in lists, as well as the dashboard color. At the same time, the page of the task effort has been improved.", 'desc' => '');
$lang->misc->feature->all['17.7'][]        = array('title' => "The table is optimized in the transition version. At the same time, we have added the new feature of Work Order and get the Feedback features improved as well. Fix bugs.", 'desc' => '');
$lang->misc->feature->all['17.6.2'][]      = array('title' => "3 themes in ZenTao including Green, ZenTao Blue, and Young Blue are updated. At the same time, the attachments could be uploaded in bulk in ZenTao. Fix bugs.", 'desc' => '');
$lang->misc->feature->all['17.6.1'][]      = array('title' => "Optimized the processing logic of multi-member tasks. Fix bugs.", 'desc' => '');
$lang->misc->feature->all['17.6'][]        = array('title' => "The processing logic of requirements is optimized, and the permissions of user requirements and soft requirements are split. Gantt chart supports manual drag and drop to manage task relationship. Fix bugs.", 'desc' => '');
$lang->misc->feature->all['17.5'][]        = array('title' => "Provide efficient visual statistical tools. Optimize ZenTao performance, the database engine is adjusted from MyISAM to InnoDB. Gantt chart is optimized and upgraded, and the Copy items of the Max version can replicate more information. Fix bugs.", 'desc' => '');
$lang->misc->feature->all['17.4'][]        = array('title' => "Optimization of details Page visualization and jumping page logic. Improvement of Kanban function. Optimization of document creating and editing pages. Fix bugs.", 'desc' => '');
$lang->misc->feature->all['17.3'][]        = array('title' => "UI optimization of the modules such as statistics and background, the use case library will synchronize the function optimization information. Fix bugs.", 'desc' => '');
$lang->misc->feature->all['17.2'][]        = array('title' => "Adjust the display of agile project block; optimize the UI of programs, projects and tests; optimize the detailed experience. Fix bugs.", 'desc' => '');
$lang->misc->feature->all['17.1'][]        = array('title' => "Modify the interaction problem of execution, project module. Complete customer's high priority requirements. The detail experience is optimized, fix bugs.", 'desc' => '');
$lang->misc->feature->all['17.0'][]        = array('title' => 'The detail experience is optimized, fix bugs.', 'desc' => '');
$lang->misc->feature->all['17.0.beta2'][]  = array('title' => 'The detail experience is optimized, fix bugs.', 'desc' => '');
$lang->misc->feature->all['17.0.beta1'][]  = array('title' => "Complete customer's high priority requirements", 'desc' => '');
$lang->misc->feature->all['16.5'][]        = array('title' => 'Fix bug.', 'desc' => '');
$lang->misc->feature->all['16.5.beta1'][]  = array('title' => 'Fix bug, merge all code to one package', 'desc' => '');
$lang->misc->feature->all['16.4'][]        = array('title' => 'Implement JIRA import function and improve plug-in extension mechanism.', 'desc' => '');
$lang->misc->feature->all['16.3'][]        = array('title' => 'Kanban adds related plan/release/version/iteration functions, and the detail experience is optimized, fix bugs.', 'desc' => '');
$lang->misc->feature->all['16.2'][]        = array('title' => 'Add kanban model project, fix bugs.', 'desc' => '');
$lang->misc->feature->all['16.1'][]        = array('title' => 'Plan to add status management and Kanban view, upgrade process optimization, fix bugs.', 'desc' => '');
$lang->misc->feature->all['16.0'][]        = array('title' => 'New general board, improve branch management, fix bugs.', 'desc' => '');
$lang->misc->feature->all['16.0.beta1'][]  = array('title' => 'Add waterfall model project, add task kanban, improved branch management, and fixed bugs.', 'desc' => '');
$lang->misc->feature->all['15.7.1'][]      = array('title' => 'Fix bug.', 'desc' => '');
$lang->misc->feature->all['15.7'][]        = array('title' => 'Add API Lib. Fix bug.', 'desc' => '');
$lang->misc->feature->all['15.6'][]        = array('title' => 'Fix bug.', 'desc' => '');
$lang->misc->feature->all['15.5'][]        = array('title' => 'Add Program / Product / Project Kanban, global addition function and novice guidance. Fix bug.', 'desc' => '');
$lang->misc->feature->all['15.4'][]        = array('title' => 'Fix bug', 'desc' => '');
$lang->misc->feature->all['15.3'][]        = array('title' => 'Adjust interface style and document, fix bug', 'desc' => '');
$lang->misc->feature->all['15.2'][]        = array('title' => 'Optimize the new version upgrade process, add execution kanban.', 'desc' => '');

$lang->misc->feature->all['15.0.3'][]      = array('title' => 'Fix Bug', 'desc' => '');
$lang->misc->feature->all['15.0.2'][]      = array('title' => 'Fix Bug', 'desc' => '');
$lang->misc->feature->all['15.0.1'][]      = array('title' => 'Fix Bug', 'desc' => '');
$lang->misc->feature->all['15.0'][]        = array('title' => 'Fix Bug', 'desc' => '');
$lang->misc->feature->all['15.0.rc3'][]    = array('title' => 'Adjust details, fix bug.', 'desc' => '');
$lang->misc->feature->all['15.0.rc2'][]    = array('title' => 'Fix Bug.', 'desc' => '');
$lang->misc->feature->all['15.0.rc1'][]    = array('title' => 'Upgrade to 15,reframe menu, add program.', 'desc' => '');
$lang->misc->feature->all['12.5.3'][]      = array('title' => 'Adjust annual data.', 'desc' => '');
$lang->misc->feature->all['12.5.2'][]      = array('title' => 'Fix Bug', 'desc' => '');
$lang->misc->feature->all['12.5.1'][]      = array('title' => 'Fix Bug', 'desc' => '');
$lang->misc->feature->all['12.5.stable'][] = array('title' => 'Fix Bug. Complete high priority story.', 'desc' => '');

$lang->misc->feature->all['12.4.4'][] = array('title'=>'Compatible with professional and enterprise editions', 'desc' => '');
$lang->misc->feature->all['12.4.3'][] = array('title'=>'Fix Bug', 'desc' => '');
$lang->misc->feature->all['12.4.2'][] = array('title'=>'Fix Bug', 'desc' => '');
$lang->misc->feature->all['12.4.1'][] = array('title'=>'Fix Bug', 'desc' => '');

$lang->misc->feature->all['12.4.stable'][] = array('title'=>'Fix Bug', 'desc' => '');

$lang->misc->feature->all['12.3.3'][] = array('title'=>'Fix Bug', 'desc' => '');
$lang->misc->feature->all['12.3.2'][] = array('title'=>'Fix workflow', 'desc' => '');
$lang->misc->feature->all['12.3.1'][] = array('title'=>'Fix bugs of high severity.', 'desc' => '');
$lang->misc->feature->all['12.3'][]   = array('title'=>'Integrate unit test, open the continuous integration closed-loop.', 'desc' => '');
$lang->misc->feature->all['12.2'][]   = array('title'=>'Add parent-child story, compatible xuanxuan.', 'desc' => '');
$lang->misc->feature->all['12.1'][]   = array('title'=>'Add Integration.', 'desc' => '<p>Add integration, and build in Jenkins</p>');
$lang->misc->feature->all['12.0.1'][] = array('title'=>'Fix Bug.', 'desc' => '');

$lang->misc->feature->all['12.0'][]   = array('title'=>'Move repo function to zentao', 'desc' => '');
$lang->misc->feature->all['12.0'][]   = array('title'=>'Move repo function to zentao', 'desc' => '');
$lang->misc->feature->all['12.0'][]   = array('title'=>'Move repo function to zentao', 'desc' => '');

$lang->misc->feature->all['11.7'][]   = array('title'=>'Optimize details and fix bug.', 'desc' => '<p>Added choices for users to choose agile or not.</p><p>Added WeChat Enterprise to the types of webhook</p><p>Added the notifier of Dingding personal messages</p>');
$lang->misc->feature->all['11.6.5'][] = array('title'=>'Fix bug.', 'desc' => '');
$lang->misc->feature->all['11.6.4'][] = array('title'=>'Optimize details and fix bug.', 'desc' => '');
$lang->misc->feature->all['11.6.3'][] = array('title'=>'Fix bug.', 'desc' => '');
$lang->misc->feature->all['11.6.2'][] = array('title'=>'Optimize details and fix bug.', 'desc' => '');
$lang->misc->feature->all['11.6.1'][] = array('title'=>'Optimize details and fix bug.', 'desc' => '');

$lang->misc->feature->all['11.6.stable'][] = array('title'=>'Improving the International Edition Interface', 'desc' => '');
$lang->misc->feature->all['11.6.stable'][] = array('title'=>'Improving the International Edition Interface', 'desc' => '');

$lang->misc->feature->all['11.5.2'][] = array('title'=>'Increase the security of ZenTao and increase the login password for weak password check', 'desc' => '');
$lang->misc->feature->all['11.5.1'][] = array('title'=>'Add a third-party authentication and fix bugs.', 'desc' => '');

$lang->misc->feature->all['11.5.stable'][] = array('title'=>'Optimize details and fix bug.', 'desc' => '');
$lang->misc->feature->all['11.5.stable'][] = array('title'=>'Optimize details and fix bug.', 'desc' => '');
$lang->misc->feature->all['11.5.stable'][] = array('title'=>'Optimize details and fix bug.', 'desc' => '');

$lang->misc->feature->all['11.4.1'][]      = array('title'=>'Optimize details and fix bug.', 'desc' => '');

$lang->misc->feature->all["11.4.stable"][] = array('title'=>'Optimize details and fix bug.', 'desc' => '<p>Enhanced test management.</p><p>Optimized the UI of Plan, Release, and Build linked stories and bugs.</p><p>Customize the feature whether to display files in child category.</p><p>Optimize details and fix bug.</p>');

$lang->misc->feature->all['11.3.stable'][] = array('title'=>'Optimize details and fix bug.', 'desc' => '<p>Add Child Plan to a Plan</p><p>Optimize the chosen</p><p>Add Timezone settings</p><p>Optimize Document Library and Document modules</p>');

$lang->misc->feature->all['11.2.stable'][] = array('title'=>'Optimize details and fix bug.', 'desc' => '<p>Add upgrade logs and database checkup after upgrading</p><p>Fixed ZenTao client and other bugs, and optimized details.</p>');

$lang->misc->feature->all['11.1.stable'][] = array('title'=>'Fix Bug.', 'desc' => '');

$lang->misc->feature->all['11.0.stable'][] = array('title'=>'ZenTao integrate Xuanxuan', 'desc' => '');

$lang->misc->feature->all['10.6.stable'][] = array('title'=>'Adjust backup mechanism', 'desc' => '<p>Increase backup settings and make backup more flexible</p><p>Show backup progress</p><p>Change the backup directory</p>');
$lang->misc->feature->all['10.6.stable'][] = array('title'=>'Adjust backup mechanism', 'desc' => '<p>Increase backup settings and make backup more flexible</p><p>Show backup progress</p><p>Change the backup directory</p>');

$lang->misc->feature->all['10.5.stable'][] = array('title'=>'Adjust document layout', 'desc' => "<p>Adjust the layout method on the left side of the document library.</p><p>Add filter conditions at the bottom of the document library menu.</p>");
$lang->misc->feature->all['10.5.stable'][] = array('title'=>'Adjust document layout', 'desc' => "<p>Adjust the layout method on the left side of the document library.</p><p>Add filter conditions at the bottom of the document library menu.</p>");

$lang->misc->feature->all['10.4.stable'][] = array('title'=>'Optimize and adjust new interface', 'desc' => '<p>Detail page restore to the previous layout.</p><p>Refactore forms to add user pages</p><p>When use cases are executed, do not update the use case stause if the user manually chooses to pass and write the results.</p>');
$lang->misc->feature->all['10.4.stable'][] = array('title'=>'Optimize and adjust new interface', 'desc' => '<p>Detail page restore to the previous layout.</p><p>Refactore forms to add user pages</p><p>When use cases are executed, do not update the use case stause if the user manually chooses to pass and write the results.</p>');
$lang->misc->feature->all['10.4.stable'][] = array('title'=>'Optimize and adjust new interface', 'desc' => '<p>Detail page restore to the previous layout.</p><p>Refactore forms to add user pages</p><p>When use cases are executed, do not update the use case stause if the user manually chooses to pass and write the results.</p>');

$lang->misc->feature->all['10.3.stable'][] = array('title'=>'Fix Bug.', 'desc' => '');
$lang->misc->feature->all['10.2.stable'][] = array('title'=>'Xuan.im is integrated!', 'desc' => '');

$lang->misc->feature->all['10.0.stable'][] = array('title'=>'New UI/UX and new experience', 'desc' => '<ol><li>My Dashboard</li><li>Dynamics</li><li>Product Home</li><li>Product Overview</li><li>Roadmap</li><li>Project Home</li><li>Project overview</li><li>QA Home</li><li>Document Home</li><li>Added work report on My Dashboard</li><li>Add/Edit/Finish todos on My Dashboard</li><li>Add prodcut report on Product Home</li><li>Add prodcut overview on Product Home</li><li>Add project report on Project Home</li><li>Add project overview on Project Home</li><li>Add Testing report on QA Home</li><li>All Product/product Home/All Project/Project Home/QA Home is moved from the right of the secondary Navbar to the left.</li><li>Kanban/Burndown/Tree/ByGroup of Project/Task has been moved from the third Navbar to the secondary one; Tree/ByGroup/Task List has been integrated to a drop-down.</li><li>Bug/Build of Project on the secondary Navbar has been integrated to a drop-down.</li><li>Display build and list by group, which is more reasonable.</li><li>Added tree to display document on the left of the page.</li><li>Added quick entry to document, including Last Update, My Doc and My Favorite</li><li>Added My Favorite to Doc module.</li></ol>');

$lang->misc->feature->all['9.8.stable'][] = array('title'=>'Message centralized management', 'desc' => '<p>Centring Mail，SMS，webhook into Message</p>');
$lang->misc->feature->all['9.8.stable'][] = array('title'=>'Message centralized management', 'desc' => '<p>Centring Mail，SMS，webhook into Message</p>');
$lang->misc->feature->all['9.8.stable'][] = array('title'=>'Message centralized management', 'desc' => '<p>Centring Mail，SMS，webhook into Message</p>');
$lang->misc->feature->all['9.8.stable'][] = array('title'=>'Message centralized management', 'desc' => '<p>Centring Mail，SMS，webhook into Message</p>');

$lang->misc->feature->all['9.7.stable'][] = array('title'=>'optimize International package，Added Demo data。', 'desc' => '');

$lang->misc->feature->all['9.6.stable'][] = array('title'=>'added Webhook Interface feature', 'desc' => 'support communication with BearyChat,dingding');
$lang->misc->feature->all['9.6.stable'][] = array('title'=>'added Webhook Interface feature', 'desc' => 'support communication with BearyChat,dingding');
$lang->misc->feature->all['9.6.stable'][] = array('title'=>'added Webhook Interface feature', 'desc' => 'support communication with BearyChat,dingding');
$lang->misc->feature->all['9.6.stable'][] = array('title'=>'added Webhook Interface feature', 'desc' => 'support communication with BearyChat,dingding');

$lang->misc->feature->all['9.5.1'][] = array('title'=>'added Restricted Operatio', 'desc' => '');

$lang->misc->feature->all['9.3.beta'][] = array('title'=>'upgraded framework，Enhanced security', 'desc' => '');

$lang->misc->feature->all['9.1.stable'][] = array('title'=>'optimize Test View', 'desc' => '<p>added TestSuite,CaseLib and Test Statements</p>');
$lang->misc->feature->all['9.1.stable'][] = array('title'=>'optimize Test View', 'desc' => '<p>added TestSuite,CaseLib and Test Statements</p>');

$lang->misc->feature->all['9.0.beta'][] = array('title'=>'ZenTao CloudMail has been added.', 'desc' => '<p>ZenTao CloudMail is a free Email service launched jointly with SendCloud. Once binded with ZenTao and passed verification, users can use this service.</p>');
$lang->misc->feature->all['9.0.beta'][] = array('title'=>'ZenTao CloudMail has been added.', 'desc' => '<p>ZenTao CloudMail is a free Email service launched jointly with SendCloud. Once binded with ZenTao and passed verification, users can use this service.</p>');

$lang->misc->feature->all['8.3.stable'][] = array('title'=>'Improved Documentation.', 'desc' => '<p>Added Document Home, restructured document library, and added privileges.</p><p>Markdown Editor is supported，and privilege and version managment is added.</p>');

$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Custom Home', 'desc' => '<p>You can add blocks to Dashboard and arrange the layout.</p><p> My Zone, Product, Project, and QA all support home custom mentioned before. </p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Custom Home', 'desc' => '<p>You can add blocks to Dashboard and arrange the layout.</p><p> My Zone, Product, Project, and QA all support home custom mentioned before. </p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Custom Home', 'desc' => '<p>You can add blocks to Dashboard and arrange the layout.</p><p> My Zone, Product, Project, and QA all support home custom mentioned before. </p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Custom Home', 'desc' => '<p>You can add blocks to Dashboard and arrange the layout.</p><p> My Zone, Product, Project, and QA all support home custom mentioned before. </p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Custom Home', 'desc' => '<p>You can add blocks to Dashboard and arrange the layout.</p><p> My Zone, Product, Project, and QA all support home custom mentioned before. </p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Custom Home', 'desc' => '<p>You can add blocks to Dashboard and arrange the layout.</p><p> My Zone, Product, Project, and QA all support home custom mentioned before. </p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Custom Home', 'desc' => '<p>You can add blocks to Dashboard and arrange the layout.</p><p> My Zone, Product, Project, and QA all support home custom mentioned before. </p>');

$lang->misc->feature->all['7.4.beta'][] = array('title'=>'Product branch feature is added.', 'desc' => '<p>Product branch/platform is added, and its related Story/Plan/Bug/Case/Module has Branch added too.</p>');
$lang->misc->feature->all['7.4.beta'][] = array('title'=>'Product branch feature is added.', 'desc' => '<p>Product branch/platform is added, and its related Story/Plan/Bug/Case/Module has Branch added too.</p>');
$lang->misc->feature->all['7.4.beta'][] = array('title'=>'Product branch feature is added.', 'desc' => '<p>Product branch/platform is added, and its related Story/Plan/Bug/Case/Module has Branch added too.</p>');

$lang->misc->feature->all['7.2.stable'][] = array('title'=>'Security Enhanced', 'desc' => '<p>Admin weak passwork check is enhanced.</p><p>ok file is required when code/upload an extension.</p><p>Sensitive action requires Admin password.</p><p>Do striptags, specialchars to content entered in ZenTao.</p>');
$lang->misc->feature->all['7.2.stable'][] = array('title'=>'Security Enhanced', 'desc' => '<p>Admin weak passwork check is enhanced.</p><p>ok file is required when code/upload an extension.</p><p>Sensitive action requires Admin password.</p><p>Do striptags, specialchars to content entered in ZenTao.</p>');

$lang->misc->feature->all['7.1.stable'][] = array('title'=>'Framework of scheduled tasks is added.', 'desc' => 'Framework of scheduled tasks is added. Daily notification, Burndown Update, Backup, Send Email and so on have been added.');
$lang->misc->feature->all['7.1.stable'][] = array('title'=>'Framework of scheduled tasks is added.', 'desc' => 'Framework of scheduled tasks is added. Daily notification, Burndown Update, Backup, Send Email and so on have been added.');

$lang->misc->feature->all['6.3.stable'][] = array('title'=>'Data table is added.', 'desc' => '<p>Fields can be customized in data table and data will be displayed according to customized fields.</p>');
$lang->misc->feature->all['6.3.stable'][] = array('title'=>'Data table is added.', 'desc' => '<p>Fields can be customized in data table and data will be displayed according to customized fields.</p>');
