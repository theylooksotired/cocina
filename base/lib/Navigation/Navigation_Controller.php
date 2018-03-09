<?php
class Navigation_Controller extends Controller{

	public function __construct($GET, $POST, $FILES) {
		parent::__construct($GET, $POST, $FILES);
		$this->ui = new Navigation_Ui($this);
	}

	public function controlActions(){
		$this->header = '<script type="text/javascript">
							function showHideMenu() {
							    var menuDiv = document.getElementById("menu");
						        menuDiv.style.display = (menuDiv.style.display === "block") ? "none" : "block";
							}
						</script>';
		switch ($this->action) {
			default:
			case 'error':
				header("HTTP/1.1 301 Moved Permanently");
				header('Location: '.url(''));
				exit();
			break;
			case 'intro':
				$this->layoutPage = 'intro';
				$recipe = new Recipe();
				$this->content = $recipe->showUi('IntroSite');
				return $this->ui->render();
			break;
			case 'recetas':
				$info = explode('_', $this->extraId);
				$item = Recipe::read($info[0]);
				if ($item->id()!='') {
					$this->metaUrl = $item->url();
					$this->titlePage = $item->getBasicInfo();
					$this->metaDescription = $this->titlePage.'. '.$item->get('description');
					$this->metaImage = $item->getImageUrl('image', 'web');
					$parent = Category::read($item->get('idCategory'));
					$this->breadCrumbs = array(url('recetas')=>'Recetas', $parent->url()=>$parent->getBasicInfo(), $item->url()=>$item->getBasicInfo());
					$this->layoutPage = 'recipe';
					$this->content = $item->showUi('Complete');
				} else {
					$info = explode('_', $this->id);
					$item = Category::read($info[0]);
					if ($item->id()!='') {
						$this->metaUrl = $item->url();
						$this->titlePage = 'Listado de recetas de '.strtolower($item->getBasicInfo());
						$this->breadCrumbs = array(url('recetas')=>'Recetas', $item->url()=>$item->getBasicInfo());
						$this->metaDescription = $this->titlePage;
						$this->content = $item->showUi('Complete');
					} else {
						$this->metaUrl = url($this->action);
						$this->titlePage = 'Listado de recetas';
						$this->metaDescription = $this->titlePage;
						$this->breadCrumbs = array(url('recetas')=>'Recetas');
						$items = new ListObjects('Recipe', array('order'=>'nameUrl', 'results'=>'12'));
						$this->content = '<div class="listAll">
											'.$items->pager().'
											'.Adsense::top().'
											'.$items->showList(array('function'=>'Public','middle'=>Adsense::top(), 'middleRepetitions'=>2)).'
											'.$items->pager().'
										</div>';
					}
				}
				return $this->ui->render();
			break;
			case 'articulos':
				$info = explode('_', $this->id);
				$item = Post::read($info[0]);
				if ($item->id()!='') {
					$this->layoutPage = 'post';
					$this->metaUrl = $item->url();
					$this->titlePage = $item->getBasicInfo();
					$this->metaDescription = $this->titlePage.' - '.$item->get('shortDescription');
					$this->metaImage = $item->getImageUrl('image', 'web');
					$this->breadCrumbs = array(url('articulos')=>'Artículos', $item->url()=>$item->getBasicInfo());
					$this->content = $item->showUi('Complete');
				} else {
					$this->metaUrl = url('articulos');
					$this->titlePage = 'Listado de artículos';
					$this->metaDescription = $this->titlePage;
					$this->breadCrumbs = array(url('articulos')=>'Artículos');
					$post = new Post();
					$this->content = $post->showUi('CompletePosts');
				}
				return $this->ui->render();
			break;
			case 'buscar':
				if (isset($this->values['search']) && $this->values['search']!='') {
					$search = Text::simpleUrl($this->values['search']);
					header('Location: '.url('buscar/'.$search));
					exit();
				}
				if ($this->id!='') {
					$this->metaUrl = url($this->action.'/'.$this->id);
					$search = str_replace('-', ' ', Text::simpleUrl($this->id));
					$this->titlePage = 'Resultados de la busqueda - '.ucwords($search);
					$items = new ListObjects('Recipe', array('where'=>'MATCH (name, nameUrl, description, preparation) AGAINST ("'.$search.'")', 'order'=>'MATCH (name, nameUrl, description, preparation) AGAINST ("'.$search.'") DESC', 'limit'=>'20'));
					if ($items->isEmpty()) {
						$items = new ListObjects('Recipe', array('where'=>'CONCAT(name," ",nameUrl," ",description," ",preparation) LIKE ("%'.$search.'%")', 'order'=>'nameUrl', 'limit'=>'20'));
					}
					if ($items->isEmpty()) {
						$this->titlePage = 'Lo sentimos, no encontramos resultados para su búsqueda';
						$itemsOther = new ListObjects('Recipe', array('order'=>'RAND()', 'limit'=>'20'));
					}
					$this->content = '<div class="itemsAll">
										'.Adsense::top().'
										'.$items->showList(array('function'=>'Public', 'middle'=>Adsense::top(), 'middleRepetitions'=>2)).'
									</div>';
					return $this->ui->render();
				} else {
					header('Location: '.url('error'));
				}
			break;


			//JSON
			case 'json-phonegap':
				$this->mode = 'json';
				$info = array('categories'=>array(),
								'recipes'=>array());
				$items = Category::readList(array('order'=>'ord'));
				foreach($items as $item) {
					$infoIns = $item->toArray();
					$info['categories'][] = $infoIns;
				}
				$items = Recipe::readList(array('order'=>'nameUrl'));
				foreach($items as $item) {
					copy(str_replace(BASE_URL, BASE_FILE, $item->getImageUrl('image', 'web')), LOCAL_FILE.'phonegap/www/img/recipe/'.$item->get('idRecipe').'.jpg');
					$infoIns = $item->toArray();
					$info['recipes'][] = $infoIns;
				}
				$content = 'var db = '.json_encode($info).';';
				$content = str_replace(LOCAL_URL,PROD_URL,$content);
				File::saveFile(LOCAL_FILE.'phonegap/www/js/db.js', $content);
				return 'DONE';
			break;


		}
	}
}
?>