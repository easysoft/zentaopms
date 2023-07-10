<?php
declare(strict_types=1);
namespace zin;

class modalHeader extends wg
{
    protected static array $defineProps = array(
        'title?: string',
        'titleClass?: string',
        'entityText?: string',
        'entityID?: int'
    );

    protected static array $defineBlocks = array(
        'suffix' => array()
    );

    protected function created()
    {
        $title      = \initPageTitle();
        $entityText = '';
        $entityID   = 0;

        global $app;
        $module = $app->getModuleName();
        $object = data($module);
        if(!empty($object))
        {
            $entity = \initPageEntity($object);
            if(!empty($entity)) list($entityText, $entityID) = $entity;
        }

        $this->setDefaultProps(array('title' => $title, 'entityText' => $entityText, 'entityID' => $entityID));
    }

    protected function build()
    {
        list($title, $entityText, $entityID) = $this->prop(array('title', 'entityText', 'entityID'));

        $header = array
        (
            $title ? span
            (
                $title,
                set::class('pl-3'),
                set::class($this->prop('titleClass')),
            ) : null,
            ($entityText || $entityID) ? entityLabel
            (
                set::level(1),
                $entityText ? set::text($entityText) : null,
                $entityID ? set::entityID($entityID) : null,
                set::reverse(true),
            ) : null,
            $this->block('suffix')
        );

        if(isAjaxRequest('modal')) return $header;

        return div
        (
            set::class('modal-header panel-form rounded-md canvas mx-auto'),
            $header
        );
    }
}
