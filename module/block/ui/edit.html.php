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
jsVar('blockID', $block->id);

$paramsRows  = array();
$showModules = ($dashboard == 'my' and $modules);
$showCodes   = ($module and $codes);

foreach($params as $key => $row)
{
    $paramsRows[] = formGroup
    (
        set::label($row['name']),
        set::name("params[$key]"),
        set::class('form-row'),
        set::value($block->params->{$key}),
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
    on::change('#code', 'getForm'),
    formGroup
    (
        set::class($showModules ? '' : 'hidden'),
        set::value($showModules ? $block->module : $dashboard),
        set::label($lang->block->lblModule),
        set::name('module'),
        set::control($showModules ? array
        (
            'type'  => 'select',
            'items' => $modules
        ) : 'input')  
    ),
    div
    (
        set::id('codesRow'),
        formGroup
        (
            set::label($lang->block->lblBlock),
            set::name('code'),
            set::class($showCodes ? '' : 'hidden'),
            set::value($showCodes ? $code : $module),
            set::control($showCodes ? array
            (
                'type'  => 'select',
                'items' => array('') + $codes
            ) : 'input')  
        )
    ),
    div
    (
        set::id('paramsRow'),
        div
        (
            set::class('form-grid'),
            formGroup
            (
                set::label($lang->block->name),
                set::name('title'),
                set::class('form-row'),
                set::value($block->title),
                set::control('input')  
            ),
            $paramsRows,
            formGroup
            (
                set::label($lang->block->grid),
                set::name("grid"),
                set::class('form-row'),
                set::value($block->grid),
                set::control(array
                (
                    'type'  => 'select',
                    'items' => $lang->block->gridOptions
                ))  
            )
        )
    )
);

render('modalDialog');
