<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
	/* *****client interface************ */
	public function index() {
		echo 'Some say love ,it is a river <a href="http://swordbearer.sinaapp.com">@SwordBearer</a>';
	}
	//通过RSS来获取文章列表
	public function getfeeds2() {
		$date=$_REQUEST['date'];//20130811
		if(!isset($date)||empty($date)){
			$date=time();
		}
		$fileName='/'.RSS_DIR.'/'.date('Ymd',$date).'.json';
		$s=new SaeStorage();
		if(!$s->fileExists(DOMAIN_NAME,$fileName)){
			$this->ajaxReturn(null,'there is no rss file',0);
		}
		
		$feed=$s->read(DOMAIN_NAME,$fileName);
		$feed=json_decode($feed);
		$this->ajaxReturn ( $feed, 'ok', 1 );
		
	}
	
	public function getFeeds(){
		if(!isset($_REQUEST['date'])||empty($_REQUEST['date'])){
			$date=time();
		}else{
			$date=$_REQUEST['date'];
		}
		$start = date ( 'Y-m-d 00:00:00', $date );
		$end = date ( 'Y-m-d 00:00:00', $date + DAY );
		$condition = 'birth>"' . $start . '" and birth<"' . $end . '"';
		$article = M('Article');
		$list = $article->where ( $condition )->order ( 'birth desc' )->getField ( 'id,title,author,birth,outline' );
		if(is_null($list)){
			return 0;
		}
		$feeds=array();
		foreach($list as $feed){
		    $feeds[]=$feed;
		}
		$this->ajaxReturn(($feeds),'ok',1);
	}
	
	public function getArticle() {
		$id = $_REQUEST ['id'];
		if (! isset ( $id ) || empty ( $id )) {
			$this->ajaxReturn ( NULL, 'Error:wrong parameter', 0 );
		}
		$article = M('Article');
		$result = $article->find($id);
		//如果该日的文章数为0，不当做异常
		if (! $result&&(!is_null($result))) {
			$this->ajaxReturn ( NULL, 'Error:there is no this article', 0 );
		}
		$this->ajaxReturn ( $result, 'ok', 1 );
	}
	
	public function downloadArticles(){
		$date=$_REQUEST['date'];//20130811
		if(!isset($date)||empty($date)){
			$date=time();
		}
		$start = date ( 'Y-m-d 00:00:00', $date);
		$end = date ( 'Y-m-d 00:00:00', $date + DAY);
		$condition = 'birth>"' . $start . '" and birth<"' . $end . '"';
		$article = M('Article');
		$list = $article->where ( $condition )->order ( 'birth desc' )->select();
		$this->ajaxReturn($list,'ok',1);
	}
	/*************************Music********************/
	public function getMusics(){
		$music=M('Music');
		$now=time();
		$start=date('Y-m-d 00:00:00',$now);
		$end=date('Y-m-d 00:00:00',$now+DAY);
		$condition = 'status=1 and birth>"' . $start . '" and birth<"' . $end . '"';
		$music=M('Music');
		$result=$music->where($condition)->select();
		if (!$result&&(!is_null($result))) {//查询失败
			$this->ajaxReturn ( NULL, 'Error:getMusics failed', 0 );
		}
		//如果该日的音乐数为0，不当做异常
		$this->ajaxReturn($result,'ok',1);
	}
	/*************************Picture********************/
	//deprecated
	public function getPictures(){
		$picture=M('Picture');
	}
}