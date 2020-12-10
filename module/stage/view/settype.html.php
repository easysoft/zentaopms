<?php
/**
 * The setType view of stage module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     stage
 * @version     $Id: setType.html.php 4903 2013-06-26 05:32:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php
$itemRow = <<<EOT
  <tr class='text-left'>
    <td>
      <input type='text' class="form-control" autocomplete="off" value="" name="keys[]">
    </td>
    <td>
      <input type='text' class="form-control" value="" autocomplete="off" name="values[]">
    </td>
    <td class='c-actions'>
      <a href="javascript:void(0)" class='btn btn-link' onclick="addItem(this)"><i class='icon-plus'></i></a>
      <a href="javascript:void(0)" class='btn btn-link' onclick="delItem(this)"><i class='icon-close'></i></a>
    </td>
  </tr>
EOT;
?>
<?php js::set('itemRow', $itemRow)?>
<div id='mainContent' class='main-row'>
  <div class='side-col'>
    <div class='panel'>
      <div class='panel-body'>
        <div class="list-group">
          <?php echo html::a(inlink('setType'), $lang->stage->setType, '', "class='selected'");?>
          <?php echo html::a(inlink('browse'), $lang->stage->browse);?>
        </div>
      </div>
    </div>
  </div>
  <div class='main-col main-content'>
    <form class="load-indicator main-form form-ajax" method='post'>
      <table class='table table-form active-disabled table-condensed mw-600px'>
        <thead>
          <tr class='text-center'>
            <th class='w-70px'><?php echo $lang->custom->key;?></th>
            <th class='w-250px'><?php echo $lang->custom->value;?></th>
            <th class='w-100px'></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($lang->stage->typeList as $key => $value):?>
          <tr class='text-center'>
            <td class='w-70px'>
            <?php echo $key;?>
            <?php echo html::hidden('keys[]', $key) ?>
          </td>
            <td class='w-200px'><?php echo html::input('values[]', $value, 'class="form-control"');?></td>
            <td class='c-actions text-left'>
              <a href="javascript:void(0)" onclick="addItem(this)" class='btn btn-link'><i class='icon-plus'></i></a>
              <a href="javascript:void(0)" class='btn btn-link' onclick="delItem(this)"><i class='icon-close'></i></a>
            </td>
          </tr>
          <?php endforeach;?>
          <tr>
            <td></td>
            <td colspan='2' class='form-actions'><?php echo html::submitButton() . html::backButton();?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<script>
function addItem(clickedButton)
{
    $(clickedButton).parent().parent().after(itemRow);
}

function delItem(clickedButton)
{
    $(clickedButton).parent().parent().remove();
}
</script>
<?php include '../../common/view/footer.html.php';?>
