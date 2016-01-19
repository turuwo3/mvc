<?php
namespace TRW\Router;

use Exception;
use TRW\Router\Route;

class Router {

	private static $routerCollection;

	public static function add($path, $param){
		$route = new Route($path, $param);
		self::$routerCollection[$route->getPath()] = $route;
	}

	public static function remove($key){
		self::$routerCollection[$key] = null;
	}

	public static function get($key = null){
		if(!empty(self::$routerCollection[$key])){
			return self::$routerCollection[$key];
		}
		return false;
	}

	public static function getAll(){
		return self::$routerCollection;
	}

	public static function has($key){
		if(empty(self::$routerCollection[$key])){
			return false;
		}
		if(array_key_exists($key, self::$routerCollection)){
			return true;
		}else {
			return false;
		}
	}

	public static function clear(){
		self::$routerCollection = [];
	}

	public static function normalize($param){
		if(empty($param['controller']))	{
			return '/';
		}
		$controller = $param['controller'];
		$action = $param['action'];
		$arguments = '';
		if(!empty($param['arguments'])){
			$arguments = implode('/', $param['arguments']);
		}
		$url = "/{$controller}/{$action}/{$arguments}"; 

		return $url;
	}

	public static function parse($request){
		if(empty($request->getParam('controller'))){
			$url = '/';
		}else {
			$url = $request->getParam('url');
		}

		if(self::has($url)){
			$route = self::$routerCollection[$url];
			return $route->parse();
		}

		throw new Exception('missing Route');

	}




}
