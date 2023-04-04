<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php if(isset($tab)):?>
<script>$('#mainMenu #<?php echo $tab;?>').addClass('btn-active-text')</script>
<?php endif;?>
<?php if($app->rawMethod == 'api'):?>
<style>.api-tip-icon.icon-help:before {margin-top: 10px; margin-left: -14px; font-size: 12px;}</style>
<div id='mainMenu' class='clearfix menu-secondary'>
  <div class="btn-toolbar pull-left">
    <?php
    foreach($lang->dev->featureBar['api'] as $key => $label)
    {
        $active = $selectedModule == $key ? 'btn-active-text' : '';
        if($key == 'index' and $selectedModule != 'restapi') $active = 'btn-active-text';

        $label = "<span class='text'>$label</span>";
        echo html::a(inlink('api', "module=$key"), $label, '', "class='btn btn-link $active'");

        if($key == 'index') echo "<icon class='icon icon-help api-tip-icon' data-toggle='popover' data-trigger='focus hover' data-placement='bottom' data-tip-class='text-muted popover-sm' data-content='{$lang->dev->apiTips}'></icon>";
    }
    ?>
  </div>
</div>
<script>$('[data-toggle="popover"]').popover();</script>
<?php endif;?>
