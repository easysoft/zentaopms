<?php
require_once dirname(__FILE__, 6) . '/test/lib/ui.php';

class exportTester extends tester
{
    public $form;

    public function __construct($productID = 1, $storyType = 'story')
    {
        parent::__construct();
        $this->login();
        $params = (object)[
            'productID' => $productID,
            'branch'    => '',
            'browseType'=> 'unclosed',
            'param'     => 0,
            'storyType' => $storyType,
            'sort'      => 'id',
        ];
        $form = $this->initForm('product', 'browse', $params, 'appIframe-product');
        $form->wait(1);
        $this->form = $form;
    }

    /**
     * 导出全部记录测试。
     * @access public
     * @return object
     */
    public function testExportAll()
    {
        $this->form->dom->exportBtn->click();
        $this->form->wait(1);

        $this->form->dom->fileType->picker($this->lang->exportFileTypeList->csv);
        $this->form->dom->encode->picker($this->lang->importEncodeList->gbk);
        $this->form->dom->exportType->picker($this->lang->exportTypeList->all);

        $this->form->dom->btn($this->lang->export)->click();
        if($this->response('method') == 'browse') return $this->success('全部记录导出流程测试成功');
        return $this->failed('全部记录导出流程测试失败');
    }

    /**
     * 导出选中记录测试。
     * @access public
     * @return object
     */
    public function testExportSelected()
    {
        $this->form->dom->firstStory->click();
        $this->form->wait(1);
        $this->form->dom->exportBtn->click();
        $this->form->wait(1);

        $this->form->dom->fileType->picker($this->lang->exportFileTypeList->html);
        $this->form->dom->exportType->picker($this->lang->exportTypeList->selected);

        $this->form->dom->btn($this->lang->export)->click();
        if($this->response('method') == 'browse') return $this->success('选中记录导出流程测试成功');
        return $this->failed('选中记录导出流程测试失败');
    }
}
