<?php
/**
 * The createexeclane file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     kanban
 * @version     $Id: createexeclane.html.php 935 2022-01-12 17:22:24Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('colorList',$config->kanban->laneColorList);?>
<?php js::set('regionID', $regionID);?>
<?php js::set('lanes', $lanes);?>
<style>
.table-form>tbody>tr>th {width: 160px;}
#otherLane_chosen {top: -8px;}
.margin-bottom {margin-bottom: -6px;}
</style>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2>
      <?php echo $lang->kanban->createLane;?>
    </h2>
  </div>
  <form class='load-indicator main-form form-ajax' method='post' enctype='multipart/form-data'>
    <table class='table table-form'>
      <tbody>
        <tr>
          <th><?php echo $lang->kanbanlane->name;?></th>
          <td colspan='3'>
            <div class='required required-wrapper'></div>
            <?php echo html::input('name', '', "class='form-control'");?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->kanbanlane->WIPType;?></th>
          <td id='laneTypeBox' colspan='3'><?php echo html::radio('laneType', $lang->kanban->laneTypeList, 'story');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->kanbanlane->column;?></th>
          <?php $mode = empty($lanes) ? 'independent' : 'sameAsOther';?>
          <td id='modeBox'><?php echo html::radio('mode', $lang->kanbanlane->modeList, $mode, '', 'block');?></td>
          <td id='laneBox' <?php if(empty($lanes)) echo 'hidden';?>><?php echo html::select('otherLane', $lanes, '', "class='form-control chosen'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->kanbanlane->color;?></th>
          <td colspan='3'>
            <div id='color-picker'></div>
            <?php echo html::input('color', '#3C4353', "class='hidden'");?>
          </td>
        </tr>
        <tr>
          <td class='text-center form-actions' colspan='4'>
          <?php echo html::submitButton();?>
          <?php echo html::commonButton($lang->cancel, "data-dismiss='modal'", 'btn btn-wide');?>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<script>
$(document).ready(function()
{
    initColorPicker();
    if($.isEmptyObject(lanes))
    {
        $('#modesameAsOther').closest('div').addClass('hidden');
        $('#laneBox').addClass('hidden');
        $('#modeindependent').attr('checked', 'checked');
        $('#modeindependent').closest('div').addClass('margin-bottom');
    }

    $('input[name=mode]').change(function() 
    {
        $('#otherLane').parents('td').toggle($(this).val() == 'sameAsOther');
        if($(this).val() == 'sameAsOther') $('#laneBox').removeClass('hidden');
    });

    $('input[name=laneType]').change(function()
    {
        var laneType = $(this).val();
        var link     = createLink('kanban', 'ajaxGetLanes', 'regionID=' + regionID + '&type=' + laneType);

        $.get(link, function(lanes)
        {
            if(lanes)
            {
                $('#modesameAsOther').parents('div').removeClass('hidden');
                $('#otherLane').replaceWith(lanes);
                $('#otherLane_chosen').remove();
                $('#otherLane').chosen();
                $('#modeindependent').closest('div').removeClass('margin-bottom');
            }
            else
            {
                $('#modesameAsOther').closest('div').addClass('hidden');
                $('#laneBox').addClass('hidden');
                $('#modeindependent').attr('checked', 'checked');
                $('#modeindependent').closest('div').addClass('margin-bottom');
            }
        })
    });
})
</script>
<?php include '../../common/view/footer.html.php';?>
