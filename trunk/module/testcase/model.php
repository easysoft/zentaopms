<?php
/**
 * The model file of case module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     case
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php
class testcaseModel extends model
{
    /* 构造函数。*/
    public function __construct()
    {
        parent::__construct();
    }

    /* 创建一个Case。*/
    function create()
    {
        extract($_POST);
        $openedBy   = $this->app->user->account;
        $openedDate = time();
        $sql = "INSERT INTO " . TABLE_CASE . " (product, module, type, pri, title, steps, openedBy, openedDate) 
                VALUES('$productID', '$moduleID', '$type', '$pri', '$title', '$steps', '$openedBy', '$openedDate' )";
        $this->dbh->exec($sql);
    }

    /* 获得某一个产品，某一个模块下面的所有case。*/
    public function getModuleCases($productID, $moduleIds = 0)
    {
        $where  = " WHERE `product` = '$productID'";
        $where .= !empty($moduleIds) ? " AND module " . helper::dbin($moduleIds) : '';
        $sql    = "SELECT * FROM " . TABLE_CASE .  $where;
        $stmt   = $this->dbh->query($sql);
        return $stmt->fetchAll();
    }

    /* 获取一个case的详细信息。*/
    public function getById($caseID)
    {
        return $this->dbh->query("SELECT * FROM " . TABLE_CASE . " WHERE id = '$caseID'")->fetch();
    }

    /* 更新case信息。*/
    public function update($caseID)
    {
        extract($_POST);
        $pri = str_replace('item', '', $pri);
        $sql = "UPDATE " . TABLE_CASE . " SET 
            title = '$title', product='$productID', module = '$moduleID', 
            type='$type', pri = '$pri', status = '$status', steps = '$steps' 
            WHERE id ='$caseID' LIMIT 1 ";
        return $this->dbh->exec($sql);
    }
}
