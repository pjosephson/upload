<?php
/**
 * Media Helper File
 *
 * 2010 Nathan Tyler
 *
 * Distributed under the terms of the MIT License.
 * Redistributions of files must retain the above copyright notice.
 *
 * PHP version 5
 * CakePHP version 1.3
 *
 * @package    upload
 * @subpackage upload.views.helpers
 * @copyright  2010 Nathan Tyler
 * @license    http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link       http://github.com/tylerdigital/upload
 */

/**
 * Upload Helper Class
 *
 * To load the helper just include it in the helpers property
 * of a controller:
 * {{{
 *     var $helpers = array('Form', 'Html', 'Upload.Upload');
 * }}}
 *
 * @see __construct()
 * @link http://book.cakephp.org/view/99/Using-Helpers
 * @package    upload
 * @subpackage upload.views.helpers
 */
class UploadHelper extends AppHelper {
	var $helpers = array('Html', 'Form');
	function __construct($settings = array()) {

	}
	
	function input($field, $options = array()) {
		$name = $field.'_file';
		$defaults = array(
			'type' => 'file',
			'model' => $this->Form->model(),
		);
		$options = am($defaults, $options);
		extract($options);
		if(empty($label)) $label = $name;
		
		$response = $this->Form->input($name, $options);
		if(!empty($this->data[$model][$name])) {
			$response = $this->Form->label($label);
			$response .= $this->Html->tag('p', "Current file: ".$this->data[$model][$name]);
			$response .= $this->embed(
				array("$model.$field" => $this->data),
				$options
			);
			$response .= $this->Form->input(
				$name.'.remove',
				array(
					'type' => 'checkbox',
					'value' => 1,
				)
			);
		}
		$response .= $this->Form->input('dir', array('type' => 'hidden',));
		return $response;
	}
	
	function embed($keyedData, $options=array()) {
		$defaults = array(
			'filesUrl' => Configure::read('Upload.filesUrl'),
			'pathMethod' => 'primaryKey',
			'emptyDefault' => true,
		);
		$options = am($defaults, $options);
		extract($options);
		foreach ($keyedData as $modelDotField => $data) {}
		list($model, $field) = pluginSplit($modelDotField);
		
		if(strpos($data[$model][$field.'_type'], 'image')!==false) return $this->image($keyedData, $options);
		return false;
	}
	
	function link($title, $keyedData=NULL, $options=array(), $confirmMessage=false) {
		$defaults = array(
			'filesUrl' => Configure::read('Upload.filesUrl'),
			'pathMethod' => 'primaryKey',
		);
		$options = am($defaults, $options);
		$linkOptions = array_intersect_key($options, array(
			'target' => null,
			'rel' => null,
			'class' => null,
		));
		extract($options);

		$url = $this->url($keyedData, $options);		
		if(empty($url)) {
			return $this->Html->link($title, '', $linkOptions, $confirmMessage);
		}

		return $this->Html->link($title, $url, $linkOptions, $confirmMessage);
	}
	
	function image($keyedData, $options=array()) {
		$defaults = array(
			'filesUrl' => Configure::read('Upload.filesUrl'),
			'pathMethod' => 'primaryKey',
			'alt' => '',
		);
		$options = am($defaults, $options);
		$imageOptions = array_intersect_key($options, array(
			'width' => null,
			'height' => null,
			'alt' => null,
			'class' => null,
		));
		extract($options);

		$url = $this->url($keyedData, $options);		
		return $this->Html->image($url, $imageOptions);
	}
	
	function url($keyedData, $options=array()) {
		$defaults = array(
			'filesUrl' => Configure::read('Upload.filesUrl'),
			'pathMethod' => 'primaryKey',
			'emptyDefault' => true,
			'emptyDefaultExtension' => 'jpg',
		);
		$options = am($defaults, $options);
		extract($options);
		
		foreach ($keyedData as $modelDotField => $data) {}
		list($model, $field) = pluginSplit($modelDotField);
		
		if(isset($data[$model])) $data = $data[$model];
		if(empty($data[$field.'_file']) && empty($emptyDefault)) {
			return false;
		}
		
		$url = $options['filesUrl'];
		if($options['pathMethod']=='primaryKey') $url .= Inflector::underscore($model).DS.$field.'_file'.DS;
		
		if(!empty($data['dir']) && !empty($data[$field.'_file'])) $url .= $data['dir'].DS;
		else $url .= 'default'.DS;
		if(!empty($displayVariation)) $url .= $displayVariation.'_';
		if(!empty($data[$field.'_file'])) $url .= $data[$field.'_file'];
		else $url .= 'default.'.$emptyDefaultExtension;
		return $url;
	}
	
}

?>