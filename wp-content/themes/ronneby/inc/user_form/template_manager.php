<?php

class Dfd_User_Form_template_manager {

	private $_files = array ();
	public $template_layout_folder = "/inc/user_form/templates_admin/";
	public $front_template_layout_folder = "/inc/user_form/templetes/";
	private $view_file = "/inc/user_form/view/view.php";
	public $template_img_folder = "/inc/user_form/templates_admin/img/";
	public $style_folder = "/inc/user_form/assets/css/";
	private $form_img_style_folder = "/inc/user_form/assets/images/style/";

	/**
	 *
	 * @var Dfd_User_Form_template_manager $_instance 
	 */
	private static $_instance = null;

	public static function instance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	function __construct() {
		$this->init();
	}

	public function init() {
		$this->findTemplateFiles();

//        print_r($this->_files);
//        echo "=" . $this->getTempleteByName("template-layout2");
	}

	public function getTemplateLayoutFolder() {
		if (is_admin()) {
			return locate_template($this->template_layout_folder);
		}
		return locate_template($this->front_template_layout_folder);
	}

	public function getViewFile() {
		return locate_template($this->view_file);
	}

	public function gettemplateImgFolder() {
		return get_template_directory_uri() . $this->template_img_folder;
	}

	public function getPresetStyleImgFolder() {
		return get_template_directory_uri() . $this->form_img_style_folder;
	}

	public function getStyleFolder() {
		
	}

	public function findTemplateFiles() {
		$this->_files = array ();
		if (is_dir($this->getTemplateLayoutFolder())) {
			if ($handle = opendir($this->getTemplateLayoutFolder())) {
				while (false !== ($entry = readdir($handle))) {
					if ($entry != "." && $entry != ".." && !is_dir($this->getTemplateLayoutFolder() . $entry)) {
						$file_name = basename($entry, ".php");
						$file_name = str_replace("-", "_", $file_name);
						$this->_files[$file_name] = $entry;
					}
				}
				closedir($handle);
			}
		}
	}

	public function getImgNameByTemplate($tempalteName) {
		$img_file = $this->gettemplateImgFolder() . $tempalteName;
		$folder = $this->template_img_folder;
		return $this->getImgfile($tempalteName,$img_file, $folder);
	}

	public function getPresetStyleImgByname($tempalteName) {
		$img_file = $this->getPresetStyleImgFolder() . $tempalteName;
		$folder = $this->form_img_style_folder;
		return $this->getImgfile($tempalteName, $img_file, $folder);
	}

	public function getImgfile($tempalteName, $img_file, $folder) {
		$aval_type = array (
			"jpeg",
			"png",
			"jpg",
			"gif",
		);
		foreach ($aval_type as $key => $type) {
			if (file_exists(locate_template($folder) . $tempalteName . "." . $type)) {
				return $img_file . "." . $type;
			}
		}
		return $tempalteName;
	}

	public function getPathTempleteByName($templateName) {
		if (key_exists($templateName, $this->_files)) {
			$file = $this->getTemplateLayoutFolder() . $templateName . ".php";
			if (is_file($file)) {
				return $file;
			}
			return false;
		}
		return false;
	}

	public function includeTemplatelayout($templateName) {
		$file = $this->getPathTempleteByName($templateName);
		if ($file) {
			Dfd_User_Input::instance()->generate($file);
		}
		return false;
	}

	public function getAllTempletes() {
		return $this->_files;
	}

}
