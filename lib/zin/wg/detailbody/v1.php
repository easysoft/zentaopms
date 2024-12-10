<?php
declare(strict_types=1);
namespace zin;

class detailBody extends wg
{
    protected static array $defineProps = array(
        'isForm?: bool=false',
        'hasExtraMain?: bool=true' // 是否展示工作流扩展字段
    );

    protected static array $defineBlocks = array(
        'main' => array('map' => 'sectionList'),
        'side' => array('map' => 'detailSide'),
        'bottom' => array('map' => 'history,fileList'),
        'floating' => array('map' => 'floatToolbar')
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function buildExtraMain()
    {
        global $app;

        $app->control->loadModel('flow');
        $isForm    = $this->prop('isForm');
        $object    = data($app->getModuleName());
        $fields    = $app->control->appendExtendForm('info', $object);
        $extraMain = array();
        foreach($fields as $field)
        {
            $fieldValue = !$isForm ? $app->control->flow->getFieldValue($field, $object) : null;
            $extraMain[] = section
            (
                $field->control == 'file' && $object->files ? fileList
                (
                    set::files($object->files),
                    set::extra($field->field),
                    set::fieldset(false),
                    set::showEdit(true),
                    set::showDelete(true)
                ) : null,
                set::title($field->name),
                set::required($field->required),
                !$isForm ? (div(!empty($field->control['control']) && $field->control['control'] == 'editor' ? html($fieldValue) : $fieldValue)) : formGroup
                (
                    set::id($field->field),
                    set::name($field->field),
                    set::disabled((bool)$field->readonly),
                    set::control($field->control),
                    set::items($field->items),
                    set::value($field->value),
                    set::placeholder($field->placeholder)

                )
            );
        }
        return empty($extraMain) ? null : sectionList($extraMain);
    }

    protected function build()
    {
        global $app;

        $main         = $this->block('main');
        $side         = $this->block('side');
        $bottom       = $this->block('bottom');
        $floating     = $this->block('floating');
        $isForm       = $this->prop('isForm');
        $hasExtraMain = $this->prop('hasExtraMain');
        $extraMain    = $hasExtraMain ? $this->buildExtraMain() : null;

        if(!$isForm)
        {
            return div
            (
                setClass('detail-body rounded flex gap-1'),
                set($this->getRestProps()),
                div
                (
                    setClass('col gap-1 grow min-w-0'),
                    $main,
                    $extraMain,
                    $bottom,
                    empty($floating) ? null : center(setClass('pt-6 sticky bottom-0'), $floating)
                ),
                $side,
                html($app->control->appendExtendCssAndJS('', '', data($app->getModuleName())))
            );
        }

        return formBase
        (
            set::actionsClass('h-14 flex flex-none items-center justify-center shadow'),
            setClass('detail-body rounded col overflow-y-hidden bg-white'),
            set($this->getRestProps()),
            div
            (
                setClass('flex-auto overflow-y-auto'),
                div
                (
                    setClass('flex'),
                    setStyle('min-height', '100%'),
                    div
                    (
                        setClass('col grow min-w-0'),
                        $main,
                        $extraMain,
                        $bottom
                    ),
                    div
                    (
                        setClass('w-1'),
                        setStyle('background', 'var(--zt-page-bg)')
                    ),
                    $side
                )
            ),
            html($app->control->appendExtendCssAndJS('', '', data($app->getModuleName())))
        );
    }
}
