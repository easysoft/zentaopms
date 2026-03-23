<?php
/**
 * The extension module en file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        https://www.zentao.net
 */
$lang->extension->common           = 'Extension';
$lang->extension->id               = 'ID';
$lang->extension->browse           = 'View Add-ons';
$lang->extension->install          = 'Install Add-on';
$lang->extension->installAuto      = 'Auto Install';
$lang->extension->installForce     = 'Force Install';
$lang->extension->uninstall        = 'Uninstall';
$lang->extension->uninstallAction  = 'Uninstall Add-on';
$lang->extension->activate         = 'Activate';
$lang->extension->activateAction   = 'Activate Add-on';
$lang->extension->deactivate       = 'Deactivate';
$lang->extension->deactivateAction = 'Deactivate Add-on';
$lang->extension->obtain           = 'Get Add-ons';
$lang->extension->view             = 'Details';
$lang->extension->downloadAB       = 'Download';
$lang->extension->upload           = 'Local Installation';
$lang->extension->erase            = 'Remove';
$lang->extension->eraseAction      = 'Remove Add-on';
$lang->extension->upgrade          = 'Upgrade Add-on';
$lang->extension->agreeLicense     = 'I agree.';

$lang->extension->browseAction = 'Add-on List';

$lang->extension->structure        = 'Directory Structure';
$lang->extension->structureAction  = 'Directory Structure';
$lang->extension->installed        = 'Installed';
$lang->extension->deactivated      = 'Deactivated';
$lang->extension->available        = 'Downloaded';

$lang->extension->name             = 'Add-on Name';
$lang->extension->code             = 'Code';
$lang->extension->desc             = 'Description';
$lang->extension->type             = 'Type';
$lang->extension->dirs             = 'Installation Directory';
$lang->extension->files            = 'Installation Files';
$lang->extension->status           = 'Status';
$lang->extension->version          = 'Version';
$lang->extension->latest           = '<small>Latest version<strong><a href="%s" target="_blank" class="extension">%s</a></strong>, compatible with ZenTao <a href="https://api.zentao.net/goto.php?item=latest" target="_blank" class="alert-link"><strong>%s</strong></a></small>';
$lang->extension->author           = 'Author';
$lang->extension->license          = 'License';
$lang->extension->site             = 'Website';
$lang->extension->downloads        = 'Downloads';
$lang->extension->compatible       = 'Compatibility';
$lang->extension->grade            = 'Score';
$lang->extension->depends          = 'Dependency';
$lang->extension->expiredDate      = 'Expiration Date';
$lang->extension->zentaoCompatible = 'Compatible Versions';
$lang->extension->installedTime    = 'Installed Time';
$lang->extension->life             = 'lifetime';

$lang->extension->publicList[0] = 'Manual Download';
$lang->extension->publicList[1] = 'Auto Download';

$lang->extension->compatibleList[0] = 'Unknown';
$lang->extension->compatibleList[1] = 'Compatible with';

$lang->extension->obtainOfficial[0] = 'Third-party';
$lang->extension->obtainOfficial[1] = 'Official';
$lang->extension->obtainOfficial[2] = 'Official Certification';

$lang->extension->byDownloads   = 'Most Popular';
$lang->extension->byAddedTime   = 'Latest Release';
$lang->extension->byUpdatedTime = 'Latest Update';
$lang->extension->bySearch      = 'Search';
$lang->extension->byCategory    = 'Category';

$lang->extension->featureBar['browse']['installed']   = $lang->extension->installed;
$lang->extension->featureBar['browse']['deactivated'] = $lang->extension->deactivated;
$lang->extension->featureBar['browse']['available']   = $lang->extension->available;

$lang->extension->installFailed            = '%s failed. Error:';
$lang->extension->uninstallFailed          = 'Uninstall failed. Error: ';
$lang->extension->confirmUninstall         = 'Uninstalling this add-on will remove or modify related database tables. Do you want to proceed?';
$lang->extension->installFinished          = 'Congratulations! The add-on was successfully %s.';
$lang->extension->refreshPage              = 'Refresh';
$lang->extension->uninstallFinished        = 'This add-on is uninstalled.';
$lang->extension->deactivateFinished       = 'This add-on is deactivated.';
$lang->extension->activateFinished         = 'This add-on is activated.';
$lang->extension->eraseFinished            = 'This add-on is removed.';
$lang->extension->unremovedFiles           = 'Some files or directories could not be deleted. Please remove them manually.';
$lang->extension->executeCommands          = '<h3>Execute the following commands to fix these issues:</h3>';
$lang->extension->successDownloadedPackage = 'Add-on downloaded successfully.';
$lang->extension->successCopiedFiles       = 'Files copied successfully.';
$lang->extension->successInstallDB         = 'Database installed successfully.';
$lang->extension->viewInstalled            = 'View Installed Add-ons';
$lang->extension->viewAvailable            = 'View Available Add-ons';
$lang->extension->viewDeactivated          = 'View Deactivated Extensions';
$lang->extension->backDBFile               = 'Add-on data has been backed up to %s.';

$lang->extension->upgradeExt     = 'Upgrade';
$lang->extension->installExt     = 'Install';
$lang->extension->upgradeVersion = '(Upgrade from %s to %s)';

$lang->extension->waring = 'Warning';

$lang->extension->errorOccurs                  = 'Error:';
$lang->extension->errorGetModules              = 'Failed to fetch add-on categories from www.sanplex.com. This may be due to network issues. Please check your connection and refresh the page.';
$lang->extension->errorGetExtensions           = 'Failed to get the add-on from www.sanplex.com. This may be due to network issues.';
$lang->extension->errorDownloadPathNotFound    = 'The download directory <strong>%s</strong> does not exist.<br />For Linux, please execute the command: <strong>mkdir -p %s</strong> to fix it.';
$lang->extension->errorDownloadPathNotWritable = 'The download directory <strong>%s</strong> is not writable.<br />For Linux, please execute the command: <strong>sudo chmod 777 %s</strong> to fix it.';
$lang->extension->errorPackageFileExists       = 'A file named <strong>%s</strong> already exists in the download directory. <strong>To %s again, <a href="%s" class="alert-link">please click here</a>.</strong>';
$lang->extension->errorDownloadFailed          = 'Download failed. Please try again. If the problem persists, please download the file manually and install it via the upload function.';
$lang->extension->errorMd5Checking             = 'The downloaded file is incomplete. Please try again. If the problem persists, please download the file manually and install it via the upload function.';
$lang->extension->errorCheckIncompatible       = 'This add-on is incompatible with your version of Sanplex and may not function correctly after %s. <strong>You can <a href="#" load-url="%s" onclick="loadUrl(this)" class="btn size-sm">Force %s</a> or <a href="#" load-url="%s" onclick="loadParentUrl(this)" class="btn size-sm">Cancel</a>.</strong>';
$lang->extension->errorFileConflicted          = 'The following file conflicts were detected:<br/>%s<strong>You can <a href="#" load-url="%s" onclick="loadUrl(this)" class="btn size-sm">Overwrite</a> or <a href="#" load-url="%s" onclick="loadParentUrl(this)" class="btn size-sm">Cancel</a>.</strong>';
$lang->extension->errorPackageNotFound         = 'File <strong>%s</strong> not found. The automatic download may have failed. Please try downloading again.';
$lang->extension->errorTargetPathNotWritable   = 'The target directory <strong>%s</strong> is not writable.';
$lang->extension->errorTargetPathNotExists     = 'The target directory <strong>%s</strong> does not exist.';
$lang->extension->errorInstallDB               = 'Failed to execute database query. Error: %s';
$lang->extension->errorConflicts               = 'Conflicted with “%s”!';
$lang->extension->errorDepends                 = 'The following add-on dependencies are missing or have incorrect versions:<br/><br/>%s';
$lang->extension->errorIncompatible            = 'This add-on is incompatible with your version of Sanplex.';
$lang->extension->errorUninstallDepends        = 'Cannot uninstall. Add-on "%s" depends on this add-on.';
$lang->extension->errorExtracted               = 'Failed to extract package file %s. It may not be a valid ZIP file. Error:<br/>%s';
$lang->extension->errorFileNotEmpty            = 'Uploaded file cannot be empty.';
