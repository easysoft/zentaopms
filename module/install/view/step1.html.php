<?php
/**
 * The html template file of step1 method of install module of ZenTaoMS.
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
 * @package     ZenTaoMS
 * @version     $Id$
 */
?>
<?php include './header.html.php';?>
<div class='yui-d0'>
  <table align='center' class='table-6 a-center'>
    <caption><?php echo $lang->install->checking;?></caption>
    <tr>
      <th class='w-p20'><?php echo $lang->install->checkItem;?></th>
      <th class='w-p25'><?php echo $lang->install->current?></th>
      <th class='w-p15'><?php echo $lang->install->result?></th>
      <th><?php echo $lang->install->action?></th>
    </tr>
    <tr>
      <th><?php echo $lang->install->phpVersion;?></th>
      <td><?php echo $phpVersion;?></td>
      <td class='<?php echo $phpResult;?>'><?php echo $lang->install->$phpResult;?></td>
      <td class='a-left f-12px'><?php if($phpResult == 'fail') echo $lang->install->phpFail;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->install->pdo;?></th>
      <td><?php $pdoResult ? printf($lang->install->loaded) : printf($lang->install->unloaded);?></td>
      <td class='<?php echo $pdoResult;?>'><?php echo $lang->install->$pdoResult;?></td>
      <td class='a-left f-12px'><?php if($pdoResult == 'fail') echo $lang->install->pdoFail;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->install->pdoMySQL;?></th>
      <td><?php $pdoMySQLResult ? printf($lang->install->loaded) : printf($lang->install->unloaded);?></td>
      <td class='<?php echo $pdoMySQLResult;?>'><?php echo $lang->install->$pdoMySQLResult;?></td>
      <td class='a-left f-12px'><?php if($pdoMySQLResult == 'fail') echo $lang->install->pdoMySQLFail;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->install->tmpRoot;?></th>
      <td>
        <?php
        $tmpRootInfo['exists']   ? print($lang->install->exists)   : print($lang->install->notExists);
        $tmpRootInfo['writable'] ? print($lang->install->writable) : print($lang->install->notWritable);
        ?>
      </td>
      <td class='<?php echo $tmpRootResult;?>'><?php echo $lang->install->$tmpRootResult;?></td>
      <td class='a-left f-12px'>
        <?php 
        if(!$tmpRootInfo['exists'])   printf($lang->install->mkdir, $tmpRootInfo['path'], $tmpRootInfo['path']);
        if(!$tmpRootInfo['writable']) printf($lang->install->chmod, $tmpRootInfo['path'], $tmpRootInfo['path']);
        ?>
      </td>
    </tr>
    <tr>
      <th><?php echo $lang->install->dataRoot;?></th>
      <td>
        <?php
        $dataRootInfo['exists']   ? print($lang->install->exists)   : print($lang->install->notExists);
        $dataRootInfo['writable'] ? print($lang->install->writable) : print($lang->install->notWritable);
        ?>
      </td>
      <td class='<?php echo $dataRootResult;?>'><?php echo $lang->install->$dataRootResult;?></td>
      <td class='a-left f-12px'>
        <?php 
        if(!$dataRootInfo['exists'])   printf($lang->install->mkdir, $dataRootInfo['path'], $dataRootInfo['path']);
        if(!$dataRootInfo['writable']) printf($lang->install->chmod, $dataRootInfo['path'], $dataRootInfo['path']);
        ?>
      </td>
    </tr>
    <tr>
      <td colspan='4'>
      <?php
      if($phpResult == 'ok' and $pdoResult == 'ok' and $pdoMySQLResult == 'ok' and $tmpRootResult == 'ok' and $dataRootResult == 'ok')
      {
          echo html::a($this->createLink('install', 'step2'), $lang->install->next);
      }
      else
      {
          echo html::a($this->createLink('install', 'step1'), $lang->install->reload);
      }
      ?>
      </td>
    </tr>
  </table>
</div>
<?php include './footer.html.php';?>
