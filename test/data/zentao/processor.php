<?php
/**
 * Processor for data generation.
 *
 * @package zentao
 * @version $id$
 * @copyright 2009-2022 Easysoft corp.
 * @author zjy
 * @license ZPL
 */
class Processor
{
    /**
     * Construct
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        global $dao;
        $this->dao = $dao;
    }

    /**
     * Init data.
     *
     * @access public
     * @return void
     */
    public function init()
    {
        $this->dao->begin();

        $this->initDept();
        $this->initUser();
        $this->initProgram();
        $this->initProduct();
        $this->initPlan();
        $this->initProject();
        $this->initBuild();
        $this->initTask();
        $this->initExecution();
        $this->initRelease();

        $this->dao->commit();
    }

    /**
     * Init department.
     *
     * @access public
     * @return void
     */
    private function initDept()
    {
        $data = array('parent' => 2, 'path' => ",2,5,", 'grade' => 2);
        $this->dao->update(TABLE_DEPT)->data($data)->where('id')->eq(5)->exec();

        $data = array('parent' => 2, 'path' => ",2,6,", 'grade' => 2);
        $this->dao->update(TABLE_DEPT)->data($data)->where('id')->eq(6)->exec();

        for($id = 18; $id <= 27; $id++)
        {
            $parent = $id - 10;
            $child  = $id + 10;

            $data = array('parent' => $parent, 'path' => ",$parent,$id,", 'grade' => 2);
            $this->dao->update(TABLE_DEPT)->data($data)->where('id')->eq($id)->exec();

            $data = array('parent' => $id, 'path' => ",$parent,$id,$child,", 'grade' => 3);
            $this->dao->update(TABLE_DEPT)->data($data)->where('id')->eq($child)->exec();
        }
    }

    /**
     * Init user.
     *
     * @access public
     * @return void
     */
    private function initUser()
    {
        $users = array();
        $users['user1'] = array('account' => 'program1whitelist', 'realname' => '项目集1白名单用户');
        $users['user2'] = array('account' => 'noprogram1', 'realname' => '不在项目集1用户');

        foreach($users as $account => $user) $this->dao->update(TABLE_USER)->data($user)->where('account')->eq($account)->exec();
    }

    /**
     * Init program.
     *
     * @access public
     * @return void
     */
    private function initProgram()
    {
    }

    /**
     * Init product.
     *
     * @access public
     * @return void
     */
    private function initProduct()
    {
    }

    /**
     * Init product plan.
     *
     * @access public
     * @return void
     */
    private function initPlan()
    {
    }

    /**
     * Init project.
     *
     * @access public
     * @return void
     */
    private function initProject()
    {
    }

    /**
     * Init build.
     *
     * @access public
     * @return void
     */
    private function initBuild()
    {
    }

    /**
     * Init task.
     *
     * @access public
     * @return void
     */
    private function initTask()
    {
    }

    /**
     * Init execution.
     *
     * @access public
     * @return void
     */
    private function initExecution()
    {
        /* Add relationship of projectproduct. */
        $projectProducts = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->fetchAll();
        $productsInProject = array();
        foreach($projectProducts as $relation)
        {
            if(!isset($productsInProject[$relation->project])) $productsInProject[$relation->project] = array();
            $productsInProject[$relation->project][] = $relation->product;
        }

        $executions = $this->dao->select('*')->from(TABLE_PROJECT)->where('type')->in('sprint,kanban,stage')->fetchAll();
        foreach($executions as $execution)
        {
            $products = $productsInProject[$execution->project];
            foreach($products as $product)
            {
                $data = new stdclass();
                $data->project = $execution->id;
                $data->product = $product;
                $this->dao->insert(TABLE_PROJECTPRODUCT)->data($data)->exec();
            }
        }
    }

    /**
     * Init release.
     *
     * @access public
     * @return void
     */
    private function initRelease()
    {
    }
}
