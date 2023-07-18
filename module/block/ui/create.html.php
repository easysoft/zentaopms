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

$showModules  = ($dashboard == 'my' && $modules);
$showCodes    = (($showModules && $module && $codes) || $dashboard != 'my');
$code         = $showCodes ? $code : $module;
$widths       = !empty($config->block->size[$module][$code]) ? array_keys($config->block->size[$module][$code]) : array('1', '2');
$widthOptions = array();
foreach($widths as $width) $widthOptions[$width] = zget($lang->block->widthOptions, $width);

$defaultWidth = !empty($config->block->size[$module][$code]) ? reset(array_keys($config->block->size[$module][$code])) : 1;

$paramsRows  = array();
foreach($params as $key => $row)
{
    $paramsRows[] = formRow
    (
        formGroup
        (
            set::label($row['name']),
            set::name("params[$key]"),
            set::value(zget($row, 'default', '')),
            set::control(array
            (
                'id'       => "params$key",
                'type'     => $row['control'],
                'items'    => isset($row['options']) ? $row['options'] : null,
            )),
            $row['control'] == 'picker' ? set::required(true) : '',
        ),
    );
}

$moduleTabs = array();
foreach($modules as $moduleKey => $moduleName)
{
    if(!$moduleKey || !$moduleName) continue;
    if($moduleKey == 'welcome') $moduleTabs[] = li(width('calc(100% - 2rem)'), setClass('nav-divider'));
    $moduleTabs[] = li
    (
        setClass('nav-item w-full'),
        a
        (
            setClass('ellipsis text-dark title' . ($moduleKey == $module ? ' active' : '')),
            on::click('getForm'),
            set('data-tab', $moduleKey),
            set('data-toggle', 'tab'),
            $moduleName
        ),
        span
        (
            setClass('link flex-1 text-right pr-2 hidden'),
            icon
            (
                setClass('text-primary'),
                'arrow-right'
            )
        )
    );
}

div
(
    set::id('blockCreateForm'),
    setClass('flex h-full overflow-hidden'),
    $showModules ? cell
    (
        width('128px'),
        setClass('bg-secondary-pale overflow-y-auto'),
        ul
        (
            setClass('nav nav-tabs nav-stacked my-2'),
            $moduleTabs
        ),
    ) : '',
    cell
    (
        width('calc(100% - ' . ($showModules ? '130' : '2') .  'px)'),
        form
        (
            setClass('border-b-0'),
            on::change('#code', 'getForm'),
            on::change('#paramstype', 'onParamsTypeChange'),
            formRow
            (
                setClass('hidden'),
                formGroup
                (
                    set::name('module'),
                    set::value($app->tab == 'my' ? $module : $dashboard),
                )
            ),
            formRow
            (
                set::id('codesRow'),
                setClass($showCodes ? '' : 'hidden'),
                formGroup
                (
                    set::label($lang->block->lblBlock),
                    set::name('code'),
                    set::value($code),
                    set::control
                    (
                        $showCodes ? array
                        (
                            'type'  => 'picker',
                            'items' => array('') + $codes
                        ) : 'input'
                    )
                )
            ),
            div
            (
                set::id('paramsRow'),
                formRow
                (
                    formGroup
                    (
                        set::label($lang->block->name),
                        set::name('title'),
                        set::value($blockTitle),
                        set::control('input')
                    ),
                ),
                $paramsRows,
                formRow
                (
                    setClass(empty($code) ? 'hidden' : ''),
                    formGroup
                    (
                        set::label($lang->block->width),
                        picker
                        (
                            set::name('width'),
                            set::items($widthOptions),
                            set::value($defaultWidth),
                            set::required(true),
                        ),
                    )
                ),
                $module == 'html' ? formRow
                (
                    formGroup
                    (
                        set::label($lang->block->lblHtml),
                        editor(set::name('html')),
                    )
                ) : null
            )
        )
    )
);

render();
