<?php
class myspacePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'createLibBtn'   => '/html/body/div[1]/div/div[1]/div[2]/a',
            'leftListHeader' => '/html/body/div[1]/div/div[2]/div[1]/header/span',
            'libName'        => '//*[@id="zin_doc_createlib_form"]/div[1]/div[1]/input',
            'createDocBtn'   => '/html/body/div/div/div[1]/div[2]/div',
            'saveDraftBtn'   => '/html/body/div[1]/div/div/div[2]/form/div[1]/div[1]/div/div/button',
            'alertTitle'     => '//*[@class="modal modal-async load-indicator modal-alert modal-trans show in"]/div[1]/div[1]/div[2]/div[1]',
            'listHeader'     => '/html/body/div[1]/div/div[2]/div[1]/header/span',
            'saveBtn'        => '/html/body/div[1]/div/div/div[2]/form/div[1]/div[1]/div/div/a',
            'releaseBtn'     => '/html/body/div[1]/div/div/div[2]/form/div[2]/div/div/div[3]/div[10]/div/button',
            'fstEditBtn'     => '/html/body/div[1]/div/div[2]/div[2]/div/div/div[2]/div[3]/div/div[1]/div/nav/a[1]',
            'fstMoreBtn'     => '//*[@class="tree-item item is-nested is-nested-show"]/menu[1]/li[2]/div[1]/nav[1]/button[1]',
            'fstDocLib'      => '/html/body/div[1]/div/div[2]/div[1]/div[1]/div/main/menu/li[1]/menu/li[2]/div/div/a',
            'editLib'        => '//*[@class="popover show fade dropdown in"]/menu[1]/menu[1]/li[3]/a[1]',
            'fstDocLib'      => '/html/body/div[1]/div/div[2]/div[1]/div[1]/div/main/menu/li[1]/menu/li[2]/div/div/a',
            'deleteAccept'   => '//*[@class="popover show fade dropdown in"]/menu[1]/menu[1]/li[4]/a[1]',
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
