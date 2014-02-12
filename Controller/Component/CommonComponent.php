<?php
App::uses('Tag', 'Model');
App::uses('User', 'Model');
App::uses('Link', 'Model');
App::uses('Article', 'Model');
App::uses('BasicComponent', 'Controller/Component');
Configure::load("static");
class CommonComponent extends Component {
        public $components = array('Basic');
	public function replyarticleAdd(&$that = null) {
		if ($that->request->params['pass'][0] != null) {
			$Article = new Article();
			$Article->create();
			$that->userID = $that->Auth->user('ID');
			if ($Article->save($that->request->data)) {
				$that->last_id = $Article->getLastInsertID();
				//debug($Article->getLastInsertID());
				$that->request->data = null;
				$that->request->data['Link'] = array(
					'user_id' => 1,
					'LFrom' => $that->request->params['pass'][0],//2138
					'LTo' => $that->last_id,
					'quant' => 1,
					'created' => date("Y-m-d H:i:s"),
					'modified' => date("Y-m-d H:i:s"),
				);
				$Link = new Link();
				$Link->create();
				if ($Link->save($that->request->data)) {
				$that->last_id = $Link->getLastInsertID();
				$that->request->data = null;
				$that->request->data['Link'] = array(
					'user_id' => 1,
					'LFrom' => 2138,//
					'LTo' => $that->last_id,
					'quant' => 1,
					'created' => date("Y-m-d H:i:s"),
					'modified' => date("Y-m-d H:i:s"),
				);
				$Link->create();
					if ($Link->save($that->request->data)) {
						$that->Session->setFlash(__('The article has been saved.'));

					} else {
						$that->Session->setFlash(__('The article could not be saved. Please, try again.'));
					}
				}
			}
		}
	}

	public function triarticleAdd(&$that = null,$model,$userID) {
		debug($that->name);
		/*$that->Tag->setValue($that->plugin,$that->name.$that->action,$that->view);*/
		if ($userID == null) {
			$userID = Configure::read('acountID.admin');
		}
		debug($userID);
		if ($that->request->params['pass'][0] != null) {
			$Article = new $model();
			$Article->create();
			if ($Article->save($that->request->data)) {
				$that->last_id = $Article->getLastInsertID();
				$that->request->data = null;
				$that->request->data['Link'] = array(
					'user_id' => $userID,
					'LFrom' => $that->request->params['pass'][0],//2138
					'LTo' => $that->last_id,
					'quant' => 1,
					'created' => date("Y-m-d H:i:s"),
					'modified' => date("Y-m-d H:i:s"),
				);
				debug($that->request->data);
				$Link = new Link();
				$Link->create();
				if ($Link->save($that->request->data)) {
					$that->last_id = $Link->getLastInsertID();
					$that->request->data = null;
					$that->request->data['Link'] = array(
						'user_id' => $userID,
						'LFrom' => $that->keyid,//
						'LTo' => $that->last_id,
						'quant' => 1,
						'created' => date("Y-m-d H:i:s"),
						'modified' => date("Y-m-d H:i:s"),
					);
					$Link->create();
					if ($Link->save($that->request->data)) {
						$that->Session->setFlash(__('The article has been saved.'));

					} else {
						$that->Session->setFlash(__('The article could not be saved. Please, try again.'));
					}
				}else {
					debug("misslink1");
				}
			}

		}

	}

	public function tritagAdd(&$that = null,$model,$userID,$targetFromID) {
		if ($userID == null) {
			$userID = Configure::read('acountID.admin');
		}
		debug(Configure::read('acountID.admin'));
		$tagID = $that->$model->find('first',
			array(
				'conditions' => array('name' => $that->request->data['Tag']['name']),
				'user_id' => $userID,
				'fields' => array('Tag.ID'),
				'order' => 'Tag.ID'
			)
		);
		if($tagID['Tag']['ID'] == null){
			$Article = new $model();
			$Article->create();
			$Article->save($that->request->data);
			$that->last_id = $Article->getLastInsertID();
		}else{
			$that->last_id = $tagID['Tag']['ID'];
			if ($that->request->params['pass'][0] != null) {
				$that->request->data = null;
				$that->request->data['Link'] = array(
						'user_id' => $userID,
						'LFrom' => $targetFromID,//$that->request->params['pass'][0],
						'LTo' => $that->last_id,
						'quant' => 1,
						'created' => date("Y-m-d H:i:s"),
						'modified' => date("Y-m-d H:i:s"),
				);
				debug($that->request->data['Link']);
				$Link = new Link();
				$Link->create();
				if ($Link->save($that->request->data)) {
					$that->last_id = $Link->getLastInsertID();
					$that->request->data = null;
					$that->request->data['Link'] = array(
							'user_id' => $userID,
							'LFrom' => $that->keyid,//
							'LTo' => $that->last_id,
							'quant' => 1,
							'created' => date("Y-m-d H:i:s"),
							'modified' => date("Y-m-d H:i:s"),
					);
					$Link->create();
					if ($Link->save($that->request->data)) {
						$that->Session->setFlash(__('The article has been saved.'));

					} else {
						$that->Session->setFlash(__('The article could not be saved. Please, try again.'));
					}
				}else {
					debug("miss");
				}
			}
		}
	}

	public function trifinder(&$that = null) {
		$id = $that->request['pass'][0];
		$this->Basic->tribasic($that,"search","Article","Article.ID",$id);
		$that->parentres = $that->returntrybasic;
		$that->k = 0;
		$that->j = 0;
		$that->i = 0;
		$that->taghash = array();
		$trikeyID = Configure::read('tagID.search');//tagConst()['searchID'];
		$that->Tag->unbindModel(array('hasOne'=>array('TO')), false);
		foreach ($that->parentres as $result){
			$res = $result['Article']['ID'];
			$this->Basic->tribasic($that,"search","Tag",$res,"Tag.ID");
			$that->taghashgen = $that->returntrybasic;
			foreach ($that->taghashgen as $tag){
				$that->subtagID = $tag['Tag']['ID'];
				$that->parentres[$that->i]['subtag'][$that->subtagID] = $tag;
				if ($that->taghash[$that->subtagID] == null) {
					$that->taghash[$that->subtagID] = array( 'ID' => $tag['Tag']['ID'], 'name' =>  $tag['Tag']['name']);
				}
			}
			$that->i++;
		}
		$that->loadModel('User');
		$that->loadModel('Key');
		$that->userlist = $that->User->find( 'list', array( 'fields' => array( 'ID', 'username')));
		$that->set( 'ulist', $that->userlist);
		$that->set('taghashes', $that->taghash);
		$that->set('results', $that->parentres);
	}
	public function trireplyfinder(&$that = null) {
		$id = $that->request['pass'][0];
		$this->Basic->tribasic($that,"reply","Article","Article.ID",$id);
		$that->parentres = $that->returntrybasic;
		$that->k = 0;
		$that->j = 0;
		$that->i = 0;
		$that->taghash = array();
		$trikeyID = Configure::read('tagID.search');//tagConst()['searchID'];
		//$that->Tag->unbindModel(array('hasOne'=>array('TO')), false);
		foreach ($that->parentres as $result){
			$res = $result['Article']['ID'];
			$this->Basic->tribasicfind($that,"search","Tag",$res,"Tag.ID");
			$that->taghashgen = $that->returntrybasic;
			foreach ($that->taghashgen as $tag){
				$that->subtagID = $tag['Tag']['ID'];
				$that->parentres[$that->i]['subtag'][$that->subtagID] = $tag;
				if ($that->taghash[$that->subtagID] == null) {
					$that->taghash[$that->subtagID] = array( 'ID' => $tag['Tag']['ID'], 'name' =>  $tag['Tag']['name']);
				}
			}
			$that->i++;
		}
		$that->loadModel('User');
		$that->loadModel('Key');
		$that->set( 'keylist', $that->Key->find( 'list', array( 'fields' => array( 'ID', 'name'))));
		$that->set( 'ulist', $that->User->find( 'list', array( 'fields' => array( 'ID', 'username'))));
		$that->set('taghashes', $that->taghash);
		$that->set('results', $that->parentres);
	}
	public function trifinderbyid(&$that = null) {
		if ($_SESSION['selected'] == null) {
			$_SESSION['selected'] = Configure::read('tagID.reply');
		}
		if ($that->request->data['keyid']['keyid'] == null) {
			$that->request->data['keyid']['keyid'] = $_SESSION['selected'];
		}
		$id = $that->request['pass'][0];
		//debug($that->request->data['keyid']['keyid']);
		$this->Basic->tribasicfiderbyid($that,$that->request->data['keyid']['keyid'],"Article","Article.ID",$id);
		$that->articleparentres = $that->returntribasic;
		$that->i = 0;
		$that->taghash = array();
		$trikeyID = Configure::read('tagID.search');//tagConst()['searchID'];
		foreach ($that->articleparentres as $result){
			$res = $result['Article']['ID'];
			$this->Basic->tribasicfiderbyid($that,Configure::read('tagID.search'),"Tag",$res,"Tag.ID");
			$that->taghashgen = $that->returntribasic;
			foreach ($that->taghashgen as $tag){
				$that->subtagID = $tag['Tag']['ID'];
				$that->articleparentres[$that->i]['subtag'][$that->subtagID] = $tag;
				if ($that->taghash[$that->subtagID] == null) {
					$that->taghash[$that->subtagID] = array( 'ID' => $tag['Tag']['ID'], 'name' =>  $tag['Tag']['name']);
				}
			}
			$that->i++;
		}
		$that->i = 0;
		$this->Basic->tribasicfiderbyid($that,$that->request->data['keyid']['keyid'],"Tag","Tag.ID",$id);
		$that->tagparentres = $that->returntribasic;
		$trikeyID = Configure::read('tagID.search');//tagConst()['searchID'];
		foreach ($that->tagparentres as $result){
			$this->Basic->tribasicfiderbyid($that,Configure::read('tagID.search'),"Tag",$result['Tag']['ID'],"Tag.ID");//ここのtribasicfinderbyidの使い方が大事
			$that->taghashgen = $that->returntribasic;
			foreach ($that->taghashgen as $tag){
				$that->subtagID = $tag['Tag']['ID'];
				$that->tagparentres[$that->i]['subtag'][$that->subtagID] = $tag;
				if ($that->taghash[$that->subtagID] == null) {
					$that->taghash[$that->subtagID] = array( 'ID' => $tag['Tag']['ID'], 'name' =>  $tag['Tag']['name']);
				}
			}
			$that->i++;
		}
		$that->loadModel('User');
		$that->loadModel('Key');
		$that->set( 'keylist', $that->Key->find( 'list', array( 'fields' => array( 'ID', 'name'))));
		$that->set( 'ulist', $that->User->find( 'list', array( 'fields' => array( 'ID', 'username'))));
		$that->set('taghashes', $that->taghash);
		$that->set('articleresults', $that->articleparentres);
		$that->set('tagresults', $that->tagparentres);
	}
	public function SecondDem(&$that,$model,$order,$keyID,$id){
		$that->Basic->tribasicfiderbyid($that,$keyID,$model,$id,$order);
		$that->taghashgen = $that->returntribasic;
			foreach ($that->taghashgen as $tag){
				$that->subtagID = $id;
				$that->taghashgen[$that->i]['subtag'][$that->subtagID] = $tag;
				if ($that->taghash[$that->subtagID] == null) {
					$that->taghash[$that->subtagID] = array( 'ID' => $id, 'name' =>  $this->taghashgen[0]['Tag']['name']);
				}
			}
			$that->i++;
		return $that->taghash;
		return $that->returntribasic;
	}
}