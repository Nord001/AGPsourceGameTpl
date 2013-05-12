<?php if(!ob_start("ob_gzhandler")) ob_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php

	$__URL__ = 'gamedomain.com';



	$isLoggedTester = false; //флаг есть ли юзер точно альфа-тестером
	
	if ((array_key_exists('action', $_GET)) && (!empty($_GET['action'])) && ($_GET['action'] == 'logout'))
	{
		setcookie('GAME_USER_LOGGED', null, time()-14400*10, '/', '.' . $__URL__, false, true);
		
		ob_clean();
		
		header('Location: /', true, 303);
		
		ob_end_flush();
		
		exit();	
	}
	else	
	if ((array_key_exists('GAME_USER_LOGGED', $_COOKIE)) && (!empty($_COOKIE['GAME_USER_LOGGED'])))
	{
		$user = json_decode($_COOKIE['GAME_USER_LOGGED'], true);	
		$isLoggedTester = true;
	}
	else	
	if ((array_key_exists('token', $_POST)) && (!empty($_POST['token'])))
	{
		$s = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
		$user = json_decode($s, true);
		
		setcookie('GAME_USER_LOGGED', $s, time() + 6 * 3600, '/', '.' . $__URL__, false, true);
		
		ob_clean();
		header('Location: ' . $_SERVER["REQUEST_URI"], true, 303);
		
		ob_end_flush();
		
		exit();		

		//$user['network'] - соц. сеть, через которую авторизовался пользователь
		//$user['identity'] - уникальная строка определяющая конкретного пользователя соц. сети
		//$user['first_name'] - имя пользователя
		//$user['last_name'] - фамилия пользователя
	}
	
?>
<!-- CHANGE THIS TITLE TAG -->
<title>Defcon: Assault - Бесплатная коллекционная карточная игра. ККИ. Free Collection Card Battle Game</title>

<!-- media-queries.js -->
<!--[if lt IE 9]>
	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
<!-- html5.js -->
<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->


<link href="font/stylesheet.css" rel="stylesheet" type="text/css" />	
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />
<link href="css/styles.css" rel="stylesheet" type="text/css" />
<link href="css/media-queries.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="fancybox/jquery.fancybox.css" media="screen" />


<!--<link rel="stylesheet" type="text/css" href="timeline/css/style.css" media="screen" />-->
<link rel="stylesheet" type="text/css" href="timeline/css/light.css" media="screen" />




<meta name="viewport" content="width=device-width" />
 
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

<link href='http://fonts.googleapis.com/css?family=Exo:400,800' rel='stylesheet' type='text/css'>

<!-- direct place uLogin -->
<script>
if("undefined"==typeof uLogin||!uLogin.uLogin){var uLogin={protocol:location.href.match(/^https/i)?"https":"http",host:encodeURIComponent(location.host),uLogin:!0,ids:[],lang:(navigator.language?navigator.language:navigator.userLanguage).substr(0,2),supportedLanguages:["en","ru","uk"],dialog:"",close:"",lightbox:"",dialogSocket:"",pixel:"//x.ulogix.ru/?rand=[rand]&u=[u]&r=[r]",scrollTimer:!1,providerNames:"vkontakte odnoklassniki mailru facebook twitter google yandex livejournal openid lastfm linkedin liveid soundcloud steam flickr youtube vimeo webmoney foursquare tumblr googleplus".split(" "),states:["ready","receive","open","close"],asyncCheckID:!1,get:function(a){return document.getElementById(a);},exists:function(a){return"undefined"!=typeof a;},add:function(a,b,c){a.addEventListener?a.addEventListener(b,function(b){c(a,b);},!1):a.attachEvent?a.attachEvent("on"+b,function(b){c(a,b);}):a["on"+b]=function(b){c(a,b);};},is_encoded:function(a){return decodeURIComponent(a)!=a;},genID:function(){for(var a=new Date,b=a.getTime()+Math.floor(100000*Math.random());uLogin.get("ul_"+b);){b=a.getTime()+Math.floor(100000*Math.random());}return"ul_"+b;},show:function(a){this.exists(a)&&(a.style.visibility="visible");},hide:function(a){this.exists(a)&&(a.style.visibility="hidden");},parse:function(a){var b={};if(!a){return b;}var c=a.split("&"),c=1<c.length?c:a.split(";");for(a=0;a<c.length;a++){var d=c[a].split("=");d[0]&&(d[0]=d[0].trim());d[1]&&(d[1]=d[1].trim());b[d[0]]=d[1];}return b;},def:function(a,b,c){return this.exists(a[b])?a[b]:c;},scrollTop:function(){return window.pageYOffset||document.documentElement.scrollTop||document.body.scrollTop;},scrollLeft:function(){return window.pageXOffset||document.documentElement.scrollLeft||document.body.scrollLeft;},clientWidth:function(){var a=0;"[object Opera]"==Object.prototype.toString.call(window.opera)&&9.5>window.parseFloat(window.opera.version())?a=document.body.clientWidth:window.innerWidth&&(a=window.innerWidth);uLogin.isIE()&&(a=document.documentElement.clientWidth);return a;},clientHeight:function(){var a=0;"[object Opera]"==Object.prototype.toString.call(window.opera)&&9.5>window.parseFloat(window.opera.version())?a=document.body.clientHeight:window.innerHeight&&(a=window.innerHeight);uLogin.isIE()&&(a=document.documentElement.clientHeight);return a;},hideAll:function(){this.lightbox&&(this.hide(this.lightbox),this.hide(this.dialog),this.hide(this.close));for(var a=0;a<this.ids.length;a++){this.ids[a].showed=!1,this.hide(this.ids[a].hiddenW),this.hide(this.ids[a].hiddenA);}},isIE:function(){if(/MSIE (\d+\.\d+);/.test(navigator.userAgent)){var a=new Number(RegExp.$1);if(9>a){return a;}}return !1;},extraction:function(){if(uLogin.extraction.disabled){return !1;}for(var a=0,b=[],c=[],d=document.getElementsByTagName("div"),e=document.getElementsByTagName("a");e[a];){e[a]&&(b[a]=e[a]),a++;}for(a=0;d[a];){d[a]&&(c[a]=d[a]),a++;}for(a=0;c[a]||b[a];){c[a]&&uLogin.addWidget(c[a]),b[a]&&uLogin.addWidget(b[a]),a++;}},addWidget:function(a){var b="",c="";a.id&&(b=a.id,c=(c=a.getAttribute("x-ulogin-params"))?c:a.getAttribute("data-ulogin"));if(b&&c){a=this.parse(c);for(var c=!0,d=0;d<this.ids.length;d++){if(b==this.ids[d].id){c=!1;break;}}c&&this.setWidgetProperties(b,this.ids.length,a);}},initWidget:function(a){if(a){var b=uLogin.get(a);if(b){var c=b.getAttribute("x-ulogin-params");if(c=c?c:b.getAttribute("data-ulogin")){var d=uLogin.parse(c),b=!1,e;for(e=0;e<uLogin.ids.length;e++){if(a==uLogin.ids[e].id){b=!0;break;}}b?uLogin.ids[e].initCheck||(uLogin.ids[e].initCheck=window.setInterval(function(){uLogin.ids[e].done&&(window.clearInterval(uLogin.ids[e].initCheck),uLogin.setWidgetProperties(a,e,d));},100)):(e=uLogin.ids.length,uLogin.setWidgetProperties(a,e,d));}}}},setWidgetProperties:function(a,b,c){this.ids[b]={id:a,dropTimer:!1,initCheck:!1,done:!1,type:this.def(c,"display",""),providers:this.def(c,"providers",""),hidden:this.def(c,"hidden",""),redirect_uri:this.def(c,"redirect_uri",""),callback:this.def(c,"callback",""),fields:this.def(c,"fields","first_name,last_name"),optional:this.def(c,"optional",""),color:this.def(c,"color","fff"),opacity:this.def(c,"opacity","75"),verify:this.def(c,"verify",""),lang:this.def(c,"lang",uLogin.lang),state:"",ready_func:[],receive_func:[],open_func:[],close_func:[]};this.ids[b].redirect_uri=uLogin.is_encoded(this.ids[b].redirect_uri)?this.ids[b].redirect_uri.replace(/\//g,"%2F"):encodeURIComponent(this.ids[b].redirect_uri);-1==uLogin.supportedLanguages.indexOf(this.ids[b].lang)&&(this.ids[b].lang=uLogin.lang);"undefined"==typeof easyXDM&&1>=this.ids.length&&(a=document.createElement("script"),a.src="//ulogin.ru/js/easyXDM.min.js",document.body.appendChild(a));var d=window.setInterval(function(){if("undefined"!=typeof easyXDM&&"undefined"!=typeof easyXDM.Socket){switch(window.clearInterval(d),c.display){case"small":case"panel":uLogin.ids[b].listener_id=!1;uLogin.initPanel(b);break;case"window":uLogin.initWindow(b);break;case"buttons":uLogin.initButtons(b,uLogin.def(c,"receiver",uLogin.ids[b].redirect_uri));break;default:uLogin.ids.splice(b,b);}}},100);},sendPixel:function(){if(uLogin.pixel){setTimeout(function(){var img=new Image(),src=uLogin.pixel;src=src.replace("[rand]",(parseInt(Math.random()*100000000)));src=src.replace("[u]",encodeURIComponent(location.href));src=src.replace("[r]",encodeURIComponent(document.referrer?document.referrer:""));img.src=src;uLogin.pixel=false;},1000);}},init:function(a){uLogin.extraction.disabled=!1;document.readyState==="complete"&&uLogin.sendPixel();""==a&&(uLogin.add(window,"load",function(){setTimeout(function(){clearInterval(uLogin.asyncCheckID);},uLogin.extraction.disabled?100:0);uLogin.sendPixel();uLogin.extraction();}),a=document.getElementsByTagName("script"),a=a[a.length-1].src,-1==a.indexOf("?")&&(a+="?"),a=a.substr(a.indexOf("?")+1));if(""!=a){var b=this.parse(a);if(b.display){var c=this.def(b,"id","uLogin");if(this.get(c)){cont=!0;for(a=0;a<this.ids.length;a++){c==this.ids[a].id&&(cont=!1);}cont&&(c=this.ids.length,this.setWidgetProperties(this.def(b,"id","uLogin"),c,b));}else{window.setTimeout('uLogin.init("'+a+'")',1000);}}}},initSocket:function(a,b,c,d){return new easyXDM.Socket({remote:a,swf:uLogin.isIE()?"https://ulogin.ru/js/easyxdm.swf":"",props:c,container:b,onMessage:function(a){-1<uLogin.states.indexOf(a)?uLogin._changeState(d,a):"undefined"!=typeof window[uLogin.ids[d].callback]&&(window[uLogin.ids[d].callback](a),uLogin.dialog&&(uLogin.lightbox.style.display="none",uLogin.dialog.style.display="none",uLogin.hide(uLogin.close)));}});},getWidgetNumber:function(a){for(var b=0;b<uLogin.ids.length;b++){if(a==uLogin.ids[b].id){return b;}}return NaN;},initWindow:function(a){var b=document.createElement("div");if(""==this.lightbox||""==this.close||""==this.dialog){b.innerHTML='<div style="position:absolute;z-index:9999;left:0;top:0;margin:0;padding:0;width:100%;height:100%;background:#'+this.ids[a].color+";opacity:0."+this.ids[a].opacity+";filter:progid:DXImageTransform.Microsoft.Alpha(opacity="+this.ids[a].opacity+');display:none;"></div>';this.lightbox=b.firstChild;b.innerHTML='<div id = "'+uLogin.genID()+'" style="position:absolute;z-index:9999;left:0;top:0;margin:0;padding:0;width:564px;height:358px;border:10px solid #666;border-radius:8px;display:none;"></div>';this.dialog=b.firstChild;b.innerHTML='<img style="width:30px;height:30px;position:absolute;z-index:9999;border:0px;left:0;top:0;margin:0;padding:0;background:url(https://ulogin.ru/img/x.png);cursor:pointer;visibility:hidden" src="https://ulogin.ru/img/blank.gif"/>';this.close=b.firstChild;this.add(this.close,"click",function(){uLogin.lightbox.style.display="none";uLogin.dialog.style.display="none";uLogin.hide(uLogin.close);});this.add(this.lightbox,"click",function(){uLogin.lightbox.style.display="none";uLogin.dialog.style.display="none";uLogin.hide(uLogin.close);});this.add(this.close,"mouseover",function(a){a.style.background="url(https://ulogin.ru/img/x_.png)";});this.add(this.close,"mouseout",function(a){a.style.background="url(https://ulogin.ru/img/x.png)";});document.body.appendChild(this.lightbox);document.body.appendChild(this.dialog);document.body.appendChild(this.close);var b=this.get(this.ids[a].id).getElementsByTagName("img")[0],c="ru"==this.ids[a].lang?"https://ulogin.ru/img/button.png":"https://ulogin.ru/img/"+this.ids[a].lang+"/button.png",d="ru"==this.ids[a].lang?"https://ulogin.ru/img/button_.png":"https://ulogin.ru/img/"+this.ids[a].lang+"/button_.png";b&&(b.src=c,b.style.border="none",this.add(b,"mouseover",function(a){a.src!=d&&(a.src=d);}),this.add(b,"mouseout",function(a){a.src!=c&&(a.src=c);}));}this.ids[a].done||(this.add(this.get(this.ids[a].id),"click",function(a,b){b.preventDefault?b.preventDefault():b.returnValue=!1;var c=a.id?a:b.srcElement;c&&uLogin.showWindow(c.id);return !1;}),uLogin.add(window,"scroll",function(){uLogin.onMoveWindow();}),uLogin.add(window,"resize",function(){uLogin.onMoveWindow();}),this.ids[a].done=!0);},onMoveWindow:function(){uLogin.lightbox.style.left=uLogin.scrollLeft()+"px";uLogin.lightbox.style.top=uLogin.scrollTop()+"px";uLogin.scrollTimer&&window.clearTimeout(uLogin.scrollTimer);uLogin.scrollTimer=window.setTimeout(uLogin.moveWindow,200);},showWindow:function(a){a=uLogin.getWidgetNumber(a);var b="https://ulogin.ru/window.html?id="+a+"&redirect_uri="+uLogin.ids[a].redirect_uri+"&callback="+uLogin.ids[a].callback+"&fields="+uLogin.ids[a].fields+"&optional="+uLogin.ids[a].optional,b=b+("&protocol="+uLogin.protocol),b=b+("&host="+uLogin.host),b=b+("&lang="+this.ids[a].lang),b=b+("&verify="+this.ids[a].verify);""!=uLogin.dialogSocket&&uLogin.dialogSocket.destroy();uLogin.dialogSocket=uLogin.initSocket(b,uLogin.dialog.getAttribute("id"),{style:{margin:"0",padding:"0",background:"#fff",width:"564px",height:"358px",border:"0",overflow:"hidden"},frameBorder:"0"},a);uLogin.lightbox.style.left=uLogin.scrollLeft()+"px";uLogin.lightbox.style.top=uLogin.scrollTop()+"px";uLogin.dialog.style.left=Math.floor(uLogin.scrollLeft()+(uLogin.clientWidth()-564)/2)+"px";uLogin.dialog.style.top=Math.floor(uLogin.scrollTop()+(uLogin.clientHeight()-358)/2)+"px";uLogin.close.style.left=Math.floor(uLogin.scrollLeft()+(uLogin.clientWidth()+562)/2)+"px";uLogin.close.style.top=Math.floor(uLogin.scrollTop()+(uLogin.clientHeight()-374)/2)+"px";uLogin.lightbox.style.display="block";uLogin.dialog.style.display="block";uLogin.lightbox.style.visibility="";uLogin.dialog.style.visibility="";uLogin.show(uLogin.close);},moveWindow:function(){for(var a=(Math.floor(uLogin.scrollLeft()+(uLogin.clientWidth()-564)/2)-new Number(uLogin.dialog.style.left.slice(0,-2)))/10,b=(Math.floor(uLogin.scrollTop()+(uLogin.clientHeight()-358)/2)-new Number(uLogin.dialog.style.top.slice(0,-2)))/10,c=(Math.floor(uLogin.scrollLeft()+(uLogin.clientWidth()+562)/2)-new Number(uLogin.close.style.left.slice(0,-2)))/10,d=(Math.floor(uLogin.scrollTop()+(uLogin.clientHeight()-374)/2)-new Number(uLogin.close.style.top.slice(0,-2)))/10,e=0;10>e;e++){uLogin.dialog.style.left=a+new Number(uLogin.dialog.style.left.slice(0,-2))+"px",uLogin.dialog.style.top=b+new Number(uLogin.dialog.style.top.slice(0,-2))+"px",uLogin.close.style.left=c+new Number(uLogin.close.style.left.slice(0,-2))+"px",uLogin.close.style.top=d+new Number(uLogin.close.style.top.slice(0,-2))+"px";}},initPanel:function(a){function b(){uLogin.ids[a].listener_id&&uLogin.removeStateListener(uLogin.ids[a].id,uLogin.ids[a].listener_id,"ready");if(!c&&""!=uLogin.ids[a].hidden&&!uLogin.ids[a].done){var b=document.createElement("div"),d=uLogin.ids[a].opacity;b.innerHTML='<img src="https://ulogin.ru/img/blank.gif" style="position:relative;width:'+e+"px;height:"+e+"px;margin:"+g+";cursor:pointer;background:"+h+';vertical-align:top;border:0px;"/>';uLogin.add(b.firstChild,"mouseover",function(b){uLogin.ids[a].showed=!1;uLogin.dropdownDelayed(a,j);b.style.filter="alpha(opacity="+d+") progid:DXImageTransform.Microsoft.AlphaImageLoader(src=transparent.png, sizingMethod='crop')";b.style.opacity=parseFloat(d)/100;});uLogin.add(b.firstChild,"mouseout",function(b){uLogin.ids[a].showed=!0;uLogin.dropdownDelayed(a,j);b.style.filter="";b.style.opacity="";});uLogin.add(b.firstChild,"click",function(){uLogin.dropdown(a,j);});uLogin.ids[a].drop=b.firstChild;uLogin.get(uLogin.ids[a].id).appendChild(uLogin.ids[a].drop);uLogin.initDrop(a);uLogin.ids[a].listener_id=uLogin.setStateListener(uLogin.ids[a].id,"ready",function(){uLogin.ids[a].done=!0;uLogin.removeStateListener(uLogin.ids[a].id,uLogin.ids[a].listener_id,"ready");});}else{if(""==uLogin.ids[a].hidden||c){uLogin.ids[a].done=!0;}}}uLogin.get(uLogin.ids[a].id).innerHTML="";var c=!0,d="small"==uLogin.ids[a].type?21:42,e="small"==uLogin.ids[a].type?16:32,g="small"==uLogin.ids[a].type?"0 5px 0 0":"0 10px 0 0",h="small"==uLogin.ids[a].type?"url(https://ulogin.ru/img/small7.png) 0 0":"url(https://ulogin.ru/img/panel7.png) 0 -3px",j="small"==uLogin.ids[a].type?1:2;if(this.ids[a].providers){document.createElement("div");var f="https://ulogin.ru/panel.html?id="+a+"&display="+j+"&redirect_uri="+this.ids[a].redirect_uri+"&callback="+this.ids[a].callback+"&providers="+this.ids[a].providers+"&fields="+this.ids[a].fields+"&optional="+this.ids[a].optional,f=f+("&protocol="+uLogin.protocol),f=f+("&host="+uLogin.host),f=f+("&lang="+this.ids[a].lang),f=f+("&verify="+this.ids[a].verify);uLogin.initSocket(f,uLogin.ids[a].id,{style:{display:"inline-block",margin:"0",padding:"0",width:this.ids[a].providers.split(",").length*d+"px",height:e+"px",border:"0",overflow:"hidden"},frameBorder:"0",allowTransparency:"true"},a);if(this.ids[a].hidden){var d=this.ids[a].providers.split(","),k;for(k in this.providerNames){if(!d[k]){c=!1;break;}}}else{c=!1;}}else{c=!1;}this.ids[a].providers?uLogin.ids[a].listener_id=uLogin.setStateListener(uLogin.ids[a].id,"ready",b):b();},initDrop:function(a){if(""!=this.ids[a].hidden){var b=document.createElement("div"),c=this.get(this.ids[a].id),d=uLogin.genID();if("other"==this.ids[a].hidden){for(var e=this.providerNames.slice(0),g=this.ids[a].providers.split(","),h=0;h<g.length;h++){e.splice(e.indexOf(g[h]),1);}this.ids[a].hidden=e.toString();}b.innerHTML='<div id = "'+d+'" style="position:absolute;z-index:9999;left:100px;top:200px;margin:0;padding:0;width:128px;height:'+(23*this.ids[a].hidden.split(",").length+-2)+'px;border:5px solid #666;border-radius:4px;visibility:hidden"></div>';this.ids[a].hiddenW=b.firstChild;c.appendChild(this.ids[a].hiddenW);e="https://ulogin.ru/drop.html?id="+a+"&redirect_uri="+this.ids[a].redirect_uri+"&callback="+this.ids[a].callback+"&providers="+this.ids[a].hidden+"&fields="+this.ids[a].fields+"&optional="+uLogin.ids[a].optional;e+="&protocol="+uLogin.protocol;e+="&host="+uLogin.host;e+="&lang="+this.ids[a].lang;e+="&verify="+this.ids[a].verify;uLogin.initSocket(e,d,{style:{position:"relative",margin:"0",padding:"0",background:"#fff",width:"128px",height:23*this.ids[a].hidden.split(",").length-2+"px",border:"0",overflow:"hidden"},frameBorder:"0"},a);b.innerHTML='<div style="position:absolute;background:#000;left:82px;top:'+(23*this.ids[a].hidden.split(",").length-7)+'px;margin:0;padding:0;width:41px;height:13px;border:5px solid #666;border-radius:0px;text-align:center"><a href="https://ulogin.ru/" target="_blank" style="display:block;margin:0px;width:41px;height:13px;background:url(https://ulogin.ru/img/text.png) no-repeat;"></a></div>';this.ids[a].hiddenW.appendChild(b.firstChild);b.innerHTML='<img src="https://ulogin.ru/img/link.png" style="width:8px;height:4px;position:absolute;z-index:9999;margin:0;padding:0;visibility:hidden"/>';this.ids[a].hiddenA=b.firstChild;c.appendChild(this.ids[a].hiddenA);this.ids[a].showed=!1;this.add(document.body,"click",function(a,b){b.target||(b.target=b.srcElement);for(var c=0;c<uLogin.ids.length;c++){b.target!=uLogin.ids[c].drop&&(uLogin.hide(uLogin.ids[c].hiddenW),uLogin.hide(uLogin.ids[c].hiddenA));}});uLogin.ids[a].hiddenW&&uLogin.ids[a].hiddenA&&(this.add(uLogin.ids[a].hiddenW,"mouseout",function(){uLogin.dropdownDelayed(a,0);}),this.add(uLogin.ids[a].hiddenA,"mouseout",function(){uLogin.dropdownDelayed(a,0);}),this.add(uLogin.ids[a].hiddenW,"mouseover",function(){uLogin.clearDropTimer(a);}),this.add(uLogin.ids[a].hiddenA,"mouseover",function(){uLogin.clearDropTimer(a);}));}},showDrop:function(a,b){if(uLogin.ids[a].hiddenW||uLogin.ids[a].hiddenA){if(uLogin.ids[a].showed||0==b){uLogin.ids[a].showed=!1,uLogin.hide(uLogin.ids[a].hiddenW),uLogin.hide(uLogin.ids[a].hiddenA);}else{uLogin.ids[a].showed=!0;var c,d,e=uLogin.ids[a].drop;c=0+e.offsetLeft;d=0+e.offsetTop;c-=e.scrollLeft;d-=e.scrollTop;uLogin.ids[a].hiddenW.style.left=c-(1==b?100:106)+"px";uLogin.ids[a].hiddenW.style.top=d+(1==b?21:37)+"px";uLogin.ids[a].hiddenA.style.left=c+(1==b?4:12)+"px";uLogin.ids[a].hiddenA.style.top=d+(1==b?17:33)+"px";uLogin.show(uLogin.ids[a].hiddenA);uLogin.show(uLogin.ids[a].hiddenW);}}},clearDropTimer:function(a){uLogin.ids[a].dropTimer&&window.clearTimeout(uLogin.ids[a].dropTimer);},dropdown:function(a,b){uLogin.clearDropTimer(a);uLogin.showDrop(a,b);},dropdownDelayed:function(a,b){uLogin.clearDropTimer(a);uLogin.ids[a].dropTimer=window.setTimeout(function(){uLogin.showDrop(a,b);},600);},initButtons:function(a,b){var c=uLogin.get(uLogin.ids[a].id);b=uLogin.is_encoded(b)?b.replace(/\//g,"%2F"):encodeURIComponent(b);uLogin._proceedChildren(c,uLogin._initButton,a,b);uLogin._changeState(a,uLogin.states[0]);uLogin.ids[a].done=!0;},_proceedChildren:function(a,b,c,d){a=a.childNodes;for(var e=0,e=0;e<a.length;e++){var g=a[e];g.getAttribute&&b(g,c,d);uLogin._proceedChildren(g,b,c,d);}},_initButton:function(a,b,c){var d=a.getAttribute("x-ulogin-button");if(d&&-1<uLogin.providerNames.indexOf(d)){if((c.match(/^https/i)?"https":"http")!=uLogin.protocol){e=":";d=c.split(e);if(1==d.length){var e="%3A",d=c.split(e);}d.splice(0,1);c=uLogin.protocol+e+d.join(e);}uLogin.add(a,"mouseover",function(a){var c=uLogin.ids[b].opacity;a.style.filter="alpha(opacity="+c+") progid:DXImageTransform.Microsoft.AlphaImageLoader(src=transparent.png, sizingMethod='crop')";a.style.opacity=parseFloat(c)/100;});uLogin.add(a,"mouseout",function(a){a.style.filter="";a.style.opacity="";});uLogin.add(a,"click",function(a){a="https://ulogin.ru/auth.php?name="+a.getAttribute("x-ulogin-button")+"&window=3&lang="+uLogin.lang+"&fields="+uLogin.ids[b].fields+"&optional="+uLogin.ids[b].optional+"&redirect_uri="+uLogin.ids[b].redirect_uri+"&verify="+uLogin.ids[b].verify+"&callback="+uLogin.ids[b].callback+"&screen="+screen.width+"x"+screen.height+"&q="+c;uLogin._changeState(b,uLogin.states[1]);var d=window.open(a,"uLogin","width=800,height=600,left="+(screen.width-800)/2+",top="+(screen.height-600)/2),e=window.setInterval(function(){d&&d.closed&&(window.clearInterval(e),uLogin._changeState(b,uLogin.states[0]));},50);});}},checkCurrentWidgets:function(){for(var a=0;uLogin.ids[a];){if("window"!=uLogin.ids[a].type){var b=uLogin.get(uLogin.ids[a].id);b&&!b.getElementsByTagName("iframe").length&&uLogin.ids[a].done&&uLogin.initWidget(uLogin.ids[a].id);}else{uLogin.initWindow(a);}a++;}},customInit:function(){uLogin.extraction.disabled=!0;for(var a=0;a<arguments.length;a++){var b=uLogin.get(arguments[a]);if(!b||!arguments[a]){return console.log('uLogin ERROR (customInit): Element with ID="'+arguments[a]+'" not found'),!1;}uLogin.addWidget(b);}},checkAsyncWidgets:function(){var a=uLogin.get("ulogin")||uLogin.get("uLogin");a&&a.id&&(uLogin.addWidget(a),clearInterval(uLogin.asyncCheckID));},setStateListener:function(a,b,c){var d=!1;a=uLogin.getWidgetNumber(a);if(NaN!=a&&uLogin.ids[a]){switch(b){case"ready":d=uLogin.ids[a].ready_func.push(c);break;case"receive":d=uLogin.ids[a].receive_func.push(c);break;case"open":d=uLogin.ids[a].open_func.push(c);break;case"close":d=uLogin.ids[a].close_func.push(c);}}return d-1;},removeStateListener:function(a,b,c){a=uLogin.getWidgetNumber(a);if(NaN!=a&&-1<uLogin.states.indexOf(c)){switch(c){case"ready":uLogin.ids[a].ready_func.length>=b&&uLogin.ids[a].ready_func.splice(b,1);break;case"receive":uLogin.ids[a].receive_func.length>=b&&uLogin.ids[a].receive_func.splice(b,1);break;case"open":uLogin.ids[a].open_func.length>=b&&uLogin.ids[a].open_func.splice(b,1);break;case"close":uLogin.ids[a].close_func.length>b&&uLogin.ids[a].close_func.splice(b,1);}}},_changeState:function(a,b){if(uLogin.ids[a]){uLogin.ids[a].state=b;var c=0;switch(b){case"ready":for(;uLogin.ids[a].ready_func[c];){uLogin.ids[a].ready_func[c](),c++;}break;case"receive":for(;uLogin.ids[a].receive_func[c];){uLogin.ids[a].receive_func[c](),c++;}break;case"open":for(;uLogin.ids[a].open_func[c];){uLogin.ids[a].open_func[c](),c++;}break;case"close":for(;uLogin.ids[a].close_func[c];){uLogin.ids[a].close_func[c](),c++;}}}}};Array.indexOf||(Array.prototype.indexOf=function(a){for(var b=0;b<this.length;b++){if(this[b]==a){return b;}}return -1;});String.prototype.trim||(String.prototype.trim=function(){return this.replace(/^\s+|\s+$/g,"");});-1==uLogin.supportedLanguages.indexOf(uLogin.lang)&&(uLogin.lang=uLogin.supportedLanguages[0]);uLogin.init("undefined"!=typeof uLogin_query?uLogin_query:"");uLogin.asyncCheckID=setInterval(function(){uLogin.checkAsyncWidgets();},20);setInterval(function(){uLogin.checkCurrentWidgets();},500);}function receiver(a,b){window[b](a);}function redirect(a,b){var c=document.createElement("form");c.action=decodeURIComponent(b);c.method="post";c.target="_top";c.style.display="none";var d=document.createElement("input");d.type="hidden";d.name="token";d.value=a;c.appendChild(d);document.body.appendChild(c);c.submit();}
</script>
</head>

<body data-spy="scroll" style="overflow-x:hidden; !important;">

<!-- TOP MENU NAVIGATION -->
<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
	
			<a class="brand pull-left hidden-phone hidden-tablet" href="#">
				Defcon: Assault - Коллекционная Карточная Игра
			</a>
			
			<a class="brand pull-left visible-mobile visible-tablet hidden-desktop" href="#">
				Defcon: Assault
			</a>
	
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
		
			<div class="nav-collapse collapse">
				<ul id="nav-list" class="nav pull-right">
					<li><a href="#home"><i class="icon-home"></i></a></li>
					<li><a href="#about">О игре</a></li>
					<li><a href="#updates">Новости</a></li>
					<li><a href="#screenshots">Скриншоты</a></li>
					<li><a href="#contact"><span class="version-top label label-success">Альфа-тест</span></a></li>
					
				</ul>
			</div>
		
		</div>
	</div>
</div>


<!-- MAIN CONTENT -->
<div class="container content container-fluid" id="home">



	<!-- HOME -->
	<div class="row-fluid">
  
		<!-- PHONES IMAGE FOR DESKTOP MEDIA QUERY -->
		<div class="span4 visible-desktop">
			<img src="img/phones.png">
			
			<center>
			
			<?php
				if ($isLoggedTester == false)
				{	// <!--<script src="//ulogin.ru/js/ulogin.js"></script>-->
					echo '<div class="alpha-login hidden-mobile hidden-tablet visible-desktop">' .
						 '<div id="uLogin" '.
'data-ulogin="display=panel;fields=first_name,last_name,photo,photo_big,email,country;providers=vkontakte,facebook,google,yandex,twitter,steam,mailru;hidden=;redirect_uri=http%3A%2F%2Fwww.defconassault.com"></div>' . 
						 '</div>' . 
						 '<div style="font-size:10px;"><span class="version-top label label-success">&nbsp;Вход&nbsp;</span> Только для альфа-тестеров</div>';
				}
				else
				{			
					echo '<div class="auth-login-testers">' . 
						 '<a class="btn btn-large btn-success" type="button" href="http://game.'.$__URL__.'/"><img src="'.$user['photo'].'" align="absmiddle" style="margin-right:2px;" class="img-rounded" /> Играть!</a>' . 
						 '<div><a href="/?action=logout" style="font-size:10px;">выйти из аккаунта</a></div></div>';
				}
			?>
			
			</center>
			
		</div>
	
		<!-- APP DETAILS -->
		<div class="span8">
	
			<!-- ICON -->
			<div class="visible-desktop" id="icon">
				<img src="img/app_icon.png" />
			</div>
			
			<!-- APP NAME -->
			<div id="app-name">
				<h1>Defcon: Assault</h1>
			</div>
            
			<!-- TAGLINE -->
			<div id="tagline">
				Коллекционная карточная игра. Тактические сражения. Бесплатно.
			</div>
		
			<!-- PHONES IMAGE FOR TABLET MEDIA QUERY -->
			<div class="hidden-desktop" id="phones">
				<img src="img/phones.png">
			</div>
            
			<!-- DESCRIPTION -->
			<div id="description" style="margin-bottom:1em;margin-top:1.5em;">
				Defcon:Assault это молниеносные сражения против ужастных пришельцев. Собирай армию, сражайся, получай деньги и опыт. Коллекционируй карты, собери свою уникальную колоду и сразись с другими игроками.
			</div>
            
			<!-- FEATURES -->
			<ul id="features">
				<li>Молниеносные тактические сражения</li>
				<li>Более 20 видов пехоты, военной техники и роботов</li>
				<li>Пять фракций: Правительство, Орден, Компания, Легион и Отступники</li>
				<li>PvE против чужих или PvP против игроков враждебных фракций</li>
				<li>Бесплатно! Игровые карты для сражения не требуют вложений!</li>
			</ul>
		
			<!-- DOWNLOAD & REQUIREMENT BOX -->
			<div class="download-box">
				<a href="#"><img src="img/available-on-the-app-store.png"></a>
			</div>
			<div class="download-box">
				<a href="#"><img src="img/android_app_on_play_logo_large.png"></a>
			</div>
			<div class="download-box">
				<strong>Требования:</strong><br>
				Любой iPhone, iPod touch или планшет iPad. Требуется iOS 4.0 или выше и доступ к Интернет через  WiFi, EDGE или 3G связь.
			</div>
			<div class="download-box">
				<strong>Требования:</strong><br>
				Смартфон или планшет с ОС Android 2.3 или выше и доступ к Интернет через  WiFi, EDGE или 3G связь.
			</div>
			
		</div>
	</div>
	
	
	
	<!-- ABOUT & UPDATES -->
	<div class="row-fluid" id="about">
	
		<div class="span6">
			<h2 class="page-title" id="scroll_up">
				О игре
				<a href="#home" class="arrow-top">
				<img src="img/arrow-top.png">
				</a>
			</h2>
			
			<p>
			В игровом мире идёт постоянная война между людьми и рассой чужих, инопланетных захватчиков. Однако, если перед лицом врага все люди смогли обьеденились, то внутри нас все так же преследуют конфликты между разными фракциями.
			</p>
			<p>Люди обьеденены в пять фракций: - Правительство, Орден, Компания, Легион и Отступники. Выбор фракции происходит после определенного количества игрового времени. В текущем видении, игрок только как менеджер-владелец своей армии (набора карт), без элементов ситибилдинга и т.п., то есть ни баз ни других построек. </p>
			<p>Основной игровой процесс заключается в формировании колоды (армии) - набора игровых карт, из которых он будет добирать карты на замену в случайной деке (в будущем - формировать свою деку для PvP), с которыми он пойдет в сражение. Сама игра - сражение с NPC и/или другими игроками в пошаговом режиме, а также исследование новых карт.
			</p>
		
		</div>
	
		<div class="span6 updates" id="updates">
			<h2 class="page-title" id="scroll_up">
				Updates
				<a href="#home" class="arrow-top">
				<img src="img/arrow-top.png">
				</a>
			</h2>
			
			<!-- UPDATES & RELEASE NOTES -->
			
			<h3 class="version">Версия 0.3</h3>
			<span class="release-date">15.03.2013</span>
			<ul>
				<li><span class="label new">NEW</span>Игра сменила название на Defcon:Assault</li>
				<li><span class="label fix">FIX</span>Новый промо-сайт игры</li>
			</ul>
			<hr>
			
			<h3 class="version">Версия 0.2</h3>
			<span class="release-date">февраль-март 2013</span>
			<ul>
				<li><span class="label new">NEW</span>Общая концепция проекта, дизайн-документ</li>
				<li><span class="label new">NEW</span>Отладка макета боевой системы</li>
			</ul>
			<hr>
		
			<h3 class="version">Версия 0.1</h3>
			<span class="release-date">февраль 2013</span>
			<ul>
				<li><span class="label label-info">NEW</span>Начало работы по проекту. Рабочее название: Frontlines War</li>
			</ul>
			
		</div>
	
	</div>
	
	
	
	<!-- SCREENSHOTS -->
	<div class="row-fluid" id="screenshots">
		
		<h2 class="page-title" id="scroll_up">
				Скриншоты
				<a href="#home" class="arrow-top">
				<img src="img/arrow-top.png">
				</a>
			</h2>
		
		<!-- SCREENSHOT IMAGES ROW 1-->
		<ul class="thumbnails">
			<li class="span3">
				<a href="img/media/01.png" rel="gallery" class="thumbnail">
				<img src="img/media/01.png" alt="">
				</a>
			</li>
		
			<li class="span3">
				<a href="img/media/02.png" rel="gallery" class="thumbnail">
				<img src="img/media/02.png" alt="">
				</a>
			</li>
			
			<li class="span3">
				<a href="img/media/03.png" rel="gallery" class="thumbnail">
				<img src="img/media/03.png" alt="">
				</a>
			</li>
 
			<li class="span3">
				<a href="img/media/04.png" rel="gallery" class="thumbnail">
				<img src="img/media/04.png" alt="">
				</a>
			</li>
		</ul>	
	</div>
	
	<!-- CONTACT -->
	<div class="row-fluid" id="contact">
	
		<h2 class="page-title" id="scroll_up">
				Заявка на тест
				<a href="#home" class="arrow-top">
				<img src="img/arrow-top.png">
				</a>
			</h2>
		
		<!-- CONTACT INFO -->
		<div class="span4" id="contact-info">
			<h3>Contact Us</h3>
			<p>FlexApp is free and thus unfortunately we cannot provide basic support for it. We simply don't have the time to answer everyone's questions.</p>
			<p>However, you may contact us about general business inquiries or to report bugs in the template!<p>
			<p><a href="mailto:contact@trippoinc.com">contact@trippoinc.com</a></p>
		</div>
		
		<!-- CONTACT FORM -->
		<div class="span7" id="contact-form">
			<form class="form-horizontal">
				<fieldset>
					<div class="control-group">
						<label class="control-label" for="name">Имя</label>
						<div class="controls">
							<input class="input-xlarge" type="text" id="name" placeholder="Алексей Иваныч">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="email">Email</label>
						<div class="controls">
							<input class="input-xlarge" type="text" id="email" placeholder="email@example.com">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="message">Расскажите о себе</label>
						<div class="controls">
							<textarea class="input-xlarge" rows="3" id="message" placeholder="Я играю в WoT:Generals и был там тестером теперь хочу к вам."></textarea>
						</div>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn btn-primary">Записаться на альфа-тестирование</button>
					</div>
				</fieldset>
			</form>
		</div>
		
	</div>
	
</div>


<!-- FOOTER -->
<div class="footer container container-fluid">
	<center>
	<div style="background-color:#777777;">
	
		<img class="img-rounded" src="/img/icons/android-64.png" /> &nbsp;
		<img class="img-rounded" src="/img/icons/apple-64.png" /> &nbsp;
		<img class="img-rounded" src="/img/icons/aws-64.png" /> &nbsp;
		<img class="img-rounded" src="/img/icons/bootstrap-64.png" /> &nbsp;
		<img class="img-rounded" src="/img/icons/css3-64.png" /> &nbsp;
		<img class="img-rounded" src="/img/icons/html5-64.png" /> &nbsp;
		<img class="img-rounded" src="/img/icons/facebook-64.png" /> &nbsp;
		<img class="img-rounded" src="/img/icons/jquery-64.png" /> &nbsp;
		<img class="img-rounded" src="/img/icons/php-64.png" /> &nbsp;
		<img class="img-rounded" src="/img/icons/tumblr-64.png" /> &nbsp;
		<img class="img-rounded" src="/img/icons/twitter-64.png" /> &nbsp;
		<img class="img-rounded" src="/img/icons/windows-64.png" /> &nbsp;
	
	</div>
	</center>
	
	<!-- COPYRIGHT - EDIT HOWEVER YOU WANT! -->
	<div id="copyright">
		Copyright &copy; 2010 - 2013 AGPsource.com &nbsp;&nbsp; Все права защищены. 
	</div>
	
	<!-- CREDIT - PLEASE LEAVE THIS LINK! -->
	<div id="credits">
		<a href="http://agpsource.com/games/defcon">Defcon:Assault</a> by <a href="http://agpsource.com">AGPsource Studio</a>.
	</div>

</div>

<!-- все необходимое для первой страницы -->
<script src="js.min.js"></script>
<script>
$(function(){
	$(".thumbnails a").attr('rel', 'gallery').fancybox();

	$("#nav-list li, #scroll_up").click(function(e) {
		e.preventDefault();
		 $('html, body').animate({
				scrollTop: $($(this).children("a").attr("href")).offset().top
		 },1500);
	 }); 
 });
</script>

</body>
</html>
<?php ob_end_flush(); ?>
