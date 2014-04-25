<?php
/**
 * The edit view file of webapp module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     webapp
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix' title='WEBAPP'><?php echo html::icon($lang->icons['app']);?> <strong><?php echo $webapp->id;?></strong></span>
    <strong><?php echo $webapp->name;?></strong>
    <small class='text-muted'> <i class='icon-pencil'></i> <?php echo $lang->webapp->edit?></small>
  </div>
</div>
<div class='main'>
  <form class='form-condensed' method='post' target='hiddenwin' enctype='multipart/form-data'>
    <table class='table table-form'>
      <tr>
        <th class='w-80px'><?php echo $lang->webapp->module?></th>
        <td class='w-p45'><?php echo html::select('module', $modules, $webapp->module, "class='form-control'")?></td><td></td>
      </tr>
      <?php if($webapp->addType != 'system'):?>
      <tr>
        <th><?php echo $lang->webapp->name?></th>
        <td><?php echo html::input('name', $webapp->name, "class='form-control'")?></td>
      </tr>
      <tr>
        <th><?php echo $lang->webapp->url?></th>
        <td><?php echo html::input('url', $webapp->url, "class='form-control'")?></td>
      </tr>
      <?php endif;?>
      <tr>
        <th><?php echo $lang->webapp->target?></th>
        <td><?php echo html::select('target', $lang->webapp->targetList, $webapp->target, "class='form-control'")?></td>
      </tr>
      <?php 
      $customWidth  = '';
      $customHeight = '';
      if(!array_key_exists($webapp->size, $lang->webapp->sizeList))
      {
          $size = $webapp->size;
          $webapp->size = 'custom';
          if(strpos($size, 'x') !== false) list($customWidth, $customHeight) = explode('x', $size);
      }
      ?>
      <tr class="size">
        <th><?php echo $lang->webapp->size?></th>
        <td><?php echo html::select('size', $lang->webapp->sizeList, $webapp->size, "class='form-control'")?></td>
      </tr>
      <tr class="customSize <?php if($webapp->size != 'custom') echo 'hidden'?>">
        <th><?php echo $lang->webapp->custom?></th>
        <td>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $lang->webapp->width;?></span>
            <?php echo html::input('customWidth', $customWidth, "class='form-control'");?>
            <span class='input-group-addon'>px</span>
          </div>
        </td>
        <td>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $lang->webapp->height;?></span>
            <?php echo html::input('customHeight', $customHeight, "class='form-control'");?>
            <span class='input-group-addon'>px</span>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->webapp->abstract?></th>
        <td colspan='2'><?php echo html::input('abstract', $webapp->abstract, "class='form-control' maxlength='30'")?><span class='help-block'><?php echo $lang->webapp->noticeAbstract?></span></td>
      </tr>
      <tr>
        <th><?php echo $lang->webapp->desc?></th>
        <td colspan='2'><?php echo html::textarea('desc', $webapp->desc, "class='form-control' rows='5'")?></td>
      </tr>
      <?php if($webapp->addType == 'custom'):?>
      <tr>
        <th><?php echo $lang->webapp->icon?></th>
        <td>
          <?php
          if($webapp->icon) echo "<p><img src='{$webapp->icon->webPath}' width='72' height='72' /></p>";
          echo html::file('files', "class='form-control' size='57'");
          ?>
        </td>
        <td><span class='help-block'><?php echo $lang->webapp->noticeIcon?></span></td>
      </tr>
      <?php endif;?>
      <tr><th></th><td colspan='2'><?php echo html::submitButton()?></td></tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>

