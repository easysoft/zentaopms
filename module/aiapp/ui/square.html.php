<?php
declare(strict_types=1);

namespace zin;

$navItems = array();
$showTag = in_array($category, array('collection', 'discovery', 'latest'));

if(count($categoryList) <= 9)
{
    foreach($categoryList as $key => $value)
    {
        $isActive = $category === $key;
        $navItems[] = array(
            'text'   => $value,
            'active' => $isActive,
            'url'    => createLink('aiapp', 'square', "category=$key"),
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
            'url'    => createLink('aiapp', 'square', "category=$key"),
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
            'url'    => createLink('aiapp', 'square', "category=$key"),
            'attrs'  => array('data-id' => $key)
        );
    }
    $isActive = array_key_exists($category, $moreCategoryList);
    $navItems[] = array(
        'text'   => $isActive ? $moreCategoryList[$category] : $lang->aiapp->more,
        'active' => $isActive,
        'type'   => 'dropdown',
        'caret'  => 'down',
        'badge'  => $isActive ? array('text' => $pager->recTotal, 'class' => 'size-sm rounded-full white') : null,
        'props'  => array('data-id' => $key),
        'items'  => $subItems,
    );
}

featureBar(set::items($navItems));

$miniProgramCard = function($miniProgram) use ($categoryList, $collectedIDs, $showTag)
{
    global $config, $lang;

    list($iconName, $iconTheme) = explode('-', $miniProgram->icon);
    $star = in_array($miniProgram->id, $collectedIDs) ? 'star' : 'star-empty';
    $delete = $star === 'star' ? 'true' : 'false';

    $starBtn = common::hasPriv('aiapp', 'collectMiniProgram')
        ? btn(
            set::size('md'),
            setClass('ghost btn-star'),
            setData('url', createLink('aiapp', 'collectMiniProgram', "appID={$miniProgram->id}&delete={$delete}")),
            on::click('window.aiSquare.handleStarBtnClick'),
            html(html::image("static/svg/{$star}.svg", "class='$star'")),
            $lang->aiapp->collect
        )
        : null;

    return a(
        common::hasPriv('aiapp', 'view') ? set::href(createLink('aiapp', 'view', "id={$miniProgram->id}")) : null,
        setClass('miniprogram-card'),
        div(
            setClass('program-content'),
            div(
                setClass('program-text'),
                div(
                    setClass('title'),
                    set::title($miniProgram->name),
                    $miniProgram->name
                ),
                div(
                    setClass('desc'),
                    set::title($miniProgram->desc),
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
                setClass(array('invisible' => !$showTag)),
                $categoryList[$miniProgram->category]
            ),
            $starBtn
        )
    );
};

div(
    setClass('miniprogram-container'),
    array_map($miniProgramCard, $miniPrograms),
);

div(
    setClass('pager-container'),
    pager(set(usePager()))
);

render();
