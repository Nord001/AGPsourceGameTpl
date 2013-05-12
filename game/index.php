<?php
/**
 * Created by JetBrains PhpStorm.
 * User: raiden
 * Date: 29.12.12
 * Time: 12:54
 * To change this template use File | Settings | File Templates.
 */

@error_reporting(E_ALL);
if(!ob_start("ob_gzhandler")) ob_start();

ini_set('date.timezone', 'GMT+0');

//загружаем инициализацию библиотек
require_once('../libs/loader.php');

//загружаем маппинг URL
require_once('../game/connectManager.php');

//для укорочения ссылок
$url = $config['Default'];

//получаем базовый профиль из куки 
if ((array_key_exists('GAME_USER_LOGGED', $_COOKIE)) && (!empty($_COOKIE['GAME_USER_LOGGED'])))
{
	$_user = Zend_Json::decode($_COOKIE['GAME_USER_LOGGED']);	
	
	$user = $db->fetchRow('SELECT * FROM user_accounts_tbl WHERE identity = '.$db->quote($_user['identity']).' LIMIT 1');
	
	if (empty($user))
	{
		ob_clean();
		header('Location: ' . $url['site']);
		exit();
	}
}
else
{
	ob_clean();
	header('Location: ' . $url['site']);
	exit();
}


	$session = Zend_Registry::getInstance()->get('session');
	
	if (!isset($session->uid))
	{
		$session->uid = $user['uid'];
		$session->identity = $user['identity'];
	}
	
	$session->user = $user;

//теперь проверим юзера 
$money_id = $db->fetchOne('SELECT _id FROM user_money_tbl WHERE uid = '.$db->quote($user['uid']).' LIMIT 1');
$money_acc = null;

if (empty($money_id))
{
	$db->query('INSERT INTO user_money_tbl SET uid = '.$db->quote($user['uid']).', account_type = \'$\', account_balance = 3000, last_updated_at = UNIX_TIMESTAMP() ');
	
	$money_id = $db->fetchOne('SELECT _id FROM user_money_tbl WHERE uid = '.$db->quote($user['uid']).' LIMIT 1');
}

$money_acc = $db->fetchRow('SELECT * FROM user_money_tbl WHERE _id = '.$db->quote($money_id).' LIMIT 1');


//теперь загрузить карты юзера 
//если нет, открыть для него их - user_cards_deka_tbl  таблица с открытыми картами. 
$res = $db->fetchAll('SELECT _id FROM user_cards_deka_tbl WHERE uid = '.$db->quote($user['uid']).' ');

if (empty($res))
{
	//у юзера нет деки 
	$sql = 'SELECT card_id FROM card_base_tbl WHERE card_balance_level != 0 AND card_cost_research = 0';
	$res = $db->fetchAll($sql);
	
	foreach($res as $x)
	{
		$sql = 'INSERT INTO user_cards_deka_tbl SET card_id = '.$db->quote($x['card_id']).', card_exp = 0, updated_at = UNIX_TIMESTAMP(), card_opened_at = UNIX_TIMESTAMP(), uid = '.$db->quote($user['uid']).' ';
		$db->query($sql);
	}
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $url['site_header']; ?></title>
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
    <meta name="format-detection" content="telephone=no">
	<link rel="icon" type="image/ico" href="<?php echo $url['static']; ?>/favicon.ico" />

	<!-- в бете используем Pubnub для коммуникации 
	<script src="http://cdn.pubnub.com/pubnub-3.4.2.min.js"></script>-->
	
	<script type="text/javascript" src="<?php echo $url['static']; ?>/js/PxLoader.js"></script>
	<script type="text/javascript" src="<?php echo $url['static']; ?>/js/PxLoaderImage.js"></script>
	
	<script>
		//предзагрузка изображений 
		var _imgLoader = new PxLoader();
		var _imgs = ['ui/loadingPage3.jpg','pages/background3.jpg','ui/cardsBtn.png','ui/arenaBtn.png',
		'ui/missionsBtn.png','ui/clansBtn.png','ui/liderboardBtn.png','ui/backBtn.png','ui/yourassaultteam.png',
		'ui/playBtn_inactive.png','ui/playBtn_active.png','ui/assault_team_text2.png','ui/enemy_text.png', 'ui/regroup_btn.png',
		'ui/battleBtn_active.png','ui/battleBtn_inactive.png','ui/all_cards.png','ui/level_1.png','ui/level_2.png',
		'ui/level_3.png','ui/level_4.png','cards/card_default.png','cards/card_attack_mask.png','cards/card_mask_block2.png',
		'cards/card_infantryman.png','cards/card_swordbearer.png','cards/card_stormtrooper.png','cards/card_engineer.png','cards/card_gladiator.png','cards/card_scout.png','cards/card_carabineer.png','cards/card_paratrooper.png','cards/card_monster_amphisbaena.png','cards/card_monsters_leveller.png','cards/card_monsters_bahamut.png','cards/card_monsters_сatoblepas.png','cards/card_monsters_hippogriff.png','cards/card_monsters_westerndragon.png','cards/card_monsters_kami.png','cards/card_monsters_carbuncle.png','cards/card_monsters_kujata.png','cards/card_mask_victory.png',
		'cards/card_mask_dead.png'];
		
		var pxImage = null;
		
		for (var i=0; i < _imgs.length; i++)
		{
			pxImage = new PxLoaderImage('<?php echo $url['static']; ?>/img/' + _imgs[i]);			
			_imgLoader.add(pxImage);
		}
			
	</script>
	

	<!-- add Head.js direct to page body (use head.css3.js bundle) -->
	<script>
	(function(f,w){function m(){}function g(a,b){if(a){"object"===typeof a&&(a=[].slice.call(a));for(var c=0,d=a.length;c<d;c++)b.call(a,a[c],c)}}function v(a,b){var c=Object.prototype.toString.call(b).slice(8,-1);return b!==w&&null!==b&&c===a}function k(a){return v("Function",a)}function h(a){a=a||m;a._done||(a(),a._done=1)}function n(a){var b={};if("object"===typeof a)for(var c in a)a[c]&&(b={name:c,url:a[c]});else b=a.split("/"),b=b[b.length-1],c=b.indexOf("?"),b={name:-1!==c?b.substring(0,c):b,url:a};
return(a=p[b.name])&&a.url===b.url?a:p[b.name]=b}function q(a){var a=a||p,b;for(b in a)if(a.hasOwnProperty(b)&&a[b].state!==r)return!1;return!0}function s(a,b){b=b||m;a.state===r?b():a.state===x?d.ready(a.name,b):a.state===y?a.onpreload.push(function(){s(a,b)}):(a.state=x,z(a,function(){a.state=r;b();g(l[a.name],function(a){h(a)});j&&q()&&g(l.ALL,function(a){h(a)})}))}function z(a,b){var b=b||m,c;/\.css[^\.]*$/.test(a.url)?(c=e.createElement("link"),c.type="text/"+(a.type||"css"),c.rel="stylesheet",
c.href=a.url):(c=e.createElement("script"),c.type="text/"+(a.type||"javascript"),c.src=a.url);c.onload=c.onreadystatechange=function(a){a=a||f.event;if("load"===a.type||/loaded|complete/.test(c.readyState)&&(!e.documentMode||9>e.documentMode))c.onload=c.onreadystatechange=c.onerror=null,b()};c.onerror=function(){c.onload=c.onreadystatechange=c.onerror=null;b()};c.async=!1;c.defer=!1;var d=e.head||e.getElementsByTagName("head")[0];d.insertBefore(c,d.lastChild)}function i(){e.body?j||(j=!0,g(A,function(a){h(a)})):
(f.clearTimeout(d.readyTimeout),d.readyTimeout=f.setTimeout(i,50))}function t(){e.addEventListener?(e.removeEventListener("DOMContentLoaded",t,!1),i()):"complete"===e.readyState&&(e.detachEvent("onreadystatechange",t),i())}var e=f.document,A=[],B=[],l={},p={},E="async"in e.createElement("script")||"MozAppearance"in e.documentElement.style||f.opera,C,j,D=f.head_conf&&f.head_conf.head||"head",d=f[D]=f[D]||function(){d.ready.apply(null,arguments)},y=1,x=3,r=4;d.load=E?function(){var a=arguments,b=a[a.length-
1],c={};k(b)||(b=null);g(a,function(d,e){d!==b&&(d=n(d),c[d.name]=d,s(d,b&&e===a.length-2?function(){q(c)&&h(b)}:null))});return d}:function(){var a=arguments,b=[].slice.call(a,1),c=b[0];if(!C)return B.push(function(){d.load.apply(null,a)}),d;c?(g(b,function(a){if(!k(a)){var b=n(a);b.state===w&&(b.state=y,b.onpreload=[],z({url:b.url,type:"cache"},function(){b.state=2;g(b.onpreload,function(a){a.call()})}))}}),s(n(a[0]),k(c)?c:function(){d.load.apply(null,b)})):s(n(a[0]));return d};d.js=d.load;d.test=
function(a,b,c,e){a="object"===typeof a?a:{test:a,success:b?v("Array",b)?b:[b]:!1,failure:c?v("Array",c)?c:[c]:!1,callback:e||m};(b=!!a.test)&&a.success?(a.success.push(a.callback),d.load.apply(null,a.success)):!b&&a.failure?(a.failure.push(a.callback),d.load.apply(null,a.failure)):e();return d};d.ready=function(a,b){if(a===e)return j?h(b):A.push(b),d;k(a)&&(b=a,a="ALL");if("string"!==typeof a||!k(b))return d;var c=p[a];if(c&&c.state===r||"ALL"===a&&q()&&j)return h(b),d;(c=l[a])?c.push(b):l[a]=[b];
return d};d.ready(e,function(){q()&&g(l.ALL,function(a){h(a)});d.feature&&d.feature("domloaded",!0)});if("complete"===e.readyState)i();else if(e.addEventListener)e.addEventListener("DOMContentLoaded",t,!1),f.addEventListener("load",i,!1);else{e.attachEvent("onreadystatechange",t);f.attachEvent("onload",i);var u=!1;try{u=null==f.frameElement&&e.documentElement}catch(F){}u&&u.doScroll&&function b(){if(!j){try{u.doScroll("left")}catch(c){f.clearTimeout(d.readyTimeout);d.readyTimeout=f.setTimeout(b,50);
return}i()}}()}setTimeout(function(){C=!0;g(B,function(b){b()})},300)})(window);
	</script>
	

    <!-- Le styles -->
    <link href="<?php echo $url['static']; ?>/css/bootstrap.min.css" rel="stylesheet">   
   
	<link href="<?php echo $url['static']; ?>/css/animate.css" rel="stylesheet">	
	<link href="<?php echo $url['static']; ?>/css/jquery.pnotify.css" rel="stylesheet">
	<link href="<?php echo $url['static']; ?>/font/stylesheet.css" rel="stylesheet">
	<!--<link href="<?php echo $url['static']; ?>/css/bootstrap-modal.css" rel="stylesheet">-->
	<link href="<?php echo $url['static']; ?>/css/jquery.percentageloader-0.1.css" rel="stylesheet">
	
	
	
	
	<!-- основные стили игры -->
	<link href="<?php echo $url['static']; ?>/css/game.css" rel="stylesheet">

	<link href="<?php echo $url['static']; ?>/css/bootstrap-responsive.min.css" rel="stylesheet">
	

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
		<script src="<?php echo $url['static']; ?>/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $url['static']; ?>/img/ico/apple-touch-icon-144-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $url['static']; ?>/img/ico/apple-touch-icon-114-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $url['static']; ?>/img/ico/apple-touch-icon-72-precomposed.png">
	<link rel="apple-touch-icon-precomposed" href="<?php echo $url['static']; ?>/img/ico/apple-touch-icon-57-precomposed.png">
	<link rel="shortcut icon" href="<?php echo $url['static']; ?>/img/ico/favicon.png">

	
    <script>
		//маппим урлы из конфига 
		var gUrl = <?php echo Zend_Json::encode($url); ?>;	
		
        var gPlayer = {
			type: 'human', //bully
			user: <?php if (!empty($_COOKIE['GAME_USER_LOGGED'])) echo $_COOKIE['GAME_USER_LOGGED']; else echo 'null'; ?>,
			money: <?php echo Zend_Json::encode($money_acc); ?>,
			id: 1,

			avatar: null, //карта аватара 
			deka: null, 
			
			status: 'online' 		
		};


    </script>
</head><!--   -->
<body>


<!-- Part 1: Wrap all page content here -->
<div id="wrap" style="max-width:1024px;min-width:1024px;">
	<!-- TOP MENU NAVIGATION 
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">		
			</div>
		</div>
	</div>
	-->
    <!-- Begin page content 2048×1536 ipad2 margin-top:41px; -->
    <div class="gameContainer" style="height:100%;">
		<div style="width:1024px;height:768px;background-repeat:no-repeat;background-image:url('<?php echo $url['static']; ?>/img/ui/loadingPage3.jpg');" class="startPageBg">
			
			<div style="" class="mainBtn_play mainBtn_play_position" onClick="gGame.ui.goTo('xmain');"></div>		
			
		</div>
		
		
		
		<div id="mainGameUI" class="carousel slide" style="width:100%;margin-bottom:0px;display:none;">
		  <div class="carousel-inner">
			<!-- основная вкладка -->
			<div class="active item itemMain" pageId="main" style="width:996px;height:745px;background-image:url('<?php echo $url['static']; ?>/img/pages/background3.jpg');">
				<div class="carousel-item-container">
<div class="row-fluid">
					<div class="span3 pull-left" style="top:50px;position:relative;left:45px;">
						<div style="margin-top:11px;">
							<img src="<?php if (isset($user['photo_big'])) echo $user['photo_big']; else echo $user['photo'];?>" align="absmiddle" width="200" height="200" class="img-polaroid" style="max-width:none;width:200px;height:200px;" /> 
						</div>
						<div style="text-align:left;margin-left:20px;">							 
							<h3 class="mainBtnText" style="font-size:2em;">								
								<?php if (isset($user['nickname'])) echo $user['nickname']; else echo $user['first_name']; ?>
							</h3>
						</div>
					</div>
					<div class="span8" style="top:50px;position:relative;padding-left:50px;font-weight:bold;">
						
						<div>
							<h3 class="mainBtnText" style="font-size:3.0em;">
								<img width="64" height="64" src="<?php echo $url['static']; ?>/img/icons/challenge_badge_05.png" align="absmiddle" /> Штурмовой лейтинант
							</h3>							
						</div>
						
						<div>
							<h3 class="mainBtnText" style="font-size:3.0em;">
								<img width="64" height="64" src="<?php echo $url['static']; ?>/img/icons/challenge_badge_31.png" align="absmiddle" /> Клан:   -=АСТАРТЕС=-
							</h3>						
						</div>
						
						<div>
							<h3 class="mainBtnText" style="font-size:3.0em;">
								<img src="<?php echo $url['static']; ?>/img/icons/dollar_currency_sign.png" align="absmiddle" /> 134 000 
							</h3>
						</div>
				
					</div>
</div>
					<div class="row-fluid" style="margin-top:30px;text-align:center;">
						<div class="span12" style="text-align:center;">
							<center>
								<div style="" class="mainBtn_battle" onClick="gGame.ui.goTo('battle#1');"></div>
							</center>
						</div>
						<!--
						<div class="span4" style="">
							<img width="145" src="<?php echo $url['static']; ?>/img/ui/trainingBtn.png" align="absmiddle" style="cursor:pointer;position:relative;top:23px;left:-55px;" class="pull-left" />
						</div>	
						-->				
					</div>
					
					<div class="row-fluid" style="margin-top:15px;text-align:center;position:relative;">
						<div class="span1"></div>
						<div class="span2">
							<img src="<?php echo $url['static']; ?>/img/ui/cardsBtn.png" align="absmiddle" style="cursor:pointer;" onClick="gGame.ui.goTo('cards');" />
						</div>
						
						<div class="span2">
							<img src="<?php echo $url['static']; ?>/img/ui/arenaBtn.png" align="absmiddle" style="cursor:pointer;" />
						</div>
						
						<div class="span2">
							<img src="<?php echo $url['static']; ?>/img/ui/missionsBtn.png" align="absmiddle" style="cursor:pointer;" />
						</div>
						
						<div class="span2">
							<img src="<?php echo $url['static']; ?>/img/ui/clansBtn.png" align="absmiddle" style="cursor:pointer;" />
						</div>
						
						<div class="span2">
							<img src="<?php echo $url['static']; ?>/img/ui/liderboardBtn.png" align="absmiddle" style="cursor:pointer;" />
						</div>												
						<div class="span1"></div>
					</div>
					
					<div class="row-fluid" style="margin-top:20px;text-align:center;">
						<!--
						<div>
							<img src="<?php echo $url['static']; ?>/img/ui/line3.png" align="absmiddle" />
							<h4 class="mainBtnText" style="font-size:1.5em;">
								<center>Моя элитная армия</center>
							</h4>	
						</div>
						-->
						<div class="span12" style="margin-left:0px;margin-top:10px;">
							<!--
							<center>	
								<img style="max-width:none;width:175px;" class="img-polaroid" src="<?php echo $url['static']; ?>/img/cards/card2_mini.jpg" align="absmiddle" />
								
								<img style="max-width:none;width:175px;" class="img-polaroid" src="<?php echo $url['static']; ?>/img/cards/card2_mini.jpg" align="absmiddle" />
								
								<img style="max-width:none;width:175px;" class="img-polaroid" src="<?php echo $url['static']; ?>/img/cards/card2_mini.jpg" align="absmiddle" />
								
								<img style="max-width:none;width:175px;" class="img-polaroid" src="<?php echo $url['static']; ?>/img/cards/card2_mini.jpg" align="absmiddle" />
								
								<img style="max-width:none;width:175px;" class="img-polaroid" src="<?php echo $url['static']; ?>/img/cards/card2_mini.jpg" align="absmiddle" />
								
							</center>
							-->
						</div>
					</div>
					
				</div>
			</div>
			
			<div class="item itemBattle" pageId="battle" style="width:996px;height:745px;background-image:url('<?php echo $url['static']; ?>/img/pages/background3.jpg');">
				<div class="carousel-item-container">
					<div class="row-fluid" style="text-align:center;">
						<div class="span2" style="margin-left:0px;">
							<img style="cursor:pointer;top:0px;left:-1px;height:70px;" class="pull-left" src="<?php echo $url['static']; ?>/img/ui/backBtn.png" align="" onClick="gGame.ui.goTo('main');" /> 
						</div>
						
						<div style="margin-top:10px;" class="span8">
							<img style="" class="" src="<?php echo $url['static']; ?>/img/ui/yourassaultteam.png" align="absmiddle" />
						</div>
						<div class="span2" style="margin-left:0px;">
							<img style="position:relative;cursor:pointer;top:13px;left:13px;" class="pull-right" src="<?php echo $url['static']; ?>/img/ui/playBtn_inactive.png" align="" onClick="gGame.ui.goTo('battle#fight');" />
						</div>
					</div>
					
					<div class="row-fluid cardsRow_1" style="margin-top:10px;text-align:center;">						
						<!--
						<div class="span4" style="margin-left:0px;">
							<center>	
								<img style="max-width:none;width:225px;" class="img-polaroid card cardBattleF1 itemTooltip" src="<?php echo $url['static']; ?>/img/cards/card2.jpg" align="absmiddle" data-cardId="1" title="<h4>Клик для замены карты на одну из своих</h4>" />									
							</center>
						</div>
						<div class="span4" style="margin-left:0px;">
							<center>
								<img style="max-width:none;width:225px;margin-right:20px;margin-left:20px;" class="img-polaroid card cardBattleF1 itemTooltip" src="<?php echo $url['static']; ?>/img/cards/card2.jpg" data-cardId="2" align="absmiddle" title="<h4>Клик для замены карты на одну из своих</h4>" />
							</center>
						</div>
						<div class="span4" style="margin-left:0px;">		
							<center>
								<img style="max-width:none;width:225px;" class="img-polaroid card cardBattleF1 itemTooltip" src="<?php echo $url['static']; ?>/img/cards/card2.jpg" align="absmiddle" data-cardId="3" title="<h4>Клик для замены карты на одну из своих</h4>" />
							</center>
						</div>
						-->
					</div>
					
					<div class="row-fluid cardsRow_2" style="margin-top:10px;text-align:center;">						
					</div>
					
				</div>
			</div>
			
			<div class="item itemCards" pageId="cards" style="width:996px;height:745px;background-image:url('<?php echo $url['static']; ?>/img/pages/background3.jpg');">
				<div class="carousel-item-container">
					<div class="row-fluid" style="margin-top:0px;text-align:center;">
						<div style="margin-top:10px;">
							<img style="cursor:pointer;position:absolute;top:0px;left:-1px;height:70px;" class="" src="<?php echo $url['static']; ?>/img/ui/backBtn.png" align="" onClick="gGame.ui.goTo('main');" /> 
							<img style="" class="" src="<?php echo $url['static']; ?>/img/ui/all_cards.png" align="absmiddle" /> 
						</div>
						
						<div class="row-fluid" style="margin-top:10px;text-align:center;padding-left:35px;">
		
							
							<div class="span3" style="margin-left:0px;text-align:center;">
								<img src="<?php echo $url['static']; ?>/img/ui/level_1.png" align="absmiddle" style="cursor:pointer;" width="150" onClick="gGame.cards.renderCards(1);" />
							</div>
							
							
							<div class="span3" style="margin-left:0px;text-align:center;">
								<img src="<?php echo $url['static']; ?>/img/ui/level_2.png" align="absmiddle" style="cursor:pointer;" width="150" onClick="gGame.cards.renderCards(2);" />
							</div>
							
							
							<div class="span3" style="margin-left:0px;">
								<img src="<?php echo $url['static']; ?>/img/ui/level_3.png" align="absmiddle" style="cursor:pointer;" width="150" onClick="gGame.cards.renderCards(3);" />
							</div>
							
						
							<div class="span3" style="margin-left:0px;">
								<img src="<?php echo $url['static']; ?>/img/ui/level_4.png" align="absmiddle" style="cursor:pointer;" width="150" onClick="gGame.cards.renderCards(4);" />
							</div>
						</div>						
					</div>
					
					<div class="row-fluid cardsRow_1" style="margin-top:30px;text-align:center;">						
						<!--
						<div class="span1" style="width:30px;"></div> 
						<div class="span3" style="margin-left:0px;">
							<center>	
								<img style="max-width:none;width:200px;" class="img-polaroid card cardBattleF1" src="<?php echo $url['static']; ?>/img/cards/card2.jpg" align="absmiddle" data-cardId="1" />
							</center>
						</div>
						<div class="span3" style="margin-left:0px;">
							<center>
								<img style="max-width:none;width:200px;margin-right:20px;margin-left:20px;" class="img-polaroid card cardBattleF1" src="<?php echo $url['static']; ?>/img/cards/card2.jpg" data-cardId="2" align="absmiddle" />
							</center>
						</div>
						<div class="span3" style="margin-left:0px;">		
							<center>
								<img style="max-width:none;width:200px;" class="img-polaroid card cardBattleF1" src="<?php echo $url['static']; ?>/img/cards/card2.jpg" align="absmiddle" data-cardId="3" />
							</center>
						</div>	
						<div class="span3" style="margin-left:0px;">		
							
							
							<center>
								<div style="width:150px;height:150px;top:0px;left:0px;" id="cardX1"></div>
								<img style="max-width:none;width:200px;position:relative;" class="card cardBlocked" src="<?php echo $url['static']; ?>/img/cards/card_mask_block.png" align="absmiddle" />
							
								<img style="max-width:none;width:200px;" class="img-polaroid card cardBattleF1" src="<?php echo $url['static']; ?>/img/cards/card2.jpg" align="absmiddle" data-cardId="3" />
							</center>
						</div>
						-->
					</div>
					
					<div class="row-fluid cardsRow_2" style="margin-top:10px;text-align:center;">						
						<!--<div class="span1" style="width:30px;"></div> 
						<div class="span3" style="margin-left:0px;">
							<center>	
								<img style="max-width:none;width:200px;" class="img-polaroid card cardBattleF1" src="<?php echo $url['static']; ?>/img/cards/card2.jpg" align="absmiddle" data-cardId="1" />
							</center>
						</div>
						<div class="span3" style="margin-left:0px;text-align:center;">
							<center>
								<img style="max-width:none;width:200px;margin-right:20px;margin-left:20px;" class="img-polaroid card cardBattleF1" src="<?php echo $url['static']; ?>/img/cards/card2.jpg" data-cardId="2" align="absmiddle" />
							</center>
						</div>
						<div class="span3" style="margin-left:0px;">		
							<center>
								<img style="max-width:none;width:200px;" class="img-polaroid card cardBattleF1" src="<?php echo $url['static']; ?>/img/cards/card2.jpg" align="absmiddle" data-cardId="3" />
							</center>
						</div>	
						<div class="span3" style="margin-left:0px;">		
							<center>
								<img style="max-width:none;width:200px;" class="img-polaroid card cardBattleF1" src="<?php echo $url['static']; ?>/img/cards/card2.jpg" align="absmiddle" data-cardId="3" />
							</center>
						</div>-->							
					</div>
					
				</div>
			</div>
			
			
			<!-- Fight!!! -->
			<div class="item itemFight" pageId="fight" style="width:996px;height:745px;background-image:url('<?php echo $url['static']; ?>/img/pages/background3.jpg');">
				<div class="carousel-item-container">
					
				</div>
			</div>
		</div>
	
		</div>


		
	</div>
</div>

<div id="footer">
    <div class="container"></div>
</div>
	
<!-- шаблоны -->
<div id="game-templates-markup" style="visibility:hidden;display:none;">
	<!--  -->
	<div id="tplFightPage">
		<div class="row-fluid fightRow" style="margin-top:10px;text-align:center;height:580px;">						
						
						<div class="span4" style="margin-left:0px;">
							<div style="text-align:center;">
								<img style="" src="<?php echo $url['static']; ?>/img/ui/assault_team_text2.png" align="absmiddle" />
							</div>
							<div class="userDeka pull-left" style="position:relative;"></div>							
						</div>
						<div class="span4" style="margin-left:0px;">
							<div style="margin-top:385px;text-align:right;" class="arrowToAttack">
								<img src="about:blank;" align="absmiddle" />
							</div>
						</div>
						<div class="span4" style="margin-left:0px;">
							<div style="text-align:center;margin-top:10px;">
								<img style="" src="<?php echo $url['static']; ?>/img/ui/enemy_text.png" align="absmiddle" />
							</div>
							<div class="monsterDeka" style="position:relative;left:125px;top:9px;"></div>
						</div>
					</div>
					
					<div class="row-fluid healthRow" style="margin-top:5px;text-align:center;color:white;">
						<div class="span6 userTopBlock" style="margin-left:0px;padding-right:123px;">
							<div class="progress progress-success userBlock" style="width:250px;float:right;">
							  <div class="bar userCardActiveHealth" style="width: 100%"></div>
							</div>
							<div style="margin-top:20px;text-align:center;float:right;margin-right:50px;">
								<img style="width:140px;cursor:pointer;" src="<?php echo $url['static']; ?>/img/ui/regroup_btn.png" align="absmiddle" class="reGroupUserDekaBtn" />
							</div>
						</div>
						<div class="span2" style="margin-left:0px;"></div>
						<div class="span4 " style="margin-left:0px;">
							<div class="progress progress-danger monsterBlock" style="width:250px;">
							  <div class="bar monsterCardActiveHealth" style="width: 100%"></div>
							</div>
						</div>
					</div>
				
	</div>
</div>
<!-- Modal -->
	<div id="myModal" class="modal modalBattleEnd hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-header" style="text-align:center;">
		<h3 id="myModalLabel">Battle finished!</h3>
	  </div>
	  <div class="modal-body" style="text-align:center;">
		<p>One fine body…</p>
	  </div>
	  <div class="modal-footer" style="text-align:center;">
		<button class="btn btn-primary" onClick="gGame.battle.collectBattleRewards();">
			<img style="width:32px;" class="" src="<?php echo $url['static']; ?>/img/ui/collectTrophy.png" align="absmiddle" />
			&nbsp;&nbsp;<font style="size:14px;font-weight:bold;">Collect!</font></button>
	  </div>
	</div>

<!-- Placed at the end of the document so the pages load faster -->
<script>
// , 'bootstrap-modalmanager.js', 'bootstrap-modal.js',
	var _JS_FILES_LIST = {
		'lib' : ['jquery.min.js', 'jquery-ui-1.10.2.custom.js', 'bootstrap.min.js', 'tracekit.js', 'lodash.min.js', 'jquery.ajaxmanager.js', 'jquery.pubsub.js', 'signalsy.js', 'spin.min.js', 'jquery.spin.js', 'jquery.percentageloader-0.1.js'],
		'game' : ['utils.js','main.js']
	};
	
	for (var i = 0; i < _JS_FILES_LIST.lib.length; i++)
	{
		head.js( gUrl.static + '/js/' + _JS_FILES_LIST.lib[ i ] );
	}
	
	for (var i = 0; i < _JS_FILES_LIST.game.length; i++)
	{
		head.js( gUrl.app + '/js/' + _JS_FILES_LIST.game[ i ] );
	}
	
	//а рисунки кто кешировать будет?
	_imgLoader.addCompletionListener(function(){
	
		//все, стартуем! 
		head(function(){
			gGame.init();
		});
	});
		
	_imgLoader.start();
	
	
</script>

</body>
</html>