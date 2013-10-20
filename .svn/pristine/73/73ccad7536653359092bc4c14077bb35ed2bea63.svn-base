<?php
class IndexAction extends BaseAction {
	public function index() {
		if (! isset ( $_SESSION [C( 'USER_AUTH_KEY' )] )) {
			$this->redirect ( __GROUP__ . "/Index/login" );
		} else {
			$this->display ();
		}
	}
	/**
	 * *****************pages**********************
	 */
	public function login() {
		if (isset ( $_SESSION [C( 'USER_AUTH_KEY' )] )) {
			$this->redirect ( __GROUP__ . "/Index/index" );
		} else {
			$this->display ();
		}
	}
	
	/**
	 * *********************functions*****************
	 */
	public function checkLogin() {
		$data = array ();
		$data ['username'] = md5 ( trim ( $_POST ['username'] ) );
		$data ['password'] = md5 ( trim ( $_POST ['password'] ) );
		$Admin = new AdminModel ();
		$result = $Admin->where ( $data )->find ();
		if ($result && $result != null) { // 登录成功
			$_SESSION [C( 'USER_AUTH_KEY' )] = $result;
			$this->redirect ( __GROUP__ . "/Index/index" );
		} else { // 登录失败
			$this->error ( "登录失败!" );
		}
	}
	public function logout() {
		if (isset ( $_SESSION [C ( 'USER_AUTH_KEY' )] )) {
			unset ( $_SESSION [C ( 'USER_AUTH_KEY' )] );
			unset ( $_SESSION );
			session_destroy ();
			$this->redirect ( __GROUP__ . "/Index/login" );
		}
	}
}

?>