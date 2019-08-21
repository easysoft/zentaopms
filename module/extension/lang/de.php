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
$lang->extension->common        = 'Erweiterung';
$lang->extension->browse        = 'Durchsuchen';
$lang->extension->install       = 'Installieren';
$lang->extension->installAuto   = 'Auto Install';
$lang->extension->installForce  = 'Installation erzwingen';
$lang->extension->uninstall     = 'Deinstallieren';
$lang->extension->uninstallAction  = 'Uninstall Extension';
$lang->extension->activate      = 'Aktivieren';
$lang->extension->activateAction   = 'Activate Extension';
$lang->extension->deactivate    = 'Deaktivieren';
$lang->extension->deactivateAction = 'Deactivate Extension';
$lang->extension->obtain        = 'Hole Erweiterung';
$lang->extension->view          = 'Details';
$lang->extension->downloadAB    = 'Download';
$lang->extension->upload        = 'Local Installation';
$lang->extension->erase         = 'Löschen';
$lang->extension->eraseAction      = 'Erase Extension';
$lang->extension->upgrade       = 'Erweiterung Upgraden';
$lang->extension->agreeLicense  = 'Lizenz';

$lang->extension->structure       = 'Struktur';
$lang->extension->structureAction = 'Struktur';
$lang->extension->installed       = 'Installiert';
$lang->extension->deactivated   = 'Deaktiviert';
$lang->extension->available     = 'Heruntergeladen';

$lang->extension->name        = 'Erweiterungsname';
$lang->extension->code        = 'Code';
$lang->extension->desc        = 'Beschreiben';
$lang->extension->type        = 'Typ';
$lang->extension->dirs        = 'Verzeichnisse';
$lang->extension->files       = 'Dateien';
$lang->extension->status      = 'Status';
$lang->extension->version     = 'Version';
$lang->extension->latest      = '<small>Letzte:<strong><a href="%s" target="_blank" class="extension">%s</a></strong>，benötigt ZenTao <a href="http://api.zentao.net/goto.php?item=latest" target="_blank"><strong>%s</strong></small>';
$lang->extension->author      = 'Author';
$lang->extension->license     = 'Lizenz';
$lang->extension->site        = 'Website';
$lang->extension->downloads   = 'Downloads';
$lang->extension->compatible  = 'Kompatibilität';
$lang->extension->grade       = 'Wertung';
$lang->extension->depends     = 'Benötigt';
$lang->extension->expireDate  = 'Verfällt';
$lang->extension->zentaoCompatible  = 'Kompatible Version';
$lang->extension->installedTime     = 'Installationszeit';

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
$lang->extension->noticeOkFile             = '<h5>Aus Sicherheitsgründen muss Ihr Adminkonto bestätigt werden.</h5>
    <h5>Bitte melden Sie sich an und erstellen Sie %s.</h5>
    <p>Hinweis</p>
    <ol>
    <li>1. Die Datei die Sie erstellen wird leer sein.</li>
    <li>2. Wenn die Datei bereits existiert, löschen Sie sie und erstellen Sie eine neue Datei.</li>
    </ol>'; 

$lang->extension->upgradeExt     = 'Upgrade';
$lang->extension->installExt     = 'Installieren';
$lang->extension->upgradeVersion = '(Upgrade %s zu %s.)';

$lang->extension->waring = 'Warnung!';

$lang->extension->errorOccurs                  = 'Error:';
$lang->extension->errorGetModules              = 'Die Kategorie konnte nicht von www.zentao.net ermittelt werden. Es könnte eine Netzwerkfehler vorliegen. Bitte prüfen Sie die Verbindung und aktualisieren Sie die Seite.';
$lang->extension->errorGetExtensions           = 'Die Erweiterung konnte nicht von www.zentao.net geladen werden. Es könnte eine Netzwerkfehler vorliegen. Bitte besuchen Sie <a href="http://www.zentao.net/extension/" target="_blank" class="alert-link">www.zentao.net</a> und laden Sie die Erweiterung. Anschließend laden Sie die Erweiterung hoch und installieren sie.';
$lang->extension->errorDownloadPathNotFound    = 'Downloadpfad der Erweiterung <strong>%s</strong> nicht gefunden.<br /> Bitte führen Sie <strong>mkdir -p %s</strong> aus.';
$lang->extension->errorDownloadPathNotWritable = 'Downloadpfad der Erweiterung <strong>%s</strong>ist nicht beschreibbar. <br />Bitte führen Sie <strong>sudo chmod 777 %s</strong> aus.';
$lang->extension->errorPackageFileExists       = '<strong>%s</strong> existiert bereits im Downloadpfad.<h5> Bitte %s es erneut, <a href="%s" class="alert-link">Hier klicken</a></h5>';
$lang->extension->errorDownloadFailed          = 'Download fehlgeschlagen. Bitte versuchen Sie es erneut. Sollte der Fehler weiterhinbestehen, Laden Sie die Datei erneut herunter und versuchen Sie es nochmals.';
$lang->extension->errorMd5Checking             = 'Korrupte Datei. Laden Sie die Datei erneut herunter und versuchen Sie es nochmals.';
$lang->extension->errorCheckIncompatible       = 'Inkompatibel mit dieser Version. Es sollte nicht nach %s genutzt werden.<h5>Sie können die Installation <a href="%s" class="btn btn-sm">force%s</a> oder <a href="#" onclick=parent.location.href="%s" class="btn btn-sm">abbrechen</a></h5>';
$lang->extension->errorFileConflicted          = '<br />%s <h5> hat einen Konflikt mit anderen. Wählen Sie <a href="%s" class="btn btn-sm">Überschreiben</a> oder <a href="#" onclick=parent.location.href="%s" class="btn btn-sm">Abrechen</a></h5>';
$lang->extension->errorPackageNotFound         = '<strong>%s </strong> nicht gefunden. Download könnte fehlgeschlagen sein. Bitte laden Sie die Datei nochmals.';
$lang->extension->errorTargetPathNotWritable   = '<strong>%s </strong> ist nicht beschreibbar.';
$lang->extension->errorTargetPathNotExists     = '<strong>%s </strong> nicht gefunden.';
$lang->extension->errorInstallDB               = 'Datenbank Befehl fehlgeschlagen. Error: %s';
$lang->extension->errorConflicts               = 'Konflikt mit “%s”!';
$lang->extension->errorDepends                 = 'Benötigte Erweiterung wurde nicht installiert oder die Versionen passen nicht:<br /><br /> %s';
$lang->extension->errorIncompatible            = 'Inkompatibel mit dieser Version von ZenTao.';
$lang->extension->errorUninstallDepends        = '“%s” benötigt für diese Erweiterung. Bitte nicht installieren.';
