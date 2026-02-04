<?php
declare(strict_types=1);
/**
 * The model file of artifact module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     artifact
 * @link        https://www.zentao.net
 */
class artifactModel extends model
{
    /**
     * 创建制品库。
     * create artifact repo.
     *
     * @param  object $data
     * @param  string $type
     * @access public
     * @return int|false
     */
    public function create(object $data, string $type): int|false
    {
        $check = '';
        if($type == 'space')  $check = "spaceID = {$data->spaceID}";
        if($type == 'repo')   $check = "repoID = {$data->repoID}";
        if($type == 'system') $check = "spaceID = 0 and repoID = 0";

        $this->dao->insert(TABLE_ARTIFACT)->data($data)
            ->batchCheck($this->config->artifact->create->requiredFields, 'notempty')
            ->check('name', 'unique', $check)
            ->autoCheck()
            ->exec();
        if(dao::isError()) return false;

        $id = $this->dao->lastInsertID();
        return $id;
    }
}
