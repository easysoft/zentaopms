<?php
/**
 * The extension module en file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Nguyễn Quốc Nho <quocnho@gmail.com>
 * @package  extension
 * @version  $Id$
 * @link  http://www.zentao.net
 */
$lang->extension->common           = 'Extension';
$lang->extension->browse           = 'Extensions';
$lang->extension->install          = 'Install Extension';
$lang->extension->installAuto      = 'Auto Installation';
$lang->extension->installForce     = 'Force Installation';
$lang->extension->uninstall        = 'Uninstall';
$lang->extension->uninstallAction  = 'Uninstall Extension';
$lang->extension->activate         = 'Kích hoạt';
$lang->extension->activateAction   = 'Kích hoạt Extension';
$lang->extension->deactivate       = 'Vô hiệu';
$lang->extension->deactivateAction = 'Deactivate Extension';
$lang->extension->obtain           = 'Nhận Extension';
$lang->extension->view             = 'Chi tiết';
$lang->extension->downloadAB       = 'Tải về';
$lang->extension->upload           = 'Local Installation';
$lang->extension->erase            = 'Erase';
$lang->extension->eraseAction      = 'Erase Extension';
$lang->extension->upgrade          = 'Nâng cấp Extension';
$lang->extension->agreeLicense     = 'I agree to the license.';

$lang->extension->structure       = 'Structure';
$lang->extension->structureAction = 'Extension Structure';
$lang->extension->installed       = 'Installed';
$lang->extension->deactivated     = 'Deactivated';
$lang->extension->available       = 'Downloaded';

$lang->extension->name             = 'Extension tên';
$lang->extension->code             = 'Mã';
$lang->extension->desc             = 'Mô tả';
$lang->extension->type             = 'Loại';
$lang->extension->dirs             = 'Installation Directory';
$lang->extension->files            = 'Installation Files';
$lang->extension->status           = 'Tình trạng';
$lang->extension->version          = 'Phiên bản';
$lang->extension->latest           = '<small>Latest:<strong><a href="%s" target="_blank" class="extension">%s</a></strong>，need zentao <a href="https://api.zentao.net/goto.php?item=latest" target="_blank"><strong>%s</strong></small>';
$lang->extension->author           = 'Author';
$lang->extension->license          = 'Bản quyền';
$lang->extension->site             = 'Website';
$lang->extension->downloads        = 'Downloads';
$lang->extension->compatible       = 'Compatibility';
$lang->extension->grade            = 'Điểm';
$lang->extension->depends          = 'Dependency';
$lang->extension->expireDate       = 'Expire';
$lang->extension->zentaoCompatible = 'Compatible Version';
$lang->extension->installedTime    = 'Installed Time';

$lang->extension->publicList[0] = 'Manual';
$lang->extension->publicList[1] = 'Tự động';

$lang->extension->compatibleList[0] = 'Unknown';
$lang->extension->compatibleList[1] = 'Compatible';

$lang->extension->obtainOfficial[0] = 'Third-party';
$lang->extension->obtainOfficial[1] = 'Official';

$lang->extension->byDownloads   = 'Downloads';
$lang->extension->byAddedTime   = 'Latest Added';
$lang->extension->byUpdatedTime = 'Latest Update';
$lang->extension->bySearch      = 'Tìm kiếm';
$lang->extension->byCategory    = 'Danh mục';

$lang->extension->installFailed            = '%s failed. Lỗi: ';
$lang->extension->uninstallFailed          = 'Uninstallation failed. Lỗi: ';
$lang->extension->confirmUninstall         = 'Uninstallation will xóa or change related database. Bạn có muốn uninstall nó?';
$lang->extension->installFinished          = 'Congrats! The extension is %sed!';
$lang->extension->refreshPage              = 'Refresh';
$lang->extension->uninstallFinished        = 'This extension is uninstalled.';
$lang->extension->deactivateFinished       = 'This extension is deactivated.';
$lang->extension->activateFinished         = 'This extension is activated.';
$lang->extension->eraseFinished            = 'This extension is removed.';
$lang->extension->unremovedFiles           = 'File or direcroty không thể xóa. You have to manually delete';
$lang->extension->executeCommands          = '<h3>Execute command lines below to fix the problem:</h3>';
$lang->extension->successDownloadedPackage = 'This extension is downloaded!';
$lang->extension->successCopiedFiles       = 'File is copied!';
$lang->extension->successInstallDB         = 'Database is installed!';
$lang->extension->viewInstalled            = 'Installed Extensions';
$lang->extension->viewAvailable            = 'Available Extensions';
$lang->extension->viewDeactivated          = 'Deactivated Extensions';
$lang->extension->backDBFile               = 'This extension data has been backed up to %s!';
$lang->extension->noticeOkFile             = '<h5>For security reasons, your Admin account has to be confirmed.</h5>
 <h5>Plese login your ZenTao server and create %s.</h5>
 <p>Note</p>
 <ol>
 <li>The file you will create is empty.</li>
 <li>If such file exists, xóa it first, and then create one.</li>
 </ol>'; 

$lang->extension->upgradeExt     = 'Nâng cấp';
$lang->extension->installExt     = 'Cài đặt';
$lang->extension->upgradeVersion = '(Upgrade %s to %s.)';

$lang->extension->waring = 'Cảnh báo';

$lang->extension->errorOccurs                  = 'Error:';
$lang->extension->errorGetModules              = 'Nhận Extension Category from www.zentao.pm failed. It could be network error. Plase check your network and refresh it.';
$lang->extension->errorGetExtensions           = 'Nhận Extensions from www.zentao.pm failed. It could be network error. Vui lòng go to <a href="https://www.zentao.net/extension/" target="_blank" class="alert-link">www.zentao.pm</a> and download the extension, and then upload it to install.';
$lang->extension->errorDownloadPathNotFound    = 'Extension download path <strong>%s</strong> không là found.<br /> Vui lòng run <strong>mkdir -p %s</strong> in Linux to fix it.';
$lang->extension->errorDownloadPathNotWritable = 'Extension download path <strong>%s</strong>is not writable. <br />Please run <strong>sudo chmod 777 %s</strong> in Linux to fix it.';
$lang->extension->errorPackageFileExists       = '<strong>%s</strong> exists in the download path.<h5> Vui lòng %s it again, <a href="%s" class="alert-link">CLICK HERE</a></h5>';
$lang->extension->errorDownloadFailed          = 'Tải về failed. Vui lòng try it again. If still not OK, try to download it manually and upload it to install.';
$lang->extension->errorMd5Checking             = 'Incomplete File. Vui lòng download it again. If still not OK, try to download it manually and upload it to install.';
$lang->extension->errorCheckIncompatible       = 'Incompatible with your ZenTao. It may not be used %s later.<h5>Bạn có thể choose to <a href="%s" class="btn btn-sm">force%s</a> or <a href="#" onclick=parent.location.href="%s" class="btn btn-sm">cancel</a></h5>';
$lang->extension->errorFileConflicted          = '<br />%s <h5> is conflicted with others. Choose <a href="%s" class="btn btn-sm">Override</a> or <a href="#" onclick=parent.location.href="%s" class="btn btn-sm">Cancel</a></h5>';
$lang->extension->errorPackageNotFound         = '<strong>%s </strong> không là found. Downloading might be failed. Vui lòng download it again.';
$lang->extension->errorTargetPathNotWritable   = '<strong>%s </strong> không thể ghi.';
$lang->extension->errorTargetPathNotExists     = '<strong>%s </strong> không là found.';
$lang->extension->errorInstallDB               = 'Database report execution failed. Lỗi: %s';
$lang->extension->errorConflicts               = 'Conflicted with “%s”!';
$lang->extension->errorDepends                 = 'Dependent extension has not been installed or the version is incorrect:<br /><br /> %s';
$lang->extension->errorIncompatible            = 'Incompatible with your ZenTao.';
$lang->extension->errorUninstallDepends        = '“%s” is dependent on this extension. Vui lòng do not uninstall it.';
