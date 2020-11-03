<?php
/**
 * The to20 view file of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     upgrade
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='container'>
  <div class='panel' style='padding:20px;'>
    <div class='panel-title text-center'><?php echo $lang->upgrade->to20Tips;?></div>
    <div class='panel-body'>
      <?php echo $lang->upgrade->to20TipsHeader;?>
      <div class='video'>
        <video src=<?php echo $video;?> height='400px' controls='controls' width='60%'></video>
      </div>
      <div class='desc' style='margin-top: 20px'><?php echo $lang->upgrade->to20Desc;?></div>
    </div>
    <div class='panel-footer text-center'>
      <?php echo html::a($lang->upgrade->demoURL, $lang->upgrade->to20Demo, '_blank', "class='btn btn-secondary'");?>
      <?php echo html::a($this->createLink('upgrade', 'backup'), $lang->upgrade->to20Button, '', "class='btn btn-primary'");?>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>

