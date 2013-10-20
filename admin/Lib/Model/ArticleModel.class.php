<?php
class ArticleModel extends Model {
	public function getById($id) {
		$data = $this->find ( $id );
		if (! $data || is_null ( $data )) {
			return false;
		}
		return $data;
	}
}
?>