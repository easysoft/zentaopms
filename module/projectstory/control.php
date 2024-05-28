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
     * @param  int    $projectID
     * @param  int    $productID
     * @param  string $branch
     * @param  string $browseType   allstory|bysearch
     * @param  int    $param
     * @param  string $storyType
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function track(int $projectID = 0, int $productID = 0, string $branch = '', string $browseType = 'allstory', int $param = 0, string $storyType = '', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $products = $this->loadModel('product')->getProductPairsByProject($projectID);
        if(empty($productID)) $productID = key($products);

        $project = $this->loadModel('project')->getByID($projectID);
        $this->session->set('hasProduct', $project->hasProduct);

        echo $this->fetch('product', 'track', "productID=$productID&branch=$branch&projectID=$projectID&browseType=$browseType&param=$param&storyType=$storyType&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
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
        echo $this->fetch($story->type, 'view', "storyID=$storyID&version=$story->version&param=" . $this->session->project);
    }

    /**
     * Link stories to a project.
     *
     * @param  int    $projectID
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @param  string $extra
     * @param  string $storyType
     * @access public
     * @return void
     */
    public function linkStory($projectID = 0, $browseType = '', $param = 0, $orderBy = 'id_desc', $recPerPage = 50, $pageID = 1, $extra = '', $storyType = 'story')
    {
        echo $this->fetch('execution', 'linkStory', "projectID={$projectID}&browseType={$browseType}&param={$param}&orderBy={$orderBy}&recPerPage={$recPerPage}&pageID={$pageID}&extra={$extra}&storyType=$storyType");
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
        $storyIdList = array_unique($storyIdList);
        foreach($storyIdList as $index => $storyID)
        {
            /* 处理选中的子需求的ID，截取-后的子需求ID。*/
            /* Process selected child story ID. */
            if(strpos((string)$storyID, '-') !== false) $storyIdList[$index] = substr($storyID, strpos($storyID, '-') + 1);
        }
        if(empty($storyIdList)) $this->send(array('result' => 'success', 'load' => true, 'closeModal' => true));

        $this->loadModel('execution');
        $executionStories = $this->projectstory->getExecutionStories($projectID, $storyIdList);
        $errors           = array();
        foreach($storyIdList as $storyID)
        {
            if(isset($executionStories[$storyID])) continue;

            $this->execution->unlinkStory($projectID, (int)$storyID);
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

    /**
     * 查看需求的报告。
     * The report page.
     *
     * @param  int    $productID
     * @param  int    $branchID
     * @param  string $storyType
     * @param  string $browseType
     * @param  int    $moduleID
     * @param  string $chartType
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function report(int $productID, int $branchID, string $storyType = 'story', string $browseType = 'unclosed', int $moduleID = 0, string $chartType = 'pie', int $projectID = 0)
    {
        echo $this->fetch('story', 'report', "productID=$productID&branchID=$branchID&storyType=$storyType&browseType=$browseType&moduleID=$moduleID&chartType=$chartType&projectID=$projectID");
    }

    /**
     * 导出需求数据。
     * Get the data of the stories to export.
     *
     * @param  int    $productID
     * @param  string $orderBy
     * @param  int    $executionID
     * @param  string $browseType
     * @param  string $storyType   requirement|story
     * @access public
     * @return void
     */
    public function export(int $productID, string $orderBy, int $executionID = 0, string $browseType = '', string $storyType = 'story')
    {
        echo $this->fetch('story', 'export', "productID=$productID&orderBy=$orderBy&executionID=$executionID&browseType=$browseType&storyType=$storyType");
    }

    /**
     * Batch review stories.
     *
     * @param  string $result
     * @param  string $reason
     * @param  string $storyType story|requirement
     * @access public
     * @return void
     */
    public function batchReview(string $result, string $reason = '', string $storyType = 'story')
    {
        echo $this->fetch('story', 'batchReview', "result=$result&reason=$reason&storyType=$storyType");
    }

    /**
     * 批量关闭需求。
     * Batch close the stories.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @param  string $storyType   story|requirement
     * @param  string $from        contribute|work
     * @access public
     * @return void
     */
    public function batchClose(int $productID = 0, int $executionID = 0, string $storyType = 'story', string $from = '')
    {
        echo $this->fetch('story', 'batchClose', "productID=$productID&executionID=$executionID&storyType=$storyType&from=$from");
    }


    /**
     * Batch change the plan of story.
     *
     * @param  int    $planID
     * @param  int    $oldPlanID
     * @access public
     * @return void
     */
    public function batchChangePlan(int $planID, int $oldPlanID = 0)
    {
        echo $this->fetch('story', 'batchChangePlan', "planID=$planID&oldPlanID=$oldPlanID");
    }

    /**
     * Batch assign to.
     *
     * @param  string $storyType story|requirement
     * @access public
     * @return void
     */
    public function batchAssignTo(string $storyType = 'story', string $assignedTo = '')
    {
        echo $this->fetch('story', 'batchAssignTo', "storyType=$storyType&assignedTo=$assignedTo");
    }


    /**
     * Batch edit story.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @param  int    $branch
     * @param  string $storyType
     * @param  string $from
     * @access public
     * @return void
     */
    public function batchEdit(int $productID = 0, int $executionID = 0, string $branch = '', string $storyType = 'story', string $from = '')
    {
        echo $this->fetch('story', 'batchEdit', "productID=$productID&executionID=$executionID&branch=$branch&storyType=$storyType&from=$from");
    }

    /**
     * 导入需求数据。
     * Import the data of the requiremens.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $storyType
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function import($productID, $branch = 0, $storyType = 'story', $projectID = 0)
    {
        echo $this->fetch('story', 'import', "productID=$productID&branch=$branch&storyType=$storyType&projectID=$projectID");
    }

    /**
     * 显示导入需求数据的页面。
     * Show the page of importing the data of the requiremens.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $type
     * @param  int    $projectID
     * @param  int    $pagerID
     * @param  int    $maxImport
     * @param  string $insert
     * @access public
     * @return void
     */
    public function showImport($productID, $branch = 0, $type = 'story', $projectID = 0, $pagerID = 1, $maxImport = 0, $insert = '')
    {
        echo $this->fetch('story', 'showImport', "productID=$productID&branch=$branch&type=$type&projectID=$projectID&pagerID=$pagerID&maxImport=$maxImport&insert=$insert");
    }

    /**
     * 导出需求模板。
     * Export the template of the requiremens.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $storyType
     * @access public
     * @return void
     */
    public function exportTemplate($productID, $branch = 0, $storyType = 'story')
    {
        echo $this->fetch('story', 'exportTemplate', "productID=$productID&branch=$branch&storyType=$storyType");
    }

    /**
     * 批量导入需求到需求库。
     * Batch import the data of the requiremens to the library.
     *
     * @access public
     * @return void
     */
    public function batchImportToLib()
    {
        echo $this->fetch('story', 'batchImportToLib');
    }

    /**
     * 导入需求到需求库。
     * Import the data of the requiremens to the library.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function importToLib($storyID)
    {
        echo $this->fetch('story', 'importToLib', "storyID=$storyID");
    }
}
