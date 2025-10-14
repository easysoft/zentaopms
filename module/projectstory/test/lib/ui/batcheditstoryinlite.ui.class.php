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
class batchEditStoryInLiteTester extends tester
{
    /**
     * Batch edit a story.
     * @param   string $storyID, $storyType, $storyFrom
     * @access  public
     * @return  object
     */
    public function batchEditStoryInLite()
    {
        /* 提交表单 */
        $this->switchVision('lite', 3);
        $form = $this->initForm('projectstory', 'story', array('projectID' => 1), 'appIframe-project');
        $form->dom->firstCheckbox->click();
        $form->dom->batchEdit->click();
        sleep(1);
        $form = $this->loadPage('story', 'batchEdit');
        $form->dom->batchEstimate->setValue(3);
        $form->dom->batchEditSave->click();
        $form->wait(1);

        $viewPage = $this->initForm('projectstory', 'view', array('storyID' => 1, 'projectID' => 1), 'appIframe-project');
        if($viewPage->dom->storyEstimate->getText() != '3h') return $this->failed('目标工时不正确');

        return $this->success('批量编辑目标成功');
    }
}
