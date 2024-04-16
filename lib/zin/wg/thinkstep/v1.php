<?php
declare(strict_types=1);
/**
 * The thinkStep widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yu Zhang<zhangyu@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */

namespace zin;

/**
 * 思引师表格填空题类型表单。
 * thinmory form fill in the blank question type form.
 *
 * @author Yu Zhang
 */

class thinkStep extends wg
{
    protected static array $defineProps = array(
        'title?: string',    // 标题
        'titleName: string', // 标题对应的name
        'desc?: string',     // 描述
        'descName: string',  // 描述对应的name
    );
    protected static array $defineBlocks = array(
        'fields' => array(),
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    private function buildNamePanel(): wg
    {
        global $lang;
        list($title, $titleName) = $this->prop(array('title', 'titleName'));
        return formRow
            (
            formGroup
            (
                set::width('full'),
                set::label($lang->thinkmodel->title),
                set::labelClass('required'),
                input
                (
                    setClass('is-required'),
                    set::value($title ? $title : ''),
                    set::name($titleName),
                    set::placeholder($lang->thinkmodel->titlePlaceholder),
                )
            )
        );
    }

    private function buildDescControl(): wg
    {
        global $lang;
        list($desc, $descName) = $this->prop(array('desc', 'descName'));
        return formRow
        (
            formGroup
            (
                set::width('full'),
                set::label($lang->thinkmodel->desc),
                editor
                (
                    setClass('desc'),
                    set::name($descName),
                    set::placeholder($lang->thinkmodel->descPlaceholder),
                    set::value($desc ? strip_tags(html_entity_decode($desc)) : ''),
                    set::rows(5)
                )
            )
        );
    }

    protected function build(): wg
    {
        return formPanel
        (
            set::grid(false),
            set::actions(array()),
            $this->buildNamePanel(),
            $this->buildDescControl(),
            $this->block('fields')
        );
    }
}
