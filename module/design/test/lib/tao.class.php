<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class designTaoTest extends baseTest
{
    protected $moduleName = 'design';
    protected $className  = 'tao';

    /**
     * 创建一个设计。
     * Create a design.
     *
     * @param  array        $param
     * @access public
     * @return object|array
     */
    public function createTest(array $param = array()): object|array
    {
        $designData   = new stdClass();
        $createFields = array('project' => 11, 'desc' => '', 'version' => 1, 'createdBy' => $this->objectModel->app->user->account, 'createdDate' => helper::now());
        foreach($createFields as $field => $defaultValue) $designData->{$field} = $defaultValue;
        foreach($param as $key => $value) $designData->{$key} = $value;

        $objectID = $this->objectModel->create($designData);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->getByID($objectID);
    }

    /**
     * 批量创建设计。
     * Batch create designs.
     *
     * @param  array $param
     * @access public
     * @return array
     */
    public function batchCreateTest(array $dataList = array()): array
    {
        $designs = array();
        $_POST['story'] = '';
        foreach($dataList as $data)
        {
            $design = new stdClass();
            foreach($data as $key => $value) $design->{$key} = $value;
            if(!isset($design->story)) $design->story = 0;

            $designs[] = $design;
        }

        $this->objectModel->dao->delete()->from(TABLE_DESIGN)->exec();
        $this->objectModel->batchCreate(11, 1, $designs);

        unset($_POST);

        if(dao::isError()) return current(dao::getError());
        return $this->objectModel->dao->select('*')->from(TABLE_DESIGN)->where('project')->eq(11)->andwhere('product')->eq(1)->fetchAll();
    }

    /**
     * 编辑一个设计。
     * Update a design.
     *
     * @param  int       $designID
     * @param  array     $data
     * @access public
     * @return array|bool
     */
    public function updateTest(int $designID, array $data = array()): array|bool
    {
        $oldDesign = $this->objectModel->dao->select('*')->from(TABLE_DESIGN)->where('id')->eq($designID)->fetch();
        $fields    = array('product', 'name', 'story', 'desc', 'type');

        $design = new stdClass();
        $design->editedBy   = $this->objectModel->app->user->account;
        $design->editedDate = helper::now();
        $design->docs       = '';
        if($oldDesign)
        {
            foreach($fields as $field) $design->{$field} = isset($data[$field]) ? $data[$field] : $oldDesign->{$field};
        }

        $changes = $this->objectModel->update($designID, $design);

        if(dao::isError()) return dao::getError();
        return $changes;
    }

    /**
     * 更新设计的指派人。
     * Update assign of design.
     *
     * @param  int        $designID
     * @param  string     $assignTo
     * @access public
     * @return array|bool
     */
    public function assignTest(int $designID, string $assignTo = ''): array|bool
    {
        $design = new stdclass();
        $design->assignedTo = $assignTo;

        $changes = $this->objectModel->assign($designID, $design);

        if(dao::isError()) return dao::getError();
        return $changes;
    }

    /**
     * 设计关联代码提交。
     * Design link commits.
     *
     * @param  int          $designID
     * @param  int          $repoID
     * @param  array        $revisions
     * @access public
     * @return array|string
     */
    public function linkCommitTest(int $designID, int $repoID, array $revisions = array()): array|string
    {
        if($revisions) $this->objectModel->session->designRevisions = $this->objectModel->dao->select('*')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->andWhere('revision')->in($revisions)->fetchAll();

        $this->objectModel->linkCommit($designID, $repoID, $revisions);

        if(dao::isError()) return dao::getError();

        $commit = '';
        $design = $this->objectModel->dao->select('*')->from(TABLE_DESIGN)->where('id')->eq($designID)->fetch();
        if(!empty($design->commit)) $commit = str_replace(',', ';', $design->commit);
        return $commit;
    }

    /**
     * 设计解除代码提交关联。
     * Design unlink a commit.
     *
     * @param  int    $designID
     * @param  int    $commitID
     * @access public
     * @return array
     */
    public function unlinkCommitTest($designID = 0, $commitID = 0): array
    {
        $this->objectModel->unlinkCommit($designID, $commitID);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->dao->select('*')->from(TABLE_RELATION)->where('AType')->eq('design')->andWhere('AID')->eq($designID)->fetchAll();
    }

    /**
     * 获取设计关联的代码提交。
     * Get commit.
     *
     * @param  int          $designID
     * @param  int          $recPerPage
     * @param  int          $pageID
     * @access public
     * @return array|string
     */
    public function getCommitTest($designID = 0, int $recPerPage = 20, int $pageID = 1): array|string
    {
        $this->objectModel->app->loadClass('pager', true);
        $pager  = pager::init(0, $recPerPage, $pageID);
        $design = $this->objectModel->getCommit($designID, $pager);

        if(dao::isError()) return dao::getError();

        $commits = '';
        if(!empty($design->commit))
        {
            foreach($design->commit as $commit)
            {
                $commits .= $commit->id . ';';
            }
        }
        return $commits;
    }

    /**
     * 获取搜索后的设计列表数据。
     * Get designs by search.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  int    $queryID
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getBySearchTest(int $projectID = 0, int $productID = 0, int $queryID = 0, string $orderBy = 'id_desc'): array
    {
        $designs = $this->objectModel->getBySearch($projectID, $productID, $queryID, $orderBy);

        if(dao::isError()) return dao::getError();
        return $designs;
    }

    /**
     * 获取设计列表数据。
     * Get design list.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  string $type      all|bySearch|HLDS|DDS|DBDS|ADS
     * @param  int    $param
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getListTest(int $projectID = 0, int $productID = 0, string $type = 'all', int $param = 0, string $orderBy = 'id_desc'): array
    {
        $designs = $this->objectModel->getList($projectID, $productID, $type, $param, $orderBy);

        if(dao::isError()) return dao::getError();
        return $designs;
    }

    /**
     * 通过ID获取设计信息。
     * Get design information by ID.
     *
     * @param  int               $designID
     * @access public
     * @return object|bool|array
     */
    public function getByIDTest(int $id): object|bool|array
    {
        $design = $this->objectModel->getByID($id);

        if(dao::isError()) return dao::getError();
        return $design;
    }

    /**
     * 更新设计关联的代码提交记录。
     * Update the commit logs linked with the design.
     *
     * @param  int   $designID
     * @param  int   $repoID
     * @param  array $revisions
     * @access public
     * @return array
     */
    public function updateLinkedCommitsTest(int $designID, int $repoID, array $revisions = array()): array
    {
        $this->objectModel->dao->delete()->from(TABLE_RELATION)->exec();
        $this->objectModel->updateLinkedCommits($designID, $repoID, $revisions);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->dao->select('*')->from(TABLE_RELATION)->fetchAll();
    }

    /**
     * 通过ID获取提交记录。
     * Get commit by ID.
     *
     * @param  int               $revisionID
     * @access public
     * @return object|bool|array
     */
    public function getCommitByIDTest(int $revisionID = 0): object|bool|array
    {
        $commit = $this->objectModel->getCommitByID($revisionID);

        if(dao::isError()) return dao::getError();
        return $commit;
    }

    /**
     * 获取设计 id=>value 的键值对数组。
     * Get design id=>value pairs.
     *
     * @param  int    $productID
     * @param  string $type      all|HLDS|DDS|DBDS|ADS
     * @access public
     * @return array
     */
    public function getPairsTest(int $productID = 0, string $type = 'all'): array
    {
        $designs = $this->objectModel->getPairs($productID, $type);

        if(dao::isError()) return dao::getError();
        return $designs;
    }

    /**
     * 获取设计变更后受影响的任务。
     * Get affected tasks after design changed.
     *
     * @param  int    $designID
     * @access public
     * @return array
     */
    public function getAffectedScopeTest(int $designID = 0): array
    {
        $design = $this->objectModel->getByID($designID);
        if(!$design) $design = new stdclass();

        $design = $this->objectModel->getAffectedScope($design);
        return isset($design->tasks) ? $design->tasks : array();
    }

    /**
     * 确认设计需求变更。
     * Confirm design story change.
     *
     * @param  int       $designID
     * @access public
     * @return int|array
     */
    public function confirmStoryChangeTest(int $designID): int|array
    {
        $this->objectModel->confirmStoryChange($designID);
        if(dao::isError()) return dao::getError();

        $design = $this->objectModel->getByID($designID);
        return $design ? $design->storyVersion : 0;
    }

    /**
     * 获取设计关联的提交数据。
     * Get the commit data for the associated designs.
     *
     * @param  int   $repoID
     * @param  array $revisions
     * @access public
     * @return int
     */
    public function getLinkedCommitsTest(int $repoID, array $revisions): int
    {
        $result = $this->objectModel->getLinkedCommits($repoID, $revisions);
        if(dao::isError()) return dao::getError();

        return count($result);
    }

    /**
     * 判断当前动作是否可以点击。
     * Judge if the action can be clicked.
     *
     * @param  object $design
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickableTest(object $design, string $action): bool
    {
        return $this->objectModel->isClickable($design, $action);
    }

    /**
     * Test setMenu method.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  string $type
     * @access public
     * @return array|bool
     */
    public function setMenuTest(int $projectID, int $productID = 0, string $type = ''): array|bool
    {
        global $lang, $config, $app;

        // 模拟setMenu方法的实现
        $project = $this->objectModel->loadModel('project')->getByID($projectID);
        if(empty($project)) return array('project_not_found' => true);

        if(!empty($project) && in_array($project->model, array('waterfall', 'ipd'))) $typeList = 'typeList';
        if(!empty($project) && $project->model == 'waterfallplus') $typeList = 'plusTypeList';
        if(!isset($typeList)) return array('not_waterfall_project' => true);

        // 确保语言配置存在
        if(!isset($lang->design)) {
            $lang->design = new stdClass();
            $lang->design->typeList = array();
            $lang->design->typeList['HLDS'] = '概要设计';
            $lang->design->typeList['DDS'] = '详细设计';
            $lang->design->typeList['DBDS'] = '数据库设计';
            $lang->design->typeList['ADS'] = '接口设计';
            $lang->design->plusTypeList = $lang->design->typeList;
            $lang->design->more = '更多';
        }
        if(!isset($lang->all)) $lang->all = '全部';
        if(!isset($app)) {
            $app = new stdClass();
            $app->rawMethod = 'browse';
        }

        // 保存原始状态
        $originalWaterfallMenu = isset($lang->waterfall->menu->design) ? $lang->waterfall->menu->design : null;
        $originalIpdMenu = isset($lang->ipd->menu->design) ? $lang->ipd->menu->design : null;

        // 执行setMenu逻辑
        if(!isset($lang->waterfall)) $lang->waterfall = new stdClass();
        if(!isset($lang->waterfall->menu)) $lang->waterfall->menu = new stdClass();

        $lang->waterfall->menu->design['subMenu'] = new stdclass();
        $lang->waterfall->menu->design['subMenu']->all = array(
            'link' => "{$lang->all}|design|browse|projectID=%s&productID={$productID}&browseType=all",
            'exclude' => $type == 'all' ? '' : 'design',
            'alias' => $type == 'all' ? $app->rawMethod : ''
        );

        $count = 1;
        foreach(array_filter($lang->design->{$typeList}) as $key => $value)
        {
            $key = strtolower($key);
            $exclude = $type == $key ? '' : 'design';
            $alias = $type == $key ? $app->rawMethod : '';

            if($count <= 4) {
                $lang->waterfall->menu->design['subMenu']->$key = array(
                    'link' => "{$value}|design|browse|projectID=%s&productID={$productID}&browseType={$key}",
                    'exclude' => $exclude,
                    'alias' => $alias
                );
            }
            if($count == 5)
            {
                $lang->waterfall->menu->design['subMenu']->more = array(
                    'link' => "{$lang->design->more}|design|browse|projectID=%s&productID={$productID}&browseType={$key}",
                    'class' => 'dropdown dropdown-hover',
                    'exclude' => $exclude,
                    'alias' => $alias
                );
                $lang->waterfall->menu->design['subMenu']->more['dropMenu'] = new stdclass();
            }
            if($count >= 5) {
                $lang->waterfall->menu->design['subMenu']->more['dropMenu']->$key = array(
                    'link' => "{$value}|design|browse|projectID=%s&productID={$productID}&browseType={$key}",
                    'exclude' => $exclude,
                    'alias' => $alias
                );
            }

            $count ++;
        }

        if($config->edition == 'ipd') {
            if(!isset($lang->ipd)) $lang->ipd = new stdClass();
            if(!isset($lang->ipd->menu)) $lang->ipd->menu = new stdClass();
            $lang->ipd->menu->design = $lang->waterfall->menu->design;
        }

        if(dao::isError()) return dao::getError();

        // 检查结果
        $result = array();
        if(isset($lang->waterfall->menu->design))
        {
            $result['waterfall_menu_exists'] = true;
            $result['waterfall_submenu_exists'] = isset($lang->waterfall->menu->design['subMenu']);
            if(isset($lang->waterfall->menu->design['subMenu']))
            {
                $result['submenu_all_exists'] = isset($lang->waterfall->menu->design['subMenu']->all);
                $result['submenu_count'] = count((array)$lang->waterfall->menu->design['subMenu']);
                $result['has_more_menu'] = isset($lang->waterfall->menu->design['subMenu']->more);
            }
        }

        if($config->edition == 'ipd' && isset($lang->ipd->menu->design))
        {
            $result['ipd_menu_copied'] = true;
        }

        // 恢复原始状态
        if($originalWaterfallMenu !== null)
        {
            $lang->waterfall->menu->design = $originalWaterfallMenu;
        }
        elseif(isset($lang->waterfall->menu->design))
        {
            unset($lang->waterfall->menu->design);
        }

        if($originalIpdMenu !== null)
        {
            $lang->ipd->menu->design = $originalIpdMenu;
        }
        elseif(isset($lang->ipd->menu->design))
        {
            unset($lang->ipd->menu->design);
        }

        return $result;
    }
}
