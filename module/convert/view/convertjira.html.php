<?php
/**
 * The html template file of execute method of convert module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id: execute.html.php 4129 2013-01-18 01:58:14Z wwccss $
 */
?>
<?php include '../../common/view/header.html.php';?>
<style>
.row {margin: auto; width: 60%}
.importMethod {padding: 0 40px}
.importMethod {width: 280px; border: 1px solid #CBD0DB; border-radius: 2px; margin-bottom: 10px; cursor: pointer; margin-top: 1px}
.importMethod:hover {border-color: #006AF1; box-shadow: 0 0 10px 0 rgba(0,0,0,.25);}
.importMethod.active img {border-color: #006AF1; border-width: 2px; margin-top: 0}
.importMethod p {margin-top: 10px; color: #0d8aee;}
</style>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='row text-center'>
      <div class='col-xs-6'>
        <div class='text-center importMethod' data-url="<?php echo $this->createLink("convert", "importNotice", "type=db");?>">
          <h3><?php echo $lang->convert->jira->importFromDB;?></h3>
          <?php echo $lang->convert->jira->dbDesc;?>
        </div>
      </div>
      <div class='col-xs-6'>
        <div class='text-center importMethod' data-url="<?php echo $this->createLink("convert", "importNotice", "type=file");?>">
          <h3><?php echo $lang->convert->jira->importFromFile;?></h3>
          <?php echo $lang->convert->jira->fileDesc;?>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
$('.importMethod').click(function(e)
{
    $.apps.open($(this).data('url'));
});
</script>
<?php include '../../common/view/footer.html.php';?>
