<?php
declare(strict_types=1);
/**
 * The control file of provider module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     provider
 * @link        https://www.zentao.net
 */
class provider extends control
{
    public function __construct($module = '', $method = '')
    {
        parent::__construct($module, $method);
        $this->loadModel('space')->setMenu();
    }

    /**
     * 浏览服务列表。
     * Browse provider list.
     *
     * @param  string $orderBy
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse(string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title     = $this->lang->provider->browse;
        $this->view->providers = $this->provider->getList($orderBy, $pager);
        $this->view->orderBy   = $orderBy;
        $this->view->users     = $this->loadModel('user')->getPairs('noletter');
        $this->view->pager     = $pager;
        $this->display();
    }

    /**
     * 创建服务。
     * Create provider.
     *
     * @param  string $type
     * @access public
     * @return void
     */
    public function create(string $type = 'GitLab', string $callback = '')
    {
        if($_POST)
        {
            $this->config->provider->form->create['token']['required']   = in_array($type, array('GitLab', 'GitHub', 'Gitea', 'Gogs', 'Jenkins'));
            $this->config->provider->form->create['account']['required'] = $type == 'Jenkins';
            $formData = form::data($this->config->provider->form->create)
                ->add('createdBy', $this->app->user->account)
                ->skipSpecial('name')
                ->get();

            if(!empty($formData->account && $type == 'Jenkins'))
            {
                $formData->token = base64_encode($formData->account . ':' . $formData->token);
            }
            unset($formData->account);

            if(!empty($formData->url) && !$this->providerZen->checkServiceUrl($formData)) return $this->sendError(dao::getError());

            $id = $this->provider->create($formData);
            if(dao::isError()) return $this->sendError(dao::getError());

            if($id) $this->loadModel('action')->create('provider', $id, 'created');

            $response = array();
            $response['load'] = $this->createLink('provider', 'browse');
            if($callback)
            {
                $response['callback'] = $callback;
                if($callback == 'refreshProvider')
                {
                    $provider = $this->provider->fetchById($id);
                    $response['callback'] = "window.refreshProvider('{$provider->type}')";
                }
            }
            return $this->sendSuccess($response);
        }
        $this->view->title    = $this->lang->provider->create;
        $this->view->type     = $type;
        $this->view->callback = $callback;
        $this->display();
    }

    /**
     * 编辑服务。
     * Edit provider.
     *
     * @param  int $id
     * @access public
     * @return void
     */
    public function edit(int $id)
    {
        $provider = $this->provider->getById($id);
        if($_POST)
        {
            $this->config->provider->form->edit['token']['required']   = in_array($provider->type, array('GitLab', 'GitHub', 'Gitea', 'Gogs', 'Jenkins'));
            $this->config->provider->form->edit['account']['required'] = $provider->type == 'Jenkins';
            $formData = form::data($this->config->provider->form->edit)
                ->add('editedBy', $this->app->user->account)
                ->skipSpecial('name')
                ->get();

            if(!empty($formData->account && $provider->type == 'Jenkins'))
            {
                $formData->token = base64_encode($formData->account . ':' . $formData->token);
            }
            unset($formData->account);

            if(!empty($formData->url) && !$this->providerZen->checkServiceUrl($formData)) return $this->sendError(dao::getError());

            $this->provider->update($id, $formData);
            if(dao::isError()) return $this->sendError(dao::getError());

            $this->loadModel('action')->create('provider', $id, 'edited');
            return $this->sendSuccess(array('load' => $this->createLink('provider', 'browse')));
        }

        $this->view->title    = $this->lang->provider->edit;
        $this->view->type     = $provider->type;
        $this->view->provider = $provider;
        $this->display();
    }

    /**
     * 删除服务。
     * delete provider.
     *
     * @param  int $id
     * @access public
     * @return void
     */
    public function delete(int $id)
    {
        $repos = $this->provider->getReposByProviderID($id, true);
        if(!empty($repos)) return $this->sendError($this->lang->provider->notice->hasRepos);

        $this->provider->delete(TABLE_PROVIDER, $id);
        if(dao::isError()) return $this->sendError(dao::getError());
        return $this->sendSuccess(array('load' => true));
    }

    /**
     * AJAX获取服务列表。
     * AJAX get provider list.
     *
     * @param  string $type
     * @access public
     * @return void
     */
    public function ajaxGetProviders($type = 'GitLab')
    {
        $providers = $this->provider->getPairs($type);
        $items     = array_map(function($id, $name){return array('text' => $name, 'value' => $id);}, array_keys($providers), $providers);
        return print(json_encode(array('items' => $items, 'value' => key($providers))));
    }
}
