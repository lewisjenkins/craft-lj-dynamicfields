<?php

namespace Craft;

class Lj_DynamicFields_RadioButtonsFieldType extends BaseFieldType
{
	public function getName()
	{
		return Craft::t('Radio Buttons (dynamic)');
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
		
		return craft()->templates->render('_includes/forms/radioGroup', array(
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
        return craft()->templates->render('lj_dynamicfields/settings/radiobuttons', array(
			'name' => 'json',
			'settings' => $this->getSettings()
        ));
    }
}