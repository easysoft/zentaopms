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
      <pre><?php echo join('<br />', $errors);?></pre>
      <?php else:?>
      <div class='alert alert-success mgb-10'><strong><?php echo $lang->upgrade->success?></strong></div>
      <div class='mt-10px'><?php echo html::a('index.php', $lang->upgrade->tohome, '', "class='btn btn-sm'")?></div>
      <div class='row adbox'>
        <h5><?php echo $lang->install->promotion?></h5>
        <div class='col-md-4'>
          <a class="card ad" href="http://www.chanzhi.org" target="_blank">
            <div class="img-wrapper" style="background-image:url(<?php echo $defaultTheme . 'images/main/chanzhi.png'?>)"><img src="<?php echo $defaultTheme . 'images/main/chanzhi.png'?>" alt=""></div>
            <strong class="card-heading"><?php echo $lang->install->chanzhi->name?></strong>
            <div class="card-reveal">
              <h5 class="card-heading"><?php echo $lang->install->chanzhi->name?></h5>
              <div class="card-content"><?php echo $lang->install->chanzhi->desc?></div>
            </div>
          </a>
        </div>
        <div class='col-md-4'>
          <a class="card ad" href="http://www.ranzhico.com" target="_blank">
            <div class="img-wrapper" style="background-image:url(<?php echo $defaultTheme . 'images/main/ranzhi.png'?>)"><img src="<?php echo $defaultTheme . 'images/main/ranzhi.png'?>" alt=""></div>
            <strong class="card-heading"><?php echo $lang->install->ranzhi->name?></strong>
            <div class="card-reveal">
              <h5 class="card-heading"><?php echo $lang->install->ranzhi->name?></h5>
              <div class="card-content"><?php echo $lang->install->ranzhi->desc?></div>
            </div>
          </a>
        </div>
        <div class='col-md-4'>
          <a class="card ad" href="http://www.yidouio.com" target="_blank">
            <div class="img-wrapper" style="background-image:url(<?php echo $defaultTheme . 'images/main/yidou.png'?>)"><img src="<?php echo $defaultTheme . 'images/main/yidou.png'?>" alt=""></div>
            <strong class="card-heading"><?php echo $lang->install->yidou->name?></strong>
            <div class="card-reveal">
              <h5 class="card-heading"><?php echo $lang->install->yidou->name?></h5>
              <div class="card-content"><?php echo $lang->install->yidou->desc?></div>
            </div>
          </a>
        </div>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
