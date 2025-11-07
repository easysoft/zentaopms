<?php
declare(strict_types=1);
/**
 * The model file of workestimation module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件(青岛)有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     workestimation
 * @link        https://www.zentao.net
 */
class workestimationModel extends model
{
    /**
     * Get budget by projectID.
     *
     * @param  int    $projectID
     * @access public
     * @return object|null
     */
    public function getBudget(int $projectID): object|null
    {
        $budget = $this->dao->select('*')->from(TABLE_WORKESTIMATION)
            ->where('project')->eq($projectID)
            ->andWhere('deleted')->eq('0')
            ->fetch();
        return $budget ? $budget : null;
    }
}
