<?php
declare(strict_types=1);
/**
 * The edit file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      liuruogu<liuruogu@easycorp.ltd>
 * @package     block
 * @link        https://www.zentao.net
 */
namespace zin;

include 'common.ui.php';

set::title($title);
jsVar('blockID', $block->id);
jsVar('blockTitle', $lang->block->blockTitle);

$showModules = ($dashboard == 'my' && $modules);
$showCodes   = (($showModules && $module && $codes) || $dashboard != 'my');
$code        = $showCodes ? $code : $module;
$blockSize   = !empty($config->block->size[$module][$code]) ? $config->block->size[$module][$code] : $config->block->defaultSize; // 获取当前区块的可选尺寸。

/* 根据区块的可选尺寸生成区块的可选宽度列表。 */
$widthOptions = array();
foreach(array_keys($blockSize) as $width) $widthOptions[$width] = zget($this->lang->block->widthOptions, $width);
row
(
    setID('blockEditForm'),
    $showModules ? cell
    (
        set::width(128),
        setClass('flex-none bg-surface rounded rounded-r-none rounded-tl-none overflow-y-auto'),
        buildBlockModuleNav()
    ) : null,
    cell
    (
        setClass('flex-auto pt-2 pr-6 pb-4'),
        form
        (
            on::change('[name="code"]', 'getForm'),
            on::change('[name="params\[type\]"]', 'changeType'),
            set::submitBtnText($lang->save),
            formRow
            (
                setClass('hidden'),
                formGroup
                (
                    set::name('module'),
                    set::value($showModules ? $module : $dashboard)
                )
            ),
            formRow
            (
                setID('codesRow'),
                setClass($showCodes ? '' : 'hidden'),
                formGroup
                (
                    set::label($lang->block->lblBlock),
                    set::name('code'),
                    set::value($showCodes ? $code : $module),
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
                setID('paramsRow'),
                setClass('space-y-4'),
                formRow
                (
                    formGroup
                    (
                        set::label($lang->block->name),
                        set::name('title'),
                        set::value($blockTitle),
                        set::control('input')
                    )
                ),
                buildParamsRows($block, null, $module, $code),
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
                            set::value($code == $block->code ? $block->width : ''),
                            set::required(true)
                        )
                    )
                ),
                formRow
                (
                    setClass($module == 'html' ? '' : 'hidden'),
                    formGroup
                    (
                        set::label($lang->block->lblHtml),
                        editor(set::name('html'), html(zget($block->params, 'html')))
                    )
                )
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
