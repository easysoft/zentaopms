<?php
/**
 * The html template file of check config method of convert module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id: checkconfig.html.php 4129 2013-01-18 01:58:14Z wwccss $
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='container mw-700px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon('cloud-upload');?></span>
      <strong><?php echo $lang->convert->checkConfig;?></strong>
      <strong class='text-important'> <?php echo strtoupper($source);?></strong>
    </div>
  </div>
  <form method='post' action='<?php echo inlink('execute');?>'>
  <div class='alert'>
    <table align='center' class='table table-data table-borderless'>
      <?php echo $checkResult;?>
    </table>
  </div>
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
</div>
<?php include '../../common/view/footer.html.php';?>
