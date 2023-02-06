<?php
namespace zin;

\commonModel::setMainMenu();

$navItems = array();
foreach(\customModel::getMainMenu() as $menuItem)
{
    $navItems[] = array
    (
        'text'   => $menuItem->text,
        'url'    => \commonModel::createMenuLink($menuItem, $app->tab),
        'active' => $menuItem->order === 1,
    );
}

page
(
    set('title', $title),
    pageheader
    (
        pageheading
        (
            $lang->{$app->tab}->common,
            set('icon', $app->tab),
            set('url', \helper::createLink($app->tab, 'browse')),
        ),
        pagenavbar
        (
            zuinav
            (
                set('js-render', false),
                set('items', $navItems),
                item(array('text' => 'text')),
            ),
        ),
        toolbar
        (
            setId('toolbar'),
            set('js-render', true),
            item(array('icon' => 'icon-plus')),
            item(array('icon' => 'icon-group')),
            item(array('text' => '研发综合界面')),
        ),
    ),

    pagemain(

    ),
);

/*
page
(
  set('title', $title),
  pageheader
  (
    to('header', pageheading()),
    pageheading(set()),
    pagenavbar(),
    pagetoolbar()
  ),
  pagemain
  (
    mainmenu
    (
      set($mainMenuOptions),
      to
      (
        'toolbar',
        toolbar
        (
          $mainMenuToolbar,
          set('items', array(array('text' => 'copy'), array('type' => 'divider'))),
          item(array('text' => 'copy')),
          item(array('type' => 'divider'))
        )
      )
    ),
    dtable
    (
      set($dtableOptions)
    )
  )
); */
