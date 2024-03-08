<?php
include dirname(__FILE__, 2) . '/zentao.class.php';
class product extends zentao
{

    /**
     * Test create product features.
     *
     * @param  object  $product
     * @access public
     * @return object
     */
    public function createProductTest($formData)
    {
        $productPage = new productPage($this->driver);

        $this->su();
        $this->go('product', 'mainNav');

        $this->createInFrom('product', $formData);
        $this->driver->wait(1);

        return $productPage;
    }
    /**
     * Access the product under secondary navigation.
     *
     * @param  int    $nav
     * @access public
     * @return mixed
     */
    public function toProductNav($nav)
    {
        $testData = $this->config->testData->product;
        $iframe   = $this->dom->common->iframe;

        $this->driver->switchToUrl('index.php?m=product&f=all', true);
        $this->driver->wait(1)->switchTo($iframe->product);

        $this->tester->searchProduct($testData->productName);
        try
        {
            $this->driver->wait(1)->click("link:{$testData->productName}");
        }
        catch(Exception $e)
        {
            $this->driver->wait(1)->click($this->dom->product->all->mainContent->productList->firstTR->programSpan);
            $this->driver->wait(1)->click("link:{$testData->productName}");
        }
        $this->driver->wait(1);
        $this->tester->openNav('product', $nav);
        $this->driver->getErrors($iframe->product);
    }

    /**
     * Search the product plan
     *
     * @param  int    $searchField
     * @param  int    $value
     * @access public
     * @return mixed
     */
    public function searchProductPlan($searchField, $value)
    {
        $e = $this->dom->productPlan->browse;

        $this->driver->wait(1)->click($e->mainMenu->searchBtn);

        $this->driver->wait(1)->picker($e->mainContent->queryBox->searchChosen, $searchField);
        $this->driver->wait(1)->setValue($e->mainContent->queryBox->firstValue, $value);
        $this->driver->click($e->mainContent->queryBox->saveBtn);
    }
}
