<?php
/**
 * The edit of programplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     programplan
 * @version     $Id: edit.html.php 4903 2013-06-26 05:32:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php
js::set('parentID', $plan->parent);
?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->programplan->edit;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <?php if($isParent === false):?>
          <tr>
            <th class="w-100px"><?php echo $lang->programplan->parent;?></th>
            <td class="w-p35-f"><?php echo html::select('parent', $parentStage, $plan->parent, "class='form-control chosen '");?></td>
          </tr>
          <?php else:?>
            <?php echo html::hidden('parent', 0);?>
          <?php endif;?>
          <tr>
            <th class='w-100px'><?php echo $lang->programplan->name;?> </th>
            <td class='w-p35-f'><?php echo html::input('name', $plan->name, "class='form-control'");?></td>
            <td></td><td></td>
          </tr>
          <tr>
            <th><?php echo $lang->programplan->percent;?> </th>
            <td>
              <div class='input-group'>
                <?php echo html::input('percent', $plan->percent, "class='form-control'");?>
                <div class='input-group-addon'>%</span>
              </div>
            </td>
          </tr>
          <tr class="<?php if($plan->parent) echo "hidden";?>" id="attributeType">
            <th><?php echo $lang->programplan->attribute;?> </th>
            <td><?php echo html::select('attribute', $lang->stage->typeList, $plan->attribute, "class='form-control'");?></td>
          </tr>
          <?php if($plan->setMilestone):?>
          <tr>
            <th><?php echo $lang->programplan->milestone;?> </th>
            <td><?php echo html::radio('milestone', $lang->programplan->milestoneList, $plan->milestone);?></td>
          </tr>
          <?php else:?>
            <?php echo html::hidden('milestone', $plan->milestone);?>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->programplan->begin;?> </th>
            <td><?php echo html::input('begin', $plan->begin, "class='form-control form-date'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->programplan->end;?> </th>
            <td><?php echo html::input('end', $plan->end, "class='form-control form-date'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->programplan->realBegan;?> </th>
            <td><?php echo html::input('realBegan', $plan->realBegan, "class='form-control form-date'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->programplan->realEnd;?> </th>
            <td><?php echo html::input('realEnd', $plan->realEnd, "class='form-control form-date'");?></td>
          </tr>
          <tr>
            <td colspan='4' class='form-actions text-center'><?php echo html::submitButton() . html::backButton()?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<script>
$("#parent").change(function()
{
    var parent = $(this).children("option:selected").val();
    if(parentID > 0)
    {
        if(parent == 0)
        {
            $("#attributeType").removeClass('hidden');
        }
        else
        {
            $("#attributeType").addClass('hidden');

        }
    }
    else
    {
        if(parent == 0)
        {
            $("#attributeType").removeClass('hidden');
        }
        else
        {
            $("#attributeType").addClass('hidden');

        }
    }
});
</script>
<?php include '../../common/view/footer.html.php';?>
