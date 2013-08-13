<?php

/**
 * 
 * @author Nicolas Van Labeke (https://github.com/vanch3d)
 *
 */
class DemoController extends UserController
{
	
	public function showDraft($draft,$cmd=null)
	{
		parent::showDraft($draft,$cmd);
	}
	
	public function groupTexts($draft)
	{
		$dr = $this->getDraft($draft);
		$tsk = $dr->task()->find_one();
		
		$parasenttok = $dr->getParasenttok();
		$data = $dr->as_array();
		$analysis = $dr->getAnalysis();
		
		
		$this->render('drafts/group.texts',array(
				'task' => $tsk->as_array(),
				'draft' => $dr->as_array()
		));
		
	}
	
	
	public function groupGraphics($draft)
	{
		$dr = $this->getDraft($draft);
		$tsk = $dr->task()->find_one();
		
		$parasenttok = $dr->getParasenttok();
		$data = $dr->as_array();
		$analysis = $dr->getAnalysis();
		
		
		$this->render('drafts/group.graphics',array(
				'task' => $tsk->as_array(),
				'draft' => $dr->as_array()
		));
		
	}
	
	
	public function groupActions($draft)
	{
		$dr = $this->getDraft($draft);
		$tsk = $dr->task()->find_one();
		
		$parasenttok = $dr->getParasenttok();
		$data = $dr->as_array();
		$analysis = $dr->getAnalysis();
		
		
		$this->render('drafts/group.actions',array(
				'task' => $tsk->as_array(),
				'draft' => $dr->as_array()
		));
		
	}
	
}