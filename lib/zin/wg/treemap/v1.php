<?php
declare(strict_types=1);
/**
 * The treemap widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;

/**
 * 组织结构图（treemap）部件。
 * The treemap widget class.
 */
class treemap extends wg
{
    protected static array $defineProps = array
    (
        'width?: string|number="100%"',  // 宽度。
        'height?: string|number="300px"', // 高度。
        'data?: array',                  // 树形结构数据。
        'rowSpace?: number',             // 节点层级之间的间距。
        'nodeSpace?: number',            // 同一层级相邻节点间的间距。
        'foldable?: bool',               // 是否可以折叠子节点。
        'clickNodeToFold?: bool',        // 是否允许直接点击节点折叠子节点。
        'sort?: bool',                   // 是否启用排序。
        'cableWidth?: number',           // 连接线宽度。
        'cableColor?: string',           // 连接线颜色。
        'cableStyle?: string',           // 连接线样式。
        'listenNodeResize?: bool',       // 监听节点尺寸变化。
        'tooltip?: array',               // 工具提示选项。
        'nodeStyle?: array',             // 定义节点的默认样式。
        'nodeTemplate?: string',         // 节点元素模板。
        'onNodeClick?: function',        // 节点元素模板。
        'afterDrawLines?: function',     // 节点元素模板。
        'afterRender?: function'         // 节点元素模板。
    );

    protected function build(): array
    {
        global $app;

        list($width, $height) = $this->prop(array('width', 'height'));
        $dataVarName = "_treemap_$this->gid";
        $treemapPath = $app->getWebRoot() . 'js/zui/treemap/index.html?options=' . $dataVarName;
        $options = $this->props->pick(array('hotkeyEnable', 'hotkeys', 'lang', 'langs', 'data', 'nodeTeamplate', 'hSpace', 'vSpace', 'canvasPadding', 'removingNodeTip', 'lineCurvature', 'subLineWidth', 'lineColor', 'lineOpacity', 'lineSaturation', 'lineLightness', 'nodeLineWidth', 'showToggleButton', 'readonly', 'minimap', 'toolbar', 'zoom', 'zoomMax', 'zoomMin', 'minimapHeight', 'onNodeClick', 'afterDrawLines', 'afterRender'));
        return array
        (
            h::jsVar("window.$dataVarName", $options),
            h::iframe
            (
                set::className('treemap-iframe'),
                set::src($treemapPath),
                set::allowfullscreen(true),
                set::allowtransparency(true),
                set::frameborder('no'),
                set::scrolling('auto'),
                set::style(array('width' => $width, 'height' => $height)),
                set($this->getRestProps())
            )
        );
    }
}
