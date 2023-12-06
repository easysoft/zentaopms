<?php
/**
 * The sso module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     sso
 * @version     $Id$
 * @link        https://www.zentao.net
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

$lang->sso->help = new stdclass();
$lang->sso->help->addr = 'Zdoo address is required. If use PATH_INFO, it is http://YOUR ZDOO ADDRESS/sys/sso-check.html If GET, it is http://YOUR ZDOO ADDRESS/sys/index.php?m=sso&f=check';
$lang->sso->help->code = 'Code must be the same as set in Zdoo';
$lang->sso->help->key  = 'Secret Key must be the same as set in Zdoo';

$lang->sso->deny           = 'Access Limited';
$lang->sso->bindNotice     = 'Nur hinzugefügte Benutzer haben keine Rechte. Der ZenTao Admin muss die REchte dem Benutzer zuweisen.';
$lang->sso->bindNoPassword = 'Das Passwort darf nicht leer sein.';
$lang->sso->bindNoUser     = 'Passwort oder Benutzer ist inkorrekt!';
$lang->sso->bindHasAccount = 'Der Benutzer existiert bereits. Benutzer ändern oder verbinden.';

$lang->sso->homeURL             = 'Feishu Page Config URL：';
$lang->sso->redirectURL         = 'Feishu Redirect URL：';
$lang->sso->feishuConfigEmpty   = 'Go to [Admin]-[Notification]-[Webhook] to set ( Feishu Work Notification)';
$lang->sso->feishuResponseEmpty = 'Request response is empty';
$lang->sso->unbound             = 'Current Feishu user is not bound in ZenTao-Wwebhook.';
