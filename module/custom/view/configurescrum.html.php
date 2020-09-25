<?php
/**
 * The html template file of configureScrum method of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Guangming Sun<sunguangming@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: configurescrum.html.php 4129 2020-09-01 01:58:14Z sgm $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php
$itemRow = <<<EOT
  <tr class='text-center'>
    <td>
      <input type='text' class="form-control" autocomplete="off" value="" name="keys[]">
      <input type='hidden' value="0" name="systems[]">
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
<?php js::set('type', $type);?>
<?php js::set('itemRow', $itemRow);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::a(inlink('configurescrum', 'type=concept'), "<span class='text'>" . $lang->custom->concept . '</span>', '', "class='btn btn-link concept'");?>
    <?php echo html::a(inlink('configurescrum', 'type=user'), "<span class='text'>" . $lang->user->role . '</span>', '', "class='btn btn-link user'");?>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <form id='ajaxForm' class='form-ajax' method='post'>
    <div class='modal-body'>
      <?php if($type == 'concept'):?>
      <div class="form-group">
        <label><?php echo $lang->custom->conceptQuestions['overview']?></label>
        <div class="checkbox"> <?php echo html::radio('productProject', $lang->custom->productProject->relation, zget($this->config->custom, 'productProject', '0_0'))?> </div>
      </div>
      <div class='form-group'>
        <label><?php echo '2. ' . $lang->custom->scrum->URAndSR;?></label>
        <div class='checkbox'><?php echo html::radio('URAndSR', $lang->custom->waterfallOptions->URAndSR, zget($this->config->custom, 'URAndSR', '0'));?></div>
      </div>
      <?php $hidden = zget($this->config->custom, 'URAndSR', 0) == 0 ? 'hidden' : '';?>
      <div class="form-group <?php echo $hidden;?>" id='URSRName'><label><?php echo '3. ' . $lang->custom->scrum->URSRName;?></label>
        <div class='input-group'>
          <?php 
            echo html::input("URCommon[{$clientLang}]", isset($URSRName->URCommon->$clientLang) ? $URSRName->URCommon->$clientLang : $lang->custom->URStory, "class='form-control'");
          ?>
          <span class='input-group-addon'></span>
          <?php echo html::input("SRCommon[{$clientLang}]", isset($URSRName->SRCommon->$clientLang) ? $URSRName->SRCommon->$clientLang : $lang->custom->SRStory, "class='form-control'");?>
        </div>
      </div>
      <div class="form-group">
        <label></label>
        <div><?php echo html::submitButton();?></div>
      </div>
      <?php endif;?>
      <?php if($type == 'user'):?>
	  <table class='table table-form active-disabled table-condensed mw-600px'>
        <tr class='text-center'>
          <td class='w-120px'><strong><?php echo $lang->custom->key;?></strong></td>
          <td><strong><?php echo $lang->custom->value;?></strong></td>
          <th class='w-90px'></th>
        </tr>
        <?php foreach($fieldList as $key => $value):?>
        <tr class='text-center'>
          <?php $system = isset($dbFields[$key]) ? $dbFields[$key]->system : 1;?>
          <td><?php echo $key === '' ? 'NULL' : $key; echo html::hidden('keys[]', $key) . html::hidden('systems[]', $system);?></td>
          <td>
            <?php echo html::input("values[]", isset($dbFields[$key]) ? $dbFields[$key]->value : $value, "class='form-control' " . (empty($key) ? 'readonly' : ''));?>
          </td>
          <td class='c-actions'>
            <a href="javascript:void(0)" onclick="addItem(this)" class='btn btn-link'><i class='icon-plus'></i></a>
            <a href="javascript:void(0)" onclick="delItem(this)" class='btn btn-link'><i class='icon-close'></i></a>
          </td>
        </tr>
        <?php endforeach;?>
        <tr>
          <td colspan='3' class='text-center form-actions'>
          <?php
          $appliedTo = array($currentLang => $lang->custom->currentLang, 'all' => $lang->custom->allLang);
          echo html::radio('lang', $appliedTo, $lang2Set);
          echo html::submitButton();
          if(common::hasPriv('custom', 'restore')) echo html::linkButton($lang->custom->restore, inlink('restore', "module=user&field=roleList"), 'hiddenwin', '', 'btn btn-wide');
          ?>
          </td>
        </tr>
      </table>
      <?php endif;?>
    </div>
  </form>
</div>
<script>
$('.' + type).addClass('btn-active-text');

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
