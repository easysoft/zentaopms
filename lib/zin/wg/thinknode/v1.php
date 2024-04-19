<?php
declare(strict_types=1);
namespace zin;

class thinkNode  extends wg
{
    protected static array $defineProps = array(
        'item: object',
        'status?: string="detail"',
    );

    protected function buildBody(): wg|array
    {
        list($item, $status) = $this->prop(array('item', 'status'));

        if($status == 'detail')
        {
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
            $isEdit        = $status === 'edit' ? true : false;

            if($item->type == 'question')
            {
                $questionType = zget($item, 'questionType', 'radio');
                if($questionType == 'radio') return thinkRadio
                (
                    set::type($item->type),
                );
                if($questionType == 'checkbox') return thinkCheckbox(set($this->getRestProps()));
            }
            return thinkStep
            (
                set::type($item->type),
                set::title($item->title),
                set::isEdit($isEdit),
                set::desc($item->desc),
                set::stepID($item->id)
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
