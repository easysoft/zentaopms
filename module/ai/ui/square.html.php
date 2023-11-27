<?php
declare(strict_types=1);

namespace zin;

$navItems = array();

if(count($categoryList) <= 9)
{
    foreach($categoryList as $key => $value)
    {
        $isActive = $category === $key;
        $navItems[] = array(
            'text'   => $value,
            'active' => $isActive,
            'url'    => createLink('ai', 'square', "category=$key#app=ai"),
            'badge'  => $isActive ? array('text' => $pager->recTotal, 'class' => 'size-sm rounded-full white') : null,
            'props'  => array('data-id' => $key)
        );
    }
}
else
{
    foreach(array_slice($categoryList, 0, 8) as $key => $value)
    {
        $isActive = $category === $key;
        $navItems[] = array(
            'text'   => $value,
            'active' => $isActive,
            'url'    => createLink('ai', 'square', "category=$key#app=ai"),
            'badge'  => $isActive ? array('text' => $pager->recTotal, 'class' => 'size-sm rounded-full white') : null,
            'props'  => array('data-id' => $key)
        );
    }

    $moreCategoryList = array_slice($categoryList, 8);
    $subItems = array();
    foreach($moreCategoryList as $key => $value)
    {
        $subItems[] = array(
            'text'   => $value,
            'active' => $key === $category,
            'url'    => createLink('ai', 'square', "category=$key#app=ai"),
            'attrs'  => array('data-id' => $key)
        );
    }
    $isActive = array_key_exists($category, $moreCategoryList);
    $navItems[] = array(
        'text'   => $isActive ? $moreCategoryList[$category] : $lang->ai->miniPrograms->more,
        'active' => $isActive,
        'type'   => 'dropdown',
        'caret'  => 'down',
        'badge'  => $isActive ? array('text' => $pager->recTotal, 'class' => 'size-sm rounded-full white') : null,
        'props'  => array('data-id' => $key),
        'items'  => $subItems,
    );
}

featureBar(set::items($navItems));

$miniProgramCard = function($miniProgram) use ($categoryList, $collectedIDs)
{
    global $config, $lang;

    list($iconName, $iconTheme) = explode('-', $miniProgram->icon);
    $star = in_array($miniProgram->id, $collectedIDs) ? 'star' : 'star-empty';
    $delete = $star === 'star' ? 'true' : 'false';

    return div(
        setClass('miniprogram-card'),
        div(
            setClass('program-content'),
            div(
                setClass('program-text'),
                div(
                    setClass('title'),
                    $miniProgram->name
                ),
                div(
                    setClass('desc'),
                    $miniProgram->desc
                )
            ),
            div(
                setClass('program-avatar'),
                btn(
                    setClass('btn-avatar'),
                    setStyle(array(
                        'width'            => '46px',
                        'height'           => '46px',
                        'border-radius'    => '50%',
                        'display'          => 'flex',
                        'justify-content'  => 'center',
                        'align-items'      => 'center',
                        'border'           => "1px solid {$config->ai->miniPrograms->themeList[$iconTheme][1]}",
                        'background-color' => "{$config->ai->miniPrograms->themeList[$iconTheme][0]}"
                    )),
                    html($config->ai->miniPrograms->iconList[$iconName]),
                )
            )
        ),
        div(
            setClass('program-actions'),
            div(
                setClass('badge'),
                $categoryList[$miniProgram->category]
            ),
            btn(
                setClass('ghost btn-star'),
                setData('url', createLink('ai', 'collectMiniProgram', "appID={$miniProgram->id}&delete={$delete}")),
                on::click('window.aiSquare.handleStarBtnClick'),
                html(html::image("static/svg/{$star}.svg", "class='$star'")),
                $lang->ai->miniPrograms->collect
            )
        )
    );
};

div(
    setClass('miniprogram-container'),
    array_map($miniProgramCard, $miniPrograms),
);

div(
    setClass('pager-container'),
    pager()
);

render();
