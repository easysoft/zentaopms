<?php
/**
 * The control file of projectStory module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     projectStory
 * @version     $Id: control.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
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
    public function story($projectID = 0, $productID = 0, $branch = 0, $browseType = '', $param = 0, $storyType = 'story', $orderBy = '', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Get productID for none-product project. */
        if($projectID)
        {
            $project = $this->loadModel('project')->getByID($projectID);
            if(!$project->hasProduct) $productID = $this->loadModel('product')->getShadowProductByProject($projectID)->id;
        }

        $this->products = $this->loadModel('product')->getProductPairsByProject($projectID);

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

        if(empty($this->products)) return print($this->locate($this->createLink('product', 'showErrorNone', 'moduleName=project&activeMenu=story&projectID=' . $projectID)));
        echo $this->fetch('product', 'browse', "productID=$productID&branch=$branch&browseType=$browseType&param=$param&storyType=$storyType&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID&projectID=$projectID");
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

        $story = $this->loadModel('story')->getByID($storyID);
        echo $this->fetch('story', 'view', "storyID=$storyID&version=$story->version&param=" . $this->session->project);
    }

    /**
     * Link stories to a project.
     *
     * @param  int    $projectID
     * @param  string $browseType
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @param  string $storyType
     * @access public
     * @return void
     */
    public function linkStory($projectID = 0, $browseType = '', $param = 0, $recTotal = 0, $recPerPage = 50, $pageID = 1, $storyType = 'story')
    {
        echo $this->fetch('execution', 'linkStory', "projectID=$projectID&browseType=$browseType&param=$param&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID&extra=&storyType=$storyType");
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
     * @return string
     */
    public function batchUnlinkStory($projectID, $storyIdList = '')
    {
        $storyIdList      = empty($storyIdList) ? array() : array_filter(explode(',', $storyIdList));
        $executionStories = $this->projectstory->getExecutionStories($projectID, $storyIdList);
        $html             = '';

        foreach($executionStories as $story)
        {
            $storyLink     = $this->createLink('story', 'view', "storyID={$story->id}");
            $executionLink = $this->createLink('execution', 'story', "executionID={$story->executionID}");
            $html         .=<<<ETO
<tr>
  <td class='c-name w-500px'><a href="$storyLink" title={$story->title} style='color:#5988e2'>{$story->title}</a></td>
  <td class='c-name w-200px'><a href="$executionLink" title={$story->execution} style='color:#32579c'>{$story->execution}</a></td>
</tr>
ETO;
        }

        $this->loadModel('execution');
        foreach($storyIdList as $storyID)
        {
            if(isset($executionStories[$storyID])) continue;
            $this->execution->unlinkStory($projectID, $storyID);
        }

        if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');
        echo $html;
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
    public function importPlanStories($projectID, $planID, $productID = 0)
    {
        echo $this->fetch('execution', 'importPlanStories', "projectID=$projectID&planID=$planID&productID=$productID");
    }
}

