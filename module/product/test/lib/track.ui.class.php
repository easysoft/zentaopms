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
        $message = [
            'ER'        => '业务需求',
            'UR'        => '用户需求',
            'SR'        => '研发需求',
            'sub_SR'    => '子研发需求',
            'project'   => '所属项目',
            'execution' => '所属执行',
            'task'      => '相关任务',
            'bug'       => '相关Bug',
            'case'      => '相关用例'
        ];
        if (isset($message[$type]))
        {
            return ($title == $expected)
                ? $this->success($message[$type] . '显示正确')
                : $this->failed($message[$type] . '显示不正确');
        }
        return $this->failed('矩阵中无该类型的数据');
    }
}
