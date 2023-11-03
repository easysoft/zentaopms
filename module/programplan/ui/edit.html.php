<?php
declare(strict_types=1);
/**
 * Edit view of program plan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yue Liu <liuyue@easycorp.ltd>
 * @package     programPlan
 * @link        https://www.zentao.net
 */

namespace zin;
$typeList = $project->model == 'ipd' ? $lang->stage->ipdTypeList : $lang->stage->typeList;
jsVar('plan',           $plan);
jsVar('stageTypeList',  $typeList);
jsVar('changeAttrLang', $lang->programplan->confirmChangeAttr);
jsVar('isTopStage',     $isTopStage);
jsVar('isLeafStage',    $isLeafStage);

modalHeader
(
    set::title($this->lang->programplan->edit),
    set::entityText($plan->name),
    set::entityID($plan->id)
);

formPanel
(
    set::submitBtnText($lang->save),
    formGroup
    (
        set::label($lang->programplan->parent),
        set::width('1/2'),
        picker
        (
            setID('parent'),
            set::name('parent'),
            set::items($parentStageList),
            set::value($plan->parent),
            set::required(true),
            on::change('changeParentStage')
        )
    ),
    formGroup
    (
        set::label($lang->programplan->name),
        set::width('1/2'),
        set::required(true),
        input(set::name('name'), set::value($plan->name))
    ),
    isset($config->setCode) && $config->setCode == 1 ?
    formGroup
    (
        set::label($lang->execution->code),
        set::width('1/2'),
        set::required(true),
        input(set::name('code'), set::value($plan->code))
    ): null,
    formGroup
    (
        set::label($lang->programplan->PM),
        set::width('1/2'),
        picker
        (
            set::name('PM'),
            set::items($PMUsers),
            set::value($plan->PM),
            set::required(true)
        )
    ),
    isset($config->setPercent) && $config->setPercent == 1 ?
    formGroup
    (
        set::label($lang->programplan->percent),
        set::width('1/2'),
        inputControl
        (
            input(set::name('percent'), set::value($plan->percent)),
            to::suffix('%'),
            set::suffixWidth('lg')
        )
    ): null,
    formRow
    (
        formGroup
        (
            set::label($lang->programplan->attribute),
            set::width('1/2'),
            div
            (
                setID('attributeType'),
                setClass('flex self-center w-full'),
                $enableOptionalAttr ?
                select(set(array(
                    'id'       => 'attribute',
                    'name'     => 'attribute',
                    'items'    => $typeList,
                    'value'    => $plan->attribute,
                    'required' => true
                ))) : zget($typeList, $plan->attribute)
            )
        ),
        formGroup
        (
            btn(set(array(
                'icon'           => 'help',
                'data-toggle'    => 'tooltip',
                'data-placement' => 'right',
                'href'           => 'helpTip',
                'class'          => 'ghost h-8 tooltip-btn'
            ))),
            div
            (
                $lang->execution->typeTip,
                setClass('w-1/3'),
                set(array(
                    'id'    => 'helpTip',
                    'class' => 'tooltip darker'
                ))
            )
        )
    ),
    formGroup
    (
        set::label($lang->programplan->planDateRange),
        set::width('2/3'),
        set::required(true),
        inputGroup
        (
            datepicker(set::name('begin'), set::value($plan->begin)),
            $lang->project->to,
            datepicker(set::name('end'), set::value($plan->end))
        )
    ),
    formGroup
    (
        set::label($lang->programplan->realDateRange),
        set::width('2/3'),
        inputGroup
        (
            datepicker(set::name('realBegan'), set::value($plan->realBegan)),
            $lang->project->to,
            datepicker(set::name('realEnd'), set::value($plan->realEnd))
        )
    ),
    formGroup
    (
        set::label($lang->project->acl),
        set::width('1/2'),
        select
        (
            set::name('acl'),
            set::items($lang->execution->aclList),
            set::value($plan->acl),
            set::disabled($plan->grade == 2 ? 'disabled' : ''),
            set::required(true)
        )
    ),
    $plan->setMilestone ?
    formGroup
    (
        set::label($lang->programplan->milestone),
        set::width('1/2'),
        radioList(
            set::name('milestone'),
            set::items($lang->programplan->milestoneList),
            set::value($plan->milestone),
            set::inline(true)
        )
    ) : input(set::type('hidden'), set::value($plan->milestone)),
    isset($this->config->qcVersion) ?
    formGroup
    (
        set::label($lang->programplan->output),
        set::width('1/2'),
        select(set(array(
            'name'     => 'output[]',
            'items'    => $documentList,
            'value'    => $plan->output,
            'multiple' => true,
            'required' => true
        )))
    ) : null
);

render('modalDialog');
