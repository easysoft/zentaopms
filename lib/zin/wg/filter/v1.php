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

class filter extends wg
{
    protected static array $defineProps = array(
        'class?: string',
        'title?: string',
        'type?: string',
        'name?: string',
        'value?: string',
        'items?: array'
    );

    protected function buildControl(): wg|array
    {
        list($type, $name, $value, $items) = $this->prop(array('type', 'name', 'value', 'items'));

        if($type == 'select') return picker
        (
            setClass('w-full'),
            set::name($name),
            set::value($value),
            set::items($items)
        );

        return input
        (
            set::name($name),
            set::type($type == 'datetime' ? 'datetime-local' : $type),
            set::value($value)
        );
    }

    protected function build(): wg|array
    {
        return inputGroup
        (
            setClass('pr-4 mb-2 ' . $this->prop('class', 'w-1/4')),
            $this->prop('title'),
            $this->buildControl()
        );
    }
}
