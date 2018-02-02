<?php

namespace Catalyst\Form;

use \InvalidArgumentException;

/**
 * Represents a form
 */
class Form {
	public const GET = 0;
	public const POST = 1;

	public const BASE_URI = "api/";

	/**
	 * Appended to distinguisher for <form> ID
	 */
	public const FORM_ELEMENT_ID_SUFFIX = "-form-element";

	/**
	 * Appended to distinguisher for submit button wrapper
	 */
	public const SUBMIT_BUTTON_WRAPPER_SUFFIX = "-submit-wrapper";
	/**
	 * Appended to distinguisher for submit button
	 */
	public const SUBMIT_BUTTON_SUFFIX = "-submit-btn";
	/**
	 * Appended to distinguisher for progress div
	 */
	public const PROGRESS_ELEMENT_ID_SUFFIX = "-form-element";

	/**
	 * Name for the form, used to distinguish it from others
	 */
	protected $distinguisher = "";
	/**
	 * Action to perform upon form submission.  One of CompletionAction
	 */
	protected $completionAction = null;
	/**
	 * Method used for request.  One of self::GET or self::POST
	 */
	protected $method = self::POST;
	/**
	 * Endpoint to handle request.  Will almost always be internal/..., relative to /api/
	 */
	protected $endpoint = "";
	/**
	 * Text for submit button
	 */
	protected $buttonText = "submit";
	/**
	 * If form is the only form on page (and thus should auto-focus)
	 */
	protected $primary = true;

	/**
	 * Array of Field[] objects
	 */
	protected $fields = [];

	/**
	 * Create a new Form based on the given parameters
	 * 
	 * @param string $distinguisher
	 * @param CompletionAction|null $completionAction
	 * @param int $method
	 * @param string $endpoint
	 * @param string $buttonText
	 * @param Field[] $fields
	 * @param bool $primary If the form is the only one on the page (it should be focused automatically if so)
	 */
	public function __construct(string $distinguisher="", ?CompletionAction $completionAction=null, int $method=self::POST, string $endpoint="", string $buttonText="", array $fields=[], bool $primary=true) {
		$this->setDistinguisher($distinguisher);
		$this->setCompletionAction($completionAction);
		$this->setMethod($method);
		$this->setEndpoint($endpoint);
		$this->setButtonText($buttonText);
		$this->setFields($fields);
		$this->setPrimary($primary);
	}

	/**
	 * Get the current form distinguisher
	 * 
	 * @return string The distinguisher
	 */
	public function getDistinguisher() : string {
		return $this->distinguisher;
	}

	/**
	 * Set the form distinguisher to a new value
	 * 
	 * @param string $distinguisher The new form distinguisher
	 */
	public function setDistinguisher(string $distinguisher) : void {
		$this->distinguisher = $distinguisher;
	}

	/**
	 * Get the current form completion action (null if none)
	 * 
	 * @return CompletionAction|null The current completion action
	 */
	public function getCompletionAction() : ?CompletionAction {
		return $this->completionAction;
	}

	/**
	 * Set the form completion action to a new value
	 * 
	 * @param CompletionAction|null $completionAction The new action to be called upon completion
	 */
	public function setCompletionAction(?CompletionAction $completionAction) : void {
		$this->completionAction = $completionAction;
	}

	/**
	 * Get the current form method (self::GET or self::POST)
	 * 
	 * @return int The form's method, either self::GET or self::POST
	 */
	public function getMethod() : int {
		return $this->method;
	}

	/**
	 * Get the current form method as a string (GET or POST)
	 * 
	 * @return string The form's method, either GET or POST
	 */
	public function getMethodString() : string {
		switch ($this->getMethod()) {
			case self::GET:
				return "GET";
				break;
			case self::POST:
				return "POST";
				break;
			default:
				throw new InvalidArgumentException("Unknown method type");
		}
	}

	/**
	 * Set the form method
	 * 
	 * @param int $method New method for form
	 */
	public function setMethod(int $method) : void {
		if ($method !== self::GET && $method !== self::POST) {
			throw new InvalidArgumentException("Method not one of POST or GET (are you using Form class constants?)");
		}
		$this->method = $method;
	}

	/**
	 * Get the current form endpoint (where the AJAX call is sent)
	 * 
	 * This should be relative to self::BASE_URL
	 * 
	 * @return string The current endpoint
	 */
	public function getEndpoint() : string {
		return $this->endpoint;
	}

	/**
	 * Set the form endpoint to a new value
	 * 
	 * This should be relative to self::BASE_URL
	 * 
	 * @param string $endpoint The new endpoint
	 */
	public function setEndpoint(string $endpoint) : void {
		$this->endpoint = $endpoint;
	}

	/**
	 * Get the current submit button text
	 * 
	 * @return string The submission button text
	 */
	public function getButtonText() : string {
		return $this->buttonText;
	}

	/**
	 * Set the submit button text to a new value
	 * 
	 * @param string $buttonText New text to display on the button
	 */
	public function setButtonText(string $buttonText) : void {
		$this->buttonText = $buttonText;
	}

	/**
	 * Get the current array of Fields
	 * 
	 * @return Field[] Current fields
	 */
	public function getFields() : array {
		return $this->fields;
	}

	/**
	 * Add a field
	 * 
	 * @param Field $field New field to add
	 */
	public function addField(Field $field) : void {
		$this->fields[] = $field;
	}

	/**
	 * Add several fields
	 * 
	 * @param Field[] $fields New fields to add
	 */
	public function addFields(array $fields) : void {
		array_map([$this, "addField"], $fields);
	}

	/**
	 * Set the field array to a new value
	 * 
	 * @param Field[] $fields New fields to replace existing array
	 */
	public function setFields(array $fields) : void {
		$this->fields = [];
		array_map([$this, "addField"], $fields); // lazy way of checking all fields
	}

	/**
	 * Get the form's primary status
	 * 
	 * @return bool If the form is primary
	 */
	public function getPrimary() : bool {
		return $this->primary;
	}

	/**
	 * Set the form's primary status
	 * 
	 * @param bool $primary If the form is primary
	 */
	public function setPrimary(bool $primary) : void {
		$this->primary = $primary;
	}

	/**
	 * Get the form's header/opening tag
	 * 
	 * @return string the opening tag
	 */
	public function getFormHeader() : string {
		return '<form action="'.htmlspecialchars($this->getDistinguisher().self::FORM_ELEMENT_ID_SUFFIX).'" id="'.htmlspecialchars($this->getDistinguisher().self::FORM_ELEMENT_ID_SUFFIX).'" method="'.htmlspecialchars($this->getMethodString()).'" enctype="multipart/form-data">';
	}

	/**
	 * Get the submission button HTML
	 * 
	 * @return string The ending html
	 */
	public function getSubmitButton() : string {
		$str = '';
		$str .= '<div class="row">';
		$str .= '<br>';
		$str .= '<div id="'.htmlspecialchars($this->getDistinguisher().self::SUBMIT_BUTTON_WRAPPER_SUFFIX).'">';
		$str .= '<button id="'.htmlspecialchars($this->getDistinguisher().self::SUBMIT_BUTTON_SUFFIX).'" class="btn waves-effect waves-light col s12 m4 l2">';
		$str .= htmlspecialchars($this->getButtonText());
		$str .= '</button>';
		$str .= '</div>';
		$str .= '<div id="'.htmlspecialchars($this->getDistinguisher().self::PROGRESS_ELEMENT_ID_SUFFIX).'" class="hide">';
		$str .= '<div class="progress">';
		$str .= '<div class="indeterminate"></div>';
		$str .= '</div>';
		$str .= '</div>';
		$str .= '</div>';
		return $str;
	}

	/**
	 * Get the form's HTML
	 * 
	 * @return string The form's HTML
	 */
	public function getHtml() : string {
		$str = '';
		$str .= '<div class="section">';
		$str .= $this->getFormHeader();
		$str .= '<div class="row">';
		foreach ($this->fields as $field) {
			$str .= $field->getHtml();
		}
		$str .= '</div>';
		$str .= '<br>';
		$str .= '<div class="divider">';
		$str .= '</div>';
		$str .= $this->getSubmitButton();
		$str .= '</form>';
		$str .= '</div>';
		return $str;
	}

	public function getJsValidation() : string {
		// TODO
	}

	public function getJsAggregator() : string {
		// TODO
	}

	public function getJsAjaxRequest() : string {
		// TODO
	}

	/**
	 * Get all of the JavaScript for the form
	 * 
	 * @return string JS code
	 */
	public function getAllJs() : string {
		return $this->getJsValidation().$this->getJsAggregator().$this->getJsAjaxRequest();
	}

	/**
	 * Check the field's forms on the servers side
	 * 
	 * No parameters as the fields have concrete names, and no return as appropriate errors are returned
	 */
	public function checkServerSide() : void {
		foreach ($this->fields as $field) {
			$field->checkServerSide();
		}
	}
}