<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

public $components = array(
    'Session',
    'Auth' => array(
// 		        'loginAction' => array(
// 		            'controller' => 'users',
// 		            'action' => 'login',
// 		            'plugin' => 'users'
// 		        ),
// 		        'authError' => 'Did you really think you are allowed to see that?',
// 		        'authenticate' => array(
// 		            'Form' => array(
// 		                'fields' => array('username' => 'email')
// 		            )
// 		        ),

	        'loginRedirect' => array('controller' => 'tags', 'action' => 'search'),
	        'logoutRedirect' => array(
	        		'controller' => 'articles', 'action' => 'index'
	    	),
        'authorize' => array('Controller')
    ),
	'Security' => array(
	'csrfCheck' => false
	)
);
public function isAuthorized($user) {
	    if ((isset($user['role']) && $user['role'] === 'admin') or (isset($user['role']) && $user['role'] === 'registered')) {
	 		return true;
	    }else {
	 		$this->Auth->login('52fdeb54-e344-4fcc-8f8c-405fe0e4e673');
	 		return true;
	    }
	    return false;
    }



    public function beforeFilter() {
    	if($this->Auth->user('id') === null){
    		$this->Auth->login('52fdeb54-e344-4fcc-8f8c-405fe0e4e673');
    	}
        $this->Auth->allow('login');

    }/*
    public function restoreLoginFromCookie() {
    	$this->Cookie->name = 'Users';
    	$cookie = $this->Cookie->read('rememberMe');
    	if (!empty($cookie) && !$this->Auth->user()) {
    		$data['User'][$this->Auth->fields['username']] = $cookie
    		[$this->Auth->fields['username']];
    		$data['User'][$this->Auth->fields['password']] = $cookie
    		[$this->Auth->fields['password']];
    		$this->Auth->login($data);
    	}
    }*/
    function beforeRender() {
    	$this->set('title_for_layout', $this->pageTitle);
    }
}
