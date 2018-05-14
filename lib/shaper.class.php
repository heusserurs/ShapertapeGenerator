<?php
require_once('lib/svg.class.php');
require_once('lib/rect.class.php');
require_once('lib/circle.class.php');


class shaper {
	public $shapertape;
	private $svg;
	private $count=0;
	private $width = 400;
	private $height = 277;
	
	private $dominoWidth = 42.995;
	private $dominoAbstandX = 3.019;
	private $dominoHeight = 12.672;
	private $dominoAbstandY = 10;
	
	public function render($paperWidth='400', $paperHeight='277', $dominoSpaceY='10') {
		$this->width = $paperWidth;
		$this->height = $paperHeight;
		$this->dominoAbstandY = $dominoSpaceY;
		
		$this->svg = new Svg();
		$this->svg->instructionStackConnectorFlag = false;
		$this->svg->width = $this->width.'mm';
		$this->svg->height = $this->height.'mm';
		
		$anzahlProZeile = (int)($this->width/($this->dominoWidth+$this->dominoAbstandX));
		$anzahlZeileProSeite = (int)($this->height/($this->dominoHeight+$this->dominoAbstandY));
		
		for($z=0; $z<$anzahlZeileProSeite; $z++) {
	 		for($i=0; $i<$anzahlProZeile; $i++) {
	 			$x = $i*($this->dominoWidth+$this->dominoAbstandX);
	 			$y = $z*($this->dominoHeight+$this->dominoAbstandY);
				$this->createDomino($x, $y);
	 		}
		}
		
		return $this->svg->render();
	}
	
	
	private function createDomino($x, $y) {
		$this->svg->addComponent($this->createRect($x, $y));
			
		$zahlen = array();
		for($i=0; $i<6; $i++) {
			do {
				$zahl = rand(2,15);
			} while ($zahl==8 || $zahl==9 || array_key_exists($zahl, $zahlen));
			$zahlen[$zahl] = $zahl;
		}
		
		$abstand = 2.519;
		for($i=1; $i<=16; $i++) {
			if($i<9) {
				$xCircle=($i*(2.54+$abstand))-$abstand/2;
				$yCircle='3.819';	// zeile 1
			} else {
				$xCircle=(($i-8)*(2.54+$abstand))-$abstand/2;
				$yCircle='8.869';	// zeile 2
			}
			if(array_search($i, $zahlen)===FALSE) {
				$this->svg->addComponent($this->createCircle($x+$xCircle, $y+$yCircle, "Domino".$this->count.'_Circle'.$i));
			}
		}
	}
	
	private function createRect($x, $y) {
		$this->count++;
		$rectObj = new Rect("Domino".$this->count);
		$rectObj->setCoordinates($x.'mm', $y.'mm');
		$rectObj->setSize($this->dominoWidth.'mm', $this->dominoHeight.'mm');
		$rectObj->fill = "#000000";
		$rectObj->rx = '2.54mm';
		$rectObj->ry = '2.54mm';
		
		return $rectObj;
	}
	
	private function createCircle($x, $y, $name='') {
		$circleObj = new Circle($name);
		$circleObj->radius = '1.27mm';
		$circleObj->centerX = $x.'mm';
		$circleObj->centerY = $y.'mm';
		$circleObj->fill = "#ffffff";
		//$circleObj->stroke = "black";
		return $circleObj;
	}
}
?>