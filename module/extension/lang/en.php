<?php
/**
 * The extension module en file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->extension->common        = 'Extension';
$lang->extension->browse        = 'Browse';
$lang->extension->install       = 'Install';
$lang->extension->installAuto   = 'Auto Install';
$lang->extension->installForce  = 'Force Install';
$lang->extension->uninstall     = 'Uninstall';
$lang->extension->activate      = 'Activate';
$lang->extension->deactivate    = 'Deactivate';
$lang->extension->obtain        = 'Get Extension';
$lang->extension->view          = 'Details';
$lang->extension->downloadAB    = 'Download';
$lang->extension->upload        = 'Local Install';
$lang->extension->erase         = 'Erase';
$lang->extension->upgrade       = 'Extension Upgrade';
$lang->extension->agreeLicense  = 'License';

$lang->extension->structure   = 'Structure';
$lang->extension->installed   = 'Installed';
$lang->extension->deactivated = 'Deactivated';
$lang->extension->available   = 'Downloaded';

$lang->extension->version     = 'Version';
$lang->extension->compatible  = 'Compatible';
$lang->extension->latest      = '<small>Latest:<strong><a href="%s" target="_blank" class="extension">%s</a></strong>，need zentao <a href="http://api.zentao.net/goto.php?item=latest" target="_blank"><strong>%s</strong></small>';
$lang->extension->author      = 'Author';
$lang->extension->license     = 'License';
$lang->extension->site        = 'Website';
$lang->extension->downloads   = 'Downloads';
$lang->extension->compatible  = 'Compatibility';
$lang->extension->grade       = 'Score';
$lang->extension->depends     = 'Dependent';

$lang->extension->publicList[0] = 'Manually';
$lang->extension->publicList[1] = 'Auto';

$lang->extension->compatibleList[0] = 'Unknown';
$lang->extension->compatibleList[1] = 'Compatible';

$lang->extension->byDownloads   = 'Downloads';
$lang->extension->byAddedTime   = 'Latest Added';
$lang->extension->byUpdatedTime = 'Latest Update';
$lang->extension->bySearch      = 'Search';
$lang->extension->byCategory    = 'Category';

$lang->extension->installFailed            = '%s failed. Error:';
$lang->extension->uninstallFailed          = 'Uninstall failed. Error:';
$lang->extension->confirmUninstall         = 'Uninstal the extension will delete or change related database. Do you want to uninstall?';
$lang->extension->installFinished          = 'Congrats! The extension has been %sed!';
$lang->extension->refreshPage              = 'Refresh';
$lang->extension->uninstallFinished        = 'Extension has been uninstalled.';
$lang->extension->deactivateFinished       = 'Extension has been deactivated.';
$lang->extension->activateFinished         = 'Extension has been activated.';
$lang->extension->eraseFinished            = 'Extension has been removed.';
$lang->extension->unremovedFiles           = 'File or direcroty cannot be deleted. You have to manually delete';
$lang->extension->executeCommands          = '<h3>Execute command lines below to fix the problem:</h3>';
$lang->extension->successDownloadedPackage = 'Extension downloaded!';
$lang->extension->successCopiedFiles       = 'File copied!';
$lang->extension->successInstallDB         = 'Database installed!';
$lang->extension->viewInstalled            = 'Installed';
$lang->extension->viewAvailable            = 'Available';
$lang->extension->viewDeactivated          = 'Deactivated';
$lang->extension->backDBFile               = 'Extension related data has been backed up to %s!';
$lang->extension->noticeOkFile             = '<h5>For security reasons, your Admin account has to be confirmed.</h5>
    <h5>Plese Login your ZenTao server and create %s.</h5>
    <p>Note</p>
    <ol>
    <li>The file you will create is empty.</li>
    <li>If there is one such file exists, delete it first, and then create yours.</li>
    </ol>'; 

$lang->extension->upgradeExt     = 'Upgrade';
$lang->extension->installExt     = 'Install';
$lang->extension->upgradeVersion = '(Upgrade %s to %s.)';

$lang->extension->waring = 'Warning!';

$lang->extension->errorOccurs                  = 'Error:';
$lang->extension->errorGetModules              = 'Get Extension Category from www.zentao.net failed. It could be network error. Plase check your network and refresh it.';
$lang->extension->errorGetExtensions           = 'Get Extension from www.zentao.net failed. It could be network error. Please go to <a href="http://www.zentao.net/extension/" target="_blank" class="alert-link">www.zentao.net</a> and download the extension, and then upload it to install.';
$lang->extension->errorDownloadPathNotFound    = 'Extension download path <strong>%s</strong> is not found.<br /> Please run <strong>mkdir -p %s</strong> in Linux to fix it.';
$lang->extension->errorDownloadPathNotWritable = 'Extensiond ownload path <strong>%s</strong>is not writable. <br />Please run <strong>sudo chmod 777 %s</strong> in Linux to fix it.';
$lang->extension->errorPackageFileExists       = '<strong>%s</strong> has existed in the download path.<h5> Please %s it again, <a href="%s" class="alert-link">CLICK HERE</a></h5>';
$lang->extension->errorDownloadFailed          = 'Download failed. Please try it again. If still not OK, try to download it manually and upload it to install.';
$lang->extension->errorMd5Checking             = 'Incomplete File. Please download it again. If still not OK, try to download it manually and upload it to install.';
$lang->extension->errorExtracted               = 'Unzip <strong> %s </strong> failed. It could be an invailid zip file. Error: <br />%s';
$lang->extension->errorCheckIncompatible       = 'It is incompatible with this versiosn of ZenTao. %sed it and you might not be able to use it. <h3>Choose <a href="%s">Force %s</a> or <a href="#" onclick=parent.location.href="%s"> Cancel</a></h3>';
$lang->extension->errorFileConflicted          = '<br />%s <h3> is conflicted with others. Choose <a href="%s">Override</a> or <a href="#" onclick=parent.location.href="%s">Cancel</a></h3>';
$lang->extension->errorPackageNotFound         = '<strong>%s </strong> is not found. Downloading might be failed. Please download it again.';
$lang->extension->errorTargetPathNotWritable   = '<strong>%s </strong> is not writable.';
$lang->extension->errorTargetPathNotExists     = '<strong>%s </strong> is not found.';
$lang->extension->errorInstallDB               = 'Database statements execution failed. Error: %s';
$lang->extension->errorConflicts               = 'Conflicted with “%s”!';
$lang->extension->errorDepends                 = 'Dependent extension has not been installed or the version is incorrect:<br /><br /> %s';
$lang->extension->errorIncompatible            = 'Incompatible with this version of ZenTao.';
$lang->extension->errorUninstallDepends        = '“%s” is dependent on this extension. Please do not install it.';
