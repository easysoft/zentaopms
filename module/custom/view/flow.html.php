<?php
/**
 * The set view file of custom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     custom
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include 'header.html.php';?>
<div class='main'>
  <form method='post' class='form-condensed' target='hiddenwin'>
    <table class='table table-form'>
      <tr>
        <th class='w-150px text-top'><?php echo $lang->custom->select;?></th>
        <?php $checkedKey = isset($config->custom->productProject) ? $config->custom->productProject : '0_0' ?>
        <td>
          <?php foreach($lang->custom->productProject->relation as $key => $value):?>
          <p><label class="radio-inline"><input type="radio" name="productProject" value="<?php echo $key?>"<?php echo $key == $checkedKey ? " checked='checked'" : ''?> id="productProject<?php echo $key;?>"><?php echo $value;?></label></p>
          <?php endforeach;?>
        </td>
      </tr>
      <tr><td></td><td><?php echo html::submitButton()?></td></tr>
      <tr>
        <td colspan='2' class='pd-0'>
          <div class='alert alert-info alert-block'><strong><?php echo $lang->custom->productProject->notice?></strong></div>
        </td>
      </tr>
    </table>
  </form>
</div>
<script>
$(function()
{
    $('#featurebar #flowTab').addClass('active');
})
</script>
<?php include '../../common/view/footer.html.php';?>
