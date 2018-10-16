<?php
class Category_Ui extends Ui{

	public function renderMenu($options=array()) {
		$class = '';
		if (isset($options['categorySelected'])) {
			$categorySelected = $options['categorySelected'];
			$class = ($categorySelected->id()==$this->object->id()) ? 'class="selected"' : '';
		}
		return '<li '.$class.'>'.$this->object->link().'</li>';
	}

	public function renderIntro() {
		$item = Recipe::readFirst(array('where'=>'idCategory="'.$this->object->id().'" AND rating="5"'));
		return '<div class="itemPublicSimple">
					<a href="'.$this->object->url().'">
						<div class="itemPublicSimpleImage" style="background-image:url('.$item->getImageUrl('image', 'small').');"></div>
						<p>'.$this->object->getBasicInfo().'</p>
					</a>
				</div>';
	}

	public function renderComplete() {
		$items = new ListObjects('Recipe', array('where'=>'idCategory="'.$this->object->id().'"', 'order'=>'nameUrl', 'results'=>'12'));
		return '<div class="listAll">
					'.Adsense::amp().'
					'.$items->showList(array('function'=>'Public','middle'=>Adsense::amp(), 'middleRepetitions'=>2)).'
					'.$items->pager().'
				</div>';
	}

	static public function renderCompleteRecipes() {
		$html = '';
		$items = Category::readList(array('order'=>'ord'));
		foreach ($items as $item) {
			$itemsIns = new ListObjects('Recipe', array('where'=>'idCategory="'.$item->id().'"', 'order'=>'RAND()', 'results'=>'5'));
			$html .= '<div class="listAllCategory">
						<h2>'.$item->link().'</h2>
						<div class="contentSimple">
							<div class="listAll listAllRecipes">
								'.$itemsIns->showList(array('function'=>'Public')).'
							</div>
							<div class="button">
								<a href="'.$item->url().'">Ver todas las recetas de '.strtolower($item->getBasicInfo()).'</a>
							</div>
						</div>
					</div>';
		}
		return $html;
	}

	public function renderJsonHeader($items) {
		$recipes = array();
		$i = 1;
		foreach ($items->list as $item) {
			$recipes[] = array("@type" => "ListItem",
								"position" => $i,
								"url" => $item->url());
			$i++;
		}
		$info = array("@context" => "http://schema.org/",
					"@type" => "ItemList",
					"itemListElement" => $recipes);
		return '<script type="application/ld+json">'.json_encode($info).'</script>';
	}

}
?>