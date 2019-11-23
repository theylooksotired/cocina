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
											'.Adsense::ampInline().'
											'.Recipe_Ui::side().'
											'.Adsense::ampInline().'
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
						<div class="contentFormatWrapper">
							<div class="contentFormat">
								<div class="contentFormatIns">
									'.$this->breadCrumbs().'
									'.$title.'
									'.Adsense::amp().'
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
						<div class="contentFormatWrapper">
							<div class="contentFormat">
								<div class="contentFormatIns">
									'.$this->breadCrumbs().'
									<div class="contentRecipe">
										<h1>'.$this->object->titlePage.'</h1>
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
						<div class="contentFormatWrapper contentFormatWrapperPost">
							<div class="contentFormat">
								<div class="contentFormatIns">
									'.$this->breadCrumbs().'
									<div class="contentArticle">
										<h1>'.$this->object->titlePage.'</h1>
										<div class="contentLeft">'.$content.'</div>
										<div class="contentRight">
											<aside>
												'.Adsense::ampInline().'
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
					<div class="menuMobile" role="button" on="tap:menu.toggle" tabindex="0">
						<i class="icon icon-menu"></i>
					</div>
					<div class="headerWrapper">
						<div class="header">
					        <div class="headerLeft">
						    	<div class="logo">
						    		<a href="'.url('').'">
						    			<span>'.Params::param('logoTop').'</span>
						    			<em>'.Params::param('logoBottom').'</em>
						    		</a>
						    	</div>
					        </div>
					        <div class="headerRight">
								<div class="searchTop">
									<form accept-charset="UTF-8" class="formSearchSimple" action="'.url(['es'=>'buscar', 'en'=>'search', 'fr'=>'rechercher', 'pt'=>'buscar']).'" method="GET" target="_top">
										<fieldset>
											<div class="text formField ">
												<input type="text" size="50" name="search" placeholder="'.__('search').'">
											</div>
											<button type="submit" class="formSubmit"><i class="icon icon-search"></i></button>
										</fieldset>
									</form>
								</div>
							</div>
						</div>
					</div>
				</header>
				'.$this->menu();
	}

	public function shareIcons() {
		return '<div class="shareIcons">
	        		<div class="shareIcon shareIconFacebook">
	        			<a href="https://www.facebook.com/RecetasCocinaRC/" target="_blank">
	        				<i class="icon icon-facebook"></i>
	        				<span>Facebook</span>
	        			</a>
	        		</div>
	        		<div class="shareIcon shareIconTwitter">
	        			<a href="https://twitter.com/RecetasCocinaRC/" target="_blank">
	        				<i class="icon icon-twitter"></i>
	        				<span>Twitter</span>
	        			</a>
	        		</div>
	        	</div>';
	}

	public function footer() {
		return '<footer>
					<div class="footer">
						<div class="footerLinks">
							<h3>'.__('otherSitesCountry').'</h3>
							<a href="https://www.recetas-argentinas.com" target="_blank" title="Recetas de Argentina">Argentina</a>
							<a href="https://www.cocina-boliviana.com" target="_blank" title="Recetas de Bolivia">Bolivia</a>
							<a href="https://www.cocina-brasilena.com" target="_blank" title="Recetas de Brasil">Brasil</a>
							<a href="https://www.cocina-chilena.com" target="_blank" title="Recetas de Chile">Chile</a>
							<a href="https://www.cocina-colombiana.com" target="_blank" title="Recetas de Colombia">Colombia</a>
							<a href="https://www.recetascostarica.com" target="_blank" title="Recetas de Costa Rica">Costa Rica</a>
							<a href="https://www.cocina-cubana.com" target="_blank" title="Recetas de Cuba">Cuba</a>
							<a href="https://www.cocina-ecuatoriana.com" target="_blank" title="Recetas de Ecuador">Ecuador</a>
							<a href="https://www.recetas-espana.com" target="_blank" title="Recetas de España">España</a>
							<a href="https://www.recetassalvador.com" target="_blank" title="Recetas del Salvador">El Salvador</a>
							<a href="https://www.recetas-guatemala.com" target="_blank" title="Recetas de Guatemala">Guatemala</a>
							<a href="https://www.recetashonduras.com" target="_blank" title="Recetas de Honduras">Honduras</a>
							<a href="https://www.recetas-italia.com" target="_blank" title="Recetas de Italia">Italia</a>
							<a href="https://www.la-cocina-mexicana.com" target="_blank" title="Recetas de México">México</a>
							<a href="https://www.recetas-nicaragua.com" target="_blank" title="Recetas de Nicaragua">Nicaragua</a>
							<a href="https://www.recetaspanama.com/" target="_blank" title="Recetas de Panamá">Panamá</a>
							<a href="https://www.comida-peruana.com" target="_blank" title="Recetas de Peru">Peru</a>
							<a href="https://www.cocina-uruguaya.com" target="_blank" title="Recetas de Uruguay">Uruguay</a>
						</div>
						<div class="footerLinks">
							<h3>'.__('otherSitesType').'</h3>
							<a href="https://www.recetaspizzas.com" target="_blank" title="Recetas de Pizzas">Pizzas</a>
							<a href="https://www.receta-vegetariana.com" target="_blank" title="Recetas vegetariana">Vegetariana</a>
							<a href="https://www.recetas-veganas.com" target="_blank" title="Recetas veganas">Veganas</a>
						</div>
						<div class="footerIns">
							<div class="footerLeft">
								'.$this->shareIcons().'
								<div class="pageComplete">
									<p><strong>© '.Params::param('metainfo-titlePage').'</strong></p>
									<p>'.__('moreInformationWrite').' <a href="mailto:info@plasticwebs.com">info@plasticwebs.com</a></p>
									<p>'.Params::param('metainfo-metaDescription').'</p>
								</div>
							</div>
						</div>
					</div>
				</footer>';
	}

	/*
	<div class="footerRight">
		<div class="appButtons">
			<p>Descarga nuestra aplicación en:</p>
			<div class="appButton appButtonGoogle">
				<a href="'.Url::format(Params::param('link-google-play')).'" target="_blank">
					<i class="icon icon-android"></i>
					<span>Google Play</span>
				</a>
			</div>
			<div class="appButton">
				<a href="'.Url::format(Params::param('link-app-store')).'" target="_blank">
					<i class="icon icon-apple"></i>
					<span>App Store</span>
				</a>
			</div>
		</div>
	</div>
	*/

	public function menu() {
		return '<div class="menuWrapper"><div class="menuAll" id="menu">'.Navigation_Ui::menuItems().'</div></div>';
	}

	static public function menuAmp() {
		return '<amp-sidebar id="menu" class="menuAmp" layout="nodisplay" side="left">'.Navigation_Ui::menuItems().'</amp-sidebar>';
	}

	static public function menuItems() {
		$category = new Category();
		if (isset($_GET['action']) && $_GET['id'] && $_GET['action']=='recetas' && $_GET['id']!='') {
			$category = Category::readFirst(array('where'=>'nameUrl="'.$_GET['id'].'"'));
		}
		$categories = new ListObjects('Category', array('order'=>'ord', 'limit'=>'6'));
		return '<ul>
					<li class="hideMobile '.(($_GET['action']=='intro') ? 'selected' : '').'">
						<a href="'.url('').'">'.__('home').'</a>
					</li>
					'.$categories->showList(array('function'=>'Menu'), array('categorySelected'=>$category)).'
					<li class="menuArticles '.(in_array($_GET['action'], ['articulos', 'articles', 'posts', 'artigos']) ? 'selected' : '').'">
						<a href="'.url(['es'=>'articulos', 'fr'=>'articles', 'en'=>'posts', 'pt'=>'artigos']).'">'.__('posts').'</a>
					</li>
				</ul>';
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
						<meta itemprop="position" content="1" />
					</span> &raquo;';
			$i = 2;
			foreach ($breadCrumbs as $url=>$title) {
				$html .= '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
								<a href="'.$url.'" itemprop="item">
									<span itemprop="name">'.$title.'</span>
								</a>
								<meta itemprop="position" content="'.$i.'" />
							</span> &raquo;';
				$i++;
			}
			$html = '<div class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">
						'.substr($html, 0, -8).'
					</div>';
		}
		return $html;
	}

	static public function facebookHeader() {
		return '';
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
		return '<amp-facebook-comments layout="responsive" height="300" width="600" data-href="'.$url.'" data-locale="'.Lang::locale().'"></amp-facebook-comments>';
	}

	static public function analytics() {
		return '<script async src="https://www.googletagmanager.com/gtag/js?id='.Params::param('metainfo-google-analytics').'"></script>
			    <script>
			      window.dataLayer = window.dataLayer || [];
			      function gtag(){dataLayer.push(arguments);}
			      gtag(\'js\', new Date());
			      gtag(\'config\', \''.Params::param('metainfo-google-analytics').'\');
			    </script>';
	}

	static public function analyticsAmp() {
		return '<amp-analytics type="googleanalytics">
			<script type="application/json">{"vars": {"account": "'.Params::param('metainfo-google-analytics').'"}, "triggers": { "trackPageview": { "on": "visible", "request": "pageview"}}}</script>
		</amp-analytics>';
	}

	static public function autoadsAmp() {
		return '<amp-auto-ads type="adsense" data-ad-client="ca-pub-7429223453905389"></amp-auto-ads>';
	}

}
?>