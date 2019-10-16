<?php
/**
 * The admin module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: en.php 4460 2013-02-26 02:28:02Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.pm
 */
$lang->admin->common        = 'Admin';
$lang->admin->index         = 'Admin Home';
$lang->admin->checkDB       = 'Check Database';
$lang->admin->sso           = 'Zdoo';
$lang->admin->ssoAction     = 'Link Zdoo';
$lang->admin->safeIndex     = 'Sécurité';
$lang->admin->checkWeak     = 'Contrôle niveau de sécurité des mots de passe';
$lang->admin->certifyMobile = 'Vérifier votre smartphone';
$lang->admin->certifyEmail  = 'Vérifier votre Email';
$lang->admin->ztCompany     = 'Vérifier votre entreprise';
$lang->admin->captcha       = 'Code de vérification';
$lang->admin->getCaptcha    = 'Obtenir le Code de vérification';

$lang->admin->api     = 'API';
$lang->admin->log     = 'Log';
$lang->admin->setting = 'Paramétrage';
$lang->admin->days    = 'Durée de conservation';

$lang->admin->info = new stdclass();
$lang->admin->info->version = 'La version actuelle est %s customisée. ';
$lang->admin->info->links   = 'Vous pouvez visiter les liens ci-dessous';
$lang->admin->info->account = 'Votre identifiant client ZenTao est %s.';
$lang->admin->info->log     = 'Les logs qui dépassent la durée de conservation seront supprimés et vous devrez exécuter un cron.';

$lang->admin->notice = new stdclass();
$lang->admin->notice->register = "Note: Vous n'êtes pas enregistré sur le site officiel de ZenTao (www.zentao.pm). %s vous pourrez ensuite obtenir les dernières Mises à Jour de ZenTao et des informations.";
$lang->admin->notice->ignore   = "Ignorer";
$lang->admin->notice->int      = "『 %s 』 devrait être un entier positif.";

$lang->admin->register = new stdclass();
$lang->admin->register->common     = 'Connectez-vous avec votre nouveau compte';
$lang->admin->register->caption    = "S'enregistrer sur la communauté Zentao";
$lang->admin->register->click      = "S'enregistrer ici";
$lang->admin->register->lblAccount = '>= 3 lettres et chiffres';
$lang->admin->register->lblPasswd  = '>= 6 lettres et chiffres';
$lang->admin->register->submit     = "S'enregistrer";
$lang->admin->register->bind       = "S'associer à un compte existant";
$lang->admin->register->success    = "Vous êtes enregistré !";

$lang->admin->bind = new stdclass();
$lang->admin->bind->caption = 'Lier au compte';
$lang->admin->bind->success = "Le compte est associé !";

$lang->admin->safe = new stdclass();
$lang->admin->safe->common     = 'Politique de sécurité';
$lang->admin->safe->set        = 'Paramétrages du Mot de passe';
$lang->admin->safe->password   = 'Force du Mot de passe';
$lang->admin->safe->weak       = 'Mots de passes non sécurisés courrants';
$lang->admin->safe->reason     = 'Type';
$lang->admin->safe->checkWeak  = 'Balayage des mots de passe faibles';
$lang->admin->safe->changeWeak = 'Forcer le changement des mots de passe faibles';
$lang->admin->safe->modifyPasswordFirstLogin = 'Forcer le changement du mot de passe après la première connexion';

$lang->admin->safe->modeList[0] = "Contrôle Modéré";
$lang->admin->safe->modeList[1] = 'Contrôle Moyen';
$lang->admin->safe->modeList[2] = 'Contrôle Fort';

$lang->admin->safe->modeRuleList[1] = ' >= 6 Majuscules, minuscules et chiffres';
$lang->admin->safe->modeRuleList[2] = ' >= 10 Majuscules, minuscules, chiffres et caractères spéciaux';

$lang->admin->safe->reasonList['weak']     = 'Mots de passe faibles courants';
$lang->admin->safe->reasonList['account']  = 'Identique au compte';
$lang->admin->safe->reasonList['mobile']   = 'Identique au numéro de mobile';
$lang->admin->safe->reasonList['phone']    = 'Identique au téléphone fixe';
$lang->admin->safe->reasonList['birthday'] = 'Identique à la date de naissance';

$lang->admin->safe->modifyPasswordList[1] = 'Oui';
$lang->admin->safe->modifyPasswordList[0] = 'Non';

$lang->admin->safe->noticeMode   = "Le mot de passe sera vérifié quand un utilisateur se connectera ou qu'un utilisateur sera créé ou modifier.";
$lang->admin->safe->noticeStrong = '';
