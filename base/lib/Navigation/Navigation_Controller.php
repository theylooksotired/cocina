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
			case 'recettes':
			case 'recipes':
			case 'receitas':
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
					$this->breadCrumbs = array(url($this->action)=>__('recipes'), $parent->url()=>$parent->getBasicInfo(), $item->url()=>$item->getBasicInfo());
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
						$this->titlePageSimple = ($item->get('title')!='') ? $item->get('title') : 'Listado de recetas de '.strtolower($item->getBasicInfo());
						$this->breadCrumbs = array(url($this->action)=>__('recipes'), $item->url()=>$item->getBasicInfo());
						$this->metaDescription = ($item->get('description')!='') ? $item->get('description') : $this->titlePageSimple;
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
						$this->titlePage = __('recipesList');
						$this->metaDescription = $this->titlePage;
						$this->breadCrumbs = array(url($this->action)=>__('recipes'));
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
			case 'articles':
			case 'posts':
			case 'artigos':
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
					$this->breadCrumbs = array(url($this->action)=>__('posts'), $item->url()=>$item->getBasicInfo());
					$this->content = $item->showUi('Complete');
				} else {
					if ($this->id!='') {
						header("HTTP/1.1 301 Moved Permanently");
						header('Location: '.url($this->action));
						exit();
					}
					$this->metaUrl = url($this->action);
					$this->titlePage = __('postsList');
					$this->metaDescription = $this->titlePage;
					$this->breadCrumbs = array(url($this->action)=>__('posts'));
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
			case 'rechercher':
			case 'search':
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
					$this->titlePage = __('searchResults').' - '.ucwords($search);
					$items = new ListObjects('Recipe', array('where'=>'active="1" AND MATCH (name, nameUrl, description, preparation) AGAINST ("'.$search.'")', 'order'=>'MATCH (name, nameUrl, description, preparation) AGAINST ("'.$search.'") DESC', 'limit'=>'20'));
					if ($items->isEmpty()) {
						$items = new ListObjects('Recipe', array('where'=>'active="1" AND CONCAT(name," ",nameUrl," ",description," ",preparation) LIKE ("%'.$search.'%")', 'order'=>'nameUrl', 'limit'=>'20'));
					}
					if ($items->isEmpty()) {
						$this->titlePage = __('noSearchResults');
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
				$exists = Db::returnSingle('SELECT column_name FROM information_schema.columns where table_schema="'.DB_NAME.'" AND TABLE_NAME="rec_Category" AND column_name="title"');
				if (!$exists) {
					Db::execute('ALTER TABLE rec_Category ADD title VARCHAR(255) NULL');
				}
				$exists = Db::returnSingle('SELECT column_name FROM information_schema.columns where table_schema="'.DB_NAME.'" AND TABLE_NAME="rec_Category" AND column_name="description"');
				if (!$exists) {
					Db::execute('ALTER TABLE rec_Category ADD description TEXT NULL');
				}
				$exists = Db::returnSingle('SELECT column_name FROM information_schema.columns where table_schema="'.DB_NAME.'" AND TABLE_NAME="rec_Lang" AND column_name="locale"');
				if (!$exists) {
					Db::execute('ALTER TABLE rec_Lang ADD locale VARCHAR(255) NULL');
				}
				Db::execute('UPDATE rec_Lang SET locale="es_LA"');
				Db::execute('DELETE FROM rec_Params WHERE code="email"');
				Db::execute('DELETE FROM rec_Params WHERE code="email-contact"');
				Db::execute('DELETE FROM rec_Params WHERE code="linksocial-facebook"');
				Db::execute('DELETE FROM rec_Params WHERE code="linksocial-twitter"');
				Db::execute('DELETE FROM rec_Params WHERE code="link-app-store"');
				Db::execute('DELETE FROM rec_Params WHERE code="link-google-play"');
				Db::execute('DELETE FROM rec_Params WHERE code LIKE "adsense%"');
				Db::execute('INSERT INTO rec_Params SET code="logoTop", name="Logo - Top", information="Recetas de"');
				Db::execute('INSERT INTO rec_Params SET code="logoBottom", name="Logo - Bottom", information="'.Params::param('country').'"');
				Db::execute('UPDATE rec_Params SET code="metainfo-metaDescription" WHERE code="metainfo-metaDescription-es"');
				Db::execute('UPDATE rec_Params SET code="metainfo-metaKeywords" WHERE code="metainfo-metaKeywords-es"');
				Db::execute('UPDATE rec_Params SET code="metainfo-titlePage" WHERE code="metainfo-titlePage-es"');
				Db::execute('DROP TABLE IF EXISTS rec_Ingredient');

				Db::execute("DROP TABLE IF EXISTS `rec_LangTrans`;
							CREATE TABLE `rec_LangTrans` (
							  `idLangTrans` int(11) NOT NULL,
							  `code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
							  `translation_es` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
							  `translation_en` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
							  `translation_fr` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
							  `translation_pt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

							INSERT INTO `rec_LangTrans` (`idLangTrans`, `code`, `translation_es`, `translation_en`, `translation_fr`, `translation_pt`) VALUES
							(1, 'accepted', 'Aceptado', 'Accepted', 'Confirmé', 'Aceptado'),
							(2, 'addNewRegister', 'Añadir un registro', 'Add a new record', 'Ajouter un nouveau record', 'Añadir un registro'),
							(3, 'april', 'Abril', 'April', 'Avril', 'Abril'),
							(4, 'label', 'Etiqueta', 'Label', 'Libelle', 'Etiqueta'),
							(5, 'august', 'Agosto', 'August', 'Août', 'Agosto'),
							(6, 'back', 'Volver', 'Back', 'Retour', 'Volver'),
							(7, 'cancel', 'Cancelar', 'Cancel', 'Annuler', 'Cancelar'),
							(8, 'here', 'aquí', 'here', 'ici', 'aquí'),
							(9, 'changePassword', 'Cambiar mi contraseña', 'Change my Password', 'Changer mon mot de passe', 'Cambiar mi contraseña'),
							(10, 'changeYourTemporaryPassword', 'Usted ha recuperado su contraseña recientemente. Por favor actualizela #HERE', 'You have recently recovered your password. Please update it #HERE', 'Vous avez récemment récupéré votre mot de passe. S\'il vous plaît mettre à jour #HERE', 'Usted ha recuperado su contraseña recientemente. Por favor actualizela #HERE'),
							(11, 'changePasswordError', 'La contraseña no ha sido cambiada, por favor intente nuevamente.', 'The password cannot be changed, please try again.', 'Le mot de pas n\'a pas été sauvegardé, veuillez essayer à nouveau.', 'La contraseña no ha sido cambiada, por favor intente nuevamente.'),
							(12, 'changePasswordSuccess', 'Su contraseña ha sido cambiada.', 'Your password has been changed.', 'Votre mot de passe a été changé.', 'Su contraseña ha sido cambiada.'),
							(13, 'classCSS', 'Clase CSS', 'CSS Class', 'Classe CSS', 'Clase CSS'),
							(14, 'createAccount', 'Crear cuenta', 'Sign up', 'Créez votre compte', 'Crear cuenta'),
							(15, 'december', 'Diciembre', 'December', 'Décembre', 'Dezembro'),
							(16, 'delete', 'Borrar', 'Delete', 'Supprimer', 'Borrar'),
							(17, 'deselectAll', 'Deseleccionar', 'Deselect All', 'Désélectionner tous', 'Deseleccionar'),
							(18, 'email', 'Email', 'Email', 'Email', 'Email'),
							(19, 'emptyList', 'La lista esta vacía', 'The list is empty', 'La liste est vide', 'La lista esta vacía'),
							(20, 'linkCode', 'Codigo para el enlace', 'Code for the link', 'Lien pour le code', 'Codigo para el enlace'),
							(21, 'internalLink', 'Enlace interno', 'Internal link', 'Lien interne', 'Enlace interno'),
							(22, 'externalLink', 'Enlace externo', 'External link', 'Lien externe', 'Enlace externo'),
							(23, 'externalLinkLabel', 'Enlace externo o etiqueta', 'External link or label', 'Lien externe ou libelle', 'Enlace externo o etiqueta'),
							(24, 'homePage', 'Inicio', 'Home', 'Accueil', 'Inicio'),
							(25, 'list', 'Lista', 'List', 'Liste', 'Lista'),
							(26, 'errorConnection', 'Si email y/o contraseña no son correctos. Intente nuevamente.', 'Your email and/or password are not correct. Please try again.', 'Votre e-mail et / ou mot de passe ne sont pas correctes. Veuillez réessayer.', 'Si email y/o contraseña no son correctos. Intente nuevamente.'),
							(27, 'errorMail', 'El email es incorrecto.', 'The email address is incorrect.', 'L\'adresse email est incorrecte.', 'El email es incorrecto.'),
							(28, 'errorPasswordAlpha', 'La contraseña debe tener solo caracteres alfanumericos.', 'The password must have only alphanumeric characters.', 'Le mot de passe ne doit comporter que des caractères alphanumériques.', 'La contraseña debe tener solo caracteres alfanumericos.'),
							(29, 'errorPasswordSize', 'La contraseña debe tener como minimo 6 caracteres.', 'The password must have at least 6 characters.', 'Le mot de passe doit comporter au moins 6 caractères.', 'La contraseña debe tener como minimo 6 caracteres.'),
							(30, 'errorsForm', 'Existen errores en el formulario.', 'There are errors in the form.', 'Il y a des erreurs dans le formulaire.', 'Existen errores en el formulario.'),
							(31, 'errorExisting', 'Este valor ya esta en uso.', 'This value is already in use.', 'Cette valeur est déjà en cours d\'utilisation.', 'Este valor ya esta en uso.'),
							(32, 'exit', 'Salir', 'Exit', 'Sortir', 'Salir'),
							(33, 'february', 'Febrero', 'February', 'Février', 'Fevereiro'),
							(34, 'home', 'Inicio', 'Home', 'Accueil', 'Início'),
							(35, 'idExists', 'El codigo ya existe', 'The code already exists', 'Le code existe déjà', 'El codigo ya existe'),
							(36, 'insertNew', 'Insertar nuevo', 'Insert new', 'Insérer', 'Insertar nuevo'),
							(37, 'january', 'Enero', 'January', 'Janvier', 'Janeiro'),
							(38, 'js_loading', 'Cargando...', 'Loading...', 'Chargement en cours...', 'Cargando...'),
							(39, 'js_messageDelete', 'Esta seguro de borrar este registro? También se borrarán los registros relacionados al mismo.', 'Are you sure about deleting this record? It will also delete the records related to it.', 'Êtes-vous sur de la suppression de cet enregistrement? Il va également supprimer les enregistrements qui lui sont liés.', 'Esta seguro de borrar este registro? También se borrarán los registros relacionados al mismo.'),
							(40, 'js_messageDeleteSimple', 'Esta seguro de borrar este registro?', 'Are you sure about deleting this record?', 'Êtes-vous sur de la suppression de cet enregistrement?', 'Esta seguro de borrar este registro?'),
							(41, 'js_notEmpty', 'Este campo no puede estar vacío', 'This field cannot be empty', 'Ce champ ne peut être vide', 'Este campo no puede estar vacío'),
							(42, 'july', 'Julio', 'July', 'Juillet', 'Julho'),
							(43, 'june', 'Junio', 'June', 'Juin', 'Junho'),
							(44, 'activateSelected', 'Activar seleccionados', 'Activate selected', 'Activer la sélection', 'Activar seleccionados'),
							(45, 'active', 'Activo', 'Active', 'Actif', 'Activo'),
							(46, 'categories', 'Categorias', 'Categories', 'Catégories', 'Categorias'),
							(47, 'category', 'Categoría', 'Category', 'Catégorie', 'Categoría'),
							(48, 'code', 'Código', 'Code', 'Code', 'Código'),
							(49, 'contact', 'Contacto', 'Contact', 'Contact', 'Contacto'),
							(50, 'contacts', 'Contactos', 'Contacts', 'Contacts', 'Contactos'),
							(51, 'date', 'Fecha', 'Date', 'Date', 'Fecha'),
							(52, 'deactivateSelected', 'Desactivar los seleccionados', 'Deactivate selected', 'Désactiver la sélection', 'Desactivar los seleccionados'),
							(53, 'deleteSelected', 'Eiminar los seleccionados', 'Delete selected items', 'Supprimer la sélection', 'Eiminar los seleccionados'),
							(54, 'description', 'Descripcion', 'Description', 'Description', 'Descripcion'),
							(55, 'email', 'Email', 'Email', 'Email', 'Email'),
							(56, 'file', 'Archivo', 'File', 'Fichier', 'Archivo'),
							(57, 'image', 'Imagen', 'Image', 'Image', 'Imagen'),
							(58, 'lang', 'Idioma', 'Language', 'Langue', 'Idioma'),
							(59, 'langs', 'Idiomas', 'Languages', 'Langues', 'Idiomas'),
							(60, 'lastName', 'Apellido', 'Last Name', 'Nom', 'Apellido'),
							(61, 'link', 'Enlace', 'Link', 'Lien', 'Enlace'),
							(62, 'links', 'Enlaces', 'Links', 'Liens', 'Enlaces'),
							(63, 'linkUrl', 'Enlace URL', 'Link URL', 'Lien URL', 'Enlace URL'),
							(64, 'menu', 'Menu', 'Menu', 'Menu', 'Menu'),
							(65, 'subMenu', 'Sub-menu', 'Sub-menu', 'Sous-menu', 'Sub-menu'),
							(66, 'message', 'Mensaje', 'Message', 'Message', 'Mensaje'),
							(67, 'metaDescription', 'Descripcion Meta', 'Meta description', 'Description meta', 'Descripción meta'),
							(68, 'metaKeywords', 'Palabra Clave Meta', 'Meta keywords', 'Mots-clés meta', 'Palabras clave meta'),
							(69, 'name', 'Nombre', 'Name', 'Nom', 'Nombre'),
							(70, 'namePerson', 'Nombre', 'Name', 'Prenom', 'Nombre'),
							(71, 'notActive', 'Inactivo', 'Not Active', 'Inactif', 'Inactivo'),
							(72, 'notCanceled', 'Válido', 'Valid', 'Validée', 'Válido'),
							(73, 'page', 'Página', 'Page', 'Page', 'Página'),
							(74, 'pages', 'Páginas', 'Pages', 'Pages', 'Páginas'),
							(75, 'params', 'Parámetros', 'Site parameters', 'Paramètres du site', 'Parámetros'),
							(76, 'metainfo', 'Información meta', 'Meta information', 'Information meta', 'Información meta'),
							(77, 'misc', 'Miscelánea', 'Miscellany', 'Miscellanées', 'Miscelánea'),
							(78, 'linksocial', 'Enlaces a las redes sociales', 'Links to social networks', 'Liens aux réseaux sociales', 'Enlaces a las redes sociales'),
							(79, 'password', 'Contraseña', 'Password', 'Mot de passe', 'Contraseña'),
							(80, 'pictures', 'Imágenes', 'Pictures', 'Photos', 'Imágenes'),
							(81, 'section', 'Sección', 'Section', 'Section', 'Sección'),
							(82, 'sections', 'Secciones', 'Sections', 'Sections', 'Secciones'),
							(83, 'htmlSections', 'Secciones HTML', 'HTML Sections', 'Sections HTML', 'Secciones HTML'),
							(84, 'htmlSectionsAdmin', 'Secciones Admin HTML', 'HTML Admin Sections', 'Sections HTML Admin', 'Secciones Admin HTML'),
							(85, 'tags', 'Tags', 'Tags', 'Tags', 'Tags'),
							(86, 'telephone', 'Teléfono', 'Telephone', 'Téléphone', 'Teléfono'),
							(87, 'address', 'Dirección', 'Address', 'Adresse', 'Dirección'),
							(88, 'title', 'Título', 'Title', 'Libellé', 'Título'),
							(89, 'translation', 'Traducción', 'Translation', 'Traduction', 'Traducción'),
							(90, 'translations', 'Traducciones', 'Translations', 'Traductions', 'Traducciones'),
							(91, 'type', 'Tipo', 'Type', 'Type', 'Tipo'),
							(92, 'user', 'Usuario', 'User', 'Utilisateur', 'Usuario'),
							(93, 'users', 'Usuarios', 'Users', 'Utilisateurs', 'Usuarios'),
							(94, 'userType', 'Tipo de usuario', 'User Type', 'Type d\'utilisateur', 'Tipo de usuario'),
							(95, 'userTypes', 'Tipos de usuario', 'User Types', 'Type d\'utilisateurs', 'Tipos de usuario'),
							(96, 'loggedAs', 'Conectado como', 'Logged as', 'Connecté en tant que', 'Conectado como'),
							(97, 'login', 'Conectarse', 'Login', 'Identification', 'Conectarse'),
							(98, 'loginMessage', 'Por favor, ingrese su email y contraseña.', 'Please enter your email and password.', 'S\'il vous plaît, tappez votre email et votre mot de passe.', 'Por favor, ingrese su email y contraseña.'),
							(99, 'logout', 'Salir', 'Logout', 'Déconnexion', 'Salir'),
							(100, 'mailDoesntExist', 'El email no existe en nuestra base de datos.', 'The email doesn\'t exist in our database.', 'L\' email n\'existe pas dans notre base de données.', 'El email no existe en nuestra base de datos.'),
							(101, 'march', 'Marzo', 'March', 'Mars', 'Março'),
							(102, 'may', 'Mayo', 'May', 'Mai', 'Maio'),
							(103, 'modify', 'Modificar', 'Modify', 'Modifier', 'Modificar'),
							(104, 'move', 'Mover', 'Move', 'Déplacer', 'Mover'),
							(105, 'information', 'Informacion', 'Information', 'Information', 'Informacion'),
							(106, 'myAccountMessage', 'Use este formulario para modificar sus datos.', 'Use this form to update your information.', 'Utilisez ce formulaire pour mettre à jour vos informations.', 'Use este formulario para modificar sus datos.'),
							(107, 'changePasswordMessage', 'Use este formulario para modificar su contraseña.', 'Use this form to change your password.', 'Utilisez ce formulaire pour mettre à jour votre mot de passe.', 'Use este formulario para modificar su contraseña.'),
							(108, 'newPassword', 'Nueva contraseña', 'New password', 'Nouveau mot de passe', 'Nueva contraseña'),
							(109, 'next', 'Siguiente', 'Next', 'Suivant', 'Siguiente'),
							(110, 'item', 'Item', 'Item', 'Item', 'Item'),
							(111, 'items', 'Items', 'Items', 'Items', 'Items'),
							(112, 'listItems', 'Lista de elementos', 'List items', 'Liste des éléments', 'Lista de elementos'),
							(113, 'noItems', 'No hay elementos en esta lista.', 'There are no items in this list.', 'Il n\'y a aucun élément dans ce liste.', 'No hay elementos en esta lista.'),
							(114, 'noItemsSearch', 'No hay resultados para su búsqueda.', 'There are no items matching your search.', 'Il n\'y a aucun élément correspondant à votre recherche.', 'No hay resultados para su búsqueda.'),
							(115, 'notEmpty', 'Este campo no puede estar vacío', 'This field cannot be empty', 'Ce champ ne peut être vide', 'Este campo no puede estar vacío'),
							(116, 'mainPage', 'Pagina principal', 'Main page', 'Page principale', 'Pagina principal'),
							(117, 'november', 'Noviembre', 'November', 'Novembre', 'Novembro'),
							(118, 'october', 'Octubre', 'October', 'Octobre', 'Outubro'),
							(119, 'oldPassword', 'Contraseña antigua o temporal', 'Old or temporary password', 'Temporarie ou ancien mot de passe', 'Contraseña antigua o temporal'),
							(120, 'oldPasswordError', 'Su contraseña antigua no es correcta.', 'Your old password is incorrect.', 'Votre ancien mot de passe est incorrect.', 'Su contraseña antigua no es correcta.'),
							(121, 'password', 'Contraseña', 'Password', 'Mot de passe', 'Contraseña'),
							(122, 'passwordConfirmation', 'Confirmación de su contraseña', 'Password confirmation', 'Confirmation de mot de passe', 'Confirmación de su contraseña'),
							(123, 'passwordConfirmationError', 'Las contraseñas no coinciden', 'The passwords don\'t match', 'Les mots de passe ne correspondent pas', 'Las contraseñas no coinciden'),
							(124, 'passwordForgot', 'Olvidé mi contraseña', 'I forgot my password', 'J\'ai oublié mon mot de passe', 'Olvidé mi contraseña'),
							(125, 'passwordForgotMessage', 'Por favor, ingrese su email y le enviaremos una contraseña temporal.', 'Please, enter your email address and we will send you a temporary password.', 'S\'il vous plaît , entrez votre adresse email et nous vous enverrons un mot de passe temporaire.', 'Por favor, ingrese su email y le enviaremos una contraseña temporal.'),
							(126, 'passwordSentMail', 'Hemos enviado un email con su contraseña temporal.', 'We have sent an email with your temporary password.', 'Nous avons envoyé un e-mail avec votre mot de passe temporaire.', 'Hemos enviado un email con su contraseña temporal.'),
							(127, 'pending', 'Pendiente', 'Pending', 'Attente', 'Pendiente'),
							(128, 'permissions', 'Permisos', 'Permissions', 'Permissions', 'Permisos'),
							(129, 'managesPermissions', 'Puede administrar permisos', 'Can manage permissions', 'Peut gérer les autorisations', 'Puede administrar permisos'),
							(130, 'permissionsError', 'No tiene permisos para ver esta página.', 'You don\'t have the permissions to visit this page.', 'Vous n\'avez pas les permissions pour visiter cette page.', 'No tiene permisos para ver esta página.'),
							(131, 'permissionListAdmin', 'Permiso para listar elementos.', 'Permission to list items.', 'Autorisation pour lister les éléments.', 'Permiso para listar elementos.'),
							(132, 'permissionInsert', 'Permiso para insertar elementos.', 'Permission to insert items.', 'Autorisation pour insérer des éléments.', 'Permiso para insertar elementos.'),
							(133, 'permissionModify', 'Permiso para modificar elementos.', 'Permission to modify items.', 'Autorisation pour modifier des éléments.', 'Permiso para modificar elementos.'),
							(134, 'permissionDelete', 'Permiso para eliminar items.', 'Permission to delete items.', 'Autorisation pour supprimer les éléments.', 'Permiso para eliminar items.'),
							(135, 'print', 'Imprimir', 'Print', 'Imprimer', 'Imprimir'),
							(136, 'reservedRights', 'Derechos reservados', 'All rights reserved', 'Tous droits réservés', 'Derechos reservados'),
							(137, 'resultsFor', 'Resultados para', 'Results for', 'Résultats pour', 'Resultados para'),
							(138, 'save', 'Guardar', 'Save', 'Enregistrer', 'Guardar'),
							(139, 'saveCheck', 'Guardar y revisar', 'Save and check', 'Enregistrer et vérifiez', 'Guardar y revisar'),
							(140, 'savedForm', 'Este registro ha sido guardado, por favor revise si todo está en orden.', 'The record has been saved, please check if everything is right.', 'L\'enregistrement a été sauvegardé, veuillez vérifier si tout est correct.', 'Este registro ha sido guardado, por favor revise si todo está en orden.'),
							(141, 'search', 'Buscar', 'Search', 'Rechercher', 'Buscar'),
							(142, 'selectAll', 'Seleccionar todos', 'Select All', 'Sélectionner tout', 'Seleccionar todos'),
							(143, 'send', 'Enviar', 'Send', 'Envoyer', 'Enviar'),
							(144, 'september', 'Septiembre', 'September', 'Septembre', 'Setembro'),
							(145, 'show', 'Mostrar', 'Show', 'Afficher', 'Mostrar'),
							(146, 'site_contact', 'Contacto', 'Contact', 'Contact', 'Contacto'),
							(147, 'site_links', 'Enlaces', 'Links', 'Liens', 'Enlaces'),
							(148, 'tryLoginAgain', 'Intente conectarse nuevamente.', 'Try to login again.', 'Ressayez de vous connecter.', 'Intente conectarse nuevamente.'),
							(149, 'userExists', 'El usuario ya existe', 'The user already exists', 'L\'utilisateur existe déjà', 'El usuario ya existe'),
							(150, 'view', 'Ver', 'Show', 'Voir', 'Ver'),
							(151, 'viewAllItems', 'Ver todos los registros', 'Show all items', 'Voir toutes les enregistrements', 'Ver todos los registros'),
							(152, 'viewFile', 'Ver el archivo', 'View the file', 'Voir le fichier', 'Ver el archivo'),
							(153, 'viewLess', 'Ver menos', 'View less', 'Moins d\'infos', 'Ver menos'),
							(154, 'viewList', 'Ver la lista', 'View the list', 'Voir la liste', 'Ver la lista'),
							(155, 'mailTemplates', 'Plantillas de los emails', 'Email templates', 'Gabarits des emails', 'Plantillas de los emails'),
							(156, 'template', 'Plantilla', 'Template', 'Gabarit', 'Plantilla'),
							(157, 'mails', 'Emails', 'Emails', 'Emails', 'Emails'),
							(158, 'mail', 'Email', 'Email', 'Email', 'Email'),
							(159, 'subject', 'Título del email', 'Subject', 'Sujet', 'Título del email'),
							(160, 'replyTo', 'Responder a', 'Reply to', 'Répondre à', 'Responder a'),
							(161, 'updatePassword', 'Recuperar su contraseña', 'Recover your password', 'Récuperer votre mot de passe', 'Recuperar su contraseña'),
							(162, 'passwordTempMessage', 'Por favor, ingrese su email y contraseña temporal', 'Please enter your email and your temporary password', 'SVP tappez votre email et mot de passe temporaire', 'Por favor, ingrese su email y contraseña temporal'),
							(163, 'updatePasswordError', 'Si email o contraseña no son correctos. Intente nuevamente.', 'Your email or password are not correct. Please try again.', 'Votre e-mail ou mot de passe ne sont pas correctes. Veuillez réessayer.', 'Si email o contraseña no son correctos. Intente nuevamente.'),
							(164, 'idsAvailable', 'IDs disponibles', 'IDs available', 'IDs disponibles', 'IDs disponibles'),
							(165, 'latitude', 'Latitud', 'Latitude', 'Latitude', 'Latitud'),
							(166, 'longitude', 'Longitud', 'Longitude', 'Longitude', 'Longitud'),
							(167, 'passwordNewMessage', 'Deje este campo vacío si no desea cambiar la contraseña', 'Leave empty if you don\'t want to change the current password', 'Laissez vide si vous ne voulez pas changer le mot de passe actuel', 'Deje este campo vacío si no desea cambiar la contraseña'),
							(168, 'information', 'Informacion', 'Information', 'Information', 'Informacion'),
							(169, 'action', 'Accion', 'Action', 'Action', 'Accion'),
							(170, 'shortDescription', 'Breve descripción', 'Short description', 'Description courte', 'Breve descripción'),
							(171, 'publishDate', 'Fecha de publicación', 'Publish date', 'Date de publication', 'Fecha de publicación'),
							(172, 'posts', 'Artículos', 'Posts', 'Articles', 'Artigos'),
							(173, 'no', 'No', 'No', 'No', 'No'),
							(174, 'yes', 'Si', 'Yes', 'Oui', 'Si'),
							(175, 'comments', 'Comentarios', 'Comment', 'Commentaire', 'Comentario'),
							(176, 'comments', 'Comentario', 'Comment', 'Commentaire', 'Comentario'),
							(177, 'web', 'Sitio web', 'Website', 'Site web', 'Sitio web'),
							(178, 'banners', 'Banners', 'Banners', 'Bannières', 'Banners'),
							(179, 'content', 'Contenido', 'Content', 'Contenu', 'Contenido'),
							(180, 'viewMore', 'Ver más', 'See more', 'Voir', 'Ver más'),
							(181, 'downloadFile', 'Descargar el archivo', 'Download file', 'Télécharger le fichier', 'Descargar el archivo'),
							(182, 'myAccount', 'Mi cuenta', 'My account', 'Mon compte', 'Mi cuenta'),
							(183, 'listTotal', '#RESULTS resultados en total.', '#RESULTS items in total.', '#RESULTS résultats en total.', '#RESULTS resultados en total.'),
							(184, 'class', 'Clase', 'Class', 'Classe', 'Clase'),
							(185, 'language', 'Idioma', 'Language', 'Langage', 'Idioma'),
							(186, 'published', 'Publicado', 'Published', 'Publié', 'Publicado'),
							(187, 'reverse', 'Reverso', 'Reverse', 'Inverse', 'Reverso'),
							(188, 'orderBy', 'Ordenar por', 'Order by', 'Trier par', 'Ordenar por'),
							(189, 'value', 'Valor', 'Value', 'Valeur', 'Valor'),
							(190, 'titlePage', 'Título de la página', 'Page title', 'Titre de la page', 'Título de la página'),
							(191, 'metaDescription', 'Descripción meta', 'Meta description', 'Description meta', 'Descripción meta'),
							(192, 'metaKeywords', 'Palabras clave meta', 'Meta keywords', 'Mots-clés meta', 'Palabras clave meta'),
							(193, 'selectValue', '- Seleccione un valor -', '- Select a value -', '- Sélectionner une valeur -', '- Seleccione un valor -'),
							(194, 'selectValueLink', '- Seleccione un valor si desea tener un enlace -', '- Select a value if you want a link -', '- Sélectionner une valeur si vous voulez avoir un lien -', '- Seleccione un valor si desea tener un enlace -'),
							(195, 'latestPosts', 'Articulos recientes', 'Latests posts', 'Articles récents', 'Articulos recientes'),
							(196, 'popularPosts', 'Articulos populares', 'Popular posts', 'Articles populaires', 'Articulos populares'),
							(197, 'pageUrl', 'pagina', 'page', 'page', 'pagina'),
							(198, 'messageThanksContact', 'Gracias por su mensaje.', 'Thanks for your message.', 'Merci pour votre message.', 'Gracias por su mensaje.'),
							(199, 'thanks', 'Gracias', 'Thanks', 'Merci', 'Gracias'),
							(200, 'documentation', 'Documentación', 'Documentation', 'Documentation', 'Documentación'),
							(201, 'portfolio', 'Portafolio', 'Portfolio', 'Portfolio', 'Portafolio'),
							(202, 'about-us', 'Sobre nosotros', 'About us', 'À propos', 'Sobre nosotros'),
							(203, 'download', 'Descargar', 'Download', 'Télécharger', 'Descargar'),
							(204, 'tryDemo', 'Demostración', 'Try the demo', 'Essayer la démo', 'Demostración'),
							(205, 'viewGitHub', 'Ver el GitHub', 'View on GitHub', 'Voie le GitHub', 'Ver el GitHub'),
							(206, 'documentation', 'Documentación', 'Documentation', 'Documentation', 'Documentación'),
							(207, 'documentationCategories', 'Documentación - Categorias', 'Documentation - Categories', 'Documentation - Categories', 'Documentación - Categorias'),
							(208, 'export', 'Exportar', 'Export', 'Exporter', 'Exportar'),
							(209, 'exportJson', 'Exportar JSON', 'Export JSON', 'Exporter JSON', 'Exportar JSON'),
							(210, 'cache', 'Cache', 'Cache', 'Cache', 'Cache'),
							(211, 'backup', 'Backup', 'Backup', 'Backup', 'Backup'),
							(212, 'exportInformation', 'Exportar los datos de su sitio web', 'Export the data of your website', 'Exportez les données de votre site Web', 'Exportar los datos de su sitio web'),
							(213, 'sqlFormat', 'Formato SQL', 'SQL format', 'Format SQL', 'Formato SQL'),
							(214, 'jsonFormat', 'Formato JSON', 'JSON format', 'Format JSON', 'Formato JSON'),
							(215, 'sqlFormatInfo', 'Un archivo con toda la base de datos', 'A single file with the entire database', 'Un fichier unique avec toute la base de données', 'Un archivo con toda la base de datos'),
							(216, 'jsonFormatInfo', 'Un archivo JSON por cada objeto', 'One JSON file for each object', 'Un fichier JSON pour chaque objet', 'Un archivo JSON por cada objeto'),
							(217, 'availableObjects', 'Objetos disponibles', 'Available objects', 'Objets disponibles', 'Objetos disponibles'),
							(218, 'results', 'resultados', 'results', 'résultats', 'resultados'),
							(219, 'resetObject', 'Reinicializar el objeto', 'Reset the object', 'Réinitialiser l\'objet', 'Reinicializar el objeto'),
							(220, 'resetObjects', 'Reinicializar los objetos', 'Reset the objects', 'Réinitialiser les objets', 'Reinicializar los objetos'),
							(221, 'reloadObject', 'Recargar el objeto', 'Reload the object', 'Recharger l\'objet', 'Recargar el objeto'),
							(222, 'js_resetObjectMessage', 'Reinicializar o recargar el objeto eliminará los datos que ha guardado y cargará los valores predeterminados. No hay manera de deshacer esta acción.', 'Resetting or reloading the object will delete the data you have saved and load the default values. There is no way to undo this action.', 'La réinitialisation o recharge de l\'objet supprime les données que vous avez enregistrées et charge les valeurs par défaut. Il n\'est pas possible d\'annuler cette action.', 'Reinicializar o recargar el objeto eliminará los datos que ha guardado y cargará los valores predeterminados. No hay manera de deshacer esta acción.'),
							(223, 'reset', 'Reinicializar', 'Reset', 'Réinitialisation', 'Reinicializar'),
							(224, 'directoryNotWritable', 'El directorio #DIRECTORY no se puede escribir', 'The directory #DIRECTORY is not writable', 'Le répertoire #DIRECTORY n\'est pas accessible en écriture', 'El directorio #DIRECTORY no se puede escribir'),
							(225, 'backupJson', 'Backup JSON', 'Backup JSON', 'Backup JSON', 'Backup JSON'),
							(226, 'backupSql', 'Backup SQL', 'Backup SQL', 'Backup SQL', 'Backup SQL'),
							(227, 'staticMethod', 'Método estático', 'Static method', 'Méthode statique', 'Método estático'),
							(228, 'objectsToCache', 'Métodos de los objetos para almacenar en la caché', 'Methods of the objects to store in the cache', 'Méthodes des objets à stocker dans le cache', 'Métodos de los objetos para almacenar en la caché'),
							(229, 'objectsToCacheInfo', 'Para almacenar objetos en caché hay que añadir la anotación @cache en los métodos de las clases Ui.', 'To cache objects you have to add the @cache annotation in the methods of the Ui classes.', 'Pour mettre en cache des objets, vous devez ajouter l\'annotation @cache dans les méthodes des classes Ui.', 'Para almacenar objetos en caché hay que añadir la anotación @cache en los métodos de las clases Ui.'),
							(230, 'noObjectsToCache', 'No hay objetos para almacenar en la memoria caché. Utilice la anotación @cache en las clases Ui.', 'There are no objects to store in the cache. Use the @cache annotation in the Ui classes.', 'Il n\'y a aucun objet à stocker dans le cache. Utilisez l\'annotation @cache dans les classes Ui.', 'No hay objetos para almacenar en la memoria caché. Utilice la anotación @cache en las clases Ui.'),
							(231, 'objectCached', 'Los métodos del objeto se almacenaron correctamente en caché.', 'The methods of the object are successfully cached.', 'Les méthodes de l\'objet ont été mises en cache avec succès.', 'Los métodos del objeto se almacenaron correctamente en caché.'),
							(232, 'objectsCached', 'Los métodos de los objectos se almacenaron correctamente en caché.', 'The methods of the objects are successfully cached.', 'Les méthodes des objets ont été mises en cache avec succès.', 'Los métodos de los objectos se almacenaron correctamente en caché.'),
							(233, 'cacheAll', 'Guardar todos los objetos', 'Cache all objects', 'Enregister tous les objets', 'Guardar todos los objetos'),
							(234, 'brands', 'Marcas', 'Brands', 'Marques', 'Marcas'),
							(235, 'readMore', 'Leer más', 'Read more', 'Plus d\'infos', 'Leer más'),
							(236, 'thanksMessage', 'Gracias por el mensaje.', 'Thanks for the message.', 'Merci pour le message.', 'Gracias por el mensaje.'),
							(237, 'examples', 'Ejemplos', 'Examples', 'Exemples', 'Ejemplos'),
							(238, 'github', 'GitHub', 'GitHub', 'GitHub', 'GitHub'),
							(239, 'recipes', 'Recetas', 'Recipes', 'Recettes', 'Receitas'),
							(240, 'ingredients', 'Ingredientes', 'Ingredients', 'Ingredients', 'Ingredientes'),
							(241, 'ingredient', 'Ingrediente', 'Ingredient', 'Ingredient', 'Ingrediente'),
							(242, 'rating', 'Puntuación', 'Rating', 'Rating', 'Puntuación'),
							(243, 'numPersons', 'Número de personas', 'Number of people', 'Nombre des personnes', 'Número de personas'),
							(244, 'preparation', 'Preparación', 'Preparation', 'Preparation', 'Preparação'),
							(245, 'recipe', 'Receta', 'Recipe', 'Recette', 'Receita'),
							(246, 'recipesList', 'Lista de recetas', 'Recipes list', 'Liste des recettes', 'Lista de receitas'),
							(247, 'postsList', 'Lista de artículos', 'Articles list', 'Liste des articles', 'Lista de artigos'),
							(248, 'otherSitesCountry', 'Otros sitios de cocina por países', 'Other cooking sites by countries', 'Autres sites de cuisine par pays', 'Outros sites de culinária por países'),
							(249, 'otherSitesType', 'Otros sitios de cocina por tipos', 'Other cooking sites by types', 'Autres sites de cuisine par types', 'Outros sites de culinária por tipos'),
							(250, 'moreInformationWrite', 'Para mayor información escríbenos a', 'For more information write to us at', 'Pour plus d\'informations écrivez-nous à', 'Para mais informações, escreva-nos para'),
							(251, 'shareTitlePost', 'Ayúdanos compartiendo este artículo o dejando tu comentario.', 'Help us by sharing this article or leaving your comment.', 'Aidez-nous en partageant cet article ou en laissant votre commentaire.', 'Ajude-nos compartilhando este artigo ou deixando seu comentário.'),
							(252, 'relatedPostsTitle', 'Otras noticias que pueden interesarte', 'Other news that may interest you', 'Autres nouvelles susceptibles de vous intéresser', 'Outras notícias que podem lhe interessar'),
							(253, 'interestingArticles', 'Algunos artículos que podrían interesarte', 'Some posts that might interest you', 'Quelques articles qui pourraient vous intéresser', 'Alguns artigos que podem lhe interessar'),
							(254, 'shareTitleRecipe', 'Ayúdanos compartiendo esta receta o dejando tu comentario.', 'Help us by sharing this recipe or leaving your comment.', 'Aidez-nous en partageant cette recette ou en laissant votre commentaire.', 'Ajude-nos compartilhando esta receita ou deixando seu comentário.'),
							(255, 'viewAllRecipes', 'Ver todas las recetas', 'See all the recipes', 'Voir toutes les recettes', 'Veja todas as receitas'),
							(256, 'viewAllPosts', 'Ver todas las noticias', 'See all the news', 'Voir toutes les nouvelles', 'Veja todas as notícias'),
							(257, 'relatedNews', 'Algunas noticias relacionadas con', 'Some news related to', 'Quelques nouvelles liées à', 'Algumas notícias relacionadas a'),
							(258, 'interestingRecipes', 'Algunas recetas que podrían interesarte', 'Some recipes that might interest you', 'Quelques recettes qui pourraient vous intéresser', 'Algumas receitas que podem lhe interessar'),
							(259, 'moreRecipes', 'También le pueden interesar estas recetas', 'You may also be interested in these recipes', 'Vous pouvez également être intéressé par ces recettes', 'Você também pode estar interessado nessas receitas'),
							(260, 'searchResults', 'Resultados de la búsqueda', 'Search results', 'Résultats de la recherche', 'Resultados da pesquisa'),
							(261, 'noSearchResults', 'Lo sentimos pero no encontramos resultados para su búsqueda.', 'We are sorry but we did not find results for your search.', 'Nous sommes désolés, mais nous n\'avons pas trouvé de résultats pour votre recherche.', 'Lamentamos, mas não encontramos resultados para sua pesquisa.');
							ALTER TABLE `rec_LangTrans`
							  ADD PRIMARY KEY (`idLangTrans`);
							ALTER TABLE `rec_LangTrans`
							  MODIFY `idLangTrans` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=262;
							COMMIT;");

				// Add the active checkbox
				// Db::execute('ALTER TABLE `'.Db::prefixTable('Recipe').'` ADD `active` INT NULL;');
				// Db::execute('UPDATE `'.Db::prefixTable('Recipe').'` SET `active`=1;');
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
                    if (Image_File::saveImageData($this->values['image_base64'], 'Recipe', $fileSave)) {
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

            case 'check-github-now-all':
            	$this->mode = 'ajax';
            	$this->checkAuthorization();
                shell_exec('wget --header="Authorization: plastic" -qO- https://www.cocina-boliviana.com/check-github-now &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.recetas-argentinas.com/check-github-now &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.cocina-brasilena.com/check-github-now &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.cocina-chilena.com/check-github-now &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.cocina-colombiana.com/check-github-now &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.cocina-cubana.com/check-github-now &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.cocina-ecuatoriana.com/check-github-now &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.la-cocina-mexicana.com/check-github-now &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.comida-peruana.com/check-github-now &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.cocina-uruguaya.com/check-github-now &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- http://www.recetaspanama.com/check-github-now &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.recetashonduras.com/check-github-now &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.recetascostarica.com/check-github-now &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.recetas-guatemala.com/check-github-now &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.recetaspizzas.com/check-github-now &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.recetas-nicaragua.com/check-github-now &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.recetassalvador.com/check-github-now &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.receta-vegetariana.com/check-github-now &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.recetas-veganas.com/check-github-now &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.recetas-espana.com/check-github-now &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.recetas-italia.com/check-github-now &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.receitas-brasil.com/check-github-now &> /dev/null');
                return 'DONE';
            break;
            case 'fix-all':
            	$this->mode = 'ajax';
            	$this->checkAuthorization();
                shell_exec('wget --header="Authorization: plastic" -qO- https://www.cocina-boliviana.com/fix &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.recetas-argentinas.com/fix &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.cocina-brasilena.com/fix &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.cocina-chilena.com/fix &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.cocina-colombiana.com/fix &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.cocina-cubana.com/fix &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.cocina-ecuatoriana.com/fix &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.la-cocina-mexicana.com/fix &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.comida-peruana.com/fix &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.cocina-uruguaya.com/fix &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- http://www.recetaspanama.com/fix &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.recetashonduras.com/fix &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.recetascostarica.com/fix &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.recetas-guatemala.com/fix &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.recetaspizzas.com/fix &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.recetas-nicaragua.com/fix &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.recetassalvador.com/fix &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.receta-vegetariana.com/fix &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.recetas-veganas.com/fix &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.recetas-espana.com/fix &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.recetas-italia.com/fix &> /dev/null');
				shell_exec('wget --header="Authorization: plastic" -qO- https://www.receitas-brasil.com/fix &> /dev/null');
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