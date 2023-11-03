<?php
declare(strict_types=1);
/**
 * The progressCircle widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */

namespace zin;

/**
 * 环形进度条（progressCircle）部件类
 * The progressCircle widget class
 */
class progressCircle extends wg
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array
    (
        'percent?: int',           // 百分比。
        'size?: int',              // 大小。
        'circleWidth?: int',       // 环形宽度。
        'circleBg: string="var(--color-surface)"',        // 环形背景色。
        'circleColor: string="var(--color-primary-500)"',     // 环形颜色。
        'text?: string|boolean',   // 文本。
        'textStyle?: string|array',// 文本样式。
        'textX?: int',             // 文本 X 坐标。
        'textY?: int'              // 文本 Y 坐标。
    );

    protected function buildCircle()
    {
        return zui::progressCircle(set($this->getDefinedProps()));
    }

    /**
     * Build widget.
     *
     * @access protected
     * @return wg
     */
    protected function build(): wg
    {
        $children    = $this->children();
        $class       = $this->prop('class');
        $circleProps = $this->getDefinedProps();
        $hasChildren = !empty($children);

        return zui::progressCircle
        (
            set($circleProps),
            set::_class(array('hide-before-init transition-opacity', $class, $hasChildren ? 'relative center' : '')),
            $hasChildren ? div
            (
                setClass('center absolute inset-0 num gap-1'),
                $children
            ) : null
        );
    }
}
