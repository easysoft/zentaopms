<?php
declare(strict_types=1);
/**
 * The users widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;

class users extends wg
{
    protected static array $defineProps = array(
        'label?: string',                   // 控件标签。
        'id?: string',                      // 控件 ID。
        'name?: string="users[]"',          // 控件名称。
        'value?: string',                   // 控件默认值。
        'items?: array',                    // picker 列表项或表项获取方法。
        'menu?: array',                     // picker 附加的菜单选项。
        'toolbar?: boolean|array',          // picker 列表工具栏。
        'multiple?: boolean|number=false',  // picker 是否允许选择多个值，如果指定为数字，则限制多选的数目，默认 `false`。
        'contactList?: boolean=true'        // 是否显示联系人列表。
    );

    protected static array $defaultProps = array(
        'menu' => array('checkbox' => true),
        'toolbar' => true,
        'multiple' => true
    );

    protected function created()
    {
        $items = $this->prop('items');
        if(!$items)
        {
            global $app;
            $users = $app->control->loadModel('user')->getPairs('noclosed|nodeleted');
            $items = array_map(function($account, $name){return array('text' => $name, 'value' => $account);}, array_keys($users), $users);
            $this->setProp('items', $items);
        }
    }

    protected function build(): wg|array
    {
        return inputGroup
        (
            $this->prop('label', null),
            picker(set($this->props->pick(array('id', 'name', 'value', 'items', 'toolbar', 'menu', 'multiple')))),
            $this->prop('contactList') ? contactList() : null
        );
    }
}
