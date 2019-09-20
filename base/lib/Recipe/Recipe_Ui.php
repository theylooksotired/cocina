<?php
class Recipe_Ui extends Ui{

	public function renderPublic() {
		$description = ($this->object->get('description')!='') ? '<p>'.nl2br($this->object->get('description')).'</p>' : '';
		return '<div class="itemPublic">
					<a href="'.$this->object->url().'">
						<h2>'.$this->object->getBasicInfo().'</h2>
						'.$this->stars().'
						<div class="itemPublicRight">
							'.$description.'
						</div>
						<div class="itemPublicLeft">
							<div class="itemPublicImage">
								'.$this->object->getImageAmp('image', 'small').'
							</div>
						</div>
					</a>
				</div>';
	}

	public function renderPublicSimple() {
		return '<div class="itemPublicSimple">
					<a href="'.$this->object->url().'">
						'.$this->object->getImage('image', 'square').'
						<p>'.$this->object->getBasicInfo().'</p>
					</a>
				</div>';
	}

	public function renderPublicMedium() {
		return '<div class="itemPublicMedium">
					<a href="'.$this->object->url().'">
						<div class="itemPublicMediumImage" style="background-image: url('.$this->object->getImageUrl('image', 'small').');"></div>
						<p>'.$this->object->getBasicInfo().'</p>
					</a>
				</div>';
	}

	public function renderSide() {
		return '<div class="itemSide itemSideRecipe">
					<div class="itemSideIns">
						<a href="'.$this->object->url().'">
							<div class="itemSideImage">
								'.$this->object->getImageIcon('image').'
							</div>
							<h2>'.$this->object->getBasicInfo().'</h2>
						</a>
					</div>
				</div>';
	}

	public function renderSimple() {
		return '<div class="itemSimple itemSimpleRecipe">
					<div class="itemSimpleIns">
						<a href="'.$this->object->url().'">'.$this->object->getBasicInfo().'</a>
					</div>
				</div>';
	}

	public function renderIntro($options=array()) {
		return '<div class="itemIntro">
					<a href="'.$this->object->url().'">
						<div class="itemIntroIns" style="background-image:url('.$this->object->getImageUrl('image', 'web').');">
							<div class="itemIntroText">
								<h2>'.$this->object->getBasicInfo().'</h2>
							</div>
						</div>
					</a>
				</div>';
	}

	public function renderComplete() {
		$this->object->loadCategory();
		$this->object->loadIngredients();
		$ingredients = '';
		foreach ($this->object->ingredients as $item) {
			$ingredients .= '<div class="ingredient"><span>'.$item['label'].'</span></div>';
		}
		return Adsense::amp().'
				<div class="itemComplete itemCompleteRecipe">
					<div class="itemCompleteTop">
						<div class="itemCompleteTopLeft">
							<div class="itemCompleteImage">
								'.$this->object->getImageAmpFill('image', 'web').'
							</div>
							<div class="itemCompleteCategory">
								<a href="'.$this->object->category->url().'">'.$this->object->category->getBasicInfo().'</a>
							</div>
							'.$this->stars().'
						</div>
						<div class="itemCompleteTopCenter"><p>'.nl2br($this->object->get('description')).'</p></div>
						<div class="itemCompleteTopRight">
							<p>
								<strong>Preparación:</strong> <span>'.$this->object->get('preparationTime').'</span>
								<i class="icon icon-clock"></i>
							</p>
							<p>
								<strong>Porciones:</strong> <span>'.$this->object->get('numPersons').'</span>
								<i class="icon icon-serving"></i>
							</p>
							<p>
								<span><strong>Cocina '.Params::param('titleCountry').'</span></strong>
								<i class="icon icon-world"></i>
							</p>
						</div>
					</div>
					<div class="itemCompleteBottom">
						<div class="itemCompleteBottomItem itemCompleteBottomAd">
							'.Adsense::ampInline().'
						</div>
						<div class="itemCompleteBottomRecipe">
							<div class="itemCompleteBottomItem itemCompleteBottomIngredients">
								<h2><i class="icon icon-ingredients"></i><span>Ingredientes</span></h2>
								<div class="ingredientList">'.$ingredients.'</div>
							</div>
							<div class="itemCompleteBottomItem itemCompleteBottomPreparation">
								<h2><i class="icon icon-preparation"></i><span>Preparación</span></h2>
								<div class="pageComplete">'.$this->object->get('preparation').'</div>
							</div>
						</div>
					</div>
					'.Adsense::amp().'
					<div class="itemCompleteShare">
						<h3>Ayúdanos compartiendo esta receta o dejando tu comentario.</h3>
						'.$this->share(array('facebook'=>true, 'twitter'=>true)).'
						'.Navigation_Ui::facebookComments($this->object->url()).'
					</div>
				</div>
				'.$this->related();
	}

	static public function renderIntroSite() {
		$posts = new ListObjects('Post', array('order'=>'publishDate DESC', 'results'=>'10'));
		$categories = new ListObjects('Category', array('order'=>'ord'));
		$recipesIntro = new ListObjects('Recipe', array('where'=>'active="1" AND rating="5"', 'order'=>'RAND()', 'limit'=>'5'));
		return Adsense::amp().'
				<div class="introTop">
					<div class="introTopItems">
						'.$categories->showList(array('function'=>'Intro')).'
					</div>
					<div class="button">
						<a href="'.url('recetas').'">Ver todas las recetas</a>
					</div>
				</div>
				'.Adsense::amp().'
				<div class="introBottom">
					<h1>'.Params::param('titlePage').'</h1>
					<div class="pageComplete introText">'.HtmlSection::show('intro-text').'</div>
					<div class="contentLeft">
						<div class="pageComplete introText introTextComplete">'.HtmlSection::show('intro').'</div>
						<div class="blockIntro">
							<h2 class="titleBlock">'.Params::param('title-intro').'</h2>
							<div class="blockIntroIns">
								'.$recipesIntro->showList(array('function'=>'Public')).'
							</div>
							<div class="button">
								<a href="'.url('recetas').'">Ver todas las recetas</a>
							</div>
						</div>
						<div class="pageComplete introText introTextComplete">'.HtmlSection::show('intro-complete').'</div>
						<div class="blockIntro">
							<h2 class="titleBlock"><a href="'.url('noticias').'">'.Params::param('title-news').'</a></h2>
							<div class="blockIntroIns">
								'.$posts->showList().'
							</div>
							<div class="button">
								<a href="'.url('noticias').'">Ver todas las noticias</a>
							</div>
						</div>
					</div>
					<div class="contentRight">
						<aside>
							'.Recipe_Ui::side().'
							'.Adsense::ampInline().'
							'.Post_Ui::side().'
						</aside>
					</div>
				</div>';
	}

	public function stars() {
		$stars = '';
		for ($i=1;$i<=5;$i++) {
			$stars .= ($i<=$this->object->get('rating')) ? '<div class="starFull"><i class="icon icon-star-full"></i></div>' : '<div class="starEmpty"><i class="icon icon-star-empty"></i></div>';
		}
		return '<div class="stars">'.$stars.'</div>';
	}

	public function related() {
		$items = new ListObjects('Post', array('order'=>'MATCH (title, titleUrl, description) AGAINST ("'.$this->object->getBasicInfo().'") DESC', 'limit'=>'7'));
		if ($items->isEmpty()) {
			$items = new ListObjects('Post', array('order'=>'RAND()', 'limit'=>'5'));
		}
		return $this->recipesBottom().
				'<div class="relatedWrapper">
					<div class="relatedRight">
						<h2 class="titleRelated">Algunas noticias relacionadas con <strong>'.$this->object->getBasicInfo().'</strong></h2>
						'.$items->showList(array('function'=>'Public'), array('amp'=>true)).'
					</div>
					<div class="relatedLeft">
						<aside>
							'.Recipe_Ui::side(true).'
						</aside>
					</div>
				</div>';
	}

	static public function side() {
		$items = new ListObjects('Recipe', array('where'=>'active="1" AND rating>=3', 'order'=>'RAND()', 'results'=>'3'));
		if (!$items->isEmpty()) {
			return '<div class="menuSideWrapper">
						<div class="menuSideWrapperTitle">Algunas recetas que podrían interesarte</div>
						<div class="menuSideWrapperItems">
							'.$items->showList(array('function'=>'Public')).'
						</div>
					</div>';
		}
	}

	public function recipesBottom() {
		$items = new ListObjects('Recipe', array('where'=>'active="1" AND idRecipe!="'.$this->object->id().'"', 'order'=>'MATCH (name, nameUrl, description, preparation) AGAINST ("'.$this->object->getBasicInfo().'") DESC', 'limit'=>'6'));
		if ($items->isEmpty()) {
			$items = new ListObjects('Recipe', array('where'=>'active="1" AND rating>=3 AND idCategory="'.$this->object->get('idCategory').'"', 'limit'=>'6'));
		}
		if (!$items->isEmpty()) {
			return '<div class="menuBottomWrapper">
						<div class="menuBottomWrapperTitle">También le pueden interesar estas recetas</div>
						<div class="menuBottomWrapperItems">
							'.$items->showList(array('function'=>'PublicMedium')).'
						</div>
					</div>';
		}
	}

	public function ptTime($time='') {
		$array = array("2 horas"=>"PT2H", "15 minutos"=>"PT15M", "30 minutos"=>"PT30M", "1 hora"=>"PT1H", "+2 horas"=>"PT5H");
		return (isset($array[$time])) ? $array[$time] : "PT2H";
	}

	public function renderJsonHeader($options=array()) {
		$simple = (isset($options['simple'])) ? true : false;
		$this->object->loadCategory();
		$this->object->loadIngredients();
		$ingredients = array();
		foreach ($this->object->ingredients as $item) { $ingredients[] = $item['label']; }
		$instructions = array();
		$instructionsDocument = new DOMDocument();
		$instructionsDocument->loadHTML(mb_convert_encoding($this->object->get('preparation'), 'HTML-ENTITIES', 'UTF-8'));
		$instructionsItems = $instructionsDocument->getElementsByTagName("li");
		foreach($instructionsItems as $instructionsItem) {
			$instructions[] = array("@type" => "HowToStep", "text" => (string) $instructionsItem->nodeValue);
		}
		$info = array("@context" => "http://schema.org/",
					"@type" => "Recipe",
					"name" => $this->object->getBasicInfo(),
					"url" => $this->object->url(),
					"image" => $this->object->getImageUrl('image', 'web'),
					"author" => array("@type" => "Organization", "name" => Params::param('titlePage')),
					"description" => $this->object->get('description'),
					"prepTime" => $this->ptTime($this->object->get('preparationTime')),
					"keywords" => "receta, ".str_replace('-', ' ', Params::param('country-code')).", ".$this->object->category->getBasicInfo(),
					"recipeYield" => intval($this->object->get('numPersons'))." porciones",
					"recipeCategory" => $this->object->category->getBasicInfo(),
					"recipeCuisine" => "Cocina ".Params::param('titleCountry'),
					"recipeIngredient" => $ingredients,
					"recipeInstructions" => $instructions,
					"aggregateRating" => array("@type" => "AggregateRating",
												"ratingValue" => $this->object->get('rating'),
												"ratingCount" => $this->object->get('rating') * 5)
					);
		return ($simple) ? json_encode($info) : '<script type="application/ld+json">'.json_encode($info).'</script>';
	}

}
?>