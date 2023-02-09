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

/* Generate dropdown menus. */
$userMenu = dropdown(setId('userMenu'));
foreach(\commonModel::printUserBarZin() as $item) $userMenu->append(item($item));

$globalCreateMenu = dropdown(setId('globalCreateMenu'));
foreach(\commonModel::printCreateListZin() as $item) $globalCreateMenu->append(item($item));

$switcherMenu = dropdown(setId('switcherMenu'));
foreach(\commonModel::printVisionSwitcherZin() as $item) $switcherMenu->append(item($item));

$cols  = array_values($config->program->dtable->fieldList);
$fields = array_keys($config->program->dtable->fieldList);
$data  = array_values($programs);

foreach($data as $row)
{
  foreach($row as $k => $v)
  {
    if (!in_array($k, $fields)) unset($row->$k);
  }

  if(!property_exists($row, 'progress'))
  {
    $row->progress = '';
  }

  if(!property_exists($row, 'actions'))
  {
    $row->actions = array();
  }
}

$statuses = array();
foreach($lang->program->featureBar['browse'] as $key => $text) $statuses[] = array('text' => $text, 'active' => $key === $status);

$others = array();
if(\common::hasPriv('project', 'batchEdit') and $programType != 'bygrid' and $hasProject === true)
{
  $others[] = array(
    'text'    => $lang->project->edit,
    'checked' => $this->cookie->editProject,
    'type'    => 'checkbox'
  );
}

$others[] = array(
  'type'  => 'button',
  'icon'  => 'search',
  'text'  => $lang->user->search,
  'class' => 'ghost'
);

$btnGroup = array();
if(\common::hasPriv('project', 'create'))
{
  $btnGroup[] = array(
    'text'  => $lang->project->create,
    'icon'  => 'plus',
    'class' => 'btn btn-secondary'
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
      set('globalCreate', array(
        'data-arrow'     => true,
        'data-toggle'    => 'dropdown',
        'data-trigger'   => 'hover',
        'href'           => '#globalCreateMenu',
      )),
      set('avatar', array(
        'name'   => $app->user->account,
        'avatar' => $app->user->avatar,
        'href'   => '#userMenu'
      )),
      set('switcher', array(
        'text' => '研发管理界面',
        'data-arrow'     => true,
        'data-toggle'    => 'dropdown',
        'data-trigger'   => 'hover',
        'href'           => '#switcherMenu',
      ))
    ),
  ),

  Pagemain(
    Mainmenu(
      set('statuses', array('items' => $statuses)),
      set('others', $others),
      set('btnGroup', $btnGroup),
    ),

    Dtable(
      set('js-render', true),
      set('cols', $cols),
      set('width', '100%'),
      set('height', 400),
      set('data', $data),
    )
  ),
  $userMenu,
  $globalCreateMenu,
  $switcherMenu,
);
