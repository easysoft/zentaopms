<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- template designed by Marco Von Ballmoos -->
<title>{$title}</title>
<link rel="stylesheet" href="{$subdir}media/stylesheet.css" />
<link rel="stylesheet" href="{$subdir}media/banner.css" />
<meta http-equiv='Content-Type' content='text/html; charset=gbk'/>
</head>
<body>
{if count($packages) > 1}
<span class="field">Packages</span> 
<select class="package-selector" onchange="window.parent.left_bottom.location=this[selectedIndex].value">
{section name=p loop=$packages}
<option value="{$packages[p].link}">{$packages[p].title}</option>
{/section}
</select>
{/if}
<strong><a href="http://www.zentao.net" target="_blank">www.zentao.net</a></strong>
</body>
</html>
