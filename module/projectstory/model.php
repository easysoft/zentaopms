<?php
declare(strict_types=1);
/**
 * The model file of projectStory module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     projectStory
 * @version     $Id
 * @link        https://www.zentao.net
 */
class projectstoryModel extends model
{
    /**
     * Set the menu.
     *
     * @param  array $products
     * @param  int   $productID
     * @param  int   $branch
     * @access public
     * @return void
     */
    public function setMenu($products = array(), $productID = 0, $branch = 0)
    {
        /* Determine if the product is accessible. */
        if($products and (!isset($products[$productID]) or !$this->loadModel('product')->checkPriv($productID))) $this->loadModel('product')->accessDenied();

        if(empty($productID)) $productID = key($products);
        $this->loadModel('product')->setMenu($products, $productID, $branch);
        $this->lang->modulePageNav = $this->product->select($products, $productID, 'projectstory', $this->app->rawMethod, '', $branch);
    }

    /**
     * Get the stories for execution linked.
     *
     * @param  int    $projectID
     * @param  array  $storyIdList
     * @access public
     * @return array
     */
    public function getExecutionStories($projectID, $storyIdList = array())
    {
        $stories     = array();
        $storyIdList = (array)$storyIdList;

        if(empty($storyIdList)) return $stories;

        return $this->dao->select('t2.id as id, t2.title as title, t3.id as executionID, t3.name as execution')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id')
            ->leftJoin(TABLE_EXECUTION)->alias('t3')->on('t1.project=t3.id')
            ->where('t1.story')->in($storyIdList)
            ->andWhere('t3.type')->in('sprint,stage,kanban')
            ->andWhere('t3.project')->eq($projectID)
            ->andWhere('t3.deleted')->eq(0)
            ->fetchAll('id');
    }
}
