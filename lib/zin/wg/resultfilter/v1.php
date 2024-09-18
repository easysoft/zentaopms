<?php
declare(strict_types=1);
/**
 * The filter widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;

requireWg('filter');

class resultFilter extends filter
{
    protected static array $defaultProps = array(
        'menu' => array('checkbox' => true),
        'multiple' => true
    );

    protected function buildDatePicker(): array
    {
        global $lang;

        list($name, $value, $onChange) = $this->prop(array('name', 'value', 'onChange'));

        return array
        (
            datePicker
            (
                setClass('w-full'),
                set::name($name . '_begin'),
                set::value(zget($value, 'begin', '')),
                on::change("$onChange(e, '{$name}', 'begin')")
            ),
            $lang->to,
            datePicker
            (
                setClass('w-full'),
                set::name($name . '_end'),
                set::value(zget($value, 'end', '')),
                on::change("$onChange(e, '{$name}', 'end')")
            )
        );
    }

    protected function buildDatetimePicker(): array
    {
        global $lang;

        list($name, $value, $onChange) = $this->prop(array('name', 'value', 'onChange'));

        return array
        (
            datetimePicker
            (
                setClass('w-full'),
                set::name($name . '_begin'),
                set::value(zget($value, 'begin', '')),
                on::change("$onChange(e, '{$name}', 'begin')")
            ),
            $lang->to,
            datetimePicker
            (
                setClass('w-full'),
                set::name($name . '_end'),
                set::value(zget($value, 'end', '')),
                on::change("$onChange(e, '{$name}', 'end')")
            )
        );
    }

    protected function build()
    {
        list($type, $layout)  = $this->prop(array('type', 'layout'));
        $class = $this->prop('class');
        if(($type == 'date' || $type == 'datetime') && empty($class)) $this->setProp('class', $layout === 'horz' ? 'w-1/2' : '');

        return parent::build();
    }
}
