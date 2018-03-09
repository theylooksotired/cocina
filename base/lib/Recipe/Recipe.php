<?php
class Recipe extends Db_Object {

	public function loadCategory() {
		if (!isset($this->category)) {
			$this->category = Category::read($this->get('idCategory'));
		}
	}

	public function url() {
		$this->loadCategory();
		return $this->category->url().'/'.$this->id().'_'.$this->get('nameUrl');
	}

}
?>