<?php
declare(strict_types=1);
/**
 * The modulePicker widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;

class modulePicker extends wg
{
    protected static array $defineProps = array(
        'id?: string',              // 控件 ID。
        'name?: string="module"',   // 控件名称。
        'value?: string',           // 控件默认值。
        'required?: bool=true',     // 是否必填。
        'items?: array',            // picker 列表项或列表项获取方法。
        'manageLink?: string'       // 维护模块链接
    );

    public static function getPageJS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function build(): wg|array
    {
        global $app, $lang;
        $app->loadLang('tree');

        $items = $this->prop('items', array());
        $items = array_filter($this->prop('items'),function($key, $value){return !empty($key) && !empty($value);}, ARRAY_FILTER_USE_BOTH);

        return inputGroup
        (
            setID('moduleBox'),
            $this->prop('label', null),
            picker(set($this->props->pick(array('id', 'name', 'value', 'required', 'items')))),
            $items ? null : span
            (
                setClass('input-group-btn'),
                a
                (
                    setID('manageModule'),
                    setClass('btn'),
                    setData(array('toggle' => 'modal', 'size' => 'lg')),
                    set('href', $this->prop('manageLink')),
                    set('title', $lang->tree->manage),
                    icon('treemap')
                )
            )
        );
    }
}
