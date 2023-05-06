<?php
declare(strict_types=1);
 /**
 * The control file of block of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
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
     * 创建区块
     * Create a block under a dashboard.
     *
     * @param  string $dashboard
     * @param  string $module
     * @param  string $code
     * @access public
     * @return void
     */
    public function create(string $dashboard, string $module = '', string $code = '')
    {
        if($_POST)
        {
            $formData = form::data($this->config->block->form->create)->get();
            $formData->dashboard = $dashboard;
            $formData->account   = $this->app->user->account;
            $formData->vision    = $this->config->vision;
            $formData->order     = $this->block->getMaxOrderByDashboard($dashboard) + 1;
            $formData->params    = json_encode($formData->params);

            $this->block->create($formData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->view->title     = $this->lang->block->createBlock;
        $this->view->dashboard = $dashboard;
        $this->view->module    = $module;
        $this->view->code      = $code;
        $this->view->modules   = $this->blockZen->getAvailableModules($dashboard);
        $this->view->codes     = $this->blockZen->getAvailableCodes($dashboard, $module);
        $this->view->params    = $this->blockZen->getAvailableParams($dashboard, $module, $code);
        $this->display();
    }

    /**
     * 编辑区块
     * Update a block.
     *
     * @param  string $dashboard
     * @param  string $module
     * @param  string $code
     * @access public
     * @return void
     */
    public function edit(string $blockID, string $module = '', string $code = '')
    {
        $blockID = (int)$blockID;
        if($_POST)
        {
            $formData = form::data($this->config->block->form->edit)->get();
            $formData->id     = $blockID;
            $formData->params = helper::jsonEncode($formData->params);

            $this->block->update($formData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        /* 如果没传code说明是首次进入页面，直接使用待编辑block的code。*/
        /* If no code is passed, it indicates that you are entering the page for the first time, and you can directly use the code of the block to be edited.*/
        $block = $this->block->getByID($blockID);
        $code  = $code ? $code : $block->code;

        $this->view->title     = $this->lang->block->editBlock;
        $this->view->block     = $block;
        $this->view->dashboard = $block->dashboard;
        $this->view->module    = $module ? $module : $block->module;
        $this->view->modules   = $this->blockZen->getAvailableModules($block->dashboard);
        $this->view->codes     = $this->blockZen->getAvailableCodes($block->dashboard, $this->view->module);
        /* codes有值且包含code时，页面才选中对应的code，否则为空。*/
        /* Only when codes have a value and contain codes, the corresponding code is selected on the page, otherwise it is blank.*/
        $this->view->code      = $this->view->codes ? (in_array($code, array_keys($this->view->codes)) ? $code : '') : $code;
        $this->view->params    = $this->blockZen->getAvailableParams($block->dashboard, $this->view->module, $this->view->code);
        $this->display();
    }

    /**
     * Delete or hidd block by blockid.
     *
     * @param  int    $id
     * @param  string $type
     * @access public
     * @return void
     */
    public function delete($blockID, $type = 'delete')
    {
        $blockID = (int)$blockID;
        if($type == 'delete') $this->block->deleteBlock($blockID);
        if($type == 'hidden') $this->block->hidden($blockID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->loadModel('score')->create('block', 'set');
        return $this->send(array('result' => 'success'));
    }

    /**
     * Sort dashboard blocks.
     *
     * @param  string  $orders
     * @access public
     * @return void
     */
    public function sort($orders)
    {
        $orders = explode(',', $orders);
        foreach($orders as $order => $blockID) $this->block->setOrder($blockID, $order);

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
    public function resize($blockID, $type, $data)
    {
        $block = $this->block->getByID($blockID);
        if(!$block) return $this->send(array('result' => 'fail', 'code' => 404));

        $field = '';
        if($type == 'vertical')   $field = 'height';
        if($type == 'horizontal') $field = 'grid';
        if(empty($field)) return $this->send(array('result' => 'fail', 'code' => 400));

        $block->$field = $data;
        $block->params = helper::jsonEncode($block->params);
        $this->block->update($block);

        if(dao::isError()) return $this->send(array('result' => 'fail', 'code' => 500));
        return $this->send(array('result' => 'success'));
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
        $this->dao->delete()->from(TABLE_BLOCK)->where('source')->eq($block->source)->andWhere('code')->eq($block->code)->exec();
        $this->loadModel('setting')->setItem('system.block.closed', $closedBlock . ",{$block->source}|{$block->code}");
        return print(js::reload('parent'));
    }

    /**
     * Reset dashboard blocks.
     *
     * @param  string $dashboard
     * @access public
     * @return void
     */
    public function reset($dashboard)
    {
        $this->block->reset($dashboard);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success'));
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

    /**
     * 展示应用的仪表盘
     * Display dashboard for app.
     *
     * @param  string  $dashboard
     * @param  string  $projectID
     * @access public
     * @return void
     */
    public function dashboard(string $dashboard, string $projectID = '0')
    {
        $projectID   = (int)$projectID;
        $blocks      = $this->block->getMyDashboard($dashboard);
        $isInitiated = $this->block->fetchBlockInitStatus($dashboard);

        /* Init block when vist index first. */
        if(empty($blocks) and !$isInitiated and !defined('TUTORIAL') and $this->block->initBlock($dashboard))
        {
            return print(js::reload());
        }

        $blocks = $this->blockZen->processBlockForRender($blocks, $projectID);

        if($this->app->getViewType() == 'json') return print(json_encode($blocks));

        list($shortBlocks, $longBlocks) = $this->blockZen->splitBlocksByLen($blocks);

        $this->view->title       = zget($this->lang->block->dashboard, $dashboard, $this->lang->block->dashboard['default']);
        $this->view->longBlocks  = $longBlocks;
        $this->view->shortBlocks = $shortBlocks;
        $this->view->dashboard   = $dashboard;
        $this->render();
    }




    /**
     * Print block.
     * 输出区块
     *
     * @param  int     $blockID
     * @access public
     * @return string
     */
    public function printBlock($blockID)
    {
        if(!$this->selfCall)
        {
            $lang = str_replace('_', '-', $this->get->lang);
            $this->app->setClientLang($lang);
            $this->app->loadLang('common');
            $this->app->loadLang('block');

            if(!$this->block->checkAPI($this->get->hash)) return;
        }

        $html    = '';
        $blockID = (int)$blockID;
        $block   = $this->block->getByID($blockID);

        if(empty($block)) return $html;

        $code = $block->code;
        if($code == 'statistic' or $code == 'list' or $code == 'overview') $code = $block->module . ucfirst($code);

        $function = 'print' . ucfirst($code) . 'Block';
        if(method_exists('blockZen', $function)) $this->blockZen->$function($blockID);

        $params = $block->params;
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

        $this->view->title     = $block->title;
        $this->view->block     = $block;
        $this->view->longBlock = $this->block->isLongBlock($block);
        $this->view->selfCall  = $this->selfCall;

        $module   = $block->module;
        $moreLink = '';
        if(isset($this->config->block->modules[$module]->moreLinkList->{$code}))
        {
            list($moduleName, $method, $vars) = explode('|', sprintf($this->config->block->modules[$module]->moreLinkList->{$code}, isset($params->type) ? $params->type : ''));
            $this->view->moreLink = $this->createLink($moduleName, $method, $vars);
        }
        $this->view->moreLink = $moreLink;

        $viewType = (isset($params->viewType) and $params->viewType == 'json') ? 'json' : 'html';
        if($viewType == 'json')
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

        $this->display('block', strtolower($code) . 'block');
    }
}
