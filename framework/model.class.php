<?php
include dirname(__FILE__) . '/base/model.class.php';
class model extends baseModel
{
    /**
     * 删除记录
     * Delete one record.
     * 
     * @param  string    $table  the table name
     * @param  string    $id     the id value of the record to be deleted
     * @access public
     * @return void
     */
    public function delete($table, $id)
    {
        $this->dao->update($table)->set('deleted')->eq(1)->where('id')->eq($id)->exec();
        $object = ltrim(strstr(trim($table, '`'), '_'), '_');
        $this->loadModel('action')->create($object, $id, 'deleted', '', $extra = ACTIONMODEL::CAN_UNDELETED);
    }
}
