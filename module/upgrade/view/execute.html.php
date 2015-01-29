<?php
/**
 * The html template file of execute method of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: execute.html.php 5119 2013-07-12 08:06:42Z wyd621@gmail.com $
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='container'>
  <div class='modal-dialog'>
    <div class='modal-header'>
      <strong><?php echo $lang->upgrade->result;?></strong>
    </div>
    <div class='modal-body'>
      <?php if($result == 'fail'):?>
      <div class='alert alert-danger mgb-10'><strong><?php echo $lang->upgrade->fail?></strong></div>
      <pre><?php echo nl2br(join('\n', $errors));?></pre>
      <?php else:?>
      <div class='alert alert-success mgb-10'><strong><?php echo $lang->upgrade->success?></strong></div>
      <div id='tohome' class='mt-10px'></div>
      <?php endif;?>
      <div id='checkExtension'><?php if($result == 'success') echo $lang->upgrade->checkExtension?></div>
    </div>
  </div>
</div>
<script>
var tohome = <?php echo json_encode(html::a('index.php', $lang->upgrade->tohome, '', "class='btn btn-sm'"))?>;
var result = '<?php echo $result?>';
</script>
<?php include '../../common/view/footer.lite.html.php';?>
