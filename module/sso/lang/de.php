<?php
/**
 * The sso module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     sso
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->sso = new stdclass();
$lang->sso->settings = 'Einstellungen';
$lang->sso->turnon   = 'An';
$lang->sso->redirect = 'Umleiten';
$lang->sso->code     = 'Alias';
$lang->sso->key      = 'Schlüssel';
$lang->sso->addr     = 'Adresse';
$lang->sso->bind     = 'Benutzerbindung';
$lang->sso->addrNotice = 'Beispiel http://www.ranzhi.com/sys/sso-check.html';

$lang->sso->turnonList = array();
$lang->sso->turnonList[1] = 'An';
$lang->sso->turnonList[0] = 'Aus';

$lang->sso->bindType = 'Typenbindung';
$lang->sso->bindUser = 'Benutzerbindung';

$lang->sso->bindTypeList['bind'] = 'Existierenden Benutzer binden';
$lang->sso->bindTypeList['add']  = 'Benutzer hinzufügen';

$lang->sso->help = <<<EOD
<p>1. Schnittstellenadresse wird benötigt. Bei Nutzung von PATH_INFO, ist es http://YOUR RANGER ADDRESS/sys/sso-check.html Bei GET ist es http://YOUR RANGER ADDRESS/sys/index.php?m=sso&f=check</p>
<p>2. Code und Schlüssel müssen dem Bereich entsprechen.</p>
EOD;
$lang->sso->bindNotice     = 'Nur hinzugefügte Benutzer haben keine Rechte. Der ZenTao Admin muss die REchte dem Benutzer zuweisen.';
$lang->sso->bindNoPassword = 'Das Passwort darf nicht leer sein.';
$lang->sso->bindNoUser     = 'Passwort oder Benutzer ist inkorrekt!';
$lang->sso->bindHasAccount = 'Der Benutzer existiert bereits. Benutzer ändern oder verbinden.';
