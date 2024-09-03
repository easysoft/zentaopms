<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            /* 执行概况页元素 */
            'executionName'   => "//*[@id='mainContent']/div[1]/div[1]/div[2]/div[1]/div",
            'status'          => "//*[@id='mainContent']/div[1]/div[1]/div[2]/div[1]/span[2]",
            'acl'             => "//*[@id='mainContent']/div[1]/div[1]/div[2]/div[1]/span[3]",
            'plannedBegin'    => "//*[@id='mainContent']/div[2]/div[1]/div/table[3]/tbody/tr/td/div/div[1]/span[2]",
            'plannedEnd'      => "//*[@id='mainContent']/div[2]/div[1]/div/table[3]/tbody/tr/td/div/div[2]/span[2]",
            'realBeganView'   => "//*[@id='mainContent']/div[2]/div[1]/div/table[3]/tbody/tr/td/div/div[3]/span[2]",
            'realEndView'     => "//*[@id='mainContent']/div[2]/div[1]/div/table[3]/tbody/tr/td/div/div[4]/span[2]",
            'edit'            => "//*[@id='mainContent']/div[3]/div/a[last()-1]",
            'linckedProducta' => "//*[@id='mainContent']/div[2]/div[1]/div/table[1]/tbody/tr[1]",
            'linckedProductb' => "//*[@id='mainContent']/div[2]/div[1]/div/table[1]/tbody/tr[2]",
            /* 编辑执行弹窗中元素 */
            'products'    => "//*[@name='products[0]']",
            'productsTip' => "//*[@id='products[0]Tip']",
            'submit'      => "//*[@type='submit']/span",
            /* 开始执行弹窗中的元素 */
            'realBeganField' => "//div[@data-name='realBegan']/label/span",
            'startSubmit'    => "//*[@name='realBegan']/../../../../div[3]/div/button",
            /* 关闭执行弹窗中的元素 */
            'realEndField'   => "//div[@data-name='realEnd']/label/span",
            'closeSubmit'    => "//*[@name='realEnd']/../../../../div[3]/div/button",
            /* 挂起执行弹窗中的元素 */
            'suspendSubmit' => "//*[@class='modal-actions']/../div[3]/div/div/form/div[2]/div/button",
            /* 延期执行弹窗中的元素 */
            'putoffSubmit'  => "//*[@class='modal-actions']/../div[3]/div/div/form/div[4]/div/button",
            /* 激活执行弹窗中的元素 */
            'activateSubmit' => "//*[@class='modal-actions']/../div[3]/div/div/form/div[3]/div/button",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
