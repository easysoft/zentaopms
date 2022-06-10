<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('colorList',$config->kanban->laneColorList);?>
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
          <td>
            <div class='required required-wrapper'></div>
            <?php echo html::input('name', '', "class='form-control'");?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->kanbanlane->column;?></th>
          <td><?php echo html::radio('mode', $lang->kanbanlane->modeList, 'sameAsOther');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->kanbanlane->otherlane;?></th>
          <td><?php echo html::select('otherLane', $lanes, '', "class='form-control chosen'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->kanbanlane->color;?></th>
          <td>
            <div id='color-picker'></div>
            <?php echo html::input('color', '#3C4353', "class='hidden'");?>
          </td>
        </tr>
        <tr>
          <td class='text-center form-actions' colspan='2'>
          <?php echo html::submitButton();?>
          <?php echo html::commonButton($lang->cancel, "data-dismiss='modal'", 'btn btn-wide');?>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>

<?php include '../../common/view/footer.html.php';?>
