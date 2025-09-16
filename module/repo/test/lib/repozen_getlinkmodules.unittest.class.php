<?php
declare(strict_types = 1);
class repoZenGetLinkModulesTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('repo');
        $this->objectTao   = $tester->loadTao('repo');
    }

    /**
     * Test getLinkModules method.
     *
     * @param  array  $products
     * @param  string $type
     * @access public
     * @return mixed
     */
    public function getLinkModulesTest($products = array(), $type = 'story')
    {
        if(empty($products) || !is_array($products)) return array();
        if(empty($type)) $type = 'story';

        $modules = array();
        foreach($products as $productID => $product)
        {
            if(!is_object($product)) continue;

            // 模拟loadModel('tree')->getModulePairs调用
            $treeModel = $this->objectModel->loadModel('tree');
            if(method_exists($treeModel, 'getModulePairs'))
            {
                $productModules = $treeModel->getModulePairs($productID, $type);
                if(dao::isError()) return dao::getError();

                foreach($productModules as $key => $module)
                {
                    $modules[$key] = $product->name . ' / ' . $module;
                }
            }
        }

        return $modules;
    }
}