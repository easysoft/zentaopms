<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
/**
 *@copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 *@license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 *@author      lijie
 *@package     story
 *@link        http://www.zentao.net
 */
class linkStoryTester extends tester
{
    /**
     * Check the linkstories after link story.
     *
     * @param string $storyID
     * @access public
     * @return object
     */
    public function linkStory($storyID)
    {
