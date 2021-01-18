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
    public $products = array();

    /**
     * Get the products associated with the project.
     *
     * @param  string  $moduleName
     * @param  string  $methodName
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->products = $this->loadModel('product')->getProductPairsByProject($this->session->PRJ);
        if(empty($this->products)) die($this->locate($this->createLink('product', 'showErrorNone', "fromModule=projectStory")));
    }

    /**
     * Obtain user requirements through product.
     *
     * @param  int    $productID
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
    public function requirement($productID = 0, $branch = '', $browseType = '', $param = 0, $storyType = 'requirement', $orderBy = '', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->session->set('storyList',   $this->app->getURI(true));
        if(empty($productID)) $productID = key($this->products);

        $this->lang->menugroup->product = 'projectstory';
        $this->lang->projectstory->menu->requirement['subModule'] = 'product';
        $this->projectstory->setMenu($this->products, $productID, $branch);

        $this->loadModel('story')->replaceURLang('requirement');
        $this->lang->story->title             = str_replace($this->lang->URCommon, $this->lang->URCommon, $this->lang->story->title);
        $this->lang->story->createRequirement = str_replace($this->lang->URCommon, $this->lang->URCommon, $this->lang->story->createRequirement);
        $this->lang->story->createStory       = str_replace($this->lang->URCommon, $this->lang->URCommon, $this->lang->story->createStory);
        $this->lang->story->noStory           = str_replace($this->lang->URCommon, $this->lang->URCommon, $this->lang->story->noStory);

        echo $this->fetch('product', 'browse', "productID=$productID&branch=$branch&browseType=$browseType&param=$param&storyType=$storyType&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * Get software requirements from product.
     *
     * @param  int    $productID
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
    public function story($productID = 0, $branch = '', $browseType = '', $param = 0, $storyType = 'story', $orderBy = '', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->session->set('storyList',$this->app->getURI(true));
        if(empty($productID)) $productID = key($this->products);

        $this->lang->menugroup->product = 'projectstory';
        $this->lang->projectstory->menu->story['subModule'] = 'product';
        $this->projectstory->setMenu($this->products, $productID, $branch);

        $this->lang->story->title             = str_replace($this->lang->SRCommon, $this->lang->SRCommon, $this->lang->story->title);
        $this->lang->story->createRequirement = str_replace($this->lang->SRCommon, $this->lang->SRCommon, $this->lang->story->createRequirement);
        $this->lang->story->createStory       = str_replace($this->lang->SRCommon, $this->lang->SRCommon, $this->lang->story->createStory);
        $this->lang->story->noStory           = str_replace($this->lang->SRCommon, $this->lang->SRCommon, $this->lang->story->noStory);

        echo $this->fetch('product', 'browse', "productID=$productID&branch=$branch&browseType=$browseType&param=$param&storyType=$storyType&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
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
        $this->lang->menugroup->story = 'projectstory';
        $this->lang->projectstory->menu->track['subModule'] = 'story';
        $this->projectstory->setMenu($this->products, $productID, $branch);

        $this->lang->story->requirement = str_replace($this->lang->URCommon, $this->lang->URCommon, $this->lang->story->requirement);
        $this->lang->story->story       = str_replace($this->lang->SRCommon, $this->lang->SRCommon, $this->lang->story->story);

        echo $this->fetch('story', 'track', "productID=$productID&branch=$branch&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
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
        echo $this->fetch('project', 'linkStory', "projectID=$projectID&browseType=$browseType&param=$param&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }
}

