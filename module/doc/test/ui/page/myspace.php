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
            'fstDocLib'      => '/html/body/div[1]/div/div[2]/div[2]/div[2]/div/div[2]/div[1]/div/div[2]/div/div/div/a[1]',
            'createDocBtn'   => '/html/body/div/div/div[1]/div[2]/div',
            'fstDocName'     => '/html/body/div/div/div[2]/div[2]/div/div/div[2]/div[1]/div/div[2]/div/div/div/a[1]',
            'saveDraftBtn'   => '/html/body/div[1]/div/div/div[2]/form/div[1]/div[1]/div/div/button',
            'alertTitle'     => '//*[@class="modal modal-async load-indicator modal-alert modal-trans show in"]/div[1]/div[1]/div[2]/div[1]',
            'saveBtn'        => '/html/body/div[1]/div/div/div[2]/form/div[1]/div[1]/div/div/a',
            'releaseBtn'     => '/html/body/div[1]/div/div/div[2]/form/div[2]/div/div/div[3]/div[10]/div/button',
            'fstEditBtn'     => '/html/body/div[1]/div/div[2]/div[2]/div/div/div[2]/div[3]/div/div[1]/div/nav/a[1]',
            'fstMoreBtn'     => '//*[@class="tree-item item is-nested is-nested-show"]/menu[1]/li[2]/div[1]/nav[1]/button[1]',
            'fstDocLib'      => '/html/body/div[1]/div/div[2]/div[1]/div[1]/div/main/menu/li[1]/menu/li[2]/div/div/a',
            'editLib'        => '//*[@class="popover show fade dropdown in"]/menu[1]/menu[1]/li[3]/a[1]',
            'fstDocLib'      => '/html/body/div[1]/div/div[2]/div[1]/div[1]/div/main/menu/li[1]/menu/li[2]/div/div/a',
            'deleteAccept'   => '/html/body/div[3]/div/div/div[3]/nav/button[1]',
            'fstMoveBtn'     => '/html/body/div[1]/div/div[2]/div[2]/div/div/div[2]/div[3]/div/div/div/nav/a[2]/i',
            'fstDeleteBtn'   => '/html/body/div[1]/div/div[2]/div[2]/div/div/div[2]/div[3]/div/div/div/nav/a[3]/i',
            'formText'       => '/html/body/div/div/div[2]/div[2]/div/div/div/div/div',
            'saveMoveBtn'    => '//*[@class="m-doc-myspace"]/div[2]/div[1]/div[1]/div[3]/div[1]/div[1]/form[1]/div[6]/div[1]/button[1]',
            'checkFstDoc'    => '/html/body/div[1]/div/div[2]/div[2]/div/div/div[2]/div[1]/div/div[2]/div/a',
            'fstSaveBtn'     => '/html/body/div[1]/div/div[2]/div[2]/div/div/div[2]/div[1]/div/div[2]/div/div[2]/a/img',
            'myFavorites'    => '/html/body/div[1]/div/div[2]/div[1]/div[1]/div/main/menu/li[3]/div/div/a',
            'createBy'       => '/html/body/div[1]/div/div[2]/div[1]/div[1]/div/main/menu/li[4]/div/div/a',
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
