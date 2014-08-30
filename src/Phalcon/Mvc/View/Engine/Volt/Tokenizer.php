<?php
/**
 * Volt Tokenizer
 *
 * @author Wenzel Pünter <wenzel@phelix.me>
 * @version 1.2.6
 * @package Phalcon
*/
namespace Phalcon\Mvc\View\Engine\Volt;

/**
 * Volt Tokenizer
*/
class Tokenizer
{
	/**
	 * Tokenize a raw fragment of (non-Volt-)code
	 * 
	 * @param string $data
	 * @param string $file
	 * @param int $line
	*/
	public static function rawFragment($data, $file, $line)
	{
		return array(
			'type' => 357,
			'value' => $data,
			'file' => $file,
			'line' => $line
		);
	}

	/**
	 * Tokenize an echo fragment
	 * 
	 * @param string $data
	 * @param string $file
	 * @param int $line
	*/
	public static function echoFragment($data, $file, $line)
	{

	}

	/**
	 * Tokenize an extends fragment
	 * 
	 * @param string $extendsFile
	 * @param string $file
	 * @param int $line
	*/
	public static function extendsFragment($extendsFile, $file, $line)
	{

	}

	/**
	 * Tokenize a block fragment
	 * 
	 * @param string $blockName
	 * @param string $blockData
	 * @param string $file
	 * @param string $line
	*/
	public static function blockFragment($blockName, $blockData, $file, $line)
	{

	}

	/**
	 * Tokenize a cache fragment
	 * 
	 * @param string $data
	 * @param string $file
	 * @param int $line
	*/
	public static function cacheFragment($data, $file, $line)
	{

	}

	/**
	 * Tokenize an autoescape fragment
	 * 
	 * @param string $data
	 * @param string $file
	 * @param int $line
	*/
	public static function autoescapeFragment($data, $file, $line)
	{

	}

	/**
	 * Tokenize a macro fragment
	 * 
	 * @param string $name
	 * @param string $params
	 * @param string $data
	 * @param string $file
	 * @param int $line
	*/
	public static function macroFragment($name, $params, $data, $file, $line)
	{

	}

	/**
	 * Tokenize an if fragment
	 * 
	 * @param string $data
	 * @param string $file
	 * @param int $line
	*/
	public static function ifFragment($data, $file, $line)
	{

	}

	/**
	 * Tokenize a set fragment
	 * 
	 * @param string $expression
	 * @param string $file
	 * @param int $line
	*/
	public static function setFragment($expression, $file, $line)
	{

	}

	/**
	 * Tokenize a for fragment
	 * 
	 * @param string $data
	 * @param string $file
	 * @param int $line
	*/
	public static function forFragment($data, $file, $line)
	{

	}

	/**
	 * Tokenize a do fragment
	 * 
	 * @param string $expr
	 * @param string $file
	 * @param int $line
	*/
	public static function doFragment($expr, $file, $line)
	{

	}
}