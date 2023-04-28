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

$paramsRows = array();
$isMyDashboard = $dashboard == 'my';

foreach($params as $key => $row)
{
    $paramsRows[] = formGroup
    (
        set::label($row['name']),
        set::name('params'),
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
        set::label($lang->block->lblModule),
        set::name('module'),
        set::class($isMyDashboard ? '' : 'hidden'),
        set::value($isMyDashboard ? $block->module : $dashboard),
        set::control(array
        (
            'type'  => 'select',
            'items' => $modules
        ))  
    ),
    div
    (
        set::id('codeRow'),
        $codes
        ? formGroup
        (
            set::label($lang->block->lblBlock),
            set::name('code'),
            set::value($code),
            set::control(array
            (
                'type'  => 'select',
                'items' => array('') + $codes
            ))  
        ) : null
    ),
    div
    (
        set::id('paramsRow'),
        set::class('form-grid'),
        $paramsRows,
        (($module and $code) or ($module and !$codes))
        ? formGroup
        (
            set::label($lang->block->name),
            set::name('title'),
            set::class('form-row'),
            set::value($block->title),
            set::control('input')  
        ) : null,
        (($module and $code) or ($module and !$codes))
        ? formGroup
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
        ) : null,
    )
);

render('modalDialog');
