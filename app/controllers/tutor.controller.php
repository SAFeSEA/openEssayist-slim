<?php

/**
 * 
 * @author Nicolas Van Labeke (https://github.com/vanch3d)
 *
 */
class TutorController extends Controller
{
	
	public function indent($json)
	{
		$result      = '';
		$pos         = 0;
		$strLen      = strlen($json);
		$indentStr   = '  ';
		$newLine     = "\n";
		$prevChar    = '';
		$outOfQuotes = true;
	
		for ($i=0; $i<=$strLen; $i++) {
	
			// Grab the next character in the string.
			$char = substr($json, $i, 1);
	
			// Are we inside a quoted string?
			if ($char == '"' && $prevChar != '\\') {
				$outOfQuotes = !$outOfQuotes;
	
				// If this character is the end of an element,
				// output a new line and indent the next line.
			} else if(($char == '}' || $char == ']') && $outOfQuotes) {
				$result .= $newLine;
				$pos --;
				for ($j=0; $j<$pos; $j++) {
					$result .= $indentStr;
				}
			}
	
			// Add the character to the result string.
			$result .= $char;
	
			// If the last character was the beginning of an element,
			// output a new line and indent the next line.
			if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
				$result .= $newLine;
				if ($char == '{' || $char == '[') {
					$pos ++;
				}
	
				for ($j = 0; $j < $pos; $j++) {
					$result .= $indentStr;
				}
			}
	
			$prevChar = $char;
		}
	
		return $result;
	}
	
	
	public function getJSON()
	{
		$response = $this->app->response();
		$response['Content-Type'] = 'application/json';
		$response['X-Powered-By'] = 'openEssayist';
		$response->status(200);
		
		$json = array(
			'orchestrator' => TutorController::getActivities(),
			'activity' => TutorController::getViewConfigurations(),
		);
		
		$response->body($this->indent(json_encode($json)));
		
	}
	
	
	/**
	 * 
	 * @return array
	 */
	public static function getActivities()
	{
		$activities = array();
		
		$activities['act-struct-01'] = array(
				'title' => "Structure",
				'description' => "OpenEssayist tried to identify the structure of your essay (introduction, conclusion, ...)",
				'prompt' => "You can explore it",
				'tasks' => array(
						'act1-res1' => array(
							'text' => 'In the main text...',
							'config' => 'show-1',
							'url' => 'me.draft.show'
								),
						'act1-res2' => array(
							'text' => 'By its word count ...',
							'url' => 'me.draft.view.structure'
								),
					),
				'action' => array()
		);
		$activities['act-struct-02'] = array(
				'title' => "Structure",
				'description' => "What do you think of your introduction and conclusion?",
				'prompt' => "You can explore them",
				'tasks' => array(
						'act2-res1' => array(
								'text' => 'In the main text...',
								'config' => 'show-2',
								'url' => 'me.draft.show'
						),
				),
				'action' => array()
		);
		$activities['act-keyword-01'] = array(
				'title' => "Key Words",
				'description' => "OpenEssayist has identified the most 'important' key words in your text",
				'prompt' => "You can explore them",
				'tasks' => array(
						'act3-res1' => array(
								'text' => 'As a list ...',
								'config' => 'show-none',
								'url' => 'me.draft.keywords'
						),
						'act3-res2' => array(
								'text' => 'As a cloud ...',
								'config' => 'show-none',
								'url' => 'me.draft.view.cloud'
						),
						),
				'action' => array()
		);
		$activities['act-summary-01'] = array(
				'title' => "Summary",
				'description' => "OpenEssayist has created a summary of your essay, by extracting the most 'important' sentences in it.",
				'prompt' => "You can explore it",
				'tasks' => array(
						'act4-res1' => array(
								'text' => 'As a summary',
								'config' => 'show-none',
								'url' => 'me.draft.sentence'
						),
				),
				'action' => array()
		);
		$activities['act-keyword-02'] = array(
				'title' => "Key Words",
				'description' => "Check how your keywords are used in your essay",
				'prompt' => "You can explore them",
				'tasks' => array(
						'act5-res1' => array(
								'text' => 'In the main text ...',
								'config' => 'show-3',
								'url' => 'me.draft.show'
						),
						'act5-res2' => array(
								'text' => 'In the dispersion graph...',
								'config' => 'show-none',
								'url' => 'me.draft.view.dispersion'
						),
						'act5-res3' => array(
								'text' => 'As a network...',
								'config' => 'show-none',
								'url' => 'me.draft.view.graph'
						),
					),
				'action' => array()
		);
		$activities['act-keyword-03'] = array(
				'title' => "Organise your Key Words",
				'description' => "A good thing to do is to organise these keywords into your own categories.",
				'prompt' => "",
				'tasks' => array(
						'act5-res1' => array(
								'text' => 'Organise',
								'config' => 'show-none',
								'url' => 'me.draft.act.keyword'
						),
				),
				'action' => array()
		);
		$activities['act-keyword-04'] = array(
				'title' => "Key Words",
				'description' => "Now that you have organised your keywords, check again how they are used in your essay",
				'tasks' => array(
						'act5-res1' => array(
								'text' => 'In the main text ...',
								'config' => 'show-4',
								'url' => 'me.draft.show'
						),
						'act5-res2' => array(
								'text' => 'In the dispersion graph...',
								'config' => 'show-none',
								'url' => 'me.draft.view.dispersion'
						),
						'act5-res3' => array(
								'text' => 'As a network...',
								'config' => 'show-none',
								'url' => 'me.draft.view.graph'
						),
				),
				'action' => array()
		);
		
		return $activities ;
	}
	
	/**
	 * 
	 * @return array
	 */
	public static function getViewConfigurations()
	{
		$configs = array();
		$configs['show-1'] = array(
			'title' => "",
			'task' => "",
			'hasTour' => true,
			'hasReport' => true,
			'config' => array(
				'allowOption' => false,
				'structure'=> array(
					'show'=> true,
					'check' => null//array('#+s:i#','#+s:c#')
					),
				'sentence'=> array(
					'show'=> false,
					//'range' => array('min'=> 0,'max'=>15)
					),
				'keyword'=> array(
					'show'=> false,
					//'check'=> array('category_all')
				),
			)
		);
		$configs['show-2'] = array(
				'config' => array(
						'allowOption' => false,
						'structure'=> array(
								'show'=> true,
								'modify' => false,
								'check' => array('#+s:i#','#+s:c#')
						),
						'sentence'=> array(
								'show'=> false,
								//'range' => array('min'=> 0,'max'=>15)
						),
						'keyword'=> array(
								'show'=> false
						),
				)
		);
		$configs['show-3'] = array(
				'config' => array(
						'allowOption' => false,
						'structure'=> array(
								'show'=> true,
								'modify' => true,
						),
						'sentence'=> array(
								'show'=> false,
								//'range' => array('min'=> 0,'max'=>15)
						),
						'keyword'=> array(
								'show'=> true,
								'check'=> array('category_all')
						),
				)
		);
		$configs['show-4'] = array(
				'config' => array(
						'allowOption' => false,
						'structure'=> array(
								'show'=> true,
								'modify' => true,
						),
						'sentence'=> array(
								'show'=> true,
								'range' => array('min'=> 0,'max'=>15)
						),
						'keyword'=> array(
								'show'=> true,
						),
				)
		);
		
		return $configs;
	} 
}