<?php
declare(strict_types=1);
/**
 * The control file of example module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      lijie
 * @package     story
 * @link        http://www.zentao.net
*/
include dirname(__FILE__, 6).'/test/lib/ui.php';
class viewTester extends tester
{
   /**
    * Check the view page.
    * @param string $type
    * @access public
    * @return void
    */
    public function view($storyType)
    {
        $browseStoryParam = array(
            'productID'  => '1',
            'branch'     => '',
            'browseType' => 'unclosed',
            'param'      => '0',
            'storyType'  => $storyType
        );
        $form      = $this->initForm('product', 'browse', $browseStoryParam, 'appIframe-product');
        $storyName = $form->dom->browseStoryName->getText();
        $form->dom->browseStoryName->click();

        $viewPage = $this->loadPage('story', 'view');
        if($this->response('method') != 'view' && $viewPage->dom->storyName->getText() != $storyName) return $this->failed('需求详情页不正确');

        return $this->success('需求详情页正确');
    }
}
