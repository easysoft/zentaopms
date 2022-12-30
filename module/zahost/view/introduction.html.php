<?php include '../../common/view/header.lite.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='article-content'>
    <h2 id='title'>
      <?php echo $lang->zahost->automation->title;?>
      <?php echo html::commonButton("<icon class='icon icon-close'></icon> $lang->close", "data-dismiss='modal'", 'btn float-right btn-close-modal');?>
    </h2>
    <h3 id='spec'>
      <icon class='icon icon-list icon-lg'></icon>
      <span><?php echo $lang->zahost->automation->abstract;?></span>
    </h3>
    <p>
      <span><?php echo $lang->zahost->automation->abstractSpec;?></span>
    </p>

    <h3>
      <icon class='icon icon-treemap-alt icon-lg'></icon>
      <?php echo $lang->zahost->automation->framework;?>
    </h3>
    <p><?php echo $lang->zahost->automation->frameworkSpec;?></p>
    <p><img src="<?php echo $config->webRoot . 'theme/default/images/main/automation.png';?>" referrerpolicy="no-referrer"></p>

    <h4>
      <?php echo $lang->zahost->automation->feature1;?>
    </h4>
    <p>
      <?php echo $lang->zahost->automation->feature1Spec;?>
    </p>

    <div id='app-introduction'>
      <h4>
        <?php echo $lang->zahost->automation->feature2;?>
      </h4>
      <ul>
        <li><?php echo $lang->zahost->automation->feature2ZenAgent;?></li>
      </ul>
      <p>
        <a href='<?php echo $config->zahost->automation->zenAgentURL;?>' target='_blank'><?php echo $config->zahost->automation->zenAgentURL;?></a>
      </p>
      <ul>
        <li><?php echo $lang->zahost->automation->feature2ZTF;?></li>
      </ul>
      <p>
        <a href='<?php echo $config->zahost->automation->ztfURL;?>' target='_blank'><?php echo $config->zahost->automation->ztfURL;?></a>
      </p>
      <ul>
        <li><?php echo $lang->zahost->automation->feature2KVM;?></li>
      </ul>
      <p>
        <a href='<?php echo $config->zahost->automation->kvmURL;?>' target='_blank'><?php echo $config->zahost->automation->kvmURL;?></a>
      </p>
      <ul>
        <li><?php echo $lang->zahost->automation->feature2Nginx;?></li>
      </ul>
      <p>
        <a href='<?php echo $config->zahost->automation->nginxURL;?>' target='_blank'><?php echo $config->zahost->automation->nginxURL;?></a>
      </p>
      <ul>
        <li><?php echo $lang->zahost->automation->feature2noVNC;?></li>
      </ul>
      <p>
        <a href='<?php echo $config->zahost->automation->novncURL;?>' target='_blank'><?php echo $config->zahost->automation->novncURL;?></a>
      </p>
      <ul>
        <li><?php echo $lang->zahost->automation->feature2Websockify;?></li>
      </ul>
      <p>
        <a href='<?php echo $config->zahost->automation->websockifyURL;?>' target='_blank'><?php echo $config->zahost->automation->websockifyURL;?></a>
      </p>
    </div>

    <h3>
      <icon class='icon icon-help icon-lg'></icon>
      <?php echo $lang->zahost->automation->support;?>
    </h3>
    <p><?php echo $lang->zahost->automation->supportSpec;?></p>
    <p>
      <a href='https://www.zentao.net/book/zentaopms' target='_blank'>https://www.zentao.net/book/zentaopms</a>
    </p>
    <div class='qr-code'>
      <div class='qr-code-img'>
        <img src='<?php echo $config->webRoot . 'theme/default/images/main/qrcode.png';?>' />
      </div>
      <div class='qr-code-details'>
        <div style='font-size: 16px; font-weight: bold;'><?php echo $lang->zahost->automation->groupTitle;?></div>
      </div>
    </div>
  </div>
  <div class="text-center">
  <?php echo html::commonButton($lang->close, "data-dismiss='modal'", 'btn btn-primary btn-wide btn-close-modal');?>
  <div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
