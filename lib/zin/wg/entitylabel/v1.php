<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'label' . DS . 'v1.php';

class entityLabel extends wg
{
    protected static $defineProps = array(
        'entityID?: string|int', // 实体编号
        'level?: string|int',    // 标题层级
        'text: string',          // 实体文本
        'reverse?: bool=false',  // 编号与文本是否交换顺序
        'textClass?: string',    // 文本样式类
        'idClass?: string'       // 编号样式类
    );

    protected static $defineBlocks = array(
        'suffix' => array()
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
        $entityID  = $this->prop('entityID');
        $className = $this->prop('idClass');
        if(!isset($entityID)) return null;

        return new label
        (
            setClass('justify-center rounded-full px-1.5 h-3.5', $className),
            $entityID
        );
    }

    private function buildEntityName(): wg
    {
        $text      = $this->prop('text');
        $level     = $this->prop('level');
        $className = $this->prop('className');

        return div
        (
            setClass("article-h$level", $className),
            $text
        );
    }

    protected function build(): wg
    {
        $reverse    = $this->prop('reverse');
        $suffix     = $this->block('suffix');
        $entityID   = $this->buildEntityID();
        $entityName = $this->buildEntityName();
        return div
        (
            setClass('entity-label', 'flex', 'items-center', 'gap-x-1'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            $reverse ? array($entityName, $entityID) : array($entityID, $entityName),
            $suffix
        );
    }
}
