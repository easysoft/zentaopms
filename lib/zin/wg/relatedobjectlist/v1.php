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
            const obj   = $(e.target).closest('li').find('.removeObject');
            const ul    = obj.closest('ul');
            const count = ul.closest('li').find('.listitem .item-content span').text();
            zui.Modal.confirm({message: `{$lang->custom->removeObjectTip}`, icon:'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
            {
                if(res)
                {
                    $.get(obj.attr('data-url'), function()
                    {
                        obj.closest('li').remove();
                        ul.closest('li').find('.listitem .item-content span').text(count - 1);
                        if(ul.find('li').length == 0) ul.closest('li').remove();
                    });
                }
            });
        };
        JS;
    }

    protected function getObjectItem(int $relatedObjectID, string $relatedObjectType, array $relatedObjectTitle, string $relationName, string $relationType): object
    {
        global $config,$lang,$app;
        $objectID   = $this->prop('objectID');
        $objectType = $this->prop('objectType');
        $title      = $relatedObjectTitle['title'];

        $app->control->loadModel('custom')->setConfig4Workflow();

        $type = $relatedObjectType == 'commit' ? 'repocommit' : $relatedObjectType;
        $item = new stdClass();
        $item->id         = $relatedObjectID;
        $item->title      = "#$relatedObjectID $title";
        $item->type       = $config->custom->relateObjectList[$type];
        $item->url        = !empty($relatedObjectTitle['url']) ? $relatedObjectTitle['url'] : null;
        $item->titleAttrs = !empty($relatedObjectTitle['url']) ? array('data-toggle' => 'modal', 'data-size' => 'lg') : null;

        if(hasPriv('custom', 'removeObjects') && $relationType != 'default')
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
                'hint'        => $lang->custom->removeObjects
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
            'title'      => $title,
            'hint'       => $title,
            'titleAttrs' => $item->titleAttrs,
            'leading'    => array('html' => wg(idLabel::create($item->type, array('class' => 'text-clip')))->render()),
            'url'        => $item->url,
            'actions'    => isset($item->actions) ? $item->actions : array()
        );
        return $info;
    }

    protected function created()
    {
        $relatedObjects = $this->prop('relatedObjects', data('relatedObjects'));
        if(!$relatedObjects) return;

        global $lang;
        $data = array();
        foreach($relatedObjects as $key => $relatedObjectList)
        {
            $explodeName  = explode('_', $key, 2);
            $relationType = $explodeName[0]; //default是内置关系，custom是用户自定义关系
            $relationName = $explodeName[1];

            $relatedObjectItems = array();
            foreach($relatedObjectList as $relatedObjectType => $relatedObjectPairs)
            {
                foreach($relatedObjectPairs as $id => $title) $relatedObjectItems[] = $this->getObjectItem($id, $relatedObjectType, $title, (string)$relationName, $relationType);
            }

            $data[$key] = array
            (
                'title'   => $relationName,
                'items'   => $relatedObjectItems,
                'content' => $relationType == 'default' ? "<i class='icon icon-help ml-2 mt-2 text-gray' data-title='{$lang->custom->defaultRelation}' data-toggle='tooltip' data-placement='right' data-type='white' data-class-name='text-gray border border-light'></i>" : ''
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
            $lang->custom->relateObject
        ) : null;
        return array($list, $btn);
    }
}
