<?php
declare(strict_types=1);
/**
 * The create file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      liuruogu<liuruogu@easycorp.ltd>
 * @package     block
 * @link        http://www.zentao.net
 */
namespace zin;

set::title($title);
jsVar('dashboard', $dashboard);
jsVar('blockTitle', $lang->block->blockTitle);

$paramsRows  = array();
$showModules = ($dashboard == 'my' && $modules);
$showCodes   = (($showModules && $module && $codes) || $dashboard != 'my');

if($module == 'scrumtest' && $code != 'all')
{
    $blockTitle = zget($codes, $code);
}
else
{
    $typeOptions = isset($params['type']['options']) ? $params['type']['options'] : array();
    $blockTitle  = zget($codes, $code);
    if(!empty($typeOptions))
    {
        $typeName   = empty($typeOptions) ? '' : $typeOptions[array_keys($typeOptions)[0]];
        $blockTitle = vsprintf($lang->block->blockTitle, array($typeName, $blockTitle));
    }
}

foreach($params as $key => $row)
{
    $paramsRows[] = formGroup
    (
        set::label($row['name']),
        set::name("params[$key]"),
        set::class('form-row'),
        set::value(zget($row, 'default', '')),
        set::control(array
        (
            'id'    => "params$key",
            'type'  => $row['control'],
            'items' => isset($row['options']) ? $row['options'] : null
        ))
    );
}

form
(
    on::change('#module', 'getForm'),
    on::change('#code', 'getForm'),
    on::change('#paramstype', 'onParamsTypeChange'),
    formGroup
    (
        set::class($showModules ? '' : 'hidden'),
        set::value($showModules ? $code : $dashboard),
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
                set::value($blockTitle),
                set::class('form-row'),
                set::control('input')
            ),
            $paramsRows,
            formGroup
            (
                set::label($lang->block->grid),
                set::name('grid'),
                set::class('form-row'),
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
