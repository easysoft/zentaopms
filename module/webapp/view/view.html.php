<?php
/**
 * The view view file of webapp module of ZenTaoPMS.
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
    <small class='text-muted'> <i class='icon-file-text'></i> <?php echo $lang->webapp->view?></small>
  </div>
  <div class='actions'>
  <?php if($type =='local') common::printLink('webapp', 'edit', "webappID=$webapp->id", "<i class='icon-pencil'></i> " . $lang->edit, '', "class='btn btn-primary'");?>
  </div>
</div>
<div class='main'>
  <table class='table table-data table-condensed table-borderless'>
    <?php if($type == 'local'):?>
    <tr>
      <th><?php echo $lang->webapp->module?></th>
      <td><?php echo $modules[$webapp->module]?></td>
    </tr>
    <?php endif;?>
    <tr>
      <th class='w-100px'><?php echo $lang->webapp->name?></th>
      <td><?php echo $webapp->name?></td>
    </tr>
    <tr>
      <th><?php echo $lang->webapp->url?></th>
      <td><?php echo $webapp->url?></td>
    </tr>
    <tr>
      <th><?php echo $lang->webapp->author?></th>
      <td><?php echo $webapp->author?></td>
    </tr>
    <tr>
      <th><?php echo $lang->webapp->target?></th>
      <td><?php echo $lang->webapp->targetList[$webapp->target]?></td>
    </tr>
    <?php if($webapp->target == 'popup'):?>
    <?php
    if(!array_key_exists($webapp->size, $lang->webapp->sizeList))
    {
        $size = $webapp->size;
        $webapp->size = 'custom';
    }
    ?>
    <tr>
      <th><?php echo $lang->webapp->size?></th>
      <td>
        <?php
        echo $lang->webapp->sizeList[$webapp->size];
        if(isset($size)) echo ' ： ' . $size;
        ?>
      </td>
    </tr>
    <?php endif;?>
    <tr>
      <th><?php echo $lang->webapp->abstract?></th>
      <td><?php echo $webapp->abstract?></td>
    </tr>
    <tr>
      <th><?php echo $lang->webapp->desc?></th>
      <td><?php echo $webapp->desc?></td>
    </tr>
    <?php if($type == 'local'):?>
    <tr>
      <th><?php echo $lang->webapp->addType?></th>
      <td><?php echo $lang->webapp->addTypeList[$webapp->addType]?></td>
    </tr>
    <tr>
      <th><?php echo $lang->webapp->addedBy?></th>
      <td><?php echo $users[$webapp->addedBy]?></td>
    </tr>
    <tr>
      <th><?php echo $lang->webapp->addedDate?></th>
      <td><?php echo $webapp->addedDate?></td>
    </tr>
    <?php endif;?>
    <?php if($type == 'api'):?>
    <tr>
      <th><?php echo $lang->webapp->downloads?></th>
      <td><?php echo $webapp->downloads?></td>
    </tr>
    <?php endif;?>
  </table>
</div>
<?php include '../../common/view/footer.lite.html.php';?>

