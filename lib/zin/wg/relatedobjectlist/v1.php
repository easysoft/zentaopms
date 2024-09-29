<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'relatedlist' . DS . 'v1.php';

class relatedObjectList extends relatedList
{
    protected static array $defineProps = array
    (
        'objectID'       => '?int',    //主动关联对象ID
        'objectType'     => '?string', //主动关联对象类型
        'relatedObjects' => '?array',  //被关联对象列表。
    );

    public static function getPageJS(): ?string
    {
        global $lang;
        return <<<JS
        window.removeObject = function(e)
        {
            const obj = $(e.target).closest('li').find('.removeObject');
            const ul  = obj.closest('ul');
            zui.Modal.confirm({message: `{$lang->removeObjectTip}`, icon:'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
            {
                if(res)
                {
                    $.get(obj.attr('data-url'), function()
                    {
                        obj.closest('li').remove();
                        if(ul.find('li').length == 0) ul.closest('li').remove();
                    });
                }
            });
        };
        JS;
    }

    protected function getObjectItem(int $relatedObjectID, string $relatedObjectType, string $relatedObjectTitle, string $relationName): object
    {
        global $config,$lang;
        $objectID   = $this->prop('objectID');
        $objectType = $this->prop('objectType');

        $item = new stdClass();
        $item->id    = $relatedObjectID;
        $item->title = "#$relatedObjectID $relatedObjectTitle";
        $item->type  = $config->custom->relateObjectList[$relatedObjectType];
        $item->url   = hasPriv($relatedObjectType, 'view') ? createLink($relatedObjectType, 'view', "objectID=$relatedObjectID") : false;

        if(hasPriv('custom', 'removeObjects'))
        {
            $removeObjectUrl = createLink('custom', 'removeObjects', "objectID=$objectID&objectType=$objectType&relationName=$relationName&relatedObjectID=$relatedObjectID&relatedObjectType=$relatedObjectType");

            $btn = array
            (
                'class'       => 'removeObject text-primary',
                'icon'        => 'unlink',
                'data-on'     => 'click',
                'data-url'    => $removeObjectUrl,
                'data-params' => 'event',
                'data-call'   => 'removeObject',
                'hint'        => $lang->removeObjects
            );
            $item->actions = array($btn);
        }

        return $item;
    }

    protected function getCommonItem(string $type, array $group, object $item): array
    {
        $title = '';
        if(isset($item->title)) $title = $item->title;
        if(isset($item->name))  $title = $item->name;
        $info = array
        (
            'title'   => $title,
            'hint'    => $title,
            'leading' => array('html' => wg(idLabel::create($item->type))->render()),
            'url'     => $item->url,
            'actions' => isset($item->actions) ? $item->actions : array()
        );

        $props = isset($group['props']) ? $group['props'] : array('titleAttrs' => array('data-toggle' => 'modal', 'data-size' => 'lg'));
        if($props) $info = array_merge($info, $props);

        return $info;
    }

    protected function created()
    {
        $relatedObjects = $this->prop('relatedObjects', data('relatedObjects'));
        if(!$relatedObjects) return;

        $data = array();
        foreach($relatedObjects as $relationName => $relatedObjectList)
        {
            $relatedObjectItems = array();
            foreach($relatedObjectList as $relatedObjectType => $relatedObjectPairs)
            {
                foreach($relatedObjectPairs as $id => $title) $relatedObjectItems[] = $this->getObjectItem($id, $relatedObjectType, $title, $relationName);
            }

            $data[$relationName] = array
            (
                'title' => $relationName,
                'items' => $relatedObjectItems
            );
        }

        $this->setProp('data', $data);
    }

    protected function build()
    {
        global $lang;
        $list = parent::build();
        $list->add(set::hoverItemActions());

        $objectID   = $this->prop('objectID');
        $objectType = $this->prop('objectType');
        $btn = hasPriv('custom', 'relateObject') ? new btn
        (
            set::url('custom', 'relateObject', "objectID=$objectID&objectType=$objectType&relatedObjectType=$objectType"),
            set::icon('plus'),
            set::size('sm'),
            set::type('secondary'),
            setClass('my-2'),
            setData(array('toggle' => 'modal', 'size' => 'lg')),
            setID('linkButton'),
            $lang->relatedObjects
        ) : null;
        return array($list, $btn);
    }
}
