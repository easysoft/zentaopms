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
class editStoryInLiteTester extends tester
{
    /**
     * Edit a story.
     * @param   string $storyID, $storyType, $storyFrom
     * @access  public
     * @return  object
     */
    public function editStoryInLite()
    {
        /* 提交表单 */
        $this->switchVision('lite', 3);
        $form = $this->initForm('projectstory', 'story', array('projectID' => 1), 'appIframe-project');
        $form->wait(3);
        $form = $this->initForm('story', 'edit', array('storyID' => 1, 'kanbangroup' => 'default', 'storyType' => 'story'), 'appIframe-project');
        $form->dom->estimate->setValue(3);
        $form->dom->assignedTo->picker('admin');
        $form->dom->btn($this->lang->save)->click();
        $form->wait(3);

        $viewPage = $this->loadPage('projectstory', 'view');
        if($viewPage->dom->storyEstimate->getText() != '3h') return $this->failed('目标工时不正确');

        return $this->success('编辑目标成功');
    }
}
