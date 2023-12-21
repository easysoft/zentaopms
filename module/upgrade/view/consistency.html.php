<?php
/**
 * The checkExtension view file of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     upgrade
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php js::set('execFixSQL', !empty($alterSQL) && !$hasError);?>

<div class='container'>
  <form method='post'>
    <div class='modal-dialog'>
      <div class='modal-header'>
        <strong><?php echo $lang->upgrade->consistency;?></strong>
      </div>
      <div class='modal-body'>
        <h4><?php echo $hasError ? $lang->upgrade->noticeErrSQL : $lang->upgrade->showSQLLog . "<span id='progressBox'></span>";?></h4>
        <div id='logBox' style='height:200px; width:100%; overflow:auto'><?php echo $hasError ? $alterSQL : '';?></div>
      </div>
      <?php if($hasError):?>
      <div class='modal-footer'><?php echo html::a('#', $this->lang->refresh, '', "class='btn btn-wide' onclick='location.reload()'");?></div>
      <?php endif;?>
    </div>
  </form>
</div>
<script>
version = "<?php echo $version;?>";
</script>
<?php include '../../common/view/footer.lite.html.php';?>
