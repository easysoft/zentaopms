<?php
class projectStory extends control
{
    /**
     * 项目需求列表。
     * Project story list.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  string $branch
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
    public function story(int $projectID = 0, int $productID = 0, string $branch = '', string $browseType = '', int $param = 0, string $storyType = 'story', string $orderBy = '', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        if(!$productID) $productID = $this->loadModel('product')->getProductIDByProject($projectID);
        echo $this->fetch('product', 'browse', "productID=$productID&branch=$branch&browseType=$browseType&param=$param&storyType=$storyType&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID&projectID=$projectID");
    }
}
