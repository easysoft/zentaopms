<?php
/**
 * The html template file of check config method of convert module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id$
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='yui-d0'><div class='u-1'>
  <form method='post' action='<?php echo inlink('execute');?>'>
  <table align='center' class='table-5 f-14px'>
    <caption><?php echo $lang->convert->checkConfig . $lang->colon . strtoupper($source);?></caption>
    <?php echo $checkResult;?>
  </table>
  <?php 
  echo html::hidden('dbHost',     $this->post->dbHost);
  echo html::hidden('dbPort',     $this->post->dbPort);
  echo html::hidden('dbUser',     $this->post->dbUser);
  echo html::hidden('dbPassword', $this->post->dbPassword);
  echo html::hidden('dbName',     $this->post->dbName);
  echo html::hidden('dbCharset',  $this->post->dbCharset);
  echo html::hidden('dbPrefix',   $this->post->dbPrefix);
  echo html::hidden('installPath',$this->post->installPath);
  ?>
  </form>
</div></div>
<?php include '../../common/view/footer.html.php';?>
