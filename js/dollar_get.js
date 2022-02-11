;(function(window){
var
 $_GET = window.$_GET = {},
 $_VAN = window.$_VAN = {},
 location = window.location,
 search = location.search,
 href = location.href, 

 index = search.indexOf('?') != -1 ? search.indexOf('?') + 1 : 0,
 get = search.substr(index).split('&'),
 vanity = href.replace(/^https?:\/\/(.*?)\//i, '').replace(/\?.*$/i, '').split('/'); 

 for (var i in get){
 var split = get[i].split('=');
 $_GET[split[0]] = split[1]||null;
 }
 for (var i in vanity)
 $_VAN[i] = vanity[i]||null;
})(window);
