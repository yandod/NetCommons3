<?php
/**
 * PageControllerクラス
 *
 * <pre>
 * ページメニュー表示用コントローラ
 * </pre>
 *
 * @copyright     Copyright 2012, NetCommons Project
 * @package       App.Controller
 * @author        Noriko Arai,Ryuji Masukawa
 * @since         v 3.0.0.0
 * @license       http://www.netcommons.org/license.txt  NetCommons License
 */
class PageController extends PageAppController {
/**
 * page_id
 * @var integer
 */
	public $page_id = null;

/**
 * hierarchy
 * @var integer
 */
	public $hierarchy = null;

/**
 * ページメニュー表示
 * @param   void
 * @return  void
 * @since   v 3.0.0.0
 */
	public function index() {
		$login_user = $this->Auth->user();
		$user_id = $login_user['id'];
		$lang = Configure::read(NC_CONFIG_KEY.'.'.'language');
		
		// カレント会員取得
		$center_page = Configure::read(NC_SYSTEM_KEY.'.'.'Center_Page');
		$current_user = $this->User->currentUser($center_page, $login_user);
		if($current_user === false) {
			$this->flash(sprintf(__('Failed to obtain the database, (%s).',true),'users'), null, 'pagesmenu_index.002');
			return;
		} else if($current_user === '') {
			$current_user = array('User' => $login_user);
		}
		
		$fetch_params = array(
			'active_page_id' => $this->page_id
		);
		$params = null;
		/*if($edit_mode) {
			$params = array(
					'conditions' => array(
							'Page.lang' => array('', $lang)
					)
			);
		}*/
		$pages = $this->Page->findMenu('all', $user_id, NC_SPACE_TYPE_PUBLIC, $current_user, $params, null, $fetch_params);
		
		$private_pages = $this->Page->findMenu('all', $user_id, array(NC_SPACE_TYPE_MYPORTAL, NC_SPACE_TYPE_PRIVATE), $current_user, $params, null, $fetch_params);
		if(isset($private_pages[NC_SPACE_TYPE_MYPORTAL])) {
			$pages[NC_SPACE_TYPE_MYPORTAL] = $private_pages[NC_SPACE_TYPE_MYPORTAL];
		}
		if(isset($private_pages[NC_SPACE_TYPE_PRIVATE])) {
			$pages[NC_SPACE_TYPE_PRIVATE] = $private_pages[NC_SPACE_TYPE_PRIVATE];
		}
		$element_params = array(
			'page_id' => $this->page_id,
			'pages' => $pages
		);
		$this->set('element_params', $element_params);
	}
	
/**
 * よく見るページ表示
 * @param   void
 * @return  void
 * @since   v 3.0.0.0
 */
	public function favorite() {
		$this->render('index');
	}

/**
 * ページ情報表示・登録
 * @param   void
 * @return  void
 * @since   v 3.0.0.0
 */
	public function meta() {
		$this->render('index');
	}

/**
 * ページスタイル表示・登録
 * @param   void
 * @return  void
 * @since   v 3.0.0.0
 */
	public function style() {
		if ($this->request->is('post')) {
			// TODO:test
			//$this->request->data['PageStyle']['test']
		}
		$this->render('index');
	}

/**
 * ページテーマ表示・登録
 * @param   void
 * @return  void
 * @since   v 3.0.0.0
 */
	public function theme() {
		$this->render('index');
	}

/**
 * ページレイアウト表示・登録
 * @param   void
 * @return  void
 * @since   v 3.0.0.0
 */
	public function layout() {
		$this->render('index');
	}
}