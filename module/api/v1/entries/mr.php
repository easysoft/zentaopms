<?php
/**
 * The mr entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
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
     * @return string
     */
    public function post()
    {
        $fields = 'repoID,jobID,sourceBranch,targetBranch,diffs,mergeStatus';
        $this->batchSetPost($fields);

        $this->loadController('mr', 'create');
        $MRID = $this->loadModel('mr')->apiCreate();
        if(dao::isError()) $this->sendError(400, dao::getError());

        $MR = $this->mr->fetchByID($MRID);
        return $this->send(201, $MR);
    }
}
