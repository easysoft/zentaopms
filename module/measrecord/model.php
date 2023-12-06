<?php
/**
 * The model file of measrecord module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     measrecord
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class measrecordModel extends model
{
    /**
     * Get list by program
     *
     * @param  int    $program
     * @param  int    $measurementID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getListByProgram($program, $measurementID, $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_MEASRECORDS)
            ->where('project')->eq($program)
            ->andWhere('mid')->eq($measurementID)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    public function getSavedMeas($program)
    {
        return $this->dao->select('DISTINCT r.mid, m.name, m.purpose')->from(TABLE_BASICMEAS)->alias('m')
            ->leftJoin(TABLE_MEASRECORDS)->alias('r')
            ->on('m.id=r.mid')
            ->where('project')->eq($program)
            ->orderBy('m.purpose, m.order desc')
            ->fetchGroup('purpose');
    }

    /**
     * Save meas record.
     *
     * @param  object    $measurement
     * @param  array     $params
     * @param  mix       $queryResult
     * @access public
     * @return bool
     */
    public function save($measurement, $params, $value, $type = '')
    {
        $record = new stdclass();
        $record->type     = $type;
        $record->mid      = $measurement->id;
        $record->measCode = $measurement->code;
        $record->date     = helper::today();
        $record->year     = date('Y');
        $record->month    = date('Ym');
        $record->week     = date('W');
        $record->day      = date('Ymd');
        $record->params   = json_encode($params);
        $record->value    = $value;

        if(isset($params['$program'])) $record->program = $params['$program'];
        if(isset($params['$product'])) $record->product = $params['$product'];
        if(isset($params['$project'])) $record->project = $params['$project'];

        $this->dao->insert(TABLE_MEASRECORDS)->data($record)->exec();
        return !dao::isError();
    }
}
