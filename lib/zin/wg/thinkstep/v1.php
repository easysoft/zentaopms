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
 * 思引师基础表单。
 * thinmory basic forms.
 */

class thinkStep extends wg
{
    protected static array $defineProps = array(
        'type?: string="transition"', // 节点或者题型类型
        'title?: string',             // 标题
        'titleName?: string="title"', // 标题对应的name
        'desc?: string',              // 描述
        'descName?: string="desc"',   // 描述对应的name
        'isEdit?: bool=false',        // 是否编辑
        'stepID?: number=0',          // stepID
        'submitUrl: string',          // 提交表单url
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
                set::label($lang->thinkwizard->step->label->title),
                set::labelClass('required'),
                input
                (
                    setClass('is-required'),
                    set::value($title ? $title : ''),
                    set::name($titleName),
                    set::placeholder($lang->thinkwizard->step->inputContent),
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
                set::label($lang->thinkwizard->step->label->desc),
                editor
                (
                    setClass('desc'),
                    set::name($descName),
                    set::placeholder($lang->thinkwizard->step->pleaseInput),
                    html($desc ?? ''),
                    set::rows(5)
                )
            ),
            formHidden('type', $this->prop('type')),
            $this->prop('isEdit') ? formHidden('stepID', $this->prop('stepID')) : null,
        );
    }

    protected function buildBody(): array
    {
        return array(
            $this->buildNamePanel(),
            $this->buildDescControl(),
            $this->block('fields')
        );
    }

    protected function build(): wg
    {
        global $lang;
        $actions = array(array(
            'text'    => $lang->thinkmodel->save,
            'btnType' => 'submit',
            'class'   => 'primary h-7 ring-0 submit-btn px-4'
        ));

        return formPanel
        (
            setClass('think-step-panel'),
            set::bodyClass('think-step-form'),
            $this->prop('submitUrl') ? set::url($this->prop('submitUrl')) : null,
            set::grid(false),
            set::actions($actions),
            $this->buildBody()
        );
    }
}
