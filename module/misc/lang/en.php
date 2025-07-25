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
$lang->misc->ping    = 'Ping';
$lang->misc->view    = 'Check';
$lang->misc->cancel  = 'Cancel';

$lang->misc->zentao = new stdclass();
$lang->misc->zentao->version           = 'ALM Version %s';
$lang->misc->zentao->labels['about']   = 'About ZenTao';
$lang->misc->zentao->labels['support'] = 'Tech Support';
$lang->misc->zentao->labels['cowin']   = 'Help Us';
$lang->misc->zentao->labels['service'] = 'Service';
$lang->misc->zentao->labels['others']  = 'From ZenTao Software';

$lang->misc->zentao->icons['about']   = 'group';
$lang->misc->zentao->icons['support'] = 'question-sign';
$lang->misc->zentao->icons['cowin']   = 'hand-right';
$lang->misc->zentao->icons['service'] = 'heart';

$lang->misc->zentao->about['bizversion']   = 'ZenTao Biz';
$lang->misc->zentao->about['official']     = "Official Website";
$lang->misc->zentao->about['changelog']    = "Revision Log";
$lang->misc->zentao->about['license']      = "License";
$lang->misc->zentao->about['extension']    = "Plugin Platform";
$lang->misc->zentao->about['follow']       = "Follow Us";

$lang->misc->zentao->support['vip']        = "VIP Support";
$lang->misc->zentao->support['manual']     = "User Manual";
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

$lang->misc->mobile      = "Mobile Access";
$lang->misc->noGDLib     = "Please visit <strong>%s</strong> in the browser of your phone.";
$lang->misc->copyright   = "&copy; 2009 - " . date('Y') . " <a href='https://easycorp.ltd/' target='_blank'>ZenTao Software</a> Email <a href='mailto:Renee@easycorp.ltd'>Renee@easycorp.ltd</a>";
$lang->misc->checkTable  = "Check Data Table";
$lang->misc->needRepair  = "Repair Table";
$lang->misc->repairTable = "Database table might be damaged due to power outage. Please check and repair!";
$lang->misc->repairFail  = "Failed to repair. Please go to the data directory of your database, and try to execute <code>myisamchk -r -f %s.MYI</code> repair.";
$lang->misc->withoutCmd  = 'Failed to repair.';
$lang->misc->connectFail = "Failed to connect to the database. Error: %s，<br/> Please check the MySQL error log and troubleshoot.";
$lang->misc->tableName   = "Table Name";
$lang->misc->tableStatus = "Status";
$lang->misc->novice      = "New to ZenTao? Do you want to start ZenTao Tutorial?";
$lang->misc->showAnnual  = 'Add Annual Summary';
$lang->misc->annualDesc  = 'After version 12.0, Annual Summary can be viewed on 『Report->Annual Summary』 page. <a href="%s" target="_blank" id="showAnnual" class="btn btn-mini btn-primary">See now</a>.';
$lang->misc->remind      = 'New feature reminders';

$lang->misc->expiredTipsTitle    = 'Dear system administrator, hello:';
$lang->misc->expiredCountTips    = 'There are <span class="expired-tips text-blue" data-toggle="tooltip" data-placement="bottom" title="%s">%s plug-ins</span> in the system that will expire soon. To avoid affecting your regular use, please get in touch with the administrator to renew or uninstall them in time.';
$lang->misc->expiredPluginTips   = 'Expired plugins are: %s. ';
$lang->misc->expiringPluginTips  = 'The plug-ins that will expire are: %s.';
$lang->misc->expiredTipsForAdmin = 'There are %s plug-ins in the current system that will expire soon. To avoid affecting the regular use of the function, please renew or uninstall them in the system background plug-in management as soon as possible.';
$lang->misc->metriclibTips       = 'Added a new metric library indexing function, which can significantly improve the query speed of related metrics after updating the index. You can update it on the "Admin->System->Metric Library" page.';

$lang->misc->noticeRepair = "<h5>If you are not Administrator, contact your ZenTao Administrator to repair tables.</h5>
    <h5>If you are the Administrator, login your ZenTao host and create a file named <span>%s</span>.</h5>
    <p>Note:</p>
    <ol>
    <li>Keep the file empty.</li>
    <li>If the file exists, remove it and create a new one.</li>
    </ol>";

$lang->misc->feature = new stdclass();
$lang->misc->feature->lastest           = 'Latest Version';
$lang->misc->feature->detailed          = 'Detail';
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
$lang->misc->feature->promptDesign      = 'Prompt Engineering';
$lang->misc->feature->promptDesignImage = 'theme/default/images/main/prompt_design_en.svg';
$lang->misc->feature->promptExec        = 'Prompt Execution';
$lang->misc->feature->promptExecImage   = 'theme/default/images/main/prompt_exec_en.svg';
$lang->misc->feature->promptLearnMore   = 'https://www.zentao.net/book/zentaopms/1097.html';

/* Release Date. */
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
$lang->misc->feature->all['21.7.1'][]      = array('title' => 'Implement enhanced project management features, including adding a top toolbar to the document editor, configuring single project workflow management, optimizing requirements, and adding deliverable management functionality.', 'desc' => '');
$lang->misc->feature->all['21.7'][]        = array('title' => 'Now, ZenTao supports bulk editing of parent-child stages across both Waterfall and Waterfall Plus methodologies, enables dependencies between cross-execution tasks, and facilitates batch stage editing within Integrated Product Development (IPD) projects, along with bug fixes.', 'desc' => '');
$lang->misc->feature->all['21.6.1'][]      = array('title' => 'Documentation Bug Resolution.', 'desc' => '');
$lang->misc->feature->all['21.6'][]        = array('title' => 'Jira import optimization. Multi-user document collaboration.', 'desc' => '');
$lang->misc->feature->all['21.6.beta'][]   = array('title' => 'Jira 2.0 import implementation and Confluence integration deployment.', 'desc' => '');
$lang->misc->feature->all['21.5'][]        = array('title' => 'Performance optimization; Enhancements for the performance of file uploads in the comment section; Document optimization.', 'desc' => '');
$lang->misc->feature->all['21.4'][]        = array('title' => 'Enhancements to testing-related details, refinements to workflow particulars, and a restructuring of the opportunity module.', 'desc' => '');
$lang->misc->feature->all['21.3'][]        = array('title' => 'Introduce a filter for postponed items in the program, project, and execution lists. After a project is successfully created, offer guidance for the next steps. In waterfall projects, allow existing tasks and data phases to be subdivided into sub-phases. Include prompt information in the new fields component of the workflow. Refactor the ticket creation page. Introduce opportunity features in Agile projects. For projects that do not have the iteration feature enabled, provide functionalities for issues, risks, opportunities, processes, QA, and meetings.', 'desc' => '');
$lang->misc->feature->all['21.2'][]        = array('title' => 'Add applications under Release; add program grouping display and document permission prompts to the document dropdown menu; allow attachments to be included when copying tasks, requirements, bugs, and use cases; system administrators can delete public contact data; add task search functionality to the project execution list; add overdue filter tags in the execution list; support workflow extension for project initiation; add version management for BI; optimize document editor compatibility; add copy functionality for feedback; allow module editing when the associated module is empty on the create feedback page; add message notifications for issues, risks, opportunities, and audits; add audit and baseline lists to the dashboard contributions; improve review and audit detail page refinements; support task search functionality on the Gantt chart page; add requirement change confirmation functionality for design; support export for review reports and baseline status reports.', 'desc' => '');
$lang->misc->feature->all['21.1'][]        = array('title' => 'This launch includes enhanced document interface space, improved host functionality, refined object relationship mapping, implementation of zero-padding logic for metrics, DuckDB-related optimizations, and significant enhancements to our online features.', 'desc' => '');
$lang->misc->feature->all['21.0'][]        = array('title' => 'Optimization of document functionalities; enhancements to product and project process templates in the BI designer.', 'desc' => '');
$lang->misc->feature->all['20.8'][]        = array('title' => 'Document Optimization: We have optimized the task dependencies and fixed some Bugs.', 'desc' => '');
$lang->misc->feature->all['20.7.1'][]      = array('title' => 'Addressing any known bugs.', 'desc' => '');
$lang->misc->feature->all['20.7'][]        = array('title' => "Optimized the new user guide; Menu can be custimized now; Improved the workflow; A contribution module has been added to the OR interface.", 'desc' => '');
$lang->misc->feature->all['20.6'][]        = array('title' => "The workflow now allows for the configuration of multiple interfaces. The approval workflow can be customized to include conditional logic based on workflow fields. Bug fixes implemented.", 'desc' => '');
$lang->misc->feature->all['20.5'][]        = array('title' => 'Document optimized; 23 new built-in metric items added.', 'desc' => '');
$lang->misc->feature->all['18.13'][]       = array('title' => "Enhanced list views for My Work, Story, Task, and Bug, along with improved detail pages for Product and Project. ZenTao now offers compatibility with Dameng database. Copying Stories, Tasks, Bugs, and Test Cases now includes the ability to copy custom fields and values added through workflow customization. Bug fixes implemented.", 'desc' => '');
$lang->misc->feature->all['20.4'][]        = array('title' => 'Add a new message center. Enhance release management. Implement branch and tag management. Enable additional signers in the approval workflow.', 'desc' => '');
$lang->misc->feature->all['20.3.0'][]      = array('title' => "The pivot table functionality now supports custom drill-down capabilities. The requirement pool feature allows users to view a matrix of requirements at multiple levels, including requirement pool requirements, business requirements, user requirements, and development requirements. When changes occur in the upstream requirement pool, the downstream requirements will need to be manually confirmed. The product roadmap can now associate business requirements and user requirements at any level of the hierarchy. We've optimized the operation button logic for managing requirement pool requirements, business requirements, and user requirements. The search tag conditions for these different types of requirements have also been streamlined. Additionally, when restoring a previously deleted requirement, the system will automatically recalculate the stage of the upstream requirements.", 'desc' => '');
$lang->misc->feature->all['20.2.0'][]      = array('title' => "This release included optimizations to the product matrix, new features to configure platform-level application settings, improvements to the approval process workflow, additions to the OR interface to capture more business requirements, the ability to track new roadmaps and the Charter projected phase for user stories, support for unlimited levels of business requirements split, new estimation capabilities for the development stage of business requirements, support for estimating delivery stage when distributing or decomposing OR requirements, the option to distribute OR requirements as business requirements, estimation for OR requirements and user requirements during upgrades, and a new TR4A review point added to the development stage.", 'desc' => '');
$lang->misc->feature->all['20.1.1'][]      = array('title' => "We've completely refactored the core PHP and UI frameworks, streamlining forms and dashboards for a whole new user experience. Plus, APCu caching delivers a significant performance boost. The baseline review list now boasts a search function, making it a breeze to find what you need. And the OR interface enhancements include a dedicated pending tasks feature, along with the ability to customize IPD project stages and review points for maximum flexibility, and a search function within the baseline review list.", 'desc' => '');
$lang->misc->feature->all['20.1.0'][]      = array('title' => 'Leverages APCu caching to significantly boost system performance. Fine-tuned the interaction design for an optimized user experience. Implemented bug fixes.', 'desc' => '');
$lang->misc->feature->all['20.0'][]        = array('title' => 'In this upgrade, we have reconstructed the underlying PHP and UI frameworks, revamped the core forms and dashboards, resulting in a completely elevated user experience.', 'desc' => '');
$lang->misc->feature->all['18.12'][]       = array('title' => "To better streamline our services, we have discontinued information and associated evaluations for non-R&D users, while implementing timely reminders for impending technical service deadlines. Our latest update includes a comprehensive approach to metric management, offering customizable metrics, a robust library of foundational metrics, and the convenience of recalculating historical metric data at one click. Additionally, we are excited to introduce the demand pool matrix, enabling the removal of approved demands from roadmaps. Furthermore, our Operations Management Interface has been enhanced with the addition of feedback settings. In addition, we have introduced the 'Associated Demand Pool' search criterion, making it easier for users to search for requirements within the demand pool.", 'desc' => '');
$lang->misc->feature->all['20.0.beta2'][]  = array('title' => 'Enhance the functionality and fine-tune the details, while addressing any known bugs.', 'desc' => '');
$lang->misc->feature->all['18.11'][]       = array('title' => "We have successfully implemented AI mini-program functionality, integrating measurement metrics and global filtering capabilities into the large-screen display. Additionally, we have added feedback functionality to the OR interface. Furthermore, we have enabled the inclusion of keywords in the Requirement Management Hub, allowing for the redistribution of requirements in the Requirement Management Hub once a user's requirement has been withdrawn.", 'desc' => '');
$lang->misc->feature->all['18.10.1'][]     = array('title' => 'The Requirement Management Hub (RM Hub) now includes notification functionality, supports product line features, and allows for distributing a single requirement to multiple products.', 'desc' => '');
$lang->misc->feature->all['20.0.beta1'][]  = array('title' => 'This release mainly involves a large-scale refactoring of the code and a brand new upgrade to the user interface (UI), bringing better product performance, stronger security, and a more user-friendly experience.', 'desc' => '');
$lang->misc->feature->all['20.0.alpha1'][] = array('title' => 'Internal release of comprehensive user interface (UI) upgrades for large-scale refactoring and reconstruction.', 'desc' => '');
$lang->misc->feature->all['18.10'][]       = array('title' => 'The use case library offers the capability to import test cases from other libraries, enables document export with image proportions automatically adjusted to fit Word, includes the addition of cookie records to the history record sorting method, and incorporates optimized logic for modifying logs of other users.When converting feedback or tickets, attachments can now be automatically included. Feedback functionality has been enhanced with the addition of keywords and CC fields. Additionally, a new water drop chart type has been introduced, and improvements have been made to the logic and visual presentation of metric collection.', 'desc' => '');
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
$lang->misc->feature->all['12.2'][]   = array('title'=>'Add parent-child story, compatible Xuanxuan IM.', 'desc' => '');
$lang->misc->feature->all['12.1'][]   = array('title'=>'Add Integration.', 'desc' => '<p>Add integration, and build in Jenkins</p>');
$lang->misc->feature->all['12.0.1'][] = array('title'=>'Fix Bug.', 'desc' => '');

$lang->misc->feature->all['12.0'][]   = array('title'=>'Move repo function to zentao', 'desc' => '');
$lang->misc->feature->all['12.0'][]   = array('title'=>'Add annual summary', 'desc' => 'Show annual summary by role.');
$lang->misc->feature->all['12.0'][]   = array('title'=>'Optimize details and fix bug.', 'desc' => '');

$lang->misc->feature->all['11.7'][]   = array('title'=>'Optimize details and fix bug.', 'desc' => '<p>Added choices for users to choose agile or not.</p><p>Added WeChat Enterprise to the types of webhook</p><p>Added the notifier of Dingding personal messages</p>');
$lang->misc->feature->all['11.6.5'][] = array('title'=>'Fix bug.', 'desc' => '');
$lang->misc->feature->all['11.6.4'][] = array('title'=>'Optimize details and fix bug.', 'desc' => '');
$lang->misc->feature->all['11.6.3'][] = array('title'=>'Fix bug.', 'desc' => '');
$lang->misc->feature->all['11.6.2'][] = array('title'=>'Optimize details and fix bug.', 'desc' => '');
$lang->misc->feature->all['11.6.1'][] = array('title'=>'Optimize details and fix bug.', 'desc' => '');

$lang->misc->feature->all['11.6.stable'][] = array('title'=>'Improving the International Edition Interface', 'desc' => '');
$lang->misc->feature->all['11.6.stable'][] = array('title'=>'Add translate function', 'desc' => '');

$lang->misc->feature->all['11.5.2'][] = array('title'=>'Increase the security of ZenTao and increase the login password for weak password check', 'desc' => '');
$lang->misc->feature->all['11.5.1'][] = array('title'=>'Add a third-party authentication and fix bugs.', 'desc' => '');

$lang->misc->feature->all['11.5.stable'][] = array('title'=>'Optimize details and fix bug.', 'desc' => '');
$lang->misc->feature->all['11.5.stable'][] = array('title'=>'Added filters to Dynamics', 'desc' => '');
$lang->misc->feature->all['11.5.stable'][] = array('title'=>'Integrated the latest ZenTao Desktop', 'desc' => '');

$lang->misc->feature->all['11.4.1'][]      = array('title'=>'Optimize details and fix bug.', 'desc' => '');

$lang->misc->feature->all["11.4.stable"][] = array('title'=>'Optimize details and fix bug.', 'desc' => "<p>Enhanced test management.</p><p>Optimized the UI of Plan, Release, and Build linked stories and bugs.</p><p>Customize the feature whether to display files in child category.</p><p>Optimize details and fix bug.</p>");

$lang->misc->feature->all['11.3.stable'][] = array('title'=>'Optimize details and fix bug.', 'desc' => '<p>Add Child Plan to a Plan</p><p>Optimize the chosen</p><p>Add Timezone settings</p><p>Optimize Document Library and Document modules</p>');

$lang->misc->feature->all['11.2.stable'][] = array('title'=>'Optimize details and fix bug.', 'desc' => '<p>Add upgrade logs and database checkup after upgrading</p><p>Fixed ZenTao client and other bugs, and optimized details.</p>');

$lang->misc->feature->all['11.1.stable'][] = array('title'=>'Fix Bug.', 'desc' => '');

$lang->misc->feature->all['11.0.stable'][] = array('title'=>'ZenTao integrated desktop.', 'desc' => '');

$lang->misc->feature->all['10.6.stable'][] = array('title'=>'Adjust backup mechanism', 'desc' => '<p>Increase backup settings and make backup more flexible</p><p>Show backup progress</p><p>Change the backup directory</p>');
$lang->misc->feature->all['10.6.stable'][] = array('title'=>'Optimize and adjust menu', 'desc' => '<p>Adjust admin menu</p><p>Adjust the secondary menu of My and Project</p>');

$lang->misc->feature->all['10.5.stable'][] = array('title'=>'Adjust document layout', 'desc' => "<p>Adjust the layout method on the left side of the document library.</p><p>Add filter conditions at the bottom of the document library menu.</p>");
$lang->misc->feature->all['10.5.stable'][] = array('title'=>'Adjust the child task logic and optimize the display of parent-child task.', 'desc' => '');

$lang->misc->feature->all['10.4.stable'][] = array('title'=>'Optimize and adjust new interface', 'desc' => '<p>Detail page restore to the previous layout.</p><p>Refactore forms to add user pages</p><p>When use cases are executed, do not update the use case stause if the user manually chooses to pass and write the results.</p>');
$lang->misc->feature->all['10.4.stable'][] = array('title'=>'After the user machine hibernates and the login fails, the session will be refreshed again.', 'desc' => '');
$lang->misc->feature->all['10.4.stable'][] = array('title'=>'Upgrade existing interface mechanisms', 'desc' => '');

$lang->misc->feature->all['10.3.stable'][] = array('title'=>'Fix Bug.', 'desc' => '');
$lang->misc->feature->all['10.2.stable'][] = array('title'=>'ZenTao desktop is integrated!', 'desc' => '');

$lang->misc->feature->all['10.0.stable'][] = array('title'=>'New UI/UX and new experience', 'desc' => '<ol><li>My Dashboard</li><li>Dynamics</li><li>Product Home</li><li>Product Overview</li><li>Roadmap</li><li>Project Home</li><li>Project overview</li><li>Test Home</li><li>Document Home</li><li>Added work report on My Dashboard</li><li>Add/Edit/Finish todos on My Dashboard</li><li>Add prodcut report on Product Home</li><li>Add prodcut overview on Product Home</li><li>Add project report on Project Home</li><li>Add project overview on Project Home</li><li>Add Testing report on Test Home</li><li>All Product/product Home/All Project/Project Home/Test Home is moved from the right of the secondary Navbar to the left.</li><li>Kanban/Burndown/Tree/ByGroup of Project/Task has been moved from the third Navbar to the secondary one; Tree/ByGroup/Task List has been integrated to a drop-down.</li><li>Bug/Build of Project on the secondary Navbar has been integrated to a drop-down.</li><li>Display build and list by group, which is more reasonable.</li><li>Added tree to display document on the left of the page.</li><li>Added quick entry to document, including Last Update, My Doc and My Favorite</li><li>Added My Favorite to Doc module.</li></ol>');

$lang->misc->feature->all['9.8.stable'][] = array('title'=>'Message centralized management', 'desc' => '<p>Gather Mail，SMS，webhook into Message</p>');
$lang->misc->feature->all['9.8.stable'][] = array('title'=>'Add recurred Todo', 'desc' => '');
$lang->misc->feature->all['9.8.stable'][] = array('title'=>"Add Block of 'AssignedToMe'", 'desc' => '');
$lang->misc->feature->all['9.8.stable'][] = array('title'=>'Support generating reports on Test Builds', 'desc' => '');

$lang->misc->feature->all['9.7.stable'][] = array('title'=>'Optimize International package. Added Demo data.', 'desc' => '');

$lang->misc->feature->all['9.6.stable'][] = array('title'=>'Added Webhook Interface feature', 'desc' => 'Support communication with BearyChat, Dingding');
$lang->misc->feature->all['9.6.stable'][] = array('title'=>'Added Point', 'desc' => 'More skilled application, more score');
$lang->misc->feature->all['9.6.stable'][] = array('title'=>'Added multiple user task and child tasks to Project', 'desc' => '');
$lang->misc->feature->all['9.6.stable'][] = array('title'=>'Added Product line management to Product', 'desc' => '');

$lang->misc->feature->all['9.5.1'][] = array('title'=>'Added restricted actions.', 'desc' => '');

$lang->misc->feature->all['9.3.beta'][] = array('title'=>'Upgraded ZenTao framework，enhanced security', 'desc' => '');

$lang->misc->feature->all['9.1.stable'][] = array('title'=>'optimize Test View', 'desc' => '<p>Added TestSuite, CaseLibrary and QA Report</p>');
$lang->misc->feature->all['9.1.stable'][] = array('title'=>'Support Group steps of TestCase', 'desc' => '');

$lang->misc->feature->all['9.0.beta'][] = array('title'=>'ZenTao CloudMail has been added.', 'desc' => '<p>ZenTao CloudMail is a free Email service launched jointly with SendCloud. Once binded with ZenTao and passed verification, users can use this service.</p>');
$lang->misc->feature->all['9.0.beta'][] = array('title'=>'Optimized Rich Text Editor and Markdown Editor.', 'desc' => '');

$lang->misc->feature->all['8.3.stable'][] = array('title'=>'Improved Documentation.', 'desc' => '<p>Added Document Home, restructured document library, and added privileges.</p><p>Markdown Editor is supported，and privilege and version managment is added.</p>');

$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Custom Home', 'desc' => '<p>You can add blocks to Dashboard and arrange the layout.</p><p> My Zone, Product, Project, and QA all support home custom mentioned before. </p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Custom Navigation', 'desc' => '<p>You can decide which project show in navigation bar and the order of projects shown in the bar.</p><p> Hover on the navigation bar and a sign will show to its right. Click the sign and a dialog box "Custom Navigation" will show. Drag the block name to switch its order on navigation bar.</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Batch Add/Edit Custom', 'desc' => '<p>You can batch add and edit fields on custom pages.</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Custom Story/Task/Bug/Case', 'desc' => '<p>You can custom fileds when add a Story/Task/Bug/Case.</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Custom Export', 'desc' => '<p>You can custom fileds when export a Story/Task/Bug/Case pages. You can also save it as template for next export.</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'Story/Task/Bug/Case Search ', 'desc' => '<p>On Story/Task/Bug/Case List page, you can do a combined search on Modules and Tabs.</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>"ZenTao Tutorial", 'desc' => '<p>Tutorial for rookies is added for first-time users to know how to use ZenTao.</p>');

$lang->misc->feature->all['7.4.beta'][] = array('title'=>'Product branch feature is added.', 'desc' => '<p>Product branch/platform is added, and its related Story/Plan/Bug/Case/Module has Branch added too.</p>');
$lang->misc->feature->all['7.4.beta'][] = array('title'=>'Release Module is improved.', 'desc' => '<p>Stop action has been added. If Stop to manage it, the Release will not show when Report Bug.</p><p>Bugs that have been omitted in the Release will be related manually.</p>');
$lang->misc->feature->all['7.4.beta'][] = array('title'=>'Create pages of Story and Bug are improved.', 'desc' => '');

$lang->misc->feature->all['7.2.stable'][] = array('title'=>'Security Enhanced', 'desc' => '<p>Admin weak passwork check is enhanced.</p><p>ok file is required when code/upload an extension.</p><p>Sensitive action requires Admin password.</p><p>Do striptags, specialchars to content entered in ZenTao.</p>');
$lang->misc->feature->all['7.2.stable'][] = array('title'=>'Details Improved', 'desc' => '');

$lang->misc->feature->all['7.1.stable'][] = array('title'=>'Framework of Cron is added.', 'desc' => 'Framework of Cron is added. Daily notification, Burndown Update, Backup, Send Email and so on have been added.');
$lang->misc->feature->all['7.1.stable'][] = array('title'=>'rpm and deb packages are provided.', 'desc' => '');

$lang->misc->feature->all['6.3.stable'][] = array('title'=>'Data table is added.', 'desc' => '<p>Fields can be customized in data table and data will be displayed according to customized fields.</p>');
$lang->misc->feature->all['6.3.stable'][] = array('title'=>'Continue improving details', 'desc' => '');
