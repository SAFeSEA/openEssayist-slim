<?php


/**
 * Controller for Group-based content
 * @author "Nicolas Van Labeke (https://github.com/vanch3d)"
 *
 */
class GroupController extends Controller
{

	/**
	 * @route "me/group/"
	 */
	public function index()
	{
		/* @var $u Users */
		$u = Model::factory('Users')->find_one($this->user['id']);
		
		/* @var $g Group */
		$g = $u->group()->find_one();
		
		$all = $g->users()->count();
		$act = $g->users()->where_equal('active', true)->count();
		
		$this->render('group/dashboard',array(
				'group' => $g->as_array(),
				'data' => array(
					'users' => $all,
					'active' => $act
					)
		));
	}
	
}