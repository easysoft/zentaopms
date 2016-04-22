<?php include '../../common/view/header.lite.html.php';?>
<table class='table table-form'>
  <tr>
    <td class='text-center w-160px'>
      <img src='theme/default/images/main/logo2.png' />
      <h4><?php printf($lang->misc->zentao->version, $config->version);?></h4>
    </td>
    <td> 
      <?php include './links.html.php';?>
    </td>
  </tr>
  <tr>
    <td colspan='2'>
      <div class='text-right copyright'>
        <?php echo $lang->misc->copyright;?>
      </div>
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.lite.html.php';?>
