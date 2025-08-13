<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'idlabel' . DS . 'v1.php';

/**
 * @deprecated Use entityTitle instead.
 */
class entityLabel extends wg
{
    protected static array $defineProps = array(
        'entityID?: string|int', // 实体编号
        'level?: string|int',    // 标题层级
        'text?: string',         // 实体文本
        'reverse?: bool=false',  // 编号与文本是否交换顺序
        'textClass?: string',    // 文本样式类
        'idClass?: string',      // 编号样式类
        'href?: string',         // 实体链接
        'titlePrefix?: array',   // 标题前缀
        'labelProps?: array'     // 标签属性
    );

    protected static array $defineBlocks = array(
        'prefix' => array(),
        'suffix' => array()
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function onAddChild(mixed $child)
    {
        if(is_string($child) && !$this->props->has('text'))
        {
            $this->props->set('text', $child);
            return false;
        }
        else
        {
            $this->props->addToList('titlePrefix', $child);
            return false;
        }
    }

    private function buildEntityID(): ?node
    {
        $entityID  = $this->prop('entityID');
        $className = $this->prop('idClass');
        if(!isset($entityID)) return null;

        return idLabel::create($entityID, array('class' => $className));
    }

    private function buildEntityName(): node
    {
        $text        = $this->prop('text');
        $level       = $this->prop('level');
        $className   = $this->prop('textClass');
        $href        = $this->prop('href');
        $labelProps  = $this->prop('labelProps');
        $titlePrefix = $this->prop('titlePrefix');

        $titleClass = empty($level)
            ? 'entity-title'
            : "entity-title entity-title-$level";

        if(empty($href)) return span
        (
            setClass($titleClass, $className),
            set($labelProps),
            $titlePrefix,
            $text
        );

        return a
        (
            setClass($titleClass, $className),
            set::href($href),
            set($labelProps),
            $titlePrefix,
            $text
        );
    }

    protected function build()
    {
        $reverse    = $this->prop('reverse');
        $prefix     = $this->block('prefix');
        $suffix     = $this->block('suffix');
        $entityID   = $this->buildEntityID();
        $entityName = $this->buildEntityName();
        return div
        (
            setClass('entity-label space-x-1.5 overflow-hidden nowrap flex items-center'),
            set($this->getRestProps()),
            $prefix,
            $reverse ? array($entityName, ' ', $entityID) : array($entityID, ' ', $entityName),
            $suffix
        );
    }
}
