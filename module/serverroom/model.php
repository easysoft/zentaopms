<?php
/**
 * The model file of ops module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jiangxiu Peng <pengjiangxiu@cnezsoft.com>
 * @package     ops
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class serverroomModel extends model
{
    /**
     * Get by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getById($id)
    {
        return $this->dao->select('*')->from(TABLE_SERVERROOM)->where('id')->eq($id)->fetch();
    }

    /**
     * Get room list.
     *
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($browseType = 'all', $param = 0, $orderBy = 'id_desc', $pager = null)
    {
        $query = '';
        if($browseType == 'bysearch')
        {
            if($param)
            {
                $query = $this->loadModel('search')->getZinQuery($param);
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

        $serverRooms = $this->dao->select('*')->from(TABLE_SERVERROOM)
            ->where('deleted')->eq('0')
            ->beginIF($query)->andWhere($query)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
        return $serverRooms;
    }

    /**
     * Get server room pairs
     *
     * @access public
     * @return array
     */
    public function getPairs(): array
    {
        $stmt = $this->dao->select('*')->from(TABLE_SERVERROOM)
            ->where('deleted')->eq('0')
            ->orderBy('id_desc')
            ->query();

        $rooms[0] = '';
        while($room = $stmt->fetch())
        {
            $name  = zget($this->lang->serverroom->cityList, $room->city) == '' ? '' : zget($this->lang->serverroom->cityList, $room->city) . ' - ';
            $name .= zget($this->lang->serverroom->providerList, $room->provider) == '' ? '' : zget($this->lang->serverroom->providerList, $room->provider) . ' - ';
            $name .= $room->name;
            $rooms[$room->id] = $name;
        }
        return $rooms;
    }

    /**
     * Create.
     *
     * @access public
     * @return int
     */
    public function create()
    {
        $now  = helper::now();
        $room = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', $now)
            ->get();

        $this->dao->insert(TABLE_SERVERROOM)->data($room)->autoCheck()
            ->batchCheck($this->config->serverroom->create->requiredFields, 'notempty')
            ->exec();

        if(!dao::isError())
        {
            $roomID = $this->dao->lastInsertID();
            return $roomID;
        }
        return false;
    }

    /**
     * Update
     *
     * @param  int    $id
     * @access public
     * @return array
     */
    public function update($id)
    {
        $oldRoom = $this->getById($id);
        $now     = helper::now();
        $room    = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', $now)
            ->get();

        $this->dao->update(TABLE_SERVERROOM)->data($room)->autoCheck()
            ->batchCheck($this->config->serverroom->edit->requiredFields, 'notempty')
            ->where('id')->eq($id)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldRoom, $room);
        return false;
    }
}
