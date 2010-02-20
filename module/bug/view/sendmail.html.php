<?php
/**
 * The mail file of bug module of ZenTaoMS.
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
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<table width='98%' align='center'>
  <tr class='header'>
    <td>
      BUG #<?php echo $bug->id . "=>$bug->assignedTo " . html::a(common::getSysURL() . $this->createLink('bug', 'view', "bugID=$bug->id"), $bug->title);?>
    </td>
  </tr>
  <tr>
    <td><?php include '../../common/mail.html.php';?></td>
  </tr>
</table>
