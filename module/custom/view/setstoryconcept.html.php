<?php
/**
 * The html template file of setstoryconcept method of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Guangming Sun<sunguangming@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: setstoryconcept.html.php 4129 2020-09-01 01:58:14Z sgm $
 */
?>
<?php
if($this->config->URAndSR)
{
    $itemRow = <<<EOT
  <tr>
    <td><input type='text' class="form-control" autocomplete="off" value="" name="URName[]"></td>
    <td><input type='text' class="form-control" autocomplete="off" value="" name="SRName[]"></td>
    <td class='c-actions'>
      <a href="javascript:void(0)" class='btn btn-link' onclick="addItem(this)"><i class='icon-plus'></i></a>
      <a href="javascript:void(0)" class='btn btn-link' onclick="delItem(this)"><i class='icon-close'></i></a>
    </td>
  </tr>
EOT;
}
else
{
    $itemRow = <<<EOT
  <tr>
    <td><input type='text' class="form-control" autocomplete="off" value="" name="SRName[]"></td>
    <td class='c-actions'>
      <a href="javascript:void(0)" class='btn btn-link' onclick="addItem(this)"><i class='icon-plus'></i></a>
      <a href="javascript:void(0)" class='btn btn-link' onclick="delItem(this)"><i class='icon-close'></i></a>
    </td>
  </tr>
EOT;

}
?>
<?php js::set('itemRow', $itemRow);?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-row'>
  <form class="load-indicator main-form form-ajax" method='post'>
    <div class='modal-body'>
      <div class='main-header'>
        <h2>
          <span><?php echo $lang->custom->setStoryConcept;?></span>
        </h2>
      </div>
      <table class='table table-form'>
        <tr class='text-center'>
          <?php if($this->config->URAndSR):?>
          <td class='w-200px'><strong><?php echo $lang->custom->URConcept;?></strong></th>
          <?php endif;?>
          <td<?php echo $this->config->URAndSR ? " class='w-200px'": " class='w-250px'";?>><strong><?php echo $lang->custom->SRConcept;?></strong></th>
        </tr>
        <tr>
          <?php if($this->config->URAndSR):?>
          <td><?php echo html::input('URName[]', '', "class='form-control'");?></td>
          <?php endif;?>
          <td><?php echo html::input('SRName[]', '', "class='form-control'");?></td>
          <td class='c-actions'>
            <a href="javascript:void(0)" class='btn btn-link' onclick="addItem(this)"><i class='icon-plus'></i></a>
          </td>
        </tr>
        <tr>
          <?php if ($this->config->URAndSR) echo "<td></td>";?>
          <td class=<?php echo !$this->config->URAndSR ? "'text-center' colspan='2'" : "text-left";?>><?php echo html::submitButton();?></td>
        </tr>
      </table>
    </div>
  </form>
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
