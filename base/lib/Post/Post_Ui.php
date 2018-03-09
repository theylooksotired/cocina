<?php
class Post_Ui extends Ui{

	public function renderPublic() {
		return '<div class="itemPublic itemPublicPost">
					<a href="'.$this->object->url().'">
						<h2>'.$this->object->getBasicInfo().'</h2>
						<div class="itemPublicIns">
							'.$this->object->getImageIcon('image').'
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
							<img itemprop="image" src="'.$this->object->getImageUrl('image', 'small').'" alt="'.$this->object->getBasicInfo().'"/>
						</div>
						<div class="postTopRight">
							<p><em itemprop="datePublished" content="'.$this->object->get('publishDate').'">'.Date::sqlText($this->object->get('publishDate')).'</em></p>
							<p><strong>'.$this->object->get('shortDescription').'</strong></p>
						</div>
					</div>
					'.Adsense::top().'
					<div class="postContent pageComplete" itemprop="articleBody">
						'.$this->object->get('description').'
					</div>
					'.Adsense::linksAll().'
					'.$this->share(array('facebook'=>true, 'twitter'=>true, 'print'=>true)).'
				</div>
				'.Navigation_Ui::facebookComments($this->object->url()).'
				'.$this->related();
	}

	public function related() {
		$items = new ListObjects('Post', array('where'=>'idPost!="'.$this->object->id().'"', 'order'=>'RAND()', 'limit'=>'5'));
		return '<div class="related relatedPost">
					<h2>Otras noticias que pueden interesarte</h2>
					'.Adsense::top().'
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

}
?>