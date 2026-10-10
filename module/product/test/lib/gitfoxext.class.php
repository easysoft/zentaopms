<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

/**
 * GitFox 独立版 product 模型扩展的测试类。
 * 测试环境的 vision 为 rnd，框架不会把 extension/gitfox/product/ext/model/gitfox.php 合并到 productModel，
 * 因此这里使用框架的扩展合并机制加载扩展方法后再测试。
 *
 * @property tmpExtproductModel $instance
 */
class productGitfoxExtModelTest
{
    /**
     * 被测试的扩展模型实例。
     * The instance of the merged product model.
     *
     * @var object
     * @access public
     */
    public $instance = null;

    /**
     * 构造函数，加载 GitFox 独立版的 product 模型扩展。
     * Constructor, load the product model extension of GitFox edition.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        global $app;

        $extFile = dirname(__FILE__, 5) . '/extension/gitfox/product/ext/model/gitfox.php';
        $tmpDir  = $app->getTmpRoot() . 'model' . DS . 'gitfoxext' . DS;
        if(!is_dir($tmpDir)) mkdir($tmpDir, 0755, true);

        $app->mergeTargetExtFiles('product', array($extFile), $tmpDir, 'model');
        $this->instance = new tmpExtproductModel('product');
    }

    /**
     * 测试获取 GitFox 应用列表。
     * Test getProductsInGitfox method.
     *
     * @param  string    $status
     * @param  string    $orderBy
     * @param  string    $keyword
     * @param  ?object   $pager
     * @param  int       $spaceID
     * @access public
     * @return array
     */
    public function getProductsInGitfoxTest(string $status = 'all', string $orderBy = 'id_desc', string $keyword = '', ?object $pager = null, int $spaceID = 0): array
    {
        return $this->instance->getProductsInGitfox($status, $orderBy, $keyword, $pager, $spaceID);
    }

    /**
     * 测试获取 GitFox 应用列表的数量。
     * Test the count of getProductsInGitfox result.
     *
     * @param  string $status
     * @param  string $keyword
     * @access public
     * @return int
     */
    public function getProductsInGitfoxCountTest(string $status = 'all', string $keyword = ''): int
    {
        return count($this->getProductsInGitfoxTest($status, 'id_desc', $keyword));
    }

    /**
     * 测试获取 GitFox 应用列表中第一个应用的名称。
     * Test the first product name of getProductsInGitfox result.
     *
     * @param  string $status
     * @param  string $keyword
     * @access public
     * @return string
     */
    public function getProductsInGitfoxFirstNameTest(string $status = 'all', string $keyword = ''): string
    {
        $products = $this->getProductsInGitfoxTest($status, 'id_asc', $keyword);
        if(empty($products)) return 'empty';

        $product = reset($products);
        return $product->name;
    }

    /**
     * 测试同步更新应用关联的系统。
     * Test updateSystemsByProduct method.
     *
     * @param  int    $productID
     * @param  string $productName
     * @access public
     * @return string
     */
    public function updateSystemsByProductTest(int $productID, string $productName): string
    {
        return $this->instance->updateSystemsByProduct($productID, $productName) ? '1' : '0';
    }

    /**
     * 测试获取指定系统字段的值。
     * Test get the field value of the given system.
     *
     * @param  int    $systemID
     * @param  string $field
     * @access public
     * @return string
     */
    public function systemFieldTest(int $systemID, string $field): string
    {
        global $tester;

        $system = $tester->dao->select('*')->from(TABLE_SYSTEM)->where('id')->eq($systemID)->fetch();
        if(empty($system) || !isset($system->$field)) return 'none';

        return (string)$system->$field;
    }

    /**
     * 测试获取指定产品的应用数量。
     * Test get the system count of the given product.
     *
     * @param  int $productID
     * @param  int $deleted
     * @access public
     * @return int
     */
    public function systemCountTest(int $productID, int $deleted = 0): int
    {
        global $tester;

        return (int)$tester->dao->select('count(1) as count')->from(TABLE_SYSTEM)
            ->where('product')->eq($productID)
            ->andWhere('deleted')->eq($deleted)
            ->fetch('count');
    }

    /**
     * 测试同步删除应用关联的系统。
     * Test deleteSystemsByProduct method.
     *
     * @param  int $productID
     * @access public
     * @return string
     */
    public function deleteSystemsByProductTest(int $productID): string
    {
        return $this->instance->deleteSystemsByProduct($productID) ? '1' : '0';
    }

    /**
     * 测试删除应用及其关联的系统。
     * Test deleteProductWithSystems method.
     *
     * @param  int $productID
     * @access public
     * @return string
     */
    public function deleteProductWithSystemsTest(int $productID): string
    {
        return $this->instance->deleteProductWithSystems($productID) ? '1' : '0';
    }

    /**
     * 测试获取产品字段的值。
     * Test get the field value of the given product.
     *
     * @param  int    $productID
     * @param  string $field
     * @access public
     * @return string
     */
    public function productFieldTest(int $productID, string $field): string
    {
        global $tester;

        $product = $tester->dao->select('*')->from(TABLE_PRODUCT)->where('id')->eq($productID)->fetch();
        if(empty($product) || !isset($product->$field)) return 'none';

        return (string)$product->$field;
    }
}
