<?php
/**
 * The limittaskdate view file of custom module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Liyuchun <liyuchun@cnezsoft.com>
 * @package     custom
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='mainContent' class='main-row'>
  <?php include 'sidebar.html.php';?>
  <div class='main-col main-content'>
    <form class="load-indicator main-form form-ajax" method='post' id='limitTaskDateForm'>
      <div class='main-header'>
        <div class='heading'>
          <strong><?php echo $lang->custom->$module->fields['limitTaskDate'];?></strong>
        </div>
      </div>
      <table class='table table-form'>
        <tr>
          <th class='c-name'><?php echo $lang->custom->beginAndEndDateRange;?></th>
          <td class='c-select text-left'>
            <?php $checkedKey = isset($config->limitTaskDate) ? $config->limitTaskDate : 0;?>
            <?php foreach($lang->custom->limitTaskDate as $key => $value):?>
            <label class="radio-inline"><input type="radio" name="limitTaskDate" value="<?php echo $key?>"<?php echo $key == $checkedKey ? " checked='checked'" : ''?> id="limitTaskDate<?php echo $key;?>"><?php echo $value;?></label>
            <?php endforeach;?>
          </td>
          <td></td>
        </tr>
        <tr>
          <td colspan='2' class='form-actions text-center'>
            <?php echo html::submitButton();?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<script>
$(function()
{
    $('#mainMenu #taskTab').addClass('btn-active-text');
})
</script>
<?php include '../../common/view/footer.html.php';?>
