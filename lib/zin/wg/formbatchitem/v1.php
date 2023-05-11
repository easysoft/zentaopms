<?php
declare(strict_types=1);
/**
 * The formBatch widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */

namespace zin;

require_once dirname(__DIR__) . DS . 'formlabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'control' . DS . 'v1.php';

/**
 * 批量编辑表单项（formBatchItem）部件类。
 * The batch edit form item widget class.
 *
 * @author Hao Sun
 */
class formBatchItem extends wg
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static $defineProps = array
    (
        'name: string',
        'label: string|bool',
        'labelClass?: string',
        'labelProps?: string',
        'required?:bool|string="auto"',
        'control?: array|string',
        'width?: number=100',
        'value?: string|array',
        'disabled?: bool',
        'items?: array',
        'placeholder?: string',
        'tip?: string',
        'tipClass?: string',
        'tipIcon?: string="info-sign"',
        'tipProps?: string',
        'ditto?: bool'
    );

    /**
     * Define default properties.
     *
     * @var    array
     * @access protected
     */
    protected function build()
    {
        list($name, $label, $labelClass, $labelProps, $required, $tip, $tipClass, $tipProps, $tipIcon, $control, $width, $strong, $value, $disabled, $items, $placeholder, $ditto) = $this->prop(array('name', 'label', 'labelClass', 'labelProps', 'required', 'tip', 'tipClass', 'tipProps', 'tipIcon', 'control', 'width', 'strong', 'value', 'disabled', 'items', 'placeholder', 'ditto'));

        if($required === 'auto') $required = isFieldRequired($name);

        if(is_string($control))                   $control = ['type' => $control, 'name' => $name];
        elseif(empty($control) && $name !== null) $control = ['name' => $name];

        if(empty($label)) $label = $name;

        if(!empty($control))
        {
            if($required !== null)    $control['required']    = $required;
            if($name !== null)        $control['name']        = $name;
            if($value !== null)       $control['value']       = $value;
            if($disabled !== null)    $control['disabled']    = $disabled;
            if($items !== null)       $control['items']       = $items;
            if($placeholder !== null) $control['placeholder'] = $placeholder;
        }

        return array
        (
            h::th
            (
                set::class('form-batch-head'),
                set('data-required', $required),
                set('data-width', $width),
                set('data-ditto', $ditto),
                set('data-name', $name),
                set($this->getRestProps()),
                span
                (
                    set::class('form-batch-label', $labelClass, $strong ? 'font-bold' : null),
                    set::required($required),
                    set($labelProps),
                    $label
                ),
                empty($tip) ? null : new btn
                (
                    set::class('form-batch-tip state text-gray', $tipClass),
                    set::size('sm'),
                    set::type('ghost'),
                    toggle('tooltip', array('title' => $tip)),
                    set($tipProps),
                    set::icon($tipIcon)
                )
            ),
            h::td
            (
                setClass('form-batch-control'),
                set('data-name', $name),
                empty($control) ? null : new control(set($control)),
                $this->children()
            )
        );
    }
}
