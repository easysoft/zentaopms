<?php
/**
 * The extension module en file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->extension->common           = 'Extension';
$lang->extension->id               = 'ID';
$lang->extension->browse           = 'Extensions';
$lang->extension->install          = 'Install Extension';
$lang->extension->installAuto      = 'Auto Installation';
$lang->extension->installForce     = 'Force Installation';
$lang->extension->uninstall        = 'Uninstall';
$lang->extension->uninstallAction  = 'Uninstall Extension';
$lang->extension->activate         = 'Activate';
$lang->extension->activateAction   = 'Activate Extension';
$lang->extension->deactivate       = 'Deactivate';
$lang->extension->deactivateAction = 'Deactivate Extension';
$lang->extension->obtain           = 'Get Extension';
$lang->extension->view             = 'Detail';
$lang->extension->downloadAB       = 'Download';
$lang->extension->upload           = 'Local Installation';
$lang->extension->erase            = 'Erase';
$lang->extension->eraseAction      = 'Erase Extension';
$lang->extension->upgrade          = 'Upgrade Extension';
$lang->extension->agreeLicense     = 'I agree to the license.';

$lang->extension->browseAction = 'Extension List';

$lang->extension->structure        = 'Structure';
$lang->extension->structureAction  = 'Extension Structure';
$lang->extension->installed        = 'Installed';
$lang->extension->deactivated      = 'Deactivated';
$lang->extension->available        = 'Downloaded';

$lang->extension->name             = 'Extension Name';
$lang->extension->code             = 'Code';
$lang->extension->desc             = 'Description';
$lang->extension->type             = 'Type';
$lang->extension->dirs             = 'Installation Directory';
$lang->extension->files            = 'Installation Files';
$lang->extension->status           = 'Status';
$lang->extension->version          = 'Version';
$lang->extension->latest           = '<small>Latest:<strong><a href="%s" target="_blank" class="extension">%s</a></strong>，need zentao <a href="https://api.zentao.net/goto.php?item=latest" target="_blank"><strong>%s</strong></small>';
$lang->extension->author           = 'Author';
$lang->extension->license          = 'License';
$lang->extension->site             = 'Website';
$lang->extension->downloads        = 'Downloads';
$lang->extension->compatible       = 'Compatibility';
$lang->extension->grade            = 'Score';
$lang->extension->depends          = 'Dependency';
$lang->extension->expiredDate      = 'Expire';
$lang->extension->zentaoCompatible = 'Compatible Version';
$lang->extension->installedTime    = 'Installed Time';
$lang->extension->life             = 'lifetime';

$lang->extension->publicList[0] = 'Manual';
$lang->extension->publicList[1] = 'Auto';

$lang->extension->compatibleList[0] = 'Unknown';
$lang->extension->compatibleList[1] = 'Compatible';

$lang->extension->obtainOfficial[0] = 'Third-party';
$lang->extension->obtainOfficial[1] = 'Official';

$lang->extension->byDownloads   = 'Downloads';
$lang->extension->byAddedTime   = 'Latest Added';
$lang->extension->byUpdatedTime = 'Latest Update';
$lang->extension->bySearch      = 'Search';
$lang->extension->byCategory    = 'Category';

$lang->extension->installFailed            = '%s failed. Error:';
$lang->extension->uninstallFailed          = 'Uninstallation failed. Error:';
$lang->extension->confirmUninstall         = 'Uninstallation will delete or change related database. Do you want to uninstall it?';
$lang->extension->installFinished          = 'Congrats! The extension is %sed!';
$lang->extension->refreshPage              = 'Refresh';
$lang->extension->uninstallFinished        = 'This extension is uninstalled.';
$lang->extension->deactivateFinished       = 'This extension is deactivated.';
$lang->extension->activateFinished         = 'This extension is activated.';
$lang->extension->eraseFinished            = 'This extension is removed.';
$lang->extension->unremovedFiles           = 'File or direcroty cannot be deleted. You have to manually delete';
$lang->extension->executeCommands          = '<h3>Execute command lines below to fix the problem:</h3>';
$lang->extension->successDownloadedPackage = 'This extension is downloaded!';
$lang->extension->successCopiedFiles       = 'File is copied!';
$lang->extension->successInstallDB         = 'Database is installed!';
$lang->extension->viewInstalled            = 'Installed Extensions';
$lang->extension->viewAvailable            = 'Available Extensions';
$lang->extension->viewDeactivated          = 'Deactivated Extensions';
$lang->extension->backDBFile               = 'This extension data has been backed up to %s!';
$lang->extension->noticeOkFile             = "<p class='font-bold mb-2'>For security reasons, your Admin account has to be confirmed.</p>
    <p class='font-bold mb-2'><strong>Please login your ZenTao server and create %s.</strong></p>
    <p class='mb-2'>Execute command: echo '' > %s</p>
    <p class='mb-2'>Note</p>
    <ul class='mb-2 pl-4' style='list-style: decimal'>Note</p>
    <li>The file you will create is empty.</li>
    <li>If such file exists, delete it first, and then create one.</li>
    </ul>";

$lang->extension->upgradeExt     = 'Upgrade';
$lang->extension->installExt     = 'Install';
$lang->extension->upgradeVersion = '(Upgrade %s to %s.)';

$lang->extension->waring = 'Warning!';

$lang->extension->errorOccurs                  = 'Error:';
$lang->extension->errorGetModules              = 'Get Extension Category from www.zentao.pm failed. It could be network error. Plase check your network and refresh it.';
$lang->extension->errorGetExtensions           = 'Get Extensions from www.zentao.pm failed. It could be network error. Please go to <a href="https://www.zentao.net/extension/" target="_blank" class="alert-link">www.zentao.pm</a> and download the extension, and then upload it to install.';
$lang->extension->errorDownloadPathNotFound    = 'Extension download path <strong>%s</strong> is not found.<br /> Please run <strong>mkdir -p %s</strong> in Linux to fix it.';
$lang->extension->errorDownloadPathNotWritable = 'Extension download path <strong>%s</strong>is not writable. <br />Please run <strong>sudo chmod 777 %s</strong> in Linux to fix it.';
$lang->extension->errorPackageFileExists       = '<strong>%s</strong> exists in the download path.<strong> Please %s it again, <a href="%s" class="alert-link">CLICK HERE</a></h5>';
$lang->extension->errorDownloadFailed          = 'Download failed. Please try it again. If still not OK, try to download it manually and upload it to install.';
$lang->extension->errorMd5Checking             = 'Incomplete File. Please download it again. If still not OK, try to download it manually and upload it to install.';
$lang->extension->errorCheckIncompatible       = 'Incompatible with your ZenTao. It may not be used %s later.<strong>You can choose to <a href="#" load-url="%s" onclick="loadUrl(this)" class="btn size-sm">force%s</a> or <a href="#" load-url="%s" onclick="loadParentUrl(this)" class="btn size-sm">cancel</a></h5>';
$lang->extension->errorFileConflicted          = '<br />%s <strong> is conflicted with others. Choose <a href="#" load-url="%s" onclick="loadUrl(this)" class="btn size-sm">Override</a> or <a href="#" load-url="%s" onclick="loadParentUrl(this)" class="btn size-sm">Cancel</a></h5>';
$lang->extension->errorPackageNotFound         = '<strong>%s </strong> is not found. Downloading might be failed. Please download it again.';
$lang->extension->errorTargetPathNotWritable   = '<strong>%s </strong> is not writable.';
$lang->extension->errorTargetPathNotExists     = '<strong>%s </strong> is not found.';
$lang->extension->errorInstallDB               = 'Database report execution failed. Error: %s';
$lang->extension->errorConflicts               = 'Conflicted with “%s”!';
$lang->extension->errorDepends                 = 'Dependent extension has not been installed or the version is incorrect:<br /><br /> %s';
$lang->extension->errorIncompatible            = 'Incompatible with your ZenTao.';
$lang->extension->errorUninstallDepends        = '“%s” is dependent on this extension. Please do not uninstall it.';
$lang->extension->errorExtracted               = 'The package file %s extracted failed. The error is:<br />%s';
$lang->extension->errorFileNotEmpty            = 'Please upload the file.';
