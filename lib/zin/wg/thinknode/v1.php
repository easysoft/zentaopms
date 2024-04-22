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
        $itemOptions = json_decode($item->options, true);

        if($status == 'detail')
        {
            if($addType === 'input') return thinkInputDetail
            (
                set::type($item->type),
                set::title($item->title),
                set::desc($item->desc),
                set::item($item),
                set::required($itemOptions['required'])
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

            if($addType == 'transition' || $item->type == 'transition') return thinkTransition
            (
                set::title($isEdit ? $item->title : ''),
                set::desc($isEdit ? $item->desc: ''),
            );
            if($item->type == 'question' || $addType)
            {
                if($addType == 'radio' || $item->questionType == 'radio') return thinkRadio
                (
                    set::data(!$isEdit ? array() : explode(', ', $item->fields)),
                    set::title($isEdit ? $item->title : ''),
                    set::desc($isEdit ? $item->desc : ''),
                    set::enableOther($isEdit ? $item->enableOther : false),
                );
                if($addType == 'checkbox' || $item->questionType == 'checkbox') return thinkCheckbox
                (
                    set::data(!$isEdit ? array() : explode(', ', $item->fields)),
                    set::title($isEdit ? $item->title : ''),
                    set::desc($isEdit ? $item->desc : ''),
                    set::enableOther($isEdit ? $item->enableOther : false),
                    set::minCount($isEdit ? $item->minCount : ''),
                    set::maxCount($isEdit ? $item->maxCount : ''),
                );
                if($addType === 'input' || $item->questionType === 'input') return thinkInput(
                    set($isEdit ? array(
                        'title'     => $item->title,
                        'desc'      => $item->desc,
                        'isEdit'    => true,
                        'stepID'    => $item->id,
                        'required'  => $itemOptions['required'],
                        'submitUrl' => createLink('thinkwizard', 'design', "wizardID={$item->wizard}&stepID={$item->id}")
                    ) : array (
                        'submitUrl' => createLink('thinkwizard', 'design', "wizardID={$item->wizard}")
                    ))
                );
                if($addType == 'tableInput' || $item->questionType === 'tableInput')  return thinkTableInput(
                    set($isEdit ? array(
                        'title'        => $item->title,
                        'desc'         => $item->desc,
                        'isEdit'       => true,
                        'stepID'       => $item->id,
                        'required'     => $itemOptions['required'],
                        'requiredRows' => $itemOptions['requiredRows'],
                        'isSupportAdd' => $itemOptions['isSupportAdd'],
                        'canAddRows'   => $itemOptions['canAddRows'],
                        'rowsTitle'    => $itemOptions['fields'],
                        'submitUrl'    => createLink('thinkwizard', 'design', "wizardID={$item->wizard}&stepID={$item->id}")
                    ): array(
                        'submitUrl' => createLink('thinkwizard', 'design', "wizardID={$item->wizard}")
                    ))
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
        list($item, $status) = $this->prop(array('item', 'status'));
        if(!$item) return array();

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
                            data('lang.thinkwizard.step.nodeInfo'),
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
