<?php
declare(strict_types=1);
/**
 * The colorInput widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     zin
 * @link        http://www.zentao.net
 */

namespace zin;

require_once dirname(__DIR__) . DS . 'input' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'inputcontrol' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'colorpicker' . DS . 'v1.php';

/**
 * 带颜色选择器的输入框（colorInput）部件类
 * The colorInput widget class
 */
class colorInput extends inputControl
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array(
        'id?: string="$GID"',         // 组件根元素的 ID。
        'name?: string',              // 作为表单项的名称。
        'value?: string=""',          // 默认值。
        'colorName?: string="color"', // 颜色表单项名称。
        'colorValue?: string=""',     // 颜色默认值。
    );

    /**
     * Build widget.
     *
     * @access protected
     */
    protected function build(): wg
    {
        list($props) = $this->props->split(array_keys(static::definedPropsList()));
        return inputControl
            (
                input
                (
                    set::name($props['name']),
                    set::value($props['value']),
                ),
                set::suffixWidth('icon'),
                to::suffix
                (
                    colorPicker
                    (
                        set::name($props['colorName']),
                        set::value($props['colorValue']),
                        set::syncColor('#' . $props['name'])
                    )
                )
            );
    }
}

