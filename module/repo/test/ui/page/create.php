<?php
class createPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'aclPrivate' => '//*[@id="acl[acl]private"]',
            'aclOpen'    => '//*[@id="acl[acl]open"]',
            'aclCustom'  => '//*[@id="acl[acl]custom"]'
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
