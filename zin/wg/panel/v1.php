<?php
namespace zin;

class panel extends wg
{
    protected static $defineProps = array
    (
        'class?: string="rounded shadow ring-0 canvas"',
        'size?: string',
        'title?: string',
        'titleClass?: string',
        'titleProps?: array',
        'headingClass?: string',
        'headingProps?: array',
        'headingActions?: array',
        'bodyClass?: string',
        'bodyProps?: array',
        'footerActions?: array',
        'footerClass?: string',
        'footerProps?: array',
    );

    static $defineBlocks = array
    (
        'heading' => array(),
        'headingActions' => array('map' => 'toolbar'),
        'footer'  => array('map' => 'nav')
    );

    protected function buildHeadingActions()
    {
        $actionsBlock = $this->block('headingActions');
        $actions      = $this->prop('headingActions');

        if(empty($actions) && empty($actionsBlock)) return NULL;

        return div
        (
            setClass('panel-actions'),
            empty($actions) ? NULL : toolbar(set::items($actions)),
            $actionsBlock
        );
    }

    protected function buildHeading()
    {
        list($title, $size) = $this->prop(['title', 'size']);
        $headingBlock       = $this->block('heading');
        $actions            = $this->buildHeadingActions();

        if(empty($title) && empty($headingBlock) && empty($actions)) return NULL;

        return div
        (
            setClass('panel-heading', $this->prop('headingClass')),
            set($this->prop('headingProps')),
            empty($title) ? NULL : div(setClass('panel-title', $this->prop('titleClass', empty($size) ? NULL : "text-$size")), $title, set($this->prop('titleProps'))),
            $headingBlock,
            $actions
        );
    }

    protected function buildBody()
    {
        return div
        (
            setClass('panel-body'),
            $this->children()
        );
    }

    protected function buildFooter()
    {
        list($footerActions) = $this->prop(array('footerActions'));
        $footerBlock         = $this->block('footer');

        if(empty($footerActions) && empty($footerBlock)) return;

        return div
        (
            setClass('panel-footer', $this->prop('footerClass')),
            set($this->prop('footerProps')),
            $footerBlock,
            empty($footerActions) ? NULL : toolbar(set::items($footerActions))
        );
    }

    protected function build()
    {
        list($class, $size) = $this->prop(['class', 'size']);
        return div
        (
            setClass('panel', $class, empty($size) ? NULL : "size-$size"),
            set($this->getRestProps()),

            $this->buildHeading(),
            $this->buildBody(),
            $this->buildFooter()
        );
    }
}
