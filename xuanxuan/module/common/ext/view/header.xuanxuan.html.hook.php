<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'xuanxuan') != false): ?>
<style>
body {scrollbar-gutter: stable both-edges;}
#mainContent .detail + .detail {padding-top: 0;}
::-webkit-scrollbar-thumb {border-radius: 5px;}
::-webkit-scrollbar {width: 10px; height: 10px;}
::-webkit-scrollbar-thumb:vertical {box-shadow: inset 1px 1px 0 rgb(0 0 0 / 10%), inset 0 -1px 0 rgb(0 0 0 / 7%); background-color: rgba(0, 0, 0, 0.2); border-radius: 10px; opacity: 0; transition: opacity 0.1s;}
::-webkit-scrollbar,
::-webkit-scrollbar-button,
::-webkit-scrollbar-track,
::-webkit-scrollbar-thumb {visibility: hidden;opacity: 0;}
:hover ::-webkit-scrollbar,
:hover ::-webkit-scrollbar-button,
:hover ::-webkit-scrollbar-track,
:hover ::-webkit-scrollbar-thumb {visibility: visible;opacity: 1;}

#navbar {display: flex; align-items: center; justify-content: center;}
#navbar .nav {display: flex; align-items: center; height: 50px;}
#navbar .nav > li {float: none;}
#navbar .nav > li > a {padding-top: 3px!important; padding-bottom: 3px!important; border-radius: 4px;}
</style>
<script>
$(function()
{
    if(window.parent === window || window.parent.appHeaderStyleUpdated) return;
    const mainHeader = document.getElementById('mainHeader');
    if(!mainHeader) return;
    const style = window.getComputedStyle(mainHeader, null);
    const color = window.getComputedStyle(document.querySelector('#navbar .nav>li:not(.active)>a'), null).color;
    const clientHeaderStyle = {windowControlBtnColor: color, background: style.background, color: color};
    window.parent.appHeaderStyleUpdated = true;
    window.open('xxc://setAppHeaderStyle/zentao-integrated/' + encodeURIComponent(JSON.stringify(clientHeaderStyle)), '_blank');
});
</script>
<?php endif; ?>
