<?php
/**
 * Volt Parser Testsuite
 *
 * @author Wenzel Pünter <wenzel@phelix.me>
*/
class VoltParserTest extends BaseTest
{
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
}