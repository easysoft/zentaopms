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
    public function createProduct($formData)
    {
        loadModel('user')->login();

        global $lang, $result;
        $productPage = new productPage();
        $result->setPage($productPage);

        $productPage->go('product', 'mainNav');
        $productPage->btn($lang->product->addBtn)->click();
        $productPage->wait(1)->getErrors("appIframe-product");

        foreach($formData as $formName => $value)
        {
            if(isset($value['wait']))        $productPage->wait($value['wait']);
            if(isset($value['picker']))      $productPage->$formName->picker($value['picker']);
            if(isset($value['multiPicker'])) $productPage->$formName->multiPicker($value['multiPicker']);
            if(isset($value['datePicker']))  $productPage->$formName->datePicker($value['datePicker']);
            if(isset($value['setValue']))    $productPage->$formName->setValue($value['setValue']);
            if(isset($value['click']))       $productPage->$formName->click();
        }

        $productPage->btn($lang->product->saveBtn)->click();
        $productPage->wait(1);

        return $productPage;
    }
}
