<?php
class Form{
	public $controller;

	public function __construct($controller){
		$this->controller = $controller;
	}

	public function input($name,$label,$options){
				$html = '<div class="form-group">
		  <label for="input'.$name.'">'.$label.'</label>';
		  $attr =' ';

		  foreach ($options as $k => $v) {
			  	if($k != "type"){
			  	$attr .= " $k=\"$v\"";
			  }
		  }
		  if($options['type'] == "text"){
		 $html .= '<input type="text" class="form-control" id="input'.$name.'" name="'.$name.'" "'.$attr.'">';	
		  }
		  elseif ($options['type'] == 'textarea') {
		  $html .= '<textarea   id="input'.$name.'" name="'.$name.'" "'.$attr.'"></textarea>';
		  }
		   elseif ($options['type'] == 'checkbox') {
		  $html .= '<input type="hidden" name = "'.$name.'" value="0">
		  <input type="checkbox" name = "'.$name.'" value="1"> ' ;
		  }



		$html .='</div>';
		return $html;
	}


 
}