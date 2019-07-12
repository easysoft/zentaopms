<?php
/**
 * The admin module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: en.php 4460 2013-02-26 02:28:02Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->admin->common        = 'Admin';
$lang->admin->index         = 'Admin Home';
$lang->admin->checkDB       = 'Datenbank prüfen';
$lang->admin->sso           = 'SSO';
$lang->admin->ssoAction     = 'Link Zdoo';
$lang->admin->safeIndex     = 'Sicherheit';
$lang->admin->checkWeak     = 'Schwache Passwörter prüfen';
$lang->admin->certifyMobile = 'Prüfen Sie die Mobilnummer';
$lang->admin->certifyEmail  = 'Prüfen Sie die Emailadresse';
$lang->admin->ztCompany     = 'Prüfen Sie das Unternehmen';
$lang->admin->captcha       = 'Bestätigungscode';
$lang->admin->getCaptcha    = 'Bestätigungscode anfordern';

$lang->admin->api     = 'API';
$lang->admin->log     = 'Log';
$lang->admin->setting = 'Einstellungen';
$lang->admin->days    = 'Gültige Tage';

$lang->admin->info = new stdclass();
$lang->admin->info->version = 'Aktuelle Version ist %s. ';
$lang->admin->info->links   = 'You can visit links below';
$lang->admin->info->account = 'Ihr ZenTao Konto ist %s.';
$lang->admin->info->log     = 'Logs die über die Gültigen Tage hinausgehen werden gelöscht. Aufgabenplanung muss laufen (cron).';

$lang->admin->notice = new stdclass();
$lang->admin->notice->register = "Hinweiß: Sie haben sich nicht bei ZenTao (www.zentao.pm) registriert. %s um die aktuellen Updates und News zu erhalten.";
$lang->admin->notice->ignore   = "Ignorieren";
$lang->admin->notice->int      = "『%s』sollte eine positive Zahl sein.";

$lang->admin->register = new stdclass();
$lang->admin->register->common     = 'Verbinde neues Konto';
$lang->admin->register->caption    = 'In der Zentao Community registieren';
$lang->admin->register->click      = 'Bitte registieren Sie sich hier';
$lang->admin->register->lblAccount = 'mindestens 3 Zeichen bitte; mit Buchsten und Ziffern.';
$lang->admin->register->lblPasswd  = 'mindestens 6 Zeichen bitte; mit Buchsten und Ziffern.';
$lang->admin->register->submit     = 'Registieren';
$lang->admin->register->bind       = "Verbinde bestehendes Konto";
$lang->admin->register->success    = "Sie haben sich erfolgreich registriert!";

$lang->admin->bind = new stdclass();
$lang->admin->bind->caption = 'Konto verknüpfen';
$lang->admin->bind->success = "Konto wurde verknüpft!";

$lang->admin->safe = new stdclass();
$lang->admin->safe->common     = 'Sicherheits-Regeln';
$lang->admin->safe->set        = 'PasswortStärke';
$lang->admin->safe->password   = 'Passwort Stärke';
$lang->admin->safe->weak       = 'Schwache Passwörter';
$lang->admin->safe->reason     = 'Typ';
$lang->admin->safe->checkWeak  = 'Schwache Passwörter prüfen';
$lang->admin->safe->changeWeak = 'Ihr Passwort ist schwach. Bitte ändern.';
$lang->admin->safe->modifyPasswordFirstLogin = 'Passwort nach der ersten Anmeldung ändern';

$lang->admin->safe->modeList[0] = 'N/A';
$lang->admin->safe->modeList[1] = 'Medium';
$lang->admin->safe->modeList[2] = 'Stark';

$lang->admin->safe->modeRuleList[1] = 'Beinhaltet Groß und Kleinbuchstaben sowie Ziffern. Länge >= 6';
$lang->admin->safe->modeRuleList[2] = 'Beinhaltet Groß und Kleinbuchstaben, Ziffern sowie Sonderzeichen.  Länge >= 10.';

$lang->admin->safe->reasonList['weak']     = 'Bekannte Schwache Passwörter';
$lang->admin->safe->reasonList['account']  = 'Entspricht ihrem Konto';
$lang->admin->safe->reasonList['mobile']   = 'Entspricht Ihrer Mobilnummer';
$lang->admin->safe->reasonList['phone']    = 'Entspricht Ihrer Telefonnummer';
$lang->admin->safe->reasonList['birthday'] = 'Entspricht Ihrem Geburtstag';

$lang->admin->safe->modifyPasswordList[1] = 'Ja';
$lang->admin->safe->modifyPasswordList[0] = 'Nein';

$lang->admin->safe->noticeMode   = 'Passwort prüfen bei Anmeldung, Anlage und Bearbeitung von Benutzern..';
$lang->admin->safe->noticeStrong = '';
