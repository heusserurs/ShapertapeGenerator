<?php

	class Svg{
		protected $output;
		public $version = "1.1";
		public $namespace = "http://www.w3.org/2000/svg";
		public $xlink = "http://www.w3.org/1999/xlink";
		public $header = '<?xml version="1.0" standalone="no"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">';
		public $fileName; 
		
		// id of the SVG file:
		public $id;
		
		// viewBox coordinates: 
		public $vbX1 = 0, $vbY1 = 0, $vbX2, $vbY2;
		
		// permit instruction stack connector work: 
		public $instructionStackConnectorFlag = true;
		
		// the frequency of the javascript function which checks instruction stack:
		public $instructionStackConnectorInterval = 1000;
		
		// components of the SVG file, eg: basic shapes. 
		public $components;
		
		// scripts related with the file:
		public $scripts;
		
		// width and height of the SVG file:
		public $width, $height;
		
		// keyboard target element id. See keyboardListener() in svgdreams-canvas.js:
		public $keyboardTargetElementId = "";
		
		function __construct(){
			
		}
		
		private function getComponents(){
			$componentsRaw = '';
			
				if(sizeof($this->components)){
					foreach($this->components AS $key=>$component){
						$componentsRaw .= "\n".$component;
					}
				}
			
			return $componentsRaw;
		}
		
		public function addComponent($componentObj){
			$this->components[] = $componentObj->render();
		}
		
		public function renderScript(){
		
			$scriptData = '<script type="text/ecmascript" xlink:href="typo3conf/ext/uh_shaper/display/templates/SVGDreams/js/svgdreams-canvas.js" />';
			
			$scriptData .= "\n \n \t <script>";
			$scriptData .= "<![CDATA[";
			
			if($this->instructionStackConnectorFlag){
				$this->scripts[] = "\n \n \t setInterval('getInstruction();', ".$this->instructionStackConnectorInterval."); ";
			}	
			
			if($this->keyboardTargetElementId != ""){
				$this->scripts[] = "\n \t var keyboardTargetElementId = '".$this->keyboardTargetElementId."';";
			}
			
				if(is_array($this->scripts)){
					foreach($this->scripts AS $key=>$script){
						$scriptData .= $script;
					}
				}
				
				
			$scriptData .= " \n \t //]]></script> \n";
			
			return $scriptData;
		}
		
		
		public function render(){
		
			// if no id was set, give a md5 id
			if(!isset($this->id)){
				$this->id = md5(microtime());
			}
		
			// initialize viewBox:
			if($this->vbX2 == 0){$this->vbX2 = $this->width;}
			if($this->vbY2 == 0){$this->vbY2 = $this->height;}			
		
			$publish = $this->header.'<svg id="'.$this->id.'" viewBox="'.$this->vbX1.' '.$this->vbY1.' '.$this->vbX2.' '.$this->vbY2.'" width="'.$this->width.'" height="'.$this->height.'" version="'.$this->version.'" xmlns="'.$this->namespace.'" xmlns:xlink="'.$this->xlink.'">';
			$publish .= $this->getComponents(); 
			$publish .= $this->renderScript();	
				
			$publish .= "\n</svg>"; 
			
			
			return $publish; 
		}
		
		public function saveFile(){
			$svgContent = $this->render();
			$openHandler = fopen($this->fileName.".svg", "w+");
			fwrite($openHandler, $svgContent);
		}
		
		public function addJS($scriptOutput){
			$this->scripts[] = $scriptOutput;
		}
		
	}

?>