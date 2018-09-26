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
		$description = ($this->object->get('description')!='') ? '<p itemprop="description">'.nl2br($this->object->get('description')).'</p>' : '';
		$preparationTime = ($this->object->get('preparationTime')!='') ? '<p><strong>Tiempo de preparación:</strong> <span>'.$this->object->get('preparationTime').'</span></p>' : '';
		$numPersons = ($this->object->get('numPersons')!='') ? '<p><strong>Porciones:</strong> <span itemprop="recipeYield">'.$this->object->get('numPersons').'</span></p>' : '';
		$query = 'SELECT ri.*
				FROM '.Db::prefixTable('RecipeIngredient').' ri
				WHERE ri.idRecipe="'.$this->object->id().'"
				ORDER BY ri.ord;';
		$ingredients = '';
		$results = Db::returnAll($query);
		foreach ($results as $result) {
			$ingredients .= '<div class="ingredient" itemprop="recipeIngredient">'.$result['label'].'</div>';
		}
		$descriptionComplete = ($this->object->get('descriptionComplete')!='') ? '<div class="descriptionComplete"><div class="pageComplete">'.$this->object->get('descriptionComplete').'</div></div>' : '';
		return Adsense::top().'
				<div class="itemComplete itemCompleteRecipe">
					<div class="itemCompleteTop">
						<div class="itemCompleteTopItem itemCompleteTopImage">
							<img itemprop="image" src="'.$this->object->getImageUrl('image', 'small').'" alt="'.$this->object->getBasicInfo().'"/>
						</div>
						<div class="itemCompleteTopItem itemCompleteTopDescription">
							'.$description.'
							'.$preparationTime.'
							'.$numPersons.'
							'.$this->stars().'
						</div>
					</div>
					<div class="itemCompleteBottom">
						<div class="itemCompleteBottomItem itemCompleteBottomAd">
							'.Adsense::inline().'
						</div>
						<div class="itemCompleteBottomRecipe">
							<div class="itemCompleteBottomItem itemCompleteBottomIngredients">
								<h2>Ingredientes</h2>
								'.$ingredients.'
							</div>
							<div class="itemCompleteBottomItem itemCompleteBottomPreparation">
								<h2>Preparación</h2>
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
					'.$this->share(array('facebook'=>true, 'twitter'=>true, 'print'=>true)).'
				</div>
				'.Navigation_Ui::facebookComments($this->object->url()).'
				'.$descriptionComplete.'
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
			$stars .= ($i<=$this->object->get('rating')) ? '<div class="starFull"></div>' : '<div class="starEmpty"></div>';
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
		return '<div class="relatedWrapper">
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

}
?>