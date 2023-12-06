<?php
/**
 * The upgrade module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Nguyễn Quốc Nho <quocnho@gmail.com>
 * @package  upgrade
 * @version  $Id: vi.php 5119 2013-07-12 08:06:42Z wyd621@gmail.com $
 * @link  https://www.zentao.net
 */
$lang->upgrade->common          = 'Cập nhật';
$lang->upgrade->start           = 'Start';
$lang->upgrade->result          = 'Kết quả';
$lang->upgrade->fail            = 'Thất bại';
$lang->upgrade->successTip      = 'Erfolgreich';
$lang->upgrade->success         = "<p><i class='icon icon-check-circle'></i></p><p>Chúc mừng!</p><p> ZenTao của bạn đã được cập nhật.</p>";
$lang->upgrade->tohome          = 'Visit ZenTao';
$lang->upgrade->license         = 'ZenTao is under Z PUBLIC LICENSE(ZPL) 1.2.';
$lang->upgrade->warnning        = 'Cảnh báo';
$lang->upgrade->checkExtension  = 'Kiểm tra Extensions';
$lang->upgrade->consistency     = 'Kiểm tra tính nhất quán';
$lang->upgrade->warnningContent = <<<EOT
<p>Vui lòng sao lưu cơ sở dữ liệu của bạn trước khi cập nhật ZenTao!</p>
<pre>
1. Sử dụng phpMyAdmin để sao lưu.
2. Sử dụng mysqlCommand để sao lưu.
   $> mysqldump -u <span class='text-danger'>username</span> -p <span class='text-danger'>dbname</span> > <span class='text-danger'>filename</span>
   Thay đổi văn bản màu đỏ thành tên người dùng và cơ sở dữ liệu tương ứng.
   ví dụ:  mysqldump -u root -p zentao >zentao.bak
</pre>
EOT;

$lang->upgrade->createFileWinCMD   = 'Mở dòng lệnh và xử lý <strong style="color:#ed980f">echo > %s</strong>';
$lang->upgrade->createFileLinuxCMD = 'Xử lý dòng lệnh: <strong style="color:#ed980f">touch %s</strong>';
$lang->upgrade->setStatusFile      = '<h4>Please complete the following actions</h4>
           <ul style="line-height:1.5;font-size:13px;">
           <li>%s</li>
           <li>Or xóa "<strong style="color:#ed980f">%s</strong>" and create <strong style="color:#ed980f">ok.txt</strong> and leave it blank.</li>
           </ul>
           <p><strong style="color:red">I have read and done as instructed above. <a href="upgrade.php">Continue upgrading.</a></strong></p>';

$lang->upgrade->selectVersion = 'Phiên bản';
$lang->upgrade->continue      = 'Tiếp tục';
$lang->upgrade->noteVersion   = "Chọn the compatible version, or it might cause data loss.";
$lang->upgrade->fromVersion   = 'Từ';
$lang->upgrade->toVersion     = 'Tới';
$lang->upgrade->confirm       = 'Xác nhận SQL';
$lang->upgrade->sureExecute   = 'Xử lý';
$lang->upgrade->forbiddenExt  = 'The extension is incompatible with the version. It has been deactivated:';
$lang->upgrade->updateFile    = 'File information has to be updated.';
$lang->upgrade->noticeSQL     = 'Your database is inconsistent with the standard and it failed to fix it. Vui lòng run the following SQL and refresh.';
$lang->upgrade->afterDeleted  = 'File không là deleted. Vui lòng refresh after you xóa it.';
$lang->upgrade->mergeProgram  = 'Data Merge';
$lang->upgrade->mergeTips     = 'Data Migration Tips';
$lang->upgrade->toPMS15Guide  = 'ZenTao open source version 15 upgrade';
$lang->upgrade->toPRO10Guide  = 'ZenTao profession version 10 upgrade';
$lang->upgrade->toBIZ5Guide   = 'ZenTao enterprise version 5 upgrade';
$lang->upgrade->toMAXGuide    = 'ZenTao ultimate version upgrade';

$lang->upgrade->line            = 'Product Line';
$lang->upgrade->allLines        = "All Product Lines";
$lang->upgrade->program         = 'Merge Project';
$lang->upgrade->existProgram    = 'Existing programs';
$lang->upgrade->existProject    = 'Existing projects';
$lang->upgrade->existLine       = 'Existing' . $lang->productCommon . ' lines';
$lang->upgrade->product         = $lang->productCommon;
$lang->upgrade->project         = 'Iteration';
$lang->upgrade->repo            = 'Repo';
$lang->upgrade->mergeRepo       = 'Merge Repo';
$lang->upgrade->setProgram      = 'Set the project to which the program belongs';
$lang->upgrade->setProject      = "Set the {$lang->executionCommon} to which the project belongs";
$lang->upgrade->dataMethod      = 'Data migration method';
$lang->upgrade->selectMergeMode = 'Please select the data merging method';
$lang->upgrade->mergeMode       = 'Data consolidation method : ';
$lang->upgrade->begin           = 'Begin Date';
$lang->upgrade->end             = 'End Date';
$lang->upgrade->unknownDate     = 'Unknown Date Project';
$lang->upgrade->selectProject   = 'The target project';
$lang->upgrade->programName     = 'Program Name';
$lang->upgrade->projectName     = 'Project Name';
$lang->upgrade->compatibleEXT   = 'Extension mechanism compatible';
$lang->upgrade->fileName        = 'File Name';
$lang->upgrade->next            = 'Next';
$lang->upgrade->back            = 'Back';

$lang->upgrade->newProgram        = 'Create';
$lang->upgrade->editedName        = 'New Name';
$lang->upgrade->projectEmpty      = 'Project must be not empty.';
$lang->upgrade->mergeSummary      = "Dear users, there are %s in your system waiting for Migration. By System Calculation, we recommend your migration plan as follows, you can also adjust according to your own situation:";
$lang->upgrade->productCount      = "%s {$lang->productCommon}";
$lang->upgrade->projectCount      = "%s {$lang->projectCommon}";
$lang->upgrade->mergeByProject    = "Currently, the following two data migration methods are available. If the historical projects are long term, we suggest upgrading the historical projects as projects.</br>If the historical projects are short cycle, we suggest that the historical projects be upgraded as iterations.";
$lang->upgrade->mergeRepoTips     = "Merge the selected version library under the selected product.";
$lang->upgrade->needBuild4Add     = 'Full text retrieval has been added in this upgrade. Need create index. Please go [Admin->System->BuildIndex] page to build index.';
$lang->upgrade->errorEngineInnodb = 'Your MySQL version is too low to support InnoDB data table engine. Please modify it to MyISAM and try again.';
$lang->upgrade->duplicateProject  = "Project name in the same program cannot be duplicate. Please adjust the duplicate names.";

$lang->upgrade->projectType['project']   = "Upgrade the historical {$lang->projectCommon} as a project";
$lang->upgrade->projectType['execution'] = "Upgrade the historical {$lang->projectCommon} as an execution";

$lang->upgrade->createProjectTip = <<<EOT
<p>After the upgrade, the existing {$lang->projectCommon} will be Project in the new version.</p>
<p>ZenTao will create an item in Execute with the same name of {$lang->projectCommon} according to the data in {$lang->projectCommon}, and move the tasks, stories, and bugs in {$lang->projectCommon} to it.</p>
EOT;

$lang->upgrade->createExecutionTip = <<<EOT
<p>ZenTao will upgrade existing {$lang->projectCommon} as execution.</p>
<p>After the upgrade, the data of existing {$lang->projectCommon} will be in a Project - Execute of the new version .</p>
EOT;

$lang->upgrade->mergeModes = array();
$lang->upgrade->mergeModes['project']   = 'Automatically merge data and upgrade historical projects as projects';
$lang->upgrade->mergeModes['execution'] = 'Automatically merge data and upgrade historical projects as executions';
$lang->upgrade->mergeModes['manually']  = 'Manually merge data';

$lang->upgrade->mergeProjectTip   = 'The historical project will be synchronized directly to the new version of the project. At the same time, the system will create an iteration with the same name as the project according to the historical project, and migrate the tasks, requirements, bugs and other data under the previous project to the iteration.';
$lang->upgrade->mergeExecutionTip = 'The system will automatically create projects by year, and merge the historical iteration data into the corresponding projects by year.';
$lang->upgrade->createProgramTip  = 'At the same time, the system will automatically create a default project set and place all projects under the default project set.';
$lang->upgrade->mergeManuallyTip  = 'You can manually select the data merging method.';

include dirname(__FILE__) . '/version.php';
