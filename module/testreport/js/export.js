function tab()
{
    tabContent = new Array();
    var divTags=document.getElementsByTagName("div");
    for(var divKey in divTags)
  	{
    	  if(typeof(divTags[divKey]) == 'object' && divTags[divKey].getAttribute('class').indexOf('tab-pane') >= 0)
  	    {
            tabContent.push(divTags[divKey]);
            divTags[divKey].setAttribute('class', 'tab-pane');
        }
    }
    tabContent[0].setAttribute('class', 'tab-pane active');

    var aTags=document.getElementsByTagName("a");
    for(var i = 0;i<aTags.length;i++)
    {
        if(aTags[i].getAttribute("data-toggle") == 'tab')
        {
            aTags[i].onclick = function()
            {
                var liTags=document.getElementsByTagName("li");
                for(var liKey in liTags)
                {
                    if(typeof(liTags[liKey]) == 'object' && liTags[liKey].parentNode.getAttribute('class') == 'nav nav-tabs') liTags[liKey].removeAttribute('class');
                }
                this.parentNode.setAttribute('class', 'active');
                for(var divKey in tabContent) tabContent[divKey].setAttribute('class', 'tab-pane');
                document.getElementById(this.getAttribute('data-target').replace(/#/, '')).setAttribute('class', 'tab-pane active');
            }
        } 
    }
}
