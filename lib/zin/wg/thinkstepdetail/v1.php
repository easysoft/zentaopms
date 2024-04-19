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
        'type?: string',       // 节点或者题型类型
        'title?: string',      // 标题
        'desc?: string',       // 描述
    );
    protected function detailHeading()
    {
        list($title, $desc) = $this->prop(array('title', 'desc'));
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
                    setClass('w-12 ml-2'),
                    setStyle(array('min-width' => '48px')),
                    a
                    (
                        icon('edit', setClass('border-0 mr-2 w-4'))
                    ),
                    a
                    (
                        icon('trash', setClass('border-0 w-4'))
                    )
                )
            ),
            div
            (
                setClass('text-sm leading-6'),
                setStyle(array('padding' => '0 48px')),
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
            $this->buildBody()
        );
    }
}
