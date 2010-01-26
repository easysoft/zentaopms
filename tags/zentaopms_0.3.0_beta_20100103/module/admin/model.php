<?php
/**
 * The model file of admin module of ZenTaoMS.
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
 * @package     admin
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php
class adminModel extends model
{
    /* 获得整个pms系统的统计信息。*/
    public function getStatOfPMS()
    {
        $sql = "SHOW TABLE STATUS";
        $tables = $this->dbh->query($sql)->fetchALL();
    }

    /* 获得某一个公司的统计信息。*/
    public function getStatOfCompany($companyID)
    {
    }

    /* 获得系统的运行信息。*/
    public function getStatOfSys()
    {
    }
}
