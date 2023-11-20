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

        list($name, $value) = $this->prop(array('name', 'value'));

        return array
        (
            datePicker
            (
                setClass('w-full'),
                set::name($name . '_begin'),
                set::value(zget($value, 'begin', ''))
            ),
            $lang->to,
            datePicker
            (
                setClass('w-full'),
                set::name($name . '_begin'),
                set::value(zget($value, 'end', ''))
            )
        );
    }

    protected function buildDatetimePicker(): array
    {
        global $lang;

        list($name, $value) = $this->prop(array('name', 'value'));

        return array
        (
            datetimePicker
            (
                setClass('w-full'),
                set::name($name . '_begin'),
                set::value(zget($value, 'begin', ''))
            ),
            $lang->to,
            datetimePicker
            (
                setClass('w-full'),
                set::name($name . '_end'),
                set::value(zget($value, 'end', ''))
            )
        );
    }

    protected function build(): wg|array
    {
        $type  = $this->prop('type');
        $class = $this->prop('class');
        if(($type == 'date' || $type == 'datetime') && empty($class)) $this->setProp('class', 'w-1/2');

        return parent::build();
    }
}
