<?php
class product
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
        $productPage = new productPage();

        loadModel('user')->login();
        $productPage->go('product', 'mainNav');

        $this->createInFrom('product', $formData);
        $productPage->driver->wait(1);

        return $productPage;
    }
}
