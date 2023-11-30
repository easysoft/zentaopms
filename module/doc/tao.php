<?php
declare(strict_types=1);
/**
 * The tao file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
class docTao extends docModel
{
    /**
     * 获取编辑过的文档ID列表。
     * Get the list of doc id list that have been edited.
     *
     * @access protected
     * @return array
     */
    protected function getEditedDocIdList(): array
    {
        return $this->dao->select('objectID')->from(TABLE_ACTION)
            ->where('objectType')->eq('doc')
            ->andWhere('action')->in('edited')
            ->andWhere('actor')->eq($this->app->user->account)
            ->andWhere('vision')->eq($this->config->vision)
            ->fetchPairs();
    }
}
