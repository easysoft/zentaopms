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
$userMenu         = \commonModel::printUserBarZin();
$globalCreateMenu = \commonModel::printCreateListZin();
$switcherMenu     = \commonModel::printVisionSwitcherZin();

$cols   = array_values($config->program->dtable->fieldList);
$fields = array_keys($config->program->dtable->fieldList);
$data   = array_values($programs);

foreach($data as $row)
{
  if(!property_exists($row, 'progress'))
  {
    if(isset($progressList[$row->id])) $row->progress = $progressList[$row->id];
    else $row->progress = '';
  }

  if(!property_exists($row, 'actions'))
  {
    $row->actions = array();
  }
}

\common::sortFeatureMenu();
$statuses = array();
foreach($lang->program->featureBar['browse'] as $key => $text)
{
  $statuses[] = array(
    'text' => $text,
    'active' => $key === $status,
    'url' => \helper::createLink('browse', "status=$key&orderBy=$orderBy"),
  );
}

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
    'class' => 'btn secondary',
    'url'   => \helper::createLink('project', 'createGuide', "programID=0&from=PGM"),
  );
}

if(\common::hasPriv('program', 'create'))
{
  $btnGroup[] = array(
    'text' => $lang->program->create,
    'icon'  => 'plus',
    'class' => 'btn primary',
    'url' => \helper::createLink('program', 'create')
  );
}

$pagerData = array(
    'items' => array
    (
        array('type' => 'info',      'text' => '共 {recTotal} 项'),
        array('type' => 'size-menu', 'text' => '每页 {recPerPage} 项', 'dropdown' => array('placement' => 'top')),
        array('type' => 'link',      'page' => 'prev', 'icon' => 'icon-angle-left', 'hint' => '上一页'),
        array('type' => 'nav'),
        array('type' => 'link',      'page' => 'next', 'icon' => 'icon-angle-right', 'hint' => '下一页'),
    ),
    'page' => 2,
    'recTotal' => 101,
    'recPerPage' => 10,
    'linkCreator' => '#?page={page}&recPerPage={recPerPage}',
    'onClickItem' => '(info)=>{console.log("> pager.onClickItem" => info)}'
);

$tabsData = array(
    array(
        'label' => '标签1',
        'id'    => 'tab1',
        'data'  => array(
            p('tab1 content'),
            avatar(set('name', $app->user->account), set('avatar', $app->user->avatar))
        )
    ),
    array(
        'label'  => '标签2',
        'data'   => div('content'),
        'active' => true
    )
);

page(
    set('title', $title),
    pageheader
    (
        pageheading
        (
            set('text', $lang->{$app->tab}->common),
            set('icon', $app->tab),
            set('url', \helper::createLink($app->tab, 'browse')),
        ),
        pagenavbar
        (
            setId('navbar'),
            set('items', $navItems)
        ),
        pagetoolbar
        (
            set('create',   array('href'=>'#globalCreateMenu')),
            set('switcher', array('href'=>'#switcherMenu', 'text' => '研发管理界面')),
            block('avatar', avatar(set('name', $app->user->account), set('avatar', $app->user->avatar), set('trigger', '#userMenu')))
        )
    ),
    pagemain(
        //mainmenu(
        //  set('statuses', array('items' => $statuses)),
        //  set('others', $others),
        //  set('btnGroup', $btnGroup),
        //),
        dtable(
          set('js-render', true),
          set('cols', $cols),
          set('width', '100%'),
          set('data', $data)
        ),
        pager
        (
            set('js-render',   true),
            set($pagerData)
        ),
        modal(
            '点击打开',
            setId('myModal2'),
            set('title', '标题'),
            to
            (
                'body',
                array('body')
            ),
            to
            (
                'footer',
                array
                (
                    btn('关闭', set('data-dismiss', 'modal')),
                    btn('保存', setClass('primary'))
                )
            )
        ),
        panel
        (
            set('theme', 'warning'),
            to('heading', '标题'),
            to('body', tabs(set('items', $tabsData))),
            to('footer', '注脚'),
            p('其它内容')
        )
    ),
    dropdown
    (
        setId('userMenu'),
        set('items', $userMenu)
    ),
    dropdown
    (
        setId('globalCreateMenu'),
        set('items', $globalCreateMenu)
    ),
    dropdown
    (
        setId('switcherMenu'),
        set('items', $switcherMenu)
    )
);
