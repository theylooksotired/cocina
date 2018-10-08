<?php
class Recipe_Ui extends Ui{

	public function renderPublic() {
		$description = ($this->object->get('description')!='') ? '<p>'.nl2br($this->object->get('description')).'</p>' : '';
		return '<div class="itemPublic">
					<a href="'.$this->object->url().'">
						<h2>'.$this->object->getBasicInfo().'</h2>
						'.$this->stars(true).'
						<div class="itemPublicRight">
							'.$description.'
						</div>
						<div class="itemPublicLeft">
							<div class="itemPublicImage">
								'.$this->object->getImageIcon('image').'
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
		$query = 'SELECT ri.*
				FROM '.Db::prefixTable('RecipeIngredient').' ri
				WHERE ri.idRecipe="'.$this->object->id().'"
				ORDER BY ri.ord;';
		$results = Db::returnAll($query);
		$ingredients = '';
		foreach ($results as $result) {
			$ingredients .= '<div class="ingredient" itemprop="recipeIngredient"><span>'.$result['label'].'</span></div>';
		}
		$category = Category::read($this->object->get('idCategory'));
		return Adsense::top().'
				<div class="itemComplete itemCompleteRecipe">
					<div class="itemCompleteTop">
						<div class="itemCompleteTopLeft">
							<img itemprop="image" src="'.$this->object->getImageUrl('image', 'small').'" alt="'.$this->object->getBasicInfo().'"/>
							<div class="itemCompleteCategory">
								<a href="'.$category->url().'" itemprop="recipeCategory">'.$category->getBasicInfo().'</a>
							</div>
							'.$this->stars().'
						</div>
						<div class="itemCompleteTopCenter">
							<p itemprop="description">'.nl2br($this->object->get('description')).'</p>
						</div>
						<div class="itemCompleteTopRight">
							<p>
								<strong>Preparación:</strong> <span itemprop="prepTime" content="'.$this->ptTime($this->object->get('preparationTime')).'">'.$this->object->get('preparationTime').'</span>
								<i class="icon icon-clock"></i>
							</p>
							<p>
								<strong>Porciones:</strong> <span itemprop="recipeYield">'.$this->object->get('numPersons').'</span>
								<i class="icon icon-serving"></i>
							</p>
							<p>
								<span itemprop="recipeCuisine"><strong>Cocina '.Params::param('titleCountry').'</span></strong>
								<i class="icon icon-world"></i>
							</p>
						</div>
					</div>
					<div class="itemCompleteBottom">
						<div class="itemCompleteBottomItem itemCompleteBottomAd">
							'.Adsense::inline().'
						</div>
						<div class="itemCompleteBottomRecipe">
							<div class="itemCompleteBottomItem itemCompleteBottomIngredients">
								<h2><i class="icon icon-ingredients"></i><span>Ingredientes</span></h2>
								<div class="ingredientList">'.$ingredients.'</div>
							</div>
							<div class="itemCompleteBottomItem itemCompleteBottomPreparation">
								<h2><i class="icon icon-preparation"></i><span>Preparación</span></h2>
								<div class="pageComplete">
									<div itemprop="recipeInstructions">
										'.$this->object->get('preparation').'
										'.Adsense::linksAll().'
									</div>
								</div>
							</div>
						</div>
					</div>
					'.Adsense::top().'
					<div class="itemCompleteShare">
						<h3>Compartir esta receta en:</h3>
						'.$this->share(array('facebook'=>true, 'twitter'=>true)).'
					</div>
				</div>
				'.Navigation_Ui::facebookComments($this->object->url()).'
				'.$this->related();
	}

	static public function renderIntroSite() {
		$posts = new ListObjects('Post', array('order'=>'publishDate DESC', 'results'=>'10'));
		$categories = new ListObjects('Category', array('order'=>'ord'));
		$recipesIntro = new ListObjects('Recipe', array('where'=>'rating="5"', 'order'=>'RAND()', 'limit'=>'5'));
		return Adsense::top().'
				<div class="introTop">
					<div class="introTopItems">
						'.$categories->showList(array('function'=>'Intro')).'
					</div>
					<div class="button">
						<a href="'.url('recetas').'">Ver todas las recetas</a>
					</div>
				</div>
				'.Adsense::top().'
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
						'.Adsense::linksAll().'
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
							'.Adsense::inline().'
							'.Post_Ui::side().'
						</aside>
					</div>
				</div>';
	}

	public function stars($simple=false) {
		$stars = '';
		for ($i=1;$i<=5;$i++) {
			$stars .= ($i<=$this->object->get('rating')) ? '<div class="starFull"><i class="icon icon-star-full"></i></div>' : '<div class="starEmpty"><i class="icon icon-star-empty"></i></div>';
		}
		if ($simple) {
			return '<div class="stars">'.$stars.'</div>';
		} else {
			return '<div class="stars" itemprop="review" itemscope itemtype="http://schema.org/Review">
						<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
							'.$stars.'
							<span style="display:none;" itemprop="ratingValue">'.$this->object->get('rating').'</span>
							<span style="display:none;" itemprop="bestRating">5</span>
						</div>
						<span style="display:none;" itemprop="author" itemscope itemtype="http://schema.org/Person">
							<span itemprop="name">'.Params::param('titlePage').'</span>
						</span>
					</div>';
		}
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
						'.$items->showList(array('function'=>'Public')).'
					</div>
					<div class="relatedLeft">
						<aside>
							'.Recipe_Ui::side().'
						</aside>
					</div>
				</div>';
	}

	static public function side() {
		$items = new ListObjects('Recipe', array('where'=>'rating>=3', 'order'=>'RAND()', 'results'=>'3'));
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
		$items = new ListObjects('Recipe', array('where'=>'rating>=3 AND idCategory="'.$this->object->get('idCategory').'"', 'limit'=>'6'));
		if (!$items->isEmpty()) {
			return '<div class="menuBottomWrapper">
						<div class="menuBottomWrapperTitle">También le pueden interesar estas recetas</div>
						<div class="menuBottomWrapperItems">
							'.$items->showList(array('function'=>'PublicMedium')).'
						</div>
					</div>';
		}
	}

	public function ptTime($time) {
		$array = array("2 horas"=>"PT2H", "15 minutos"=>"PT15M", "30 minutos"=>"PT30M", "1 hora"=>"PT1H", "+2 horas"=>"PT5H");
		return (isset($array[$item])) ? $array[$item] : $array[0];
	}

}
?>