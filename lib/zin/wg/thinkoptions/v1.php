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
 * 思引师选项部件类
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
        'name?: string="fields"',           // 步骤输入框作为表单项的名称。
        'data?: array',                     // 默认值。
        'stepText?: string',                // 步骤文本。
        'sameLevelText?: string',           // 同级文本。
        'deleteStepTip?: string',           // 有子层级禁用删除提示。
        'showOther?: bool=true',            // 是否展示启用其他。
        'enableOther?: bool=false',         // 是否启用其他。
        'otherName?: string="enableOther"', // 启用其他的 name。
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
        global $lang, $app;
        $app->loadLang('thinkstep');
        $id            = $this->prop('id') ? $this->prop('id') : $this->gid;
        $deleteStepTip = $this->prop('deleteStepTip', $lang->thinkstep->deleteOptionTip);

        list($enableOther, $otherName, $showOther) = $this->prop(array('enableOther', 'otherName', 'showOther'));

        return div
        (
            setID($id),
            setClass('think-options w-full'),
            div(set::className('think-options-body')),
            zui::thinkOptions
            (
                set::_to("#$id"),
                set::deleteStepTip($deleteStepTip),
                set::enterPlaceholder($lang->thinkstep->pleaseInput),
                set($this->props->pick(array('name', 'data')))
            ),
            $showOther ? div
            (
                setClass('w-full flex justify-between items-center h-8 rounded mt-1 ring-opacity-70 ring-gray-300'),
                setStyle(array('box-shadow' => 'var(--tw-ring-inset) 0 0 0 calc(1px + var(--tw-ring-offset-width)) var(--tw-ring-color)')),
                div
                (
                    setClass('h-full flex items-center flex-1'),
                    div
                    (
                        setClass('h-full flex items-center pl-2.5 opacity-80'),
                        setStyle(array('width' => '48px', 'background' => 'rgba(var(--color-gray-200-rgb), .6)')),
                        $lang->other
                    ),
                    div
                    (
                        setClass('h-full w-full flex items-center text-gray-400 pl-2.5'),
                        setStyle('background', 'rgba(244, 245, 247, .7)'),
                        $lang->thinkstep->pleaseInput,
                    ),
                ),
                div
                (
                    setClass('h-full flex items-center pr-2.5'),
                    setStyle(array('width' => '60px', 'background' => 'rgba(244, 245, 247, .7)')),
                    checkbox
                    (
                        set::name($otherName),
                        set::checked($enableOther),
                        set::text($lang->thinkstep->enable)
                    ),
                )
            ) : null
        );
    }
}
