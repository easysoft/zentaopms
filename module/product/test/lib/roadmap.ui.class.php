<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class roadmapTester extends tester
{
    public function getIterationCount($productID, $num)
    {
        $form = $this->initForm('product', 'roadmap', $productID, 'appIframe-product');
        $iterationInfo  = $form->dom->iterationInfo->getText();
        $iterationCount = filter_var($iterationInfo, FILTER_SANITIZE_NUMBER_INT);
        return ($iterationCount == $num) ? $this->success('迭代次数正确') : $this->failed('迭代次数不正确');
    }
}
