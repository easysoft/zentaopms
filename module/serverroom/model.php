<?php
declare(strict_types=1);
/**
 * The model file of serverroom module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jiangxiu Peng <pengjiangxiu@cnezsoft.com>
 * @package     serverroom
 * @link        https://www.zentao.net
 */
class serverroomModel extends model
{
    /**
     * 获取机房列表。
     * Get room list.
     *
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList(string $browseType = 'all', int $param = 0, string $orderBy = 'id_desc', object $pager = null): array
    {
        $query = '';
        if($browseType == 'bysearch')
        {
            if($param)
            {
                $query = $this->loadModel('search')->getQuery($param);
                if($query)
                {
                    $this->session->set('serverroomQuery', $query->sql);
                    $this->session->set('serverroomForm', $query->form);
                }
                else
                {
                    $this->session->set('serverroomQuery', ' 1 = 1');
                }
            }
            else
            {
                if($this->session->serverroomQuery == false) $this->session->set('serverroomQuery', ' 1 = 1');
            }

            $query = $this->session->serverroomQuery;
        }

        return $this->dao->select('*')->from(TABLE_SERVERROOM)
            ->where('deleted')->eq('0')
            ->beginIF($query)->andWhere($query)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 获取机房键值对信息。
     * Get server room pairs
     *
     * @access public
     * @return array
     */
    public function getPairs(): array
    {
        $rooms = $this->dao->select('id, city, provider, name')->from(TABLE_SERVERROOM)
            ->where('deleted')->eq('0')
            ->orderBy('id_desc')
            ->fetchAll('id');

        foreach($rooms as $roomID => $room)
        {
            $city     = zget($this->lang->serverroom->cityList, $room->city, '');
            $provider = zget($this->lang->serverroom->providerList, $room->provider, '');

            $name  = $city ? $city . ' - ' : '';
            $name .= $provider ? $provider . ' - ' : '';
            $name .= $room->name;
            $rooms[$roomID] = $name;
        }

        $rooms[0] = '';
        return $rooms;
    }

    /**
     * 创建机房信息。
     * Create serverroom.
     *
     * @param  object $room
     * @access public
     * @return int
     */
    public function create(object $room)
    {
        $this->dao->insert(TABLE_SERVERROOM)->data($room)->autoCheck()
            ->batchCheck($this->config->serverroom->create->requiredFields, 'notempty')
            ->exec();

        if(dao::isError()) return false;

        return $this->dao->lastInsertID();
    }

    /**
     * 更新机房信息。
     * Update serverroom.
     *
     * @param  int    $roomID
     * @access public
     * @return array
     */
    public function update(int $roomID, object $room): array|false
    {
        $oldRoom = $this->fetchByID($roomID);
        $this->dao->update(TABLE_SERVERROOM)->data($room)->autoCheck()
            ->batchCheck($this->config->serverroom->edit->requiredFields, 'notempty')
            ->where('id')->eq($roomID)
            ->exec();

        if(dao::isError()) return false;
        return common::createChanges($oldRoom, $room);
    }
}
