<?php 
/**
* @version		1.0.0
* @package		Tux Joomla Complement
* @copyright	Copyright (C) 2011 Miguel Tuyaré - Tux Merlín. All rights reserved.
* @license		GNU/GPL 3.0
* Tux Joomla Complement is free software. 
* This version may have been modified pursuant to the GNU General Public License, 
* and as distributed it includes or is derivative of works licensed under 
* the GNU General Public License or other free or open source software licenses.
*/
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldTuxElement extends JFormField
{
	/**
	 * Element name	
	 * @access	protected
	 * @var		string
	 */
	protected $type 	= 'Tuxelement';	
	
	/**
	 * Clear space label 
	 */
	public function getLabel() {
	// Initialize variables.
	$label = '';
	$replace = '';
 
	// Get the label text from the XML element, defaulting to the element name.
	$text = $this->element['label'] ? (string) $this->element['label'] : (string) $this->element['name'];
 
	// Build the class for the label.
	$class = !empty($this->description) ? 'hasTip' : '';
	$class = $this->required == true ? $class.' required' : $class;
 
	// Add replace checkbox
	$replace = '<input type="checkbox" name="update['.$this->name.']" value="1" />';
 
	// Add the opening label tag and main attributes attributes.
	$label .= '<label id="'.$this->id.'-lbl" for="'.$this->id.'" class="'.$class.'"';
 
	// If a description is specified, use it to build a tooltip.
	if (!empty($this->description)) {
		$label .= ' title="'.htmlspecialchars(trim(JText::_($text), ':').'::' .
				JText::_($this->description), ENT_COMPAT, 'UTF-8').'"';
	}
 
	// Add the label text and closing tag.
	$label .= '>'.$replace.JText::_($text).'</label>';
 
	return $label; 
}

	public function getInput() {
		
		return $this->element;
	}	
}
?>