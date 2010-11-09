<?php
/**
 * The html template file of execute method of convert module of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id$
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='yui-d0'>
  <table align='center' class='f-14px'>
    <caption><?php echo $lang->convert->execute . $lang->colon . strtoupper($source);?></caption>
    <?php echo $executeResult;?>
  </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
