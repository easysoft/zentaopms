<?php
declare(strict_types=1);
namespace zin;

class panel extends wg
{
    protected static array $defineProps = array(
        'id?: string',
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
        'footerProps?: array',      // 底部属性。
        'container?: bool'          // 是否使用 Container 层。
    );

    protected static array $defineBlocks = array(
        'heading'        => array(),
        'headingActions' => array('map' => 'toolbar'),
        'titleSuffix'    => array(),
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

    protected function buildContainer(): array|wg
    {
        $content = func_get_args();
        if(!$this->prop('container')) return $content;
        return div(setClass('container'), $content);
    }

    protected function buildHeading(): ?wg
    {
        list($title, $size) = $this->prop(array('title', 'size'));
        $headingBlock       = $this->block('heading');
        $actions            = $this->buildHeadingActions();

        if(empty($title) && empty($headingBlock) && empty($actions)) return null;

        return div
        (
            setClass('panel-heading', $this->prop('headingClass')),
            set($this->prop('headingProps')),
            $this->buildContainer
            (
                empty($title) ? null : div
                (
                    setClass('panel-title', $this->prop('titleClass', empty($size) ? null : "text-$size")),
                    $this->prop('titleIcon') ? icon($this->prop('titleIcon')) : null,
                    set($this->prop('titleProps')),
                    $title,
                    $this->block('titleSuffix')
                ),
                $headingBlock,
                $actions
            )
        );
    }

    protected function buildBody(): wg
    {
        list($bodyClass, $bodyProps) = $this->prop(array('bodyClass', 'bodyProps'));
        return div
        (
            setClass('panel-body', $bodyClass),
            set($bodyProps),
            $this->buildContainer($this->children())
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
            $this->buildContainer
            (
                $footerBlock,
                empty($footerActions) ? null : toolbar(set::items($footerActions))
            )
        );
    }

    protected function buildProps(): array
    {
        list($id, $class, $size, $shadow) = $this->prop(array('id', 'class', 'size', 'shadow'));
        return array(setID($id), setClass('panel', $class, empty($size) ? null : "size-$size", $shadow ? 'shadow' : null));
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
