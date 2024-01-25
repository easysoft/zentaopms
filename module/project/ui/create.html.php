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
jsVar('+projectID', $copyProjectID);
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
    on::click('[name=name], [name=code], [name=end], [name=days], [data-name="parent"] .pick *', 'removeTips'),
    on::click('[type=submit]', 'removeAllTips'),
    on::change('[name=hasProduct]', 'changeType'),
    on::change('[name=longTime]')->do('$("[name=end]").zui("datePicker").render({disabled: $(target).prop("checked")}); $("[data-name=days]").toggleClass("hidden",$(target).prop("checked"));'),
    on::change('[name=future]', 'toggleBudget'),
    on::change('[name=begin], [name=end]', 'computeWorkDays'),
    on::change('[name^=products]', 'toggleStageBy'),
    on::change('[name=parent], [name=budget]', 'checkBudget'),
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
            span($name, set::title($name))
        );
    }
}
else
{
    $copyProjectsBox[] = div
    (
        setClass('inline-flex items-center w-full bg-lighter h-12 mt-2 mb-8'),
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
