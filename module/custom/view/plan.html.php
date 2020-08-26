<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php common::printLink('custom', 'plan', '', "<span class='text'>{$lang->custom->plan}</span>", '', "class='btn btn-link btn-active-text'"); ?>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <form class="load-indicator main-form form-ajax" method='post'>
      <table class='table table-form mw-900px'>
        <tr>
          <th class='w-120px'><?php echo $lang->custom->planStatus;?></th>
          <td><?php echo html::radio('planStatus', $lang->custom->planStatusList, $status);?></td>
        </tr>
        <tr>
          <td></td>
          <td class='text-left'><?php echo html::submitButton();?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
