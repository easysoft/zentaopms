<?php
declare(strict_types=1);
namespace zin;

$parentID = $parentProgram->id ?? 0;
$currency = $parentID ? $parentProgram->budgetUnit : $config->project->defaultCurrency;

jsVar('longTime', $lang->project->longTime);
jsVar('weekend', $config->execution->weekend);
jsVar('page', 'create');
jsVar('parentBudget', $lang->program->parentBudget);
jsVar('beginLessThanParent', $lang->program->beginLessThanParent);
jsVar('endGreatThanParent', $lang->program->endGreatThanParent);
jsVar('ignore', $lang->program->ignore);
jsVar('currencySymbol', $lang->project->currencySymbol);
jsVar('budgetOverrun', $lang->project->budgetOverrun);

$fields = useFields('program.create');

$autoLoad = array();

formGridPanel
(
    set::title($title),
    set::fields($fields),
    set::loadUrl($loadUrl),
    set::autoLoad($autoLoad)
);

render();
