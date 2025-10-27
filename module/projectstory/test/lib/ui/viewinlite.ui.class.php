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
class viewInLiteTester extends tester
{
   /*
    * Check the view page in lite vision.
    * @param string $type
    * @access public
    * @return void
    */
    public function viewInLite()
    {
        $this->switchVision('lite', 3);
        $form      = $this->initForm('projectstory', 'story', array('projectID' => '1'), 'appIframe-project');
        $storyName = $form->dom->firstStory->getText();
        $form->dom->firstStory->click();

        $viewPage = $this->loadPage('story', 'view');
        if($this->response('method') != 'view' && $viewPage->dom->storyName->getText() != $storyName) return $this->failed('目标详情页不正确');

        return $this->success('目标详情页内容正确');
    }
}
