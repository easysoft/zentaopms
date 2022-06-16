<?php
/**
 * The mr entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      liyuchun <liyuchun@easysoft.ltd>
 * @package     repo
 * @version     1
 * @link        http://www.zentao.net
 */
class mrEntry extends baseEntry
{
    /**
     * Create mr.
     *
     * @access public
     * @return void
     */
    public function post()
    {
        $fields = 'repoID,jobID,sourceBranch,targetBranch,diffs,mergeStatus';
        $this->batchSetPost($fields);
        $postData = fixer::input('post')->get();

        $this->loadController('mr', 'create');
        $MRID = $this->loadModel('mr')->apiCreate();
        if(dao::isError()) $this->sendError(400, dao::getError());

        $MR = $this->mr->getByID($MRID);
        $this->send(201, $MR);
    }
}
