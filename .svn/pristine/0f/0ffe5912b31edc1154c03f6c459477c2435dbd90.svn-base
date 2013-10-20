<?php
class ArticleAction extends BaseAction {
	/*
	*首页显示文章列表
	*/
	public function index() {
		$this->checkUser ();
		$date = $_REQUEST ['date'];
		$article = new ArticleModel ();
		if (! isset ( $date )||empty ( $date )){
			$condition='id>"0"';
		} else {
			$end = date ( 'Y-m-d', strtotime("$date +1 day"));
			$condition = 'birth>"' . $date . '" and birth<"' . $end . '"';
		}
		import ( 'ORG.Util.Page' );
		$count = $article->where ( $condition )->count ();
		$Page = new Page ( $count, 15);
		$show = $Page->show ();
		$list = $article->where ($condition )->order ( 'birth desc' )->limit ( $Page->firstRow . ',' . $Page->listRows )->getField ( 'id,status,title,birth,source,outline' );
		$this->assign ( 'list', $list );
		$this->assign ( 'page', $show );
		$this->display ();
	}
	public function show_article() {
		$this->checkUser ();
		$article = new ArticleModel ();
		$data = $article->getById ( $_REQUEST ['id'] );
		if (! $data) {
			$this->error ( '查无此文' );
		}
		$this->assign ( 'article', $data );
		$this->display ();
	}
	public function edit_article() {
		$this->checkUser ();
		if (! empty ( $_REQUEST ['id'] )) {
			$this->gotoEdit ( $_REQUEST ['id'] );
		} else {
			if (isset ( $_REQUEST ['is_add'] ))
				$this->addArticle ();
			else if (isset ( $_REQUEST ['article_id'] )) {
				$this->saveArticle ();
			}
		}
		$this->display ();
	}
	public function delete_article(){
		$this->checkUser();
		
		$this->redirect(__GROUP__.'/article/index');
	}
	/************************************************************/
	/**
	 * 添加文章
	 */
	private function addArticle() {
		$this->checkUser ();
		if (empty ( $_REQUEST ['article_title'] ) || empty ( $_REQUEST ['article_content'] )) {
			return;
		}
		if (empty ( $_REQUEST ['article_time'] )) {
			$_REQUEST ['article_time'] = date ( 'Y-m-d H:i:s', time () );
		}
		$data ['title'] = $_REQUEST ['article_title'];
		$data ['author'] = $_REQUEST ['article_author'];
		$data ['source'] = $_REQUEST ['article_source'];
		$data ['outline'] = $_REQUEST ['article_outline'];
		$data ['status'] = 1;
		$data ['birth'] = $_REQUEST ['article_time'];
		$data ['content'] = $_REQUEST ['article_content'];
		$article = new ArticleModel ();
		$result = $article->add ( $data );
		if ($result && $result != null) {
			$this->redirect ( __GROUP__ . '/article/index' );
		} else {
			$this->error ( '添加失败,请重试' );
		}
	}
	private function saveArticle() {
		$this->checkUser ();
		$data ['id'] = $_REQUEST ['article_id'];
		$data ['title'] = $_REQUEST ['article_title'];
		$data ['author'] = $_REQUEST ['article_author'];
		$data ['source'] = $_REQUEST ['article_source'];
		$data ['outline'] = $_REQUEST ['article_outline'];
		$data ['status'] = 1;
		$data ['birth'] = $_REQUEST ['article_time'];
		$data ['content'] = $_REQUEST ['article_content'];
		$article = new ArticleModel ();
		$result = $article->save ( $data );
		if ($result && $result != null) {
			$this->redirect ( __GROUP__ . '/article/index' );
		} else {
			$this->error ( '保存失败,请重试' );
		}
	}
	/**
	 * 跳转至编辑页面
	 *
	 * @param unknown $id        	
	 */
	private function gotoEdit($id) {
		$this->checkUser ();
		$article = new ArticleModel ();
		$data = $article->getById ( $id);
		if (! $data) {
			$this->error ( '查无此文' );
		}
		$this->assign ( 'article', $data );
	}
}
?>