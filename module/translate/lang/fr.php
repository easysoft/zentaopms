<?php
/**
 * The translate module zh-cn file of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2012 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     translate
 * @version     $Id$
 * @link        https://www.zentao.pm
 */
$lang->translate->common       = 'Traduction';
$lang->translate->index        = 'Accueil';
$lang->translate->addLang      = 'Ajout Langue';
$lang->translate->module       = 'Traduire Module';
$lang->translate->review       = 'Vérifier';
$lang->translate->reviewAction = 'Vérifier Traduction';
$lang->translate->result       = 'Sauvez Résultat de vérification';
$lang->translate->batchPass    = 'Batch Pass';
$lang->translate->export       = 'Exporter Fichier Langue';
$lang->translate->setting      = 'Paramétrage';
$lang->translate->chooseModule = 'Choisir Module';

$lang->translate->name        = 'Langue';
$lang->translate->code        = 'Code';
$lang->translate->key         = 'Clé';
$lang->translate->reference   = 'Référence Langue';
$lang->translate->status      = 'Statut';
$lang->translate->refreshPage = 'Rafraichir';
$lang->translate->reason      = 'Raison';

$lang->translate->reviewTurnon = 'Vérification';
$lang->translate->reviewTurnonList['1'] = 'On';
$lang->translate->reviewTurnonList['0'] = 'Off';

$lang->translate->resultList['pass']   = 'Passe';
$lang->translate->resultList['reject'] = 'Rejet';

$lang->translate->group              = 'Vue';
$lang->translate->allTotal           = 'Total';
$lang->translate->translatedTotal    = 'Traduit';
$lang->translate->changedTotal       = 'Modifié';
$lang->translate->reviewedTotal      = 'Vérifié';
$lang->translate->translatedProgress = 'Traduction %';
$lang->translate->reviewedProgress   = 'Vérification %';

$lang->translate->builtIn  = 'Built-in Language';
$lang->translate->finished = 'Traduction terminée';
$lang->translate->progress = '%s Faits';
$lang->translate->count    = '（%s langues）';

$lang->translate->finishedLang    = 'Traduit';
$lang->translate->translatingLang = 'En cours de traduction';
$lang->translate->allItems        = 'Tous les objets ：%s';

$lang->translate->statusList['waiting']    = 'A Faire';
$lang->translate->statusList['translated'] = 'Fait';
$lang->translate->statusList['reviewed']   = 'Vérifié';
$lang->translate->statusList['rejected']   = 'Rejecté';
$lang->translate->statusList['changed']    = 'Modifié';

$lang->translate->notice = new stdclass();
$lang->translate->notice->failDirPriv  = "Vous n'avez pas les droits pour écrire dans ce répertoire. Modifiez vos privilèges. <br /><code>%s</code>";
$lang->translate->notice->failCopyFile = "Echec lors de la copie de %s vers %s. Modifiez vos privilèges.";
$lang->translate->notice->failUnique   = "L'objet avec le Code %s existe déjà.";
$lang->translate->notice->failMaxInput = "Modifiez la valeur de max_input_vars en % dans php.ini pour vous assurer que votre formulaire est soumit.";
$lang->translate->notice->failRuleCode = "『 Code 』 doit être composé de lettres, chiffres et souslignés.";
