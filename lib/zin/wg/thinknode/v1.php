<?php
declare(strict_types=1);
namespace zin;

class thinkNode  extends wg
{
    protected static array $defineProps = array(
        'item: object',
        'status?: string="detail"',
        'addType?: string',
    );

    protected function buildBody(): wg|array
    {
        list($item, $status, $addType) = $this->prop(array('item', 'status', 'addType'));

        if($status == 'detail')
        {
            if($item->questionType === 'input') return thinkInputDetail
            (
                set::type($item->type),
                set::title($item->title),
                set::desc($item->desc),
                set::item($item),
                set::required($item->required)
            );
            if($item->questionType === 'tableInput') return thinkTableInputDetail
            (
                set::type($item->type),
                set::title($item->title),
                set::desc($item->desc),
                set::item($item),
                set::required($item->required),
                set::rowsTitle( explode(', ', $item->fields)),
            );
            return thinkStepDetail
            (
                set::type($item->type),
                set::title($item->title),
                set::desc($item->desc),
                set::item($item),
            );
        }
        else
        {
            $item->options = null;
            $isEdit = $status === 'edit' ? true : false;

            if($addType == 'transition' || $isEdit  && $item->type == 'transition') return thinkTransition
            (
                set::title($isEdit ? $item->title : ''),
                set::desc($isEdit ? $item->desc: ''),
            );
            if($isEdit && $item->type == 'question' || $addType)
            {
                if($addType == 'radio' || $isEdit && $item->questionType == 'radio') return thinkRadio
                (
                    set::data(!$isEdit ? array() : explode(', ', $item->fields)),
                    set::title($isEdit ? $item->title : ''),
                    set::desc($isEdit ? $item->desc : ''),
                    set::enableOther($isEdit ? $item->enableOther : false),
                );
                if($addType == 'checkbox' || $isEdit && $item->questionType == 'checkbox') return thinkCheckbox
                (
                    set::data(!$isEdit ? array() : explode(', ', $item->fields)),
                    set::title($isEdit ? $item->title : ''),
                    set::desc($isEdit ? $item->desc : ''),
                    set::enableOther($isEdit ? $item->enableOther : false),
                    set::minCount($isEdit ? $item->minCount : ''),
                    set::maxCount($isEdit ? $item->maxCount : ''),
                );
                if($addType === 'input' || $isEdit && $item->questionType == 'input') return thinkInput(
                    set::title($isEdit ? $item->title : ''),
                    set::desc($isEdit ? $item->desc : ''),
                    set::required($isEdit ? $item->required : false),
                    set::type('question')
                );
                if($addType == 'tableInput' || $isEdit && $item->questionType == 'tableInput')  return thinkTableInput(
                    set::title($isEdit ? $item->title : ''),
                    set::desc($isEdit ? $item->desc : ''),
                    set::required($isEdit ? $item->required : false),
                    set::type('question'),
                    set::requiredRows($isEdit ? $item->requiredRows : 1),
                    set::isSupportAdd($isEdit ? $item->isSupportAdd : false),
                    set::canAddRows($isEdit ? $item->canAddRows : 1),
                    set::rowsTitle(!$isEdit ? null : explode(', ', $item->fields))
                );
            }
            if($isEdit) return thinkStep
            (
                set::type($item->type),
                set::title($item->title),
                set::isEdit($isEdit),
                set::desc($item->desc),
                set::stepID($item->id),
                set::submitUrl(createLink('thinkwizard', 'design', "wizardID={$item->wizard}&&stepID={$item->id}"))
            );
        }
    }

    protected function build(): wg|array
    {
        global $lang;
        list($item, $status, $addType) = $this->prop(array('item', 'status', 'addType'));
        if(!$item) return array();

        $title = isset($item->id) && !$addType ? ($item->type == 'question' ? $lang->thinkwizard->step->editTitle[$item->questionType] : $lang->thinkwizard->step->editTitle[$item->type]) : $lang->thinkwizard->step->addTitle[$addType];

        return array(
            div
            (
                setClass('relative'),
                $status !== 'detail' ? array(
                    div
                    (
                        setClass('flex items-center'),
                        setStyle(array('height' => '48px', 'padding' => '0 48px', 'color' => 'var(--color-gray-950)')),
                        div
                        (
                            setClass('font-medium'),
                            $title
                        )
                    ),
                    h::hr()
                ) : null,
                $this->buildBody(),
                $this->children()
            )
        );
    }
}
