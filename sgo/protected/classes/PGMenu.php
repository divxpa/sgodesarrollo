<?php

/**
 * PGMenu
 *
 * @package
 * @author Martin
 * @copyright Copyright (c) 2010
 * @version $Id$
 * @access public
 */
/**
 * PGMenu
 * Este control permite generar un menu multinivel dinámico mediante una simple inserción en un archivo .page . Se alimenta de un datasource que
 * debe respetar ciertas características; y su renderizado depende de una plantilla css definida especificamente para este control.
 * @package
 * @author Martin
 * @copyright Copyright (c) 2010
 * @version $Id$
 * @access public
 */
class PGMenu extends TWebControl{
	/**
	 * PGMenu::setDataSource()
	 * Define el origen de datos del cual se alimentara el menú. Este origen de datos es un array que debe respetar ciertas características.
	 * La forma de este array debe ser la siguiente
	 * $datasource = Array(
	 * 					 0 => Array(
	 *								 "IDMENU" => 1,
	 *								 "IDPAGINA" => 1,
	 *								 "NOMBRE" => "Inicio",
	 *								 "PAGINA" => "Home",
	 *								 "TITULO" => "Inicio",
	 *								 "IDCONTENEDOR" =>"",
	 *								 "CONTENIDOS" => 0
	 * 								 "TARGET" => "_blank"),
	 *					 1 => Array(
	 *								 "IDMENU" => 2,
	 *								 "IDPAGINA" =>"",
	 *								 "NOMBRE" => "Administración",
	 *								 "PAGINA" =>"",
	 *								 "TITULO" =>"",
	 *								 "IDCONTENEDOR" =>"",
	 *								 "CONTENIDOS" => "18",
	 * 								 "TARGET" => "_self")
	 *					[..]
	 *
	 * Aclaraciones
	 * Donde IDMENU es el id que identifica a cada elemento del menu (Obligatorio)
	 * IDPAGINA es el id de la página a la que apunta ese elemento del menu (No Obligatorio)
	 * NOMBRE es el nombre con el cual se debe renderizar el elemento en pantalla (Obligatorio)
	 * PAGINA es el la ubicación de la página a la cual debe linkear el elemento de menú (No Obligatorio)
	 * TITULO es el nombre que se le asignara al enlace al cual apuntara el elemento de menú (Obligatorio)
	 * IDCONTENEDOR indica el id de menu que contiene al elemento actual (Obligatorio)
	 * CONTENIDOS indica la cantidad de elementos hijos inmediatos que tiene el elemento actual (Obligatorio)
	 *
	 * @param mixed $datasource
	 * @return
	 */
	public function setDataSource($datasource){
		$this->setViewState("DataSource",$datasource);
	}
	public function getDataSource(){
		return $this->getViewState("DataSource",array());
	}
	public function OnLoad($param){
		parent::onLoad($param);
		$this->RenderMenu();
	}

	/**
	 * PGMenu::RenderMenu()
	 *
	 * @return
	 */
	public function RenderMenu(){
		//empiezo a formar el menu
		$primero = true;
		$menu = new TActivePanel();
		$menu->setCssClass("nav-wrapper");

		if($this->getShowSubNav()){
			$navleft = new TActivePanel();
			$navleft->setCssClass("nav-left");
			$menu->getControls()->add($navleft);
			$nav = new TActivePanel();
			$nav->setCssClass("nav");
			$nav->getControls()->add('<ul id="navigation">');
		}
		else{
			$navleft = new TActivePanel();
			$navleft->setCssClass("nav-left");
			$navleft->setHeight("50px");
			$menu->getControls()->add($navleft);
			$nav = new TActivePanel();
			$nav->setCssClass("nav");
			$nav->getControls()->add('<ul id="navigation">');
		}

		$finder = PaginaRecord::finder();
		$pagina = $finder->findBy_Pagina($this->Page->PagePath);
		$activeIdMenu=0;//$activeIdMenu = $pagina->IdMenuActivo;
		$data = $this->getDataSource();

		foreach($data as $m){
			//me fijo si es contenedor de otros menues
			if($m["CONTENEDOR"]==""){
				//si no tiene página es contenedor, sino es un menu unico
				if($m["PAGINA"]==""){
					//como es un contenedor busco todos sus menues hijos
					$primerhijo = true;
					$hijos = 0;

					foreach($data as $d){
						//si el idmenu es igual al idcontenedor es que $d es hijo de $m
						//contenidos indica la cantidad de hijos que contiene
						if($m["IDMENU"]==$d["IDCONTENEDOR"] && $m["CONTENIDOS"] > 0){
							$hijos++;
							//si es el primer menu hijo antes armo el html del contenedor
							if($primerhijo){

								if($m["IDMENU"]!=$activeIdMenu){
									$nav->getControls()->add('<li>');
								}
								else{
									$nav->getControls()->add('<li class="active">');
								}

								if($primero){
									$primero = false;
								}

								$nav->getControls()->add('<a href="#">');
								$nav->getControls()->add('<span class="menu-left"></span>');
								$nav->getControls()->add('<span class="menu-mid">' . $m["NOMBRE"] . '</span>');
								$nav->getControls()->add('<span class="menu-right"></span>');
								$nav->getControls()->add('</a><div class="sub"><ul>');
								/*
								$menu .= '<a href="#">';
								$menu .= '<span class="menu-left"></span>';
								$menu .= '<span class="menu-mid">' . $m["NOMBRE"] . '</span>';
								$menu .= '<span class="menu-right"></span>';
								$menu .= '</a><div class="sub"><ul>';
								   */
								$primerhijo = false;
							}
							$primersubmenu = true;
							$submenuclose = false;
							$submenuhijoclose = false;

							foreach($data as $s){
								if($s["IDCONTENEDOR"]==$d["IDMENU"]){
									if($primersubmenu){
										$nav->getControls()->add('<li><a href="#">' . $d["NOMBRE"] . '</a><div class="submenu"><ul>');
										//$menu .= '<li><a href="#">' . $d["NOMBRE"] . '</a><div class="submenu"><ul>';
										$primersubmenu = false;
									}
									$nav->getControls()->add('<li><a target="'.$s["TARGET"].'" href="?page=' . $s["PAGINA"] . '">' . $s["NOMBRE"] . '</a></li>');
									//$menu .= '<li><a href="?page=' . $s["PAGINA"] . '">' . $s["NOMBRE"] . '</a></li>';
									$submenuhijoclose = true;
								}
							}
							if($submenuhijoclose){
								$nav->getControls()->add("</ul></div></li>");
								//$menu .= "</ul></div></li>";
							}
							if($submenuclose){
								$nav->getControls()->add("</ul><div class=\"btm-bg\"></div></div></li>");
								//$menu .= "</ul><div class=\"btm-bg\"></div></div></li>";
								//$menu .= "</ul></div></li>";
							}

							if($primersubmenu && ($d["CONTENIDOS"]>0 || $d["IDPAGINA"]!="")){
								$nav->getControls()->add('<li><a target="'.$d["TARGET"].'" href="?page=' . $d["PAGINA"] . '">' . $d["NOMBRE"] . '</a></li>');
								//$menu .= '<li><a href="?page=' . $d["PAGINA"] . '">' . $d["NOMBRE"] . '</a></li>';
							}
							$renderclose = true;
						}

					}
					if($hijos==0){
						$nav->getControls()->add('<li>');
						$nav->getControls()->add('<a href="#">');
						$nav->getControls()->add('<span class="menu-left"></span>');
						$nav->getControls()->add('<span class="menu-mid">' . $m["NOMBRE"] . '</span>');
						$nav->getControls()->add('<span class="menu-right"></span>');
						$nav->getControls()->add('</a></li>');
						/*
						$menu .= '<li>';
						$menu .= '<a href="#">';
						$menu .= '<span class="menu-left"></span>';
						$menu .= '<span class="menu-mid">' . $m["NOMBRE"] . '</span>';
						$menu .= '<span class="menu-right"></span>';
						$menu .= '</a></li>';
						   */
						$renderclose = false;
					}
					//si entró al forearch anterior hay que cerrar los tags
					if($renderclose){
						//$menu .= '</ul><div id="btm-bg"></div></div></li></ul>';
						$nav->getControls()->add('</ul><div class="btm-bg"></div></div></li>');
						//$menu .= '</ul><div class="btm-bg"></div></div></li>';
					}

				}
				//menu único
				else{
					if($primero){
						//$menu .= '<ul><li class="active">';
						$nav->getControls()->add('<li class="active">');
						//$menu .= '<li class="active">';
						$primero = false;
					}
					else{
						//$menu .= '<ul><li>';
						$nav->getControls()->add('<li>');
						//$menu .= '<li>';
					}
					$nav->getControls()->add('<a target="'.$m["TARGET"].'" href="?page=' . $m["PAGINA"] . '">');
					$nav->getControls()->add('<span class="menu-left"></span>');
					$nav->getControls()->add('<span class="menu-mid">' . $m["NOMBRE"] . '</span>');
					$nav->getControls()->add('<span class="menu-right"></span></a></li>');
					/*
					$menu .= '<a href="?page=' . $m["PAGINA"] . '">';
					$menu .= '<span class="menu-left"></span>';
					$menu .= '<span class="menu-mid">' . $m["NOMBRE"] . '</span>';
					//$menu .= '<span class="menu-right"></span></a></li></ul>';
					$menu .= '<span class="menu-right"></span></a></li>';
					   */
				}
			}
		}
		$nav->getControls()->add('</ul>');
		//$menu .= '</ul>';

		if($this->getShowSubNav()){
			//$menu.=$this->getSubNav();
			$subnav = $this->getSubNav();
			$nav->getControls()->add($subnav);
			$menu->getControls()->add($nav);
			$navright = new TActivePanel();
			$navright->setCssClass("nav-right");
			$menu->getControls()->add($navright);
			//$menu .='</div><div class="nav-right"></div></div>';
		}
		else{
			$menu->getControls()->add($nav);
			$navright = new TActivePanel();
			$navright->setCssClass("nav-right");
			//$navright->setHeight("50px");
			$menu->getControls()->add($navright);
			//$menu .='</div><div class="nav-right" style="height:50px;"></div></div>';
		}

		$this->getControls()->add($menu);
	}

	public function getSubNav(){
		$subnav = new TActivePanel();
		$subnav->setCssClass("subnav");

		//$subnav = '<div class="subnav">';
		$separate = false;
		if($this->getCity()!=""){
			$subnav->getControls()->add($this->getCity());
			//$subnav .= $this->getCity();
			$separate = true;
		}
		if($this->getShowDate()){
			if($separate){
				$subnav->getControls()->add(" - " . date("d/m/Y"));
				//$subnav.= " - " . date("d/m/Y");
			}
			else{
				$subnav->getControls()->add(date("d-m-Y"));
				//$subnav.= date("d-m-Y");
			}
			$separate = true;
		}
		if($this->getShowTime()){
			if($separate){
				$subnav->getControls()->add(" - " . date("H:i"));
				//$subnav.= " - " . date("H:i");
			}
			else{
				$subnav->getControls()->add(date("H:i"));
				//$subnav.= date("H:i");
			}
		}
		if($this->getShowWheater()){
			$weather = $this->getWeather();
			$subnav->getControls()->add($weather);
			//$subnav .= $this->getWeather();
		}
		if($this->getShowSearchFields()){
			$searchcontrols = $this->getSearchControls();
			$subnav->getControls()->add($searchcontrols);
		}

		//$subnav.='</div>';
		return $subnav;
	}

	public function getShowSubNav(){
		return $this->getViewState("ShowSubNav",false);
	}
	/**
	 * PGMenu::setShowSubNav()
	 * Esta propiedad determina si mostrar o no la sub barra debajo de los menues
	 * @param mixed $value
	 * @return
	 */
	public function setShowSubNav($value){
		if(strtolower($value)=="true"){
			$value = true;
		}
		else{
			if(strtolower($value)=="false"){
				$value = false;
			}
			else{
				$value = false;
			}
		}
		$this->setViewState("ShowSubNav",$value);
	}
	public function getCity(){
		return $this->getViewState("City","");
	}
	/**
	 * PGMenu::setCity()
	 * metodo para establecer el parametro city que sera mostrado en la subnav
	 * @param mixed $value
	 * @return
	 */
	public function setCity($value){
		$this->setViewState("City",$value);
	}

	/**
	 * PGMenu::setShowDate()
	 * establece si mostrar o no la fecha en la subnav
	 * @param mixed $value
	 * @return
	 */
	public function setShowDate($value){
		if(strtolower($value)=="true"){
			$this->setViewState("ShowDate",true);
		}
		else{
			$this->setViewState("ShowDate",false);
		}
	}
	public function getShowDate(){
		return $this->getViewState("ShowDate",false);
	}

	/**
	 * PGMenu::setShowTime()
	 * establece si mostrar o no la hora enla subnav
	 * @param mixed $value
	 * @return
	 */
	public function setShowTime($value){
		if(strtolower($value)=="true"){
			$this->setViewState("ShowTime",true);
		}
		else{
			$this->setViewState("ShowTime",false);
		}
	}
	public function getShowTime(){
		return $this->getViewState("ShowTime",false);
	}

	/**
	 * PGMenu::setShowWheater()
	 * Establece si mostrar o no la información del tiempo
	 * @param mixed $value
	 * @return
	 */
	public function setShowWheater($value){
		if(strtolower($value)=="true"){
			$this->setViewState("ShowWeather",true);
		}
		else{
			$this->setViewState("ShowWeather",false);
		}
	}
	public function getShowWheater(){
		return $this->getViewState("ShowWeather",false);
	}

	/**
	 * PGMenu::getWeather()
	 * retorno un <div> conteniendo la información del clima provista por la API de Google
	 * @return
	 */
	private function getWeather(){
		$city = $this->getCity();
		$urlrequest = "http://www.google.com/ig/api?hl=es&weather=$city";
		$xmlcontent = utf8_encode (file_get_contents($urlrequest,0));
		//echo "<pre>";print_r($xmlcontent);exit;
		$xml = new TXmlDocument('1.0','UTF-8');
		$xml->loadFromString($xmlcontent);

		$weathertag = $xml->getElementByTagName("weather");
		$currenttag = $weathertag->getElementByTagName("current_conditions");
		if(!is_null($currenttag)){
			$condicion  = $currenttag->getElementByTagName("condition")->getAttribute("data");
			$temperatura = $currenttag->getElementByTagName("temp_c")->getAttribute("data") . " °C";
			$viento = $currenttag->getElementByTagName("wind_condition")->getAttribute("data");
			$humedad = $currenttag->getElementByTagName("humidity")->getAttribute("data");
			$icono = "http://www.google.com" . $currenttag->getElementByTagName("icon")->getAttribute("data");

			$weatherdiv = new TActivePanel();
			$weatherdiv->setCssClass("weather");
			//$weatherdiv = '<div class="weather">';
			$weatherdiv->getControls()->add('<img src="' . $icono . '" class="iconoclima" title="' . $condicion . '" alt="' . $condicion . '">');
			//$weatherdiv .= '<img src="' . $icono . '" class="iconoclima" title="' . $condicion . '" alt="' . $condicion . '">';
			$weatherdiv->getControls()->add(" <strong>$temperatura</strong> $condicion $viento $humedad");
			//$weatherdiv.= " <strong>$temperatura</strong> $condicion $viento $humedad";
			//$weatherdiv .="</div>";
		}
		else{
			//$weatherdiv = "";
			$weatherdiv = new TActivePanel();
			$weatherdiv->setCssClass("weather");
		}
		return $weatherdiv;
	}

	/**
	 * PGMenu::setShowSearchFields()
	 * Indica si se deben mostrar los controles para realizar búsquedas
	 * @param mixed $value
	 * @return
	 */
	public function setShowSearchFields($value){
		if(strtolower($value)=="true"){
			$this->setViewState("ShowSearchFields",true);
		}
		else{
			$this->setViewState("ShowSearchFields",false);
		}
	}

	public function getShowSearchFields(){
		return $this->getViewState("ShowSearchFields",false);
	}

	/**
	 * PGMenu::getSearchControls()
	 *
	 * @return
	 */
	private function getSearchControls(){
		$searchcontrols = new TActivePanel();
		$searchcontrols->setCssClass("SearchControls");
		$optiona = new TActiveRadioButton();
		//esto se podria parametrizar mas adelante
		$optiona->setText("Neuquen.com.ar");
		$optiona->setValue(0);
		$optiona->setGroupName("Buscador");
		$searchcontrols->getControls()->add($optiona);
		$optionb = new TActiveRadioButton();
		$optionb->setText("Google");
		$optionb->setValue(1);
		$optionb->setGroupName("Buscador");
		$searchcontrols->getControls()->add($optionb);
		$searchfield = new TActiveTextBox();
		$searchfield->setCssClass("SearchField");
		$searchcontrols->getControls()->add($searchfield);
		return $searchcontrols;
	}

}
?>