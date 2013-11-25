<?php

namespace Craft;

class Lj_DynamicFields_DropdownFieldType extends BaseFieldType
{
	public function getName()
	{
		return Craft::t('Dropdown (dynamic)');
	}

	public function getInputHtml($name, $value)
	{
		$oldTemplatesPath = craft()->path->getTemplatesPath();
		craft()->path->setTemplatesPath(craft()->path->getSiteTemplatesPath());
		$options = json_decode('[' . craft()->templates->renderString($this->getSettings()->json) . ']', true);
		craft()->path->setTemplatesPath($oldTemplatesPath);
		
		if ($this->isFresh()) :
			foreach ($options as $option) :
				if (!empty($option['default'])) :
					$value = $option['value'];
					break;
				endif;
			endforeach;
		endif;
		
		return craft()->templates->render('lj_dynamicfields/input/dropdown', array(
			'name' => $name,
			'value' => $value,
			'options' => $options
		));
	}
	
	protected function defineSettings()
    {
        return array(
            'json' => array(AttributeType::String)
        );
    }
	
	public function getSettingsHtml()
    {
        return craft()->templates->render('lj_dynamicfields/settings/dropdown', array(
			'name' => 'json',
			'settings' => $this->getSettings()
        ));
    }
}