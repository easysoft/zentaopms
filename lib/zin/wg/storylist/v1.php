<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'entitylist' . DS . 'v1.php';

class storyList extends entitylist
{
    protected static array $defaultProps = array
    (
        'type' => 'story'
    );

    protected function getItem(object $entity): array
    {
        $item = array
        (
            'innerClass'   => 'px-0 relative group',
            'leading'      => array(),
            'innerTag'     => 'div',
            'titleClass'   => 'flex gap-2 items-center flex-auto min-w-0',
            'textClass'    => 'flex-none',
            'actionsClass' => $this->compact ? 'absolute top-0.5 right-0' : null,
            'hint'         => $entity->title,
            'title'        => span(setClass('text-clip'), $entity->title),
            'leading'      => idLabel::create($entity->id)
        );

        if(hasPriv($entity->type, 'view'))
        {
            $item['titleAttrs'] = array('data-toggle' => 'modal', 'data-size' => 'lg');
            $item['url']        = createLink($entity->type, 'view', "id=$entity->id");
        }

        return $item;
    }

    protected function getItems()
    {
        global $lang;
        $items = $this->prop('items', array());
        $list  = array();

        foreach($items as $key => $entity)
        {
            if(is_object($entity))
            {
                $list[] = $this->getItem($entity);
            }
            elseif(is_array($entity))
            {
                $list[] = array('id' => $key, 'title' => $lang->story->typeList[$key]);
                foreach($entity as $subEntity)
                {
                    $list[] = $this->getItem($subEntity);
                }
            }
        }

        return $list;
    }
}
