<?php

class ContentBlock extends PostBlock
{
	static $db = array(
		'Content' => 'HTMLText',
		'ImageAlign' => "enum('left,right')"
	);

	static $has_one = array(
		'Image' => 'Image'
	);

	public function getCMSFields()
	{
		$fields = parent::getCMSFields();

		$fields->addFieldToTab('Root.Main', new HTMLEditorField('Content', 'Content'));

		$fields->addFieldToTab('Root.Main', new DropdownField('ImageAlign', 'Image Align', $this->dbObject('ImageAlign')->enumValues()));
		$fields->addFieldToTab('Root.Main', $imageField = new UploadField('Image', 'Image'));
		$imageField->setFolderName('Uploads/content-block-images');

		return $fields;
	}

	/**
	 * get a preview of the content for the block 
	 * 
	 * @return String
	 */
	public function ContentPreview()
	{
		$base = strip_tags($this->Content);
		return substr($base, 0, 150);
	}
}