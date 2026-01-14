<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class projectreleaseTaoTest extends baseTest
{
    protected $moduleName = 'projectrelease';
    protected $className  = 'tao';

    /**
     * 测试获取项目发布列表。
     * Test get list of releases.
     *
     * @param  int          $projectID
     * @param  string       $type
     * @param  string       $orderBy
     * @access public
     * @return array|string
     */
    public function getListTest(int $projectID, string $type = 'all', string $orderBy = 't1.date_desc'): array|string
    {
        $objects = $this->objectModel->getList($projectID, $type, $orderBy);

        if(dao::isError()) return dao::getError();

        return implode(',', array_column($objects, 'id'));
    }

    /**
     * 测试获取项目的最新发布。
     * Test get last release.
     *
     * @param  int               $projectID
     * @access public
     * @return object|array|bool
     */
    public function getLastTest(int $projectID): object|array|bool
    {
        $object = $this->objectModel->getLast($projectID);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * 测试获取项目已经发布的版本。
     * Test get released builds from project.
     *
     * @param  int          $projectID
     * @access public
     * @return string|array
     */
    public function getReleasedBuildsTest($projectID): string|array
    {
        $objects = $this->objectModel->getReleasedBuilds($projectID);

        if(dao::isError()) return dao::getError();
        return implode(',', $objects);
    }

    /**
     * 测试项目发布信息，包括分支名称、版本信息等。
     * Test process release.
     *
     * @param  int          $releaseID
     * @access public
     * @return string|array
     */
    public function processReleaseTest(int $releaseID): string|array
    {
        global $tester;
        $release     = $tester->dao->findById($releaseID)->from(TABLE_RELEASE)->fetch();
        $branchGroup = $tester->loadModel('branch')->getByProducts(explode(',', (string)$release->product));
        $builds      = $tester->dao->select("id, project, product, branch, execution, name, scmPath, filePath")->from(TABLE_BUILD)->where('id')->in($release->build)->fetchAll('id');
        $this->objectModel->processRelease($release, $branchGroup, $builds);

        if(dao::isError()) return dao::getError();

        $return = "project:{$release->project} branch:{$release->branch} build:{$release->build} branchName:{$release->branchName} buildInfos:" . implode(',', array_column($release->buildInfos, 'name'));
        return $return;
    }

    /**
     * 测试获取项目的最新发布。
     * Test get last release.
     *
     * @param  object    $release
     * @param  string    $action
     * @access public
     * @return int|array
     */
    public function isClickableTest(object $release, string $action): int|array
    {
        $isClickable = $this->objectModel->isClickable($release, $action);

        if(dao::isError()) return dao::getError();

        return $isClickable ? 1 : 2;
    }

    /**
     * 测试获取分支名称。
     * Test get branch name.
     *
     * @param  int     $productID
     * @param  string  $branch
     * @param  array   $branchGroup
     * @access public
     * @return string|array
     */
    public function getBranchNameTest(int $productID, string $branch, array $branchGroup): string|array
    {
        $reflectionClass = new ReflectionClass($this->instance);
        $method = $reflectionClass->getMethod('getBranchName');
        $method->setAccessible(true);
        $result = $method->invoke($this->instance, $productID, $branch, $branchGroup);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试通用初始化方法。
     * Test common action method.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  string $branch
     * @access public
     * @return array|string
     */
    public function commonActionTest(int $projectID = 0, int $productID = 0, string $branch = ''): array|string
    {
        global $tester;

        $productModel = $tester->loadModel('product');
        $projectModel = $tester->loadModel('project');
        $branchModel = $tester->loadModel('branch');

        $products = $productModel->getProductPairsByProject($projectID);
        if(empty($products)) return '0,0,0,,0,0';

        if(!$productID) $productID = key($products);
        $product = $productModel->getByID($productID);
        if(!$product) return '0,0,0,,0,0';

        $project = $projectModel->getByID($projectID);
        $branches = (isset($product->type) and $product->type == 'normal') ? array() : $branchModel->getPairs($productID, 'active', $projectID);

        if(dao::isError()) return dao::getError();

        $result = array();
        $result['productsCount'] = count($products);
        $result['productID'] = $product ? $product->id : 0;
        $result['branchesCount'] = count($branches);
        $result['branch'] = $branch;
        $result['projectID'] = $project ? $project->id : 0;
        $result['appListCount'] = 0;

        return implode(',', $result);
    }
}
