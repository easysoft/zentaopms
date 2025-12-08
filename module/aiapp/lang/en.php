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
$lang->aiapp->langData->storyReview        = 'Story Review';
$lang->aiapp->langData->storyReviewHint    = 'Review the story on the current page';
$lang->aiapp->langData->storyReviewMessage = "Here is the story to be reviewed:\n\n### Story Title\n\n{title}\n\n### Story Description\n\n{spec}\n\n### Acceptance Criteria\n\n{verify}";
$lang->aiapp->langData->aiReview           = 'AI Review';
$lang->aiapp->langData->currentPage        = 'Current Page';
$lang->aiapp->langData->story              = 'Story';
$lang->aiapp->langData->demand             = 'Demand Pool Story';
$lang->aiapp->langData->bug                = 'Bug';
$lang->aiapp->langData->doc                = 'Document';
$lang->aiapp->langData->design             = 'Design';
$lang->aiapp->langData->feedback           = 'Feedback';
$lang->aiapp->langData->currentDocContent  = 'Current Document';
$lang->aiapp->langData->globalMemoryTitle  = 'ZenTao';
$lang->aiapp->langData->zaiConfigNotValid  = 'ZAI configuration has not been set up yet. Please contact the administrator to <a href="{zaiConfigUrl}">configure ZAI</a>.<br>If the configuration has been completed, please try reloading the page.';
$lang->aiapp->langData->unauthorizedError  = 'Authorization failed, invalid API key. Please contact the administrator to <a href="{zaiConfigUrl}">configure ZAI</a>.<br>If the configuration has been completed, please try reloading the page.';
$lang->aiapp->langData->applyFormFormat    = 'Apply to %s form';
$lang->aiapp->langData->beforeChange       = 'Before Change';
$lang->aiapp->langData->afterChange        = 'After Change';
$lang->aiapp->langData->changeProp         = 'Property';
$lang->aiapp->langData->changeTitleFormat  = 'Change {type} {id}';
$lang->aiapp->langData->applyFormSuccess   = 'Successfully applied to %s form';
$lang->aiapp->langData->changeExplainDesc  = 'Explain the changes of the data in the solution, try to explain each changed attribute.';
$lang->aiapp->langData->promptResultTitle  = 'Solution title, if no suitable title can be omitted';
$lang->aiapp->langData->promptExtraLimit   = 'Usually tool `{toolName}` only needs to be called once, unless the user requires multiple solutions.';
$lang->aiapp->langData->goTesting          = 'Go Testing';
$lang->aiapp->langData->notSupportPreview  = 'Not support preview this content';
$lang->aiapp->langData->dataListSizeInfo   = 'Total %s items';
$lang->aiapp->langData->promptTestDataIntro = 'Here is the example {type} of {name}:';
$lang->aiapp->langData->searchingKLibs       = 'Searching knowledge libraries...';
