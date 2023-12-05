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
$lang->extension->common           = 'Erweiterung';
$lang->extension->id               = 'ID';
$lang->extension->browse           = 'Durchsuchen';
$lang->extension->install          = 'Installieren';
$lang->extension->installAuto      = 'Auto Install';
$lang->extension->installForce     = 'Installation erzwingen';
$lang->extension->uninstall        = 'Deinstallieren';
$lang->extension->uninstallAction  = 'Uninstall Extension';
$lang->extension->activate         = 'Aktivieren';
$lang->extension->activateAction   = 'Activate Extension';
$lang->extension->deactivate       = 'Deaktivieren';
$lang->extension->deactivateAction = 'Deactivate Extension';
$lang->extension->obtain           = 'Hole Erweiterung';
$lang->extension->view             = 'Details';
$lang->extension->downloadAB       = 'Download';
$lang->extension->upload           = 'Local Installation';
$lang->extension->erase            = 'Löschen';
$lang->extension->eraseAction      = 'Erase Extension';
$lang->extension->upgrade          = 'Erweiterung Upgraden';
$lang->extension->agreeLicense     = 'Lizenz';

$lang->extension->browseAction = 'Extension List';

$lang->extension->structure        = 'Struktur';
$lang->extension->structureAction  = 'Struktur';
$lang->extension->installed        = 'Installiert';
$lang->extension->deactivated      = 'Deaktiviert';
$lang->extension->available        = 'Heruntergeladen';

$lang->extension->name             = 'Erweiterungsname';
$lang->extension->code             = 'Code';
$lang->extension->desc             = 'Beschreiben';
$lang->extension->type             = 'Typ';
$lang->extension->dirs             = 'Verzeichnisse';
$lang->extension->files            = 'Dateien';
$lang->extension->status           = 'Status';
$lang->extension->version          = 'Version';
$lang->extension->latest           = '<small>Letzte:<strong><a href="%s" target="_blank" class="extension">%s</a></strong>，benötigt ZenTao <a href="http://api.zentao.net/goto.php?item=latest" target="_blank"><strong>%s</strong></small>';
$lang->extension->author           = 'Author';
$lang->extension->license          = 'Lizenz';
$lang->extension->site             = 'Website';
$lang->extension->downloads        = 'Downloads';
$lang->extension->compatible       = 'Kompatibilität';
$lang->extension->grade            = 'Wertung';
$lang->extension->depends          = 'Benötigt';
$lang->extension->expiredDate      = 'Verfällt';
$lang->extension->zentaoCompatible = 'Kompatible Version';
$lang->extension->installedTime    = 'Installationszeit';
$lang->extension->life             = 'lifetime';

$lang->extension->publicList[0] = 'Manuell';
$lang->extension->publicList[1] = 'Auto';

$lang->extension->compatibleList[0] = 'Unbekannt';
$lang->extension->compatibleList[1] = 'Kompatibel';

$lang->extension->obtainOfficial[0] = 'Third party';
$lang->extension->obtainOfficial[1] = 'Official';

$lang->extension->byDownloads   = 'Downloads';
$lang->extension->byAddedTime   = 'Zuletzt hinzugefügt';
$lang->extension->byUpdatedTime = 'Zuletzt bearbeitet';
$lang->extension->bySearch      = 'Suche';
$lang->extension->byCategory    = 'Kategorie';

$lang->extension->installFailed            = '%s fehlgeschlagen. Error:';
$lang->extension->uninstallFailed          = 'Deinstallation fehlgeschlagen. Error:';
$lang->extension->confirmUninstall         = 'Deinstallation der Erweiterung führt zur Anpassung der Datenbank. Möchten Sie deinstallieren?';
$lang->extension->installFinished          = 'Glückwunsch! Die Erweiterung wurde %s!';
$lang->extension->refreshPage              = 'Aktualisieren';
$lang->extension->uninstallFinished        = 'Erweiterung wurde deinstalliert.';
$lang->extension->deactivateFinished       = 'Erweiterung wurde deaktiviert.';
$lang->extension->activateFinished         = 'Erweiterung wurde aktiviert.';
$lang->extension->eraseFinished            = 'Erweiterung wurde entfernt';
$lang->extension->unremovedFiles           = 'Datei oder Verzeichnis konnte nicht gelöscht werden. Sie müssen es manuell löschen';
$lang->extension->executeCommands          = '<h3>Führen Sie die unten aufgeführten Befehle aus um das Problem zu lösen:</h3>';
$lang->extension->successDownloadedPackage = 'Erweiterung heruntergeladen!';
$lang->extension->successCopiedFiles       = 'Date kopiert!';
$lang->extension->successInstallDB         = 'Datenbank installiert!';
$lang->extension->viewInstalled            = 'Installiert';
$lang->extension->viewAvailable            = 'Verfügbar';
$lang->extension->viewDeactivated          = 'Deactiviert';
$lang->extension->backDBFile               = 'Erweiterungsdaten wurden gesichert nach %s!';
$lang->extension->noticeOkFile             = "<p class='font-bold mb-2'>For security reasons, your Admin account has to be confirmed.</p>
    <p class='font-bold mb-2'><strong>Please login your ZenTao server and create %s.</strong></p>
    <p class='mb-2'>Execute command: echo '' > %s</p>
    <p class='mb-2'>Note</p>
    <ul class='mb-2 pl-4' style='list-style: decimal'>Note</p>
    <li>The file you will create is empty.</li>
    <li>If such file exists, delete it first, and then create one.</li>
    </ul>";

$lang->extension->upgradeExt     = 'Upgrade';
$lang->extension->installExt     = 'Installieren';
$lang->extension->upgradeVersion = '(Upgrade %s zu %s.)';

$lang->extension->waring = 'Warnung!';

$lang->extension->errorOccurs                  = 'Error:';
$lang->extension->errorGetModules              = 'Die Kategorie konnte nicht von www.zentao.net ermittelt werden. Es könnte eine Netzwerkfehler vorliegen. Bitte prüfen Sie die Verbindung und aktualisieren Sie die Seite.';
$lang->extension->errorGetExtensions           = 'Die Erweiterung konnte nicht von www.zentao.net geladen werden. Es könnte eine Netzwerkfehler vorliegen. Bitte besuchen Sie <a href="http://www.zentao.net/extension/" target="_blank" class="alert-link">www.zentao.net</a> und laden Sie die Erweiterung. Anschließend laden Sie die Erweiterung hoch und installieren sie.';
$lang->extension->errorDownloadPathNotFound    = 'Downloadpfad der Erweiterung <strong>%s</strong> nicht gefunden.<br /> Bitte führen Sie <strong>mkdir -p %s</strong> aus.';
$lang->extension->errorDownloadPathNotWritable = 'Downloadpfad der Erweiterung <strong>%s</strong>ist nicht beschreibbar. <br />Bitte führen Sie <strong>sudo chmod 777 %s</strong> aus.';
$lang->extension->errorPackageFileExists       = '<strong>%s</strong> existiert bereits im Downloadpfad.<strong> Bitte %s es erneut, <a href="%s" class="alert-link">Hier klicken</a></h5>';
$lang->extension->errorDownloadFailed          = 'Download fehlgeschlagen. Bitte versuchen Sie es erneut. Sollte der Fehler weiterhinbestehen, Laden Sie die Datei erneut herunter und versuchen Sie es nochmals.';
$lang->extension->errorMd5Checking             = 'Korrupte Datei. Laden Sie die Datei erneut herunter und versuchen Sie es nochmals.';
$lang->extension->errorCheckIncompatible       = 'Incompatible with your ZenTao. It may not be used %s later.<strong>You can choose to <a href="#" load-url="%s" onclick="loadUrl(this)" class="btn size-sm">force%s</a> or <a href="#" load-url="%s" onclick="loadParentUrl(this)" class="btn size-sm">cancel</a></h5>';
$lang->extension->errorFileConflicted          = '<br />%s <strong> is conflicted with others. Choose <a href="#" load-url="%s" onclick="loadUrl(this)" class="btn size-sm">Override</a> or <a href="#" load-url="%s" onclick="loadParentUrl(this)" class="btn size-sm">Cancel</a></h5>';
$lang->extension->errorPackageNotFound         = '<strong>%s </strong> nicht gefunden. Download könnte fehlgeschlagen sein. Bitte laden Sie die Datei nochmals.';
$lang->extension->errorTargetPathNotWritable   = '<strong>%s </strong> ist nicht beschreibbar.';
$lang->extension->errorTargetPathNotExists     = '<strong>%s </strong> nicht gefunden.';
$lang->extension->errorInstallDB               = 'Datenbank Befehl fehlgeschlagen. Error: %s';
$lang->extension->errorConflicts               = 'Konflikt mit “%s”!';
$lang->extension->errorDepends                 = 'Benötigte Erweiterung wurde nicht installiert oder die Versionen passen nicht:<br /><br /> %s';
$lang->extension->errorIncompatible            = 'Inkompatibel mit dieser Version von ZenTao.';
$lang->extension->errorUninstallDepends        = '“%s” benötigt für diese Erweiterung. Bitte nicht installieren.';
$lang->extension->errorExtracted               = 'The package file %s extracted failed. The error is:<br />%s';
$lang->extension->errorFileNotEmpty            = 'Please upload the file.';
