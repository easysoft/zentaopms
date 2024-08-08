<?php
declare(strict_types=1);
/**
 * The batchedit view file of project module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     project
 * @link        https://www.zentao.net
 */
namespace zin;

$programAclList = array();
$projectAclList = array();
foreach($lang->program->subAcls as $acl => $label) $programAclList[] = array('text' => $label, 'value' => $acl);
foreach($lang->project->acls as $acl => $label)    $projectAclList[] = array('text' => $label, 'value' => $acl);

jsVar('longTime', LONG_TIME);
jsVar('weekend', $config->execution->weekend);
jsVar('programAclList', $programAclList);
jsVar('projectAclList', $projectAclList);
jsVar('disabledprograms', !empty($globalDisableProgram));
jsVar('beginLessThanParent', $lang->project->beginLessThanParent);
jsVar('endGreatThanParent', $lang->project->endGreatThanParent);

$setCode = (isset($config->setCode) and $config->setCode == 1);
$items = array();
$items[] = array
(
    'name'    => 'id',
    'label'   => $lang->idAB,
    'control' => 'hidden',
    'hidden'  => true
);
$items[] = array
(
    'name'    => 'id',
    'label'   => $lang->idAB,
    'control' => 'index',
    'width'   => '38px'
);
$items[] = array
(
    'name'    => 'parent',
    'label'   => $lang->project->program,
    'control' => 'picker',
    'items'   => $programs,
    'width'   => '136px'
);
$items[] = array
(
    'name'     => 'name',
    'required' => true,
    'label'    => $lang->project->name,
);
if($setCode)
{
    $items[] = array
    (
        'name'     => 'code',
        'label'    => $lang->project->code,
        'required' => strpos($config->project->edit->requiredFields, 'code') !== false,
        'width'    => '136px'
    );
}
$items[] = array
(
    'name'         => 'PM',
    'label'        => $lang->project->PM,
    'control'      => 'picker',
    'ditto'        => true,
    'defaultDitto' => 'off',
    'items'        => $PMUsers,
    'width'        => '136px'
);
$items[] = array
(
    'name'     => 'begin',
    'required' => true,
    'label'    => $lang->project->begin,
    'control'  => 'date',
    'width'    => '120px'
);
$items[] = array
(
    'name'     => 'end',
    'required' => true,
    'label'    => $lang->project->end,
    'width'    => '120px',
    'control'  => array
    (
        'control' => 'date',
        'display' => jsRaw("(value) => (value === '" . LONG_TIME . "' ? '" . $lang->project->longTime . "' : zui.formatDate(value, 'yyyy-MM-dd'))"),
        'actions' => array
        (
            array('text' => $lang->datepicker->dpText->TEXT_TODAY, 'data-set-date' => helper::today()),
            array('text' => $lang->project->longTime, 'data-set-date' => LONG_TIME)
        )
    ),
);
$items[] = array
(
    'name'  => 'days',
    'label' => $lang->project->days,
    'width' => '84px'
);
$items[] = array
(
    'name'    => 'acl',
    'label'   => $lang->project->acl,
    'control' => 'picker',
    'items'   => array(),
    'width'   => '76px'
);

formBatchPanel
(
    set::title($lang->project->batchEdit),
    set::mode('edit'),
    set::data(array_values($projects)),
    set::onRenderRow(jsRaw('renderRowData')),
    on::change('[name^=begin],[name^=end]', 'batchComputeWorkDays'),
    $config->systemMode != 'light' ? on::change('[name^=begin],[name^=end],[name^=parent]', 'batchCheckDate') : null,
    set::items($items)
);

h::table
(
    setID('dateTipTemplate'),
    setClass('hidden'),
    h::tr
    (
        setClass('dateTip'),
        h::td
        (
            set::colspan($setCode ? 9 : 8),
            div
            (
                setClass('text-right'),
                span(setClass('beginLess text-warning hidden'), html($lang->project->beginLessThanParent)),
                span(setClass('endGreater text-warning hidden'), html($lang->project->endGreatThanParent)),
                a(setClass('underline text-warning'), set::href('javascript:;'), $lang->project->ignore)
            )
        )
    )
);

render();
