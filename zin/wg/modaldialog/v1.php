<?php
namespace zin;

class modalDialog extends wg
{
    static $defineProps = [
        'title?: string',
        'itemID?: number',
        'headerClass?: string',
        'headerProps?: array',
        'actions?: array',
        'closeBtn?: boolean|array=true',
        'footerActions?: array',
        'footerClass?: string',
        'footerProps?: array',
    ];

    static $defineBlocks = [
        'header' => [],
        'actions' => [],
        'footer' => ['map' => 'toolbar']
    ];

    protected function buildHeader()
    {
        $title       = $this->prop('title');
        $itemID      = $this->prop('itemID');
        $headerBlock = $this->block('header');

        if(empty($title) && empty($headerBlock)) return null;

        return div
        (
            setClass('modal-header', $this->prop('headerClass')),
            set($this->prop('headerProps')),
            empty($itemID) ? NULL : label($itemID, setStyle(['min-width' => '30px']), setClass('justify-center')),
            empty($title) ? NULL : div(setClass('modal-title'), $title),
            $headerBlock
        );
    }

    protected function buildActions()
    {
        list($actions, $closeBtn) = $this->prop(array('actions', 'closeBtn'));
        $actionsBlock = $this->block('actions');

        if(empty($actions) && empty($actionsBlock) && !$closeBtn) return;

        return div
        (
            setClass('modal-actions'),
            empty($actions) ? NULL : toolbar(set::items($actions)),
            $actionsBlock,
            $closeBtn ? btn
            (
                set('data-dismiss', 'modal'),
                set::square(true),
                is_array($closeBtn) ? set($closeBtn) : setClass('ghost'),
                span(setClass('close'))
            ) : NULL
        );
    }

    protected function buildFooter()
    {
        list($footerActions) = $this->prop(array('footerActions'));
        $footerBlock         = $this->block('footer');

        if(empty($footerActions) && empty($footerBlock)) return;

        return div
        (
            setClass('modal-footer', $this->prop('footerClass')),
            set($this->prop('footerProps')),
            $footerBlock,
            empty($footerActions) ? NULL : toolbar(set::items($footerActions))
        );
    }

    protected function buildBody()
    {
        return div
        (
            setClass('modal-body'),
            $this->children()
        );
    }

    protected function build()
    {
        return div
        (
            setClass('modal-dialog'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            div
            (
                setClass('modal-content'),
                $this->buildHeader(),
                $this->buildActions(),
                $this->buildBody(),
                $this->buildFooter()
            )
        );
    }
}
