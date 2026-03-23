<?php
/**
 * The misc module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     misc
 * @version     $Id: English.php 824 2010-05-02 15:32:06Z wwccss $
 * @link        https://www.zentao.net
 */
$lang->misc = new stdclass();
$lang->misc->common  = 'Misc';
$lang->misc->ping    = 'Keep alive';
$lang->misc->view    = 'View';
$lang->misc->cancel  = 'Cancel';

$lang->misc->zentao = new stdclass();
$lang->misc->zentao->version           = 'Free %s';
$lang->misc->zentao->labels['about']   = 'About ZenTao';
$lang->misc->zentao->labels['support'] = 'Technical support';
$lang->misc->zentao->labels['cowin']   = 'Help Us';
$lang->misc->zentao->labels['service'] = 'Services';
$lang->misc->zentao->labels['others']  = 'Other products';

$lang->misc->zentao->icons['about']   = 'group';
$lang->misc->zentao->icons['support'] = 'question-sign';
$lang->misc->zentao->icons['cowin']   = 'hand-right';
$lang->misc->zentao->icons['service'] = 'heart';

$lang->misc->zentao->about['bizversion']   = 'Upgrade to Standard';
$lang->misc->zentao->about['official']     = "Official Website";
$lang->misc->zentao->about['changelog']    = "Release notes";
$lang->misc->zentao->about['license']      = "License";
$lang->misc->zentao->about['extension']    = "Plugin marketplace";
$lang->misc->zentao->about['follow']       = "Follow Us";

$lang->misc->zentao->support['vip']        = "Premium support";
$lang->misc->zentao->support['manual']     = "User Manual";
$lang->misc->zentao->support['faq']        = "FAQ";
$lang->misc->zentao->support['ask']        = "Q&A";
$lang->misc->zentao->support['video']      = "Video tutorials";
$lang->misc->zentao->support['qqgroup']    = "Official QQ Group";

$lang->misc->zentao->cowin['reportbug']    = "Report a bug";
$lang->misc->zentao->cowin['feedback']     = "Suggest a feature";
$lang->misc->zentao->cowin['recommend']    = "Refer a friend";

$lang->misc->zentao->service['zentaotrain'] = 'ZenTao Training';
$lang->misc->zentao->service['idc']         = 'ZenTao Cloud';
$lang->misc->zentao->service['custom']      = 'Custom Development';

global $config;
$lang->misc->zentao->others['chanzhi']  = "<img src='{$config->webRoot}theme/default/images/main/chanzhi.ico' /> Zsite";
$lang->misc->zentao->others['zdoo']     = "<img src='{$config->webRoot}theme/default/images/main/zdoo.ico' /> ZDOO";

$lang->misc->zentao->others['ydisk']    = "<img src='{$config->webRoot}theme/default/images/main/ydisk.ico' /> Y Disk";
$lang->misc->zentao->others['meshiot' ] = "<img src='{$config->webRoot}theme/default/images/main/meshiot.ico' /> MeshioT";

$lang->misc->mobile      = "Mobile Access";
$lang->misc->noGDLib     = "Access via mobile browser: <strong>%s</strong>";
$lang->misc->copyright   = '© 2009 - ' . date("Y") . ' <a href="https://www.zentao.pm">EasyCorp</a> Email: support@zentao.pm';
$lang->misc->checkTable  = "Check and repair tables";
$lang->misc->needRepair  = "Repair tables";
$lang->misc->repairTable = "Database tables might be corrupted due to power loss. Please check and repair.";
$lang->misc->repairFail  = "Repair failed. Run <code>myisamchk -r -f %s.MYI</code> in the database directory to fix it.";
$lang->misc->withoutCmd  = 'Repair failed';
$lang->misc->connectFail = "Database connection failed. Error: %s.<br/>Check the MySQL error log.";
$lang->misc->tableName   = "Table Name";
$lang->misc->tableStatus = "Status";
$lang->misc->novice      = "New to ZenTao? Would you like to start the tutorial?";
$lang->misc->showAnnual  = 'Annual Summary';
$lang->misc->annualDesc  = 'The Annual Summary feature is now available (Report -> Annual Summary). <a href="%s" class="btn btn-mini btn-primary">View now</a>';
$lang->misc->remind      = 'New feature alert';

$lang->misc->expiredTipsTitle    = 'Dear Admin,';
$lang->misc->expiredCountTips    = 'You have <span class="text-blue" title="%s">%s plugins</span> expiring soon. Please contact your admin to renew or uninstall them.';
$lang->misc->expiredPluginTips   = 'Expired plugins: %s.';
$lang->misc->expiringPluginTips  = 'Plugins that will expire soon: %s.';
$lang->misc->expiredTipsForAdmin = '%s plugins are expiring soon. To avoid service disruption, please go to the plugin manager to renew or uninstall them.';
$lang->misc->metriclibTips       = 'A new metric library index is available. Updating it improves query speeds. Go to Admin -> Settings -> Metric Library to update.';

$lang->misc->noticeRepair = "<h5>Contact your admin to repair.</h5>
<h5>If you are an admin, log in to the server and create a file named <span>%s</span>.</h5>
<p>Note:</p>
<ol>
<li>Leave the file empty.</li>
<li>If it exists, delete and recreate it.</li></ol>";

$lang->misc->feature = new stdclass();
$lang->misc->feature->lastest           = 'Latest Version';
$lang->misc->feature->detailed          = 'Details';
$lang->misc->feature->introduction      = 'New features';
$lang->misc->feature->tutorial          = 'Tutorial';
$lang->misc->feature->tutorialImage     = 'theme/default/images/main/tutorial_en.png';
$lang->misc->feature->youngBlueTheme    = 'Youth Blue theme';
$lang->misc->feature->youngBlueImage    = 'theme/default/images/main/new_theme_en.png';
$lang->misc->feature->visions           = "Switch views";
$lang->misc->feature->nextStep          = 'Next';
$lang->misc->feature->prevStep          = 'Previous';
$lang->misc->feature->close             = 'Get started';
$lang->misc->feature->learnMore         = 'Learn More';
$lang->misc->feature->downloadFile      = 'Download release notes';
$lang->misc->feature->tutorialDesc      = '<p>ZenTao 15 introduces several new features. Check out the <strong>Tutorial</strong> to get started.</p><p>Hover over your avatar and click <span style="color: #0c60e1">[Tutorial]</span> to open it..</p>';
$lang->misc->feature->themeDesc         = '<p>Experience the new "Youth Blue" theme in ZenTao 15 for a modern and user-friendly interface.</p><p>Hover over your avatar, go to <span style="color: #0c60e1">[Theme]</span>, and select Youth Blue to apply it.</p>';
$lang->misc->feature->visionsDesc       = '<p>Views are introduced in version 16.5. Manage R&D tasks in the <span style="color: #0c60e1">[Full Feature View]</span>, and daily office tasks in the <span style="color: #0c60e1">[Operation Management View]</span>.</p><p>Your current view is displayed next to your avatar. Click it to switch views.</p>';
$lang->misc->feature->visionsImage      = 'theme/default/images/main/visions_en.png';
$lang->misc->feature->aiPrompts         = 'AI prompts';
$lang->misc->feature->aiPromptsImage    = 'theme/default/images/main/ai_prompts_en.svg';
$lang->misc->feature->promptDesign      = 'Prompt design';
$lang->misc->feature->promptDesignImage = 'theme/default/images/main/prompt_design_en.svg';
$lang->misc->feature->promptExec        = 'Prompt Execution';
$lang->misc->feature->promptExecImage   = 'theme/default/images/main/prompt_exec_en.svg';
$lang->misc->feature->promptLearnMore   = 'https://www.zentao.pm/book/zentaopms/1097.html';

/* Release Date. */
$lang->misc->releaseDate['22.0']        = '2026-03-05';
$lang->misc->releaseDate['22.0.beta']   = '2026-01-27';
$lang->misc->releaseDate['21.7.9']      = '2026-03-02';
$lang->misc->releaseDate['21.7.8']      = '2025-12-15';
$lang->misc->releaseDate['21.7.7']      = '2025-10-29';
$lang->misc->releaseDate['21.7.6']      = '2025-09-29';
$lang->misc->releaseDate['21.7.5']      = '2025-09-11';
$lang->misc->releaseDate['21.7.4']      = '2025-07-29';
$lang->misc->releaseDate['21.7.3']      = '2025-07-03';
$lang->misc->releaseDate['21.7.2']      = '2025-06-27';
$lang->misc->releaseDate['21.7.1']      = '2025-05-30';
$lang->misc->releaseDate['21.7']        = '2025-05-16';
$lang->misc->releaseDate['21.6.1']      = '2025-04-30';
$lang->misc->releaseDate['21.6']        = '2025-04-11';
$lang->misc->releaseDate['21.6.beta']   = '2025-03-21';
$lang->misc->releaseDate['21.5']        = '2025-03-06';
$lang->misc->releaseDate['21.4']        = '2025-01-15';
$lang->misc->releaseDate['21.3']        = '2024-12-27';
$lang->misc->releaseDate['21.2']        = '2024-12-03';
$lang->misc->releaseDate['21.1']        = '2024-11-15';
$lang->misc->releaseDate['21.0']        = '2024-11-01';
$lang->misc->releaseDate['20.8']        = '2024-10-21';
$lang->misc->releaseDate['20.7.1']      = '2024-09-30';
$lang->misc->releaseDate['20.7']        = '2024-09-14';
$lang->misc->releaseDate['20.6']        = '2024-08-30';
$lang->misc->releaseDate['20.5']        = '2024-08-16';
$lang->misc->releaseDate['18.13']       = '2024-08-09';
$lang->misc->releaseDate['20.4']        = '2024-08-02';
$lang->misc->releaseDate['20.3.0']      = '2024-07-22';
$lang->misc->releaseDate['20.2.0']      = '2024-07-10';
$lang->misc->releaseDate['20.1.1']      = '2024-06-21';
$lang->misc->releaseDate['20.1.0']      = '2024-06-03';
$lang->misc->releaseDate['20.0']        = '2024-04-30';
$lang->misc->releaseDate['18.12']       = '2024-04-12';
$lang->misc->releaseDate['20.0.beta2']  = '2024-03-15';
$lang->misc->releaseDate['18.11']       = '2024-02-28';
$lang->misc->releaseDate['18.10.1']     = '2024-01-17';
$lang->misc->releaseDate['20.0.beta1']  = '2024-01-26';
$lang->misc->releaseDate['20.0.alpha1'] = '2024-01-08';
$lang->misc->releaseDate['18.10']       = '2023-12-18';
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
// $lang->misc->releaseDate['20.0.alpha1'] = '2020-10-30';
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
$lang->misc->feature->all['21.7.2'][]      = array('title' => 'Optimized the document functionality. Project template functionality is available.', 'desc' => '');
$lang->misc->feature->all['21.7.1'][]      = array('title' => 'Added top toolbar to document editor; supported single-project workflow config; optimized requirements; added deliverable management.', 'desc' => '');
$lang->misc->feature->all['21.7'][]        = array('title' => 'Supported bulk editing of parent-child stages in Waterfall, Waterfall Plus, and IPD projects; enabled cross-execution task dependencies; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['21.6.1'][]      = array('title' => 'Fixed document-related bugs.', 'desc' => '');
$lang->misc->feature->all['21.6'][]        = array('title' => 'Optimized Jira import; added multi-user document collaboration.', 'desc' => '');
$lang->misc->feature->all['21.6.beta'][]   = array('title' => 'Added Jira 2.0 and Confluence imports.', 'desc' => '');
$lang->misc->feature->all['21.5'][]        = array('title' => 'Optimized system and document performance; improved attachment upload speed in comments.', 'desc' => '');
$lang->misc->feature->all['21.4'][]        = array('title' => 'Refactored the opportunity module; optimized testing and workflow details.', 'desc' => '');
$lang->misc->feature->all['21.3'][]        = array('title' => 'Added "Delayed" filter to program, project, and execution lists; added onboarding guide after project creation; allowed splitting phases with existing data into sub-phases in Waterfall projects; supported tooltips for new workflow fields; refactored the ticket creation page; added the opportunity feature to Agile projects; enabled issues, risks, opportunities, processes, QA, and meetings for projects without iterations.', 'desc' => '');
$lang->misc->feature->all['21.2'][]        = array('title' => 'Added applications under releases; grouped programs and added permission hints in document dropdowns; included attachments when copying tasks, requirements, bugs, and test cases; allowed admins to delete public contacts; added task search and "Delayed" filter to execution lists; supported workflow extensions for project initiation; added version management for BI; optimized document editor compatibility; added copy feature for feedback; enabled module management on empty feedback creation pages; added notifications for issues, risks, opportunities, and audits; added audit and baseline lists to My Dashboard; optimized review and audit detail pages; supported task search in Gantt charts; added requirement change confirmation for designs; supported exporting review and baseline status reports.', 'desc' => '');
$lang->misc->feature->all['21.1'][]        = array('title' => 'Optimized API documentation space and core feature access; improved host management; refined object relationships; added zero-padding for metrics; optimized DuckDB and release features.', 'desc' => '');
$lang->misc->feature->all['21.0'][]        = array('title' => 'Optimized document features; improved product and project process templates in BI Designer.', 'desc' => '');
$lang->misc->feature->all['20.8'][]        = array('title' => 'Optimized document features and task dependencies; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['20.7.1'][]      = array('title' => 'Fixed known bugs.', 'desc' => '');
$lang->misc->feature->all['20.7'][]        = array('title' => 'Optimized onboarding guide, custom menus, and workflows; added Contribution module to the OR view.', 'desc' => '');
$lang->misc->feature->all['20.6'][]        = array('title' => 'Supported multi-view configuration in workflows; allowed using workflow fields as conditions in approval flows; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['20.5'][]        = array('title' => 'Optimized document features; added 23 built-in metrics.', 'desc' => '');
$lang->misc->feature->all['18.13'][]       = array('title' => 'Optimized performance for My To-Do, Requirement, Task, and Bug lists, and Product/Project detail pages; supported Dameng database; supported copying custom workflow fields and values when duplicating requirements, tasks, bugs, and test cases; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['20.4'][]        = array('title' => 'Added Message Center; improved release management; added branch and tag management; supported adding signers in approval flows.', 'desc' => '');
$lang->misc->feature->all['20.3.0'][]      = array('title' => 'Supported custom drill-down in pivot tables; enabled viewing multi-level pool, business, user, and R&D requirements in the requirement pool matrix; added confirmation for downstream requirements when upstream requirements change; allowed associating business and user requirements at any product roadmap level; optimized action buttons and search tags for pool, business, and user requirements; automatically recalculated upstream requirement stages when restoring deleted requirements.', 'desc' => '');
$lang->misc->feature->all['20.2.0'][]      = array('title' => 'Optimized product matrix; added platform app configuration; improved approval flows; added Business Requirements to the OR view; added "Roadmap Set" and "Charter Initiated" stages for User Requirements; supported infinite levels and R&D stage calculation for Business/User Requirements; supported delivery stage calculation when distributing or splitting OR requirements; allowed distributing OR requirements as Business Requirements; calculated OR and User requirements during legacy upgrades; added TR4A review point to the development stage.', 'desc' => '');
$lang->misc->feature->all['20.1.1'][]      = array('title' => 'Refactored core PHP and UI frameworks, core forms, and dashboards for a revamped user experience; supported APCu caching for major performance boosts; added search to baseline review lists; added "To Do" feature to the OR view; supported custom stages and review points in IPD projects.', 'desc' => '');
$lang->misc->feature->all['20.1.0'][]      = array('title' => 'Supported APCu caching for significant performance boosts; optimized interaction designs and DevOps details; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['20.0'][]        = array('title' => 'Refactored core PHP and UI frameworks, core forms, and dashboards for an upgraded user experience.', 'desc' => '');
$lang->misc->feature->all['18.12'][]       = array('title' => 'Removed information and logic for non-R&D users; added expiration reminders for technical services; supported itemized metric management, custom metrics, base metric library, and one-click recalculation of historical metrics; added requirement pool matrix; allowed removing initiated requirements from roadmaps; added feedback settings to the Operation Management view; added the "Associated Demand Pool" search filter for demand pool requirements.', 'desc' => '');
$lang->misc->feature->all['20.0.beta2'][]  = array('title' => 'Optimized details; fixed known bugs.', 'desc' => '');
$lang->misc->feature->all['18.11'][]       = array('title' => 'Added AI mini-programs; supported metric references and global filters on large screens; added feedback feature to the OR view; added keywords to pool requirements; allowed redistributing pool requirements after withdrawing user requirements.', 'desc' => '');
$lang->misc->feature->all['18.10.1'][]     = array('title' => 'Added notifications and supported product lines in the requirement pool; allowed distributing a single requirement to multiple products.', 'desc' => '');
$lang->misc->feature->all['20.0.beta1'][]  = array('title' => 'Refactored core code and upgraded the UI for better performance, stronger security, and enhanced user experience.', 'desc' => '');
$lang->misc->feature->all['20.0.alpha1'][] = array('title' => 'Internal release for large-scale code refactoring and comprehensive UI upgrades.', 'desc' => '');
$lang->misc->feature->all['18.10'][]       = array('title' => 'Supported importing test cases from other libraries; allowed exporting documents with adaptive image scaling for Word; saved history sorting preferences in cookies; optimized log editing logic; carried over attachments when converting feedback or tickets; added keyword and CC fields to feedback; added liquid fill gauge charts; optimized metric collection and display.', 'desc' => '');
$lang->misc->feature->all['18.9'][]        = array('title' => 'Integrated AI Large Language Models (LLMs); introducedan upgraded client version for meetings; added participants to test requests; supported online preview for video attachments; allowed customizing review inspection categories.', 'desc' => '');
$lang->misc->feature->all['18.8'][]        = array('title' => 'Added metrics and application inspection dashboards to BI; added a configuration wizard to the DevOps Platform; added market management to the Requirement and Market Management view; revamped client navigation and personal center.', 'desc' => '');
$lang->misc->feature->all['18.7'][]        = array('title' => 'Added cloud-native platform, artifact repository, and application management to DevOps; optimized navigation and UI interactions; 
added AI prompt designer with LLM integration and custom AI apps.', 'desc' => '');
$lang->misc->feature->all['18.6'][]        = array('title' => 'Optimized performance of frequently used lists; improved feature details for BI and Waterfall projects; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['18.5'][]        = array('title' => 'Supported importing Academy courses from the cloud and previewing PDFs within courses; optimized loading speeds for frequently used lists; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['18.4'][]        = array('title' => 'Optimized core list performance; supported Dameng database; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['18.4.beta1'][]  = array('title' => 'Fixed bugs.', 'desc' => '');
$lang->misc->feature->all['18.4.alpha1'][] = array('title' => 'Optimized permissions and document interactions; introduced test scenarios; supported importing test cases from XMind; comprehensively upgraded dashboards, pivot tables, charts, and data tables in BI.', 'desc' => '');
$lang->misc->feature->all['18.3'][]        = array('title' => 'Supported customizing language items, menus, and search tags in secondary development; added toggleable editor features in secondary development; supported auto-saving form drafts and restoring unsaved data upon return.', 'desc' => '');
$lang->misc->feature->all['18.2'][]        = array('title' => 'Added Agile Plus and Waterfall Plus management models; supported infinite sub-phases for Waterfall projects; revamped Admin UI; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['18.1'][]        = array('title' => 'Optimized interactions for automation testing solutions; added snapshot management; supported online PPT collaboration in the ZenTao client; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['18.0'][]        = array('title' => 'Launched automation testing solutions; added tickets to the Operation Management view; supported all notification types in approval flows; improved earned value calculation rules.', 'desc' => '');
$lang->misc->feature->all['18.0.beta3'][]  = array('title' => 'Upgraded the Statistics module to BI, including 5 built-in macro-management dashboards.', 'desc' => '');
$lang->misc->feature->all['18.0.beta2'][]  = array('title' => 'Optimized multi-branch/multi-platform products; supported creating twin requirements; allowed plans, builds, and releases to link cross-branch requirements and bugs; added bot chat mechanism to the ZenTao client.', 'desc' => '');
$lang->misc->feature->all['18.0.beta1'][]  = array('title' => 'Improved core workflows; added standalone projects and iterationless projects; allowed projects to link products across programs; supported switching between ZenTao Lite and Full Lifecycle Management modes.', 'desc' => '');
$lang->misc->feature->all['17.8'][]        = array('title' => 'Revamped status colors in lists and dashboards; optimized the task effort page.', 'desc' => '');
$lang->misc->feature->all['17.7'][]        = array('title' => 'Optimized tables in the transition version; added ticket feature; optimized feedback; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['17.6.2'][]      = array('title' => 'Updated Green, ZenTao Blue, and Youth Blue themes; supported bulk uploading of attachments; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['17.6.1'][]      = array('title' => 'Optimized the processing logic for multi-user tasks; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['17.6'][]        = array('title' => 'Optimized requirement processing logic; separated permissions for user and software requirements; supported drag-and-drop to manage task dependencies in Gantt charts; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['17.5'][]        = array('title' => 'Provided efficient visual statistical tools; migrated database engine from MyISAM to InnoDB for better performance; upgraded Gantt charts; allowed copying more data like tasks when duplicating projects in Premium; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['17.4'][]        = array('title' => 'Optimized visuals on detail pages and page redirection logic; improved Kanban features; optimized document creation and editing pages; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['17.3'][]        = array('title' => 'Optimized UI for Statistics, Admin, and other modules; improved data synchronization in the test case library; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['17.2'][]        = array('title' => 'Adjusted the display of Agile project blocks; optimized UI for programs, projects, and tests; improved UX details; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['17.1'][]        = array('title' => 'Fixed interaction issues in Execution and Project modules; fulfilled high-priority customer requests; improved UX details; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['17.0'][]        = array('title' => 'Optimized UX details; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['17.0.beta2'][]  = array('title' => 'Optimized UX details; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['17.0.beta1'][]  = array('title' => 'Fulfilled high-priority customer requests; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['16.5'][]        = array('title' => 'Fixed bugs.', 'desc' => '');
$lang->misc->feature->all['16.5.beta1'][]  = array('title' => 'Merged all code into a single package; optimized the upgrade process.', 'desc' => '');
$lang->misc->feature->all['16.4'][]        = array('title' => 'Supported Jira import; improved the plugin extension mechanism.', 'desc' => '');
$lang->misc->feature->all['16.3'][]        = array('title' => 'Allowed Kanbans to link plans, releases, builds, and iterations; improved UX details.', 'desc' => '');
$lang->misc->feature->all['16.2'][]        = array('title' => 'Added professional R&D Kanban; supported creating Kanban model projects; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['16.1'][]        = array('title' => 'Added status management and Kanban views to plans; optimized the upgrade process; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['16.0'][]        = array('title' => 'Added general Kanban; improved branch management; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['16.0.beta1'][]  = array('title' => 'Added Waterfall model projects and task Kanban; improved branch management and details; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['15.7.1'][]      = array('title' => 'Fixed bugs.', 'desc' => '');
$lang->misc->feature->all['15.7'][]        = array('title' => 'Added API library; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['15.6'][]        = array('title' => 'Fixed bugs.', 'desc' => '');
$lang->misc->feature->all['15.5'][]        = array('title' => 'Added Kanban views for programs, products, and projects; added global "Add" feature and onboarding guide; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['15.4'][]        = array('title' => 'Fixed bugs.', 'desc' => '');
$lang->misc->feature->all['15.3'][]        = array('title' => 'Updated UI styles; optimized documents; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['15.2'][]        = array('title' => 'Optimized the new version upgrade process; added Execution Kanban.', 'desc' => '');

$lang->misc->feature->all['15.0.3'][]      = array('title' => 'Fixed bugs.', 'desc' => '');
$lang->misc->feature->all['15.0.2'][]      = array('title' => 'Fixed bugs.', 'desc' => '');
$lang->misc->feature->all['15.0.1'][]      = array('title' => 'Fixed bugs.', 'desc' => '');
$lang->misc->feature->all['15.0'][]        = array('title' => 'Fixed bugs.', 'desc' => '');
$lang->misc->feature->all['15.0.rc3'][]    = array('title' => 'Optimized details; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['15.0.rc2'][]    = array('title' => 'Fixed bugs; optimized UI interactions.', 'desc' => '');
$lang->misc->feature->all['15.0.rc1'][]    = array('title' => 'Upgraded to ZenTao 15; refactored navigation and document library; added program management.', 'desc' => '');
$lang->misc->feature->all['12.5.3'][]      = array('title' => 'Optimized the Annual Summary.', 'desc' => '');
$lang->misc->feature->all['12.5.2'][]      = array('title' => 'Fixed bugs.', 'desc' => '');
$lang->misc->feature->all['12.5.1'][]      = array('title' => 'Fixed bugs.', 'desc' => '');
$lang->misc->feature->all['12.5.stable'][] = array('title' => 'Fixed bugs; fulfilled high-priority requirements.', 'desc' => '');

$lang->misc->feature->all['12.4.4'][] = array('title' => 'Supported compatibility with Professional and Standard versions.', 'desc' => '');
$lang->misc->feature->all['12.4.3'][] = array('title' => 'Fixed bugs.', 'desc' => '');
$lang->misc->feature->all['12.4.2'][] = array('title' => 'Fixed bugs.', 'desc' => '');
$lang->misc->feature->all['12.4.1'][] = array('title' => 'Fixed bugs.', 'desc' => '');

$lang->misc->feature->all['12.4.stable'][] = array('title' => 'Fixed bugs.', 'desc' => '');

$lang->misc->feature->all['12.3.3'][] = array('title' => 'Fixed bugs.', 'desc' => '');
$lang->misc->feature->all['12.3.2'][] = array('title' => 'Fixed workflows.', 'desc' => '');
$lang->misc->feature->all['12.3.1'][] = array('title' => 'Fixed high-severity bugs.', 'desc' => '');
$lang->misc->feature->all['12.3'][]   = array('title' => 'Integrated unit testing; completed the continuous integration loop.', 'desc' => '');
$lang->misc->feature->all['12.2'][]   = array('title' => 'Added parent-child requirements; supported compatibility with the latest Xuanxuan IM.', 'desc' => '');
$lang->misc->feature->all['12.1'][]   = array('title' => 'Added integration.', 'desc' => '<p>Added integration, and build in Jenkins.</p>');
$lang->misc->feature->all['12.0.1'][] = array('title' => 'Fixed bugs.', 'desc' => '');

$lang->misc->feature->all['12.0'][]   = array('title'=>'Move repo function to zentao', 'desc' => '');
$lang->misc->feature->all['12.0'][]   = array('title'=>'Add annual summary', 'desc' => 'Show annual summary by role.');
$lang->misc->feature->all['12.0'][]   = array('title' => 'Optimized details; fixed bugs.', 'desc' => '');

$lang->misc->feature->all['11.7'][]   = array('title' => 'Optimized details; fixed bugs.', 'desc' => '<p>Added option to enable Agile concepts.</p><p>Added WeCom to webhook types.</p><p>Supported sending personal notifications to DingTalk.</p>');
$lang->misc->feature->all['11.6.5'][] = array('title' => 'Fixed bugs.', 'desc' => '');
$lang->misc->feature->all['11.6.4'][] = array('title' => 'Optimized details; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['11.6.3'][] = array('title' => 'Fixed bugs.', 'desc' => '');
$lang->misc->feature->all['11.6.2'][] = array('title' => 'Optimized details; fixed bugs.', 'desc' => '');
$lang->misc->feature->all['11.6.1'][] = array('title' => 'Optimized details; fixed bugs.', 'desc' => '');

$lang->misc->feature->all['11.6.stable'][] = array('title'=>'Improving the International Edition Interface', 'desc' => '');
$lang->misc->feature->all['11.6.stable'][] = array('title' => 'Added translation feature.', 'desc' => '');

$lang->misc->feature->all['11.5.2'][] = array('title' => 'Enhanced ZenTao security; added weak password checks for login.', 'desc' => '');
$lang->misc->feature->all['11.5.1'][] = array('title' => 'Supported passwordless login via third-party apps; fixed bugs.', 'desc' => '');

$lang->misc->feature->all['11.5.stable'][] = array('title'=>'Optimize details and fix bug.', 'desc' => '');
$lang->misc->feature->all['11.5.stable'][] = array('title'=>'Added filters to Dynamics', 'desc' => '');
$lang->misc->feature->all['11.5.stable'][] = array('title' => 'Integrated the latest ZenTao Client.', 'desc' => '');

$lang->misc->feature->all['11.4.1'][]      = array('title' => 'Optimized details; fixed bugs.', 'desc' => '');

$lang->misc->feature->all["11.4.stable"][] = array('title' => 'Optimized details; fixed bugs.', 'desc' => '<p>Enhanced test task management.</p><p>Optimized interactions when linking {$lang->SRCommon} and bugs to plans, releases, and builds.</p><p>Added an option to show or hide documents in child categories within the document library.</p><p>Optimized details and fixed bugs.</p>');

$lang->misc->feature->all['11.3.stable'][] = array('title' => 'Optimized details; fixed bugs.', 'desc' => '<p>Added child plans to plans.</p><p>Optimized dropdown (Chosen) interactions.</p><p>Added timezone settings.</p><p>Optimized document library and document modules.</p>');

$lang->misc->feature->all['11.2.stable'][] = array('title' => 'Optimized details; fixed bugs.', 'desc' => '<p>Added upgrade logs and post-upgrade database checks.</p><p>Fixed ZenTao Client integration bugs and optimized other details.</p>');

$lang->misc->feature->all['11.1.stable'][] = array('title' => 'Mainly fixed bugs.', 'desc' => '');

$lang->misc->feature->all['11.0.stable'][] = array('title' => 'Integrated Xuanxuan IM.', 'desc' => '');

$lang->misc->feature->all['10.6.stable'][] = array('title'=>'Adjust backup mechanism', 'desc' => '<p>Increase backup settings and make backup more flexible</p><p>Show backup progress</p><p>Change the backup directory</p>');
$lang->misc->feature->all['10.6.stable'][] = array('title' => 'Optimized and adjusted menus.', 'desc' => '<p>Adjusted Admin menus.</p><p>Adjusted secondary menus for My Dashboard and Project.</p>');

$lang->misc->feature->all['10.5.stable'][] = array('title'=>'Adjust document layout', 'desc' => "<p>Adjust the layout method on the left side of the document library.</p><p>Add filter conditions at the bottom of the document library menu.</p>");
$lang->misc->feature->all['10.5.stable'][] = array('title' => 'Adjusted child task logic and optimized the display of parent-child tasks.', 'desc' => '');

$lang->misc->feature->all['10.4.stable'][] = array('title'=>'Optimize and adjust new interface', 'desc' => '<p>Detail page restore to the previous layout.</p><p>Refactore forms to add user pages</p><p>When use cases are executed, do not update the use case stause if the user manually chooses to pass and write the results.</p>');
$lang->misc->feature->all['10.4.stable'][] = array('title'=>'After the user machine hibernates and the login fails, the session will be refreshed again.', 'desc' => '');
$lang->misc->feature->all['10.4.stable'][] = array('title' => 'Upgraded existing API mechanisms.', 'desc' => '');

$lang->misc->feature->all['10.3.stable'][] = array('title' => 'Fixed bugs.', 'desc' => '');
$lang->misc->feature->all['10.2.stable'][] = array('title' => 'Integrated Xuanxuan IM.', 'desc' => '');

$lang->misc->feature->all['10.0.stable'][] = array('title' => 'Revamped UI and interaction experience.', 'desc' => '<ol><li>Revamped My Dashboard.</li><li>Revamped Dynamics page.</li><li>Revamped Product Home.</li><li>Revamped Product Overview.</li><li>Revamped Roadmap.</li><li>Revamped Project Home.</li><li>Revamped Project Overview.</li><li>Revamped QA Home.</li><li>Revamped Document Home.</li><li>Added work statistics block to My Dashboard.</li><li>Allowed adding, editing, and finishing to-dos directly in the To-Do block on My Dashboard.</li><li>Added product statistics block to Product Home.</li><li>Added product overview block to Product Home.</li><li>Added project statistics block to Project Home.</li><li>Added project overview block to Project Home.</li><li>Added QA statistics block to QA Home.</li><li>Moved "All Products", "Product Home", "All Projects", "Project Home", and "QA Home" buttons from the right to the left of the secondary navigation.</li><li>Moved Kanban, Burndown, Tree View, and Group View buttons in the project task list from the tertiary to the secondary navigation; integrated Tree View, Group View, and Task List into a dropdown menu.</li><li>Grouped QA-related navigation items (Bugs, Builds, and Test Requests) under a single dropdown in the project secondary navigation.</li><li>Grouped Build and Test Request lists by product for a more logical layout.</li><li>Added a tree view to the left side of Documents.</li><li>Added quick access features to Documents, including "Recently Updated", "My Documents", and "My Favorites".</li><li>Added a "Favorite" feature to Documents.</li></ol>');

$lang->misc->feature->all['9.8.stable'][] = array('title'=>'Message centralized management', 'desc' => '<p>Gather Mail，SMS，webhook into Message</p>');
$lang->misc->feature->all['9.8.stable'][] = array('title'=>'Add recurred Todo', 'desc' => '');
$lang->misc->feature->all['9.8.stable'][] = array('title'=>"Add Block of 'AssignedToMe'", 'desc' => '');
$lang->misc->feature->all['9.8.stable'][] = array('title' => 'Supported selecting multiple test requests in a project to generate reports.', 'desc' => '');

$lang->misc->feature->all['9.7.stable'][] = array('title' => 'Optimized the International package; added English demo data.', 'desc' => '');

$lang->misc->feature->all['9.6.stable'][] = array('title'=>'Added Webhook Interface feature', 'desc' => 'Support communication with BearyChat, Dingding');
$lang->misc->feature->all['9.6.stable'][] = array('title'=>'Added Point', 'desc' => 'More skilled application, more score');
$lang->misc->feature->all['9.6.stable'][] = array('title'=>'Added multiple user task and child tasks to Project', 'desc' => '');
$lang->misc->feature->all['9.6.stable'][] = array('title' => 'Added product line feature to the Product view.', 'desc' => '');

$lang->misc->feature->all['9.5.1'][] = array('title' => 'Added restricted actions.', 'desc' => '');

$lang->misc->feature->all['9.3.beta'][] = array('title' => 'Upgraded the framework; enhanced application security.', 'desc' => '');

$lang->misc->feature->all['9.1.stable'][] = array('title'=>'optimize Test View', 'desc' => '<p>Added TestSuite, CaseLibrary and QA Report</p>');
$lang->misc->feature->all['9.1.stable'][] = array('title' => 'Supported grouping test steps in test cases.', 'desc' => '');

$lang->misc->feature->all['9.0.beta'][] = array('title'=>'ZenTao CloudMail has been added.', 'desc' => '<p>ZenTao CloudMail is a free Email service launched jointly with SendCloud. Once binded with ZenTao and passed verification, users can use this service.</p>');
$lang->misc->feature->all['9.0.beta'][] = array('title' => 'Optimized the rich text and Markdown editors.', 'desc' => '');

$lang->misc->feature->all['8.3.stable'][] = array('title' => 'Optimized document features.', 'desc' => '<p>Added Document Home, reorganized the document library structure, and added permissions.</p><p>Added multiple file browsing modes, supported Markdown for documents, and added document permission and file version management.</p>');

$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Custom Home', 'desc' => '<p>You can add blocks to Dashboard and arrange the layout.</p><p> My Zone, Product, Project, and QA all support home custom mentioned before. </p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Custom Navigation', 'desc' => '<p>You can decide which project show in navigation bar and the order of projects shown in the bar.</p><p> Hover on the navigation bar and a sign will show to its right. Click the sign and a dialog box "Custom Navigation" will show. Drag the block name to switch its order on navigation bar.</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Batch Add/Edit Custom', 'desc' => '<p>You can batch add and edit fields on custom pages.</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Custom Story/Task/Bug/Case', 'desc' => '<p>You can custom fileds when add a Story/Task/Bug/Case.</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Custom Export', 'desc' => '<p>You can custom fileds when export a Story/Task/Bug/Case pages. You can also save it as template for next export.</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Story/Task/Bug/Case Search ', 'desc' => '<p>On Story/Task/Bug/Case List page, you can do a combined search on Modules and Tabs.</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title' => 'Added onboarding tutorial.', 'desc' => '<p>Added an onboarding tutorial to help new users quickly learn how to use ZenTao.</p>');

$lang->misc->feature->all['7.4.beta'][] = array('title'=>'Product branch feature is added.', 'desc' => '<p>Product branch/platform is added, and its related Story/Plan/Bug/Case/Module has Branch added too.</p>');
$lang->misc->feature->all['7.4.beta'][] = array('title'=>'Release Module is improved.', 'desc' => '<p>Stop action has been added. If Stop to manage it, the Release will not show when Report Bug.</p><p>Bugs that have been omitted in the Release will be related manually.</p>');
$lang->misc->feature->all['7.4.beta'][] = array('title' => 'Optimized the creation pages for {$lang->SRCommon} and bugs.', 'desc' => '');

$lang->misc->feature->all['7.2.stable'][] = array('title'=>'Security Enhanced', 'desc' => '<p>Admin weak passwork check is enhanced.</p><p>ok file is required when code/upload an extension.</p><p>Sensitive action requires Admin password.</p><p>Do striptags, specialchars to content entered in ZenTao.</p>');
$lang->misc->feature->all['7.2.stable'][] = array('title' => 'Optimized details.', 'desc' => '');

$lang->misc->feature->all['7.1.stable'][] = array('title'=>'Framework of Cron is added.', 'desc' => 'Framework of Cron is added. Daily notification, Burndown Update, Backup, Send Email and so on have been added.');
$lang->misc->feature->all['7.1.stable'][] = array('title' => 'Provided RPM and DEB packages.', 'desc' => '');

$lang->misc->feature->all['6.3.stable'][] = array('title'=>'Data table is added.', 'desc' => '<p>Fields can be customized in data table and data will be displayed according to customized fields.</p>');
$lang->misc->feature->all['6.3.stable'][] = array('title' => 'Continued optimizing details.', 'desc' => '');
