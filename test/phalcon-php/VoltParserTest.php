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
}