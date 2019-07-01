<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content load-indicator">
  <div class="main-header">
    <h2><?php echo $lang->translate->resultList['reject'];?></h2>
  </div>
  <form class="main-form form-ajax" method="post">
    <table class="table table-form">
      <tbody>
        <tr>
          <th class='w-100px'><?php echo $lang->translate->reason;?></th>
          <td class='required'><?php echo html::textarea('reason', '', "class='form-control'");?></td>
        </tr>
        <tr>
          <th></th>
          <td><?php echo html::submitButton();?></td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
