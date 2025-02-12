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
        $title = $this->prop('title');
        if(empty($title)) $title = \initPageTitle();

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
        list($title, $entityText, $entityID, $inModal) = $this->prop(array('title', 'entityText', 'entityID', 'inModal'));
        if(empty($inModal)) $inModal = false;

        $header = h::div
        (
            setClass('flex items-center overflow-hidden w-full'),
            $title ? div
            (
                $title,
                set::className($this->prop('titleClass'), 'whitespace-nowrap')
            ) : null,
            ($entityText || $entityID) ? entityLabel
            (
                set::level(1),
                setClass('pl-2 flex'),
                $entityText ? set::text($entityText) : null,
                $entityID ? set::entityID($entityID) : null,
                $entityID ? set::idClass('self-center') : null,
                set::reverse(true)
            ) : null,
            $this->block('suffix')
        );

        if(isAjaxRequest('modal') || $inModal) return $header;

        return h::div
        (
            set::className('modal-header panel-form rounded-md canvas mx-auto w-full'),
            set::style(array('margin-bottom' => '0px')),
            $header
        );
    }
}
