<?php

namespace lewisjenkins\craftdynamicfields\fields;

use lewisjenkins\craftdynamicfields\CraftDynamicFields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Db;
use yii\db\Schema;
use craft\helpers\Json;

class Radiobuttons extends Field
{
     
    public $radioOptions = '';
    public $columnType = 'text';

    public static function displayName(): string
    {
        return Craft::t('craft-dynamic-fields', 'Radio Buttons (dynamic)');
    }

    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate(
            'craft-dynamic-fields/_components/fields/Radiobuttons_settings',
            [
                'field' => $this,
            ]
        );
    }
    
    public function getContentColumnType(): string
    {
        return $this->columnType;
    }

    public function normalizeValue($value, ElementInterface $element = null): string
    {	
		$view = Craft::$app->getView();
		$templateMode = $view->getTemplateMode();
		$view->setTemplateMode($view::TEMPLATE_MODE_SITE);

		$variables['element'] = $element;
		$variables['this'] = $this;
		
		$options = json_decode('[' . $view->renderString($this->radioOptions, $variables) . ']', true);
		
		$view->setTemplateMode($templateMode);
		
		if (!$value && $this->isFresh($element) ) :
			foreach ($options as $key => $option) :
				if (!empty($option['default'])) :
					$value = $option['value'];
				endif;
			endforeach;
		endif;
		
		return (is_null($value) ? '' : $value);
    }
    
    public function getInputHtml($value, ElementInterface $element = null): string
    {
		$view = Craft::$app->getView();
		$templateMode = $view->getTemplateMode();
		$view->setTemplateMode($view::TEMPLATE_MODE_SITE);

		$variables['element'] = $element;
		$variables['this'] = $this;
		
		$options = json_decode('[' . $view->renderString($this->radioOptions, $variables) . ']', true);
		
		$view->setTemplateMode($templateMode);
		
        return Craft::$app->getView()->renderTemplate('craft-dynamic-fields/_includes/forms/radioGroup', [
            'name' => $this->handle,
            'value' => $value,
            'options' => $options
        ]);
    }
}
