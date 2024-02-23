<?php
declare(strict_types=1);
/**
 * The dateRangePicker widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;

require_once dirname(__DIR__) . DS . 'datepicker' . DS . 'v1.php';

/**
 * 日期范围选择器（dateRangePicker）部件类
 * The date range picker widget class
 */
class dateRangePicker extends wg
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array
    (
        'beginName?: string',               // 开始日期表单项名称。
        'endName?: string',                 // 结束日期表单项名称。
        'beginValue?: string',              // 开始日期默认值。
        'endValue?: string',                // 结束日期默认值。
        'beginPlaceholder?: string',        // 开始日期占位文本。
        'endPlaceholder?: string',          // 结束日期占位文本。
        'beginDisabled?: bool',             // 开始日期禁用。
        'endDisabled?: bool',               // 结束日期禁用。
        'addon?: string="-"',               // 开始和结束日期之间的分隔符。
        'format?: string',                  // 日期格式。
        'required?: bool',                  // 是否必填。
        'begin?: array',                    // 开始日期选择器配置。
        'end?: array',                      // 结束日期选择器配置。
        'endMenu?: array|bool|jsCallback',  // 结束日期快捷设置菜单。
        'endList?: array|jsCallback'        // 结束日期快捷设置选项。
    );

    protected function buildBeginProps(): array
    {
        list($beginName, $beginValue, $beginPlaceholder, $format, $required, $begin, $beginDisabled) = $this->prop(array('beginName', 'beginValue', 'beginPlaceholder', 'format', 'required', 'begin', 'beginDisabled'));

        $props = array();
        $props['icon']        = '';
        $props['name']        = $beginName;
        $props['value']       = $beginValue;
        $props['placeholder'] = $beginPlaceholder;
        $props['format']      = $format;
        $props['required']    = $required;
        $props['disabled']    = $beginDisabled;

        return is_array($begin) ? array_merge($props, $begin) : $props;
    }

    protected function buildEndProps(): array
    {
        list($endName, $endValue, $endPlaceholder, $format, $required, $end, $endMenu, $endList, $endDisabled) = $this->prop(array('endName', 'endValue', 'endPlaceholder', 'format', 'required', 'end', 'endMenu', 'endList', 'endDisabled'));

        $id = "{$this->gid}_end";

        if($endMenu === true || (empty($endMenu) && !empty($endList))) $endMenu = array();
        if(is_array($endMenu) && !empty($endList))
        {
            if(is_array($endList))
            {
                $endMenu['items'] = jsCallback()
                    ->const('endList', $endList)
                    ->const('$ele', jsRaw("$('#$id').closest('.date-range-picker')"))
                    ->const('beginDate', jsRaw('zui.createDate($ele.find(".date-picker>input.pick-value").first().val())'))
                    ->do(<<<'JS'
                    return Object.keys(endList).map((key) =>
                    {
                        const date = zui.addDate(beginDate, key - 1);
                        return {text: endList[key], 'data-set-date': zui.formatDate(date, "yyyy-MM-dd")};
                    });
                    JS);
            }
            else
            {
                $endMenu['items'] = $endList;
            }
        }

        $props = array();
        $props['icon']        = '';
        $props['id']          = $id;
        $props['name']        = $endName;
        $props['value']       = $endValue;
        $props['placeholder'] = $endPlaceholder;
        $props['format']      = $format;
        $props['required']    = $required;
        $props['disabled']    = $endDisabled;
        $props['menu']        = $endMenu;
        $props['minDate']     = jsRaw(<<<JS
        () => zui.formatDate($('#$id').closest('.date-range-picker').find('.date-picker>input.pick-value').first().val(), 'yyyy-MM-dd')
        JS);

        return is_array($end) ? array_merge($props, $end) : $props;
    }

    /**
     * Build the widget.
     *
     * @access protected
     * @return mixed
     */
    protected function build()
    {
        $begin = new datePicker(set($this->buildBeginProps()));
        $end   = new datePicker(set($this->buildEndProps()));

        return div
        (
            setClass('date-range-picker center-row'),
            set($this->getRestProps()),
            $begin,
            div(setClass('addon px-2'), $this->prop('addon')),
            $end,
            $this->children()
        );
    }
}
