<?php
/**
 * Volt Tokenizer
 *
 * @author Wenzel Pünter <wenzel@phelix.me>
 * @version 1.2.6
 * @package Phalcon
*/
namespace Phalcon\Mvc\View\Engine\Volt;

use \Phalcon\Mvc\View\Engine\Volt\Scanner,
	\Phalcon\Mvc\View\Exception;

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
		$scanner = new Scanner($data);
		return array(
			'type' => 359,
			'expr' => $scanner->scanExpression(),
			'file' => $file,
			'line' => $line
		);
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
		return array(
			'type' => 310,
			'path' => $extendsFile,
			'file' => $file,
			'line' => $line
		);
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
		if(empty($blockData) === true) {
			return array(
				'type' => 307,
				'name' => $blockName,
				'file' => $file,
				'line' => $line
			);
		} else {
			$scanner = new Scanner($blockData);
			return array(
				'type' => 307,
				'name' => $blockName,
				'block_statements' => $scanner->scanBlockStatements($line),
				'file' => $file,
				'line' => $line
			);
		}
	}

	/**
	 * Tokenize a cache fragment
	 * 
	 * @param string $data
	 * @param string $file
	 * @param int $line
	 * @throws Exception
	*/
	public static function cacheFragment($data, $file, $line)
	{
		$matches = array();
		if(preg_match('#^{%\s*cache\s+(?P<expr>[^{}]*)\s*(?P<ttl>\d*)\s*%}(?P<block>.*){%\s*endcache\s*%}$#', $data, $matches) == false) {
			throw new Exception('Malformed caching expression.');
		}

		if(empty($matches['expr']) === true ||
			empty($matches['block']) === true) {
			throw new Exception('Malformed caching expression.');
		}

		$blockScanner = new Scanner($matches['block']);
		$exprScanner = new Scanner($matches['expr']);

		if(empty($matches['ttl']) === false) {
			return array(
				'type' => 314,
				'expr' => $exprScanner->scanExpression(),
				'lifetime' => $matches['ttl'],
				'block_statements' => $blockScanner->scanBlockStatements(),
				'file' => $file,
				'line' => $line
			);
		} else {
			return array(
				'type' => 314,
				'expr' => $exprScanner->scanExpression(),
				'block_statements' => $blockScanner->scanBlockStatements(),
				'file' => $file,
				'line' => $line
			);
		}
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