<?php
/**
 * The manage child product view of project module of ZenTaoMS.
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
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<div id='doc3'>
  <form method='post'>
    <table align='center' class='table-5 a-left'> 
      <caption><?php echo $lang->project->manageChilds;?></caption>
      <tr>
        <td>
        <?php
        echo html::checkbox("childs", $projects, $childProjects);
        ?>
        </td>
      </tr>
      <tr><td class='a-center'><input type='submit' name='submit' /></td></tr>
    </table>
  </form>
</div>
<?php include '../../common/footer.html.php';?>
