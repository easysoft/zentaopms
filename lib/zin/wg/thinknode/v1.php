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
            );
        }
        else
        {
            $item->options = null;

            if($item == 'node') return thinkNode(set($this->getRestProps()));
            if($type == 'transition') return thinkTransition(set($this->getRestProps()));
            if($type == 'question')
            {
                $questionType = zget($item, 'questionType', 'radio');
                if($questionType == 'radio') return thinkRadio(set($this->getRestProps()));
                if($questionType == 'checkbox') return thinkCheckbox(set($this->getRestProps()));
            }
        }
    }

    protected function build(): wg|array
    {
        list($item, $status) = $this->prop(array('item', 'status'));
        if(!$item) return array();

        return array(
            $status !== 'detail' ? array(
                div
                (
                    setClass('flex items-center justify-between'),
                    setStyle(array('height' => '48px', 'padding' => '0 48px', 'color' => 'var(--color-gray-950)')),
                    div
                    (
                        setClass('font-medium'),
                        data('lang.thinkwizard.step.nodeInfo'),
                    ),
                    btn
                    (
                        set::type('primary'),
                        set::btnType('submit'),
                        data('lang.save')
                    )
                ),
                h::hr()
            ) : null,
            $this->buildBody(),
            $this->children()
        );
    }
}
