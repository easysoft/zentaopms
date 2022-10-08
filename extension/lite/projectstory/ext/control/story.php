<?php
class projectStory extends control
{
    public function story($projectID = 0, $productID = 0, $branch = 0, $browseType = '', $param = 0, $storyType = 'story', $orderBy = '', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        if(!$productID) $productID = $this->loadModel('product')->getProductIDByProject($projectID);
        echo $this->fetch('product', 'browse', "productID=$productID&branch=$branch&browseType=$browseType&param=$param&storyType=$storyType&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID&projectID=$projectID");
    }
}
