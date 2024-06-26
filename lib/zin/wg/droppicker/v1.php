<?php
declare(strict_types=1);
/**
 * The dropPicker widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */

namespace zin;

require_once dirname(__DIR__) . DS . 'dropdown' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';

/**
 * 下拉菜单形式的选择器。
 * The dropPicker widget class.
 *
 * @author Hao Sun
 */
class dropPicker extends wg
{
    /**
     * Define the properties.
     *
     * @var array
     * @access protected
     */
    protected static array $defineProps = array
    (
        'btnClass' => 'string="w-full"',
        'text'     => 'string',
        'value'    => 'string',
        'name'     => 'string',
        'items'    => 'array',
        'menu'     => 'array'
    );

    /**
     * Override the build method.
     *
     * @access protected
     * @return array
     */
    protected function build()
    {
        list($items, $text, $value, $name, $btnClass, $menu) = $this->prop(array('items', 'text', 'value', 'name', 'btnClass', 'menu'));
        $btnID = $this->gid;

        $menuProps = array();
        $menuProps['getItem'] = jsRaw(<<<JS
        function(item)
        {
            const selected = document.getElementById('$btnID').querySelector('input').value;
            item.selected = String(item.value) === selected;
            return item;
        }
        JS);
        $menuProps['onClickItem'] = jsRaw(<<<JS
        function(e)
        {
            if(e.item.value === undefined) return;
            const btn = document.getElementById('$btnID');
            const input = btn.querySelector('input');
            btn.querySelector('.text').innerText = e.item.text;
            input.value = e.item.value;
            $(input).trigger('change');
        }
        JS);
        if(is_array($menu)) $menuProps = array_merge($menuProps, $menu);

        return new dropdown
        (
            new btn
            (
                $text,
                setClass('justify-between', $btnClass),
                setID($btnID),
                h::formHidden($name, $value)
            ),
            set::items($items),
            set::width('100%'),
            set::menu($menuProps),
            set($this->getRestProps())
        );
    }
}
