<?php
/**
 * @file plugins/generic/customContributorFields/forms/CustomContributorFieldForm.inc.php
 *
 * Formulário para criar ou editar um campo personalizado.
 */

import('lib.pkp.classes.form.Form');

class CustomContributorFieldForm extends Form {

    /** @var int */
    public $contextId;

    /** @var int|null */
    public $fieldId;

    /** @var array */
    public $fieldData;

    function __construct($contextId, $fieldId = null) {
        parent::__construct('plugins/generic/customContributorFields/templates/fieldForm.tpl');
        $this->contextId = $contextId;
        $this->fieldId = $fieldId;

        $this->addCheck(new FormValidatorPost($this));
        $this->addCheck(new FormValidatorCSRF($this));
    }

    function initData() {
        if ($this->fieldId) {
            $dao = DAORegistry::getDAO('CustomContributorFieldsDAO');
            $fields = $dao->getFieldsByContextId($this->contextId);
            foreach ($fields as $field) {
                if ($field['id'] == $this->fieldId) {
                    $this->setData('field_name', $field['field_name']);
                    $this->setData('field_type', $field['field_type']);
                    $this->setData('is_required', $field['is_required']);
                    $this->setData('is_public', $field['is_public']);
                    $this->setData('show_on_form', $field['show_on_form']);
                    $this->setData('show_on_profile', $field['show_on_profile']);
                    break;
                }
            }
        }
    }

    function readInputData() {
        $this->readUserVars([
            'field_name', 'field_type', 'is_required', 'is_public', 'show_on_form', 'show_on_profile'
        ]);
    }

    function execute(...$functionArgs) {
        $dao = DAORegistry::getDAO('CustomContributorFieldsDAO');

        if ($this->fieldId) {
            // Atualização futura
        } else {
            $dao->insertField(
                $this->contextId,
                $this->getData('field_name'),
                $this->getData('field_type'),
                $this->getData('is_required'),
                $this->getData('is_public'),
                $this->getData('show_on_form'),
                $this->getData('show_on_profile')
            );
        }

        return true;
    }
}
