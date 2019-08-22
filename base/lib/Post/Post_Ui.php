<?php
class Post_Ui extends Ui{

	public function renderPublic() {
		return '<div class="itemPublic itemPublicPost">
					<a href="'.$this->object->url().'">
						<h2>'.$this->object->getBasicInfo().'</h2>
						<div class="itemPublicIns">
							<div class="itemPublicImage">
							'.$this->object->getImageAmp('image', 'small').'
							</div>
							<p>'.$this->object->get('shortDescription').'</p>
						</div>
					</a>
				</div>';
	}

	public function renderSide() {
		return '<div class="itemSimpleLink">
					<a href="'.$this->object->url().'">
						<p>'.$this->object->getBasicInfo().'</p>
						<p><em>'.Date::sqlText($this->object->get('publishDate')).'</em></p>
					</a>
				</div>';
	}

	public function renderComplete() {
		$breadCrumbs = array(url('noticias')=>'Noticias', $this->object->url()=>$this->object->getBasicInfo());
		return '<div class="itemComplete itemCompletePost">
					<div class="postTop">
						<div class="postTopLeft">
							'.$this->object->getImageAmp('image', 'web').'
						</div>
						<div class="postTopRight">
							<p>'.Date::sqlText($this->object->get('publishDate')).'</p>
							<p><strong>'.$this->object->get('shortDescription').'</strong></p>
						</div>
					</div>
					'.Adsense::amp().'
					<div class="postContent pageComplete">'.$this->object->get('description').'</div>
					<div class="itemCompleteShare">
						<h3>Ayúdanos compartiendo esta nota o dejando tu comentario.</h3>
						'.$this->share(array('facebook'=>true, 'twitter'=>true)).'
						'.Navigation_Ui::facebookComments($this->object->url()).'
					</div>
					'.Adsense::amp().'
				</div>
				'.$this->related();
	}

	public function related() {
		$items = new ListObjects('Post', array('where'=>'idPost!="'.$this->object->id().'"', 'order'=>'RAND()', 'limit'=>'5'));
		return '<div class="related relatedPost">
					<h2>Otras noticias que pueden interesarte</h2>
					<div class="relatedIns">'.$items->showList().'</div>
				</div>';
	}

	static public function intro() {
		$items = new ListObjects('Post', array('results'=>'5'));
		return '<div class="listPublic listPublicIntro">
					'.$items->showListPager().'
				</div>';
	}

	static public function side() {
		$items = new ListObjects('Post', array('order'=>'RAND()', 'limit'=>'3'));
		if (!$items->isEmpty()) {
			return '<div class="menuSideWrapper">
						<div class="menuSideWrapperTitle">Algunos artículos que podrían interesarte</div>
						<div class="menuSideWrapperItems">
							'.$items->showList(array('function'=>'Public')).'
						</div>
					</div>';
		}
	}

	public function renderJsonHeader() {
		$info = array("@context" => "http://schema.org/",
					"@type" => "Article",
					"headline" => $this->object->getBasicInfo(),
					"image" => $this->object->getImageUrl('image', 'web'),
					"author" => array("@type" => "Organization", "name" => Params::param('titlePage')),
					"publisher" => array("@type" => "Organization", "name" => "Plasticwebs", "logo" => "https://www.plasticwebs.com/plastic/visual/img/logo.jpg"),
					"datePublished" => $this->object->get('publishDate'),
					"dateModified" => $this->object->get('publishDate'),
					"articleBody" => strip_tags($this->object->get('description'))
					);
		return '<script type="application/ld+json">'.json_encode($info).'</script>';
	}

}
?>