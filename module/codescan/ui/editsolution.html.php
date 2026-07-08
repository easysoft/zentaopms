<?php
declare(strict_types=1);
/**
 * The create solution view file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;

$rulesets = array();
foreach($setList as $set)
{
    $priority = empty($set->priority) ? $lang->codescan->priorityList['low'] : zget($lang->codescan->priorityList, $set->priority);

    $rulesets[] = array(
        'value'   => $set->id,
        'text'    => $set->name,
        'hint'    => $set->lang
    );
}

formPanel
(
    set::id('codescanCreateForm'),
    set::title($lang->codescan->editSolution),
    formGroup
    (
        set::name('name'),
        set::required(true),
        set::label($lang->codescan->name),
        set::value($solution->name)
    ),
    formGroup
    (
        set::name('rulesets'),
        set::label($lang->codescan->rulesets),
        set::required(true),
        set::value($solution->rulesets),
        set::control(array(
            'type'     => 'picker',
            'items'    => $rulesets,
            'multiple' => true
        ))
    ),
    formGroup
    (
        set::name('desc'),
        set::label($lang->codescan->desc),
        set::value($solution->desc),
        set::control(array('type' => 'textarea', 'rows' => 4))
    )
);
