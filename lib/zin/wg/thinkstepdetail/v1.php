<?php
declare(strict_types=1);
namespace zin;

/**
 * 思引师表格基础详情部件类。
 * thinmory basic detail widget class.
 */

class thinkStepDetail extends wg
{
    protected static array $defineProps = array(
        'item: object',
    );
    protected function detailHeading()
    {
        global $lang;

        $item = $this->prop('item');
        return div
        (
            div
            (
                setClass('flex items-start justify-between pt-6 pb-2 px-8 mx-4'),
                div
                (
                    setClass('text-md leading-6 font-medium text-current'),
                    $item->title
                ),
                div
                (
                    setClass('ml-2'),
                    setStyle(array('min-width' => '48px')),
                    btnGroup
                    (
                        btn
                        (
                            setClass('btn ghost text-gray w-5 h-5'),
                            set::icon('edit'),
                            set::url(createLink('thinkwizard', 'design', "wizardID={$item->wizard}&stepID={$item->id}&status=edit")),
                        ),
                        !$item->existNotNode ? btn
                        (
                            setClass('btn ghost text-gray w-5 h-5 ml-1 ajax-submit'),
                            set::icon('trash'),
                            setData('url', createLink('thinkstep', 'ajaxDelete', "stepID={$item->id}")),
                            setData('confirm',  $lang->thinkwizard->step->deleteTips[$item->type])
                        ) : btn
                        (
                            set(array(
                                'class'          => 'ghost w-5 h-5 text-gray opacity-50 ml-1',
                                'icon'           => 'trash',
                                'data-toggle'    => 'tooltip',
                                'data-title'     => $lang->thinkwizard->step->cannotDeleteNode,
                                'data-placement' => 'bottom-start',
                            ))
                        )
                    )
                )
            ),
            div
            (
                setClass('text-sm leading-6 py-0 px-8 mx-4 text-opacity-60 text-fore text-sm'),
                setStyle(array('margin-top' => '-30px')),
                section
                (
                    setClass(' break-words"'),
                    set::content($item->desc),
                    set::useHtml(true)
                )
            )
        );
    }

    protected function buildBody(): array
    {
        return array(
            $this->detailHeading(),
        );
    }
    protected function build(): array
    {
        return array
        (
            $this->buildBody(),
            $this->children()
        );
    }
}
