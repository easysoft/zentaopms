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

require_once dirname(__DIR__) . DS . 'formbatchitem' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'formbase' . DS . 'v1.php';

/**
 * 批量编辑表单（formBatch）部件类，支持 Ajax 提交。
 * The batch operate form widget class.
 *
 * @author Hao Sun
 */
class formBatch extends formBase
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array(
        'items?: array[]',              // 使用一个列定义对象数组来定义批量表单项。
        'minRows?: int',                // 最小显示的行数目。
        'maxRows?: int',                // 最多显示的行数目。
        'data?: array[]',               // 初始化行数据。
        'mode?: string',                // 批量操作模式，可以为 `'add'`（批量添加） 或 `'edit'`（批量编辑）。
        'actionsText?: string',         // 操作列头部文本，如果不指定则使用 `$lang->actions` 的值。
        'idKey?: string',               // 用于从行数据获取 ID 的属性名。
        'addRowIcon?: string|false',    // 添加行的图标，如果设置为 `false` 则不显示图标
        'deleteRowIcon?: string|false', // 删除行的图标，如果设置为 `false` 则不显示图标
        'sortRowIcon?: string|false',   // 排序行的图标，如果设置为 `false` 则不显示图标
        'sortable?: boo|array',         // 排序配置，设置为 false 不启用排序，设置为 true 使用默认排序
        'onRenderRow?: function',       // 渲染行时的回调函数。
        'hiddenFields?: array',         // 被隐藏的字段。
        'onRenderRowCol?: function',    // 渲染列时的回调函数。
        'batchFormOptions?: array'      // 批量表单选项。
    );

    /**
     * Define default properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defaultProps = array(
        'maxRows' => 100,
        'mode'    => 'add'
    );

    protected static array $defineBlocks = array(
        'formBefore' => array()
    );

    /**
     * Handle building inner items.
     *
     * @param  node|array  $item
     * @access public
     * @return mixed
     */
    public function onBuildItem(node|array $item): node
    {
        if($item instanceof formBatchItem) return $item;

        if(!($item instanceof item))
        {
            if(!is_array($item)) return $item;
            $item = item(set($item));
        }

        return new formBatchItem(inherit($item));
    }

    public function children(): array
    {
        $children = array();
        $children[] = $this->buildContent();
        $children[] = $this->buildActions();
        return $children;
    }

    /**
     * Build batch form content.
     *
     * @access protected
     * @return array|node
     */
    protected function buildContent(): array|node
    {
        $items         = array_merge($this->prop('items', array()), $this->block('children'));
        $hiddenFields  = $this->prop('hiddenFields', array());
        $templateItems = array();
        $headItems     = array();
        $otherItems    = array();

        foreach($items as $item)
        {
            if($item instanceof setting)                 $item = $item->toArray();
            if($item instanceof item || is_array($item)) $item = $this->onBuildItem($item);
            if($item instanceof formBatchItem)
            {
                if($item->hasProp('name') && is_null($item->prop('hidden'))) $item->setProp('hidden', in_array($item->prop('name'), $hiddenFields));
                list($headItem, $templateItem) = $item->build();
                $headItems[]     = $headItem;
                $templateItems[] = $templateItem;
            }
            else
            {
                $otherItems[] = $item;
            }
        }

        if($this->prop('mode') === 'add')
        {
            $actionsText = $this->prop('actionsText');
            if($actionsText === null) $actionsText = data('lang.actions');
            $headItems[] = h::th
            (
                zui::width($this->prop('actionsWidth')),
                set('data-name', 'ACTIONS'),
                setClass('form-batch-head'),
                span(setClass('form-label form-batch-label'), $actionsText)
            );
        }

        return array(
            div
            (
                setClass('form-batch-container relative'),
                $this->block('formBefore'),
                h::table
                (
                    setClass('table form-batch-table'),
                    h::thead(setClass('sticky top-0 bg-canvas z-10'), h::tr($headItems)),
                    h::tbody(),
                )
            ),
            template(setClass('form-batch-template'), h::tr($templateItems)),
            $otherItems
        );
    }

    /**
     * Build batch form props.
     *
     * @access protected
     * @return array
     */
    protected function buildProps(): array
    {
        $props = parent::buildProps();

        $props[] = setClass('form-batch');

        $batchFormOptions = $this->props->pick(array('minRows', 'maxRows', 'data', 'mode', 'idKey', 'onRenderRow', 'onRenderRowCol', 'addRowIcon', 'deleteRowIcon', 'sortRowIcon', 'sortable'));
        $batchFormOptions = array_merge($batchFormOptions, $this->prop('batchFormOptions', array()));
        $props = array_merge($props, zui::create('batchForm', $batchFormOptions));

        return $props;
    }
}
