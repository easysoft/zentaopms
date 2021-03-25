<?php
/**
 * The control file of projectStory module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
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
        $this->products = $this->loadModel('product')->getProductPairsByProject($projectID);
        if(empty($this->products)) die($this->locate($this->createLink('product', 'showErrorNone', 'moduleName=project&activeMenu=story&projectID=' . $projectID)));
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
    public function track($productID = 0, $branch = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        if(empty($productID)) $productID = key($this->products);

        $this->lang->story->requirement = str_replace($this->lang->URCommon, $this->lang->URCommon, $this->lang->story->requirement);
        $this->lang->story->story       = str_replace($this->lang->SRCommon, $this->lang->SRCommon, $this->lang->story->story);

        echo $this->fetch('story', 'track', "productID=$productID&branch=$branch&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * View a story.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function view($storyID)
    {
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
     * @access public
     * @return void
     */
    public function linkStory($projectID = 0, $browseType = '', $param = 0, $recTotal = 0, $recPerPage = 50, $pageID = 1)
    {
        echo $this->fetch('execution', 'linkStory', "projectID=$projectID&browseType=$browseType&param=$param&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
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
}

