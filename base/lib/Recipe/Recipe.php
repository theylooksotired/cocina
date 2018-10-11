<?php
class Recipe extends Db_Object {

	public function url() {
		$this->loadCategory();
		return $this->category->url().'/'.$this->get('nameUrl');
	}

	public function loadCategory() {
		if (!isset($this->category)) $this->category = Category::read($this->get('idCategory'));
	}

	public function loadIngredients() {
		if (!isset($this->ingredients)) {
			$query = 'SELECT ri.*
					FROM '.Db::prefixTable('RecipeIngredient').' ri
					WHERE ri.idRecipe="'.$this->id().'"
					ORDER BY ri.ord;';
			$this->ingredients = Db::returnAll($query);
		}
	}

}
?>