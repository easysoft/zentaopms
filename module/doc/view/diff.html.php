<?php
/**
 * The view of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     doc
 * @version     $Id: view.html.php 975 2010-07-29 03:30:25Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix' title='DOC'><?php echo html::icon($lang->icons['doc']);?> <strong><?php echo $docID;?></strong></span>
    <strong><?php echo $newDoc->title;?></strong>
    <span><?php echo " #" . $oldVersion . ' : ' . $newVersion?></span>
    <?php if($newDoc->deleted):?>
    <span class='label label-danger'><?php echo $lang->doc->deleted;?></span>
    <?php endif; ?>
  </div>
  <div class='actions'><?php echo html::backButton(); ?></div>
</div>
<div class='row-table'>
  <table class='table table-data alldiff'>
    <?php if($oldDoc->title != $newDoc->title):?>
    <tr>
      <th><?php $lang->doc->title?></th>
      <td><?php echo $oldDoc->title?></td>
      <td><?php echo $newDoc->title?></td>
    </tr>
    <?php endif;?>
    <?php foreach($diff as $field => $diffLines):?>
    <?php if(empty($diffLines)) continue;?>
    <tr>
      <th><?php echo $lang->doc->$field?></th>
      <td colspan='2'>
        <table class='diff table table-data table-hover table-condensed'>
          <?php
          $oldLines = explode("\n", htmlspecialchars(trim($oldDoc->$field)));
          $newLines = explode("\n", htmlspecialchars(trim($newDoc->$field)));
          $lines = max(count($oldLines), count($newLines));
          $oldLineNO = 0;
          $newLineNO = 0;
          ?>
          <?php for($i = 0; $i < $lines; $i++):?>
          <tr>
            <?php
            list($showNumber, $action) = $this->doc->getLineNumber($diffLines['old'], $i, $oldLineNO);
            $showLine = '';
            if($showNumber)
            {
                $showLine = $oldLines[$oldLineNO];
                $oldLineNO ++;
            }
            elseif(isset($oldLines[$oldLineNO]) and empty($oldLines[$oldLineNO]))
            {
                $showNumber = $oldLineNO + 1;
                $oldLineNO ++;
                if(isset($diffLines['old'][$i])) $action = 'diff';
            }
            if($action) $showLine = "<i class='icon icon-minus'></i> " . $showLine;
            ?>
            <td class='oldNO num'><?php echo $showNumber?></td>
            <td class='oldLine <?php echo $action?>line'><?php echo $showLine?></td>
            <?php
            list($showNumber, $action) = $this->doc->getLineNumber($diffLines['new'], $i, $newLineNO);
            $showLine = '';
            if($showNumber)
            {
                $showLine = $newLines[$newLineNO];
                $newLineNO ++;
            }
            elseif(isset($newLines[$newLineNO]) and empty($newLines[$newLineNO]))
            {
                $showNumber = $newLineNO + 1;
                $newLineNO ++;
                if(isset($diffLines['new'][$i])) $action = 'diff';
            }
            if($action) $showLine = "<i class='icon icon-plus'></i> " . $showLine;
            ?>
            <td class='newNO num'><?php echo $showNumber?></td>
            <td class='newLine <?php echo $action?>line'><?php echo $showLine?></td>
          </tr>
          <?php endfor;?>
        </table>
      </td>
    </tr>
    <?php endforeach;?>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
