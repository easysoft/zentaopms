<?php
declare(strict_types=1);
namespace zin;

class panel extends wg
{
    protected static array $defineProps = array(
        'class?: string="rounded ring-0 bg-canvas"', // 类名。
        'size?: "sm"|"lg"',         // 额外尺寸。
        'title?: string',           // 标题。
        'shadow?: bool=true',       // 阴影效果。
        'titleClass?: string',      // 标题类名。
        'titleProps?: array',       // 标题属性。
        'headingClass?: string',    // 标题栏类名。
        'headingProps?: array',     // 标题栏属性。
        'headingActions?: array[]', // 标题栏操作按钮。
        'bodyClass?: string',       // 主体类名。
        'bodyProps?: array',        // 主体属性。
        'footerActions?: array[]',  // 底部操作按钮。
        'footerClass?: string',     // 底部类名。
        'footerProps?: array'       // 底部属性。
    );

    protected static array $defineBlocks = array(
        'heading'        => array(),
        'headingActions' => array('map' => 'toolbar'),
        'footer'         => array('map' => 'nav')
    );

    protected function buildHeadingActions(): ?wg
    {
        $actionsBlock = $this->block('headingActions');
        $actions      = $this->prop('headingActions');

        if(empty($actions) && empty($actionsBlock)) return null;

        return div
        (
            setClass('panel-actions'),
            empty($actions) ? null : toolbar(set::items($actions)),
            $actionsBlock
        );
    }

    protected function buildHeading(): ?wg
    {
        list($title, $size) = $this->prop(['title', 'size']);
        $headingBlock       = $this->block('heading');
        $actions            = $this->buildHeadingActions();

        if(empty($title) && empty($headingBlock) && empty($actions)) return null;

        return div
        (
            setClass('panel-heading', $this->prop('headingClass')),
            set($this->prop('headingProps')),
            !empty($title) ? div
            (
                setClass('panel-title', $this->prop('titleClass', empty($size) ? null : "text-$size")),
                $this->prop('titleIcon') ? icon($this->prop('titleIcon')) : null,
                set($this->prop('titleProps')),
                $title
            ) : null,
            $headingBlock,
            $actions
        );
    }

    protected function buildBody(): wg
    {
        return div
        (
            setClass('panel-body ' . $this->prop('bodyClass')),
            set($this->prop('bodyProps')),
            $this->children()
        );
    }

    protected function buildFooter(): ?wg
    {
        list($footerActions) = $this->prop(array('footerActions'));
        $footerBlock         = $this->block('footer');

        if(empty($footerActions) && empty($footerBlock)) return null;

        return div
        (
            setClass('panel-footer', $this->prop('footerClass')),
            set($this->prop('footerProps')),
            $footerBlock,
            empty($footerActions) ? null : toolbar(set::items($footerActions))
        );
    }

    protected function buildProps(): array
    {
        list($class, $size, $shadow) = $this->prop(array('class', 'size', 'shadow'));
        return array(setClass('panel', $class, empty($size) ? null : "size-$size", $shadow ? 'shadow' : null));
    }

    protected function build(): wg
    {
        return div
        (
            $this->buildProps(),
            set($this->getRestProps()),
            $this->buildHeading(),
            $this->buildBody(),
            $this->buildFooter()
        );
    }
}
