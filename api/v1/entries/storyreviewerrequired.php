<?php
/**
 * The story reviewer required entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ruogu Liu <liuruogu@chandao.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class storyreviewerrequiredEntry extends entry
{
    /**
     * GET method.
     *
     * @param  string    $storyType
     * @access public
     * @return string
     */
    public function get($storyType = '')
    {
        if(empty($storyType)) $storyType = $this->param('type', 'story');
        if(!in_array($storyType, array('story', 'requirement', 'epic'))) return $this->sendError(400, 'Invalid story type. Must be one of: story, requirement, epic.');

        $this->loadModel('story');
        $reviewerRequired = $this->story->checkForceReview($storyType);

        return $this->send(200, array('storyType' => $storyType, 'reviewerRequired' => (bool)$reviewerRequired));
    }
}
