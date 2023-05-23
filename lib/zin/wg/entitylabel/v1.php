<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'label' . DS . 'v1.php';

class entityLabel extends wg
{
    protected static $defineProps = array(
        'entityID?: string|int', // 实体编号
        'level?: string|int',    // 标题层级
        'text: string'           // 实体文本
    );

    protected function onAddChild(mixed $child): mixed
    {
        if(is_string($child) && !$this->props->has('text'))
        {
            $this->props->set('text', $child);
            return false;
        }
    }

    private function buildEntityID(): ?wg
    {
        $entityID = $this->prop('entityID');
        if(!isset($entityID)) return null;

        return new label
        (
            setClass('justify-center rounded-full mr-2 px-1.5 h-3.5'),
            $entityID
        );
    }

    private function buildEntityName(): wg
    {
        $text  = $this->prop('text');
        $level = $this->prop('level');

        return div
        (
            setClass("article-h$level"),
            $text
        );
    }

    protected function build(): wg
    {
        $entityID = $this->buildEntityID();
        $entityName = $this->buildEntityName();
        return div
        (
            setClass('entity-label', 'flex', 'items-center'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            $entityID,
            $entityName
        );
    }
}
