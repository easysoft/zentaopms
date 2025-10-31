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
$lang->aiapp->langData->storyReview        = 'Story-Bewertung';
$lang->aiapp->langData->storyReviewHint    = 'Story auf der aktuellen Seite bewerten';
$lang->aiapp->langData->storyReviewMessage = "Hier ist die zu bewertende Story:\n\n### Story-Titel\n\n{title}\n\n### Story-Beschreibung\n\n{spec}\n\n### Akzeptanzkriterien\n\n{verify}";
$lang->aiapp->langData->aiReview           = 'KI-Bewertung';
$lang->aiapp->langData->currentPage        = 'Aktuelle Seite';
$lang->aiapp->langData->story              = 'Story';
$lang->aiapp->langData->demand             = 'Anforderungspool-Story';
$lang->aiapp->langData->bug                = 'Fehler';
$lang->aiapp->langData->doc                = 'Dokument';
$lang->aiapp->langData->design             = 'Design';
$lang->aiapp->langData->feedback           = 'Rückmeldung';
$lang->aiapp->langData->currentDocContent  = 'Aktuelles Dokument';
$lang->aiapp->langData->globalMemoryTitle  = 'ZenTao';
$lang->aiapp->langData->zaiConfigNotValid  = 'ZAI-Konfiguration wurde noch nicht eingerichtet. Bitte wenden Sie sich an den Administrator, um <a href="{zaiConfigUrl}">ZAI zu konfigurieren</a>.<br>Falls die Konfiguration bereits abgeschlossen wurde, versuchen Sie bitte, die Seite neu zu laden.';
$lang->aiapp->langData->unauthorizedError  = 'Autorisierung fehlgeschlagen, ungültiger API-Schlüssel. Bitte wenden Sie sich an den Administrator, um <a href="{zaiConfigUrl}">ZAI zu konfigurieren</a>.<br>Falls die Konfiguration bereits abgeschlossen wurde, versuchen Sie bitte, die Seite neu zu laden.';
$lang->aiapp->langData->applyFormFormat    = 'Auf %s-Formular anwenden';
$lang->aiapp->langData->beforeChange       = 'Vor dem Ändern';
$lang->aiapp->langData->afterChange        = 'Nach dem Ändern';
$lang->aiapp->langData->changeProp         = 'Eigenschaft';
$lang->aiapp->langData->changeTitleFormat  = 'Änderung {type} {id}';
$lang->aiapp->langData->applyFormSuccess   = 'Erfolgreich auf %s-Formular angewendet';
$lang->aiapp->langData->changeExplainDesc  = 'Erklären Sie die Änderungen an den Daten in der Lösung, versuchen Sie, jede geänderte Eigenschaft zu erklären.';
$lang->aiapp->langData->promptResultTitle  = 'Lösungstitel, wenn kein geeigneter Titel angegeben werden kann';
$lang->aiapp->langData->promptExtraLimit   = 'Normalerweise muss das Werkzeug `{toolName}` nur einmal aufgerufen werden, es sei denn, der Benutzer fordert mehrere Lösungen an.';
$lang->aiapp->langData->goTesting          = 'Zur Prüfung';
$lang->aiapp->langData->notSupportPreview  = 'Voraussichtlich nicht unterstützt';
$lang->aiapp->langData->dataListSizeInfo   = 'Total %s items';
$lang->aiapp->langData->promptTestDataIntro = 'Hier ist das Beispiel {type} von {name} :';
