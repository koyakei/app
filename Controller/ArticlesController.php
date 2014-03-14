<?php
App::uses('AppController', 'Controller');
App::uses('Tag', 'Model');
App::uses('User', 'Model');
App::uses('Link', 'Model');
App::uses('Article', 'Model');
Configure::load("static");
/**
 * Articles Controller
 *
 * @property Article $Article
 * @property PaginatorComponent $Paginator
 */
class ArticlesController extends AppController {
	//public $uses = array('Article');
	public $paginate = array(
			//PostalCodeモデルの設定
				'Article'=>array(
						'order' => array('modified' => 'desc')
				)
			);
	 public function beforeFilter() {
        parent::beforeFilter();
        $this->Security->validateOnce = false;
        $this->Security->validatePost = false;
        $this->Security->csrfCheck = false;
        $this->Auth->allow('logout','view','index');
	$this->Auth->authenticate = array(
		'Basic' => array('user' => 'admin'),
		//'Form' => array('user' => 'Member')
		);
	}
/**
 * Components
 *
 * @var array
 */
	public $components = array('Auth','Search.Prg','Paginator','Common','Basic','Cookie','Session',
			'Security',
			'Search.Prg','Users.RememberMe');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Article->recursive = 0;
		$this->set('articles', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
        	if($this->request->data['tagRadd']['add'] == true){
        		$that->Session->setFlash(__('radd に入ってきている。'));
        		$this->Basic->tagRadd($this);
        		$this->Basic->social($this);
        	}else {
        		//$this->Session->setFlash(__('radd に入ってinai。'));
        	}
		if($this->request->data['Article']['name'] != null){
			//$this->keyid = $this->request->data['Article']['keyid'];
        	debug($this->request->data);
			$this->Common->triarticleAdd($this,'Article',$this->Auth->user('id'),$id);
			$this->Basic->social($this);
		}
		if($this->request->data['Tag']['name'] != null){
			$this->keyid = $this->request->data['Tag']['keyid'];
			$this->Common->tritagAdd($this,"Tag",$this->Auth->user('id'),$this->request->params['pass'][0]);
			$this->Basic->social($this);
		}

		$this->set('idre', $id);
		if (!$this->Article->exists($id)) {
			throw new NotFoundException(__('Invalid tag'));
		}
		$this->taghashgen = $this->Article->find('first',array('conditions' => array('Article.' . $this->Article->primaryKey => $id)));
		$this->Article->read(null,$id);
		$this->set('idre', $id);
		$this->i = 0;
		$trikeyID = Configure::read('tagID.search');//tagConst()['searchID'];
		$this->set('article',$this->taghashgen);
		$this->Common->SecondDem($this,"Tag","Tag.ID",$trikeyID,$id);
		$this->set('headresults', $this->returntribasic);
		$this->set('headtaghashes', $this->taghash);
		$targetID = $id;
		$this->Common->trifinderbyid($this,$id,$option);
		$this->loadModel('User');
		$this->loadModel('Key');
		$this->set( 'keylist', $this->Key->find( 'list', array( 'fields' => array( 'ID', 'name'))));
		$this->set( 'ulist', $this->User->find( 'list', array( 'fields' => array( 'ID', 'username'))));
		$this->set('currentUserID', $this->Auth->user('id'));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->set('currentUserID', $this->Auth->user('id'));
		$this->loadModel('User');
		$this->set( 'ulist', $this->User->find( 'list', array( 'fields' => array( 'ID', 'username'))));
		if ($this->request->is('post')) {
			if ($this->Article->save($this->request->data)) {
				$this->Session->setFlash(__('The article has been saved.'));
				//return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The article could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Article->exists($id)) {
			throw new NotFoundException(__('Invalid article'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Article->save($this->request->data)) {
				$this->Session->setFlash(__('The article has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The article could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Article.' . $this->Article->primaryKey => $id));
			$this->request->data = $this->Article->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Article->id = $id;
		if (!$this->Article->exists()) {
			throw new NotFoundException(__('Invalid article'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Article->delete()) {
			$this->Session->setFlash(__('The article has been deleted.'));
		} else {
			$this->Session->setFlash(__('The article could not be deleted. Please, try again.'));
		}
		//return $this->redirect(array('action' => 'index'));
		return $this->redirect($this->referer());
	}}
