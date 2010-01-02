<?php
/**
 * The model file of release module of ZenTaoMS.
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
 * @package     release
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php
class releaseModel extends model
{
    public function __construct()
    {
        parent::__construct();
    }

    function create($release = array())
    {
        if(!is_array($release) or empty($release)) die(js::alert($this->lang->release->errorErrorFormat));
        extract($release);

        if(empty($name))    $errorMSG[] = $this->lang->release->errorEmptyName;
        if(empty($product)) $errorMSG[] = $this->lang->release->errorEmptyProduct;
        if(!empty($errorMSG)) die(js::alert(join($errorMSG, '\n')));

        $sql = "INSERT INTO " . TABLE_RELEASE . " (`name`, `product`, `desc`, `planDate`) VALUES('$name', '$product', '$desc', '$planDate')";
        return $this->dbh->query($sql);
    }

    function read($id)
    {
    }

    function update($id)
    {
    }
    
    function delete($id)
    {
    }

    function getList($product = 0)
    {
        $product = (int)$product;
        $where = $product > 0 ? " WHERE `product` = '$product'" : '';
        $sql = "SELECT * FROM " . TABLE_RELEASE .  $where;
        $stmt = $this->dbh->query($sql);
        return $stmt->fetchAll();
    }
}
