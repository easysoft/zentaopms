<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class browseTester extends tester
{
    /**
     * 检查产品各需求列表下的数据
     * check data of SR|UR|ER list
     * @param $url       url参数
     * @param $type      类型 SR|UR|ER
     * @param $tab       tab名
     * @param $expectNum 预期数量
     * @return mixed
     */
    public function checkData($url, $type, $tab, $expectNum)
    {
        $form = $this->initForm('product', 'browse', $url, 'appIframe-product');
        $form->wait(2);
        $numDom = $tab . 'Num';
        $typeMessage = [
            'SR' => '研发需求',
            'UR' => '用户需求',
            'ER' => '业务需求'
        ];
        $tabMessage = [
            'all'          => '全部',
            'open'         => '未关闭',
            'assignedToMe' => '指给我',
            'createdByMe'  => '我创建',
            'reviewByMe'   => '待我评审',
            'draft'        => '草稿',
            'reviewedByMe' => '我评审',
            'assignedByMe' => '我指派',
            'closedByMe'   => '我关闭',
            'activated'    => '激活',
            'changing'     => '变更中',
            'reviewing'    => '评审中',
            'toBeClosed'   => '待关闭',
            'closed'       => '已关闭'
        ];
        if (!$form->dom->$tab)
        {
            $form->dom->more->click();
            $form->wait(1);
        }
        $form->dom->$tab->click();
        $form->wait(2);
        $num = $form->dom->$tab ? $form->dom->$numDom->getText() : $form->dom->moreNum->getText();
        if (isset($typeMessage[$type]) && isset($tabMessage[$tab]))
        {
            return ($num == $expectNum)
                ? $this->success("{$typeMessage[$type]}的{$tabMessage[$tab]}tab下数据正确")
                : $this->failed("{$typeMessage[$type]}的{$tabMessage[$tab]}tab下数据不正确");
        }
    }
}
