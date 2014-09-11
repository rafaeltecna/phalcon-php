<?php
/**
 * Volt Parser Testsuite
 *
 * @author Wenzel Pünter <wenzel@phelix.me>
*/
class VoltParserTest extends BaseTest
{
	public function testRawFragment()
	{
		$parser = new Phalcon\Mvc\View\Engine\Volt\Scanner('<p>Test + Paragraph</p>');

		$this->assertEquals($parser->scanBlockStatements(), array(
			array(
				'type' => 357,
				'value' => '<p>Test + Paragraph</p>',
				'file' => 'eval code',
				'line' => 1
			)
		));
	}
	public function testEchoArray()
	{
		$parser = new Phalcon\Mvc\View\Engine\Volt\Scanner('{{ [[1, 2], [3, 4], [5, 6]] }}');

		$this->assertEquals($parser->scanBlockStatements(), array(
			array(
				'type' => 359,
				'expr' => array(
					'type' => 360,
					'left' => array(
						array(
							'expr' => array(
								'type' => 360,
								'left' => array(
									array(
										'expr' => array(
											'type' => 258,
											'value' => '1',
											'file' => 'eval code',
											'line' => 1
										),
										'file' => 'eval code',
										'line' => 1
									),
									array(
										'expr' => array(
											'type' => 258,
											'value' => '2',
											'file' => 'eval code',
											'line' => 1
										),
										'file' => 'eval code',
										'line' => 1
									)
								),
								'file' => 'eval code',
								'line' => 1
							),
							'file' => 'eval code',
							'line' => 1
						), 
						array(
							'expr' => array(
								'type' => 360,
								'left' => array(
									array(
										'expr' => array(
											'type' => 258,
											'value' => '3',
											'file' => 'eval code',
											'line' => 1
										),
										'file' => 'eval code',
										'line' => 1
									),
									array(
										'expr' => array(
											'type' => 258,
											'value' => '4',
											'file' => 'eval code',
											'line' => 1
										),
										'file' => 'eval code',
										'line' => 1
									)
								),
								'file' => 'eval code',
								'line' => 1
							),
							'file' => 'eval code',
							'line' => 1
						),
						array(
							'expr' => array(
								'type' => 360,
								'left' => array(
									array(
										'expr' => array(
											'type' => 258,
											'value' => '5',
											'file' => 'eval code',
											'line' => 1
										),
										'file' => 'eval code',
										'line' => 1
									),
									array(
										'expr' => array(
											'type' => 258,
											'value' => 6,
											'file' => 'eval code',
											'line' => 1
										),
										'file' => 'eval code',
										'line' => 1
									)
								)
							),
							'file' => 'eval code',
							'line' => 1
						)
					),
					'file' => 'eval code',
					'line' =>  1
				),
				'file' => 'eval code',
				'line' =>  1
			)
		));
	}

	public function testAddition()
	{
		$parser = new Phalcon\Mvc\View\Engine\Volt\Scanner('{{ 3 + 2 }}');

		$this->assertEquals($parser->scanBlockStatements(), array(
			array(
				'type' => 359,
				'expr' => array(
					array(
						'type' => 43,
						'left' => array(
							'type' => 258,
							'value' => '3',
							'file' => 'eval code',
							'line' => 1
						),
						'right' => array(
							'type' => 258,
							'value' => '2',
							'file' => 'eval code',
							'line' => 1
						),
						'file' => 'eval code',
						'line' => 1
					)
				),
				'file' => 'eval code',
				'line' => 1
			)
		));
	}

	public function testSubtraction()
	{
		$parser = new Phalcon\Mvc\View\Engine\Volt\Scanner('{{ 3 - 2 }}');

		$this->assertEquals($parser->scanBlockStatements(), array(
			array(
				'type' => 359,
				'expr' => array(
					array(
						'type' => 45,
						'left' => array(
							'type' => 258,
							'value' => '3',
							'file' => 'eval code',
							'line' => 1
						),
						'right' => array(
							'type' => 258,
							'value' => '2',
							'file' => 'eval code',
							'line' => 1
						),
						'file' => 'eval code',
						'line' => 1
					)
				),
				'file' => 'eval code',
				'line' => 1
			)
		));
	}

	public function testMultiplication()
	{
		$parser = new Phalcon\Mvc\View\Engine\Volt\Scanner('{{ 3 * 2 }}');

		$this->assertEquals($parser->scanBlockStatements(), array(
			array(
				'type' => 359,
				'expr' => array(
					array(
						'type' => 42,
						'left' => array(
							'type' => 258,
							'value' => '3',
							'file' => 'eval code',
							'line' => 1
						),
						'right' => array(
							'type' => 258,
							'value' => '2',
							'file' => 'eval code',
							'line' => 1
						),
						'file' => 'eval code',
						'line' => 1
					)
				),
				'file' => 'eval code',
				'line' => 1
			)
		));
	}

	public function testModulo()
	{
		$parser = new Phalcon\Mvc\View\Engine\Volt\Scanner('{{ 3 % 2 }}');

		$this->assertEquals($parser->scanBlockStatements(), array(
			array(
				'type' => 359,
				'expr' => array(
					array(
						'type' => 37,
						'left' => array(
							'type' => 258,
							'value' => '3',
							'file' => 'eval code',
							'line' => 1
						),
						'right' => array(
							'type' => 258,
							'value' => '2',
							'file' => 'eval code',
							'line' => 1
						),
						'file' => 'eval code',
						'line' => 1
					)
				),
				'file' => 'eval code',
				'line' => 1
			)
		));
	}

	public function testDivision()
	{
		$parser = new Phalcon\Mvc\View\Engine\Volt\Scanner('{{ 3 / 2 }}');

		$this->assertEquals($parser->scanBlockStatements(), array(
			array(
				'type' => 359,
				'expr' => array(
					array(
						'type' => 47,
						'left' => array(
							'type' => 258,
							'value' => '3',
							'file' => 'eval code',
							'line' => 1
						),
						'right' => array(
							'type' => 258,
							'value' => '2',
							'file' => 'eval code',
							'line' => 1
						),
						'file' => 'eval code',
						'line' => 1
					)
				),
				'file' => 'eval code',
				'line' => 1
			)
		));
	}

	public function testExtends()
	{
		$parser = new Phalcon\Mvc\View\Engine\Volt\Scanner('{% extends "base.volt" %}');

		$this->assertEquals($parser->scanBlockStatements(), array(
			array(
				'type' => 310,
				'path' => 'base.volt',
				'file' => 'eval code',
				'line' => 1
			)
		));
	}

	public function testSimpleEcho()
	{
		$parser = new Phalcon\Mvc\View\Engine\Volt\Scanner('{{ 1 }}');

		$this->assertEquals($parser->scanBlockStatements(), array(
			array(
				'type' => 359,
				'expr' => array(
					'type' => 258,
					'value' => '1',
					'file' => 'eval code',
					'line' => 1
				),
				'file' => 'eval code',
				'line' => 1
			)
		));
	}

	public function testSimpleBlock()
	{
		$parser = new Phalcon\Mvc\View\Engine\Volt\Scanner('{% block content %}{% endblock %}');

		$this->assertEquals($parser->scanBlockStatements(), array(
			array(
				'type' => 307,
				'name' => 'content',
				'file' => 'eval code',
				'line' => 1
			)
		));
	}

	public function testRawBlock()
	{
		$parser = new Phalcon\Mvc\View\Engine\Volt\Scanner('{% block content %}Raw Content{% endblock %}');

		$this->assertEquals($parser->scanBlockStatements(), array(
			array(
				'type' => 307,
				'name' => 'content',
				'block_statements' => array(
					array(
						'type' => 357,
						'value' => 'Raw Content',
						'file' => 'eval code',
						'line' => 1
					)
				),
				'file' => 'eval code',
				'line' => 1
			)
		));
	}

	public function testMathBlock()
	{
		$parser = new Phalcon\Mvc\View\Engine\Volt\Scanner('{% block content %}{{ 1 + 2}}{% endblock %}');

		$this->assertEquals($parser->scanBlockStatements(), array(
			array(
				'type' => 307,
				'name' => 'content',
				'block_statements' => array(
					array(
						'type' => 359,
						'expr' => array(
							'type' => 43,
							'left' => array(
								'type' => 258,
								'value' => '1',
								'file' => 'eval code',
								'line' => 1
							),
							'right' => array(
								'type' => 258,
								'value' => '2',
								'file' => 'eval code',
								'line' => 1
							),
							'file' => 'eval code',
							'line' => 1
						),
						'file' => 'eval code',
						'line' => 1
					)
				),
				'file' => 'eval code',
				'line' => 1
			)
		));
	}

	public function testSimpleCacheFragment()
	{
		$parser = new Phalcon\Mvc\View\Engine\Volt\Scanner('{% cache "sidebar" %}<p>data</p>{% endcache %}');

		$this->assertEquals($parser->scanBlockStatements(), array(
		array(
			'type' => 314,
			'expr' => array(
				'type' => 260,
				'value' => 'sidebar',
				'file' => 'eval code',
				'line' => 1
			),
			'block_statements' => array(
				array(
					'type' => 357,
					'value' => '<p>data</p>',
					'file' => 'eval code',
					'line' => 1
				)
			),
			'file' => 'eval code',
			'line' => 1
		)));
	}

	public function testExtendedCacheFragment()
	{
		$parser = new Phalcon\Mvc\View\Engine\Volt\Scanner('{% cache "sidebar" 3600 %}<p>data</p>{% endcache %}');

		$this->assertEquals($parser->scanBlockStatements(), array(
		array(
			'type' => 314,
			'expr' => array(
				'type' => 260,
				'value' => 'sidebar',
				'file' => 'eval code',
				'line' => 1
			),
			'lifetime' => '3600',
			'block_statements' => array(
				array(
					'type' => 357,
					'value' => '<p>data</p>',
					'file' => 'eval code',
					'line' => 1
				)
			),
			'file' => 'eval code',
			'line' => 1
		)));
	}

	public function testComplexCacheFragment()
	{
		$parser = new Phalcon\Mvc\View\Engine\Volt\Scanner('{% cache ("article-" ~ post.id) 3600 %}<p>data</p>{% endcache %}');

		$this->assertEquals($parser->scanBlockStatements(), array(
			array(
				'type' => 314,
				'expr' => array(
					'type' => 356,
					'left' => array(
						'type' => 126,
						'left' => array(
							'type' => 260,
							'value' => 'article-',
							'file' => 'eval code',
							'line' => 1
						),
						'right' => array(
							'type' => 46,
							'left' => array(
								'type' => 265,
								'value' => 'post',
								'file' => 'eval code',
								'line' => 1
							),
							'right' => array(
								'type' => 265,
								'value' => 'id',
								'file' => 'eval code',
								'line' => 1
							),
							'file' => 'eval code',
							'line' => 1
						),
						'file' => 'eval code',
						'line' => 1
					),
					'file' => 'eval code',
					'line' => 1
				),
				'lifetime' => '3600',
				'block_statements' => array(
					array(
						'type' => 357,
						'value' => '<p>data</p>',
						'file' => 'eval code',
						'line' => 1
					)
				),
				'file' => 'eval code',
				'line' => 1
			)
		));
	}

	public function testAutoescapeTrue()
	{
		$parser = new Phalcon\Mvc\View\Engine\Volt\Scanner('{% autoescape true %}Autoescaped: {{ robot.name }}{% endautoescape %}');

		$this->assertEquals($parser->scanBlockStatements(), array(
			array(
				'type' => 317,
				'enable' => 1,
				'block_statements' => array(
					array(
						'type' => 357,
						'value' => 'Autoesacped: ',
						'file' => 'eval code',
						'line' => 1
					),
					array(
						'type' => 359,
						'expr' => array(
							'type' => 46,
							'left' => array(
								'type' => 265,
								'value' => 'robot',
								'file' => 'eval code',
								'line' => 1
							),
							'right' => array(
								'type' => 265,
								'value' => 'name',
								'file' => 'eval code',
								'line' => 1
							),
							'file' => 'eval code',
							'line' => 1
						),
						'file' => 'eval code',
						'line' => 1
					)
				),
				'file' => 'eval code',
				'line' => 1
			)
		));
	}

	public function testAutoescapeFalse()
	{
		$parser = new Phalcon\Mvc\View\Engine\Volt\Scanner('{% autoescape false %}Autoescaped{% endautoescape %}');

		$this->assertEquals($parser->scanBlockStatements(), array(
			array(
				'type' => 317,
				'enable' => 0,
				'block_statements' => array(
					array(
						'type' => 357,
						'value' => 'Autoesacped',
						'file' => 'eval code',
						'line' => 1
					)
				),
				'file' => 'eval code',
				'line' => 1
			)
		));
	}
}