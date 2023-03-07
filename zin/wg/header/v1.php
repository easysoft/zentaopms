<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'heading' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'navbar' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'toolbar' . DS . 'v1.php';

class header extends wg
{
    static $defineBlocks = array
    (
        'heading' => array('map' => 'toolbar'),
        'navbar' => array('map' => 'nav'),
        'toolbar' => array('map' => 'btn')
    );

    protected function buildHeading()
    {
        $heading = $this->block('heading');
        if(empty($heading)) $heading = new heading();
        return $heading;
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
                setClass('gap-5'),
                static::quickAddMenu(),
                static::userBar(),
                static::visionSwitcher()
            );
        }
        return h::div
        (
            set::id('toolbar'),
            $toolbar
        );
    }

    /**
     * Build.
     *
     * @access protected
     * @return object
     */
    protected function build()
    {
        return h::header
        (
            setId('header'),
            h::div
            (
                setClass('container'),
                $this->buildHeading(),
                $this->buildNavbar(),
                $this->buildToolbar()
            )
        );
    }

    static function visionSwitcher()
    {
        global $lang, $app, $config;

        if(!isset($app->user)) return;

        if(!isset($app->user->visions)) $app->user->visions = trim($config->visions, ',');
        $currentVision = $app->config->vision;
        $userVisions   = array_filter(explode(',', $app->user->visions));
        $configVisions = array_filter(explode(',', trim($config->visions, ',')));

        /* The standalone lite version removes the lite interface button */
        if(trim($config->visions, ',') == 'lite') return true;

        if(count($userVisions) < 2 || count($configVisions) < 2) return btn($lang->visionList[$currentVision]);

        $items = array();
        foreach($userVisions as $vision)
        {
            $items[] = array
            (
                'active' => $currentVision == $vision,
                'url' => createLink('my', 'ajaxSwitchVision', "vision=$vision"),
                'data-type' => 'ajax',
                'text' => $lang->visionList[$vision],
            );
        }

        return dropdown
        (
            btn
            (
                setClass('bg-white border-none rounded bg-opacity-30'),
                set::text($lang->visionList[$currentVision]),
                set::caret(false)
            ),

            set::id('versionMenu'),
            set::trigger('hover'),
            set::placement('bottom'),
            set::arrow(true),
            set::items($items)
        );
    }

    static function userBar()
    {
        global $lang, $app;

        if(!isset($app->user)) return;

        $isGuest = $app->user->account == 'guest';
        $items   = array();

        if(!$isGuest)
        {
            $noRole = (!empty($app->user->role) and isset($lang->user->roleList[$app->user->role])) ? '' : ' no-role';

            $items[] = array
            (
                'outerProps' => array('class' => 'items-center gap-2 px-2 py-1 row'),
                'children' => array($app->user->realname),
            );

            // echo '<li class="user-profile-item">';
            // echo "<a href='" . helper::createLink('my', 'profile', '', '', true) . "' data-width='700' class='iframe $noRole'" . '>';
            // echo html::avatar($app->user, '', 'avatar-circle', 'id="menu-avatar"');
            // echo '<div class="user-profile-name">' . (empty($app->user->realname) ? $app->user->account : $app->user->realname) . '</div>';
            // if(isset($lang->user->roleList[$app->user->role])) echo '<div class="user-profile-role">' . $lang->user->roleList[$app->user->role] . '</div>';

            $items[] = array('type' => 'divider');

            $items[] = array
            (
                'url' => createLink('my', 'profile', '', '', true),
                'icon' => 'account',
                'text' => $lang->profile,
                'class' => 'iframe',
                'data-width' => 700
            );

            if($app->config->vision === 'rnd')
            {
                if(!commonModel::isTutorialMode())
                {
                    $items[] = array
                    (
                        'url' => createLink('tutorial', 'start'),
                        'icon' => 'guide',
                        'text' => $lang->tutorialAB,
                        'class' => '800',
                        'outerClass' => 'user-tutorial',
                        'data-width' => 700,
                        'data-class-name' => 'modal-inverse',
                        'data-headerless' => true,
                        'data-backdrop' => true,
                        'data-keyboard' => true
                    );
                }

                $items[] = array
                (
                    'url' => createLink('my', 'preference', 'showTip=false', '', true),
                    'icon' => 'controls',
                    'text' => $lang->preference,
                    'class' => 'iframe',
                    'data-width' => 700
                );
            }

            if(common::hasPriv('my', 'changePassword'))
            {
                $items[] = array
                (
                    'url' => createLink('my', 'changepassword', '', '', true),
                    'icon' => 'cog-outline',
                    'text' => $lang->changePassword,
                    'class' => 'iframe',
                    'data-width' => 600
                );
            }

            $items[] = array('type' => 'divider');
        }

        $items[] = array('type' => 'heading', 'text' => $lang->theme, 'icon' => 'theme');
        foreach($app->lang->themes as $key => $value)
        {
            $items[] = array('text' => $value, 'data-value' => $key, 'url' => "javascript:selectTheme(\"$key\")", 'active' => $app->cookie->theme == $key);
        }

        $items[] = array('type' => 'heading', 'text' => $lang->lang, 'icon' => 'theme');
        foreach ($app->config->langs as $key => $value)
        {
            $items[] = array('text' => $value, 'data-value' => $key, 'url' => "javascript:selectLang(\"$key\")", 'active' => $app->cookie->lang == $key);
        }

        // commonModel::printAboutBar();

        $items[] = array('type' => 'divider');

        if($isGuest)
        {
            $items[] = array('text' => $lang->login, 'url' => createLink('user', 'login'), 'target' => '_top');
        }
        else
        {
            $items[] = array('text' => $lang->logout, 'url' => createLink('user', 'logout'), 'target' => '_top', 'icon' => 'exit');
        }

        return dropdown
        (
            a
            (
                icon('account', set::size('lg')),
                setClass('primary border border-white border-opacity-50 avatar w-7 circle'),
                set::square(true),
                set::caret(false)
            ),

            set::id('userMenu'),
            set::trigger('hover'),
            set::placement('bottom'),
            set::arrow(true),
            set::items($items)
        );
    }

    static function quickAddMenu()
    {
        global $app, $config, $lang;

        /* Initialize the default values. */
        $showCreateList = $needPrintDivider = false;

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

        /* Check whether the creation permission is available, and print create buttons. */
        $items = array();
        foreach($lang->createIcons as $objectType => $objectIcon)
        {
            $createMethod = 'create';
            $module       = $objectType == 'kanbanspace' ? 'kanban' : $objectType;
            if($objectType == 'effort') $createMethod = 'batchCreate';
            if($objectType == 'kanbanspace') $createMethod = 'createSpace';
            if(strpos('|bug|execution|kanbanspace|', "|$objectType|") !== false) $needPrintDivider = true;

            if(!common::hasPriv($module, $createMethod)) continue;

            if($objectType == 'doc' and !common::hasPriv('doc', 'tableContents')) continue;

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
                    $params             = "objectType=&objectID=0&libID=0";
                    $createMethod       = 'selectLibType';
                    $isOnlyBody         = true;
                    $item['class']      = 'iframe';
                    $item['data-width'] = '700px';
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
                        $item['data-toggle'] = 'modal';
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
                    $isOnlyBody         = true;
                    $item['class']      = 'iframe';
                    $item['data-width'] = '75%';
                    break;
                case 'kanban':
                    $isOnlyBody         = true;
                    $item['class']      = 'iframe';
                    $item['data-width'] = '75%';
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
                setClass('bg-white border-none rounded bg-opacity-20'),
                set::square(true),
                set::size('sm'),
                set::caret(false)
            ),

            set::id('quickAddMenu'),
            set::trigger('hover'),
            set::placement('bottom'),
            set::arrow(true),
            set::items($items)
        );
    }
}
