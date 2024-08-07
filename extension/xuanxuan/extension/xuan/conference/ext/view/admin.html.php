<?php
/**
 * The admin view file of conference module of XXB.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd., www.zentao.net)
 * @license     ZOSL (https://zpl.pub/page/zoslv1.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     conference
 * @version     $Id$
 * @link        https://xuanim.com
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div class='panel'>
  <div class="panel-heading">
    <?php echo $lang->conference->common; ?>
  </div>
  <form method='post' id='ajaxForm' class="form-ajax<?php if ($enabled) echo ' conference-enabled'; ?>">
    <table class="table table-form">
      <tr>
        <th class="w-150px"><?php echo $lang->conference->enabled; ?></th>
        <td class="w-400px">
          <?php if ($type != 'edit') : ?>
            <div class="checkbox-primary disabled <?php if ($enabled) echo 'checked'; ?>">
              <label><?php echo $lang->conference->enabledTip; ?></label>
            </div>
          <?php else : ?>
            <div class="checkbox-primary">
              <input type="checkbox" name="enabled" id='enabled' value="true" <?php if ($enabled) echo 'checked'; ?> <?php if ($type != 'edit') echo 'disabled'; ?>>
              <label for='enabled'><?php echo $lang->conference->enabledTip; ?></label>
            </div>
          <?php endif; ?>
        </td>
        <td></td>
      </tr>
      <?php if ($type == 'edit' || $enabled) : ?>
        <tr class='edit-row common-row'>
          <th class="w-120px"><?php echo $lang->conference->domain; ?></th>
          <td class="w-400px code">
            <?php if ($type == 'edit') : ?>
              <div class='required required-wrapper'></div>
              <?php echo html::input('domain', $domain, "class='form-control'"); ?>
            <?php else : echo empty($domain) ? $lang->conference->notset : $domain;
            endif; ?>
          </td>
          <?php if($type == 'edit'): ?>
            <td class="text-muted" style="padding-left: 20px;"><?php echo $lang->conference->serverAddrTip; ?></td>
          <?php endif; ?>
        </tr>
      <?php endif; ?>
      <tr>
        <th></th>
        <td colspan='2'>
          <?php if ($type == 'edit') echo html::submitButton(); ?>
          <?php if ($type != 'edit') echo '<a class="btn btn-primary" href="' . helper::createLink('conference', 'admin', 'type=edit') . '">' . $lang->edit; ?>
        </td>
      </tr>
    </table>
  </form>
</div>

<style>
  .edit-row {
    display: none
  }

  #ajaxForm.conference-enabled .edit-row {
    display: table-row
  }
</style>

<script>
  $(function() {
    $('#enabled').on('change', function() {
      $('#ajaxForm').toggleClass('conference-enabled', $('#enabled').is(':checked'));
    });
  });
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
