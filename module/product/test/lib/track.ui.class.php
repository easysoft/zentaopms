<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class trackTester extends tester
{
    /**
     * 检查产品矩阵
     * check data of product track
     *
     * @param $trackurl 产品ID
     * @param $type     数据类型 ER|UR|SR|sub_SR|project|execution|task|bug|case
     * @param $expected 预期对象名称
     * @return mixed
     */
    public function checkTrackData($trackurl, $type, $expected)
    {
        $form = $this->initForm('product', 'track', $trackurl, 'appIframe-product');
        $form->wait(2);
        $title = $form->dom->$type->getText();
