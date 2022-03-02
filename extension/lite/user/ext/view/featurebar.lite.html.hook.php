<script>
$('#mainMenu .btn-toolbar > a[href*="bug"], a[href*="testtask"], a[href*="testcase"], a[href*="issue"], a[href*="risk"], a[href*="requirement"]').remove();
<?php if($this->config->edition != 'open' and common::hasPriv('user', 'todocalendar')):?>
$('#mainMenu .btn-toolbar > a:first').attr('href', createLink('user', 'todocalendar', 'userID=<?php echo $user->id?>'));
<?php endif;?>
</script>
