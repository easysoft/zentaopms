<?php
declare(strict_types=1);
/**
 * The control file of example module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      lijie
 * @package     epic
 * @link        http://www.zentao.net
 */
include dirname(__FILE__, 6).'/test/lib/ui.php';
class createChildStoryTester extends tester
{
    public function checkDisplay($storyName)
    {
        $browseStoryParam = array(
            'productID'  => '1',
            'branch'     => '',
            'browseType' => 'allstory',
            'param'      => '0',
            'storyType'  => 'epic'
        );
        $form = $this->initForm('product', 'browse', $browseStoryParam, 'appIframe-product');
        $form->dom->search(array("需求名称,包含,$storyName"));
        if($form->dom->subdivide->attr('title') != '评审中和已关闭的需求，无法进行拆分操作') return $this->failed('细分按钮高亮不正确');
        return $this->success('细分按钮高亮正确');
    }
    /**
     * Batchcreate requirement.
     *
     * @param  string $type
     * @param  string $storyName
     * @access public
     * @return object
     */
    public function batchCreateDefault($storyName, $childName)
    {
        $browseStoryParam = array(
            'productID'  => '1',
            'branch'     => '',
            'browseType' => 'unclosed',
            'param'      => '0',
            'storyType'  => 'epic'
        );

        $form = $this->initForm('product', 'browse', $browseStoryParam, 'appIframe-product');
        $form->dom->search(array("需求名称,包含,$storyName"));
        $form->wait(1);

        $form->dom->decompose->click();
        $form->wait(3);

        $form = $this->loadPage('requirement', 'batchCreate');
        $form->dom->name->setValue($childName);
        $form->dom->reviewer->multiPicker(array('admin'));
        $form->dom->requirementSave->click();
        $form->wait(3);

        if($this->response('method') != 'view')
        {
            if($form->dom->alertModal('text') == '已有相同标题的需求或标题为空，请检查输入。') return $this->success('批量创建需求页面名称为空提示正确');
            return $this->failed('批量创建需求表单页面提示信息不正确');
        }

        /* 跳转到父需求详情页。 */

        $viewPage = $this->initForm('epic', 'view', array('storyID' => '3'), 'appIframe-product');
        $viewPage->wait(2);
        if($viewPage->dom->getElement('//*[@id="table-story-children"]/div[2]/div[1]/div/div[2]/div/a')->getText() != $childName) return $this->failed('子需求名称不正确');
        if($viewPage->dom->getElement('//*[@id="table-story-children"]/div[2]/div[2]/div/div[3]/div/span')->getText() != '评审中') return $this->failed('子需求状态不正确');

        $viewPage->dom->getElement('//*[@id="table-story-children"]/div[2]/div[1]/div/div[2]/div/a')->click();
        $viewPage->wait(2);
        $viewPage = $this->loadPage('requirement', 'view');
        $viewPage->wait(1);
        if($viewPage->dom->parentStoryName->getText() != '激活业务需求') return $this->failed('父需求不正确');

        return $this->success('拆分业务需求成功');
    }
}
