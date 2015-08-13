<?php

namespace serviform;

/**
 * Renderable element class
 */
abstract class BaseRenderable extends Base
{
	/**
	 * @var mixed
	 */
	protected $_template = null;



	/**
	 * @return string
	 */
	public function getInput()
	{
		$return = null;
		$template = $this->getTemplate();
		if ($template === null) {
			if (is_callable($template)) {
				$return = call_user_func($template, $this);
			} elseif (file_exists($template)) {
				ob_start();
				ob_implicit_flush(false);
				require($template);
				$return = ob_get_clean();
			}
		}
		return $return;
	}



	/**
	 * @param mixed $template
	 */
	public function setTemplate($template)
	{
		$this->_template = $template;
	}

	/**
	 * @return mixed
	 */
	public function getTemplate()
	{
		return $this->_template;
	}
}