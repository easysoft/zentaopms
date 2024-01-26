<?php
declare(strict_types=1);
namespace zin;

$parentID = $parentProgram->id ?? 0;
$currency = $parentID ? $parentProgram->budgetUnit : $config->project->defaultCurrency;

jsVar('page', 'create');
jsVar('longTime',            $lang->project->longTime);
jsVar('weekend',             $config->execution->weekend);
jsVar('parentBudget',        $lang->program->parentBudget);
jsVar('beginLessThanParent', $lang->program->beginLessThanParent);
jsVar('endGreatThanParent',  $lang->program->endGreatThanParent);
jsVar('ignore',              $lang->program->ignore);
jsVar('currencySymbol',      $lang->project->currencySymbol);
jsVar('budgetOverrun',       $lang->project->budgetOverrun);

unset($lang->project->endList['999']);
jsVar('endList', $lang->project->endList);

$fields = useFields('program.create');

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
    on::change('[name=longTime]')->do('$("[name=end]").zui("datePicker").render({disabled: $(target).prop("checked")});'),
    on::change('[name=begin], [name=end]', 'onDateChange')
);

render();
