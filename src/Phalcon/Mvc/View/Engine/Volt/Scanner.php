<?php
/**
 * Volt Scanner
 *
 * @author Wenzel Pünter <wenzel@phelix.me>
 * @version 1.2.6
 * @package Phalcon
*/
namespace Phalcon\Mvc\View\Engine\Volt;

use \Phalcon\Mvc\View\Exception;

/**
 * Volt Scanner
*/
class Scanner
{
	/**
	 * Volt
	 * 
	 * @var string
	 * @access protected
	*/
	protected $_volt;

	/**
	 * File
	 * 
	 * @var string
	 * @access protected
	*/
	protected $_file;

	/**
	 * Constructor
	 * 
	 * @param string $volt
	 * @param string|null $file
	 * @throws Exception
	*/
	public function __construct($volt, $file = null)
	{
		if(is_string($volt) === false) {
			throw new Exception('Invalid parameter type.');
		}

		if(is_string($file) === true) {
			$this->_file = $file;
		} elseif(is_null($file) === true) {
			$this->_file = 'eval code';
		} else {
			throw new Exception('Invalid parameter type.');
		}

		$this->_volt = $volt;
	}

	/**
	 * Throw token exception
	 * 
	 * @param string $token
	 * @param int $line
	 * @throws Exception
	*/
	public function throwTokenException($token, $line)
	{
		throw new Exception('Unexpected token "'.$token.'" in '.$this->_file.' at line '.$line);
	}

	/**
	 * Identify and extract block statements
	 * 
	 * @throws Exception
	 * @param int $line
	 * @return array
	*/
	public function scanBlockStatements($line = 1)
	{
		if(is_int($line) === false) {
			throw new Exception('Invalid parameter type.');
		}

		/* Splitting */
		$flags = \PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE;
		$expressions = array(
			'({\#)',
			'(\#})',
			'({{)',
			'(}})',
			'(")',
			'(\')',
			'({%\s*extends\s+"(?:.*)"\s*%})',
			'({%\s*block\s*(?:\w+)\s*%})',
			'({%\s*endblock\s*%})',
			'({%\s*endcache\s*%})',
			'({%\s*cache\s+(?:[^{}]*)\s*(?:\d*)\s*%})',
			'({%\s*endautoescape\s*%})',
			'({%\s*autoescape\s+(?:true|false)\s*%})',
			'({%(?:-?)\s*macro\s*(?:\w)\((?:(?:(?:\w)\s*(?:,?)\s*)+)\)\s*%})',
			'({%\s*endmacro\s*%})',
			'({%\s*if\s*(?:.*)\s*%})',
			'({%\s*endif\s*%})',
			'({%\s*set\s+(?:.*)\s*%})',
			'({%\s*for\s+(?:(?:\w+),?)\s*(?:\w+)\s+in\s+(?:.+)\s*%})',
			'({%\s*endfor\s*%})',
			'({%\s*do\s*(?P<expr>.*)\s*%})'
		);
		$regexp = '#'.implode('|', $expressions).'#';

		$matches = preg_split($regexp, $this->_volt, -1, $flags);

		/* Scanning */
		$scannerComment = 0;
		$scannerAutoescape = 0;
		$scannerCache = 0;
		$scannerIf = 0;
		$scannerFor = 0;
		$scannerBlock = null;
		$scannerMacro = null;
		$scannerExtends = false;
		$scannerStatement = false;
		$scannerInString = 0;

		$buffer = '';
		$intermediate = array();

		foreach($matches as $match) {
			$blockMatches = array();
			$line += substr_count($match, "\n");

			if($match === '"') {
				/* Open/close string */
				if($scannerInString === 1 &&
					(empty($buffer) === true ||
					substr($buffer, -1, 1) !== "\\")) {
					$scannerInString = 0;
				} elseif($scannerInString === 0) {
					$scannerInString = 1;
				}

			} elseif($match === "'") {
				/* Open/close string */
				if($scannerInString === 2 &&
					(empty($buffer) === true ||
					substr($buffer, -1, 1) !== "\\")) {
					$scannerInString = 0;
				} elseif($scannerInString === 0) {
					$scannerInString = 2;
				}

			} elseif($scannerInString === 0) {

				if($match === '{#') {
					/* Start comment */
					if(empty($buffer) === false) {
						$intermediate[] = Tokenizer::rawFragment($buffer, $this->_file, $line);
						$buffer = '';
					}

					++$scannerComment;
				} elseif($match === '#}') {
					/* End comment */
					--$scannerComment;

					if($scannerComment < 0) {
						$this->throwTokenException('#}', $line);
					} elseif($scannerComment === 0) {
						//Remove comment from buffer
						$buffer = '';
					}

				} elseif($match === '{{') {
					/* Open echo statement */
					if(empty($buffer) === false) {
						$intermediate[] = Tokenizer::rawFragment($buffer, $this->_file, $line);
						$buffer = '';
						$match = '';
					}

					if($scannerStatement === true) {
						$this->throwTokenException('{{', $line);
					} else {
						$scannerStatement = true;
					}

				} elseif($match === '}}') {
					/* Close echo statement */
					if($scannerStatement === false ||
						empty($buffer) === true) {
						$this->throwTokenException('}}', $line);
					} else {
						$intermediate[] = Tokenizer::echoFragment($buffer.$match, $this->_file, 
							$line);
						$scannerStatement = false;
						$buffer = '';
						$match = '';
					}

				} elseif(preg_match('#^{%\s*extends\s+"(?P<file>.*)"\s*%}$#', $match, 
					$blockMatches) != false) {
					//Check for {% extends "FILE" %}
					if($scannerExtends === true || count($intermediate) !== 0) {
						throw new Exception('Extends statement must be placed at the first line in the template in '.$this->_file.' on line '.$line);
					} else {
						$intermediate[] = Tokenizer::extendsFragment($blockMatches['file'], $this->_file, $line);
						$match = '';
						$scannerExtends = true;
					}

				} elseif(preg_match('#^{%\s*block\s*(?P<name>\w+)\s*%}$#', $match, 
					$blockMatches) != false) {
					//Check for {% block NAME %}
					if(is_string($scannerBlock) === true) {
						$this->throwTokenException('{% block ', $line);
					} else {
						if(empty($buffer) === false) {
							$intermediate[] = Tokenizer::rawFragment($buffer, $this->_file, $line);
						}
						$match = '';
						$scannerBlock = $blockMatches['name'];
					}
					
				} elseif(preg_match('#^{%\s*endblock\s*%}$#', $match) != false) {
					//Check for {% endblock %}
					if(is_string($scannerBlock) === false) {
						$this->throwTokenException('{% endblock %}', $line);
					} else {
						$intermediate[] = Tokenizer::blockFragment($scannerBlock, $buffer, $this->_file, $line);
						$buffer = '';
						$match = '';
						$scannerBlock = null;
					}

				} elseif(preg_match('#^{%\s*endcache\s*%}$#', $match) != false) {
					//Check for {% endcache %}
					--$scannerCache;

					if($scannerCache < 0) {
						$this->throwTokenException('{% endcache %}', $line);
					} elseif($scannerCache === 0) {
						$intermediate[] = Tokenizer::cacheFragment($buffer.$match, $this->_file, $line);
						$buffer = '';
						$match = '';
					}

				} elseif(preg_match('#^{%\s*cache\s+(?:[^{}]*)\s*(?:\d*)\s*%}$#', $match) != false) {
					//Check for {% cache NAME TTL %}
					if($scannerCache === 0 &&
						empty($buffer) === false) {
						$intermediate[] = Tokenizer::rawFragment($buffer, $this->_file, $line);
						$buffer = '';
					}

					++$scannerCache;

				} elseif(preg_match('#^{%\s*autoescape\s+(?:true|false)\s*%}$#', $match) != false) {
					//Check for {% autoescape BOOL %}
					if($scannerAutoescape === 0 &&
						empty($buffer) === false) {
						$intermediate[] = Tokenizer::rawFragment($buffer, $this->_file, $line);
						$buffer = '';
					}

					++$scannerAutoescape;

				} elseif(preg_match('#^{%\s*endautoescape\s*%}$#', $match) != false) {
					//Check for {% autoescape %}
					--$scannerAutoescape;

					if($scannerAutoescape < 0) {
						$this->throwTokenException('{% endautoescape %}', $line);
					} elseif($scannerAutoescape === 0) {
						$intermediate[] = Tokenizer::autoescapeFragment($buffer.$match, $this->_file, $line);
						$buffer = '';
						$match = '';
					}

				} elseif(preg_match('#^{%(?:-?)\s*macro\s*(?P<name>\w)\((?P<params>(?:(?:\w)\s*(?:,?)\s*)+)\)\s*%}$#', $match, $blockMatches) != false) {
					//Check for {% macro NAME(PARAMS) %}
					if(is_array($scannerMacro) === true) {
						throw new Exception('Embedding macros into other macros is not allowed in '.$this->_file.' on line '.$line);
					} else {
						if(empty($buffer) === false) {
							$intermediate[] = Tokenizer::rawFragment($buffer, $this->_file, $line);
							$buffer = '';
						}

						$scannerMacro = array(
							'name' => $blockMatches['name'],
							'params' => $blockMatches['params']
						);
						$match = '';
					}

				} elseif(preg_match('#^{%\s*endmacro\s*%}$#', $match) != false) {
					//Check for {% endmacro %}
					if(is_array($scannerMacro) === false ||
						empty($buffer) === true) {
						$this->throwTokenException('{% endmacro %}', $line);
					} else {
						$intermediate[] = Tokenizer::macroFragment($scannerMacro['name'], $scannerMacro['params'], $buffer, $this->_file, $line);
						$buffer = '';
						$match = '';
						$scannerMacro = null;
					}

				} elseif(preg_match('#^{%\s*if\s*(?:.*)\s*%}$#', $match) != false) {
					//Check for {% if CONDITION %}
					if($scannerIf === 0 &&
						empty($buffer) === false) {
						$intermediate[] = Tokenizer::rawFragment($buffer, $this->_file, $line);
						$buffer = '';
					}

					--$scannerIf;

				} elseif(preg_match('#^{%\s*endif\s*%}$#', $match) != false) {
					//Check for {% endif %}
					--$scannerIf;

					if($scannerIf < 0) {
						$this->throwTokenException('{% endif %}', $line);
					} elseif($scannerIf === 0 &&
						empty($buffer) === false) {
						$intermediate[] = Tokenizer::ifFragment($buffer.$match, $this->_file, $line);
						$buffer = '';
						$match = '';
					}

				} elseif(preg_match('#^{%\s*set\s+(?P<expression>.*)\s*%}$#', $match, $blockMatches) != false) {
					//Check for {% set expression %}
					if(empty($buffer) === false) {
						$intermediate[] = Tokenizer::rawFragment($buffer, $this->_file, $line);
					}

					$intermediate[] = Tokenizer::setFragment($blockMatches['expression'], $this->_file, $line);
					$buffer = '';
					$match = '';

				} elseif(preg_match('#^{%\s*for\s+(?:(?:\w+),?)\s*(?:\w+)\s+in\s+(?:.+)\s*%}$#', $match) != false) {
					//Check for {% for IDENTIFIER in EXPR %}
					if($scannerFor === 0 &&
						empty($buffer) === false) {
						$intermediate[] = Tokenizer::rawFragment($buffer, $this->_file, $line);
						$buffer = '';
					}

					++$scannerFor;

				} elseif(preg_match('#^{%\s*endfor\s*%}$#', $match) != false) {
					//Check for {% endfor %}
					--$scannerFor;
					if($scannerFor < 0) {
						$this->throwTokenException('{% endfor %}', $line);
					} elseif($scannerFor === 0 &&
						empty($buffer) === false) {
						$intermediate[] = Tokenizer::forFragment($buffer.$match, $this->_file, $line);
						$buffer = '';
						$match = '';
					}

				} elseif(preg_match('#^{%\s*do\s*(?P<expr>.*)\s*%}$#', $match, $blockMatches) != false) {
					//Check for {% do EXPR %}
					if(empty($buffer) === false) {
						$intermediate[] = Tokenizer::rawFragment($buffer, $this->_file, $line);
					}

					$intermediate[] = Tokenizer::doFragment($blockMatches['expr'], $this->_file, $line);
					$buffer = '';
					$match = '';
				}
			}

			$buffer .= $match;
		}

		if($scannerComment === 0 && $scannerAutoescape === 0 && $scannerCache === 0 &&
			$scannerIf === 0 && $scannerFor === 0 && $scannerBlock === null && $scannerMacro === null &&
			$scannerStatement === false) {
			if(empty($buffer) === false) {
				$intermediate[] = Tokenizer::rawFragment($buffer, $this->_file, $line);
			}
		} else {
			throw new Exception('Missing volt token.');
		}

		return $intermediate;
	}

	/**
	 * Identify and extract expressions
	 * 
	 * @throws Exception
	 * @return array
	*/
	public function scanExpression()
	{
		/* Splitting */
		$flags = \PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE;
		$expressions = array(
			'(\[)',
			'(\])',
			'(,)',
			'(:)',
			'({)',
			'(})'
		);
		$regexp = '#'.implode('|', $expressions).'#';
		$matches = preg_split($regexp, $this->_volt, -1, $flags);

		/* Scanning */
		//@todo
	}
}