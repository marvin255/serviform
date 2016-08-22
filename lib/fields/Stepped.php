<?php

namespace serviform\fields;

use \serviform\helpers\Html;
use \InvalidArgumentException;
use \serviform\fields\stepped\IStep;
use \serviform\fields\stepped\Step;

/**
 * Stepped form class
 */
class Stepped extends \serviform\fields\Form
{
	/**
	 * Return step model by number
	 * @param int $number
	 */
	public function getStep($number = null)
	{
		if ($number === null) $number = $this->getCurrentStep();
		$number = (int) $number;
		$steps = $this->getSteps();
		if (!isset($steps[$number])) {
			throw new InvalidArgumentException("Step with number {$number} does not exist");
		}
		return $steps[$number];
	}


	/**
	 * @return array
	 */
	public function getElements()
	{
		$elements = parent::getElements();
		$name = 'stepped_current_step';
		if (!isset($elements[$name])) {
			$currentStepElement = $this->createElement([
				'type' => 'input',
				'parent' => $this,
				'value' => $this->getCurrentStep(),
				'name' => $name,
				'attributes' => [
					'type' => 'hidden',
				],
			]);
			$elements['stepped_current_step'] = $currentStepElement;
		} else {
			$elements['stepped_current_step']->setValue($this->getCurrentStep());
		}
		return $elements;
	}


	/**
	 * @var array
	 */
	protected $_steps = [];

	/**
	 * Sets steps description
	 * @param array $steps
	 */
	public function setSteps(array $steps)
	{
		$this->_steps = [];
		foreach ($steps as $step) $this->addStep($step);
	}

	/**
	 * Returns steps
	 * @return array
	 */
	public function getSteps()
	{
		return $this->_steps;
	}

	/**
	 * Adds new step description
	 * @param \serviform\fields\stepped\IStep|array $step
	 */
	public function addStep($step)
	{
		if ($step instanceof IStep) {
			$stepModel = $step;
			$stepModel->setParent($this);
		} elseif (is_array($step)) {
			$stepModel = new Step;
			$stepModel->setParent($this);
			$stepModel->config($step);
		} else {
			throw new InvalidArgumentException("Wrong step type: must be an array or \serviform\fields\stepped\IStep instance");
		}
		$this->_steps[] = $stepModel;
	}


	/**
	 * @var int
	 */
	protected $_currentStep = 0;

	/**
	 * Sets current Stepped
	 * @param int $step
	 */
	public function setCurrentStep($step)
	{
		$steps = $this->getSteps();
		$this->_currentStep = (int) $step;
		if (!isset($steps[$this->_currentStep])) {
			throw new InvalidArgumentException("Invalid step number: {$step}");
		}
	}

	/**
	 * Returns current step
	 * @return int
	 */
	public function getCurrentStep()
	{
		return $this->_currentStep;
	}
}
