<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'heading' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'navbar' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'toolbar' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'useravatar' . DS . 'v1.php';

class header extends wg
{
    protected static array $defineBlocks = array
    (
        'heading'         => array('map' => 'heading'),
        'headingToolbar'  => array('map' => 'toolbar'),
        'dropmenu'        => array('map' => 'dropmenu'),
        'navbar'          => array('map' => 'nav'),
        'toolbar'         => array('map' => 'btn')
    );

    protected function buildHeading()
    {
        if($this->hasBlock('heading')) return $this->block('heading');
        $headingToolbar = $this->block('headingToolbar');
        $dropmenu       = $this->block('dropmenu');
        return new heading($headingToolbar, $dropmenu);
    }

    protected function buildNavbar()
    {
        $navbar = $this->block('navbar');
        if(empty($navbar)) $navbar = new navbar();
        return $navbar;
    }

    protected function buildToolbar()
    {
        $toolbar = $this->block('toolbar');
        if(empty($toolbar))
        {
            $toolbar = new toolbar
            (
                setClass('gap-4'),
                static::quickAddMenu(),
                static::messageBar(),
                static::userBar()
            );
        }
        $pageToolbar = data('pageToolbar');
        return h::div
        (
            set::id('toolbar'),
            div
            (
                setID('pageToolbar'),
                setClass('btn-group mr-2'),
                $pageToolbar ? html($pageToolbar) : null
            ),
            $toolbar
        );
    }

    /**
     * Build.
     *
     * @access protected
     */
    protected function build()
    {
        return h::header
        (
            setID('header'),
            commonModel::isTutorialMode() ? setStyle('min-width', 'fit-content') : null,
            h::div
            (
                setClass('container'),
                $this->buildHeading(),
                $this->buildNavbar(),
                $this->buildToolbar()
            )
        );
    }

    public static function userBar()
    {
        global $lang, $app, $config;

        if(!isset($app->user)) return;

        $user       = $app->user;
        $isGuest    = $user->account == 'guest';
        $items      = array();
        $modalClass = (isset($config->zin->mode) && $config->zin->mode == 'compatible') ? 'open-in-parent' : null;

        if(!$isGuest)
        {
            $noRole = empty($user->role) || !isset($lang->user->roleList[$user->role]);
            $items[] = array
            (
                'url'          => createLink('my', 'profile', '', '', true),
                'leadingClass' => 'row items-center gap-2 px-2 py-1 text-inherit',
                'icon'         => ' hidden',
                'title'        => empty($user->realname) ? $user->account : $user->realname,
                'titleClass'   => 'text-lg',
                'subtitle'     => $noRole ? null : $lang->user->roleList[$user->role],
                'innerClass'   => $modalClass,
                'data-toggle'  => 'modal',
                'data-size'    => 700,
                'data-id'      => 'profile',
                'leading'      => array('html' => userAvatar(set::user($user))->render(), 'className' => 'center mr-1')
            );

            $items[] = array('type' => 'divider');

            $items[] = array
            (
                'url'         => createLink('my', 'profile', '', '', true),
                'icon'        => 'account',
                'text'        => $lang->profile,
                'innerClass'  => $modalClass,
                'data-toggle' => 'modal',
                'data-size'   => 700,
                'data-id'     => 'profile'
            );

            if(common::hasPriv('my', 'changePassword'))
            {
                $items[] = array
                (
                    'url'         => createLink('my', 'changepassword', '', '', true),
                    'icon'        => 'cog-outline',
                    'text'        => $lang->changePassword,
                    'innerClass'  => $modalClass,
                    'data-toggle' => 'modal',
                    'data-size'   => 'sm'
                );
            }

            $items[] = array('type' => 'divider');

            if($app->config->vision === 'rnd' && !commonModel::isTutorialMode())
            {
                $items[] = array
                (
                    'url'             => createLink('tutorial', 'start'),
                    'icon'            => 'guide',
                    'text'            => $lang->tutorialAB,
                    'class'           => '800',
                    'outerClass'      => 'user-tutorial',
                    'data-width'      => 700,
                    'data-class-name' => 'modal-inverse tutorial-start',
                    'data-headerless' => true,
                    'data-backdrop'   => true,
                    'data-keyboard'   => true,
                    'innerClass'      => $modalClass,
                    'data-toggle'     => 'modal'
                );
            }
        }

        $helpItems = array();
        $manualUrl = ((!empty($config->isINT)) ? $config->manualUrl['int'] : $config->manualUrl['home']) . '&theme=' . $_COOKIE['theme'];
        $helpItems[] = array('text' => $lang->manual, 'url' => $manualUrl, 'attrs' => array('data-app' => 'help'));
        $helpItems[] = array('text' => $lang->changeLog, 'url' => createLink('misc', 'changeLog'), 'data-toggle' => 'modal', 'innerClass' => $modalClass);
        $items[] = array('text' => $lang->help, 'icon' => 'help', 'items' => $helpItems);

        $items[] = array('type' => 'divider');

        if(!$isGuest && $app->config->vision === 'rnd')
        {
            $items[] = array
            (
                'url'         => createLink('my', 'preference', 'showTip=false', '', true),
                'icon'        => 'controls',
                'text'        => $lang->preference,
                'data-width'  => 700,
                'innerClass'  => $modalClass,
                'data-toggle' => 'modal'
            );
        }

        $themeItems = array();
        foreach($app->lang->themes as $key => $value)
        {
            $themeItems[] = array('text' => $value, 'data-value' => $key, 'url' => "javascript:selectTheme(\"$key\")", 'active' => $app->cookie->theme == $key);
        }
        $items[] = array
        (
            'text' => $lang->theme,
            'icon' => 'theme',
            'key'  => 'theme',
            'items' => $themeItems
        );

        $langItems = array();
        foreach($app->config->langs as $key => $value)
        {
            $langItems[] = array('text' => $value, 'data-value' => $key, 'url' => "javascript:selectLang(\"$key\")", 'active' => $app->cookie->lang == $key);
        }
        $items[] = array('text' => $lang->lang, 'icon' => 'lang', 'items' => $langItems);

        $items[] = array('type' => 'divider');

        /* Zentao desktop client menu. */
        if(isset($config->xxserver->installed) && $config->xuanxuan->turnon)
        {
            $clientSubMenu = array();
            $clientSubMenu[] = array('text' => $lang->downloadClient, 'url' => createLink('misc', 'downloadClient'), 'data-toggle' => 'modal', 'innerClass' => $modalClass);
            $clientSubMenu[] = array('text' => $lang->clientHelp, 'url' => $lang->clientHelpLink, 'attrs' => array('data-app' => 'help'));
            $items[] = array('text' => $lang->clientName, 'icon' => 'desktop', 'items' => $clientSubMenu);
        }

        $mobileSubMenu[] = array('content' => array('html' => "<img src='{$config->webRoot}static/images/app-qrcode.png' />", 'style' => 'width: 100px; heigth: 100px;'));
        $items[]         = array('icon' => 'mobile', 'text' => $lang->downloadMobile, 'items' => $mobileSubMenu);

        $items[] = array('text' => $lang->aboutZenTao, 'icon' => 'about', 'url' => createLink('misc', 'about'), 'data-toggle' => 'modal', 'innerClass' => $modalClass);
        $items[] = array('type' => 'html', 'className' => 'menu-item', 'html' => $lang->designedByAIUX);

        $items[] = array('type' => 'divider');

        if($isGuest)
        {
            $items[] = array('text' => $lang->login, 'url' => createLink('user', 'login'), 'target' => '_top');
        }
        else
        {
            $items[] = array('text' => $lang->logout, 'url' => "javascript:$.apps.logout()", 'icon' => 'exit');
        }

        return dropdown
        (
            a
            (
                setClass('w-7 h-7 cursor-pointer'),
                userAvatar
                (
                    set::circle(true),
                    set::size(28),
                    set::user($user)
                ),
                set::caret(false)
            ),

            set::id('userMenu'),
            set::placement('bottom-end'),
            set::menu(array('style' => array('color' => 'var(--color-fore)'))),
            set::strategy('fixed'),
            set::arrow(true),
            set::items($items)
        );
    }

    public static function messageBar()
    {
        global $app, $lang, $config;

        $app->loadConfig('message');
        if(!$config->message->browser->turnon) return null;

        $showCount   = $config->message->browser->count;
        $unreadCount = $app->dbh->query("SELECT COUNT(1) AS `count` FROM " . TABLE_NOTIFY . " WHERE `objectType` = 'message' AND status != 'read' AND `toList` = ',{$app->user->account},'")->fetch()->count;
        $dotStyle    = commonModel::getDotStyle($showCount != '0', $unreadCount);
        if($unreadCount > 99) $unreadCount = '99+';

        return dropdown
        (
            set::arrow(true),
            set::placement('bottom-end'),
            set::offset(array("alignmentAxis" => -50)),
            to::trigger
            (
                btn
                (
                    setID('messageBar'),
                    set(array('data-on' => 'click', 'data-call' => 'fetchMessage', 'data-fetcher' => createLink('message', 'ajaxGetDropMenu'))),
                    setClass('text-primary bg-inherit ring-primary ring-opacity-40'),
                    set::square(true),
                    set::caret(false),
                    set::size('sm'),
                    icon('bell', set::size('lg')),
                    $unreadCount ? label(setClass('danger label-dot absolute' . ($showCount ? ' rounded-sm' : '')), set::style($dotStyle), $showCount ? $unreadCount : null) : null
                )
            ),
            to::menu(menu
            (
                setClass('dropdown-menu not-hide-menu messageDropdownBox'),
                set::style(array('padding' => '0')),
                div(setID('dropdownMessageMenu'))
            ))
        );
    }

    public static function quickAddMenu()
    {
        global $app, $config, $lang;

        /* Initialize the default values. */
        $showCreateList = $needPrintDivider = false;
        $isCompatible   = isset($config->zin->mode) && $config->zin->mode == 'compatible';
        $modalClass     = $isCompatible ? 'open-in-parent' : null;

        /* Get default product id. */
        $productID = isset($_SESSION['product']) ? $_SESSION['product'] : 0;
        if($productID)
        {
            $product = $app->dbh->query("SELECT id  FROM " . TABLE_PRODUCT . " WHERE `deleted` = '0' and vision = '{$config->vision}' and id = '{$productID}'")->fetch();
            if(empty($product)) $productID = 0;
        }
        if(!$productID and $app->user->view->products)
        {
            $product = $app->dbh->query("SELECT id FROM " . TABLE_PRODUCT . " WHERE `deleted` = '0' and vision = '{$config->vision}' and id " . helper::dbIN($app->user->view->products) . " order by `order` desc limit 1")->fetch();
            if($product) $productID = $product->id;
        }

        if($config->vision == 'lite')
        {
            $condition  = " WHERE `deleted` = '0' AND `vision` = 'lite' AND `model` = 'kanban'";
            if(!$app->user->admin) $condition .= " AND `id` " . helper::dbIN($app->user->view->projects);

            $object = $app->dbh->query("select id from " . TABLE_PROJECT . $condition . ' LIMIT 1')->fetch();
            if(empty($object)) unset($lang->createIcons['story'], $lang->createIcons['task'], $lang->createIcons['execution']);
        }

        if($config->edition == 'open')     unset($lang->createIcons['effort']);
        if($config->systemMode == 'light') unset($lang->createIcons['program']);
        if(empty($config->board))          unset($lang->createIcons['board']);

        /* Check whether the creation permission is available, and print create buttons. */
        $items = array();
        foreach($lang->createIcons as $objectType => $objectIcon)
        {
            $createMethod = 'create';
            $module       = $objectType == 'kanbanspace' ? 'kanban' : $objectType;
            if($objectType == 'effort') $createMethod = 'batchCreate';
            if($objectType == 'kanbanspace') $createMethod = 'createSpace';
            if($objectType == 'board') $createMethod = 'createBoard';
            if(str_contains('|bug|execution|kanbanspace|', "|$objectType|")) $needPrintDivider = true;

            if(!common::hasPriv($module, $createMethod)) continue;

            if($objectType == 'doc' and !common::hasPriv('doc', 'create')) continue;

            /* Determines whether to print a divider. */
            if($needPrintDivider and $showCreateList)
            {
                $items[] = array('type' => 'divider');
                $needPrintDivider = false;
            }

            $showCreateList = true;
            $isOnlyBody     = false;
            $item           = array('icon' => $objectIcon, 'text' => $lang->createObjects[$objectType]);

            $params = '';
            switch($objectType)
            {
                case 'doc':
                    $params              = "objectType=&objectID=0&libID=0";
                    $createMethod        = 'selectLibType';
                    $item['innerClass']  = $modalClass;
                    $item['data-toggle'] = 'modal';
                    break;
                case 'project':
                    if($config->vision == 'lite')
                    {
                        $params = "model=kanban";
                    }
                    else if(!defined('TUTORIAL'))
                    {
                        $params              = "programID=0&from=global";
                        $createMethod        = 'createGuide';
                        $item['innerClass']  = $modalClass;
                        $item['data-toggle'] = 'modal';

                        if($isCompatible) $item['data-type'] = 'ajax';
                    }
                    else
                    {
                        $params = "model=scrum&programID=0&copyProjectID=0&extra=from=global";
                    }

                    break;
                case 'bug':
                    $params = "productID=$productID&branch=&extras=from=global";
                    break;
                case 'story':
                    if(!$productID and $config->vision == 'lite')
                    {
                        $module = 'project';
                        $params = "model=kanban";
                    }
                    else
                    {
                        $params = "productID=$productID&branch=0&moduleID=0&storyID=0&objectID=0&bugID=0&planID=0&todoID=0&extra=from=global";
                        if($config->vision == 'lite')
                        {
                            $projectID = isset($_SESSION['project']) ? $_SESSION['project'] : 0;
                            $projects  = $app->dbh->query("SELECT t2.id FROM " . TABLE_PROJECTPRODUCT . " AS t1 LEFT JOIN " . TABLE_PROJECT . " AS t2 ON t1.project = t2.id WHERE t1.`product` = '{$productID}' and t2.`type` = 'project' and t2.id " . helper::dbIN($app->user->view->projects) . " ORDER BY `order` desc")->fetchAll();

                            $projectIdList = array();
                            foreach($projects as $project) $projectIdList[$project->id] = $project->id;
                            if($projectID and !isset($projectIdList[$projectID])) $projectID = 0;
                            if(empty($projectID)) $projectID = key($projectIdList);

                            $params = "productID={$productID}&branch=0&moduleID=0&storyID=0&objectID={$projectID}&bugID=0&planID=0&todoID=0&extra=from=global";
                        }
                    }

                    break;
                case 'task':
                    $params = "executionID=0&storyID=0&moduleID=0&taskID=0&todoID=0&extra=from=global";
                    break;
                case 'testcase':
                    $params = "productID=$productID&branch=&moduleID=0&from=&param=0&storyID=0&extras=from=global";
                    break;
                case 'execution':
                    $projectID = isset($_SESSION['project']) ? $_SESSION['project'] : 0;
                    $params = "projectID={$projectID}&executionID=0&copyExecutionID=0&planID=0&confirm=no&productID=0&extra=from=global";
                    break;
                case 'product':
                    $params = "programID=&extra=from=global";
                    break;
                case 'program':
                    $params = "parentProgramID=0&extra=from=global";
                    break;
                case 'kanbanspace':
                    $isOnlyBody          = true;
                    $item['innerClass']  = $modalClass;
                    $item['data-toggle'] = 'modal';
                    $item['data-width']  = '75%';
                    break;
                case 'kanban':
                    $isOnlyBody          = true;
                    $item['innerClass']  = $modalClass;
                    $item['data-toggle'] = 'modal';
                    $item['data-width']  = '75%';
                    break;
                case 'board':
                    $createMethod = 'createByTemplate';
                    $params       = 'templateID=1';
                    $isOnlyBody   = true;
                    $item['innerClass']  = $modalClass;
                    $item['data-toggle'] = 'modal';
                    $item['data-width']  = '75%';
                    break;
            }

            $item['url'] = createLink($module, $createMethod, $params, '', $isOnlyBody);

            $items[] = $item;
        }

        if(!$showCreateList) return '';

        return dropdown
        (
            btn
            (
                icon('plus', set::size('lg')),
                setClass('text-primary ring-primary ring-opacity-30 bg-inherit'),
                set::square(true),
                set::size('sm'),
                set::caret(false)
            ),

            set::id('quickAddMenu'),
            set::menu(array('style' => array('color' => 'var(--color-fore)'))),
            set::placement('bottom'),
            set::strategy('fixed'),
            set::arrow(true),
            set::items($items)
        );
    }
}
