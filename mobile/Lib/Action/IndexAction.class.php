<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
	const ARTICLE_PAGE_SIZE=20;
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
	/**
	* 获取文章列表
	* @param flag 标志位 1 refresh；2 getmore
	* @param startId 查询的起始id
	*/
	public function getFeeds(){
		$flag=$_REQUEST['flag'];
		$startId=$_REQUEST['startId'];
		if(empty($startId)){
			$startId=0;
		}
		if($flag==2){/* 点击加载更多 */
			$condition='status=1 and id<'.$startId;
		}else{/* 下拉刷新 */
			$condition = 'status=1 and id>' . $startId;
		}
		$article = M('Article');
		$list = $article->where ( $condition )->order ( 'birth desc' )->limit(self::ARTICLE_PAGE_SIZE)->getField ( 'id,title,author,birth,outline' );
		$feeds=array();
		foreach($list as $item){
			$feeds[]=$item;
		}
		$this->ajaxReturn(($feeds),'ok',1);
	}
	
	/**
	* 获得某一篇文章的详细数据
	* @param id 文章的 id
	*/
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
	/*下载今日所有文章的全部数据*/
	public function downloadArticles(){
		$date=$_REQUEST['date'];//20130811
		if(!isset($date)||empty($date)){
			$date=time();
		}
		$start = strtotime(date ( 'Y-m-d 00:00:00', $date));
		$end = strtotime("+1 day",$start);
		$condition = 'birth>"' . $start . '" and birth<"' . $end . '"';
		$article = M('Article');
		$list = $article->where ( $condition )->order ( 'birth desc' )->select();
		$this->ajaxReturn($list,'ok',1);
	}
	/*************************Music********************/
	/**
	* 获取当日的音乐列表
	*/
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
}