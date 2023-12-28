<?php
declare(strict_types=1);
namespace zin;

class modalDialog extends wg
{
    protected static array $defineProps = array(
        'title?: string',
        'titleClass?: string',
        'size?: string|number',
        'condensed?: bool',
        'itemID?: int',
        'headerClass?: string',
        'headerProps?: array',
        'bodyClass?: string',
        'bodyProps?: array',
        'actions?: array',
        'closeBtn?: bool|array=true',
        'footerActions?: array',
        'footerClass?: string',
        'footerProps?: array',
        'rawContent?: bool',
        'autoLoad?: string'
    );

    protected static array $defineBlocks = array(
        'header' => array('map' => 'modalHeader'),
        'actions' => array(),
        'footer' => array('map' => 'toolbar')
    );

    protected function buildHeader()
    {
        $title       = $this->prop('title');
        $titleClass  = $this->prop('titleClass');
        $itemID      = $this->prop('itemID');
        $headerBlock = $this->block('header');

        if(empty($title) && empty($headerBlock)) return null;

        return div
        (
            setClass('modal-header', $this->prop('headerClass')),
            set($this->prop('headerProps')),
            empty($itemID) ? null : label($itemID, setStyle(['min-width' => '30px']), setClass('justify-center')),
            empty($title) ? null : div(setClass('modal-title', $titleClass), $title),
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
            empty($actions) ? null : toolbar(set::items($actions)),
            $actionsBlock,
            $closeBtn ? btn
            (
                set('data-dismiss', 'modal'),
                set::square(true),
                is_array($closeBtn) ? set($closeBtn) : setClass('ghost'),
                span(setClass('close'))
            ) : null
        );
    }

    protected function buildFooter()
    {
        $footerActions = $this->prop('footerActions');
        $footerBlock   = $this->block('footer');

        if(empty($footerActions) && empty($footerBlock)) return;

        return div
        (
            setClass('modal-footer', $this->prop('footerClass')),
            set($this->prop('footerProps')),
            $footerBlock,
            empty($footerActions) ? null : toolbar(set::items($footerActions))
        );
    }

    protected function buildBody()
    {
        $rawContent = $this->prop('rawContent', !zin::$rawContentCalled);
        return div
        (
            setClass('modal-body scrollbar-hover', $this->prop('bodyClass')),
            set($this->prop('bodyProps')),
            $this->children(),
            $rawContent ? rawContent() : null
        );
    }

    protected function setSize(): ?directive
    {
        $size = $this->prop('size');
        if(!$size) return null;
        if(is_string($size)) return set('data-size', $size);
        return setStyle('width', "{$size}px");
    }

    protected function getAutoLoad(): ?string
    {
        global $app;
        $autoLoad = $this->prop('autoLoad');
        if(!is_null($autoLoad)) return $autoLoad;

        if($app->rawMethod !== 'view') return null;
        $objectType = $app->rawModule;
        $objectID    = data($objectType . 'ID');
        if(empty($objectID))
        {
            $object = data($objectType);
            if(is_object($object) && isset($object->id))      $objectID = $object->id;
            elseif(is_array($object) && isset($object['id'])) $objectID = $object['id'];
        }
        if(empty($objectID)) return null;
        return "$objectType:$objectID";
    }

    protected function build(): wg
    {
        return div
        (
            setClass('modal-dialog', $this->prop('condensed') ? 'modal-condensed' : ''),
            set($this->getRestProps()),
            setData('auto-load', $this->getAutoLoad()),
            $this->setSize(),
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
