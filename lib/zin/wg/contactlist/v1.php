<?php
declare(strict_types=1);
/**
 * The contactList widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;

class contactList extends wg
{
    protected static array $defineProps = array(
        'name?: string="contactList"',  // 控件名称。
        'target?: string',              // 选中项改变时更新的目标
        'items?: array',                // picker 列表项或表项获取方法。
        'placeholder?: string',         // picker 占位符。
    );

    public static function getPageJS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function created()
    {
        $items = $this->prop('items');
        if(!$items)
        {
            global $app;
            $lists = $app->control->loadModel('user')->getContactLists();
            $items = array_map(function($id, $name){return array('text' => $name, 'value' => $id);}, array_keys($lists), $lists);
            $this->setProp('items', $items);
        }

        $placeholder = $this->prop('placeholder');
        if(!$placeholder)
        {
            global $lang;
            $placeholder = $lang->contact->common;
            $this->setProp('placeholder', $placeholder);
        }
    }

    protected function build(): wg|array
    {
        global $app, $lang;
        $app->loadLang('user');

        $items  = $this->prop('items');
        $target = $this->prop('target');

        return span
        (
            setID('contactBox'),
            setClass('input-group-' . ($items ? 'addon p-0 w-24' : 'btn')),
            picker
            (
                $items ? null : setClass('hidden'),
                set::name($this->prop('name')),
                set::items($items),
                set::placeholder($this->prop('placeholder')),
                on::change("loadContactUsers('{$target}', ele)"),
            ),
            a
            (
                $items ? setClass('hidden') : null,
                setID('manageContact'),
                setClass('btn'),
                setData(array('toggle' => 'modal')),
                set('href', createLink('my', 'managecontacts')),
                set('title', $lang->user->contacts->manage),
                icon('groups')
            )
        );
    }
}
