<?php
class Navigation_Controller extends Controller{

	public function __construct($GET, $POST, $FILES) {
		parent::__construct($GET, $POST, $FILES);
		$this->ui = new Navigation_Ui($this);
	}

	public function controlActions(){
		$this->mode = 'amp';
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
				if ($this->extraId!='') {
					$info = explode('_', $this->extraId);
					$item = (isset($info[1])) ? Recipe::read($info[0]) : Recipe::readFirst(array('where'=>'nameUrl="'.$this->extraId.'"'));
				}
				if ($this->extraId!='' && $item->id()!='') {
					if ((isset($_GET['pagina']) && $_GET['pagina']!='') || $this->extraId!=$item->get('nameUrl')) {
						header("HTTP/1.1 301 Moved Permanently");
						header('Location: '.$item->url());
						exit();
					}
					$this->layoutPage = 'recipe';
					$this->metaUrl = $item->url();
					$this->titlePage = $item->getBasicInfo();
					$this->metaDescription = $item->get('description');
					$this->metaImage = $item->getImageUrl('image', 'web');
					$this->header = $item->showUi('JsonHeader').$this->ampFacebookHeader();
					$parent = Category::read($item->get('idCategory'));
					$this->breadCrumbs = array(url('recetas')=>'Recetas', $parent->url()=>$parent->getBasicInfo(), $item->url()=>$item->getBasicInfo());
					$this->content = $item->showUi('Complete');
				} else {
					if ($this->id!='') {
						$info = explode('_', $this->id);
						$item = (isset($info[1])) ? Category::read($info[0]) : Category::readFirst(array('where'=>'nameUrl="'.$this->id.'"'));
					}
					if ($this->id!='' && $item->id()!='') {
						if ($this->extraId!='' || $this->id!=$item->get('nameUrl')) {
							header("HTTP/1.1 301 Moved Permanently");
							header('Location: '.$item->url());
							exit();
						}
						$itemUi = new Category_Ui($item);
						$this->metaUrl = $item->url();
						$this->titlePage = ($item->get('title')!='') ? $item->get('title') : 'Listado de recetas de '.strtolower($item->getBasicInfo());
						$this->breadCrumbs = array(url('recetas')=>'Recetas', $item->url()=>$item->getBasicInfo());
						$this->metaDescription = ($item->get('description')!='') ? $item->get('description') : $this->titlePage;
						$items = new ListObjects('Recipe', array('where'=>'active="1" AND idCategory="'.$item->id().'"', 'order'=>'nameUrl', 'results'=>'12'));
						$this->header = $items->metaNavigation().'
										'.$itemUi->renderJsonHeader($items);
						$this->content = '<div class="listAll">
											'.Adsense::amp().'
											'.$items->showList(array('function'=>'Public','middle'=>Adsense::amp(), 'middleRepetitions'=>2)).'
											'.$items->pager().'
										</div>';
					} else {
						if ($this->id!='') {
							header("HTTP/1.1 301 Moved Permanently");
							header('Location: '.url($this->action));
							exit();
						}
						$this->metaUrl = url($this->action);
						$this->titlePage = 'Listado de recetas';
						$this->metaDescription = $this->titlePage;
						$this->breadCrumbs = array(url('recetas')=>'Recetas');
						$items = new ListObjects('Recipe', array('where'=>'active="1"', 'order'=>'nameUrl', 'results'=>'12'));
						$this->header = $items->metaNavigation();
						$this->content = '<div class="listAll">
											'.Adsense::amp().'
											'.$items->showList(array('function'=>'Public','middle'=>Adsense::amp(), 'middleRepetitions'=>2)).'
											'.$items->pager().'
										</div>';
					}
				}
				return $this->ui->render();
			break;

			case 'articulos':
				if ($this->id!='') {
					$info = explode('_', $this->id);
					$item = (isset($info[1])) ? Post::read($info[0]) : Post::readFirst(array('where'=>'titleUrl="'.$this->id.'"'));
				}
				if ($this->id!='' && $item->id()!='') {
					$this->layoutPage = 'post';
					$this->metaUrl = $item->url();
					$this->titlePage = $item->getBasicInfo();
					$this->metaDescription = $item->get('shortDescription');
					$this->metaImage = $item->getImageUrl('image', 'web');
					$this->header = $item->showUi('JsonHeader').$this->ampFacebookHeader();
					$this->breadCrumbs = array(url('articulos')=>'Artículos', $item->url()=>$item->getBasicInfo());
					$this->content = $item->showUi('Complete');
				} else {
					if ($this->id!='') {
						header("HTTP/1.1 301 Moved Permanently");
						header('Location: '.url('articulos'));
						exit();
					}
					$this->metaUrl = url('articulos');
					$this->titlePage = 'Listado de artículos';
					$this->metaDescription = $this->titlePage;
					$this->breadCrumbs = array(url('articulos')=>'Artículos');
					$items = new ListObjects('Post', array('order'=>'publishDate DESC', 'results'=>'12'));
					$this->header = $items->metaNavigation();
					$this->content = '<div class="listAllSimple">
										'.Adsense::amp().'
										'.$items->showList(array('function'=>'Public','middle'=>Adsense::amp(), 'middleRepetitions'=>2)).'
										'.$items->pager().'
									</div>';
				}
				return $this->ui->render();
			break;

			case 'buscar':
				if (isset($_GET['search']) && $_GET['search']!='') {
					$search = Text::simpleUrl($_GET['search']);
					header("HTTP/1.1 301 Moved Permanently");
					header('Location: '.url('buscar/'.$search));
					exit();
				}
				if ($this->id!='') {
					$this->headersFormAmp();
					$this->metaUrl = url($this->action.'/'.$this->id);
					$search = str_replace('-', ' ', Text::simpleUrl($this->id));
					$this->titlePage = 'Resultados de la busqueda - '.ucwords($search);
					$items = new ListObjects('Recipe', array('where'=>'active="1" AND MATCH (name, nameUrl, description, preparation) AGAINST ("'.$search.'")', 'order'=>'MATCH (name, nameUrl, description, preparation) AGAINST ("'.$search.'") DESC', 'limit'=>'20'));
					if ($items->isEmpty()) {
						$items = new ListObjects('Recipe', array('where'=>'active="1" AND CONCAT(name," ",nameUrl," ",description," ",preparation) LIKE ("%'.$search.'%")', 'order'=>'nameUrl', 'limit'=>'20'));
					}
					if ($items->isEmpty()) {
						$this->titlePage = 'Lo sentimos, no encontramos resultados para su búsqueda';
						$itemsOther = new ListObjects('Recipe', array('where'=>'active="1"', 'order'=>'RAND()', 'limit'=>'20'));
					}
					$this->content = '<div class="itemsAll">
										'.Adsense::amp().'
										'.$items->showList(array('function'=>'Public', 'middle'=>Adsense::amp(), 'middleRepetitions'=>2)).'
									</div>';
					return $this->ui->render();
				} else {
					header('Location: '.url('error'));
				}
			break;


			//JSON
			case 'json-phonegap':
				$this->mode = 'ajax';
				$this->checkAuthorization();
				$info = array('site'=>array('title'=>Params::param('metainfo-titlePage'),
											'titleGeneric'=>Params::param('titleGeneric'),
											'titleCountry'=>Params::param('titleCountry'),
											'description'=>Params::param('metainfo-metaDescription'),
											'url'=>url(''),
											'version'=>(Params::param('appVersion') ? Params::param('appVersion') : '4.0.0'),
											'id'=>Params::param('appId'),
											'admobBanner'=>Params::param('admobBanner'),
											'admobIntersitial'=>Params::param('admobIntersitial')),
								'categories'=>array(),
								'recipes'=>array());
				$items = Category::readList(array('order'=>'ord'));
				foreach($items as $item) {
					$infoIns = (array)$item->values;
					unset($infoIns['created']);
					unset($infoIns['modified']);
					unset($infoIns['ord']);
					$info['categories'][] = $infoIns;
				}
				$items = Recipe::readList(array('order'=>'nameUrl'));
				foreach($items as $item) {
					$item->loadMultipleValuesAll();
					$infoIns = (array)$item->values;
					unset($infoIns['created']);
					unset($infoIns['modified']);
					unset($infoIns['ord']);
					$infoIns['ingredients'] = array_map(function($item) {return $item['label'];}, (array)$infoIns['ingredients']);
					$info['recipes'][] = $infoIns;
				}
				$content = json_encode($info, JSON_PRETTY_PRINT);
				return $content;
			break;
			case 'fix':
				$this->mode = 'ajax';
				$this->checkAuthorization();
				// Add the active checkbox
				Db::execute('ALTER TABLE `'.Db::prefixTable('Recipe').'` ADD `active` INT NULL;');
				Db::execute('UPDATE `'.Db::prefixTable('Recipe').'` SET `active`=1;');
				// // Add title and description to categories
				// Db::execute('ALTER TABLE `'.Db::prefixTable('Category').'` ADD `title` VARCHAR(255) NULL;');
				// Db::execute('ALTER TABLE `'.Db::prefixTable('Category').'` ADD `description` TEXT NULL;');
				/*
				// Fix encoding
				$items = Category::readList();
				foreach($items as $item) {
					$item->modify(array('name'=>html_entity_decode($item->get('name'), ENT_COMPAT, 'UTF-8')));
				}
				$items = Recipe::readList();
				foreach($items as $item) {
					$item->modify(array(
										'name'=>html_entity_decode($item->get('name'), ENT_COMPAT, 'UTF-8'),
										'description'=>html_entity_decode($item->get('description'), ENT_COMPAT, 'UTF-8'),
										'preparation'=>html_entity_decode($item->get('preparation'), ENT_COMPAT, 'UTF-8')
								));
				}
				$items = RecipeIngredient::readList();
				foreach($items as $item) {
					$item->modifySimple('label', html_entity_decode($item->get('label'), ENT_COMPAT, 'UTF-8'));
				}
				*/
				return 'DONE';
			break;

			/**
            * SAVE IMAGE
            */
            case 'save-image':
            	$this->mode = 'ajax';
            	$this->checkAuthorization();
                $recipe = Recipe::read($this->id);
                if (isset($this->values['image_base64']) && $this->values['image_base64']!='') {
        			$fileSave = Text::simpleUrlFileBase($recipe->id().'_image');
                    if (Image_File::saveImageUrl($this->values['image_base64'], 'Recipe', $fileSave)) {
                        $recipe->modifySimple('image', $fileSave);
                    }
        		}
                return 'DONE';
            break;

			/**
            * GITHUB
            */
            case 'check-github-now':
            	$this->mode = 'ajax';
            	$this->checkAuthorization();
                $url = "https://github.com/theylooksotired/cocina/archive/master.zip";
                $zipFile = LOCAL_FILE."master.zip";
                file_put_contents($zipFile, fopen($url, 'r'));
                $zip = new ZipArchive;
                $res = $zip->open($zipFile);
                if ($res === TRUE) {
                    $zip->extractTo('.');
                    $zip->close();
                }
                unlink($zipFile);
                shell_exec('cp -r '.LOCAL_FILE.'cocina-master/* '.LOCAL_FILE);
                shell_exec('rm -rf '.LOCAL_FILE.'cocina-master');
                return 'DONE';
            break;


		}
	}

	function checkAuthorization() {
		$headers = apache_request_headers();
		if (!isset($headers) || !isset($headers['Authorization']) || $headers['Authorization']!='plastic') {
			header('Location: '.url(''));
			exit();
		}
	}

	function headersFormAmp() {
		header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Origin: ". str_replace('.', '-', SERVER_URL) .".cdn.ampproject.org");
        header("AMP-Access-Control-Allow-Source-Origin: " . SERVER_URL);
        header("Access-Control-Expose-Headers: AMP-Access-Control-Allow-Source-Origin");
	}

	function ampFacebookHeader() {
		return '<script async custom-element="amp-facebook-comments" src="https://cdn.ampproject.org/v0/amp-facebook-comments-0.1.js"></script>';
	}

}
?>