<?php

namespace zin;

\commonModel::setMainMenu();

$navItems = array();
foreach(\customModel::getMainMenu() as $menuItem)
{
  $navItems[] = array(
    'text'   => $menuItem->text,
    'url'    => \commonModel::createMenuLink($menuItem, $app->tab),
    'active' => $menuItem->order === 1,
  );
}

Page(
  set('title', $title),
  Pageheader(
    Pageheading(
      $lang->{$app->tab}->common,
      set('icon', $app->tab),
      set('url', \helper::createLink($app->tab, 'browse')),
    ),
    Pagenavbar(
      Zuinav(
        set('js-render', false),
        set('items', $navItems),
      ),
    ),
    PageToolbar(
      set('avatar', array(
        'avatar' => $app->user->avatar,
        'href' => '#userMenu'
      )),
      set('switcher', array(
        'text' => '研发管理界面'
      ))
    ),
  ),

  Pagemain(
    Mainmenu(
      set('statuses', array('items' => array(
        array('text' => '全部'),
        array('text' => '未关闭'),
        array('text' => '未开始'),
        array('text' => '进行中'),
        array('text' => '已挂起'),
        array('text' => '已关闭'),
      ))),
      set('others', array(
        array('type' => 'checkbox', 'text' => '编辑项目'),
        array('type' => 'button', 'icon' => 'search', 'text' => '搜索', 'class' => 'ghost'),
        array('type' => 'button', 'icon' => 'unfold-all', 'text' => '排序', 'class' => 'ghost'),
      )),
      set(
        'btnGroup',
        array(
          array('icon' => 'plus', 'text' => '创建项目', 'class' => 'secondary'),
          array('icon' => 'plus', 'text' => '添加项目集', 'class' => 'primary'),
        ),
      ),
    ),
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
