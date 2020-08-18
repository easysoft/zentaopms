<?php include '../../common/view/header.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->risk->create;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th><?php echo $lang->risk->name;?></th>
            <td><?php echo html::input('name', '', "class='form-control'");?></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->risk->percent;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::input('percent', '', "class='form-control'");?>
                <span class='input-group-addon'>%</span> 
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->risk->type;?></th>
            <td><?php echo html::select('type', $lang->risk->typeList, '',  "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <td></td>
            <td colspan='3' class='form-actions'>
              <?php echo html::submitButton() . html::backButton();?>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
