<?php include '../../common/view/header.lite.html.php';?>
<div id="mainContent" class="main-content load-indicator">
  <div class="main-header">
    <h2><?php echo $lang->translate->setting;?></h2>
  </div>
  <form class="main-form" method="post" target="hiddenwin">
    <table class="table table-form">
      <tbody>
        <tr>
          <th><?php echo $lang->translate->reviewTurnon;?></th>
          <td><?php echo html::radio('needReview', $lang->translate->reviewTurnonList, zget($this->config->translate, 'needReview', 0));?></td>
          <td></td>
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
