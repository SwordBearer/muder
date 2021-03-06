<?php
class RssAction extends BaseAction {
	/* pages*********** */
	public function index() {
		$this->checkUser ();
		$feedList = $this->storage_getFeedList ();
		$files=$feedList['files'];
		$feedList=array();
		foreach($files as $file){
			$bean = array ();
			if(!strpos($file['Name'],'.json')){
				continue;
			}
			$bean ['name'] = $file['Name'];
			$bean ['fullName'] =$file['fullName'];
			$bean ['length'] = round ($file['length'] / 1024, 2 );
			$bean ['uploadTime'] = date('Y-m-d H:i:s',$file['uploadTime']);
			$feedList [] = $bean;
		}
		$this->assign ( 'feedList', $feedList );
		$this->display ();
	}
	public function generate_feed() {
		$this->checkUser ();
		$result=$this->storage_GenerateRssFeed(time());
		if ($result>0){
			$this->redirect ( __GROUP__ . '/rss/index' );
		}
		else if($result==0){
			$this->error('没有文章啊亲，你搞毛？');
		}
		else {
			$this->error ( '生成JSON文件失败' );
		}
	}
	/* functions*********** */
	private function getFeedList() {
		$feedList = array ();
		if (is_dir ( RSS_DIR )) {
			$handler = opendir ( RSS_DIR );
			while ( ($filename = readdir ( $handler )) !== false ) {
				if ($filename == "." || $filename == "..") {
					continue;
				}
				$filePath = RSS_DIR . $filename;
				$filename = iconv ( "GB2312", "UTF-8", $filename );
				if (is_file ( $filePath )) {
					$bean = array ();
					$bean ['name'] = $filename;
					$bean ['size'] = round ( filesize ( $filePath ) / 1024, 2 );
					$bean ['mtime'] = date ( 'Y-m-d H:i:s', filemtime ( $filePath ) );
					$feedList [] = $bean;
				}
			}
		}
		closedir ( $handler );
		return $feedList;
	}
	private function storage_getFeedList(){
		$feedList=array();
		$s=new SaeStorage();
		$feedList=$s->getListByPath(DOMAIN_NAME,RSS_DIR,100,0,true);
		return $feedList;
	}
	private function storage_GenerateRssFeed($date){
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
		$result = json_encode ( $feeds, true );
		$fileName= date( 'Ymd' ,$date).'.json';
		$s = new SaeStorage();
		$s->write( DOMAIN_NAME , RSS_DIR.'/'.$fileName , $result );
		return 1;
	}
	
}
?>