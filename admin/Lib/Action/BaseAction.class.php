<?php
class BaseAction extends Action {
	protected function checkUser() {
		if (! isset ( $_SESSION [C ( 'USER_AUTH_KEY' )] )) {
			$this->error ( "未登录，无访问权限", __GROUP__ . "/index/login" );
		}
	}
}
?>