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

include 'common.ui.php';

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

row
(
    set::id('blockCreateForm'),
    $showModules ? cell
    (
        set::width(128),
        set::class('flex-none bg-surface rounded rounded-r-none rounded-tl-none overflow-y-auto'),
        buildBlockModuleNav()
    ) : null,
    cell
    (
        setClass('flex-auto pr-6 pb-4'),
        form
        (
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
                set::class('space-y-4'),
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
                buildParamsRows(),
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

if(isInModal())
{
    set::condensed(true);
    set::bodyClass('border-t');
    set::bodyProps(array('style' => array('padding' => 0)));
}

render();
