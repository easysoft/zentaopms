<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class linkStoryTester extends tester
{
    /**
     * 检查当执行未关联产品时关联需求的提示信息
     * Check info of linkstory when the execution linked no product.
     *
     * @access public
     * @return object
     */
    public function checkNoProductInfo()
    {
        $form = $this->initForm('execution', 'story', array('execution' => '3'), 'appIframe-execution);
    }
}
