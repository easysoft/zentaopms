<?php
declare(strict_types=1);
/**
 * The tao file of design module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     design
 * @link        https://www.zentao.net
 */
class designTao extends designModel
{
    /**
     * 更新设计关联的代码提交记录。
     * Update the commit logs linked with the design.
     *
     * @param  int       $designID
     * @param  int       $repoID
     * @param  array     $revisions
     * @access protected
     * @return bool
     */
    protected function updateLinkedCommits(int $designID, int $repoID, array $revisions = array()): bool
    {
        if(!$designID || !$repoID || empty($revisions)) return true;

        $design = $this->dao->select('project,product')->from(TABLE_DESIGN)->where('id')->eq($designID)->fetch();
        if(!$design) return true;

        foreach($revisions as $revision)
        {
            $data = new stdclass();
            $data->project  = $design->project;
            $data->product  = $design->product;
            $data->AType    = 'design';
            $data->AID      = $designID;
            $data->BType    = 'commit';
            $data->BID      = $revision;
            $data->relation = 'completedin';
            $data->extra    = $repoID;
            $this->dao->replace(TABLE_RELATION)->data($data)->autoCheck()->exec();

            $data->AType    = 'commit';
            $data->AID      = $revision;
            $data->BType    = 'design';
            $data->BID      = $designID;
            $data->relation = 'completedfrom';
            $this->dao->replace(TABLE_RELATION)->data($data)->autoCheck()->exec();
        }

        return !dao::isError();
    }
}
