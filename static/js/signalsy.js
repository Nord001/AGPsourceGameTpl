/** Клиентский роутер Signalsy **/

;(function(window, undefined) {


var signalsy = {
	onCallSuccess: function(d, callBack){},
	onCallError: function(d, callBack){
		var _str = 'Ошибка сервера или сетевого соединения';
		if ((typeof(d) != 'undefined') && (typeof(d['error']) != 'undefined'))
			_str = d.error;
			
		if (!$.isFunction(callBack))
			callBack = null;
			
		//надо использовать глобальную нотификацию
		signalsy.notify( _str, {
			onShow: callBack,
			type: 'error',
			theme: 'noty_theme_twitter',
			layout: 'center',
			force: true
		});

		return this;
	}, 
	
	ajax: $.manageAjax.create('game', {
		queue: true, 
		cacheResponse: false, //пока запретим, только серверное кеширование 
		maxRequests: 2,
		preventDoubleRequests: false				
	}),

	/* глобальная нотификация (через jQuery.noty)
	   onShow
	   onClose 
	  */
	notify: function(text, opt){
		if (typeof($.noty) == 'undefined')
			return;
		
		opt = $.extend(opt, $.noty.defaultOptions);
		opt.text = text;
		
		noty(opt);
	}, 
	
	//локальный кеш для вызовов методов
	// action => {ts:<timestamp_last_call>, result: <mixed result>} 
	__localCallCache: {},
	__callMap: {}, //таблица роутинга 
	
	//инициализация таблицы роутинга 
	init: function(){
		// signalsy/routes/get
		this.ajax.add({
			url: 'signalsy/routes/get',
			async: 	true,
			cache:	true,
			success: function(d){
				if ((d != null) && (d.status == 'OK'))
				{
					signalsy.__callMap = d.data;
				}
			},
			error:   this.onError
		});		
	},
	
	/** Регистрация метода **/
	addCall: function(action, param, cache, callBack){
		if (!$.isFunction(callBack))
			return false;
		
		//Если надо сделать, чтобы локальные методы перекрывали удаленные, раскоментировать 
		if (typeof(this.__callMap[action]) != 'undefined') return false;

		//кеш для локальных методов в мс.
		cache = false; 
		/*parseInt(cache);
		
		if (cache < 1)
			cache = false;
		else
		if (cache < 300)
			cache = 300;
		*/
		
		signalsy.__callMap[action] = {
			url: callBack,
			p: param,
			t: 'fn',
			c: cache
		};
		
		return this;
	},
	
	//готовит URL c параметрами
	url: function(url, params){
		var _url = gHost.api + '/' + url + '?PHPSESSID='+gHost.PHPSESSID+'&ukey=1';
		
		if (($.isArray(params)) || ($.isPlainObject(params)))
		{
			_url = _url + '&';
			
			$.each(params, function(i,v){
				_url = _url + i + '=' + v + '&';
			});
			
			_url = _url.substr(0,_url.length-1);
		}
		
		return _url;
	},

	/**
		Глобальный метод вызова URL или екшина.
		Если есть описание в карте вызовов, то проверяет наличие необходимых параметров, 
		также устанавливает кеширование и тип ответа 
		
		vk: - методы контакта 
		mr: - методы мейла.ру
		fb: - методы фейсбука
		g+: - методы гуглплюса
		ok: - методы Одноклассников 
		
		rm: - метод сервера ( или напрямую URL)
		
		без префикса - генерирует локальное событие через паблик/субсткайб 	
		
		если же без префикса и в методе содержатся точки (мин 2) - это прямой вызов метода.
	**/
	call: function(action, params, onSuccess, onError){
	//fbug([action, params, onSuccess, onError]);

		//проверка запроса 
		var _url = null;
		var _params = null;
			_params = params;
		var _version = 1; //для версионности 
		var _tmp;

		if ((arguments.length == 2) && ($.isFunction(arguments[1]))) //только екшин и саксес-обработчик
		{
			onSuccess = arguments[1];
			_params = {};
		}
		else
		if ((arguments.length == 3) && (($.isFunction(arguments[1])) && ($.isFunction(arguments[2]))))
		{
			onSuccess = arguments[1];
			onError = arguments[2];	
		}
		
		if ($.isFunction(action))
		{
			action( params );
			return this;
		}
		
		if (!$.isFunction(onSuccess))
			onSuccess = this.onCallSuccess;
		
		if (!$.isFunction(onError))
			onError = this.onCallError;
		
		//есть версия 
		_tmp = action.indexOf('?');
		if (_tmp !== -1)
		{
			_version = parseInt(action.substring( _tmp + 1 ));
			action = action.substring(0,  _tmp);
		}
		
		_tmp = action.split('.');
		
		//= временная штука 
		if (( (action.indexOf('vk:') === 0) && (this.platform == 'fb') ) || ( (action.indexOf('fb:') === 0) && (this.platform == 'vk') ))
		{
			fbug('[WARNING] Платформа: ' + this.platform + ' не поддерживает вызов другого API ('+action+'). Используйте модуль game.social');
			
			onSuccess();	
		}
		
		//так же можно вызвать методы VK (VK.api враппер) и FB
		// такие вызовы начинаются с vk: и fb: (mail.ru: mr:)
		if ((action.indexOf('vk:') === 0) && (this.platform == 'vk'))
		{
			if (this.social._API == null){
				onSuccess();
				return this;
			}
		
			action = action.substring(3);
		
			this.social._API.api(action, params, function(d){
				if (typeof(d.response) != 'undefined') { 
				  //все ок 
				  onSuccess( d.response );			  
				}
				else
				if (typeof(d.error) != 'undefined')
					onError({error:'[VK.api] Error: ' + d.error.error_msg});
			});
			
			return this;	
		}
		else
		if ((action.indexOf('fb:') === 0) && (this.platform == 'fb'))
		{
			if (game.social._API == null){
				onSuccess();
				return this;
			}		
		}
		else
		if ((action.indexOf('mr:') === 0) || (action.indexOf('g+:') === 0) || (action.indexOf('ok:') === 0))
		{
			this.onCallError({error:'API method namespace not supported now'}); 
			return this;
		}
		
		//задали удаленный метод - по дефолту все 
		if (action.indexOf('/') !== -1) //задали URL 
			_url = {'url':action, 'p': [], 't': 'json', 'c':false};
		else
		if (typeof(this.__callMap[action]) == 'undefined')
		{
			onCallError({error: action + ': unknown method or URL at API. Define it at server side or use direct URL'}); 
			return this;
		}	//throw new Error('Unknown method or URL at API. Define it at server side or use direct URL');
		else
			_url = this.__callMap[ action ];
		
		if ((typeof(_url.c) == 'undefined') || ((_url.c !== false) && (_url.c !== true)))
		{
			_url.c = false;
			this.__callMap[ action ].c = false;
		}
		
		if ((typeof(_url.p) == 'undefined') || ((_url.p == null)))
		{
			_url.p = [];
			this.__callMap[ action ].p = [];
		}
		
		
		//проверка параметров 
		if (_url.p.length > 0)
		{		
			var isParamsFailure = false;
			var _px = '';
			
			$.each(_url.p, function(i,v){
				if (typeof(params[v]) == 'undefined')
				{
					isParamsFailure = true;
					_px = '(' + v + ')';
				}
			});
			
			if (isParamsFailure == true)
			{
				onCallError({error:'Missing required params '+_px+' for action: ' + _url.url});
				return this; //game;
			}
			//throw new Error('Missing required params '+_px+' for action: ' + _url.p);		
		}
		
		//если это локальный метод, вызвать 
		if ((_url.t == 'fn') && ($.isFunction(_url.url)))
		{
	fbug( _url );		
			/* кеширование вызова методов 
			if (_url.c != false)
			{
				var _ts = new Date().getTime();
				
				if (typeof(game.__localCallCache[ action ]) != 'undefined')
				{
					if ((game.__localCallCache[ action ].ts > 0) && (game.__localCallCache[ action ].ts > _ts))
					{
						return this;				
					}
				}		
			}
			*/
			try
			{
				_url.url( _params );
				
				onSuccess();

			}catch(e){
				this.onCallError({error:e.message});
			}
		}
		else
		{	
			_params['v'] = _version;
				
			fbug( _params );
			
			//теперь конструируем запрос 
			this.ajax.add({
				url: 	_url.url,
				async: 	true,
				data: 	_params,
				cache:	_url.c,
				success: function(d){
					
					if ((d == null) || (d.status != 'OK'))
						onCallError({error:d.error});
					else
						onSuccess(d);
				},
				error:  onError
			});
		}

		return this;
	},
	
	platform: 'web', //платформа

	social: {
		_API: null, //экземпляр js api обьекта 
		_credits:null,
		//регистрация апи 
		registerApi: function(platform, onSuccess){
			if (platform == 'vk')
			{
				$(document).append('<script src="http://vk.com/js/api/xd_connection.js?2"; type="text/javascript"></script>');			
			}
			else
			if (platform == 'fb')
			{
				$(document).append('<script src="http://connect.facebook.net/en_US/all.js"; type="text/javascript"></script>');			
			}
		}
	
	
	}
}


//загрузить таблицу роутинга 
signalsy.init();


// Export to global object
window.signalsy = signalsy;

}(window));
