<?php
declare(strict_types=1);
/**
 * The select widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;

/**
 * 选择框（select）部件类，支持 Ajax 提交。
 * The select control widget class.
 *
 * @author Hao Sun
 */
class select extends wg
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array
    (
        'name: string',
        'id?: string',
        'class?: string="form-control"',
        'value?: string=""',
        'required?: bool',
        'disabled?: bool',
        'multiple?: bool',
        'items?: array',
        'size?: int',
    );

    /**
     * The lifecycle method of created.
     *
     * Set default id with name.
     * @access protected
     * @return void
     */
    protected function created()
    {
        if($this->prop('id') === null && $this->prop('name') !== null)
        {
            $name = $this->prop('name');
            $id   = substr($name, -2) == '[]' ? substr($name, 0, - 2) : $name;
            $this->setProp('id', $id);
        }
    }

     /**
     * Handle building inner options.
     *
     * @param  wg|array  wg
     * @access public
     * @return wg
     */
    public function onBuildItem(wg|array $item): wg
    {
        if($item instanceof item) $item = $item->props->toJSON();

        $text  = isset($item['text']) ? $item['text'] : '';
        unset($item['text']);

        if(!isset($item['selected']))
        {
            $value     = isset($item['value']) ? $item['value'] : '';
            $valueList = $this->getValueList();

            $item['selected'] = in_array($value, $valueList);
        }

        return h::option(set($item), $text);
    }

    /**
     * Get value list.
     *
     * @access public
     * @return array
     */
    public function getValueList()
    {
        list($value, $multiple) = $this->prop(array('value', 'multiple'));
        if($multiple) return is_array($value) ? $value : explode(',', (string)$value);
        return array($value);
    }

    /**
     * The lifecycle method of building.
     *
     * @access protected
     * @return wg
     */
    protected function build(): wg
    {
        list($items, $multiple, $required) = $this->prop(array('items', 'multiple', 'required'));

        $hasEmptyItem = false;
        $valueList    = $this->getValueList();
        if(!empty($items))
        {
            foreach($items as $key => $item)
            {
                if(!is_array($item))           $item = array('text' => $item, 'value' => $key);
                if(!is_string($item['value'])) $item['value'] = strval($item['value']);
                if(!isset($item['selected']))  $item['selected'] = in_array($item['value'], $valueList);
                $items[$key] = $this->onBuildItem($item);

                if($item['value'] === '') $hasEmptyItem = true;
            }
        }
        else
        {
            $items = array();
        }

        /* Prepend empty option when current select is not required and whitout any empty item. */
        if(!$required && !$hasEmptyItem) array_unshift($items, $this->onBuildItem(array('text' => '', 'value' => '', 'selected' => in_array('', $valueList))));

        $props = $this->props->skip(['items', 'value', 'multiple', 'required']);

        return h::select
        (
            setClass('form-control',  $required ? 'is-required' : ''),
            set::multiple($multiple),
            set($props),
            $items,
            $this->children()
        );
    }
}
