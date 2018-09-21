<?php
class Navigation_Ui extends Ui {

	public function render() {
		$layoutPage = (isset($this->object->layoutPage)) ? $this->object->layoutPage : '';
		$title = (isset($this->object->titlePage)) ? '<h1>'.$this->object->titlePage.'</h1>' : '';
		$message = (isset($this->object->message)) ? '<div class="message">'.$this->object->message.'</div>' : '';
		$messageError = (isset($this->object->messageError)) ? '<div class="message messageError">'.$this->object->messageError.'</div>' : '';
		$menuInside = (isset($this->object->menuInside)) ? $this->object->menuInside : '';
		$content = (isset($this->object->content)) ? $this->object->content : '';
		$contentExtra = (isset($this->object->contentExtra)) ? $this->object->contentExtra : '';
		$idInside = (isset($this->object->idInside)) ? $this->object->idInside : '';
		switch ($layoutPage) {
			case 'intro':
				return $this->header().'
						'.$this->menu().'
						<div class="contentFormatWrapper">
							<div class="contentFormat">
								<div class="contentFormatIns">
									'.$content.'
								</div>
							</div>
						</div>
						'.$this->footer();
			break;
			default:
				return $this->header().'
						'.$this->menu().'
						<div class="contentFormatWrapper">
							<div class="contentFormat">
								<div class="contentFormatIns">
									'.$this->breadCrumbs().'
									'.$title.'
									<div class="contentLeft">
										'.$content.'
									</div>
									<div class="contentRight">
										<aside>
											'.Adsense::inline().'
											'.Recipe_Ui::side().'
											'.Adsense::inline().'
											'.Post_Ui::side().'
										</aside>
									</div>
								</div>
							</div>
						</div>
						'.$this->footer();
			break;
			case 'simple':
				return $this->header().'
						'.$this->menu().'
						<div class="contentFormatWrapper">
							<div class="contentFormat">
								<div class="contentFormatIns">
									'.$this->breadCrumbs().'
									'.$title.'
									'.Adsense::top().'
									<div class="contentSimple">
										'.$content.'
									</div>
								</div>
							</div>
						</div>
						'.$this->footer();
			break;
			case 'single':
				return $this->header().'
						'.$this->menu().'
						<div class="contentFormatWrapper">
							<div class="contentFormat">
								<div class="contentFormatIns">
									'.$this->breadCrumbs().'
									'.$title.'
									<div class="contentSimple">
										'.$content.'
									</div>
								</div>
							</div>
						</div>
						'.$this->footer();
			break;
			case 'recipe':
				return Navigation_Ui::facebookHeader().'
						'.$this->header().'
						'.$this->menu().'
						<div class="contentFormatWrapper">
							<div class="contentFormat">
								<div class="contentFormatIns">
									'.$this->breadCrumbs().'
									<div itemscope itemtype="http://schema.org/Recipe">
										<h1 itemprop="name">'.$this->object->titlePage.'</h1>
										<span style="display:none;" itemprop="author" itemscope itemtype="http://schema.org/Person">
											<span itemprop="name">'.Params::param('titlePage').'</span>
										</span>
										'.$content.'
									</div>
								</div>
							</div>
						</div>
						'.$this->footer();
			break;
			case 'post':
				return Navigation_Ui::facebookHeader().'
						'.$this->header().'
						'.$this->menu().'
						<div class="contentFormatWrapper contentFormatWrapperPost">
							<div class="contentFormat">
								<div class="contentFormatIns">
									'.$this->breadCrumbs().'
									<div itemscope itemtype="http://schema.org/Article">
										<h1 itemprop="headline">'.$this->object->titlePage.'</h1>
										<div style="display:none;" itemprop="author" itemscope itemtype="http://schema.org/Organization">
											<span itemprop="name">'.Params::param('titlePage').'</span>
											<img itemprop="logo" src="'.LOCAL_URL.'visual/img/logo.png"/>
										</div>
										<div style="display:none;" itemprop="publisher" itemscope itemtype="http://schema.org/Organization">
											<span itemprop="name">PlasticWebs</span>
											<img itemprop="logo" src="https://www.plasticwebs.com/plastic/visual/img/logo.png"/>
										</div>
										<div class="contentLeft">
											'.$content.'
										</div>
										<div class="contentRight">
											<aside>
												'.Adsense::inline().'
												'.Recipe_Ui::side().'
											</aside>
										</div>
									</div>
								</div>
							</div>
						</div>
						'.$this->footer();
			break;
		}
	}

	public function header() {
		return '<header>
					<div class="menuMobile" onclick="showHideMenu()"></div>
					<div class="headerWrapper">
						<div class="header">
					        <div class="headerLeft">
						    	<div class="logo">
						    		<a href="'.url('').'">'.Params::param('titlePage').'</a>
						    	</div>
					        </div>
					        <div class="headerRight">
								<div class="searchTop">
									<form accept-charset="UTF-8" class="formSearchSimple" enctype="multipart/form-data" method="post" action="'.url('buscar').'">
										<fieldset>
											<div class="text formField ">
												<input type="text" size="50" name="search" placeholder="'.__('search').'">
											</div>
											<button type="submit" class="formSubmit">
												<span class="iconSearch">'.__('search').'</span>
											</button>
										</fieldset>
									</form>
								</div>
							</div>
						</div>
					</div>
				</header>';
	}

	public function shareIcons() {
		return '<div class="shareIcons">
	        		<div class="shareIcon shareIconFacebook">
	        			<a href="'.Params::param('linksocial-facebook').'" target="_blank">Facebook</a>
	        		</div>
	        		<div class="shareIcon shareIconTwitter">
	        			<a href="'.Params::param('linksocial-twitter').'" target="_blank">Twitter</a>
	        		</div>
	        	</div>';
	}

	public function footer() {
		return '<footer>
					<div class="footer">
						<div class="footerLinks">
							<h3>Otros sitios de cocina por países</h3>
							<a href="http://www.cocina-argentina.com" target="_blank" title="Recetas de cocina de Argentina">Argentina</a>
							<a href="https://www.cocina-boliviana.com" target="_blank" title="Recetas de cocina de Bolivia">Bolivia</a>
							<a href="https://www.cocina-brasilena.com" target="_blank" title="Recetas de cocina de Brasil">Brasil</a>
							<a href="https://www.cocina-chilena.com" target="_blank" title="Recetas de cocina de Chile">Chile</a>
							<a href="https://www.cocina-colombiana.com" target="_blank" title="Recetas de cocina de Colombia">Colombia</a>
							<a href="https://www.cocina-cubana.com" target="_blank" title="Recetas de cocina de Cuba">Cuba</a>
							<a href="https://www.cocina-ecuatoriana.com" target="_blank" title="Recetas de cocina de Ecuador">Ecuador</a>
							<a href="http://www.la-cocina-mexicana.com" target="_blank" title="Recetas de cocina de México">México</a>
							<a href="https://www.comida-peruana.com" target="_blank" title="Recetas de cocina del Peru">Peru</a>
						</div>
						<div class="footerIns">
							<div class="footerLeft">
								'.$this->shareIcons().'
								<div class="pageComplete">
									<p><strong>© '.Params::param('metainfo-titlePage').'</strong></p>
									<p>Para mayor información escribenos a <a href="mailto:info@plasticwebs.com">info@plasticwebs.com</a></p>
									<p>'.Params::param('metainfo-metaDescription').'</p>
								</div>
							</div>
							<div class="footerRight">
								<div class="appButtons">
									<p>Descarga nuestra aplicaci&oacute;n en:</p>
									<div class="appButton appButtonApple">
										<a href="'.Url::format(Params::param('link-app-store')).'" target="_blank">App Store</a>
									</div>
									<div class="appButton appButtonGoogle">
										<a href="'.Url::format(Params::param('link-google-play')).'" target="_blank">Google Play</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</footer>';
	}

	public function menu() {
		$category = new Category();
		if ($this->object->action=='recetas') {
			$infoCategory = explode('_', $this->object->id);
			$category = Category::read($infoCategory[0]);
		}
		$categories = new ListObjects('Category', array('order'=>'ord', 'limit'=>'6'));
		return '<nav class="menuWrapper">
					<nav class="menuAll" id="menu">
						<ul>
							<li class="hideMobile '.(($this->object->action=='intro') ? 'selected' : '').'"><a href="'.url('').'">Inicio</a></li>'.$categories->showList(array('function'=>'Menu'), array('categorySelected'=>$category)).'<li class="menuArticles '.(($this->object->action=='articulos') ? 'selected' : '').'"><a href="'.url('articulos').'">Artículos</a></li>
						</ul>
					</nav>
				</div>';
	}

	public function breadCrumbs() {
		if (isset($this->object->breadCrumbs)) {
			return Navigation_Ui::renderBreadCrumbs($this->object->breadCrumbs);
		}
	}

	static public function renderBreadCrumbs($breadCrumbs=array()) {
		$html = '';
		if (is_array($breadCrumbs)) {
			$html .= '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
						<a href="'.url('').'" itemprop="item">
							<span itemprop="name">'.__('home').'</span>
						</a>
					</span> &raquo;';
			foreach ($breadCrumbs as $url=>$title) {
				$html .= '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
								<a href="'.$url.'" itemprop="item">
									<span itemprop="name">'.$title.'</span>
								</a>
							</span> &raquo;';
			}
			$html = '<div class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">
						'.substr($html, 0, -8).'
					</div>';
		}
		return $html;
	}

	static public function facebookHeader() {
		return '<div id="fb-root"></div>
					<script>(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) return;
					  js = d.createElement(s); js.id = id;
					  js.src = \'https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.12&appId=168728593755836&autoLogAppEvents=1\';
					  fjs.parentNode.insertBefore(js, fjs);
					}(document, \'script\', \'facebook-jssdk\'));</script>';
	}

	static public function facebookComments($url) {
		return '<div class="fb-comments" data-href="'.$url.'" data-width="100%" data-numposts="5"></div>';
	}

}
?>