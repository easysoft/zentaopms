<?php

/**
 * The ai module en lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
$lang->aiapp->common           = 'AI';
$lang->aiapp->squareCategories = array('collection' => 'My collection', 'discovery' => 'Discovery', 'latest' => 'Latest');
$lang->aiapp->newVersionTip    = 'The mini-program has been updated on %s. The above is the past record.';
$lang->aiapp->noMiniProgram    = 'The mini program you visited does not exist.';
$lang->aiapp->title            = 'Mini Programs';
$lang->aiapp->unpublishedTip   = 'The mini program you are using is not published.';
$lang->aiapp->noModelError     = 'No language model is configured, please contact the administrator.';
$lang->aiapp->chatNoResponse   = 'Something went wrong.';
$lang->aiapp->more             = 'More';
$lang->aiapp->collect          = 'Collect';
$lang->aiapp->deleted          = 'Deleted';
$lang->aiapp->clear            = 'Reset';
$lang->aiapp->modelCurrent     = 'Current Model';
$lang->aiapp->categoryList     = array('work' => 'Work', 'personal' => 'Personal', 'life' => 'Life', 'creative' => 'Creative', 'others' => 'Others');
$lang->aiapp->generate         = 'Generate';
$lang->aiapp->regenerate       = 'Regenerate';
$lang->aiapp->emptyNameWarning = '「%s」 cannot be empty';
$lang->aiapp->chatTip          = 'Please enter the field content on the left and try generating the results.';
$lang->aiapp->noModel          = array('The language model has not been configured yet. Please contact the administrator or go to the backend to configure <a id="to-language-model"> the language model.</a>。', 'If the relevant configuration has been completed, please try <a id="reload-current">reloading</a> the page.');
$lang->aiapp->clearContext     = 'The context content has been cleared.';
$lang->aiapp->newChatTip       = 'Please enter the fields on the left to start a new conversation.';
$lang->aiapp->disabledTip      = 'The current mini program is disabled.';
$lang->aiapp->continueasking   = 'Continue asking';

$lang->aiapp->miniProgramSquare  = 'Browse General Agent List';
$lang->aiapp->collectMiniProgram = 'Collect General Agent';
$lang->aiapp->miniProgramChat    = 'Execute General Agent';
$lang->aiapp->view               = 'View General Agent Details';
$lang->aiapp->browseConversation = 'Browse Conversation';
$lang->aiapp->manageGeneralAgent = 'Manage General Agent';
$lang->aiapp->models             = 'Browse Model List';

$lang->aiapp->id                 = 'ID';
$lang->aiapp->model              = 'Model Name';
$lang->aiapp->converse           = 'Converse';
$lang->aiapp->pageSummary        = 'Total %s items.';

$lang->aiapp->tips = new stdClass();
$lang->aiapp->tips->noData = 'No data';

$lang->aiapp->langData                     = new stdClass();
$lang->aiapp->langData->name               = 'ZenTao';
$lang->aiapp->langData->storyReview        = 'Revue d\'exigence';
$lang->aiapp->langData->storyReviewHint    = 'Réviser les exigences de la page actuelle';
$lang->aiapp->langData->storyReviewMessage = "Voici l'exigence à réviser :\n\n### Titre de l'exigence\n\n{title}\n\n### Description de l'exigence\n\n{spec}\n\n### Critères d'acceptation de l'exigence\n\n{verify}";
$lang->aiapp->langData->aiReview           = 'Revue IA';
$lang->aiapp->langData->currentPage        = 'Page actuelle';
$lang->aiapp->langData->story              = 'Exigence';
$lang->aiapp->langData->demand             = 'Exigence du pool de demandes';
$lang->aiapp->langData->bug                = 'BUG';
$lang->aiapp->langData->doc                = 'Document';
$lang->aiapp->langData->design             = 'Conception';
$lang->aiapp->langData->feedback           = 'Retour';
$lang->aiapp->langData->currentDocContent  = 'Document actuel';
$lang->aiapp->langData->globalMemoryTitle  = 'ZenTao';
$lang->aiapp->langData->zaiConfigNotValid  = 'La configuration ZAI n\'a pas encore été effectuée. Veuillez contacter l\'administrateur pour <a href="{zaiConfigUrl}">configurer ZAI</a>.<br>Si la configuration correspondante a été terminée, veuillez essayer de recharger la page.';
$lang->aiapp->langData->unauthorizedError  = 'Échec d\'autorisation, clé API invalide. Veuillez contacter l\'administrateur pour <a href="{zaiConfigUrl}">configurer ZAI</a>.<br>Si la configuration correspondante a été terminée, veuillez essayer de recharger la page.';
$lang->aiapp->langData->applyFormFormat    = 'Appliquer au formulaire %s';
$lang->aiapp->langData->beforeChange       = 'Avant le changement';
$lang->aiapp->langData->afterChange        = 'Après le changement';
$lang->aiapp->langData->changeProp         = 'Propriété';
$lang->aiapp->langData->changeTitleFormat  = 'Changement {type} {id}';
$lang->aiapp->langData->applyFormSuccess   = 'Successfully applied to %s form';
$lang->aiapp->langData->changeExplainDesc  = 'Expliquez les changements apportés aux données, essayez d\'expliquer chaque attribut modifié.';
$lang->aiapp->langData->promptResultTitle  = 'Titre de la solution, si aucun titre approprié n\'est disponible';
$lang->aiapp->langData->promptExtraLimit   = 'Normalement, l\'outil `{toolName}` ne doit être appelé qu\'une seule fois, sauf si l\'utilisateur demande plusieurs solutions.';
$lang->aiapp->langData->goTesting          = 'Aller au test';
$lang->aiapp->langData->notSupportPreview  = 'Vraisemblablement non pris en charge';
$lang->aiapp->langData->dataListSizeInfo   = 'Total %s items';
$lang->aiapp->langData->promptTestDataIntro = 'Voici l\'exemple {type} de {name} :';
$lang->aiapp->langData->searchingKLibs      = 'Recherche de bases de connaissances...';
