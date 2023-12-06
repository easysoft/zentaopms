<?php
/**
 * The custom view file of ops module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     ops
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php
$itemRow = <<<EOT
  <tr class='text-center'>
    <td>
      <input type='text' class="form-control" autocomplete='off' value="" name="keys[]">
      <input type='hidden' value="0" name="systems[]">
    </td>
    <td>
      <input type='text' class="form-control" autocomplete='off' value="" name="values[]">
    </td>
    <td class='c-actions text-left'>
      <a href='javascript:void()' class='btn' onclick='addDevItem(this)'><i class='icon-plus'></i></a>
      <a href='javascript:void()' class='btn' onclick='delDevItem(this)'><i class='icon-close'></i></a>
    </td>
  </tr>
EOT;
?>
<?php js::set('itemRow', $itemRow); ?>
<div id='mainContent' class='main-row'>
  <?php if(!empty($lang->{$module}->osNameList) && array_key_exists($field, $lang->{$module}->osNameList)):?>
  <div class='side-col'>
    <div class='cell'>
      <div class='list-group'>
        <?php
        foreach($lang->{$module}->osNameList as $key => $value)
        {
            echo html::a(inlink('os', "field=$key"), $value, '', "id='{$key}Tab'");
        }
        ?>
      </div>
    </div>
  </div>
  <?php endif;?>

  <div class='main-col main-content'>
    <form method='post' target='hiddenwin'>
      <div class='main-header'>
        <h2><?php echo $lang->$module->$field;?></h2>
      </div>
      <table class='table table-form active-disabled table-condensed mw-600px'>
        <tr class='text-center'>
          <td class='w-120px'><strong><?php echo $lang->custom->key;?></strong></td>
          <td><strong><?php echo $lang->custom->value;?></strong></td>
          <th class='w-80px'></th>
        </tr>
        <?php foreach($lang->$module->$fieldList as $key => $value):?>
        <tr class='text-center'>
          <td><?php echo $key === '' ? 'NULL' : $key; echo html::hidden('keys[]', $key) . html::hidden('systems[]', 0);?></td>
          <td>
            <?php echo html::input("values[]", $value, "class='form-control' autocomplete='off' " . (empty($key) ? 'readonly' : ''));?>
          </td>
          <td class='c-actions text-left'>
            <a href='javascript:void()' class='btn' onclick='addDevItem(this)'><i class='icon-plus'></i></a>
            <a href='javascript:void()' class='btn' onclick='delDevItem(this)'><i class='icon-close'></i></a>
          </td>
        </tr>
        <?php endforeach;?>
        <tr>
          <td colspan='3' class='text-center form-actions'>
          <?php
          $appliedTo   = array($this->app->getClientLang() => $lang->custom->currentLang, 'all' => $lang->custom->allLang);
          echo html::radio('lang', $appliedTo, str_replace('_', '-', $currentLang));
          echo html::submitButton();
          if(common::hasPriv('custom', 'restore')) echo html::linkButton($lang->custom->restore, $this->createLink('custom', 'restore', "module=$module&field=$fieldList"), 'hiddenwin', '', 'btn btn-wide');
          ?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<script>
    function addDevItem(clickedButton)
    {
        $(clickedButton).parent().parent().after(itemRow);
    }

    function delDevItem(clickedButton)
    {
        $(clickedButton).parent().parent().remove();
    }
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
