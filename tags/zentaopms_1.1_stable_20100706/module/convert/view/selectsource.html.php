<?php
/**
 * The html template file of select source method of convert module of ZenTaoMS.
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
 * @package     convert
 * @version     $Id$
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='yui-d0'>
  <form method='post' action='<?php echo inlink('setConfig');?>'>
  <table align='center' class='table-5 f-14px'>
    <caption><?php echo $lang->convert->selectSource;?></caption>
    <tr>
      <th class='w-p20'><?php echo $lang->convert->source;?></th>
      <th><?php echo $lang->convert->version;?></th>
    </tr>
    <?php foreach($lang->convert->sourceList as $name => $versions):?>
    <tr>
      <th class='rowhead'><?php echo $name;?></th>
      <td><?php echo html::radio('source', $versions);?></td>
    </tr>
    <?php endforeach;?>
    <tr>
      <td colspan='2' class='a-center'><?php echo html::submitButton();?></td>
    </tr>
  </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
