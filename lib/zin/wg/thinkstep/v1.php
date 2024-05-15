<?php
declare(strict_types=1);
namespace zin;

class thinkStep  extends wg
{
    protected static array $defineProps = array(
        'item: object',
        'action?: string="detail"',
        'addType?: string',
    );

    protected function buildQuestionBody(): array|wg
    {
        $item = $this->prop('item');
        if(property_exists($item, 'questionType') && $item->questionType === 'tableInput') return thinkTableInputDetail
        (
            set::item($item),
            set::required($item->required),
            set::rowsTitle(explode(', ', $item->fields)),
        );
        return array();

    }

    protected function buildBody(): wg|array
    {
        list($item, $action, $addType) = $this->prop(array('item', 'action', 'addType'));

        if($addType === 'input' || $item->questionType === 'input') return thinkInput
        (
            set::step($item),
            set::mode($action),
        );

        if($addType === 'radio' || $item->questionType === 'radio') return thinkRadio
        (
            set::step($item),
            set::mode($action),
        );

        if($addType === 'checkbox' || $item->questionType === 'checkbox') return thinkCheckbox
        (
            set::step($item),
            set::mode($action),
        );

        if($action == 'detail')
        {
            return thinkStepDetail
            (
                set::item($item),
                $this->buildQuestionBody()
            );
        }
        else
        {
            $item->options = null;
            $isEdit = $action === 'edit' ? true : false;


            if($addType === 'transition' || $isEdit && $item->type === 'transition') return thinkTransition
            (
                set::title($isEdit ? $item->title : ''),
                set::desc($isEdit ? $item->desc: ''),
            );
            if($isEdit && $item->type === 'question' || $addType)
            {
                if($addType === 'radio' || ($isEdit && $item->questionType === 'radio')) return thinkRadio
                (
                    set::data(!$isEdit ? null : explode(', ', $item->fields)),
                    set::title($isEdit ? $item->title : ''),
                    set::desc($isEdit ? $item->desc : ''),
                    set::required($isEdit ? $item->required : 0),
                    set::enableOther($isEdit ? $item->enableOther : false),
                );
                if($addType === 'checkbox' || ($isEdit && $item->questionType === 'checkbox')) return thinkCheckbox
                (
                    set::data(!$isEdit ? null : explode(', ', $item->fields)),
                    set::title($isEdit ? $item->title : ''),
                    set::desc($isEdit ? $item->desc : ''),
                    set::required($isEdit ? $item->required : 0),
                    set::enableOther($isEdit ? $item->enableOther : false),
                    set::minCount($isEdit && isset($item->minCount) ? $item->minCount : ''),
                    set::maxCount($isEdit && isset($item->maxCount) ? $item->maxCount : ''),
                );
                if($addType === 'input' || ($isEdit && $item->questionType === 'input')) return thinkInput(
                    set::title($isEdit ? $item->title : ''),
                    set::desc($isEdit ? $item->desc : ''),
                    set::required($isEdit ? $item->required : false),
                    set::type('question')
                );
                if($addType === 'tableInput' || ($isEdit && $item->questionType === 'tableInput'))  return thinkTableInput(
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
            if($isEdit) return thinkNode
            (
                set::type($item->type),
                set::title($item->title),
                set::desc($item->desc),
            );
        }
    }

    protected function build(): wg|array
    {
        global $lang;
        list($item, $action, $addType) = $this->prop(array('item', 'action', 'addType'));
        if(!$item) return array();

        $title = isset($item->id) && !$addType ? ($item->type == 'question' ? $lang->thinkwizard->step->editTitle[$item->questionType] : $lang->thinkwizard->step->editTitle[$item->type]) : $lang->thinkwizard->step->addTitle[$addType];

        return array(
            div
            (
                setClass($action == 'detail' ? 'relative pt-6 px-8 mx-4' : 'relative'),
                $action !== 'detail' ? array(
                    div
                    (
                        setClass('flex items-center text-gray-950 h-12 py-0 px-8 mx-4'),
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
