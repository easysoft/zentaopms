<?php
declare(strict_types=1);
class projectreleaseZen extends projectrelease
{
    /**
     * 获取当前项目的所有产品，当前产品，分支，项目
     * Get products of the project and current product, branch, project.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  string $branch
     * @access public
     * @return void
     */
    protected function commonAction(int $projectID = 0, int $productID = 0, string $branch = '')
    {
        /* 获取当前项目的所有产品。*/
        /* Get product list by project. */
        $this->products = $this->product->getProductPairsByProject($projectID);
        if(empty($this->products)) $this->locate($this->createLink('product', 'showErrorNone', 'moduleName=project&activeMenu=projectrelease&projectID=' . $projectID));

        /* 获取当前的产品。*/
        /*  Get current product. */
        if(!$productID) $productID = key($this->products);
        $this->loadModel('product')->checkAccess($productID, $this->products);
        $product = $this->product->getByID($productID);

        $this->view->products = $this->products;
        $this->view->product  = $product;
        $this->view->branches = (isset($product->type) and $product->type == 'normal') ? array() : $this->loadModel('branch')->getPairs($productID, 'active', $projectID);
        $this->view->branch   = $branch;
        $this->view->project  = $this->project->getByID($projectID);
        $this->view->appList  = $this->loadModel('system')->getList($productID);
    }

    /**
     * 构造项目发布数据。
     * Construct project release data.
     *
     * @param  int       $projectID
     * @access protected
     * @return void
     */
    protected function buildReleaseForCreate(int $projectID): object|false
    {
        $this->lang->projectrelease->system = $this->lang->release->system;
        if(!$this->post->newSystem && !$this->post->system) $this->config->release->form->create['system']['required'] = true;
        if($this->post->newSystem  && !$this->post->systemName)
        {
            $this->config->release->form->create['systemName'] = array('type' => 'string', 'required' => true);
            $this->lang->projectrelease->systemName = $this->lang->release->system;
        }

        $release = form::data($this->config->release->form->create)
            ->add('product', $this->post->product ? $this->post->product : 0)
            ->add('branch', $this->post->branch ? $this->post->branch : 0)
            ->setIF($projectID, 'project', $projectID)
            ->setIF($this->post->build === false, 'build', 0)
            ->get();

        /* Check build if build is required. */
        if(strpos($this->config->release->create->requiredFields, 'build') !== false && empty($release->build)) dao::$errors['build'] = sprintf($this->lang->error->notempty, $this->lang->release->build);
        if(!$this->post->newSystem && $this->post->system)
        {
            $system = $this->loadModel('system')->fetchByID((int)$this->post->system);
            if(!$system) dao::$errors['system'][] = sprintf($this->lang->error->notempty, $this->lang->release->system);

            if($system->integrated == '1')
            {
                $releases = (array)$this->post->releases;

                $release->build    = '';
                $release->releases = trim(implode(',', array_filter($releases)), ',');
                if(!$release->releases) dao::$errors['releases[' . key($releases) . ']'][] = sprintf($this->lang->error->notempty, $this->lang->release->name);
            }
        }
        if(dao::isError()) return false;

        if($this->post->newSystem && $this->post->systemName && $this->post->product)
        {
            $system = new stdclass();
            $system->name        = $this->post->systemName;
            $system->product     = $this->post->product;
            $system->createdBy   = $this->app->user->account;
            $system->createdDate = helper::now();

            $release->system = $this->loadModel('system')->create($system);
        }
        return $release;
    }
}
