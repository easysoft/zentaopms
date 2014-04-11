<?php
/**
 * The html template file of execute method of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
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
      <pre><?php echo nl2br(join('\n', $errors));?></pre>
      <?php else:?>
      <div id='tohome'></div>
      <?php endif;?>
      <div id='checkExtension'><?php if($result == 'success') echo $lang->upgrade->checkExtension?></div>
    </div>
  </div>
</div>
<script>
var tohome = <?php echo json_encode(html::linkButton($lang->upgrade->tohome, 'index.php'))?>;
var result = '<?php echo $result?>';
</script>
<?php include '../../common/view/footer.lite.html.php';?>
