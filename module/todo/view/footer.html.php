<?php js::set('account', $app->user->account)?>
<?php if($this->app->viewType == 'xhtml'):?>
<?php include '../../common/view/footer.lite.html.php';?>
<?php else:?>
<?php include '../../common/view/footer.html.php';?>
<?php endif;?>
