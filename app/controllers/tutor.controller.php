<?php

/**
 * 
 * @author Nicolas Van Labeke (https://github.com/vanch3d)
 *
 */
class TutorController extends Controller
{
	/**
	 * 
	 * @return array
	 */
	public static function getActivities()
	{
		$activities = array();
		
		$activities['act1'] = array(
				'title' => "Structure",
				'description' => "OpenEssayist tried to identify the structure of your essay (introduction, conclusion, ...)",
				'prompt' => "You can explore it",
				'res' => array(
						'act1-res1' => array(
							'text' => 'In the main text (tailored)...',
							'config' => 'show-1',
							'url' => 'me.draft.show'
								),
						'act1-res1b' => array(
							'text' => 'In the main text (free access)...',
							'url' => 'me.draft.show'
								),
						'act1-res1c' => array(
							'text' => 'In the main text (restricted)...',
							'config' => 'show-2',
							'url' => 'me.draft.show'
								),
						'act1-res2' => array(
							'text' => 'As metrics ...',
							'url' => 'me.draft.view.structure'
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
								'show'=> true,
								'check'=> array('category_all')
						),
				)
		);
		
		return $configs;
	} 
}