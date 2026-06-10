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
    public function browse(string $orderBy = 'id_desc', int $recPerPage = 20, int $pageID = 1)
    {
        $this->view->title = $this->lang->provider->browse;
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
    public function create(string $type = 'GitLab')
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
            return $this->sendSuccess(array('load' => $this->createLink('provider', 'browse')));
        }
        $this->view->title = $this->lang->provider->create;
        $this->view->type  = $type;
        $this->display();
    }
}
