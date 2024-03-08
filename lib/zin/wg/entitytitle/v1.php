<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'idlabel' . DS . 'v1.php';

class entityTitle extends wg
{
    protected static array $defineProps = array
    (
        'id?: string|int',                // 对象 ID。
        'idClass?: array|string',         // ID 类名。
        'title?: string',                 // 标题文本。
        'inline?: bool',                  // 是否启用内联样式。
        'url?: string|bool',              // 标题链接。
        'object?: object',                // 对象。
        'deleted?: bool',                 // 是否已删除。
        'type?: string',                  // 对象类型。
        'color?: string',                 // 颜色。
        'linkProps?: array',              // 链接属性。
        'titleProps?: array',             // 标题属性。
        'titleClass?: array|string',      // 标签类名。
        'joiner?: string="/"',            // 连接符。
        'joinerClass?: array|string',     // 连接符类名。
        'parentId?: string|int',          // 父级对象 ID。
        'parentTitle?: string',           // 父级标题文本。
        'parentUrl?: string|bool',        // 父级标题链接。
        'parent?: object',                // 父级对象。
        'parentType?: string',            // 父级对象类型。
        'parentColor?: string',           // 父级颜色。
        'parentClass?: array|string',     // 父级类名。
        'parentTitleProps?: string',      // 父级标题属性。
        'parentTitleClass?: array|string' // 父级标签类名。
    );

    protected static array $defineBlocks = array
    (
        'prefix' => array(),
        'suffix' => array()
    );

    protected function created()
    {
        $object = $this->prop('object');
        if($object)
        {
            if(!$this->hasProp('id'))      $this->setProp('id', $object->id);
            if(!$this->hasProp('title'))   $this->setProp('title', isset($object->title) ? $object->title : $object->name);
            if(!$this->hasProp('url'))     $this->setProp('url', $object->url);
            if(!$this->hasProp('color'))   $this->setProp('color', $object->color);

            if(!$this->hasProp('deleted') && isset($object->deleted)) $this->setProp('deleted', $object->deleted);
            if(!$this->hasProp('parent') && isset($object->parent))  $this->setProp('parent', $object->parent);
        }

        $parent = $this->prop('parent');
        if($parent)
        {
            if(!$this->hasProp('parentId'))    $this->setProp('parentId', $parent->id);
            if(!$this->hasProp('parentTitle')) $this->setProp('parentTitle', isset($parent->title) ? $parent->title : $parent->name);
            if(!$this->hasProp('parentUrl'))   $this->setProp('parentUrl', $parent->url);
            if(!$this->hasProp('parentColor')) $this->setProp('parentColor', $parent->color);
        }
    }

    protected function buildTitle()
    {
        global $lang;

        list($id, $title, $url, $type, $color, $titleProps, $titleClass, $deleted) = $this->prop(array('id', 'title', 'url', 'type', 'color', 'titleProps', 'titleClass', 'deleted'));

        if($url === true && $type) $url = createLink($type, 'view', $type . 'ID={id}');
        if(is_string($url) && $id) $url = str_replace('{id}', "$id", $url);

        return array
        (
            $id ? idLabel::create
            (
                $id,
                array('class' => array($this->prop('idClass'), $this->prop('inline') ? 'mr-2' : '')),
            ) : null,
            is_string($url) ?
                a(
                    setClass('entity-title-link', $titleClass),
                    set::href($url),
                    set($titleProps),
                    set($this->prop('linkProps')),
                    $color ? setStyle('color', $color) : null,
                    $title
                ) : span
                (
                    setClass('entity-title-text', $titleClass),
                    set($titleProps),
                    $color ? setStyle('color', $color) : null,
                    $title
                ),
            $deleted ? span(setClass('label danger'), $lang->deleted) : null
        );
    }

    protected function buildParentTitle()
    {
        $parentTitle = $this->prop('parentTitle');
        if(!isset($parentTitle)) return null;

        list($parentID, $parent, $parentUrl, $parentType, $parentColor, $parentClass, $parentTitleProps, $parentTitleClass, $joiner, $joinerClass) = $this->prop(array('parentId', 'parent', 'parentUrl', 'parentType', 'parentColor', 'parentClass', 'parentTitleProps', 'parentTitleClass', 'joiner', 'joinerClass'));

        return array
        (
            new entityTitle
            (
                setClass($parentClass),
                set::object($parent),
                set::id($parentID),
                set::title($parentTitle),
                set::url($parentUrl),
                set::color($parentColor),
                set::type($parentType),
                set::titleProps($parentTitleProps),
                set::titleClass($parentTitleClass)
            ),
            $joiner ? span(setClass('entity-title-joiner', $joinerClass), $joiner) : null
        );
    }

    protected function build()
    {
        return div
        (
            setClass('entity-title', $this->prop('inline') ? '' : 'row items-center gap-2'),
            set($this->getRestProps()),
            $this->block('prefix'),
            $this->buildParentTitle(),
            $this->buildTitle(),
            $this->children(),
            $this->block('suffix')
        );
    }
}
