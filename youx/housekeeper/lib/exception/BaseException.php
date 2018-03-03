<?php 
use think\Exception; 

class BaseException extends Exception{
	public $code="400";
	public $msg ="参数错误";
	public $errorcode ="1000";
}