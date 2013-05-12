/** Базовый класс, загружается и стартует первым **/

//настройки TraceKit
TraceKit.remoteFetching = false;
TraceKit.collectWindowErrors = true;



//базовая версия лог-функции 
function fbug(i){ 
	if (typeof(console) != 'undefined')
		console.log(i);
}

//общий обработчик ошибок 
window.onerror = function(message, url, linenumber){
	fbug('[jERR:' + linenumber + '] ' + message + ' (' + url + ':'+linenumber+')');	
	return true;
}

//Основной класс игры 
var gGame = {
			curBattle: null,
			
			tpl: null,
			curDeka: null, //все карты доступные юзеру сейчас 
			
			_resizeEls: function(){
				return;
				$('#gameContainer').css({
					height: $('#wrap').height() + 'px',
					width:  $('#wrap').width() + 'px'
				});
				
				var _el = $('#mainGameUI');
					_el.css('height', $('#wrap').height() + 'px');
					_el.find('.item').css({
						height: $('#wrap').height() + 'px',
						width:  $('#wrap').width() + 'px'
					});
					//_el.find('.resizeContainer').css('width', $('#wrap').width() + 'px');
			},
			
			router: null, //если есть, используем sygnalsy
		
			init: function(){
				//инит темплейт кеша 
				gGame.tpl = $('#game-templates-markup');
				
				if (typeof(signalsy) != 'undefined')
					gGame.router = signalsy;
			
				//фиксим высоту елемента 
				gGame._resizeEls();
				//$('#mainGameUI').css('height', $('#wrap').height() + 'px');
				
				//динамически ресайзим 
				$(window).on('resize', function(){
					gGame._resizeEls();
				});
				
				//привяжем основные кнопки 
				$('#mainGameUI, .mainMenuBlock').find('.mainBtn').click(function(e){
					var pageId = $(e.currentTarget).attr('pageId');
					
					gGame.ui.goTo( pageId );
				});
				
				$('#mainGameUI').find('.menuGoToHome').click(function(){
					gGame.ui.goTo( 'main' );
				});
				
				$('.itemTooltip').tooltip({
					html:true
				});
				
				//инит карусели 
				gGame.ui.el = $('.carousel');
				gGame.ui.el.carousel({
					interval: false
				}).on('slid', function(e){
					//к сожалению, нет возможности узнать что за страница 
					var pageCode = $(e.target).find('.active').attr('pageId');
					gGame.ui.onPageOpen( pageCode );
				});
				
				
			},
			
			
			//реалтайм коммуникация 
			comet: {
				conn: null, //соединение 
				
				init: function(){
					gGame.comet.conn = PUBNUB.init({
						publish_key   : 'pub-04ca9ace-bcdc-4359-bcf2-91f09310dc73',
						subscribe_key : 'sub-9d8ec79c-b2c1-11e0-98d5-97615fb46dfa'
					});
				}
			
			
			},
			
			//управление базовым UI 
			ui: {
				el: null,
				//коды страниц и ид в карусели
				pages:{
					'xmain': 0, //это самое начало
					'main': 0,
					'cards': 2,
					
					'shop':2,
					'scores' : 3,
					'chat' : 4			
				},
				//юзер переключает интерфйес (хочет на)
				goTo: function(pageCode){
					if (pageCode == 'xmain') //мы переключаемся с главной 
					{
						var el = $('body').find('.gameContainer');
						
							el.find('.startPageBg').hide().remove();
							el.find('#mainGameUI').show();
							
							
						//загрузим текущие карты 
						gGame.router.call('myCardsDeka', {}, function(d){				
							gGame.curDeka = d.data;
						});	
							
							
						return;
					}
						
					//$('body').modalmanager('loading'); 
					gGame.ui.el.carousel( gGame.ui.pages[ pageCode ] );
									
				},
				
				//обработчик, срабатывает когда юзер переходит на страницу 
				onPageOpen: function(pageCode){
					fbug('Open: ' + pageCode);				
					
					if (pageCode == 'cards')
					{
						gGame.cards.init();						
					}
				
				}			
			},
			
			cards: {
				_store:{}, //стор для карт
				
				init: function(){
					
					if (_.size( gGame.cards._store) == 0)
					{				
						gGame.cards._loading( gGame.cards.renderCards );					
					}
					else
						gGame.cards.renderCards(1);
				},
				
				_loading: function(callBack){
					gGame.router.call('cardsDataLoading', {}, function(d){
						//$('body').modalmanager('loading');
						fbug('cards data loaded');
						
						gGame.cards._store = d.data;

						if ($.isFunction(callBack))
							callBack();
					});
				},
				
				renderCards: function(level){
					if (typeof(level) == 'undefined')
						level = 1;
						
					var el = $('#mainGameUI').find('.itemCards');
					var row1 = el.find('.cardsRow_1');
					var row2 = el.find('.cardsRow_2');
					
					var _cards = [];
					
					_.each(gGame.cards._store, function(v){
						if (v.card_balance_level == level)
							_cards.push( v );
					});
					
					_cards = gUtils.array_chunk(_cards, 4);
				
					var _str = '<div class="span1" style="width:30px;"></div>';
					
					_.each(_cards[0], function(v){ // margin-right:20px;margin-left:20px;
						_str = _str + '<div class="span3" style="margin-left:0px;text-align:center;">';
						
						if (v.isResearched == 'no')
						{
						_str = _str + '<img style="max-width:none;width:200px;position:absolute;" class="card cardBlocked" src="' +
							gUrl['static'] + '/img/cards/card_mask_block2.png" align="absmiddle" />';
						}
						else
						if (v.isResearched == 'progress')
						{
							_str = _str + '<img style="max-width:none;width:200px;position:absolute;" class="card cardBlocked" src="' +
								gUrl['static'] + '/img/cards/card_mask_progress.png" align="absmiddle" />';
						}
						
						_str = _str + '<img style="max-width:none;width:200px;" class="img-polaroid card" src="' + 
						gUrl['static'] + '/img/cards/' + v.card_img_full + '" data-cardId="'+v.card_id+'" align="absmiddle" /></div>';
					});
					
row1.html(_str); 

//row1.find('#cardX1').percentageLoader({width : 150, height : 150, progress : 0.5, value : '512kb'});

					
					var _str = '<div class="span1" style="width:30px;"></div>';
					
					_.each(_cards[1], function(v){ // margin-right:20px;margin-left:20px;
						_str = _str + '<div class="span3" style="margin-left:0px;text-align:center;">';
						
						if (v.isResearched == 'no')
						{
						_str = _str + '<img style="max-width:none;width:200px;position:absolute;" class="card cardBlocked" src="' +
							gUrl['static'] + '/img/cards/card_mask_block2.png" align="absmiddle" />';
						}
						else
						if (v.isResearched == 'progress')
						{
							_str = _str + '<img style="max-width:none;width:200px;position:absolute;" class="card cardBlocked" src="' +
								gUrl['static'] + '/img/cards/card_mask_progress.png" align="absmiddle" />';
						}
						
						_str = _str + '<img style="max-width:none;width:200px;" class="img-polaroid card" src="' + 
						gUrl['static'] + '/img/cards/' + v.card_img_full + '" data-cardId="'+v.card_id+'" align="absmiddle" /></div>';
					});
					
					row2.empty().append(_str); 
					
				
				}
			
			}
	};