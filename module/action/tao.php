<?php
declare(strict_types=1);
class actionTao extends actionModel
{
    /**
     * 获取一个action的基础数据。
     * Fetch base info of a action.
     *
     * @param  int $actionID
     * @access protected
     * @return object|bool
     */
    protected function fetchBaseInfo(int $actionID): object|bool
    {
        return $this->dao->select('*')->from(TABLE_ACTION)->where('id')->eq($actionID)->fetch();
    }

    /**
     * 获取一个基础对象的信息。
     * Get object base info.
     *
     * @param  string $table
     * @param  array  $queryParam
     * @param  string $field
     * @param  string $orderby
     * @access protected
     * @return object|bool
     */
    protected function getObjectBaseInfo(string $table, array $queryParam, string $field = '*', string $orderby = ''): object|bool
    {
        $querys = array_map(function($key, $query){return "`{$key}` = '{$query}'";}, array_keys($queryParam), $queryParam);
        return $this->dao->select($field)->from($table)->where(implode(' and ', $querys))->orderby($orderby)->fetch();
    }

    /**
     * 获取已经删除了的阶段列表。
     * Get deleted staged list.
     *
     * @param  array $stagePathList
     * @access protected
     * @return void
     */
    protected function getDeletedStagedList(array $stagePathList)
    {
        return $this->dao->select('*')->from(TABLE_EXECUTION)->where('id')->in($stagePathList)->andWhere('deleted')->eq(1)->andWhere('type')->eq('stage')->orderBy('id_asc')->fetchAll('id');
    }

    /**
     * 根据执行id获取属性。
     * Get attribute by execution id.
     *
     * @param  int $id
     * @access protected
     * @return object
     */
    protected function getAttributeByID($id): object
    {
        return $this->dao->select('attribute')->from(TABLE_EXECUTION)->where('id')->eq($id)->fetch('attribute');
    }

    /**
     * 获取无需过滤的关联关系。
     * Get no filter required relation.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access protected
     * @return array
     */
    protected function getNoFilterRequiredRelation(string $objectType, int $objectID): array
    {
        $product   = array(0);
        $project   = 0;
        $execution = 0;
        switch($objectType)
        {
            case 'product':
                $product = array($objectID);
                break;
            case 'project':
            case 'execution':
                $productList = $this->getProductByProject($objectID);
                extract(array($objectType => $objectID, 'product' => $productList));

                if($objectType == 'execution')
                {
                    $executionInfo = $this->loadModel('execution')->getById($objectID);
                    $project = $executionInfo ? $executionInfo->project : 0;
                }
                break;
        }

        return array($product, $project, $execution);
    }

    /**
     * 根据项目获取产品。
     * Get product by project.
     *
     * @param  int   $objectID
     * @access protected
     * @return array
     */
    protected function getProductByProject(int $objectID): array
    {
        $products = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($objectID)->fetchPairs('product');
        return $products ? array_keys($products) : array();
    }

    /**
     * 根据项目获取产品列表。
     * Get product list by project.
     *
     * @param  int   $projectID
     * @access protected
     * @return array
     */
    protected function getProductListByProject(int $projectID): array
    {
        $products = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetchPairs('product');
        return $products ? array_keys($products) : array();
    }

    /**
     * 获取用户故事相关的产品、项目、阶段。
     * Get story related product, project, stage.
     *
     * @param  string $actionType
     * @param  int    $objectID
     * @param  int    $extra
     * @access protected
     * @return array
     */
    protected function getStoryActionRelated(string $actionType, int $objectID, int $extra): array
    {
        $product = array(0);
        $project = $execution = 0;
        switch($actionType)
        {
            case 'linked2build':
            case 'unlinkedfrombuild':
                $build = $this->loadModel('build')->getByID($extra);
                if($build)
                {
                    $project   = $build->project;
                    $execution = $build->execution;
                }

                break;
            case 'estimated':
                $executionInfo = $this->loadModel('execution')->getById($extra);
                if($execution) $project = $executionInfo->project;
                $execution = $extra;

                break;
            default:
                $projectList = $this->dao->select('t2.id,t2.project,t2.type')->from(TABLE_PROJECTSTORY)->alias('t1')
                    ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
                    ->where('t1.story')->eq($objectID)
                    ->fetchAll();
                foreach($projectList as $projectInfo)
                {
                    if($projectInfo->type == 'project')
                    {
                        $project = $projectInfo->id;
                        continue;
                    }
                    $project   = $projectInfo->project;
                    $execution = $projectInfo->id;
                }
                break;
        }

        return array($product, $project, $execution);
    }

    /**
     * 获取用例相关的产品、项目、阶段。
     * Get case related product, project, stage.
     *
     * @param  string $objectType
     * @param  string $actionType
     * @param  string $table
     * @param  int    $objectID
     * @param  int    $extra
     * @access protected
     * @return array
     */
    protected function getCaseRelated(string $objectType, string $actionType, int $objectID, int $extra): array
    {
        $product = array(0);
        $project = $execution = 0;
        $result  = $this->dao->select('product, project, execution')->from($this->config->objectTables[$objectType])->where('id')->eq($objectID)->fetch();

        if($result)
        {
            $product   = explode(',', (string)$result->product);
            $project   = $result->project;
            $execution = $result->execution;
        }

        if(in_array($actionType, array('linked2testtask', 'unlinkedfromtesttask', 'assigned', 'run')) && $extra)
        {
            $testtask = $this->dao->select('project,execution')->from(TABLE_TESTTASK)->where('id')->eq($extra)->fetch();
            $project   = $testtask->project;
            $execution = $testtask->execution;
        }

        return array($product, $project, $execution);
    }

    /**
     * 常规获取相关的产品、项目、执行。
     * Get general related product, project, execution.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $field
     * @access protected
     * @return array
     */
    protected function getGenerateRelated(string $objectType, int $objectID, string $field = 'product, project, execution'): array
    {
        $product = array(0);
        $project = $execution = 0;

        $result  = $this->dao->select($field)->from($this->config->objectTables[$objectType])->where('id')->eq($objectID)->fetch();

        if($result)
        {
            $product   = implode(',', array($result->product));
            $project   = $result->project;
            $execution = $result->execution;
        }

        return array($product, $project, $execution);
    }

    /**
     * 获取用例相关的产品、项目、执行。
     * Get case related product, project, execution.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access protected
     * @return array
     */
    protected function getReleaseRelated(string $objectType, int $objectID): array
    {
        $product = array(0);
        $project = $execution = 0;

        $result  = $this->dao->select('product, build')->from($this->config->objectTables[$objectType])->where('id')->eq($objectID)->fetch();

        if($result)
        {
            $product = $result->product;
            $project = $this->dao->select('project')->from(TABLE_BUILD)->where('id')->eq($result->build)->fetch('project');
        }

        return array($product, $project, $execution);
    }

    /**
     * 获取任务相关的产品、项目、执行。
     * Get task related product, project, execution.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access protected
     * @return array
     */
    protected function getTaskReleated(string $objectType, int $objectID): array
    {
        $product = array(0);
        $project = $execution = 0;

        $fields = 'project, execution, story';
        $result = $this->dao->select($fields)->from($this->config->objectTables[$objectType])->where('id')->eq($objectID)->fetch();

        if($result)
        {
            $table = $result->story != 0 ? TABLE_STORY : TABLE_PROJECTPRODUCT;
            $field = $result->story != 0 ? 'id' : 'project';
            $value = $result->story != 0 ? $result->story : $result->execution;

            $products = $this->dao->select('product')->from($table)->where($field)->eq($value)->fetch('product');
            $product  = $products ? array($products) : array();

            $project   = $result->project;
            $execution = $result->execution;
        }

        return array($product, $project, $execution);
    }

    /**
     * 获取需求相关的产品、项目、执行。
     * Get story related product, project, execution.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access protected
     * @return array
     */
    protected function getReviewRelated(string $objectType, int $objectID): array
    {
        $product = array(0);
        $project = $execution = 0;

        $result = $this->dao->select('*')->from($this->config->objectTables[$objectType])->where('id')->eq($objectID)->fetch();

        if($result)
        {
            $products = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($result->project)->fetchPairs('product');
            if(!empty($products)) array_keys($products);
            $project  = zget($result, 'project', 0);
        }

        return array($product, $project, $execution);
    }
}
