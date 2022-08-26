<?php
/**
 * The admin module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: en.php 4460 2013-02-26 02:28:02Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->admin->index           = 'Admin Home';
$lang->admin->checkDB         = 'Check Database';
$lang->admin->sso             = 'Zdoo';
$lang->admin->ssoAction       = 'Link Zdoo';
$lang->admin->safeIndex       = 'Sécurité';
$lang->admin->checkWeak       = 'Contrôle niveau de sécurité des mots de passe';
$lang->admin->certifyMobile   = 'Vérifier votre smartphone';
$lang->admin->certifyEmail    = 'Vérifier votre Email';
$lang->admin->ztCompany       = 'Vérifier votre entreprise';
$lang->admin->captcha         = 'Code de vérification';
$lang->admin->getCaptcha      = 'Obtenir le Code de vérification';
$lang->admin->register        = 'Register';
$lang->admin->resetPWDSetting = 'Reset password Setting';
$lang->admin->tableEngine     = 'Table Engine';

$lang->admin->api            = 'API';
$lang->admin->log            = 'Log';
$lang->admin->setting        = 'Paramétrage';
$lang->admin->days           = 'Durée de conservation';
$lang->admin->resetPWDByMail = 'Reset the password via the email';

$lang->admin->changeEngine   = "Change to InnoDB";
$lang->admin->changingTable  = 'Replacing data table %s engine...';
$lang->admin->changeSuccess  = 'The data table %s engine has been changed to InnoDB.';
$lang->admin->changeFail     = "Failed to replace table %s engine. Reason: <span class='text-red'>%s</span>。";
$lang->admin->errorInnodb    = 'Your MySQL does not support InnoDB data table engine.';
$lang->admin->changeFinished = "Database engine replacement completed.";
$lang->admin->engineInfo     = "The <strong>%s</strong> table engine is <strong>%s</strong>.";
$lang->admin->engineSummary['hasMyISAM'] = "There are %s tables that are not InnoDB engines";
$lang->admin->engineSummary['allInnoDB'] = "All tables are InnoDB engines";

$lang->admin->info = new stdclass();
$lang->admin->info->version = 'La version actuelle est %s customisée. ';
$lang->admin->info->links   = 'Vous pouvez visiter les liens ci-dessous';
$lang->admin->info->account = 'Votre identifiant client ZenTao est %s.';
$lang->admin->info->log     = 'Les logs qui dépassent la durée de conservation seront supprimés et vous devrez exécuter un cron.';

$lang->admin->notice = new stdclass();
$lang->admin->notice->register = "Note: Vous n'êtes pas enregistré sur le site officiel de ZenTao (www.zentao.pm). %s vous pourrez ensuite obtenir les dernières Mises à Jour de ZenTao et des informations.";
$lang->admin->notice->ignore   = "Ignorer";
$lang->admin->notice->int      = "『 %s 』 devrait être un entier positif.";

$lang->admin->registerNotice = new stdclass();
$lang->admin->registerNotice->common     = 'Connectez-vous avec votre nouveau compte';
$lang->admin->registerNotice->caption    = "S'enregistrer sur la communauté Zentao";
$lang->admin->registerNotice->click      = "S'enregistrer ici";
$lang->admin->registerNotice->lblAccount = '>= 3 lettres et chiffres';
$lang->admin->registerNotice->lblPasswd  = '>= 6 lettres et chiffres';
$lang->admin->registerNotice->submit     = "S'enregistrer";
$lang->admin->registerNotice->bind       = "S'associer à un compte existant";
$lang->admin->registerNotice->success    = "Vous êtes enregistré !";

$lang->admin->bind = new stdclass();
$lang->admin->bind->caption = 'Lier au compte';
$lang->admin->bind->success = "Le compte est associé !";

$lang->admin->safe = new stdclass();
$lang->admin->safe->common                   = 'Politique de sécurité';
$lang->admin->safe->set                      = 'Paramétrages du Mot de passe';
$lang->admin->safe->password                 = 'Force du Mot de passe';
$lang->admin->safe->weak                     = 'Mots de passes non sécurisés courrants';
$lang->admin->safe->reason                   = 'Type';
$lang->admin->safe->checkWeak                = 'Balayage des mots de passe faibles';
$lang->admin->safe->changeWeak               = 'Forcer le changement des mots de passe faibles';
$lang->admin->safe->loginCaptcha             = 'Login using CAPTCHA';
$lang->admin->safe->modifyPasswordFirstLogin = 'Forcer le changement du mot de passe après la première connexion';
$lang->admin->safe->passwordStrengthWeak     = 'Le mot de passe est moins fort que les paramètres du système.';

$lang->admin->safe->modeList[0] = "Contrôle Modéré";
$lang->admin->safe->modeList[1] = 'Contrôle Moyen';
$lang->admin->safe->modeList[2] = 'Contrôle Fort';

$lang->admin->safe->modeRuleList[1] = ' >= 6 Majuscules, minuscules et chiffres.';
$lang->admin->safe->modeRuleList[2] = ' >= 10 Majuscules, minuscules, chiffres et caractères spéciaux.';

$lang->admin->safe->reasonList['weak']     = 'Mots de passe faibles courants';
$lang->admin->safe->reasonList['account']  = 'Identique au compte';
$lang->admin->safe->reasonList['mobile']   = 'Identique au numéro de mobile';
$lang->admin->safe->reasonList['phone']    = 'Identique au téléphone fixe';
$lang->admin->safe->reasonList['birthday'] = 'Identique à la date de naissance';

$lang->admin->safe->modifyPasswordList[1] = 'Oui';
$lang->admin->safe->modifyPasswordList[0] = 'Non';

$lang->admin->safe->loginCaptchaList[1] = 'Yes';
$lang->admin->safe->loginCaptchaList[0] = 'Non';

$lang->admin->safe->resetPWDList[1] = 'ON';
$lang->admin->safe->resetPWDList[0] = 'Off';

$lang->admin->safe->noticeMode     = "Le mot de passe sera vérifié lors de la création et de la modification des coordonnées de l'utilisateur, et du changement de mot de passe.";
$lang->admin->safe->noticeWeakMode = "Le mot de passe sera vérifié lors de la connexion au système, de la création et de la modification des coordonnées de l'utilisateur, et du changement de mot de passe.";
$lang->admin->safe->noticeStrong   = "Le mot de passe est d'autant plus sécurisé qu'il est long, qu'il contient plus de lettres, de chiffres ou de caractères spéciaux, et que les lettres du mot de passe sont peu répétitives !";
