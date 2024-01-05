<?php
declare(strict_types=1);
/**
 * The mindmap widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;

/**
 * 脑图（mindmap）部件。
 * The mindmap widget class.
 */
class mindmap extends wg
{
    protected static array $defineProps = array
    (
        'data?: array',
        'width?: string|number="100%"',
        'height?: string|number="300px"',
        'hotkeyEnable?: bool',
        'hotkeys?: array',
        'lang?: string',
        'langs?: array',
        'nodeTeamplate?: string',
        'hSpace?: number',
        'vSpace?: number',
        'canvasPadding?: number',
        'removingNodeTip?: string',
        'lineCurvature?: number',
        'subLineWidth?: number',
        'lineColor?: string',
        'lineOpacity?: number',
        'lineSaturation?: number',
        'lineLightness?: number',
        'nodeLineWidth?: number',
        'showToggleButton?: bool',
        'readonly?: bool',
        'minimap?: bool',
        'toolbar?: bool',
        'zoom?: number',
        'zoomMax?: number',
        'zoomMin?: number',
        'minimapHeight?: number',
        'enableDrag?: bool',
        'manual?: bool',
        'afterNodeLoad?: function'
    );

    protected function build(): array
    {
        global $app;

        list($width, $height) = $this->prop(array('width', 'height'));
        $dataVarName = "_mindmap_$this->gid";
        $mindmapPath = $app->getWebRoot() . 'js/mindmap/index.html?options=' . $dataVarName;
        $options = $this->props->pick(array('hotkeyEnable', 'hotkeys', 'lang', 'langs', 'data', 'nodeTeamplate', 'hSpace', 'vSpace', 'canvasPadding', 'removingNodeTip', 'lineCurvature', 'subLineWidth', 'lineColor', 'lineOpacity', 'lineSaturation', 'lineLightness', 'nodeLineWidth', 'showToggleButton', 'readonly', 'minimap', 'toolbar', 'zoom', 'zoomMax', 'zoomMin', 'minimapHeight', 'enableDrag', 'manual', 'afterNodeLoad'));
        return array
        (
            h::iframe
            (
                set::className('mindmap-iframe'),
                set::src($mindmapPath),
                set::allowfullscreen(true),
                set::allowtransparency(true),
                set::frameborder('no'),
                set::scrolling('auto'),
                set::style(array('width' => $width, 'height' => $height)),
                set($this->getRestProps())
            ),
            h::jsVar("window.$dataVarName", $options)
        );
    }
}
