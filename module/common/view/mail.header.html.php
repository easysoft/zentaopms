<?php
$onlybody = isonlybody() ? true : false;
if($onlybody) $_GET['onlybody'] = 'no';
?>

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
　<head>
　　<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
　　<title><?php echo $mailTitle ?></title>
　　<meta name='viewport' content='width=device-width, initial-scale=1.0'/>
　</head>
  <body style='background-color: #e5e5e5;'>
  　<table border='0' cellpadding='0' cellspacing='0' width='100%' style='font-size: 13px; color: #333; line-height: 20px; font-family: "Helvetica Neue",Helvetica,"Microsoft Yahei","Hiragino Sans GB","WenQuanYi Micro Hei",Tahoma,Arial,sans-serif;'>
      <tr>
        <td>
          <table align='center' border='0' cellpadding='0' cellspacing='0' width='600' style='border: none; border-collapse: collapse;'>
            <tr>
              <td style='padding: 10px 0; border: none; vertical-align: middle;'><strong style='font-size: 16px'><?php echo $this->app->company->name ?></strong></td>
            </tr>
          </table>
          <table align='center' border='0' cellpadding='0' cellspacing='0' width='600' style='border-collapse: collapse; background-color: #fff; border: 1px solid #cfcfcf; box-shadow: 0 0px 6px rgba(0, 0, 0, 0.1); margin-bottom: 20px; font-size:13px;'>
