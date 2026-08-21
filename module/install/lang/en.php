<?php
/**
 * The install module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     install
 * @version     $Id: en.php 4972 2013-07-02 06:50:10Z zhujinyonging@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->install = new stdclass();

$lang->install->common = 'Install';
$lang->install->next   = 'Next';
$lang->install->pre    = 'Back';
$lang->install->reload = 'Refresh';
$lang->install->error  = 'Error ';

$lang->install->officeDomain = 'https://www.zentao.pm';

$lang->install->start            = 'Install Now';
$lang->install->keepInstalling   = 'Continue installing Current Version';
$lang->install->seeLatestRelease = 'Check for Updates';
$lang->install->welcome          = 'Thanks for choosing ZenTao!';
$lang->install->license          = 'License Agreement';
$lang->install->desc             = <<<EOT
ZenTao Project Management Software (ZenTao PMS) is an open-source software released under <a href='http://zpl.pub/page/zplv12.html' target='_blank'>ZPL</a> or <a href='https://www.gnu.org/licenses/agpl-3.0.en.html' target='_blank'>AGPL</a> License. It is an all-in-one platform that integrates Product, Project, and Test Management, along with office automation and organizational management—making it the top choice for small and medium-sized enterprises.

Built with PHP and MySQL on the proprietary ZenTao PHP framework, it offers high extensibility, allowing third-party developers and organizations to easily develop extensions or customize ZenTao accordingly.
EOT;
$lang->install->links = <<<EOT
ZenTao PMS is developed by <strong><a href='https://easycorp.cn' target='_blank' class='text-danger'>ZenTao Software (Qingdao) Group Co., Ltd </a></strong>.
Official Website: <a href='https://www.zentao.net' target='_blank'>https://www.zentao.net</a>
Technical Support: <a href='https://www.zentao.net/ask/' target='_blank'>https://www.zentao.net/ask/</a>
Follow us on LinkedIn: <a href='https://www.linkedin.com/company/1156596/' target='_blank'>ZenTao Software</a>
Facebook: <a href='https://www.facebook.com/natureeasysoft' target='_blank'>ZenTao Software</a>
Twitter: <a href='https://twitter.com/ZentaoA' target='_blank'>ZenTao ALM</a>
You are currently installing version: <strong class='text-danger'>%s</strong>.
EOT;

$lang->install->selectMode          = "Select mode";
$lang->install->introduction        = "15.0+ Feature Introduction of ZenTao ";
$lang->install->howToUse            = "How do you plan to use the new version of ZenTao?";
$lang->install->guideVideo          = 'https://dl.zentao.net/vedio/program0716.mp4';
$lang->install->introductionContent = <<<EOT
<div>
<h4>Dear users, welcome to ZenTao PMS.</h4>
<p>ZenTao has two managment modes in version 15.0 and up. One is the classic management mode, providing two core features, Product and Project; the other is a new project management mode, with Program and Execution added. The following is an introduction to the new mode:</p>
<div class='block-content'>
<div class='block-details'><p class='block-title'><i class='icon icon-program'></i> <strong>Program</strong></p>
<p>Program is used to manage a group of products and projects, and the company executives or PMO can use it for strategic planning.</p></div>
<div class='block-details block-right'>
<p class='block-title'><i class='icon icon-product'></i> <strong>Product</strong></p>
<p>Products break down corporate strategy into actionable requirements, allowing Product Managers to create release plans.<p>
</div>
<div class='block-details'>
<p class='block-title'><i class='icon icon-project'></i> <strong>Project</strong></p>
<p>Projects organize resources for R&D and track the entire management process to ensure efficient, high-quality delivery.</p>
</div>
<div class='block-details block-right'>
<p class='block-title'><i class='icon icon-run'></i> <strong>Execution</strong></p>
<p>Execution is used to break down, assign, and track tasks, ensuring that project goals are implemented at the individual level.<p>
</div>
</div>
<div class='text-center introduction-link'>
<a href='https://dl.zentao.net/zentao/zentaoconcept.pdf' target='_blank' class='btn btn-wide btn-info'><i class='icon icon-p-square'></i> Document Introduction</a>
<a href='j a v a s c r i p t :showVideo()' class='btn btn-wide btn-info'><i class='icon icon-video-play'></i> Video Introduction</a>
</div>
</div>
EOT;

$lang->install->newReleased = "<strong class='text-danger'>Notice</strong>: The latest version <strong class='text-danger'>%s</strong> is available on the official website, released on %s.";
$lang->install->or          = 'Or';
$lang->install->checking    = 'System check';
$lang->install->ok          = 'Passed(√)';
$lang->install->fail        = 'Failed(×)';
$lang->install->loaded      = 'Loaded';
$lang->install->unloaded    = 'Not loaded';
$lang->install->exists      = 'Found ';
$lang->install->notExists   = 'Not found ';
$lang->install->writable    = 'Writable ';
$lang->install->notWritable = 'Not writable ';
$lang->install->phpINI      = 'PHP Configuration File';
$lang->install->checkItem   = 'Item';
$lang->install->current     = 'Current Configuration';
$lang->install->result      = 'Result';
$lang->install->action      = 'Suggestions';

$lang->install->phpVersion = 'PHP Version';
$lang->install->phpFail    = 'PHP Version should be 5.2.0+';

$lang->install->pdo           = 'PDO Extension';
$lang->install->pdoFail       = 'Modify the PHP configuration file to load the PDO extension.';
$lang->install->pdoMySQL      = 'PDO_MySQL Extension';
$lang->install->pdoMySQLFail  = 'Modify the PHP configuration file to load the pdo_mysql extension.';
$lang->install->json          = 'JSON Extension';
$lang->install->jsonFail      = 'Modify the PHP configuration file to load the JSON extension.';
$lang->install->openssl       = 'OpenSSL Extension';
$lang->install->opensslFail   = 'Modify the PHP configuration file to load the OPENSSL extension.';
$lang->install->mbstring      = 'Mbstring Extension';
$lang->install->mbstringFail  = 'Modify the PHP configuration file to load the MBSTRING extension.';
$lang->install->zlib          = 'Zlib Extension';
$lang->install->zlibFail      = 'Modify the PHP configuration file to load the ZLIB extension.';
$lang->install->curl          = 'Curl Extension';
$lang->install->curlFail      = 'Modify the PHP configuration file to load the CURL extension.';
$lang->install->filter        = 'Filter Extension';
$lang->install->filterFail    = 'Modify the PHP configuration file to load the FILTER extension.';
$lang->install->gd            = 'GD Extension';
$lang->install->gdFail        = 'Modify the PHP configuration file to load the GD extension.';
$lang->install->iconv         = 'Iconv Extension';
$lang->install->iconvFail     = 'Modify the PHP configuration file to load the ICONV extension.';
$lang->install->tmpRoot       = 'Temporary File Directory';
$lang->install->dataRoot      = 'Upload File Directory';
$lang->install->session       = 'Session Storage Directory';
$lang->install->sessionFail   = 'Modify the PHP configuration file to set session.save_path. <br />If using the BT Panel, go to "App Store" in the BT Web Panel, open PHP settings, go to the "Session Configuration" item, select files, and click Save. For older versions, the PHP configuration file needs to be modified manually.';
$lang->install->mkdirWin      = '<p>Need to create directory %s. The command is:<br /> mkdir %s</p>';
$lang->install->chmodWin      = 'Need to modify the permissions of directory "%s".';
$lang->install->mkdirLinux    = '<p>Need to create directory %s.<br /> The command is:<br /> mkdir -p %s</p>';
$lang->install->chmodLinux    = 'Need to modify the permissions of directory "%s".<br />The command is:<br />chmod 777 -R %s';

$lang->install->timezone       = 'Set Timezone';
$lang->install->defaultLang    = 'Default Language';
$lang->install->dbDriver       = 'Database Driver';
$lang->install->dbHost         = 'Database Host';
$lang->install->dbHostNote     = 'If 127.0.0.1 is not accessible, try localhost.';
$lang->install->dbPort         = 'Host Port';
$lang->install->dbEncoding     = 'Database Charset';
$lang->install->dbUser         = 'Database Username';
$lang->install->dbPassword     = 'Database Password';
$lang->install->dbName         = 'Database Name';
$lang->install->dbSchema       = 'Database Schema';
$lang->install->dbSchemaNote   = 'Schema for Gauss/PostgreSQL; ignored by MySQL.';
$lang->install->dbPrefix       = 'Table Prefix';
$lang->install->clearDB        = 'Clean up existing data';
$lang->install->importDemoData = 'Import Demo Data';
$lang->install->working        = 'Operation Mode';

$lang->install->dbDriverList = array();
$lang->install->dbDriverList['mysql']     = 'MySQL';

$lang->install->requestTypes['GET']       = 'GET';
$lang->install->requestTypes['PATH_INFO'] = 'PATH_INFO';

$lang->install->workingList['full']      = 'Application Lifecycle Management';

$lang->install->errorConnectDB      = 'Database connection failed.';
$lang->install->errorDBName         = ' “.” are not allowed in the database name';
$lang->install->errorDBSchema       = 'Invalid schema name.';
$lang->install->errorCreateDB       = 'Database creation failed.';
$lang->install->errorTableExists    = 'The data table has existed. If ZenTao has been installed before, please return to the previous step and clear data, then continue the installation.';
$lang->install->errorCreateTable    = 'Table creation failed.';
$lang->install->errorEngineInnodb   = 'Your MySQL does not support InnoDB data table engine. Please modify it to MyISAM and try again.';
$lang->install->errorImportDemoData = 'Importing demo data failed.';
$lang->install->errorDBUserPriv     = 'The current database user does not have sufficient permissions! \nPlease switch to the root user or use the following SQL statement to grant the current user permissions：\n';

$lang->install->setConfig          = 'Create config file';
$lang->install->key                = 'Item';
$lang->install->value              = 'Value';
$lang->install->saveConfig         = 'Save config file';
$lang->install->save2File          = '<div class="text-warning">Copy the content in the text box above and save it to "<strong> %s </strong>". You can change this configuration file later.</div>';
$lang->install->saved2File         = 'The configuration file has been saved to " <strong>%s</strong> ". You can change this file later.';
$lang->install->errorNotSaveConfig = 'The configuration file is not saved.';
$lang->install->errorNotInitConfig = 'The configuration file has not been created.';

global $app;
$lang->install->CSRFNotice = "CSRF defense has been enabled in the system. If you don't need it, contact the administrator to disable it manually in the {$app->basePath}config/config.php file.";

$lang->install->getPriv  = 'Set Admin';
$lang->install->company  = 'Company Name';
$lang->install->account  = 'Admin Account';
$lang->install->password = 'Admin Password';

$lang->install->placeholder = new stdclass();
$lang->install->placeholder->password = 'The Password should be ≥ 6 characters, combination of uppercase, lowercase letters and numbers.';

$lang->install->errorEmpty['company']  = "{$lang->install->company} should not be blank.";
$lang->install->errorEmpty['account']  = "{$lang->install->account} should not be blank.";
$lang->install->errorEmpty['password'] = "{$lang->install->password} should not be blank.";

$lang->install->langList['1'] = array('module' => 'process', 'key' => 'support', 'value' => 'Support Process');
$lang->install->langList['2'] = array('module' => 'process', 'key' => 'engineering', 'value' =>  'Engineering Process');
$lang->install->langList['3'] = array('module' => 'process', 'key' => 'project', 'value' => 'Project Management');

$lang->install->processList['11'] = 'Project management';
$lang->install->processList['12'] = 'Project planning';
$lang->install->processList['13'] = 'Project monitoring';
$lang->install->processList['14'] = 'Risk management';
$lang->install->processList['15'] = 'Closing management';
$lang->install->processList['16'] = 'Quantitative Project management';
$lang->install->processList['17'] = 'Stories development';
$lang->install->processList['18'] = 'Design and development';
$lang->install->processList['19'] = 'Implementation and testing';
$lang->install->processList['20'] = 'System test';
$lang->install->processList['21'] = 'Customer acceptance';
$lang->install->processList['22'] = 'Quality assurance';
$lang->install->processList['23'] = 'Configuration management';
$lang->install->processList['24'] = 'Metric analysis';
$lang->install->processList['25'] = 'Cause analysis and resolution';
$lang->install->processList['26'] = 'Decision analysis';

$lang->install->basicmeasList['2'] = array('name' => 'Initial size of project user requirements', 'unit' => 'Story points or function points', 'definition' => 'Sum of the size of the baseline version of the first CUSTOMER requirements specification for each product of the project');
$lang->install->basicmeasList['3'] = array('name' => 'Initial scale of project software requirements', 'unit' => 'Story points or function points', 'definition' => 'Sum of the size of the first software requirements specification baseline release for each product of the project');
$lang->install->basicmeasList['4'] = array('name' => 'Real-time scale of project user requirements', 'unit' => 'Story points or function points', 'definition' => 'The actual size of the project user requirements');
$lang->install->basicmeasList['5'] = array('name' => 'Real-time scale of project software requirements', 'unit' => 'Story points or function points', 'definition' => 'The actual scale of the project software requirements');
$lang->install->basicmeasList['6'] = array('name' => 'Estimated project size', 'unit' => 'Story points or function points', 'definition' => 'The estimated size of the project when it was originally estimated');
$lang->install->basicmeasList['8'] = array('name' => 'Project requirements phase planning days', 'unit' => 'Day', 'definition' => 'The sum of planned days for all requirements phases under the project');
$lang->install->basicmeasList['9'] = array('name' => 'Number of days planned during project design phase', 'unit' => 'Day', 'definition' => 'The sum of planned days for all design phases under the project');
$lang->install->basicmeasList['10'] = array('name' => 'Planned number of days during project development phase', 'unit' => 'Day', 'definition' => 'The sum of planned days for all development phases under the project');
$lang->install->basicmeasList['11'] = array('name' => 'Number of days planned for project test phase', 'unit' => 'Day', 'definition' => 'The sum of planned days for all test phases under the project');
$lang->install->basicmeasList['12'] = array('name' => 'Actual days of project requirements phase', 'unit' => 'Day', 'definition' => 'The sum of the actual days of all requirements phases under the project');
$lang->install->basicmeasList['13'] = array('name' => 'Actual days of project design phase', 'unit' => 'Day', 'definition' => 'The sum of the actual days of all design phases under the project');
$lang->install->basicmeasList['14'] = array('name' => 'Actual number of days during the project development phase', 'unit' => 'Day', 'definition' => 'The sum of the actual days of all r&d phases under the project');
$lang->install->basicmeasList['15'] = array('name' => 'Actual number of days during the project test phase', 'unit' => 'Day', 'definition' => 'The sum of the actual days of all test phases under the project');
$lang->install->basicmeasList['26'] = array('name' => 'Plan days by product requirements phase', 'unit' => 'Day', 'definition' => 'The sum of planned days for all requirements phases under the product');
$lang->install->basicmeasList['27'] = array('name' => 'Plan days by product design stage', 'unit' => 'Day', 'definition' => 'The sum of planned days for all design phases under the product');
$lang->install->basicmeasList['28'] = array('name' => 'Planned days by product development phase', 'unit' => 'Day', 'definition' => 'The sum of planned days for all development phases under the product');
$lang->install->basicmeasList['29'] = array('name' => 'Plan days by product test phase', 'unit' => 'Day', 'definition' => 'The sum of planned days for all test phases under the product');
$lang->install->basicmeasList['30'] = array('name' => 'Actual days of product requirements stage', 'unit' => 'Day', 'definition' => 'The sum of the actual days of all requirement phases under the product');
$lang->install->basicmeasList['31'] = array('name' => 'Actual days of product design stage', 'unit' => 'Day', 'definition' => 'The sum of the actual days of all design phases under the product');
$lang->install->basicmeasList['32'] = array('name' => 'By actual days of product development stage', 'unit' => 'Day', 'definition' => 'The sum of the actual days of all development phases under the product');
$lang->install->basicmeasList['33'] = array('name' => 'By actual days of product test phase', 'unit' => 'Day', 'definition' => 'The sum of the actual days of all testing phases under the product');
$lang->install->basicmeasList['34'] = array('name' => 'Real-time estimated working hours of project tasks', 'unit' => 'Hour','definition' => 'The sum of the initial estimated man-hours for all tasks under the project');
$lang->install->basicmeasList['35'] = array('name' => 'Total estimated real-time working hours of project requirements', 'unit' => 'Hour','definition' => 'The sum of the initial estimated man-hours for all requirements related tasks of the project');
$lang->install->basicmeasList['36'] = array('name' => 'Total estimated time of project design work in real time', 'unit' => 'Hour','definition' => 'The sum of the initial estimated man-hours for all design-related tasks of the project');
$lang->install->basicmeasList['37'] = array('name' => 'Total estimated time of project development in real time', 'unit' => 'Hour','definition' => 'The sum of the initial estimated man-hours for all development related tasks of the project');
$lang->install->basicmeasList['38'] = array('name' => 'Total estimated time of project test work in real time', 'unit' => 'Hour','definition' => 'The sum of the initial estimated man-hours for all test related tasks of the project');
$lang->install->basicmeasList['39'] = array('name' => 'Actual man-hours cost by project tasks', 'unit' => 'Hour','definition' => 'The sum of the actual man-hours cost for all tasks under the project');
$lang->install->basicmeasList['40'] = array('name' => 'The actual number of man-hours cost by project requirements work', 'unit' => 'Hour','definition' => 'The sum of the actual man-hours cost by all demand-related tasks of the project');
$lang->install->basicmeasList['41'] = array('name' => 'The actual number of man-hours cost by project design work', 'unit' => 'Hour','definition' => 'The sum of the actual man-hours cost by all design-related tasks of the project');
$lang->install->basicmeasList['42'] = array('name' => 'The actual number of man-hours cost by project development work', 'unit' => 'Hour','definition' => 'The sum of the actual man-hours cost by all development related tasks of the project');
$lang->install->basicmeasList['43'] = array('name' => 'The actual number of man-hours cost by the project testing work', 'unit' => 'Hour','definition' => 'The sum of the actual man-hours cost by all test related tasks of the project');
$lang->install->basicmeasList['44'] = array('name' => 'Total estimated initial hours of project development work', 'unit' => 'Hour','definition' => 'The sum of the initial estimated work hours of all development related work in the first baseline release of the project plan');
$lang->install->basicmeasList['45'] = array('name' => 'Total estimated initial hours of project design work', 'unit' => 'Hour','definition' => 'The sum of the initial estimated man-hours of all design-related work in the first baseline version of the project plan');
$lang->install->basicmeasList['46'] = array('name' => 'Total estimated initial work hours for project testing', 'unit' => 'Hour','definition' => 'The sum of the initial estimated man-hours of all test-related work in the first baseline release of the project plan');
$lang->install->basicmeasList['47'] = array('name' => 'Total estimated initial hours of work required for the project', 'unit' => 'Hour','definition' => 'The sum of the initial estimated man-hours of all requirements related work in the first baseline version of the project plan');
$lang->install->basicmeasList['48'] = array('name' => 'Total estimated initial work hours for project tasks', 'unit' => 'Hour','definition' => 'The sum of the initial estimated man-hours for all tasks in the first baseline version of the project plan');
$lang->install->basicmeasList['49'] = array('name' => 'The final estimated number of project development hours', 'unit' => 'Hour','definition' => 'The sum of the initial estimated work hours of all development-related tasks in the last baseline release of the project plan');
$lang->install->basicmeasList['50'] = array('name' => 'Total estimated final work hours of project requirements', 'unit' => 'Hour','definition' => 'The sum of the initial estimated work hours of all requirements related tasks in the last baseline release of the project plan');
$lang->install->basicmeasList['51'] = array('name' => 'The final estimated number of project testing hours', 'unit' => 'Hour','definition' => 'The sum of the initial estimated man-hours of all test related tasks in the last baseline release of the project plan');
$lang->install->basicmeasList['52'] = array('name' => 'The final estimated total working hours of project design work', 'unit' => 'Hour','definition' => 'The sum of the initial estimated man-hours of all design-related tasks in the last baseline release of the project plan');
$lang->install->basicmeasList['53'] = array('name' => 'Total estimated final work hours of project tasks', 'unit' => 'Hour', 'definition' => 'The sum of the initial estimated man-hours for all tasks in the last baseline release of the project plan');

$lang->install->selectedMode     = 'Selection mode';
$lang->install->selectedModeTips = 'You can go to the Admin - Custom - Mode to set it later.';

$lang->install->groupList['ADMIN']['name']        = 'Admin';
$lang->install->groupList['ADMIN']['desc']        = 'System Admin';
$lang->install->groupList['DEV']['name']          = 'Dev';
$lang->install->groupList['DEV']['desc']          = 'Developer';
$lang->install->groupList['QA']['name']           = 'Test';
$lang->install->groupList['QA']['desc']           = 'Tester';
$lang->install->groupList['PM']['name']           = 'PM';
$lang->install->groupList['PM']['desc']           = 'Project Manager';
$lang->install->groupList['PO']['name']           = 'PO';
$lang->install->groupList['PO']['desc']           = 'Product Owner';
$lang->install->groupList['TD']['name']           = 'Dev Manager';
$lang->install->groupList['TD']['desc']           = 'Development Manager';
$lang->install->groupList['PD']['name']           = 'PD';
$lang->install->groupList['PD']['desc']           = 'Product Director';
$lang->install->groupList['QD']['name']           = 'QD';
$lang->install->groupList['QD']['desc']           = 'Test Director';
$lang->install->groupList['TOP']['name']          = 'Senior Manager';
$lang->install->groupList['TOP']['desc']          = 'Senior Manager';
$lang->install->groupList['OTHERS']['name']       = 'Others';
$lang->install->groupList['OTHERS']['desc']       = 'other users';
$lang->install->groupList['LIMITED']['name']      = 'Restricted User';
$lang->install->groupList['LIMITED']['desc']      = 'Restricted User Group (Related Content Editing Only)';
$lang->install->groupList['PROJECTADMIN']['name'] = 'Project Admin';
$lang->install->groupList['PROJECTADMIN']['desc'] = 'Project Admins can manage project permissions.';
$lang->install->groupList['LITEADMIN']['name']    = 'Admin';
$lang->install->groupList['LITEADMIN']['desc']    = 'Operations Management User Groups';
$lang->install->groupList['LITEPROJECT']['name']  = 'Project Management';
$lang->install->groupList['LITEPROJECT']['desc']  = 'Operations Management User Groups';
$lang->install->groupList['LITETEAM']['name']     = 'Team Members';
$lang->install->groupList['LITETEAM']['desc']     = 'Operations Management User Groups';

$lang->install->groupList['IPDPRODUCTPLAN']['name'] = 'Product Planner';
$lang->install->groupList['IPDDEMAND']['name']      = 'Story Analyst';
$lang->install->groupList['IPDPMT']['name']         = 'PMT Members';
$lang->install->groupList['IPDADMIN']['name']       = 'Admins';

$lang->install->groupList['DEVOPSADMIN']['name']     = 'DEVOPS ADMIN';
$lang->install->groupList['DEVOPSINSPECTOR']['name'] = 'DEVOPS INSPECTOR';
$lang->install->groupList['DEVOPSUSER']['name']      = 'DEVOPS DEVELOPER';

$lang->install->cronList[''] = 'Monitor Cron';
$lang->install->cronList['moduleName=execution&methodName=computeBurn'] = 'Update Burndown Chart';
$lang->install->cronList['moduleName=report&methodName=remind']         = 'Daily Task Reminder';
$lang->install->cronList['moduleName=svn&methodName=run']               = 'Synchronize SVN';
$lang->install->cronList['moduleName=git&methodName=run']               = 'Synchronize GIT';
$lang->install->cronList['moduleName=backup&methodName=backup']         = 'Backup data&file';
$lang->install->cronList['moduleName=mail&methodName=asyncSend']        = 'Asynchronize sending emails';
$lang->install->cronList['moduleName=webhook&methodName=asyncSend']     = 'Asynchronize sending webhook';
$lang->install->cronList['moduleName=admin&methodName=deleteLog']       = 'Delete overdue logs';
$lang->install->cronList['moduleName=todo&methodName=createCycle']      = 'Create recurring todos';
$lang->install->cronList['moduleName=ci&methodName=initQueue']          = 'Create recurring tasks';
$lang->install->cronList['moduleName=ci&methodName=checkCompileStatus'] = 'Synchronize Jenkins Status';
$lang->install->cronList['moduleName=ci&methodName=exec']               = 'Execute Jenkins';

$lang->install->dbProgress      = "Installing Database Tables";
$lang->install->dbProgressLabel = 'Progress';
$lang->install->dbExecutingTips = 'Please wait. Do not refresh, power off, or shut down.';
$lang->install->dbFinish        = "Database tables installed successfully";
$lang->install->dbFail          = 'Database table installation failed. Please check if the network connection is stable, if the database configuration is correct, and if the database user has permission to create the table. Alternatively, return to the previous page, select "Clear up existing data," and try again.';
$lang->install->success         = "Installed!";
$lang->install->login           = 'ZenTao Login';
$lang->install->register        = 'ZenTao Community Signup';

$lang->install->successLabel       = "<p>You have installed ZenTao successfully %s.</p>";
$lang->install->successNoticeLabel = "<p>You have installed ZenTao %s.<strong class='text-danger'> Please delete install.php</strong>.</p>";
$lang->install->congratulations    = "Congratulations! ZenTao has been installed successfully.";
$lang->install->joinZentao         = <<<EOT
<p>Note: To stay updated with the latest ZenTao news, please register on the ZenTao Community (<a href='https://www.zentao.net' class='alert-link' target='_blank'>www.zentao.net</a>).</p>
EOT;

$lang->install->product = array('chanzhi', 'zdoo', 'xuanxuan', 'ydisk', 'meshiot');

$lang->install->promotion = "Recommended products from the ZenTao family:";

$lang->install->chanzhi       = new stdclass();
$lang->install->chanzhi->name = 'ZSITE';
$lang->install->chanzhi->logo = 'images/main/chanzhi.ico';
$lang->install->chanzhi->url  = 'https://www.zsite.com';
$lang->install->chanzhi->desc = <<<EOD
<ul>
<li>Professional enterprise marketing portal system</li>
<li>Feature-rich with an intuitive and user-friendly interface</li>
<li>Highly optimized for SEO with attention to every detail</li>
<li>Open source and free for unlimited commercial use!</li>
</ul>
EOD;

$lang->install->zdoo = new stdclass();
$lang->install->zdoo->name = 'ZDOO Collaboration';
$lang->install->zdoo->logo = 'images/main/zdoo.ico';
$lang->install->zdoo->url  = 'https://www.zdoo.com';
$lang->install->zdoo->desc = <<<EOD
<ul>
<li>CRM & Order Tracking</li>
<li>Project Tasks, Announcements & Docs</li>
<li>Cash Management: Income & Expenses</li>
<li>Forums, Blogs & Activity Feeds</li>
</ul>
EOD;

$lang->install->ydisk = new stdclass();
$lang->install->ydisk->name = 'YDisk';
$lang->install->ydisk->logo = 'images/main/ydisk.ico';
$lang->install->ydisk->url  = 'http://www.ydisk.cn';
$lang->install->ydisk->desc = <<<EOD
<ul>
  <li>Self-Hosted: deploy on your own machine</li>
  <li>Unlimited Storage: depend on your hard drive size</li>
  <li>Fast Transmission: as fast as your bandwidth allows</li>
  <li>Secure: 12 permissions for any strict settings</li>
</ul>
EOD;

$lang->install->meshiot = new stdclass();
$lang->install->meshiot->name = 'MeshIoT';
$lang->install->meshiot->logo = 'images/main/meshiot.ico';
$lang->install->meshiot->url  = 'https://www.meshiot.com';
$lang->install->meshiot->desc = <<<EOD
<ul>
  <li>Performance: one gateway can monitor 65,536 equipments</li>
  <li>Accessibility: unique radio communication protocol covers 2,500m radius</li>
  <li>Dimming System: 200+ sensors and monitors</li>
  <li>Battery Available: no changes required to any equipment on your site</li>
</ul>
EOD;

$lang->install->solution = new stdclass();
$lang->install->solution->skip        = 'Skip';
$lang->install->solution->skipInstall = 'Skip';
$lang->install->solution->log         = 'Install Log';
$lang->install->solution->title       = 'CI&CD platform application settings';
$lang->install->solution->progress    = 'CI&CD platform Installation';
$lang->install->solution->desc        = 'Welcome to the CI&CD platform. We will install the following applications simultaneously when you install the platform to help you get started quickly!';
$lang->install->solution->overMemory  = 'Insufficient memory prevents simultaneous installation. It is recommended to install applications manually after the platform is started.';

$lang->install->changeModes = [];
$lang->install->changeModes['create'] = 'Add';
$lang->install->changeModes['update'] = 'Update';
$lang->install->changeModes['delete'] = 'Delete';

$lang->install->changeActions = [];
$lang->install->changeActions['createView']  = 'Create database view %VIEW%';
$lang->install->changeActions['dropView']    = 'Drop database view %VIEW%';
$lang->install->changeActions['createTable'] = 'Create database table %TABLE%';
$lang->install->changeActions['dropTable']   = 'Drop database table %TABLE%';
$lang->install->changeActions['renameTable'] = 'Rename database table %OLD% to %NEW%';
$lang->install->changeActions['addField']    = 'Add field %FIELD% to database table %TABLE%';
$lang->install->changeActions['modifyField'] = 'Modify field %FIELD% in database table %TABLE%';
$lang->install->changeActions['dropField']   = 'Drop field %FIELD% from database table %TABLE%';
$lang->install->changeActions['renameField'] = 'Rename field %OLD% in table %TABLE% to %NEW%';
$lang->install->changeActions['addIndex']    = 'Add index %INDEX% to database table %TABLE%';
$lang->install->changeActions['dropIndex']   = 'Drop index %INDEX% from database table %TABLE%';
$lang->install->changeActions['insertValue'] = 'Insert data into database table %TABLE%';
$lang->install->changeActions['updateValue'] = 'Update data in database table %TABLE%';
$lang->install->changeActions['deleteValue'] = 'Delete data from database table %TABLE%';
$lang->install->changeActions['other']       = 'Other operations';
