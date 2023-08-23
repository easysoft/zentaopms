<?php
class testtaskZen extends testtask
{
    /**
     * 根据不同情况获取不同的产品列表，大多用于1.5级导航。
     * Get products.
     *
     * @access protected
     * @return array
     */
    protected function getProducts(): array
    {
        /* Get product data. */
        $this->loadModel('product');
        $products = array();
        $objectID = 0;
        $tab      = ($this->app->tab == 'project' or $this->app->tab == 'execution') ? $this->app->tab : 'qa';
        if(!isonlybody())
        {
            if($tab == 'project')
            {
                /* 如果是在项目应用下打开的测试单，则获取当前项目对应的产品。 */
                $objectID = $this->session->project;
                $products = $this->product->getProducts($objectID, 'all', '', false);
            }
            elseif($tab == 'execution')
            {
                /* 如果是在执行应用下打开的测试单，则获取当前执行对应的产品。 */
                $objectID = $this->session->execution;
                $products = $this->product->getProducts($objectID, 'all', '', false);
            }
            else
            {
                /* 如果是在测试应用下打开的测试单，则获取所有产品。 */
                $products = $this->product->getPairs('', 0, '', 'all');
            }
            if(empty($products) and !helper::isAjaxRequest()) helper::end($this->locate($this->createLink('product', 'showErrorNone', "moduleName=$tab&activeMenu=testtask&objectID=$objectID")));
        }
        else
        {
            /* 如果是在弹窗下打开的测试单，则获取所有产品。 */
            $products = $this->product->getPairs('', 0, '', 'all');
        }

        return $products;
    }
}
