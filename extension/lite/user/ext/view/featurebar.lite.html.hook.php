<script>
$('#mainMenu .btn-toolbar > a[href*="bug"], a[href*="testtask"], a[href*="testcase"], a[href*="issue"], a[href*="risk"], a[href*="requirement"]').remove();
$('#mainMenu .btn-toolbar > a:first').attr('href', createLink('user', 'todocalendar', 'userID=<?php echo $user->id?>'));
</script>
