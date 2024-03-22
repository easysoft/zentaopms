<?php
declare(strict_types=1);
/**
 * The create view file of project module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     project
 * @link        https://www.zentao.net
 */
namespace zin;

$fields = useFields('project.create');
$fields->autoLoad('parent', 'acl');
$loadUrl = $this->createLink('project', 'create', "model={$model}&program={parent}");

jsVar('model', $model);
jsVar('ignore', $lang->project->ignore);
jsVar('budgetOverrun', $lang->project->budgetOverrun);
jsVar('currencySymbol', $lang->project->currencySymbol);
jsVar('parentBudget', $lang->project->parentBudget);
jsVar('budgetUnitLabel', $lang->project->tenThousandYuan);
jsVar('+projectID', 0);
jsVar('LONG_TIME', LONG_TIME);
jsVar('weekend', $config->execution->weekend);
jsVar('beginLessThanParent', $lang->project->beginLessThanParent);
jsVar('endGreatThanParent', $lang->project->endGreatThanParent);

unset($lang->project->endList['999']);
jsVar('endList', $lang->project->endList);

$modelMenuItems = array();
foreach($lang->project->modelList as $key => $text)
{
    if(empty($key)) continue;
    $modelMenuItems[] = array('text' => $text, 'selected' => $key == $model, 'url' => createLink('project', 'create', "model=$key"));
}

$modeDropdown = dropdown
(
    btn
    (
        zget($lang->project->modelList, $model),
        setClass('gray-300-outline size-sm rounded-full ml-2')
    ),
    set::arrow(true),
    set::placement('bottom'),
    set::items($modelMenuItems)
);

$handleLongTimeChange = jsCallback()->do(<<<'JS'
    const endPicker  = $element.find('[name=end]').closest('[data-zui-datepicker]').zui('datePicker');
    const isLongTime = $element.find('[name=longTime]').prop('checked');
    endPicker.render({disabled: isLongTime});
    if(isLongTime) endPicker.$.setValue('');
    $element.find('[name=days]').attr('disabled', isLongTime ? 'disabled' : null);
JS);

$toggleLongTime = jsCallback()->do(<<<'JS'
    const isMultiple = $('#form-project-create [name=multiple]').prop('checked');
    if(!isMultiple) $('#form-project-create [name=longTime]').prop('checked', false);
    $('#form-project-create [name=longTime]').closest('.checkbox-primary').toggleClass('hidden', !isMultiple);

    const $endPicker = $('#form-project-create [name=end]').closest('[data-zui-datepicker]').zui('datePicker');
    $endPicker.render({disabled: false});
    $('#form-project-create [name=days]').removeAttr('disabled');
JS);

formGridPanel
(
    to::titleSuffix($modeDropdown),
    to::headingActions
    (
        btn
        (
            set::icon('copy'),
            setClass('primary-ghost size-md'),
            toggle::modal(array('target' => '#copyProjectModal', 'destoryOnHide' => true)),
            $lang->project->copy
        ),
        divider(setClass('h-4 mr-4 ml-2 self-center'))
    ),
    on::click('[name=name], [name=code], [data-name=begin] .has-warning *, [name=days], [data-name="parent"] .pick *', 'removeTips'),
    on::click('[type=submit]', 'removeAllTips'),
    on::click('[name=multiple]', $toggleLongTime),
    on::change('[name=parent]')->toggleClass('.productsBox .linkProduct .form-label', 'required', "\$(target).val() > 0"),
    on::change('[name=hasProduct]', 'changeType'),
    on::change('[name=longTime]', $handleLongTimeChange),
    on::change('[name=future]', 'toggleBudget'),
    on::change('[name=begin], [name=end]', 'computeWorkDays'),
    on::change('[name^=products]', 'toggleStageBy'),
    on::change('[name=parent], [name=budget]', 'checkBudget'),
    on::init()->do('setTimeout(() => $element.find("[name=longTime]").trigger("change"), 500)'),
    set::title($lang->project->create),
    set::fullModeOrders(array('begin,days,PM,budget', !empty($config->setCode) ? 'parent,hasProduct,name,code,begin' : 'parent,name,hasProduct,begin')),
    set::fields($fields),
    set::loadUrl($loadUrl)
);

$copyProjectsBox = array();
if(!empty($copyProjects))
{
    foreach($copyProjects as $id => $name)
    {
        $copyProjectsBox[] = btn
        (
            setClass('project-block justify-start'),
            setClass($copyProjectID == $id ? 'primary-outline' : ''),
            set('data-id', $id),
            set('data-pinyin', zget($copyPinyinList, $name, '')),
            icon(setClass('text-gray'), $lang->icons['project']),
            span($name, set::title($name), setClass('text-left'))
        );
    }
}
else
{
    $copyProjectsBox[] = div
    (
        setClass('inline-flex items-center w-full bg-gray-100 h-12 mt-2 mb-8'),
        icon('exclamation-sign icon-2x pl-2 text-warning'),
        span
        (
            set::className('font-bold ml-2'),
            $lang->project->copyNoProject
        )
    );
}

modal
(
    set::id('copyProjectModal'),
    to::header
    (
        span
        (
            h4
            (
                set::className('copy-title'),
                $lang->project->copyTitle
            )
        ),
        input
        (
            set::name('projectName'),
            set::placeholder($lang->project->searchByName)
        )
    ),
    div
    (
        set::id('copyProjects'),
        setClass('flex items-center flex-wrap'),
        $copyProjectsBox
    )
);

render();
