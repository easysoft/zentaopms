<?php
namespace zin;

$parentID = $program->parent;
$currency = $parentID ? $parentProgram->budgetUnit : $config->project->defaultCurrency;
$aclList  = $parentID ? $lang->program->subAclList : $lang->program->aclList;
$delta    = $program->end == LONG_TIME ? 999 : (strtotime($program->end) - strtotime($program->begin)) / 3600 / 24 + 1;

jsVar('page', 'edit');
jsVar('longTime',            $lang->project->longTime);
jsVar('weekend',             $config->execution->weekend);
jsVar('parentBudget',        $lang->program->parentBudget);
jsVar('beginLessThanParent', $lang->program->beginLessThanParent);
jsVar('endGreatThanParent',  $lang->program->endGreatThanParent);
jsVar('ignore',              $lang->program->ignore);
jsVar('currencySymbol',      $lang->project->currencySymbol);
jsVar('budgetOverrun',       $lang->project->budgetOverrun);
jsVar('parentBeginDate',     zget($parentProgram, 'begin', ''));
jsVar('parentEndDate',       zget($parentProgram, 'end', ''));
jsVar('parentBudgetData',    zget($parentProgram, 'budget', ''));

$fields = useFields('program.edit');

$autoLoad = array();
$autoLoad['parent'] = 'parent,future,budget,budgetUnit,acl,whitelist';

formGridPanel
(
    set::modeSwitcher(false),
    set::title($title),
    set::fields($fields),
    set::loadUrl($loadUrl),
    set::autoLoad($autoLoad),
    on::change('[name=budget]', 'budgetOverrunTips'),
    on::change('[name=future]', 'onFutureChange'),
    on::change('[name=acl]',    'onAclChange'),
    on::change('[name=begin], [name=end]', 'onDateChange')
);

render();
