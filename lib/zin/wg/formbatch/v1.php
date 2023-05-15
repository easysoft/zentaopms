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
    static $defineProps = array
    (
        'items?: array[]',      // 使用一个列定义对象数组来定义批量表单项。
        'minRows?: int',        // 最小显示的行数目。
        'maxRows?: int',        // 最多显示的行数目。
        'data?: array[]',       // 初始化行数据。
        'mode?: string',        // 批量操作模式，可以为 `'add'`（批量添加） 或 `'edit'`（批量编辑）。
        'actionsText?: string', // 操作列头部文本，如果不指定则使用 `$lang->actions` 的值。
    );

    /**
     * Define default properties.
     *
     * @var    array
     * @access protected
     */
    static $defaultProps = array
    (
        'minRows' => 1,
        'maxRows' => 100,
        'mode'    => 'add'
    );

    /**
     * Handle building inner items.
     *
     * @param  wg|array  wg
     * @access public
     * @return wg
     */
    public function onBuildItem(wg|array $item): wg
    {
        if($item instanceof formBatchItem) return $item;

        if(!($item instanceof item))
        {
            if(!is_array($item)) return $item;
            $item = item(set($item));
        }

        return new formBatchItem(inherit($item));
    }

    /**
     * Build batch form content.
     *
     * @access protected
     * @return array|wg
     */
    protected function buildContent(): array|wg
    {
        $items         = array_merge($this->children(), $this->prop('items', array()));
        $templateItems = array();
        $headItems     = array();
        $otherItems    = array();

        foreach($items as $item)
        {
            if($item instanceof item || is_array($item)) $item = $this->onBuildItem($item);
            if($item instanceof formBatchItem)
            {
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
                set('data-name', 'ACTIONS'),
                setClass('form-batch-head'),
                span(setClass('form-label form-batch-label'), $actionsText)
            );
        }

        return array
        (
            div
            (
                setClass('form-batch-container'),
                h::table
                (
                    setClass('table form-batch-table'),
                    h::thead(h::tr($headItems)),
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
        list($mode, $minRows, $maxRows) = $this->prop(array('mode', 'minRows', 'maxRows'));

        $props[] = setClass('form-batch');
        $props[] = set('data-mode', $mode);
        $props[] = set('data-min-rows', $minRows);
        $props[] = set('data-max-rows', $maxRows);

        return $props;
    }

    /**
     * Build content after current widget.
     */
    protected function buildAfter(): array
    {
        $after = parent::buildAfter();
        $after[] = zui::batchForm(set::_to('#' . $this->id()));
        return $after;
    }
}
