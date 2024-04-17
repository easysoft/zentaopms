<?php
declare(strict_types=1);
/**
 * The thinkOptions widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zemei Wang<wangzemei@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;

require_once dirname(__DIR__) . DS . 'input' . DS . 'v1.php';

/**
 * 思引师选项（thinkOptions）部件类
 * The thinkOptions widget class
 */
class thinkOptions extends wg
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array
    (
        'id?: string="$GID"',               // 组件根元素的 ID。
        'name?: string="options"',          // 步骤输入框作为表单项的名称。
        'data?: array',                     // 默认值。
        'stepText?: string',                // 步骤文本。
        'sameLevelText?: string',           // 同级文本。
        'expectDisabledTip?: string',       // 预期输入框禁用提示。
        'deleteStepTip?: string',           // 有子层级禁用删除提示。
        'dragNestedTip?: string',           // 拖拽超出提示。
        'enableOther?: boolean=true',       // 是否展示启用其他。
        'otherName?: string="enableOther"',               // 启用其他的 name。
    );

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    /**
     * Build the widget.
     *
     * @access protected
     * @return mixed
     */
    protected function build()
    {
        $id                = $this->prop('id') ? $this->prop('id') : $this->gid;
        $expectDisabledTip = $this->prop('expectDisabledTip', data('lang.testcase.expectDisabledTip'));
        $deleteStepTip     = $this->prop('deleteStepTip', data('lang.testcase.deleteStepTip'));
        $dragNestedTip     = $this->prop('dragNestedTip', data('lang.testcase.dragNestedTip'));

        list($enableOther, $otherName) = $this->prop(array('enableOther', 'otherName'));

        return div
        (
            setID($id),
            setClass('think-options w-full'),
            div
            (
                set::className('think-options-body')
            ),
            zui::thinkOptions
            (
                set::_to("#$id"),
                set::expectDisabledTip($expectDisabledTip),
                set::deleteStepTip($deleteStepTip),
                set::dragNestedTip($dragNestedTip),
                set::enterPlaceholder(data('lang.thinkwizard.step.pleaseInput')),
                set($this->props->pick(array('name', 'data')))
            ),
            $enableOther ? div
            (
                setClass('w-full flex justify-between items-center h-8 px-2.5 rounded mt-1'),
                setStyle(array('background' => 'rgba(242, 244, 247, .7)', '--tw-ring-color' => 'rgba(var(--color-gray-300-rgb), .7)', 'box-shadow' => 'var(--tw-ring-inset) 0 0 0 calc(1px + var(--tw-ring-offset-width)) var(--tw-ring-color)')),
                div
                (
                    setClass('flex items-center'),
                    div(setStyle(array('width' => '44px', 'color' => '#5E626D')), data('lang.other')),
                    div(setStyle(array('color' => 'var(--color-gray-400)')), data('lang.thinkwizard.step.pleaseInput')),
                ),
                checkbox(set::name($otherName), set::text(data('lang.thinkwizard.step.enable'))),
            ): null
        );
    }
}
