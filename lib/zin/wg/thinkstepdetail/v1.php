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
        'type?: string',       // 节点或者题型类型
        'title?: string',      // 标题
        'desc?: string',       // 描述
    );
    protected function detailHeading()
    {
        global $lang;

        list($item, $title, $desc) = $this->prop(array('item', 'title', 'desc'));
        return div
        (
            div
            (
                setClass('flex items-start justify-between'),
                setStyle(array('padding' => '24px 48px 8px')),
                div
                (
                    setClass('text-md leading-6 font-medium'),
                    setStyle(array('color' => '#313C52')),
                    $title
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
                        btn
                        (
                            setClass('btn ghost text-gray w-5 h-5 ml-1 ajax-submit'),
                            set::icon('trash'),
                            setData('url', createLink('thinkwizard', 'ajaxDeleteStep', "stepID={$item->id}")),
                            setData('confirm',  $lang->thinkwizard->step->deleteTips[$item->type])
                        )
                    )
                )
            ),
            div
            (
                setClass('text-sm leading-6'),
                setStyle(array('padding' => '0 48px', 'color' => '#9EA3B0', 'font-size' => '12px', 'margin-top' => '-30px')),
                section
                (
                    set::content($desc),
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
