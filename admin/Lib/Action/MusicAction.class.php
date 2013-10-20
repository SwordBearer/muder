<?php
class MusicAction extends BaseAction{
	public function index(){
		$this->checkUser();
		import ( 'ORG.Util.Page' );
		$music = M('Music');
		$count = $music->where('')->count ();
		$Page = new Page ( $count, 5 );
		$show = $Page->show ();
		$list = $music->where('')->order ( 'birth desc' )->limit ( $Page->firstRow . ',' . $Page->listRows )->select();
		$this->assign ( 'list', $list );
		$this->assign ( 'page', $show );
		$this->display ();
	}
	
	public function edit_music() {
		$this->checkUser ();
		if (! empty ( $_REQUEST ['id'] )) {
			$this->gotoEdit ( $_REQUEST ['id'] );
		} else {
			if (isset ( $_REQUEST ['is_add'] ))
				$this->addMusic ();
			else if (isset ( $_REQUEST ['music_id'] )) {
				$this->saveMusic ();
			}
		}
		$this->display ();
	}
	
	public function show_music(){
		$this->checkUser ();
		$music = M('Music');
		$data = $music->find( $_REQUEST ['id'] );
		if (! $data) {
			$this->error ( '查无此乐！' );
		}
		$this->assign ( 'music', $data );
		$this->display ();
	}
	
	public function delete_music(){
		$id = $_REQUEST ['id'];
		if (! isset ( $id ) || empty ( $id )) {
			$this->ajaxReturn ( NULL, 'Error:wrong parameter', 0 );
		}
		$music = M('Music');
		$result = $music->delete($id);
		if (! $result || is_null ( $result )) {
			$this->error ('删除音乐失败！');
		}
		$this->redirect ( __GROUP__ . '/music/index' );
	}
	
	/************************************************************/
	
	/**
	 * 添加文章
	 */
	private function addMusic() {
		$this->checkUser ();
		if (empty ( $_REQUEST ['music_name'] ) || empty ( $_REQUEST ['music_details'] )||empty($_REQUEST ['music_author'])) {
			return;
		}
		$data ['status'] = 1;
		$data ['name'] = $_REQUEST ['music_name'];
		$data ['author'] = $_REQUEST ['music_author'];
		$data ['birth'] = date ( 'Y-m-d H:i:s', time () );
		$data ['details'] = $_REQUEST ['music_details'];
		$data ['url'] = $_REQUEST ['music_url'];
		$data ['lyc_url'] =$_REQUEST ['music_lyc_url'];
		$data ['thumbnail'] = $_REQUEST ['music_thumbnail'];
		$music = M('Music');
		$result = $music->add ( $data );
		if ($result && $result != null) {
			$this->redirect ( __GROUP__ . '/music/index' );
		} else {
			$this->error ( '添加音乐失败,请重试' );
		}
	}
	private function saveMusic() {
		$this->checkUser ();
		$data ['id'] = $_REQUEST ['music_id'];
		$data ['status'] = 1;
		$data ['name'] = $_REQUEST ['music_name'];
		$data ['author'] = $_REQUEST ['music_author'];
		$data ['details'] = $_REQUEST ['music_details'];
		$data ['url'] = $_REQUEST ['music_url'];
		$data ['lyc_url'] = $_REQUEST ['music_lyc_url'];
		$data ['thumbnail'] = $_REQUEST ['music_thumbnail'];
		$music =  M('Music');
		$result = $music->data($data)->save();
		if ($result && $result != null) {
			$this->redirect ( __GROUP__ . '/music/index' );
		} else {
			$this->error ( '保存音乐失败,请重试' );
		}
	}
	/**
	 * 跳转至编辑页面
	 *
	 * @param unknown $id        	
	 */
	private function gotoEdit($id) {
		$this->checkUser ();
		$music =  M('Music');
		$data = $music->find($id);
		if (! $data) {
			$this->error ( '查无此乐' );
		}
		$this->assign ( 'music', $data );
	}
}
?>