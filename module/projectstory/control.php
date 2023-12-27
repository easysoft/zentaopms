<?php
declare(strict_types=1);
/**
 * The control file of projectStory module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     projectStory
 * @version     $Id: control.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
class projectStory extends control
{
    /**
     * All products.
     *
     * @var    array
     * @access public
     */
    public $products = array();

    /**
     * Get software requirements from product.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $browseType
     * @param  int    $param
     * @param  string $storyType
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function story(int $projectID = 0, int $productID = 0, string $branch = '0', string $browseType = '', int $param = 0, string $storyType = 'story', string $orderBy = '', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* Get productID for none-product project. */
        if($projectID)
        {
            $project = $this->loadModel('project')->getByID($projectID);
            if(!$project->hasProduct) $productID = $this->loadModel('product')->getShadowProductByProject($projectID)->id;
        }

        $this->products = $this->loadModel('product')->getProductPairsByProject($projectID);
        if(empty($productID) && count($this->products) == 1) $productID = key($this->products);

        /* Set product list for export. */
        $this->session->set('exportProductList',  $this->products);
        $this->session->set('executionStoryList', $this->app->getURI(true));
        $this->session->set('productList',        $this->app->getURI(true));
        if($storyType == 'requirement')
        {
            unset($this->lang->projectstory->featureBar['story']['linkedExecution']);
            unset($this->lang->projectstory->featureBar['story']['unlinkedExecution']);
            $this->lang->projectstory->unlinkStory = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->projectstory->unlinkStory);
        }

        if(empty($this->products)) $this->locate($this->createLink('product', 'showErrorNone', 'moduleName=project&activeMenu=story&projectID=' . $projectID));

        echo $this->fetch('product', 'browse', array
        (
            'productID'  => $productID,
            'branch'     => $branch,
            'browseType' => $browseType,
            'param'      => $param,
            'storyType'  => $storyType,
            'orderBy'    => $orderBy,
            'recTotal'   => $recTotal,
            'recPerPage' => $recPerPage,
            'pageID'     => $pageID,
            'projectID'  => $projectID
        ));
    }

    /**
     * Obtain the tracking matrix through the product.
     *
     * @param  int   $productID
     * @param  int   $browseType
     * @param  int   $recTotal
     * @param  int   $recPerPage
     * @param  int   $pageID
     * @access public
     * @return void
     */
    public function track($projectID = 0, $productID = 0, $branch = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $products = $this->loadModel('product')->getProductPairsByProject($projectID);
        if(empty($productID)) $productID = key($products);

        $project = $this->loadModel('project')->getByID($projectID);
        $this->session->set('hasProduct', $project->hasProduct);

        echo $this->fetch('product', 'track', "productID=$productID&branch=$branch&projectID=$projectID&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * View a story.
     *
     * @param  int    $storyID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function view($storyID, $projectID = 0)
    {
        if($projectID) $this->session->set('project', $projectID, 'project');
        $this->session->set('productList', $this->app->getURI(true), 'product');

        $story = $this->loadModel('story')->getByID((int)$storyID);
        echo $this->fetch('story', 'view', "storyID=$storyID&version=$story->version&param=" . $this->session->project);
    }

    /**
     * Link stories to a project.
     *
     * @param  int    $projectID
     * @param  string $browseType
     * @param  int    $param
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkStory($projectID = 0, $browseType = '', $param = 0, $recPerPage = 50, $pageID = 1)
    {
        echo $this->fetch('execution', 'linkStory', "projectID={$projectID}&browseType={$browseType}&param={$param}&orderBy=id_desc&recPerPage={$recPerPage}&pageID={$pageID}");
    }

    /**
     * Unlink a story.
     *
     * @param  int    $projectID
     * @param  int    $storyID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function unlinkStory($projectID, $storyID, $confirm = 'no')
    {
        echo $this->fetch('execution', 'unlinkStory', "projectID=$projectID&storyID=$storyID&confirm=$confirm");
    }

    /**
     * Batch unlink story.
     *
     * @param  int    $projectID
     * @param  string $storyIdList
     * @access public
     * @return void
     */
    public function batchUnlinkStory(int $projectID, string $storyIdList = '')
    {
        $storyIdList = empty($storyIdList) ? array() : array_filter(explode(',', $storyIdList));
        if(empty($storyIdList)) $this->send(array('result' => 'success', 'load' => true, 'closeModal' => true));

        $this->loadModel('execution');
        $executionStories = $this->projectstory->getExecutionStories($projectID, $storyIdList);
        $errors           = array();
        foreach($storyIdList as $storyID)
        {
            if(isset($executionStories[$storyID])) continue;

            $this->execution->unlinkStory($projectID, $storyID);
            if(dao::isError()) $errors[$storyID] = dao::getError();
        }
        if(empty($errors)) $this->loadModel('score')->create('ajax', 'batchOther');
        if(empty($executionStories)) $this->send(array('result' => 'success', 'load' => true, 'closeModal' => true));

        $this->view->executionStories = $executionStories;
        $this->display();
    }

    /**
     * Import plan stories.
     *
     * @param  int    $projectID
     * @param  int    $planID
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function importPlanStories($projectID = 0, $planID = 0, $productID = 0)
    {
        echo $this->fetch('execution', 'importPlanStories', "projectID=$projectID&planID=$planID&productID=$productID");
    }
}

