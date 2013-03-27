<?php

/**
 *
 * @author Nicolas Van Labeke (https://github.com/vanch3d)
 *
 */
class Draft extends Model {
	/**
	 *
	 * @key		Draft/task_id
	 * @return 	Task
	 * @see 	ORMWrapper
	 */
	public function task() {
		return $this->belongs_to('Task');
	}

	/**
	 * @key		Draft/user_id
	 * @return 	Users
	 * @see 	ORMWrapper
	 */
	public function user() {
		return $this->belongs_to('Users');
	}

	/**
	 * 
	 * @return array
	 */
	public function getParasenttok()
	{
		$data = array();
		$json = $this->analysis;
		$rr = json_decode($json);
		//var_dump($rr->se_stats);
		//var_dump($rr->se_reotg_array);

		$rank = array();
		foreach ($rr->se_ranked as $i => $item)
			$rank[$item[1]] = $i;

		$inc = 0;
		foreach ($rr->parasenttok as $i => &$par)
		{
			foreach ($par as $j => &$sent)
			{	
				$sent = array(
					'id' => $inc,
					'text' => $sent,
					'tag' => $rr->se_reotg_array[$inc][2]
				);
				if ($rank[$inc]!=null)
					$sent['rank'] = $rank[$inc];
				$inc++;
				
			}
			
		}
		return $rr->parasenttok;
	}
}