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
include dirname(__FILE__, 5).'/test/lib/ui.php';
class editStoryTester extends tester
{
    /**
     * Edit a story.
     *
     * @param   string $storyFrom
     * @access  public
     * @return  object
     */
    public function editStory($storyFrom)
    {
        $editStoryParam = array(
            'storyID'     => '4',
            'kanbanGroup' => 'default',
            'storyType'   => 'story',
        );
        /* 提交表单 */
        $form = $this->initForm('story', 'edit', $editStoryParam, 'appIframe-product');
        $form->dom->source->picker($storyFrom);
        $form->dom->assignedTo->picker('admin');
        $from->dom-btn($this->lang->save)->click();
        $form->wait(1);

        /* 跳转到需求列表页面搜索创建需求并进入该需求详情页。 */
        $browsePage = $this->loadPage('product', 'browse');
}
}
