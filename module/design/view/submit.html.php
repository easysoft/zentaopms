<?php include '../../common/view/header.lite.html.php';?>
<main id="main">
  <div class="container">
    <div id="mainContent" class='main-content load-indicator'>
      <div class='main-header'>
        <h2><?php echo $lang->design->submit;?></h2>
      </div>
      <form class='main-form' method='post' target='hiddenwin'>
        <table class="table table-form">
          <tr>
            <th><?php echo $lang->design->reviewObject;?></th>
            <td>
              <?php echo html::select('object', $lang->design->typeList, $designType, "class='form-control'");?>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->design->range;?></th>
            <td>
              <?php echo html::select('range', $lang->design->rangeList, '', "class='form-control'");?>
            </td>
          </tr>
          <tr>
            <th></th>
            <?php echo html::hidden('designID', '');?>
            <td><?php echo html::submitButton('', '', 'btn btn-primary');?></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
<script>
    var designID = $.cookie('submitDesignID');
    $("#designID").val(designID);
</script>
</main>
