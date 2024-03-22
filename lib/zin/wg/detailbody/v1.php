<?php
declare(strict_types=1);
namespace zin;

class detailBody extends wg
{
    protected static array $defineProps = array(
        'isForm?: bool=false'
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

        $data      = data($app->getModuleName());
        $fields    = $app->control->appendExtendForm('info', $data);
        $extraMain = array();
        foreach($fields as $field)
        {
            $fieldControl = $field->control;
            $extraMain[] = section
            (
                $field->control == 'file' && $data->files ? fileList
                (
                    set::files($data->files),
                    set::extra($field->field),
                    set::fieldset(false),
                    set::showEdit(true),
                    set::showDelete(true)
                ) : null,
                set::title($field->name),
                formGroup
                (
                    set::id($field->field),
                    set::name($field->field . (is_array($fieldControl) && $fieldControl['control'] == 'checkList' ? '[]' : '' )),
                    set::required($field->required),
                    set::control($field->control),
                    set::items($field->items),
                    set::value($field->value)

                )
            );
        }
        return sectionList($extraMain);
    }

    protected function build()
    {
        global $app;

        $main      = $this->block('main');
        $side      = $this->block('side');
        $bottom    = $this->block('bottom');
        $floating  = $this->block('floating');
        $isForm    = $this->prop('isForm');
        $extraMain = $this->buildExtraMain();

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
                $side
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
            html($app->control->appendExtendCssAndJS())
        );
    }
}
