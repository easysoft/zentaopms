<?php
/**
 * The sso module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     sso
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->sso = new stdclass();
$lang->sso->settings = 'Paramétrages';
$lang->sso->turnon   = 'Zdoo';
$lang->sso->redirect = 'Redirection automatique vers Zdoo';
$lang->sso->code     = 'Code';
$lang->sso->key      = 'Clé Secrète';
$lang->sso->addr     = 'Adresse';
$lang->sso->bind     = 'Liaison utilisateur';
$lang->sso->addrNotice = 'Example http://www.ranzhi.com/sys/sso-check.html';

$lang->sso->turnonList = array();
$lang->sso->turnonList[1] = 'On';
$lang->sso->turnonList[0] = 'Off';

$lang->sso->bindType = 'Type de lien';
$lang->sso->bindUser = 'Liaison Utilisateur';

$lang->sso->bindTypeList['bind'] = 'Se lier à un utilisateur existant';
$lang->sso->bindTypeList['add']  = 'Ajouter User';

$lang->sso->help = new stdclass();
$lang->sso->help->addr = 'Zdoo address is required. If use PATH_INFO, it is http://YOUR ZDOO ADDRESS/sys/sso-check.html If GET, it is http://YOUR ZDOO ADDRESS/sys/index.php?m=sso&f=check';
$lang->sso->help->code = 'Code must be the same as set in Zdoo';
$lang->sso->help->key  = 'Secret Key must be the same as set in Zdoo';

$lang->sso->deny           = 'Access Limited';
$lang->sso->bindNotice     = "Un utilisateur qui a été récemment ajouté n'a pas de permission. Vous devez demander à l'administrateur ZenTao d'accorder des droits à cet utilisateur.";
$lang->sso->bindNoPassword = 'Password ne devrait pas être vide.';
$lang->sso->bindNoUser     = 'Password erroné/Utilisateur introuvable !';
$lang->sso->bindHasAccount = "Ce nom d'utilisateur existe déjà. Modifiez votre code utilisateur ou liez-vous à lui.";

$lang->sso->homeURL             = 'Feishu Page Config URL：';
$lang->sso->redirectURL         = 'Feishu Redirect URL：';
$lang->sso->feishuConfigEmpty   = 'Go to [Admin]-[Notification]-[Webhook] to set ( Feishu Work Notification)';
$lang->sso->feishuResponseEmpty = 'Request response is empty';
$lang->sso->unbound             = 'Current Feishu user is not bound in ZenTao-Wwebhook.';
