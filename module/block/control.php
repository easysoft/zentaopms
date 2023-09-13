<?php
 /**
 * The control file of block of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class block extends control
{
    /**
     * construct.
     *
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        /* Mark the call from zentao or ranzhi. */
        $this->selfCall = !isset($_GET['hash']);
        if($this->methodName != 'admin' and $this->methodName != 'dashboard' and !$this->selfCall and !$this->loadModel('sso')->checkKey()) helper::end('');
    }

    /**
     * Block admin.
     *
     * @param  int    $id
     * @param  string $module
     * @access public
     * @return void
     */
    public function admin($id = 0, $module = 'my')
    {
        $this->session->set('blockModule', $module);

        $title = $id == 0 ? $this->lang->block->createBlock : $this->lang->block->editBlock;

        if($module == 'my')
        {
            $modules = $this->lang->block->moduleList;
            unset($modules['doc']);

            list($programModule, $programMethod)     = explode('-', $this->config->programLink);
            list($productModule, $productMethod)     = explode('-', $this->config->productLink);
            list($projectModule, $projectMethod)     = explode('-', $this->config->projectLink);
            list($executionModule, $executionMethod) = explode('-', $this->config->executionLink);

            foreach($modules as $moduleKey => $moduleName)
            {
                if($moduleKey == 'todo') continue;
                if(in_array($moduleKey, $this->app->user->rights['acls'])) unset($modules[$moduleKey]);

                $method = 'index';
                if($moduleKey == 'program')   $method = $programMethod;
                if($moduleKey == 'product')   $method = $productMethod;
                if($moduleKey == 'project')   $method = $projectMethod;
                if($moduleKey == 'execution') $method = $executionMethod;

                if(!common::hasPriv($moduleKey, $method)) unset($modules[$moduleKey]);
            }

            $closedBlock = isset($this->config->block->closed) ? $this->config->block->closed : '';
            if(strpos(",$closedBlock,", ",|assigntome,") === false and $this->config->vision != 'or') $modules['assigntome'] = $this->lang->block->assignToMe;
            if(strpos(",$closedBlock,", ",|dynamic,") === false) $modules['dynamic'] = $this->lang->block->dynamic;
            if(strpos(",$closedBlock,", ",|guide,") === false and $this->config->global->flow == 'full') $modules['guide'] = $this->lang->block->guide;
            if($this->config->edition != 'ipd' and strpos(",$closedBlock,", ",|welcome,") === false and $this->config->global->flow == 'full') $modules['welcome'] = $this->lang->block->welcome;
            if(strpos(",$closedBlock,", ",|html,") === false) $modules['html'] = 'HTML';
            if(strpos(",$closedBlock,", ",|contribute,") === false and $this->config->vision == 'rnd') $modules['contribute'] = $this->lang->block->contribute;
            $modules = array('' => '') + $modules;

            $hiddenBlocks = $this->block->getHiddenBlocks();
            foreach($hiddenBlocks as $block) $modules['hiddenBlock' . $block->id] = $block->title;
            $this->view->modules = $modules;
        }
        elseif(isset($this->lang->block->moduleList[$module]))
        {
            $this->get->set('mode', 'getblocklist');
            if($module == 'project')
            {
                $this->get->set('dashboard', 'project');

                if(($this->config->edition == 'max' or $this->config->edition == 'ipd') and $this->app->tab == 'project')
                {
                    $project = $this->loadModel('project')->getByID($this->session->project);
                    if(isset($project->model) and !helper::hasFeature("{$project->model}_issue"))
                    {
                        unset($this->lang->block->modules['scrum']['index']->availableBlocks->scrumissue);
                        unset($this->lang->block->modules['waterfall']['index']->availableBlocks->waterfallissue);
                    }
                    if(isset($project->model) and !helper::hasFeature("{$project->model}_risk"))
                    {
                        unset($this->lang->block->modules['scrum']['index']->availableBlocks->scrumrisk);
                        unset($this->lang->block->modules['waterfall']['index']->availableBlocks->waterfallrisk);
                    }
                }
            }
            $this->view->blocks = $this->fetch('block', 'main', "module=$module&id=$id");
            $this->view->module = $module;
        }

        $this->view->title   = $title;
        $this->view->block   = $this->block->getByID($id);
        $this->view->blockID = $id;
        $this->display();
    }

    /**
     * Set params when type is rss or html.
     *
     * @param  int    $id
     * @param  string $type
     * @access public
     * @return void
     */
    public function set($id, $type, $source = '')
    {
        if($_POST)
        {
            $source = isset($this->lang->block->moduleList[$source]) ? $source : '';
            $this->block->save($id, $source, $type, $this->session->blockModule);
            if(dao::isError()) return print(js::error(dao::geterror()));
            return print(js::reload('parent'));
        }

        $block = $this->block->getByID($id);
        if($block and empty($type)) $type = $block->block;
        if(isset($block->params->num) and !isset($block->params->count))
        {
            $block->params->count = $block->params->num;
            unset($block->params->num);
        }

        if(isset($this->lang->block->moduleList[$source]))
        {
            $func   = 'get' . ucfirst($type) . 'Params';
            $params = $this->block->$func($source);
            $this->view->params = json_decode($params, true);
        }
        elseif($type == 'assigntome')
        {
            $params = $this->block->getAssignToMeParams();
            $this->view->params = json_decode($params, true);
        }

        $this->view->source = $source;
        $this->view->type   = $type;
        $this->view->id     = $id;
        $this->view->block  = ($block) ? $block : array();
        $this->display();
    }

    /**
     * Delete block
     *
     * @param  int    $id
     * @param  string $sys
     * @param  string $type
     * @access public
     * @return void
     */
    public function delete($id, $module = 'my', $type = 'delete')
    {
        if($type == 'hidden')
        {
            $this->dao->update(TABLE_BLOCK)->set('hidden')->eq(1)->where('`id`')->eq($id)->andWhere('account')->eq($this->app->user->account)->andWhere('module')->eq($module)->exec();
        }
        else
        {
            $this->dao->delete()->from(TABLE_BLOCK)->where('`id`')->eq($id)->andWhere('account')->eq($this->app->user->account)->andWhere('module')->eq($module)->exec();
        }
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->loadModel('score')->create('block', 'set');
        return $this->send(array('result' => 'success'));
    }

    /**
     * Sort block.
     *
     * @param  string    $oldOrder
     * @param  string    $newOrder
     * @param  string    $module
     * @access public
     * @return void
     */
    public function sort($orders, $module = 'my')
    {
        $orders    = explode(',', $orders);
        $blockList = $this->block->getBlockList($module);

        foreach ($orders as $order => $blockID)
        {
            $block = $blockList[$blockID];
            if(!isset($block)) continue;
            $block->order = $order;
            $this->dao->replace(TABLE_BLOCK)->data($block)->exec();
        }

        if(dao::isError()) return $this->send(array('result' => 'fail'));
        $this->loadModel('score')->create('block', 'set');
        return $this->send(array('result' => 'success'));
    }

    /**
     * Resize block
     * @param  integer $id
     * @access public
     * @return void
     */
    public function resize($id, $type, $data)
    {
        $block = $this->block->getByID($id);
        if($block)
        {
            $field = '';
            if($type == 'vertical') $field = 'height';
            if($type == 'horizontal') $field = 'grid';
            if(empty($field)) return $this->send(array('result' => 'fail', 'code' => 400));

            $block->$field = $data;
            $block->params = helper::jsonEncode($block->params);
            $this->dao->replace(TABLE_BLOCK)->data($block)->exec();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'code' => 500));
            return $this->send(array('result' => 'success'));
        }
        else
        {
            return $this->send(array('result' => 'fail', 'code' => 404));
        }
    }

    /**
     * Display dashboard for app.
     *
     * @param  string    $module
     * @param  string    $type
     * @param  int       $projectID
     * @access public
     * @return void
     */
    public function dashboard($module, $type = '', $projectID = 0)
    {
        if($this->loadModel('user')->isLogon()) $this->session->set('blockModule', $module);
        session_write_close();
        $blocks = $this->block->getBlockList($module, $type);
        $vision = $this->config->vision;

        $section = 'common';
        if($module == 'project' and $projectID)
        {
            $project = $this->loadModel('project')->getByID($projectID);
            $section = $project->model . 'common';
        }

        $inited = $this->dao->select('*')->from(TABLE_CONFIG)
            ->where('module')->eq($module)
            ->andWhere('owner')->eq($this->app->user->account)
            ->andWhere('`section`')->eq($section)->fi()
            ->andWhere('`key`')->eq('blockInited')
            ->andWhere('vision')->eq($vision)
            ->fetch('value');

        /* Init block when vist index first. */
        if((empty($blocks) and !$inited and !defined('TUTORIAL')))
        {
            if($this->block->initBlock($module, $type)) return print(js::reload());
        }

        $acls = $this->app->user->rights['acls'];
        $shortBlocks = $longBlocks = array();
        foreach($blocks as $key => $block)
        {
            if(in_array($block->block, array('waterfallrisk', 'waterfallissue')))
            {
                $model = isset($project->model) ? $project->model : 'waterfall';
                if($block->block == 'waterfallrisk' and !helper::hasFeature("{$model}_risk")) continue;
                if($block->block == 'waterfallissue' and !helper::hasFeature("{$model}_issue")) continue;
            }

            if(in_array($block->block, array('scrumrisk', 'scrumissue')))
            {
                $model = isset($project->model) ? $project->model : 'scrum';
                if($block->block == 'scrumrisk' and !helper::hasFeature("{$model}_risk")) continue;
                if($block->block == 'scrumissue' and !helper::hasFeature("{$model}_issue")) continue;
            }

            if(!empty($block->source) and $block->source != 'todo' and !empty($acls['views']) and !isset($acls['views'][$block->source]))
            {
                unset($blocks[$key]);
                continue;
            }

            $block->params = json_decode($block->params);
            if(isset($block->params->num) and !isset($block->params->count)) $block->params->count = $block->params->num;

            $blockID = $block->block;
            $source  = empty($block->source) ? 'common' : $block->source;

            $block->blockLink = $this->createLink('block', 'printBlock', "id=$block->id&module=$block->module");
            $block->moreLink  = '';
            if(isset($this->config->block->modules[$source]->moreLinkList->{$blockID}))
            {
                list($moduleName, $method, $vars) = explode('|', sprintf($this->config->block->modules[$source]->moreLinkList->{$blockID}, isset($block->params->type) ? $block->params->type : ''));

                /* The list assigned to me jumps to the work page when click more button. */
                $block->moreLink = $this->createLink($moduleName, $method, $vars);
                if($moduleName == 'my' and strpos($this->config->block->workMethods, $method) !== false)
                {
                    $block->moreLink = $this->createLink($moduleName, 'work', 'mode=' . $method . '&' . $vars);
                }
                elseif($moduleName == 'project' and $method == 'dynamic')
                {
                    $block->moreLink = $this->createLink('project', 'dynamic', "projectID=$projectID&type=all");
                }
                elseif($moduleName == 'project' and $method == 'execution')
                {
                    $block->moreLink = $this->createLink('project', 'execution', "status=all&projectID=$projectID");
                }
                elseif($moduleName == 'project' and $method == 'testtask')
                {
                    $block->moreLink = $this->createLink('project', 'testtask', "projectID=$projectID");
                }
                elseif($moduleName == 'testtask' and $method == 'browse')
                {
                    $block->moreLink = $this->createLink('testtask', 'browse', "productID=0&branch=0&type=all,totalStatus");
                }

            }
            elseif($block->block == 'dynamic')
            {
                $block->moreLink = $this->createLink('company', 'dynamic');
            }

            if($this->block->isLongBlock($block))
            {
                $longBlocks[$key] = $block;
            }
            else
            {
                $shortBlocks[$key] = $block;
            }
        }

        $this->view->longBlocks  = $longBlocks;
        $this->view->shortBlocks = $shortBlocks;
        $this->view->module      = $module;

        if($this->app->getViewType() == 'json') return print(json_encode($blocks));

        $this->display();
    }

    /**
     * latest dynamic.
     *
     * @access public
     * @return void
     */
    public function dynamic()
    {
        $this->view->actions = $this->loadModel('action')->getDynamic('all', 'today', 'id_desc', 30);
        $this->view->users   = $this->loadModel('user')->getPairs('nodeleted|noletter|all', '', 0, array_unique(helper::arrayColumn($this->view->actions, 'actor')));

        $this->display();
    }

    /**
     * Welcome block.
     *
     * @access public
     * @return void
     */
    public function welcome()
    {
        $this->view->tutorialed = $this->loadModel('tutorial')->getTutorialed();

        $cacheKey = $this->config->cacheKeys->block->welcome;
        if(helper::isCacheEnabled() && $this->cache->has($cacheKey))
        {
            $data = $this->cache->get($cacheKey);
        }
        else
        {
            $data = $this->block->getWelcomeBlockData();
            if($this->config->cache->enable) $this->cache->set($cacheKey, $data);
        }

        $this->view->tasks      = $data['tasks'];
        $this->view->doneTasks  = $data['doneTasks'];
        $this->view->bugs       = $data['bugs'];
        $this->view->stories    = $data['stories'];

        $this->view->delay['task'] = $data['delayTask'];
        $this->view->delay['bug']  = $data['delayBug'];

        $time = date('H:i');
        $welcomeType = '19:00';
        foreach($this->lang->block->welcomeList as $type => $name)
        {
            if($time >= $type) $welcomeType = $type;
        }
        $this->view->welcomeType = $welcomeType;
        $this->display();
    }

    /**
     * Print contribute block.
     *
     * @access public
     * @return void
     */
    public function contribute()
    {
        session_write_close();
        $cacheKey = $this->config->cacheKeys->block->contribute;
        if(helper::isCacheEnabled() && $this->cache->has($cacheKey))
        {
            $data = $this->cache->get($cacheKey);
        }
        else
        {
            $data = $this->loadModel('user')->getPersonalData();
            if($this->config->cache->enable) $this->cache->set($cacheKey, $data);
        }
        $this->view->data = $data;
        $this->display();
    }


    /**
     * Print block.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function printBlock($id, $module = 'my')
    {
        session_write_close(); // Avoid session blocking.
        $block = $this->block->getByID($id);

        if(empty($block)) return false;

        $html = '';
        if($block->block == 'html')
        {
            if (empty($block->params->html))
            {
                $html = "<div class='empty-tip'>" . $this->lang->block->emptyTip . "</div>";
            }
            else
            {
                $html = "<div class='panel-body'><div class='article-content'>" . $block->params->html . '</div></div>';
            }
        }
        elseif($block->source != '')
        {
            $this->get->set('mode', 'getblockdata');
            $this->get->set('blockTitle', $block->title);
            $this->get->set('module', $block->module);
            $this->get->set('source', $block->source);
            $this->get->set('blockid', $block->block);
            $this->get->set('param', base64_encode(json_encode($block->params)));
            $html = $this->fetch('block', 'main', "module={$block->source}&id=$id");
        }
        elseif($block->block == 'dynamic')
        {
            $html = $this->fetch('block', 'dynamic');
        }
        elseif($block->block == 'guide')
        {
            $html = $this->fetch('block', 'guide', "blockID=$block->id");
        }
        elseif($block->block == 'assigntome')
        {
            $this->get->set('param', base64_encode(json_encode($block->params)));
            $html = $this->fetch('block', 'printAssignToMeBlock', 'longBlock=' . $this->block->isLongBlock($block));
        }
        elseif($block->block == 'welcome')
        {
            $html = $this->fetch('block', 'welcome');
        }
        elseif($block->block == 'contribute')
        {
            $html = $this->fetch('block', 'contribute');
        }

        echo $html;
    }

    /**
     * Main function.
     *
     * @access public
     * @return void
     */
    public function main($module = '', $id = 0)
    {
        session_write_close(); // Avoid session blocking.
        if(!$this->selfCall)
        {
            $lang = str_replace('_', '-', $this->get->lang);
            $this->app->setClientLang($lang);
            $this->app->loadLang('common');
            $this->app->loadLang('block');

            if(!$this->block->checkAPI($this->get->hash)) return;
        }

        $mode = strtolower($this->get->mode);

        if($mode == 'getblocklist')
        {
            $model     = '';
            $block     = $this->block->getByID($id);
            $dashboard = $this->get->dashboard;

            /* Create a project block. */
            if($dashboard == 'project')
            {
                $project = $this->loadModel('project')->getByID($this->session->project);
                $model   = $project->model;
            }

            /* Edit a project block. */
            if($id and $block->module == 'project')
            {
                $model     = $block->type;
                $dashboard = 'project';
            }

            $blocks = $this->block->getAvailableBlocks($module, $dashboard, $model);
            if(!$this->selfCall)
            {
                echo $blocks;
                return true;
            }

            $blocks = json_decode($blocks, true);
            if(empty($blocks)) $blocks = array();
            $blockPairs = array('' => '') + $blocks;

            echo '<div class="form-group">';
            echo '<label for="moduleBlock" class="col-sm-3">' . $this->lang->block->lblBlock . '</label>';
            echo '<div class="col-sm-7">';
            if($model) echo html::hidden('type', $model);
            echo html::select('moduleBlock', $blockPairs, ($block and $block->source != '') ? $block->block : '', "class='form-control chosen'");
            echo '</div></div>';
        }
        elseif($mode == 'getblockform')
        {
            $code = strtolower($this->get->blockid);
            $func = 'get' . ucfirst($code) . 'Params';
            echo $this->block->$func($module);
        }
        elseif($mode == 'getblockdata')
        {
            $code = strtolower($this->get->blockid);
            $this->loadModel('program')->refreshStats();

            $params = $this->get->param;
            $params = json_decode(base64_decode($params));
            if(isset($params->num) and !isset($params->count)) $params->count = $params->num;
            if(!$this->selfCall)
            {
                $this->app->user = $this->dao->select('*')->from(TABLE_USER)->where('ranzhi')->eq($params->account)->fetch();
                if(empty($this->app->user))
                {
                    $this->app->user = new stdclass();
                    $this->app->user->account = 'guest';
                }
                $this->app->user->admin  = strpos($this->app->company->admins, ",{$this->app->user->account},") !== false;
                $this->app->user->rights = $this->loadModel('user')->authorize($this->app->user->account);
                $this->app->user->groups = $this->user->getGroups($this->app->user->account);
                $this->app->user->view   = $this->user->grantUserView($this->app->user->account, $this->app->user->rights['acls']);

                $sso = base64_decode($this->get->sso);
                $this->view->sso  = $sso;
                $this->view->sign = strpos($sso, '?') === false ? '?' : '&';
            }

            if($id) $block = $this->block->getByID($id);
            $this->view->longBlock = $this->block->isLongBlock($id ? $block : $params);
            $this->view->selfCall  = $this->selfCall;
            $this->view->block     = $id ? $block : '';

            $this->viewType    = (isset($params->viewType) and $params->viewType == 'json') ? 'json' : 'html';
            $this->params      = $params;
            $this->view->code  = $this->get->blockid;
            $this->view->title = $this->get->blockTitle;

            $func = 'print' . ucfirst($code) . 'Block';
            if(method_exists('block', $func))
            {
                $this->$func($module);
            }
            else
            {
                $this->view->data = $this->block->$func($module, $params);
            }

            $this->view->moreLink = '';
            if(isset($this->config->block->modules[$module]->moreLinkList->{$code}))
            {
                list($moduleName, $method, $vars) = explode('|', sprintf($this->config->block->modules[$module]->moreLinkList->{$code}, isset($params->type) ? $params->type : ''));
                $this->view->moreLink = $this->createLink($moduleName, $method, $vars);
            }

            if($this->viewType == 'json')
            {
                unset($this->view->app);
                unset($this->view->config);
                unset($this->view->lang);
                unset($this->view->header);
                unset($this->view->position);
                unset($this->view->moduleTree);

                $output['status'] = is_object($this->view) ? 'success' : 'fail';
                $output['data']   = json_encode($this->view);
                $output['md5']    = md5(json_encode($this->view));
                return print(json_encode($output));
            }

            $this->display();
        }
    }

    /**
     * Print List block.
     *
     * @access public
     * @return void
     */
    public function printListBlock($module = 'product')
    {
        $func = 'print' . ucfirst($module) . 'Block';
        $this->view->module = $module;
        $this->$func();

    }

    /**
     * Print todo block.
     *
     * @access public
     * @return void
     */
    public function printTodoBlock()
    {
        $limit = ($this->viewType == 'json' or !isset($this->params->count)) ? 0 : (int)$this->params->count;
        $todos = $this->loadModel('todo')->getList('all', $this->app->user->account, 'wait, doing', $limit, $pager = null, $orderBy = 'date, begin');
        $uri   = $this->createLink('my', 'index');

        $this->session->set('todoList',     $uri, 'my');
        $this->session->set('bugList',      $uri, 'qa');
        $this->session->set('taskList',     $uri, 'execution');
        $this->session->set('storyList',    $uri, 'product');
        $this->session->set('testtaskList', $uri, 'qa');
        session_write_close();

        $tasks = $this->loadModel('task')->getUserSuspendedTasks($this->app->user->account);
        foreach($todos as $key => $todo)
        {
            if($todo->date == '2030-01-01')
            {
                unset($todos[$key]);
                continue;
            }
            if($todo->type == 'task' and isset($tasks[$todo->idvalue])) unset($todos[$key]);
        }

        $this->view->todos = $todos;
    }

    /**
     * Print task block.
     *
     * @access public
     * @return void
     */
    public function printTaskBlock()
    {
        $this->session->set('taskList',  $this->createLink('my', 'index'), 'execution');
        session_write_close();
        if(preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) return;

        $account = $this->app->user->account;
        $type    = $this->params->type;

        $this->app->loadLang('execution');
        $this->view->tasks = $this->loadModel('task')->getUserTasks($account, $type, $this->viewType == 'json' ? 0 : (int)$this->params->count, null, $this->params->orderBy);
    }

    /**
     * Print bug block.
     *
     * @access public
     * @return void
     */
    public function printBugBlock()
    {
        $this->session->set('bugList', $this->createLink('my', 'index'), 'qa');
        session_write_close();
        if(preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) return;

        $projectID = $this->lang->navGroup->qa  == 'project' ? $this->session->project : 0;
        $projectID = $this->view->block->module == 'my' ? 0 : $projectID;
        $this->view->bugs = $this->loadModel('bug')->getUserBugs($this->app->user->account, $this->params->type, $this->params->orderBy, $this->viewType == 'json' ? 0 : (int)$this->params->count, null, $projectID);
    }

    /**
     * Print case block.
     *
     * @access public
     * @return void
     */
    public function printCaseBlock()
    {
        $this->session->set('caseList', $this->createLink('my', 'index'), 'qa');
        session_write_close();
        $this->app->loadLang('testcase');
        $this->app->loadLang('testtask');

        $projectID = $this->lang->navGroup->qa  == 'project' ? $this->session->project : 0;
        $projectID = $this->view->block->module == 'my' ? 0 : $projectID;

        $cases = array();
        if($this->params->type == 'assigntome')
        {
            $cases = $this->dao->select('t1.assignedTo AS assignedTo, t2.*')->from(TABLE_TESTRUN)->alias('t1')
                ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
                ->leftJoin(TABLE_TESTTASK)->alias('t3')->on('t1.task = t3.id')
                ->Where('t1.assignedTo')->eq($this->app->user->account)
                ->andWhere('t1.status')->ne('done')
                ->andWhere('t3.status')->ne('done')
                ->andWhere('t3.deleted')->eq(0)
                ->andWhere('t2.deleted')->eq(0)
                ->beginIF($projectID)->andWhere('t2.project')->eq($projectID)->fi()
                ->orderBy($this->params->orderBy)
                ->beginIF($this->viewType != 'json')->limit((int)$this->params->count)->fi()
                ->fetchAll();
        }
        elseif($this->params->type == 'openedbyme')
        {
            $cases = $this->dao->findByOpenedBy($this->app->user->account)->from(TABLE_CASE)
                ->andWhere('deleted')->eq(0)
                ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
                ->orderBy($this->params->orderBy)
                ->beginIF($this->viewType != 'json')->limit((int)$this->params->count)->fi()
                ->fetchAll();
        }
        $this->view->cases = $cases;
    }

    /**
     * Print testtask block.
     *
     * @access public
     * @return void
     */
    public function printTesttaskBlock()
    {
        $this->app->loadLang('testtask');

        $uri = $this->createLink('my', 'index');
        $this->session->set('productList',  $uri, 'product');
        $this->session->set('testtaskList', $uri, 'qa');
        $this->session->set('buildList',    $uri, 'execution');
        session_write_close();
        if(preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) return;

        $this->view->projects  = $this->loadModel('project')->getPairsByProgram();
        $this->view->testtasks = $this->dao->select('t1.*,t2.name as productName,t2.shadow,t3.name as buildName,t4.name as projectName')->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_BUILD)->alias('t3')->on('t1.build=t3.id')
            ->leftJoin(TABLE_PROJECT)->alias('t4')->on('t1.execution=t4.id')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t5')->on('t1.execution=t5.project')
            ->where('t1.deleted')->eq('0')
            ->beginIF(!$this->app->user->admin)->andWhere('t1.product')->in($this->app->user->view->products)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t1.execution')->in($this->app->user->view->sprints)->fi()
            ->andWhere('t1.product = t5.product')
            ->beginIF($this->params->type != 'all')->andWhere('t1.status')->eq($this->params->type)->fi()
            ->orderBy('t1.id desc')
            ->beginIF($this->viewType != 'json')->limit((int)$this->params->count)->fi()
            ->fetchAll();
    }

    /**
     * Print story block.
     *
     * @access public
     * @return void
     */
    public function printStoryBlock()
    {
        $this->session->set('storyList', $this->createLink('my', 'index'), 'product');
        session_write_close();
        if(preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) return;

        $this->app->loadClass('pager', $static = true);
        $count   = isset($this->params->count) ? (int)$this->params->count : 0;
        $pager   = pager::init(0, $count , 1);
        $type    = isset($this->params->type) ? $this->params->type : 'assignedTo';
        $orderBy = isset($this->params->type) ? $this->params->orderBy : 'id_asc';

        $this->view->stories = $this->loadModel('story')->getUserStories($this->app->user->account, $type, $orderBy, $this->viewType != 'json' ? $pager : '', 'story');
    }

    /**
     * Print plan block.
     *
     * @access public
     * @return void
     */
    public function printPlanBlock()
    {
        $uri = $this->createLink('my', 'index');
        $this->session->set('productList', $uri, 'product');
        $this->session->set('productPlanList', $uri, 'product');
        session_write_close();

        $this->app->loadLang('productplan');
        $this->view->plans = $this->dao->select('t1.*,t2.name as productName')->from(TABLE_PRODUCTPLAN)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.shadow')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('t1.product')->in($this->app->user->view->products)->fi()
            ->orderBy('t1.begin desc')
            ->beginIF($this->viewType != 'json')->limit((int)$this->params->count)->fi()
            ->fetchAll();
    }

    /**
     * Print releases block.
     *
     * @access public
     * @return void
     */
    public function printReleaseBlock()
    {
        $uri = $this->createLink('my', 'index');
        $this->session->set('releaseList', $uri, 'product');
        $this->session->set('buildList', $uri, 'execution');
        session_write_close();

        $this->app->loadLang('release');
        $this->view->releases = $this->dao->select('t1.*,t2.name as productName,t3.name as buildName')->from(TABLE_RELEASE)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_BUILD)->alias('t3')->on('t1.build=t3.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.shadow')->eq(0)
            ->beginIF($this->view->block->module != 'my' and $this->session->project)->andWhere('t1.project')->eq((int)$this->session->project)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t1.product')->in($this->app->user->view->products)->fi()
            ->orderBy('t1.id desc')
            ->beginIF($this->viewType != 'json')->limit((int)$this->params->count)->fi()
            ->fetchAll();
    }

    /**
     * Print Build block.
     *
     * @access public
     * @return void
     */
    public function printBuildBlock()
    {
        $this->session->set('buildList', $this->createLink('my', 'index'), 'execution');
        session_write_close();
        $this->app->loadLang('build');

        $builds = $this->dao->select('t1.*, t2.name AS productName, t2.shadow, t3.name AS projectName')->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t1.project=t3.id')
            ->where('t1.deleted')->eq('0')
            ->beginIF(!$this->app->user->admin)->andWhere('t1.execution')->in($this->app->user->view->sprints)->fi()
            ->beginIF($this->view->block->module != 'my' and $this->session->project)->andWhere('t1.project')->eq((int)$this->session->project)->fi()
            ->orderBy('t1.id desc')
            ->beginIF($this->viewType != 'json')->limit((int)$this->params->count)->fi()
            ->fetchAll();
        $this->view->builds = $builds;
    }

    /**
     * Print project block.
     *
     * @access public
     * @return void
     */
    public function printProjectBlock()
    {
        $this->app->loadLang('execution');
        $this->app->loadLang('task');
        $count   = isset($this->params->count)   ? $this->params->count   : 15;
        $type    = isset($this->params->type)    ? $this->params->type    : 'all';
        $orderBy = isset($this->params->orderBy) ? $this->params->orderBy : 'id_desc';

        $this->view->projects = $this->loadModel('project')->getOverviewList('byStatus', $type, $orderBy, $count);
        $this->view->users    = $this->loadModel('user')->getPairs('noletter', '', 0, array_unique(helper::arrayColumn($this->view->projects, 'PM')));
    }

    /**
     * Print product block.
     *
     * @access public
     * @return void
     */
    public function printProductBlock()
    {
        $this->app->loadClass('pager', $static = true);
        if(!empty($this->params->type) and preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) return;
        $count = isset($this->params->count) ? (int)$this->params->count : 0;
        $type  = isset($this->params->type) ? $this->params->type : '';
        $pager = pager::init(0, $count, 1);

        $productStats  = $this->loadModel('product')->getStats('order_desc', $this->viewType != 'json' ? $pager : '', $type);
        $productIdList = array();
        foreach($productStats as $product) $productIdList[] = $product->id;

        $this->app->loadLang('project');
        $executions = $this->dao->select('t1.product,t2.id,t2.project,t2.name,t2.multiple')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.product')->in($productIdList)
            ->andWhere('t2.type')->in('stage,sprint')
            ->andWhere('t2.deleted')->eq(0)
            ->orderBy('t1.project')
            ->fetchAll('product');

        $executionPairs = array();
        $noMultiples    = array();
        foreach($executions as $execution)
        {
            if(empty($execution->multiple)) $noMultiples[$execution->product] = $execution->project;
            $executionPairs[$execution->product] = $execution->name;
        }
        if($noMultiples)
        {
            $noMultipleProjects = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('id')->in($noMultiples)->fetchPairs('id', 'name');
            foreach($noMultiples as $productID => $projectID)
            {
                if(isset($noMultipleProjects[$projectID])) $executionPairs[$productID] = $noMultipleProjects[$projectID] . "({$this->lang->project->disableExecution})";
            }
        }

        $this->view->executions   = $executionPairs;
        $this->view->productStats = $productStats;
    }

    /**
     * Print statistic block.
     *
     * @param  string $module
     * @access public
     * @return void
     */
    public function printStatisticBlock($module = 'product')
    {
        $func = 'print' . ucfirst($module) . 'StatisticBlock';
        $this->view->module = $module;
        $this->$func();
    }

    /**
     * Print project statistic block.
     *
     * @access public
     * @return void
     */
    public function printProjectStatisticBlock()
    {
        if(!empty($this->params->type) and preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) return;

        /* Load models and langs. */
        $this->loadModel('project');
        $this->loadModel('weekly');
        $this->loadModel('execution');
        $this->app->loadLang('task');
        $this->app->loadLang('story');
        $this->app->loadLang('bug');

        /* Set project status and count. */
        $status = isset($this->params->type)  ? $this->params->type       : 'all';
        $count  = isset($this->params->count) ? (int)$this->params->count : 15;

        /* Get projects. */
        $projects      = $this->project->getOverviewList('byStatus', $status, 'order_asc', $count, '');
        if(empty($projects))
        {
            $this->view->projects = $projects;
            return false;
        }

        $cacheKey = sprintf($this->config->cacheKeys->block->projectStatistic, $status, $count, '');
        if(helper::isCacheEnabled() && $this->cache->has($cacheKey))
        {
            $projects = $this->cache->get($cacheKey);
        }
        else
        {
            $today  = helper::today();
            $monday = date('Ymd', strtotime($this->weekly->getThisMonday($today)));

            $projectExecutions = $this->dao->select('*')->from(TABLE_EXECUTION)
                ->where('project')->in(array_keys($projects))
                ->andWhere('deleted')->eq('0')
                ->andWhere('type')->in('sprint,stage,kanban')
                ->groupBy('project')
                ->orderBy('id_desc')
                ->fetchAll('project');

            foreach($projects as $projectID => $project)
            {
                if(in_array($project->model, array('scrum', 'kanban', 'agileplus')))
                {
                    $project->execution = zget($projectExecutions, $projectID, array());
                }
                elseif(in_array($project->model, array('waterfall', 'waterfallplus', 'ipd')))
                {
                    $begin   = $project->begin;
                    $weeks   = $this->weekly->getWeekPairs($begin);
                    $current = zget($weeks, $monday, '');
                    $current = substr($current, 0, -11) . substr($current, -6);

                    $PVEV = $this->weekly->getPVEV($projectID, $today);
                    $project->pv = $PVEV['PV'];
                    $project->ev = $PVEV['EV'];
                    $project->ac = $this->weekly->getAC($projectID, $today);
                    $project->sv = $this->weekly->getSV($project->ev, $project->pv);
                    $project->cv = $this->weekly->getCV($project->ev, $project->ac);

                    $left              = (float)$this->weekly->getLeft($project->id, $today);
                    $project->progress = ($project->ac + $left) ? floor($project->ac / ($project->ac + $left) * 1000) / 1000 * 100 : 0;
                    $project->progress = $project->progress > 100 ? 100 : $project->progress;
                    $project->current  = $current;
                }
            }

            if($this->config->cache->enable) $this->cache->set($cacheKey, $projects);
        }

        $this->view->projects = $projects;
    }

    /**
     * Print product statistic block.
     *
     * @access public
     * @param  string $storyType requirement|story
     * @return void
     */
    public function printProductStatisticBlock($storyType = 'story')
    {
        if(!empty($this->params->type) and preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) return;

        $status = isset($this->params->type) ? $this->params->type : '';
        $count  = isset($this->params->count) ? $this->params->count : '';

        $products      = $this->loadModel('product')->getOrderedProducts($status, $count);
        $productIdList = array_keys($products);

        if(empty($products))
        {
            $this->view->products = $products;
            return false;
        }

        /* Get stories. */
        $stories = $this->dao->select('product, stage, COUNT(status) AS count')->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere('product')->in($productIdList)
            ->beginIF($storyType)->andWhere('type')->eq($storyType)->fi()
            ->groupBy('product, stage')
            ->fetchGroup('product', 'stage');

        /* Padding the stories to sure all status have records. */
        foreach($stories as $product => $story)
        {
            foreach(array_keys($this->lang->story->stageList) as $stage)
            {
                $story[$stage] = isset($story[$stage]) ? $story[$stage]->count : 0;
            }
            $stories[$product] = $story;
        }

        /* Get plans. */
        $plans = $this->dao->select('product, end')->from(TABLE_PRODUCTPLAN)
            ->where('deleted')->eq(0)
            ->andWhere('product')->in($productIdList)
            ->fetchGroup('product');
        foreach($plans as $product => $productPlans)
        {
            $expired   = 0;
            $unexpired = 0;

            foreach($productPlans as $plan)
            {
                if($plan->end <  helper::today()) $expired++;
                if($plan->end >= helper::today()) $unexpired++;
            }

            $plan = array();
            $plan['expired']   = $expired;
            $plan['unexpired'] = $unexpired;

            $plans[$product] = $plan;
        }

        /* Get releases. */
        $releases = $this->dao->select('product, status, COUNT(*) AS count')->from(TABLE_RELEASE)
            ->where('deleted')->eq(0)
            ->andWhere('product')->in($productIdList)
            ->groupBy('product, status')
            ->fetchGroup('product', 'status');
        foreach($releases as $product => $release)
        {
            $release['normal']    = isset($release['normal'])    ? $release['normal']->count    : 0;
            $release['terminate'] = isset($release['terminate']) ? $release['terminate']->count : 0;

            $releases[$product] = $release;
        }

        /* Get last releases. */
        $lastReleases = $this->dao->select('product, COUNT(*) AS count')->from(TABLE_RELEASE)
            ->where('date')->eq(date('Y-m-d', strtotime('-1 day')))
            ->andWhere('product')->in($productIdList)
            ->groupBy('product')
            ->fetchPairs();

        foreach($products as $productID => $product)
        {
            $product->stories     = isset($stories[$productID])      ? $stories[$productID]      : 0;
            $product->plans       = isset($plans[$productID])        ? $plans[$productID]        : 0;
            $product->releases    = isset($releases[$productID])     ? $releases[$productID]     : 0;
            $product->lastRelease = isset($lastReleases[$productID]) ? $lastReleases[$productID] : 0;
        }

        $this->app->loadLang('story');
        $this->app->loadLang('productplan');
        $this->app->loadLang('release');

        $this->view->products = $products;
    }

    /**
     * Print execution statistic block.
     *
     * @access public
     * @return void
     */
    public function printExecutionStatisticBlock()
    {
        if(!empty($this->params->type) and preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) return;

        $this->app->loadLang('task');
        $this->app->loadLang('story');
        $this->app->loadLang('bug');

        $status  = isset($this->params->type)  ? $this->params->type : 'undone';
        $count   = isset($this->params->count) ? (int)$this->params->count : 0;

        /* Get projects. */
        $projectID  = $this->view->block->module == 'my' ? 0 : (int)$this->session->project;
        $executions = $this->loadModel('execution')->getOrderedExecutions($projectID, $status, $count, 'skipparent');
        if(empty($executions))
        {
            $this->view->executions = $executions;
            return false;
        }

        $executionIdList = array_keys($executions);

        /* Get tasks. Fix bug #2918.*/
        $yesterday  = date('Y-m-d', strtotime('-1 day'));
        $taskGroups = $this->dao->select("id,parent,execution,status,finishedDate,estimate,consumed,`left`")->from(TABLE_TASK)
            ->where('execution')->in($executionIdList)
            ->andWhere('deleted')->eq(0)
            ->fetchGroup('execution', 'id');

        $tasks = array();
        foreach($taskGroups as $executionID => $taskGroup)
        {
            $undoneTasks       = 0;
            $yesterdayFinished = 0;
            $totalEstimate     = 0;
            $totalConsumed     = 0;
            $totalLeft         = 0;

            foreach($taskGroup as $taskID => $task)
            {
                if(strpos('wait|doing|pause|cancel', $task->status) !== false) $undoneTasks ++;
                if(strpos($task->finishedDate, $yesterday) !== false) $yesterdayFinished ++;

                if($task->parent == '-1') continue;

                $totalConsumed += $task->consumed;
                $totalEstimate += $task->estimate;
                if($task->status != 'cancel' and $task->status != 'closed') $totalLeft += $task->left;
            }

            $executions[$executionID]->totalTasks        = count($taskGroup);
            $executions[$executionID]->undoneTasks       = $undoneTasks;
            $executions[$executionID]->yesterdayFinished = $yesterdayFinished;
            $executions[$executionID]->totalEstimate     = round($totalEstimate, 1);
            $executions[$executionID]->totalConsumed     = round($totalConsumed, 1);
            $executions[$executionID]->totalLeft         = round($totalLeft, 1);
        }

        /* Get stories. */
        $stories = $this->dao->select("t1.project, count(t2.status) as totalStories, count(t2.status != 'closed' or null) as unclosedStories, count(t2.stage = 'released' or null) as releasedStories")->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->where('t1.project')->in($executionIdList)
            ->andWhere('t2.deleted')->eq(0)
            ->groupBy('project')
            ->fetchAll('project');

        foreach($stories as $executionID => $story)
        {
            foreach($story as $key => $value)
            {
                if($key == 'project') continue;
                $executions[$executionID]->$key = $value;
            }
        }

        /* Get bugs. */
        $bugs = $this->dao->select("execution, count(status) as totalBugs, count(status = 'active' or null) as activeBugs, count(resolvedDate like '{$yesterday}%' or null) as yesterdayResolved")->from(TABLE_BUG)
            ->where('execution')->in($executionIdList)
            ->andWhere('deleted')->eq(0)
            ->groupBy('execution')
            ->fetchAll('execution');

        foreach($bugs as $executionID => $bug)
        {
            foreach($bug as $key => $value)
            {
                if($key == 'project') continue;
                $executions[$executionID]->$key = $value;
            }
        }

        foreach($executions as $execution)
        {
            if(!isset($executions[$execution->id]->totalTasks))
            {
                $executions[$execution->id]->totalTasks        = 0;
                $executions[$execution->id]->undoneTasks       = 0;
                $executions[$execution->id]->yesterdayFinished = 0;
                $executions[$execution->id]->totalEstimate     = 0;
                $executions[$execution->id]->totalConsumed     = 0;
                $executions[$execution->id]->totalLeft         = 0;
            }
            if(!isset($executions[$execution->id]->totalBugs))
            {
                $executions[$execution->id]->totalBugs         = 0;
                $executions[$execution->id]->activeBugs        = 0;
                $executions[$execution->id]->yesterdayResolved = 0;
            }
            if(!isset($executions[$execution->id]->totalStories))
            {
                $executions[$execution->id]->totalStories    = 0;
                $executions[$execution->id]->unclosedStories = 0;
                $executions[$execution->id]->releasedStories = 0;
            }

            $executions[$execution->id]->progress      = ($execution->totalConsumed || $execution->totalLeft) ? round($execution->totalConsumed / ($execution->totalConsumed + $execution->totalLeft), 3) * 100 : 0;
            $executions[$execution->id]->taskProgress  = $execution->totalTasks ? round(($execution->totalTasks - $execution->undoneTasks) / $execution->totalTasks, 2) * 100 : 0;
            $executions[$execution->id]->storyProgress = $execution->totalStories ? round(($execution->totalStories - $execution->unclosedStories) / $execution->totalStories, 2) * 100 : 0;
            $executions[$execution->id]->bugProgress   = $execution->totalBugs ? round(($execution->totalBugs - $execution->activeBugs) / $execution->totalBugs, 2) * 100 : 0;
        }

        $this->view->executions = $executions;
    }

    /**
     * Print waterfall report block.
     *
     * @access public
     * @return void
     */
    public function printWaterfallReportBlock()
    {
        $this->app->loadLang('programplan');
        $project = $this->loadModel('project')->getByID($this->session->project);
        $today   = helper::today();
        $date    = date('Ymd', strtotime('this week Monday'));
        $begin   = $project->begin;
        $weeks   = $this->loadModel('weekly')->getWeekPairs($begin);
        $current = zget($weeks, $date, '');

        $this->weekly->save($this->session->project, $date);

        $PVEV = $this->weekly->getPVEV($this->session->project, $today);
        $this->view->pv = (float)$PVEV['PV'];
        $this->view->ev = (float)$PVEV['EV'];
        $this->view->ac = (float)$this->weekly->getAC($this->session->project, $today);
        $this->view->sv = $this->weekly->getSV($this->view->ev, $this->view->pv);
        $this->view->cv = $this->weekly->getCV($this->view->ev, $this->view->ac);

        $left     = (float)$this->weekly->getLeft($this->session->project, $today);
        $progress = ($this->view->ac + $left) ? floor($this->view->ac / ($this->view->ac + $left) * 1000) / 1000 * 100 : 0;
        $this->view->progress = $progress > 100 ? 100 : $progress;
        $this->view->current  = $current;
    }

    /**
     * Print waterfall general report block.
     *
     * @access public
     * @return void
     */
    public function printWaterfallGeneralReportBlock()
    {
        $this->app->loadLang('programplan');
        $this->loadModel('project');
        $this->loadModel('weekly');

        $data = $this->project->getWaterfallPVEVAC($this->session->project);
        $this->view->pv = (float)$data['PV'];
        $this->view->ev = (float)$data['EV'];
        $this->view->ac = (float)$data['AC'];
        $this->view->sv = $this->weekly->getSV($this->view->ev, $this->view->pv);
        $this->view->cv = $this->weekly->getCV($this->view->ev, $this->view->ac);

        $left     = (float)$data['left'];
        $progress = (!empty($this->view->ac) or !empty($left)) ? floor($this->view->ac / ($this->view->ac + $left) * 1000) / 1000 * 100 : 0;
        $this->view->progress = $progress > 100 ? 100 : $progress;
    }

    /**
     * Print waterfall gantt block.
     *
     * @access public
     * @return void
     */
    public function printWaterfallGanttBlock()
    {
        $products  = $this->loadModel('product')->getProductPairsByProject($this->session->project);
        $productID = $this->session->product ? $this->session->product : 0;
        $productID = isset($products[$productID]) ? $productID : key($products);

        $this->view->plans     = $this->loadModel('programplan')->getDataForGantt($this->session->project, $productID, 0, 'task', false);
        $this->view->products  = $products;
        $this->view->productID = $productID;
    }

    /**
     * Print waterfall issue block.
     *
     * @access public
     * @return void
     */
    public function printWaterfallIssueBlock()
    {
        $uri = $this->app->tab == 'my' ? $this->createLink('my', 'index') : $this->server->http_referer;
        $this->session->set('issueList', $uri, 'project');
        session_write_close();
        if(preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) return;
        $this->view->users  = $this->loadModel('user')->getPairs('noletter');
        $this->view->issues = $this->loadModel('issue')->getBlockIssues($this->session->project, $this->params->type, $this->viewType == 'json' ? 0 : (int)$this->params->count, $this->params->orderBy);
    }

    /**
     * Print waterfall risk block.
     *
     * @access public
     * @return void
     */
    public function printWaterfallRiskBlock()
    {
        $uri = $this->app->tab == 'my' ? $this->createLink('my', 'index') : $this->server->http_referer;
        $this->session->set('riskList', $uri, 'project');
        session_write_close();
        $this->view->users = $this->loadModel('user')->getPairs('noletter');
        $this->view->risks = $this->loadModel('risk')->getBlockRisks($this->session->project, $this->params->type, $this->viewType == 'json' ? 0 : (int)$this->params->count, $this->params->orderBy);
    }

    /**
     * Print waterfall estimate block.
     *
     * @access public
     * @return void
     */
    public function printWaterfallEstimateBlock()
    {
        $this->app->loadLang('durationestimation');
        $this->loadModel('project');

        $projectID = $this->session->project;
        $members   = $this->loadModel('user')->getTeamMemberPairs($projectID, 'project');
        $budget    = $this->loadModel('workestimation')->getBudget($projectID);
        $workhour  = $this->loadModel('project')->getWorkhour($projectID);
        if(empty($budget)) $budget = new stdclass();

        $this->view->people    = $this->dao->select('sum(people) as people')->from(TABLE_DURATIONESTIMATION)->where('project')->eq($this->session->project)->fetch('people');
        $this->view->members   = count($members) ? count($members) - 1 : 0;
        $this->view->consumed  = $this->dao->select('sum(cast(consumed as decimal(10,2))) as consumed')->from(TABLE_TASK)->where('project')->eq($projectID)->andWhere('deleted')->eq(0)->andWhere('parent')->lt(1)->fetch('consumed');
        $this->view->budget    = $budget;
        $this->view->totalLeft = (float)$workhour->totalLeft;
    }

    /**
     * Print waterfall progress block.
     *
     * @access public
     * @return void
     */
    public function printWaterfallProgressBlock()
    {
        $this->loadModel('milestone');
        $this->loadModel('weekly');
        $this->app->loadLang('execution');

        $projectID = $this->session->project;
        $project   = $this->loadModel('project')->getByID($projectID);

        $projectWeekly = $this->dao->select('*')->from(TABLE_WEEKLYREPORT)->where('project')->eq($projectID)->orderBy('weekStart_asc')->fetchAll('weekStart');

        $charts['PV'] = '[';
        $charts['EV'] = '[';
        $charts['AC'] = '[';
        foreach($projectWeekly as $weekStart => $data)
        {
            $charts['labels'][] = $weekStart;
            $charts['PV']      .= $data->pv . ',';
            $charts['EV']      .= $data->ev . ',';
            $charts['AC']      .= $data->ac . ',';
        }

        $charts['PV'] .= ']';
        $charts['EV'] .= ']';
        $charts['AC'] .= ']';

        $this->view->charts = $charts;
    }

    /**
     * Print srcum project block.
     *
     * @access public
     * @return void
     */
    public function printScrumOverviewBlock()
    {
        $projectID = $this->session->project;
        $this->app->loadLang('execution');
        $this->app->loadLang('bug');
        $totalData = $this->loadModel('project')->getOverviewList('byId', $projectID, 'id_desc', 1);

        $this->view->totalData = $totalData;
        $this->view->projectID = $projectID;
    }

    /**
     * Print srcum project list block.
     *
     * @access public
     * @return void
     */
    public function printScrumListBlock()
    {
        if(!empty($this->params->type) and preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) return;
        $count = isset($this->params->count) ? (int)$this->params->count : 15;
        $type  = isset($this->params->type) ? $this->params->type : 'undone';

        $this->app->loadClass('pager', $static = true);
        $pager = pager::init(0, $count, 1);
        $this->loadModel('execution');
        $this->view->executionStats = !defined('TUTORIAL') ? $this->execution->getStatData($this->session->project, $type, 0, 0, false, '', 'id_desc', $pager) : array($this->loadModel('tutorial')->getExecution());
    }

    /**
     * Print srcum product block.
     *
     * @access public
     * @return void
     */
    public function printScrumProductBlock()
    {
        $stories  = array();
        $bugs     = array();
        $releases = array();
        $count    = isset($this->params->count) ? (int)$this->params->count : 15;

        $products      = $this->dao->select('id, name')->from(TABLE_PRODUCT)->where('program')->eq($this->session->program)->limit(15)->fetchPairs();
        $productIdList = array_keys($products);
        if(!empty($productIdList))
        {
            $fields   = 'product, count(*) as total';
            $stories  = $this->dao->select($fields)->from(TABLE_STORY)->where('product')->in($productIdList)->andWhere('deleted')->eq('0')->groupBy('product')->fetchPairs();
            $bugs     = $this->dao->select($fields)->from(TABLE_BUG)->where('product')->in($productIdList)->andWhere('deleted')->eq('0')->groupBy('product')->fetchPairs();
            $releases = $this->dao->select($fields)->from(TABLE_RELEASE)->where('product')->in($productIdList)->andWhere('deleted')->eq('0')->groupBy('product')->fetchPairs();
        }

        $this->view->products = $products;
        $this->view->stories  = $stories;
        $this->view->bugs     = $bugs;
        $this->view->releases = $releases;
    }

    /**
     * Print scrum issue block.
     *
     * @access public
     * @return void
     */
    public function printScrumIssueBlock()
    {
        $uri = $this->app->tab == 'my' ? $this->createLink('my', 'index') : $this->server->http_referer;
        $this->session->set('issueList', $uri, 'project');
        session_write_close();
        if(preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) return;
        $this->view->users  = $this->loadModel('user')->getPairs('noletter');
        $this->view->issues = $this->loadModel('issue')->getBlockIssues($this->session->project, $this->params->type, $this->viewType == 'json' ? 0 : (int)$this->params->count, $this->params->orderBy);
    }

    /**
     * Print scrum risk block.
     *
     * @access public
     * @return void
     */
    public function printScrumRiskBlock()
    {
        $uri = $this->app->tab == 'my' ? $this->createLink('my', 'index') : $this->server->http_referer;
        $this->session->set('riskList', $uri, 'project');
        session_write_close();
        $this->view->users = $this->loadModel('user')->getPairs('noletter');
        $this->view->risks = $this->loadModel('risk')->getBlockRisks($this->session->project, $this->params->type, $this->viewType == 'json' ? 0 : (int)$this->params->count, $this->params->orderBy);
    }

    /**
     * Print sprint block.
     *
     * @access public
     * @return void
     */
    public function printSprintBlock()
    {
        $sprints = $this->dao->select('status, count(*) as sprints')->from(TABLE_EXECUTION)
            ->where('deleted')->eq(0)
            ->beginIF($this->config->vision == 'lite')->andWhere('type')->eq('kanban')->fi()
            ->beginIF($this->config->vision == 'rnd')->andWhere('type')->in('sprint,kanban')->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi()
            ->andWhere('project')->eq($this->session->project)
            ->groupBy('status')
            ->fetchPairs();

        $summary = new stdclass();
        $summary->total  = array_sum($sprints);
        $summary->doing  = zget($sprints, 'doing', 0);
        $summary->closed = zget($sprints, 'closed', 0);

        $progress = new stdclass();
        $progress->doing  = $summary->total == 0 ? 0 : round($summary->doing  / $summary->total, 3);
        $progress->closed = $summary->total == 0 ? 0 : round($summary->closed / $summary->total, 3);

        $this->view->summary  = $summary;
        $this->view->progress = $progress;
    }

    /**
     * Print project dynamic block.
     *
     * @access public
     * @return void
     */
    public function printProjectDynamicBlock()
    {
        $projectID = $this->session->project;

        $this->view->actions = $this->loadModel('action')->getDynamic('all', 'all', 'id_desc', 30, 'all', $projectID);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter', '', 0, array_unique(helper::arrayColumn($this->view->actions, 'actor')));
    }

    /**
     * Print srcum road map block.
     *
     * @param  int    $productID
     * @param  int    $roadMapID
     * @access public
     * @return void
     */
    public function printScrumRoadMapBlock($productID = 0, $roadMapID = 0)
    {
        $uri = $this->app->tab == 'my' ? $this->createLink('my', 'index') : $this->server->http_referer;
        $this->session->set('releaseList',     $uri, 'product');
        $this->session->set('productPlanList', $uri, 'product');
        session_write_close();

        $products  = $this->loadModel('product')->getPairs('', $this->session->project);
        if(!is_numeric($productID)) $productID = key($products);

        $this->view->roadmaps  = $this->product->getRoadmap($productID, 0, 6);
        $this->view->productID = $productID;
        $this->view->roadMapID = $roadMapID;
        $this->view->products  = $products;
        $this->view->sync      = 1;

        if($_POST)
        {
            $this->view->sync = 0;
            $this->display('block', 'scrumroadmapblock');
        }
    }

    /**
     * Print srcum test block.
     *
     * @access public
     * @return void
     */
    public function printScrumTestBlock()
    {
        $uri = $this->app->tab == 'my' ? $this->createLink('my', 'index') : $this->server->http_referer;
        $this->session->set('testtaskList', $uri, 'qa');
        $this->session->set('productList',  $uri, 'product');
        $this->session->set('projectList',  $uri, 'project');
        $this->session->set('buildList',    $uri, 'execution');
        session_write_close();
        $this->app->loadLang('testtask');

        $count  = zget($this->params, 'count', 10);
        $status = isset($this->params->type)  ? $this->params->type : 'wait';

        $this->view->project   = $this->loadModel('project')->getByID($this->session->project);
        $this->view->testtasks = $this->dao->select('t1.*,t2.name as productName,t3.name as buildName,t4.name as projectName')
            ->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_BUILD)->alias('t3')->on('t1.build=t3.id')
            ->leftJoin(TABLE_PROJECT)->alias('t4')->on('t1.project=t4.id')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t5')->on('t1.project=t5.project')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.project')->eq($this->session->project)->fi()
            ->andWhere('t1.product = t5.product')
            ->beginIF($status != 'all')->andWhere('t1.status')->eq($status)->fi()
            ->orderBy('t1.id desc')
            ->limit($count)
            ->fetchAll();
    }

    /**
     * Print qa statistic block.
     *
     * @access public
     * @return void
     */
    public function printQaStatisticBlock()
    {
        if(!empty($this->params->type) and preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) return;

        $this->app->loadLang('bug');
        $status = isset($this->params->type)  ? $this->params->type : '';
        $count  = isset($this->params->count) ? (int)$this->params->count : 0;

        $projectID  = $this->lang->navGroup->qa == 'project' ? $this->session->project : 0;
        $products   = $this->loadModel('product')->getOrderedProducts($status, $count, $projectID, 'all');
        $executions = $this->loadModel('execution')->getPairs($projectID, 'all', 'empty|withdelete');
        if(empty($products))
        {
            $this->view->products = $products;
            return false;
        }

        $productIdList = array_keys($products);
        $today         = date(DT_DATE1);
        $yesterday     = date(DT_DATE1, strtotime('yesterday'));
        $testtasks     = $this->dao->select('*')->from(TABLE_TESTTASK)->where('product')->in($productIdList)->andWhere('project')->ne(0)->andWhere('deleted')->eq(0)->orderBy('id')->fetchAll('product');
        $bugs          = $this->dao->select("product, count(id) as total,
            count(IF(assignedTo = '{$this->app->user->account}', 1, null)) as assignedToMe,
            count(IF(status != 'closed', 1, null)) as unclosed,
            count(IF(status != 'closed' and status != 'resolved', 1, null)) as unresolved,
            count(IF(confirmed = '0' and toStory = '0', 1, null)) as unconfirmed,
            count(IF(resolvedDate >= '$yesterday' and resolvedDate < '$today', 1, null)) as yesterdayResolved,
            count(IF(closedDate >= '$yesterday' and closedDate < '$today', 1, null)) as yesterdayClosed")
            ->from(TABLE_BUG)
            ->where('product')->in($productIdList)
            ->andWhere('execution')->in(array_keys($executions))
            ->andWhere('deleted')->eq(0)
            ->groupBy('product')
            ->fetchAll('product');

        $confirmedBugs = $this->dao->select('product')->from(TABLE_ACTION)
            ->where('objectType')->eq('bug')
            ->andWhere('action')->eq('bugconfirmed')
            ->andWhere('date')->ge($yesterday)
            ->andWhere('date')->lt($today)
            ->fetchAll();
        $productConfirmedBugs = array();
        foreach($confirmedBugs as $bug)
        {
            if(!isset($productConfirmedBugs[$bug->product])) $productConfirmedBugs[$bug->product] = 0;
            $productConfirmedBugs[$bug->product]++;
        }

        foreach($products as $productID => $product)
        {
            $bug = isset($bugs[$productID]) ? $bugs[$productID] : '';
            $product->total              = empty($bug) ? 0 : $bug->total;
            $product->assignedToMe       = empty($bug) ? 0 : $bug->assignedToMe;
            $product->unclosed           = empty($bug) ? 0 : $bug->unclosed;
            $product->unresolved         = empty($bug) ? 0 : $bug->unresolved;
            $product->unconfirmed        = empty($bug) ? 0 : $bug->unconfirmed;
            $product->yesterdayResolved  = empty($bug) ? 0 : $bug->yesterdayResolved;
            $product->yesterdayClosed    = empty($bug) ? 0 : $bug->yesterdayClosed;
            $product->yesterdayConfirmed = empty($productConfirmedBugs[",$productID,"]) ? 0 : $productConfirmedBugs[",$productID,"];

            $product->assignedRate    = $product->total ? round($product->assignedToMe  / $product->total * 100, 2) : 0;
            $product->unresolvedRate  = $product->total ? round($product->unresolved    / $product->total * 100, 2) : 0;
            $product->unconfirmedRate = $product->total ? round($product->unconfirmed   / $product->total * 100, 2) : 0;
            $product->unclosedRate    = $product->total ? round($product->unclosed      / $product->total * 100, 2) : 0;
            $product->testtask        = isset($testtasks[$productID]) ? $testtasks[$productID] : '';
        }

        $this->view->products = $products;
    }

    /**
     * Print overview block.
     *
     * @access public
     * @return void
     */
    public function printOverviewBlock($module = 'product')
    {
        $func = 'print' . ucfirst($module) . 'OverviewBlock';
        $this->view->module = $module;
        $this->$func();
    }

    /**
     * Print product overview block.
     *
     * @access public
     * @return void
     */
    public function printProductOverviewBlock()
    {
        $normal = 0;
        $closed = 0;

        $products = $this->loadModel('product')->getList();

        foreach($products as $product)
        {
            if(!$this->product->checkPriv($product->id)) continue;

            if($product->status == 'normal') $normal++;
            if($product->status == 'closed') $closed++;
        }

        $total  = $normal + $closed;

        $this->view->total         = $total;
        $this->view->normal        = $normal;
        $this->view->closed        = $closed;
        $this->view->normalPercent = $total ? round(($normal / $total), 2) * 100 : 0;
    }

    /**
     * Print execution overview block.
     *
     * @access public
     * @return void
     */
    public function printExecutionOverviewBlock()
    {
        $projectID  = $this->view->block->module == 'my' ? 0 : (int)$this->session->project;
        $executions = $this->loadModel('execution')->getList($projectID);

        $total = 0;
        foreach($executions as $execution)
        {
            if(empty($execution->multiple)) continue;
            if(!isset($overview[$execution->status])) $overview[$execution->status] = 0;
            $overview[$execution->status]++;
            $total++;
        }

        $overviewPercent = array();
        $this->app->loadLang('project');
        foreach($this->lang->project->statusList as $statusKey => $statusName)
        {
            if(!isset($overview[$statusKey])) $overview[$statusKey] = 0;
            $overviewPercent[$statusKey] = $total ? round($overview[$statusKey] / $total, 2) * 100 . '%' : '0%';
        }

        $this->view->total           = $total;
        $this->view->overview        = $overview;
        $this->view->overviewPercent = $overviewPercent;
    }

    /**
     * Print qa overview block.
     *
     * @access public
     * @return void
     */
    public function printQaOverviewBlock()
    {
        $casePairs = $this->dao->select('lastRunResult, COUNT(*) AS count')->from(TABLE_CASE)
            ->where('1=1')
            ->beginIF($this->view->block->module != 'my' and $this->session->project)->andWhere('project')->eq((int)$this->session->project)->fi()
            ->groupBy('lastRunResult')
            ->fetchPairs();

        $total = array_sum($casePairs);

        $this->app->loadLang('testcase');
        foreach($this->lang->testcase->resultList as $result => $label)
        {
            if(!isset($casePairs[$result])) $casePairs[$result] = 0;
        }

        $casePercents = array();
        foreach($casePairs as $result => $count)
        {
            $casePercents[$result] = $total ? round($count / $total * 100, 2) : 0;
        }

        $this->view->total        = $total;
        $this->view->casePairs    = $casePairs;
        $this->view->casePercents = $casePercents;
    }

    /**
     * Print execution block.
     *
     * @access public
     * @return void
     */
    public function printExecutionBlock()
    {
        if(!empty($this->params->type) and preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) return;

        $count  = isset($this->params->count) ? (int)$this->params->count : 0;
        $status = isset($this->params->type)  ? $this->params->type : 'all';

        $this->loadModel('execution');
        $this->app->loadClass('pager', true);
        $pager = pager::init(0, $count, 1);

        $projectPairs = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('type')->eq('project')->fetchPairs('id', 'name');
        $projectID    = $this->view->block->module == 'my' ? 0 : (int)$this->session->project;

        $this->view->executionStats = $this->execution->getStatData($projectID, $status, 0, 0, false, 'skipParent', 'id_asc', $pager);
    }

    /**
     * Print assign to me block.
     *
     * @access public
     * @return void
     */
    public function printAssignToMeBlock($longBlock = true)
    {
        $hasIssue   = helper::hasFeature('issue');
        $hasRisk    = helper::hasFeature('risk');
        $hasMeeting = helper::hasFeature('meeting');

        $hasViewPriv = array();
        if(common::hasPriv('todo',  'view'))                                     $hasViewPriv['todo']  = true;
        if(common::hasPriv('task',  'view'))                                     $hasViewPriv['task']  = true;
        if(common::hasPriv('bug',   'view') and $this->config->vision != 'lite') $hasViewPriv['bug']   = true;
        if(common::hasPriv('story', 'view') and $this->config->vision != 'lite') $hasViewPriv['story'] = true;
        if($this->config->URAndSR and common::hasPriv('story', 'view') and $this->config->vision != 'lite') $hasViewPriv['requirement'] = true;
        if(common::hasPriv('risk',  'view') and ($this->config->edition == 'max' or $this->config->edition == 'ipd') and $this->config->vision != 'lite' && $hasRisk) $hasViewPriv['risk']        = true;
        if(common::hasPriv('issue', 'view') and ($this->config->edition == 'max' or $this->config->edition == 'ipd') and $this->config->vision != 'lite' && $hasIssue) $hasViewPriv['issue']       = true;
        if(common::hasPriv('meeting', 'view') and ($this->config->edition == 'max' or $this->config->edition == 'ipd') and $this->config->vision != 'lite' && $hasMeeting) $hasViewPriv['meeting']     = true;
        if(common::hasPriv('feedback', 'view') and $this->config->edition != 'open') $hasViewPriv['feedback'] = true;
        if(common::hasPriv('ticket', 'view') and $this->config->edition != 'open')   $hasViewPriv['ticket']   = true;

        $params          = $this->get->param;
        $params          = json_decode(base64_decode($params));
        $count           = array();
        $objectList      = array('todo' => 'todos', 'task' => 'tasks', 'bug' => 'bugs', 'story' => 'stories', 'requirement' => 'requirements');
        $objectCountList = array('todo' => 'todoCount', 'task' => 'taskCount', 'bug' => 'bugCount', 'story' => 'storyCount', 'requirement' => 'requirementCount');
        if($this->config->edition == 'max' or $this->config->edition == 'ipd')
        {
            if($hasRisk)
            {
                $objectList      += array('risk' => 'risks');
                $objectCountList += array('risk' => 'riskCount');
            }

            if($hasIssue)
            {
                $objectList      += array('issue' => 'issues');
                $objectCountList += array('issue' => 'issueCount');
            }

            $objectList      += array('feedback' => 'feedbacks', 'ticket' => 'tickets');
            $objectCountList += array('feedback' => 'feedbackCount', 'ticket' => 'ticketCount');
        }

        if($this->config->edition == 'biz')
        {
            $objectList      += array('feedback' => 'feedbacks', 'ticket' => 'tickets');
            $objectCountList += array('feedback' => 'feedbackCount', 'ticket' => 'ticketCount');
        }

        $tasks = $this->loadModel('task')->getUserSuspendedTasks($this->app->user->account);
        foreach($objectCountList as $objectType => $objectCount)
        {
            if(!isset($hasViewPriv[$objectType])) continue;

            $table      = $objectType == 'requirement' ? TABLE_STORY : $this->config->objectTables[$objectType];
            $orderBy    = $objectType == 'todo' ? "`date` desc" : 'id_desc';
            $limitCount = isset($params->{$objectCount}) ? $params->{$objectCount} : 0;
            $objects    = $this->dao->select('t1.*')->from($table)->alias('t1')
                ->beginIF($objectType == 'story' or $objectType == 'requirement')->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')->fi()
                ->beginIF($objectType == 'bug')->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')->fi()
                ->beginIF($objectType == 'task')->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.execution=t2.id')->fi()
                ->beginIF($objectType == 'issue' or $objectType == 'risk')->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')->fi()
                ->beginIF($objectType == 'ticket')->leftJoin(TABLE_USER)->alias('t2')->on('t1.openedBy = t2.account')->fi()
                ->where('t1.deleted')->eq(0)
                ->andWhere('t1.assignedTo')->eq($this->app->user->account)->fi()
                ->beginIF($objectType == 'story')->andWhere('t1.type')->eq('story')->andWhere('t2.deleted')->eq('0')->fi()
                ->beginIF($objectType == 'requirement')->andWhere('t1.type')->eq('requirement')->andWhere('t2.deleted')->eq('0')->fi()
                ->beginIF($objectType == 'bug')->andWhere('t2.deleted')->eq('0')->fi()
                ->beginIF($objectType == 'story' or $objectType == 'requirement')->andWhere('t2.deleted')->eq('0')->fi()
                ->beginIF($objectType == 'todo')->andWhere('t1.cycle')->eq(0)->andWhere('t1.status')->eq('wait')->andWhere('t1.vision')->eq($this->config->vision)->fi()
                ->beginIF($objectType != 'todo')->andWhere('t1.status')->ne('closed')->fi()
                ->beginIF($objectType == 'feedback')->andWhere('t1.status')->in('wait, noreview')->fi()
                ->beginIF($objectType == 'issue' or $objectType == 'risk')->andWhere('t2.deleted')->eq(0)->fi()
                ->beginIF($objectType == 'ticket')->andWhere('t1.status')->in('wait,doing,done')->fi()
                ->orderBy($orderBy)
                ->beginIF($limitCount)->limit($limitCount)->fi()
                ->fetchAll();

            if($objectType == 'todo')
            {
                $this->app->loadClass('date');
                $this->app->loadLang('todo');
                foreach($objects as $key => $todo)
                {
                    if($todo->status == 'done' and $todo->finishedBy == $this->app->user->account)
                    {
                        unset($objects[$key]);
                        continue;
                    }
                    if($todo->type == 'task' and isset($tasks[$todo->idvalue]))
                    {
                        unset($objects[$key]);
                        continue;
                    }

                    $todo->begin = date::formatTime($todo->begin);
                    $todo->end   = date::formatTime($todo->end);
                }
            }

            if($objectType == 'task')
            {
                $this->app->loadLang('task');
                $this->app->loadLang('execution');

                $objects = $this->loadModel('task')->getUserTasks($this->app->user->account, 'assignedTo');

                foreach($objects as $k => $task)
                {
                    if(in_array($task->status, array('closed', 'cancel'))) unset($objects[$k]);
                }
                if($limitCount > 0) $objects = array_slice($objects, 0, $limitCount);
            }

            if($objectType == 'bug')   $this->app->loadLang('bug');
            if($objectType == 'risk')  $this->app->loadLang('risk');
            if($objectType == 'issue') $this->app->loadLang('issue');

            if($objectType == 'feedback' or $objectType == 'ticket')
            {
                $this->app->loadLang('feedback');
                $this->app->loadLang('ticket');
                $this->view->users    = $this->loadModel('user')->getPairs('all,noletter');
                $this->view->products = $this->dao->select('id, name')->from(TABLE_PRODUCT)->where('deleted')->eq('0')->fetchPairs('id', 'name');
            }

            $count[$objectType] = count($objects);
            $this->view->{$objectList[$objectType]} = $objects;
        }

        if(isset($hasViewPriv['meeting']))
        {
            $this->app->loadLang('meeting');
            $today        = helper::today();
            $now          = date('H:i:s', strtotime(helper::now()));
            $meetingCount = isset($params->meetingCount) ? isset($params->meetingCount) : 0;

            $meetings = $this->dao->select('*')->from(TABLE_MEETING)->alias('t1')
                ->leftjoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
                ->where('t1.deleted')->eq('0')
                ->andWhere('t2.deleted')->eq('0')
                ->andWhere('(t1.date')->gt($today)
                ->orWhere('(t1.begin')->gt($now)
                ->andWhere('t1.date')->eq($today)
                ->markRight(2)
                ->andwhere('(t1.host')->eq($this->app->user->account)
                ->orWhere('t1.participant')->in($this->app->user->account)
                ->markRight(1)
                ->orderBy('t1.id_desc')
                ->beginIF($meetingCount)->limit($meetingCount)->fi()
                ->fetchAll();

            $count['meeting'] = count($meetings);
            $objectList += array('meeting' => 'meetings');
            $this->view->meetings = $meetings;
            $this->view->depts    = $this->loadModel('dept')->getOptionMenu();
            $this->view->users    = $this->loadModel('user')->getPairs('all,noletter');
        }

        $limitCount = !empty($params->reviewCount) ? $params->reviewCount : 20;
        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, $limitCount, 1);
        $reviews = $this->loadModel('my')->getReviewingList('all', 'time_desc', $pager);
        if($reviews)
        {
            $todoPriv = $hasViewPriv['todo'];
            unset($hasViewPriv['todo']);
            $hasViewPriv = array_merge(array('todo' => $todoPriv, 'review' => true), $hasViewPriv);

            $objectList += array('review' => 'reviews');

            $count['review']       = count($reviews);
            $this->view->reviews   = $reviews;
            if($this->config->edition == 'max' or $this->config->edition == 'ipd')
            {
                $this->app->loadLang('approval');
                $this->view->flows = $this->dao->select('module,name')->from(TABLE_WORKFLOW)->where('buildin')->eq(0)->fetchPairs('module', 'name');
            }
        }

        foreach($objectList as $objectType => $object)
        {
            if($objectType == 'todo') continue;
            if(empty($this->view->$object)) $hasViewPriv[$objectType] = false;
        }

        $this->view->selfCall    = $this->selfCall;
        $this->view->hasViewPriv = $hasViewPriv;
        $this->view->count       = $count;
        $this->view->longBlock   = $longBlock;
        $this->display();
    }

    /**
     * Print recent project block.
     *
     * @access public
     * @return void
     */
    public function printRecentProjectBlock()
    {
        /* load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager    = new pager(0, 3, 1);
        $cacheKey = $this->config->cacheKeys->block->recentProject;
        if(helper::isCacheEnabled() && $this->cache->has($cacheKey))
        {
            $this->app->loadLang('project');
            $projects = $this->cache->get($cacheKey);
        }
        else
        {
            $projects = $this->loadModel('project')->getInfoList('all', 'id_desc', $pager, 1);
            if($this->config->cache->enable) $this->cache->set($cacheKey, $projects);
        }
        $this->view->projects = $projects;
    }

    /**
     * Print project team block.
     *
     * @access public
     * @return void
     */
    public function printProjectTeamBlock()
    {
        $count   = isset($this->params->count)   ? $this->params->count   : 15;
        $status  = isset($this->params->type)    ? $this->params->type    : 'all';
        $orderBy = isset($this->params->orderBy) ? $this->params->orderBy : 'id_desc';

        /* Get projects. */
        $this->app->loadLang('task');
        $this->app->loadLang('program');
        $this->app->loadLang('execution');
        $this->view->projects = $this->loadModel('project')->getOverviewList('byStatus', $status, $orderBy, $count);
    }

    /**
     * Print document statistic block.
     *
     * @access public
     * @return void
     */
    public function printDocStatisticBlock()
    {
        $this->view->statistic = $this->loadModel('doc')->getStatisticInfo();
    }

    /**
     * Print document dynamic block.
     *
     * @access public
     * @return void
     */
    public function printDocDynamicBlock()
    {
        $this->view->actions = $this->loadModel('doc')->getDynamic(30);
        $this->view->users   = $this->loadModel('user')->getPairs('nodeleted|noletter|all', '', 0, array_unique(helper::arrayColumn($this->view->actions, 'actor')));
    }

    /**
     * Print my collection of documents block.
     *
     * @access public
     * @return void
     */
    public function printDocMyCollectionBlock()
    {
        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, 6, 1);

        $docList = $this->loadModel('doc')->getDocsByBrowseType('collectedbyme', 0, 0, 'editedDate_desc', $pager);
        $libList = array();
        foreach($docList as $doc)
        {
            $doc->editedDate   = substr($doc->editedDate, 0, 10);
            $doc->editInterval = helper::getDateInterval($doc->editedDate);

            $libList[] = $doc->lib;
        }

        $this->view->docList  = $docList;
    }

    /**
     * Print recent update block.
     *
     * @access public
     * @return void
     */
    public function printDocRecentUpdateBlock()
    {
        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, 6, 1);

        $docList = $this->loadModel('doc')->getDocsByBrowseType('byediteddate', 0, 0, 'editedDate_desc', $pager);
        $libList = array();
        foreach($docList as $doc)
        {
            $doc->editedDate   = substr($doc->editedDate, 0, 10);
            $doc->editInterval = helper::getDateInterval($doc->editedDate);

            $libList[] = $doc->lib;
        }

        $this->view->docList  = $docList;
    }

    /**
     * Print view list block.
     *
     * @access public
     * @return void
     */
    public function printDocViewListBlock()
    {
        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, 6, 1);

        $this->view->docList = $this->loadModel('doc')->getDocsByBrowseType('all', 0, 0,'views_desc', $pager);
    }

    /**
     * Print collect list block.
     *
     * @access public
     * @return void
     */
    public function printDocCollectListBlock()
    {
        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, 6, 1);

        $this->view->docList = $this->loadModel('doc')->getDocsByBrowseType('all', 0, 0, 'collects_desc', $pager);
    }

    /**
     * Print product's document block.
     *
     * @access public
     * @return void
     */
    public function printProductDocBlock()
    {
        $this->loadModel('doc');
        $this->session->set('docList', $this->createLink('doc', 'index'), 'doc');
        session_write_close();

        /* Set project status and count. */
        $count         = isset($this->params->count) ? (int)$this->params->count : 15;
        $products      = $this->loadModel('product')->getOrderedProducts('all');
        $involveds     = $this->product->getOrderedProducts('involved');
        $productIdList = array_merge(array_keys($products), array_keys($involveds));

        $stmt = $this->dao->select('id,product,lib,title,type,addedBy,addedDate,editedDate,`status`,acl,`groups`,`users`,deleted')->from(TABLE_DOC)->alias('t1')
            ->where('deleted')->eq(0)
            ->andWhere('product')->in($productIdList)
            ->orderBy('product,status,editedDate_desc')
            ->query();
        $docGroup = array();
        while($doc = $stmt->fetch())
        {
            if(!isset($docGroup[$doc->product])) $docGroup[$doc->product] = array();
            if(count($docGroup[$doc->product]) >= $count) continue;
            if($this->doc->checkPrivDoc($doc)) $docGroup[$doc->product][$doc->id] = $doc;
        }

        $hasDataProducts = $hasDataInvolveds = array();
        foreach($products as $productID => $product)
        {
            if(isset($docGroup[$productID]) and count($docGroup[$productID]) > 0)
            {
                $hasDataProducts[$productID] = $product;
                if(isset($involveds[$productID])) $hasDataInvolveds[$productID] = $product;
            }
        }

        $this->view->users     = $this->loadModel('user')->getPairs('noletter');
        $this->view->products  = $hasDataProducts;
        $this->view->involveds = $hasDataInvolveds;
        $this->view->docGroup  = $docGroup;
    }

    /**
     * Print project's document block.
     *
     * @access public
     * @return void
     */
    public function printProjectDocBlock()
    {
        $this->loadModel('doc');
        $this->app->loadLang('project');
        $this->session->set('docList', $this->createLink('doc', 'index'), 'doc');
        session_write_close();

        /* Set project status and count. */
        $count    = isset($this->params->count) ? (int)$this->params->count : 15;
        $projects = $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('deleted')->eq('0')
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('type')->eq('project')
            ->beginIF($this->config->vision == 'rnd')->andWhere('model')->ne('kanban')->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->orderBy('order_asc,id_desc')
            ->fetchAll('id');

        $involveds = $this->dao->select('t1.*')->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_TEAM)->alias('t2')->on('t1.id=t2.root')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->andWhere('t1.type')->eq('project')
            ->beginIF($this->config->vision == 'rnd')->andWhere('t1.model')->ne('kanban')->fi()
            ->andWhere('t2.type')->eq('project')
            ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->projects)->fi()
            ->andWhere('t1.openedBy', true)->eq($this->app->user->account)
            ->orWhere('t1.PM')->eq($this->app->user->account)
            ->orWhere('t2.account')->eq($this->app->user->account)
            ->markRight(1)
            ->orderBy('t1.order_asc,t1.id_desc')
            ->fetchAll('id');

        $projectIdList = array_keys($projects);

        $stmt = $this->dao->select('t1.id,t1.lib,t1.title,t1.type,t1.addedBy,t1.addedDate,t1.editedDate,t1.status,t1.acl,t1.groups,t1.users,t1.deleted,if(t1.project = 0, t2.project, t1.project) as project')->from(TABLE_DOC)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted', true)->eq(0)
            ->orWhere('t2.deleted is null')
            ->markRight(1)
            ->andWhere('t1.project', true)->in($projectIdList)
            ->orWhere('t2.project')->in($projectIdList)
            ->markRight(1)
            ->orderBy('project,t1.status,t1.editedDate_desc')
            ->query();
        $docGroup = array();
        while($doc = $stmt->fetch())
        {
            if(!isset($docGroup[$doc->project])) $docGroup[$doc->project] = array();
            if(count($docGroup[$doc->project]) >= $count) continue;
            if($this->doc->checkPrivDoc($doc)) $docGroup[$doc->project][$doc->id] = $doc;
        }

        $hasDataProjects = $hasDataInvolveds = array();
        foreach($projects as $projectID => $project)
        {
            if(isset($docGroup[$projectID]) and count($docGroup[$projectID]) > 0)
            {
                $hasDataProjects[$projectID] = $project;
                if(isset($involveds[$projectID])) $hasDataInvolveds[$projectID] = $project;
            }
        }

        $this->view->users     = $this->loadModel('user')->getPairs('noletter');
        $this->view->projects  = $hasDataProjects;
        $this->view->involveds = $hasDataInvolveds;
        $this->view->docGroup  = $docGroup;
    }

    /**
     * Print guide block
     *
     * @param  int    $blockID
     * @access public
     * @return void
     */
    public function guide($blockID = 0)
    {
        $this->app->loadLang('custom');
        $this->app->loadLang('my');
        $this->loadModel('setting');

        $this->view->blockID       = $blockID;
        $this->view->programs      = $this->loadModel('program')->getTopPairs('', 'noclosed', true);
        $this->view->programID     = isset($this->config->global->defaultProgram) ? $this->config->global->defaultProgram : 0;
        $this->view->URSRList      = $this->loadModel('custom')->getURSRPairs();
        $this->view->URSR          = $this->setting->getURSR();
        $this->view->programLink   = isset($this->config->programLink)   ? $this->config->programLink   : 'program-browse';
        $this->view->productLink   = isset($this->config->productLink)   ? $this->config->productLink   : 'product-all';
        $this->view->projectLink   = isset($this->config->projectLink)   ? $this->config->projectLink   : 'project-browse';
        $this->view->executionLink = isset($this->config->executionLink) ? $this->config->executionLink : 'execution-task';

        $this->display();
    }

    /**
     * Close block forever.
     *
     * @param  int    $blockID
     * @access public
     * @return void
     */
    public function close($blockID)
    {
        $block = $this->block->getByID($blockID);
        $closedBlock = isset($this->config->block->closed) ? $this->config->block->closed : '';
        $this->dao->delete()->from(TABLE_BLOCK)->where('source')->eq($block->source)->andWhere('block')->eq($block->block)->exec();
        $this->loadModel('setting')->setItem('system.block.closed', $closedBlock . ",{$block->source}|{$block->block}");
        return print(js::reload('parent'));
    }

    /**
     * Ajax reset.
     *
     * @param  string $module
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function ajaxReset($module, $confirm = 'no')
    {
        if($confirm != 'yes') return print(js::confirm($this->lang->block->confirmReset, inlink('ajaxReset', "module=$module&confirm=yes")));

        $this->dao->delete()->from(TABLE_BLOCK)
            ->where('module')->eq($module)
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('account')->eq($this->app->user->account)
            ->exec();

        $this->dao->delete()->from(TABLE_CONFIG)
            ->where('module')->eq($module)
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('owner')->eq($this->app->user->account)
            ->andWhere('`key`')->eq('blockInited')
            ->exec();
        return print(js::reload('parent'));
    }

    /**
     * Ajax for use new block.
     *
     * @param  string $module
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function ajaxUseNew($module, $confirm = 'no')
    {
        if($confirm == 'yes')
        {
            $this->dao->delete()->from(TABLE_BLOCK)->where('module')->eq($module)->andWhere('account')->eq($this->app->user->account)->exec();
            $this->dao->delete()->from(TABLE_CONFIG)->where('module')->eq($module)->andWhere('owner')->eq($this->app->user->account)->andWhere('`key`')->eq('blockInited')->exec();
            return print(js::reload('parent'));
        }
        elseif($confirm == 'no')
        {
            $this->loadModel('setting')->setItem("{$this->app->user->account}.$module.block.initVersion", $this->config->block->version);
        }
    }
}
