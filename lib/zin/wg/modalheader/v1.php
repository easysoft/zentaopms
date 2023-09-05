<?php
declare(strict_types=1);
namespace zin;

class modalHeader extends wg
{
    protected static array $defineProps = array(
        'inModal?: bool',
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

    protected function build(): wg|array
    {
        list($title, $entityText, $entityID, $inModal) = $this->prop(array('title', 'entityText', 'entityID', 'inModal'));
        if(empty($inModal)) $inModal = false;

        $header = h::div
        (
            setClass('flex items-center'),
            $title ? div
            (
                $title,
                set::className($this->prop('titleClass')),
            ) : null,
            ($entityText || $entityID) ? entityLabel
            (
                set::level(1),
                setClass('pl-2'),
                $entityText ? set::text($entityText) : null,
                $entityID ? set::entityID($entityID) : null,
                set::reverse(true),
            ) : null,
            $this->block('suffix')
        );

        if(isAjaxRequest('modal') || $inModal) return $header;

        return h::div
        (
            set::className('modal-header panel-form rounded-md canvas mx-auto'),
            set::style(array('margin-bottom' => '0px')),
            $header
        );
    }
}
