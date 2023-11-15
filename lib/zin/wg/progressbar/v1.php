<?php
declare(strict_types=1);
/**
 * The progressBar widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */

namespace zin;

/**
 * 进度条（progressBar）部件类
 * The progressBar widget class
 */
class progressBar extends wg
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array
    (
        'percent?: number|array=50',   // 百分比。
        'color?: string',              // 颜色。
        'background?: string',         // 背景色。
        'height: number=20',           // 高度。
        'width: string|number="auto"', // 宽度，可以为 'auto' | '100%' | number | ({} & string)。
        'striped?: bool',              // 是否显示条纹。
        'active?: bool'                // 是否显示动画。
    );

    /**
     * Build widget.
     *
     * @access protected
     * @return wg
     */
    protected function build(): wg
    {
        list($percent, $color, $background, $height, $width, $striped, $active) = $this->prop(array('percent', 'color', 'background', 'height', 'width', 'striped', 'active'));
        $style = array
        (
            'width' => is_numeric($width) ? "{$width}px" : $width,
            'height' => is_numeric($height) ? "{$height}px" : $height,
            '--progress-bg' => $background,
            '--progress-bar-color' => $color
        );

        if(!is_array($percent)) $percent = array($percent);
        $bars = array();
        foreach($percent as $info)
        {
            if(!is_array($info)) $info = array('value' => $info);
            $bars[] = div
            (
                setClass('progress-bar'),
                setStyle(array('width' => "{$info['value']}%", '--progress-bar-color' => isset($info['color']) ? $info['color'] : $color))
            );
        }

        return div
        (
            setClass('progress', $striped ? 'progress-striped' : '', $active ? 'active' : ''),
            setStyle($style),
            set($this->getRestProps()),
            $bars,
            $this->children()
        );
    }
}
