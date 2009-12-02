<span><?php echo "$action->date, <strong>$action->action</strong> by <strong>$action->actor</strong>"; ?></span>
<?php if(!empty($action->comment) or !empty($histories)):?>
<div class='history'>
<?php
if(!empty($histories))
{
    foreach($histories[$action->id] as $history)
    {
        if($history->diff != '')
        {
            echo "CHANGE <strong>$history->field</strong>, the diff is: <blockquote>" . nl2br($history->diff) . "</blockquote>";
        }
        else
        {
            echo "CHANGE <strong>$history->field</strong> FROM '$history->old' TO '$history->new' . <br />";
        }
    }
}
echo nl2br($action->comment); 
?>
</div>
<?php endif;?>
<style>
del  {background:#fcc}
ins  {background:#cfc; text-decoration:none}
table, tr, th, td {border:1px solid gray; font-size:12px; border-collapse:collapse}
tr, th, td {padding:5px}
.history {border:1px solid gray; padding:10px; margin-top:10px; margin-bottom:10px}
.header  {background:#efefef}
</style>
