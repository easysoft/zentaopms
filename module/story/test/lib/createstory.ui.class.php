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
class createStoryTester extends tester
{
    /**
     * Create a default story.
     *
     * @param   string $storyName
     * @access  public
     * @return  object
     */
    public function createDefault($storyName)
    {
        /* 提交表单 */
        $createStoryParam = array(
            'product'  => '4',
            'branch'   => 'all',
            'moduleID' => '0',
            'storyID'  => '0',
            'project'  => '0',
        );
        $form = $this->initForm('story','create', $createStoryParam, 'appIframe-product');
        $form->dom->title->setValue($storyName);
}
}
