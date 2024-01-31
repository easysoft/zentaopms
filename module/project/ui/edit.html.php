<?php
declare(strict_types=1);
/**
 * The edit view file of project module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     project
 * @link        https://www.zentao.net
 */
namespace zin;

$fields = useFields('project.edit');
$fields->autoLoad('parent', 'acl');
$fields->orders('begin,days,PM,budget', !empty($config->setCode) ? 'parent,hasProduct,name,code,begin' : 'parent,name,hasProduct,begin');
$fields->fullModeOrders('begin,days,PM,budget', !empty($config->setCode) ? 'parent,hasProduct,name,code,begin' : 'parent,name,hasProduct,begin');
$loadUrl = $this->createLink('project', 'create', "model={$model}&program={parent}");

jsVar('model', $model);
jsVar('ignore', $lang->project->ignore);
jsVar('budgetOverrun', $lang->project->budgetOverrun);
jsVar('currencySymbol', $lang->project->currencySymbol);
jsVar('parentBudget', $lang->project->parentBudget);
jsVar('budgetUnitLabel', $lang->project->tenThousandYuan);
jsVar('unLinkProductTip', $lang->project->unLinkProductTip);
jsVar('weekend', $config->execution->weekend);
jsVar('allProducts', $allProducts);
jsVar('branchGroups', $branchGroups);
jsVar('+projectID', 0);
jsVar('currentProject', $project->id);
jsVar('from', $from);
jsVar('programID', $programID);
jsVar('LONG_TIME', LONG_TIME);

unset($lang->project->endList['999']);
jsVar('endList', $lang->project->endList);

$labelClass = $config->project->labelClass[$model];

$disableModel = true;

formGridPanel
(
    to::titleSuffix
    (
            $disableModel ? btn
            (
                set::id('project-model'),
                setClass("{$labelClass} h-5 px-2"),
                zget($lang->project->modelList, $model, '')
            ) : dropdown
            (
                btn
                (
                    set::id('project-model'),
                    setClass("$labelClass h-5 px-2"),
                    zget($lang->project->modelList, $model, '')
                ),
                set::placement('bottom'),
                set::menu(array('style' => array('color' => 'var(--color-fore)'))),
                set::items($projectModelItems)
            )
    ),
    on::change('[name=hasProduct]', 'changeType'),
    on::change('[name=longTime]')->do('const $endPicker = $("[name=end]").zui("datePicker"); $endPicker.render({disabled: $(target).prop("checked")}); if($(target).prop("checked")){ $endPicker.$.setValue(""); $("[name=days]").attr("disabled", "disabled");} else{ $("[name=days]").removeAttr("disabled");}'),
    on::change('[name=future]', 'toggleBudget'),
    on::change('[name=begin], [name=end]', 'computeWorkDays'),
    on::change('[name=parent], [name=budget]', 'checkBudget'),
    on::change('[name^=products]', 'toggleStageBy'),
    set::modeSwitcher(false),
    set::defaultMode('full'),
    set::title($lang->project->edit),
    set::fields($fields)
);

render();

