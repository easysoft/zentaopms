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
    protected function buildDateControl(): array
    {
        global $lang;

        list($type, $name, $value) = $this->prop(array('type', 'name', 'value'));

        return array(
            input
            (
                set::name($name),
                set::type($type == 'datetime' ? 'datetime-local' : $type),
                set::value(zget($value, 'begin', ''))
            ),
            $lang->to,
            input
            (
                set::name($name),
                set::type($type == 'datetime' ? 'datetime-local' : $type),
                set::value(zget($value, 'end', ''))
            ),
        );
    }

    protected function build(): wg|array
    {
        $type = $this->prop('type');

        if($type == 'date' || $type == 'datetime') return inputGroup
        (
            setClass('pr-4 mb-2 ' . $this->prop('class', $type == 'datetime' ? 'w-1/3' : 'w-1/4')),
            $this->prop('title'),
            $this->buildDateControl($type)
        );

        return parent::build();
    }
}
