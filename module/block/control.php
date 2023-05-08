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
     * 永久关闭区块。
     * Close block forever.
     *
     * @param  string    $blockID
     * @access public
     * @return void
     */
    public function close(string $blockID)
    {
        $blockID = (int)$blockID; // 强制转换为 int 类型，防止调用 model、tao 方法时报错。

        /* 永久关闭区块。 */
        $block = $this->block->getByID($blockID);
        $this->block->closeBlock($block);

        /* 将关闭的区块保存到配置信息。 */
        $closedBlock = isset($this->config->block->closed) ? $this->config->block->closed : '';
        $this->loadModel('setting')->setItem('system.block.closed', $closedBlock . ",{$block->module}|{$block->code}");
        return $this->send(array('result' => 'success'));
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
     * 展示应用的仪表盘。
     * Display dashboard for app.
     *
     * @param  string  $dashboard
     * @param  string  $projectID
     * @access public
     * @return void
     */
    public function dashboard(string $dashboard, string $projectID = '0')
    {
        $projectID = (int)$projectID; // 强制转换为 int 类型，防止调用 model、tao 方法时报错。

        /* 获取传入应用对应的区块列表 以及 获取当前应用下区块启用状态。 */
        $blocks      = $this->block->getMyDashboard($dashboard);
        $isInitiated = $this->block->fetchBlockInitStatus($dashboard);

        /* 判断用户是否为首次登录 ，判断条件 当前用户没有该 app 下的区块数据 且 没有设置过该 app 下的区块启用状态 且不是演示模式。 */
        if(empty($blocks) and !$isInitiated and !defined('TUTORIAL'))
        {
            $this->block->initBlock($dashboard); // 初始化该 app 下区块数据。
            $blocks = $this->block->getMyDashboard($dashboard); // 获取初始化后的区块列表。
        }

        /* 处理页面需要的数据格式。  */
        $blocks = $this->blockZen->processBlockForRender($blocks, $projectID);

        /* 如果页面渲染方式为 json 的话，直接返回 json 格式数据 ，主要是为了给 app 提供数据。 */
        if($this->app->getViewType() == 'json') return print(json_encode($blocks));

        /* 将区块列表分为长区块和短区块。 */
        list($shortBlocks, $longBlocks) = $this->blockZen->splitBlocksByLen($blocks);

        /* 组织渲染页面需要数据。 */
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

        if(isset($block->params->num) and !isset($block->params->count)) $block->params->count = $block->params->num;

        $code = $block->code;
        if($code == 'statistic' or $code == 'list' or $code == 'overview') $code = $block->module . ucfirst($code);

        $function = 'print' . ucfirst($code) . 'Block';
        if(method_exists('blockZen', $function)) $this->blockZen->$function($block);

        if(!$this->selfCall)
        {
            $this->app->user = $this->dao->select('*')->from(TABLE_USER)->where('ranzhi')->eq($block->params->account)->fetch();
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
            list($moduleName, $method, $vars) = explode('|', sprintf($this->config->block->modules[$module]->moreLinkList->{$code}, isset($block->params->type) ? $block->params->type : ''));
            $this->view->moreLink = $this->createLink($moduleName, $method, $vars);
        }
        $this->view->moreLink = $moreLink;

        $viewType = (isset($block->params->viewType) and $block->params->viewType == 'json') ? 'json' : 'html';
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
