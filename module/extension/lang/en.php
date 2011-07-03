<?php
/**
 * The extension module en file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->extension->common       = 'Extension';
$lang->extension->browse       = 'Browse';
$lang->extension->install      = 'Install';
$lang->extension->installAuto  = 'AutoInstall';
$lang->extension->installForce = 'ForceInstall';
$lang->extension->uninstall    = 'Uninstall';
$lang->extension->activate     = 'Activate';
$lang->extension->deactivate   = 'Deactivate';
$lang->extension->obtain       = 'Obtain';
$lang->extension->download     = 'Download';
$lang->extension->downloadAB   = 'Down';
$lang->extension->upload       = 'Upload';
$lang->extension->erase        = 'Erase';
$lang->extension->upgrade      = 'Upgrade';

$lang->extension->installed   = 'Installed';
$lang->extension->deactivated = 'Deactivated';
$lang->extension->available   = 'Available';

$lang->extension->id          = 'ID';
$lang->extension->name        = 'Name';
$lang->extension->code        = 'Code';
$lang->extension->version     = 'Version';
$lang->extension->author      = 'Author';
$lang->extension->license     = 'License';
$lang->extension->desc        = 'Description';
$lang->extension->site        = 'Site';
$lang->extension->addedTime   = 'Added Time';
$lang->extension->updatedTime = 'Updated Time';
$lang->extension->downloads   = 'Downloads';
$lang->extension->public      = 'Public';
$lang->extension->compatible  = 'Compatible';

$lang->extension->publicList[0] = 'Manually';
$lang->extension->publicList[1] = 'Auto';

$lang->extension->compatibleList[0] = 'Incompatible';
$lang->extension->compatibleList[1] = 'Compatible';

$lang->extension->byDownloads   = 'Downloads';
$lang->extension->byAddedTime   = 'New added';
$lang->extension->byUpdatedTime = 'Last updated';
$lang->extension->bySearch      = 'Search';
$lang->extension->byCategory    = 'By Category';

$lang->extension->installFailed            = 'Install failed, the reason is:';
$lang->extension->installFinished          = 'Good, the extension has been installed successfully.';
$lang->extension->refreshPage              = 'Refresh';
$lang->extension->uninstallFinished        = 'Extension has been successfully uninstalled.';
$lang->extension->deactivateFinished       = 'Extension has been successfully deactivated.';
$lang->extension->activateFinished         = 'Extension has been successfully activated.';
$lang->extension->eraseFinished            = 'Extension has been successfully erased.';
$lang->extension->unremovedFiles           = 'There are some unremoved files, you need remove them manually';
$lang->extension->executeCommands          = '<h3>Execute the following commands to fix them:</h3>';
$lang->extension->successDownloadedPackage = 'Successfully downloaded the package file.';
$lang->extension->successCopiedFiles       = 'Successfully copied files. ';
$lang->extension->successInstallDB         = 'Successfully installed database.';
$lang->extension->viewInstalled            = 'View installed extensions.';
$lang->extension->viewAvailable            = 'View available extensions';
$lang->extension->viewDeactivated          = 'View deactivated extensions';

$lang->extension->errorGetModules              = "Get extensions' categories data from the www.zentao.net failed. ";
$lang->extension->errorGetExtensions           = 'Get extensions from www.zentao.net failed. You can visit <a href="http://www.zentao.net/extension/" target="_blank">www.zentao.net</a> to find your extensions, download it manually and then upload to zentaopms to install it.';
$lang->extension->errorDownloadPathNotFound    = 'The save path of package file <strong>%s</strong>does not exists.<br />For linux users, can execute <strong>mkdir -p %s</strong> to fix it.';
$lang->extension->errorDownloadPathNotWritable = 'The save path of package file <strong>%s</strong>is not writable.<br />For linux users, can execute <strong>sudo chmod 777 %s</strong> to fix it.';
$lang->extension->errorPackageFileExists       = 'There is already a file with the same name <strong>%s</strong>.<h3> If you want to install again, <a href="%s">please click this link</a>.</h3>';
$lang->extension->errorDownloadFailed          = 'Download failed, please try again. Or you can download it manually and upload it to install.';
$lang->extension->errorMd5Checking             = 'The downloawd files checking failed, Please download it manually and upload it to install';
$lang->extension->errorExtracted               = 'The package file <strong> %s </strong> extracted failed. The error is:<br />%s';
$lang->extension->errorCheckIncompatible       = 'This extenion is not compatible with current zentao version. <h3>You can <a href="%s">force install</a> or <a href="#" onclick=parent.location.href="%s">cancel the installation</a></h3>.';
$lang->extension->errorFileConflicted          = 'There are some files conflicted: <br />%s <h3>You can <a href="%s">Overide them</a> or <a href="#" onclick=parent.location.href="%s">Cancel the installation</a></h3>.';
$lang->extension->errorPackageNotFound         = 'The package file <strong>%s </strong> not found, perhaps download failed, try again.';
$lang->extension->errorTargetPathNotWritable   = 'Target path <strong>%s </strong>not writable.';
$lang->extension->errorTargetPathNotExists     = 'Target path <strong>%s </strong>not exists';
$lang->extension->errorInstallDB               = 'Execute database sql failed, the error is: %s';
