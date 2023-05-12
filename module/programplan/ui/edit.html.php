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

jsVar('plan', $plan);
jsVar('stageTypeList',  $lang->stage->typeList);
jsVar('changeAttrLang', $lang->programplan->confirmChangeAttr);
jsVar('isTopStage',     $isTopStage);
jsVar('isLeafStage',    $isLeafStage);

formPanel
(
    set::title(''),
    set::actions(array()),
    div
    (
        setClass('flex items-center pb-2.5'),
        span($lang->edit),
        span($plan->name, setClass('text-lg font-bold ml-3')),
        label($plan->id,  setClass('circle ml-2 label-id px-2'))
    ),
    formGroup
    (
        set(array(
            'label' => $lang->programplan->parent,
            'width' => '2/3'
        )),
        select
        (
            on::change('changeParentStage(this)'),
            set(array(
                'id'       => 'parent',
                'name'     => 'parent',
                'items'    => $parentStageList,
                'value'    => $plan->parent,
                'required' => true
            ))
        )
    ),
    formGroup
    (
        set(array(
            'label'    => $lang->programplan->name,
            'required' => true,
            'width'    => '2/3'
        )),
        input(set(array(
            'name'  => 'name',
            'value' => $plan->name
        )))
    ),
    isset($config->setCode) && $config->setCode == 1 ?
    formGroup
    (
        set(array(
            'label'    => $lang->execution->code,
            'required' => true,
            'width'    => '2/3'
        )),
        input(set(array(
            'name'  => 'code',
            'value' => $plan->code
        )))
    ): null,
    formGroup
    (
        set(array(
            'label' => $lang->programplan->PM,
            'width' => '2/3'
        )),
        select(set(array(
            'name'     => 'PM',
            'items'    => $PMUsers,
            'value'    => $plan->PM,
            'required' => true
        )))
    ),
    isset($config->setPercent) && $config->setPercent == 1 ?
    formGroup
    (
        set(array(
            'label' => $lang->programplan->percent,
            'width' => '2/3'
        )),
        inputControl
        (
            input(set(array(
                'name'  => 'percent',
                'value' => $plan->percent
            ))),
            to::suffix('%'),
            set::suffixWidth('lg')
        )
    ): null,
    formRow
    (
        formGroup
        (
            set(array(
                'label' => $lang->programplan->attribute,
                'width' => '2/3'
            )),
            $enableOptionalAttr ?
            select(set(array(
                'id'       => 'attribute',
                'name'     => 'attribute',
                'items'    => $lang->stage->typeList,
                'value'    => $plan->attribute,
                'required' => true
            ))):
            zget($lang->stage->typeList, $plan->attribute)
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
                set(array(
                    'id'    => 'helpTip',
                    'class' => 'tooltip darker'
                ))
            )
        )
    ),
    $plan->setMilestone ?
    formGroup
    (
        set(array(
            'label' => $lang->programplan->milestone,
            'width' => '2/3'
        )),
        radioList(set(array(
            'name'   => 'milestone',
            'items'  => $lang->programplan->milestoneList,
            'value'  => $plan->milestone,
            'inline' => true
        )))
    ) : input(set(array(
        'type'  => 'hidden',
        'value' => $plan->milestone
    ))),
    formGroup
    (
        set(array(
            'label' => $lang->project->acl,
            'width' => '2/3'
        )),
        select(set(array(
            'name'     => 'acl',
            'items'    => $lang->execution->aclList,
            'value'    => $plan->acl,
            'disabled' => $plan->grade == 2 ? 'disabled' : '',
            'required' => true
        )))
    ),
    formGroup
    (
        set(array(
            'label'    => $lang->programplan->planDateRange,
            'required' => true,
            'width'    => '2/3'
        )),
        inputGroup
        (
            input(set(array(
                'type'  => 'date',
                'name'  => 'begin',
                'value' => $plan->begin
            ))),
            $lang->project->to,
            input(set(array(
                'type'  => 'date',
                'name'  => 'end',
                'value' => $plan->end
            )))
        )
    ),
    formGroup
    (
        set(array(
            'label' => $lang->programplan->realDateRange,
            'width' => '2/3'
        )),
        inputGroup
        (
            input(set(array(
                'type'  => 'date',
                'name'  => 'realBegan',
                'value' => $plan->realBegan
            ))),
            $lang->project->to,
            input(set(array(
                'type'  => 'date',
                'name'  => 'realEnd',
                'value' => $plan->realEnd
            )))
        )
    ),
    isset($this->config->qcVersion) ?
    formGroup
    (
        set(array(
            'label' => $lang->programplan->output,
            'width' => '2/3'
        )),
        select(set(array(
            'name'     => 'output[]',
            'items'    => $documentList,
            'value'    => $plan->output,
            'multiple' => true,
            'required' => true
        )))
    ) : null,
    formGroup
    (
        set(array(
            'width' => '2/3',
            'class' => 'justify-center form-actions'
        )),
        btn
        (
            on::click('editStage()'),
            set(array(
                'text'  => $lang->save,
                'class' => 'primary toolbar-item'
            ))
        )
    )
);

render();
