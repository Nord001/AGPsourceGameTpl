<?php
if(!ob_start("ob_gzhandler")) ob_start();

//общий документ рут 
$_SERVER['DOCUMENT_ROOT'] = '/home/defconassault.com';

// фикс для lizard-a
if(strtoupper(substr(PHP_OS, 0, 3)) != 'WIN')
{
    $_SERVER['DOCUMENT_ROOT'] = '/srv/www/defconassault.com';
}
/**
 * Signalsy Platform Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@wheemplay.com so we can send you a copy immediately.
 *
 * @category   Signalsy
 * @package    Signalsy Core
 * @copyright  Copyright (c) 2009 AGPsource Team
 * @license    http://signalsy.com/license/ New BSD License
 */
$_signalsy_st = microtime(true);

ini_set('date.timezone', 'GMT+0');
 /**
 error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_WARNING);
 //! при использовании XCache могут быть E_WARNING сообщения от загрузчика, их можно игнорировать
 if (isset($_REQUEST['debug']))
 {
 	error_reporting(E_ALL);
 }
 else
 	error_reporting(E_ERROR);
 */	
 error_reporting(E_ALL);
 
 //если системная загрузка больше 0.8, не обслуживаем дальше клиентов
 if (function_exists('sys_getloadavg') === true)
 {
	 $load = sys_getloadavg();
	 if ($load[0] > 80) {
	    header('HTTP/1.1 503 Too busy, try again later');
	    die('Server too busy. Please try again later.');
	 }
 }
 
 $__debug__ = true; //ВАЖНО! если включен - выводит весь ексепшин

 //загружаем инициализацию библиотек
 require_once('../libs/loader.php');
 
 
 //default including code path	
 set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '/libs' . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '/game/inc' );
 
 require_once('Exceptionizer.php');
 
 $exceptionizer = new PHP_Exceptionizer(E_ALL); 
 
 require_once('Zend/Loader/Autoloader.php');
 require_once('connectManager.php');
 
 //rewrite base URL, default for index = www.domain.com/sire/index
 if ((empty($_REQUEST['__route__'])) || ($_REQUEST['__route__'] == '/') || (!isset($_REQUEST['__route__'])))
 {
	//удалить сессию
	Zend_Session::destroy(true, true);
	
	@header('Location: /');
	die(1);
 }
 else
 {
	try
	{	

		$loader = Zend_Loader_Autoloader::getInstance();
		
		//время начала обработки
		Zend_Registry::set('signalsy_st', $_signalsy_st);

//		$loader->registerNamespace('Admin'); //для админ-панели
		$loader->registerNamespace('Api'); // для API
		$loader->registerNamespace('Game'); // для API
		$loader->registerNamespace('Map'); // для API
		$loader->registerNamespace('System'); // для API
		$loader->registerNamespace('User'); // для API
		$loader->registerNamespace('Signalsy'); // для API
		
		//если запросили специальный урл - выдать таблицу сигналов 
		if ($_REQUEST['__route__'] === 'signalsy/routes/get')
		{
			header('Content-type: application/json');
			echo Zend_Json::encode(Array(
				'status' => 'OK',
				'timestamp' => time(),
				'error' => null,
				'data' => connectManager::buildMap()
			));
			exit();
		}
		
		
		
		
		
		//главный конфиг
		$config = parse_ini_file( $_SERVER['DOCUMENT_ROOT'] . '/config.ini', true);
		
		Zend_Registry::set('config', $config);
		Zend_Registry::set('exceptionizer', $exceptionizer);	
	
		if ($config['DkLabRealplexor']['useDklab'] == true)
			require_once('Dklab/Realplexor.php');
	
		
		
		//staring routings
		$router = Signalsy_xRouter::getInstance($config);
		
		
		
		Zend_Registry::set('router', $router);

		//start dispatch
		$router->dispatchURL($_REQUEST['__route__']);

	}catch(Exception $e){
		
		//echo '<pre>';
		//var_dump($e); exit(1);
		
		header('Content-type: application/json');
		header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
		
		$error = Array(
				'status' => 'FAILURE',
				'timestamp' => time(),
				'error' => $e->getMessage(),
				'data' => null
		);		
		
		if ((array_key_exists('debug', $_REQUEST)) || ($__debug__ === true))
		{
			$error['data'] = $e->getTraceAsString();
		}
//echo '<pre>'; var_dump($e->getCode()); exit(1);			
		echo Zend_Json::encode($error);
		
		exit();
	}	
 }
 


 
