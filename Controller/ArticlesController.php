<?php
App::uses('AppController', 'Controller');
App::uses('Tag', 'Model');
App::uses('User', 'Model');
App::uses('Link', 'Model');
Configure::load("static");
/**
 * Articles Controller
 *
 * @property Article $Article
 * @property PaginatorComponent $Paginator
 */
class ArticlesController extends AppController {

	public function isAuthorized($user) {
		// 登録済ユーザーは投稿できる
		if ($this->action === 'add'|| $this->action === 'transmitter') {
			return true;
		}

		// 投稿のオーナーは編集や削除ができる
		if (in_array($this->action, array('edit', 'delete'))) {
			$postId = (int) $this->request->params['pass'][0];
			if ($this->Article->isOwnedBy($postId, $user['id'])) {
				return true;
			}else {
				return false;
			}
		}

		return parent::isAuthorized($user);
	}
	public $presetVars = array(
			'user_id' => array('type' => 'value'),
			'keyword' => array('type' => 'value'),
			'andor' => array('type' => 'value'),
			'from' => array('type' => 'value'),
			'to' => array('type' => 'value'),
	);
	//public $uses = array('Article');
	//public $paginate = array( 'limit' => 25);
	 public function beforeFilter() {
        parent::beforeFilter();
        $this->Security->validateOnce = false;
        $this->Security->validatePost = false;
        $this->Security->csrfCheck = false;
//         $this->Auth->allow();
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
	public $components = array('Search.Prg','Paginator','Common','Basic','Cookie','Session');
	public $helpers = array(
			'Html',
			'Session'
	);

	public function singletrikeytable($id = null,$trikey = null){
		parent::singletrikeytable($id,$trikey);
	}
/**
 * index method
 *
 * @return void
 */
	public function index() {
		parent::index();
		$this->Article->recursive = 0;
// 		$this->paginate->setting = array('order'=> array('Article.modified' => 'DESC'));

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
		parent::view($id);
// 		$this->Article->read(null,$id);
		$this->i = 0;
		$trikeyID = Configure::read('tagID.search');


		$this->set('headtaghashes', $this->taghash);
		$targetID = $id;

	}
	/**
	 * anonymous_view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function anonymous_view($id = null) {

		if($this->request->data['Article']['name'] != null){
			$this->Common->triarticleAdd($this,'Article',$this->Auth->user('id'),$id,$options);
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

		$this->pageTitle = $this->taghashgen["Article"]['name'];
		$this->Article->read(null,$id);
		$this->set('idre', $id);
		$this->i = 0;
		$trikeyID = Configure::read('tagID.search');//tagConst()['searchID'];
		$this->set('article',$this->taghashgen);
		$this->Common->SecondDem($this,"Tag","Tag.ID",$trikeyID,$id);
		$this->set('headresults', $this->returntribasic);
		$this->set('headtaghashes', $this->taghash);
		$targetID = $id;
		$this->Common->trifinderbyid($this,$id);
		$this->loadModel('User');
		$this->loadModel('Key');
		$this->set('articleresults', $this->articleparentres);
		$this->set('tagresults', $this->tagparentres);
		$this->set('taghashes', $this->taghash);
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
		if (!$this->request->is('ajax')){
			$this->set('currentUserID', $this->Auth->user('id'));
			$this->loadModel('User');
			$this->set( 'ulist', $this->User->find( 'list', array( 'fields' => array( 'ID', 'username'))));
		}
		if ($this->request->is('post')) {
			debug($this->request->data("Article.user_id"));
// 			if (is_null($this->request->data("Article.user_id"))){
// 				$this->request->data("Article.user_id") = AuthComponent::user("id");
// 			}
			if ($this->Article->save($this->request->data)) {
				if ($this->request->is('ajax')){
					$this->render('ajaxAdd');
					$this->autoRender = false;
					$this->set('res', array("id" => $this->Article->getLastInsertID()));
				}else{
					$this->Session->setFlash(__('The article has been saved.'));
				}
			} else {
				$this->Session->setFlash(__('The article could not be saved. Please, try again.'));

			}
		}
	}

	function  ajaxAdd(){
		$rTags = (array) $this->request->query("rTag_ids");
		$this->autoRender = false;
		$Taguser = new Taguser();
		if ($this->Article->save($this->request->query)) {
			$redTags = array();
			if (!empty($rTags)){
				foreach ($rTags as $rTagId){
					$this->Common->triAddbyid($this,$this->Auth->user("id"),
							$rTagId,$this->Article->getLastInsertID(),
							array("key" => Configure::read("tagID.search")));
					$redTags = $redTags +
							$this->Basic->rCheck(Configure::read('tagID.search'),
									$this->Article->getLastInsertID(),$rTagId)
							;

				}
			}
				return json_encode(array("ID" => $this->Article->getLastInsertID(),"rTags" =>$redTags));
		} else {
			throw new NotFoundException(__('missed add article'));
		}
	}
	function add2(){
			$this->set('currentUserID', $this->Auth->user('id'));
			$this->loadModel('User');
			$this->set( 'ulist', $this->User->find( 'list', array( 'fields' => array( 'ID', 'username'))));
	}
	function addArticles($target_ids= NULL,$trikey= NULL,$user_id= NULL,$name= NULL,$options = NULL){
		parent::vaddArticles($target_ids,$trikey,$user_id,$name,$options);
	}
	function ajaxRTagAdd(){
		$this->autoRender = false;
		$user_id = $this->request->query("user_id");
		$articles = $this->request->query("articles");
		if (empty($user_id)){
			$user_id = $this->Auth->user("id");
		}
		$Taglink = new Taglink();
		foreach ($articles as $key => $article){
			foreach ($this->request->query("rTagIds") as $i => $rTagId){
				$related = false;
				$articles[$key]["rTags"] = (array) $article["rTags"];
				foreach ($article["rTags"] as $addedTag){//関連づけ済みのタグ
					if ($addedTag["Tag"]["ID"] == $rTagId){//すでに追加されているか？
// 						unset($articles[$key]);
						$related = true;//すでに追加されている
						goto relatedTag_level;//追加されていたら、次の追加するタグを走査するレベルに飛ぶ
					}
				}
				if (!$related){
					$this->Common->triAddbyId($this,$this->Auth->user("id"),
							$rTagId,$article["ID"],array("key" => Configure::read("tagID.search")));
					//返値に追加

					$newTag = $this->Basic->rCheck(Configure::read('tagID.search'),
							$article["ID"],$rTagId);
					$articles[$key]["rTags"] = $articles[$key]["rTags"] +
					$newTag;
				}

				relatedTag_level:
			}

		}
		//entity.id に対して付与したタグ
// 		@example array("entity.ID"=> array("id","name"));
//ここで全部　$scope.primeを書き換える？
		return json_encode($articles);

	}
/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		session_start();
		parent::edit($id);

	$this->Session->write(`before.URL`,"a");
		if (!$this->Article->exists($id)) {
			throw new NotFoundException(__('Invalid article'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Article->save($this->request->data)) {
				$this->redirect($this->Session->read('beforeURL'));
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
	}
	function ajaxRDel(){
		$this->autoRender =false;
		$data = $this->request->query;

		$Link = new Link();
		if (empty($data["user_id"])){
			$data["Link.user_id"] = $this->Auth->user("id");
		}else{
			$data["Link.user_id"] =$data["user_id"];
		}
		$data["Link.ID"] =$data["ID"];
		if ($Link->deleteAll($data)){
			//         		if($this->Basic->taglimitcountup($this)){


			//         		}else{
			return true;
			//         		}
		} else {
			return false;
// 			throw new NotFoundException(__('not exist'));
		}
	}
}

