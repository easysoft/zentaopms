<?php
declare(strict_types=1);
namespace zin;

class workflowfield extends wg
{
    /**
     * 默认的组件属性。
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array
    (
        'fields: array',         // 工作流字段。
        'checkedFields: array',  // 选中字段。
        'mode: string',          // 应用场景。
        'module: string'         // 所属模块。
    );

    /**
     * 获取JS。
     * Get page JS.
     *
     * @static
     * @access public
     * @return string
     */
    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    /**
     * 获取CSS。
     * Get page CSS.
     *
     * @static
     * @access public
     * @return string
     */
    public static function getPageCss(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    /**
     * 构建组件。
     * Build.
     *
     * @access protected
     * @return zin
     */
    protected function build()
    {
        global $lang;

        $fields        = $this->prop('fields')        ? $this->prop('fields')        : array();
        $checkedFields = $this->prop('checkedFields') ? $this->prop('checkedFields') : array();
        $mode          = $this->prop('mode')          ? $this->prop('mode')          : 'canSetValue';
        $module        = $this->prop('module')        ? $this->prop('module')        : '';
        return div
        (
            setClass('workflowfield'),
            checkbox(set::name($mode), set::rootClass('mb-1'), set::text($lang->workflowfield->$mode), set::checked(!empty($checkedFields)), setData(array('on' => 'change', 'call' => 'changeMode', 'params' => 'event'))),
            div
            (
                setClass('fieldBox', empty($checkedFields) ? 'hidden' : ''),
                checkbox(set::rootClass('mb-1'), set::text($lang->workflowfield->fieldName), set::checked(!empty($checkedFields)), setData(array('on' => 'change', 'call' => 'changeAll', 'params' => 'event'))),
                divider(),
                checkList(set::name("fields[$module][]"), set::items($fields), set::value(array_keys($checkedFields))),
                input(setClass('hidden'), set::name('modules[]'), set::value($module))
            )
        );
    }
}
