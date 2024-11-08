<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'relatedlist' . DS . 'v1.php';

class relatedObjectList extends relatedList
{
    protected static array $defineProps = array
    (
        'objectID'       => '?int',                //主动关联对象ID
        'objectType'     => '?string',             //主动关联对象类型
        'relatedObjects' => '?array',              //被关联对象列表
        'browseType'     => '?string="byRelation"' //浏览类型 byRelation|byObject
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

    protected function getObjectItem(int $relatedObjectID, string $relatedObjectType, array $relatedObjectInfo, string $relationName, string $relationType, string $browseType): object
    {
        global $config, $lang, $app;
        $objectID   = $this->prop('objectID');
        $objectType = $this->prop('objectType');
        $title      = $relatedObjectInfo['title'];

        $itemType = $relationName;
        if($browseType == 'byRelation')
        {
            $relatedObjectTypeList = $config->custom->relateObjectList;
            $relatedObjectTypeList['commit'] = $config->custom->relateObjectList['repocommit'];
            if(!isset($relatedObjectTypeList[$relatedObjectType])) $relatedObjectTypeList[$relatedObjectType] = $app->loadLang($relatedObjectType)->$relatedObjectType->common;
            $itemType = $relatedObjectTypeList[$relatedObjectType];
        }
        $item = new stdClass();
        $item->id         = $relatedObjectID;
        $item->title      = "#$relatedObjectID $title";
        $item->type       = $itemType;
        $item->url        = !empty($relatedObjectInfo['url']) ? $relatedObjectInfo['url'] : null;
        $item->titleAttrs = !empty($relatedObjectInfo['url']) && !in_array($relatedObjectType, array('repocommit', 'commit', 'mr', 'release', 'build')) ? array('data-toggle' => 'modal', 'data-size' => 'lg') : null;

        if(hasPriv('custom', 'removeObjects'))
        {
            $disabled        = $relationType == 'default' ? 'disabled' : '';
            $removeObjectUrl = createLink('custom', 'removeObjects', "objectID=$objectID&objectType=$objectType&relationName=$relationName&relatedObjectID=$relatedObjectID&relatedObjectType=$relatedObjectType");

            $btn = array
            (
                'class'       => "removeObject text-primary $disabled",
                'icon'        => 'unlink',
                'data-on'     => 'click',
                'data-url'    => $removeObjectUrl,
                'data-params' => 'event',
                'data-call'   => $disabled ? null : 'removeObject',
                'hint'        => $disabled ? $lang->custom->defaultRelation : $lang->custom->removeObjects
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

        $browseType = $this->prop('browseType', data('browseType'));

        global $config, $app;
        $data = array();
        if($browseType == 'byRelation')
        {
            foreach($relatedObjects as $relationNameAndType => $relatedObjectList)
            {
                $nameAndType  = explode('_', $relationNameAndType, 2);
                $relationType = $nameAndType[0]; //default是内置关系，custom是用户自定义关系
                $relationName = $nameAndType[1];

                $relatedObjectItems = array();
                foreach($relatedObjectList as $relatedObjectType => $relatedObjectPairs)
                {
                    foreach($relatedObjectPairs as $relatedObjectID => $relatedObjectInfo) $relatedObjectItems[] = $this->getObjectItem($relatedObjectID, $relatedObjectType, $relatedObjectInfo, (string)$relationName, $relationType, $browseType);
                }

                $data[$relationNameAndType] = array
                (
                    'title'   => $relationName,
                    'items'   => $relatedObjectItems
                );
            }
        }
        if($browseType == 'byObject')
        {
            foreach($relatedObjects as $relatedObjectType => $relatedObjectList)
            {
                $relatedObjectItems = array();
                foreach($relatedObjectList as $relationNameAndType => $relatedObjectPairs)
                {
                    $nameAndType  = explode('_', $relationNameAndType, 2);
                    $relationType = $nameAndType[0]; //default是内置关系，custom是用户自定义关系
                    $relationName = $nameAndType[1];

                    foreach($relatedObjectPairs as $relatedObjectID => $relatedObjectInfo) $relatedObjectItems[] = $this->getObjectItem($relatedObjectID, $relatedObjectType, $relatedObjectInfo, (string)$relationName, $relationType, $browseType);
                }

                $relatedObjectTypePairs = $config->custom->relateObjectList;
                $relatedObjectTypePairs['commit'] = $config->custom->relateObjectList['repocommit'];
                if(!isset($relatedObjectTypePairs[$relatedObjectType])) $relatedObjectTypePairs[$relatedObjectType] = $app->loadLang($relatedObjectType)->$relatedObjectType->common;
                $data[$relatedObjectType] = array
                (
                    'title'   => $relatedObjectTypePairs[$relatedObjectType],
                    'items'   => $relatedObjectItems
                );
            }
        }

        $this->setProp('data', $data);
    }

    protected function build()
    {
        global $lang, $app;
        $list = parent::build();
        $list->add(set::hoverItemActions());

        $objectID       = $this->prop('objectID');
        $objectType     = $this->prop('objectType');
        $relatedObjects = $this->prop('relatedObjects', data('relatedObjects'));
        $btn = hasPriv('custom', 'relateObject') ? btn
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
        $graphBtn = hasPriv('custom', 'showRelationGraph') && !empty($relatedObjects) ? btn
        (
            set::url('custom', 'showRelationGraph', "objectID=$objectID&objectType=$objectType"),
            set::icon('treemap'),
            set::size('sm'),
            set::type('secondary'),
            setClass('my-2 pull-right'),
            setData(array('toggle' => 'modal', 'size' => 'lg')),
            setID('graphButton'),
            $app->loadLang('custom')->custom->relationGraph
        ) : null;
        return array($btn, $graphBtn, $list);
    }
}
