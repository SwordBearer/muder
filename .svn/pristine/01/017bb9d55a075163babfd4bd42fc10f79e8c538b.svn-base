<?php
class BaseAction extends Action {
	/**
	 * ******************authorize************
	 */
	protected function authorize() {
		$uid = $_REQUEST ['uid'];
		$token = $_REQUEST ['token'];
		if (is_null ( $uid ) || is_null ( $token )) { // 空，认为没有该账户
			$this->ajaxReturn ( null, "addLine FAILED:authorize is WRONG ", 109 );
		}
		$User = new UserModel ();
		$result = $User->authorize ( $uid, $token );
		if (! $result) { // User中需要对105重新处理
			$this->ajaxReturn ( null, "addLine FAILED:authorize is WRONG ", 109 );
		}
	}
}

?>