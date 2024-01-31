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

unset($lang->project->endList['999']);
jsVar('endList', $lang->project->endList);

$fields = useFields('program.edit');

$autoLoad = array();
$autoLoad['parent'] = 'parent,future,budget,budgetUnit,acl,whitelist';

$handleLongTimeChange = jsCallback()->do(<<<'JS'
    const endPicker = $element.find('[name=end]').zui('datePicker');
    const longTime  = $element.find('[name=longTime]').prop('checked');
    endPicker.render({disabled: longTime});
    if(longTime) endPicker.$.setValue('');
JS);

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
    on::change('[name=longTime]', $handleLongTimeChange),
    on::change('[name=begin], [name=end]', 'onDateChange'),
    on::inited()->triggerEvent('$element.find("[name=longTime]")', 'change')
);

render();
