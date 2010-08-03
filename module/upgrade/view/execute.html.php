<?php
/**
 * The html template file of execute method of upgrade module of ZenTaoMS.
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
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id$
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<style>body{background:white}</style>
<div class='yui-d0' style='margin-top:50px'>
  <table align='center' class='table-5 f-14px'>
    <caption><?php echo $lang->upgrade->$result;?></caption>
    <tr>
      <td>
      <?php
      if($result == 'fail')
      {
          echo nl2br(join('\n', $errors));
      }
      else
      {
          echo html::linkButton($lang->upgrade->tohome, 'index.php');
      }
      ?>
      </td>
    </tr>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
