<?php
declare(strict_types=1);
/**
 * The ui file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      liuruogu<liuruogu@easycorp.ltd>
 * @package     block
 * @link        http://www.zentao.net
 */
namespace zin;

set::title($title);
jsVar('blockID', $blockData->id);

$paramsRows = array();

foreach($params as $code => $row)
{
    $paramsRows[] = formGroup
    (
        set::label($row['name']),
        set::name('params'),
        set::class('form-row'),
        set::value($blockData->params->{$code}),
        set::control(array
        (
            'type'  => $row['control'],
            'items' => isset($row['options']) ? $row['options'] : null
        ))  
    );
}

form
(
    on::change('#module', 'getForm'),
    on::change('#block', 'getForm'),
    formGroup
    (
        set::label($lang->block->lblModule),
        set::name('module'),
        set::value($blockData->module),
        set::control(array
        (
            'type'  => 'select',
            'items' => $modules
        ))  
    ),
    div
    (
        set::id('blockRow'),
        set::class('form-row'),
        $blocks
        ? formGroup
        (
            set::label($lang->block->lblBlock),
            set::name('block'),
            set::value($block),
            set::control(array
            (
                'type'  => 'select',
                'items' => array('') + $blocks
            ))  
        ) : null
    ),
    div
    (
        set::id('paramsRow'),
        set::class('form-grid'),
        $paramsRows,
        (($module and $block) or ($module and !$blocks))
        ? formGroup
        (
            set::label($lang->block->name),
            set::name('title'),
            set::class('form-row'),
            set::value($blockData->title),
            set::control('input')  
        ) : null,
        (($module and $block) or ($module and !$blocks))
        ? formGroup
        (
            set::label($lang->block->grid),
            set::name("grid"),
            set::class('form-row'),
            set::value($blockData->grid),
            set::control(array
            (
                'type'  => 'select',
                'items' => $lang->block->gridOptions
            ))  
        ) : null,
    )
);

render('modalDialog');
