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

/* Generate dropdown menu. */
$shotcutAddMenu = dropdown(setId('shortcutAdd'));
foreach(\commonModel::printCreateListZin() as $item) $shotcutAddMenu->append(item($item));

$userMenu = dropdown(setId('userMenu'));
foreach(\commonModel::printUserBarZin() as $item) $userMenu->append(item($item));

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
            set('js-render', false),
            set('items', $navItems),
            item(array('text' => 'text')),
        ),
        toolbar
        (
            setId('toolbar'),
            set('js-render', true),
            item(array('icon' => 'icon-plus')),
            item(array('icon' => 'icon-group')),
            item(array('text' => '研发综合界面')),
            btn
            (
                icon('plus'),
                setClass('rounded-sm btn square size-sm secondary'),
                set('data-toggle',  'dropdown'),
                set('data-trigger', 'hover'),
                set('data-arrow',   'true'),
                set('href',         '#shortcutAdd'),
            ),
            avatar
            (
                set('role',   '研发'),
                set('avatar', '/data/upload/1/202302/07134647027036pb'),
                set('href',   '#userMenu'),
            ),
        ),
    ),
    pagemain(

    ),
    $shotcutAddMenu,
    $userMenu,
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
