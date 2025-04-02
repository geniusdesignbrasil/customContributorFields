<?php
/**
 * @file plugins/generic/customContributorFields/controllers/grid/CustomContributorFieldsGridHandler.inc.php
 *
 * GridHandler para administrar os campos personalizados em Configurações > Website
 */

import('lib.pkp.classes.controllers.grid.GridHandler');
import('lib.pkp.classes.controllers.grid.DataObjectGridCellProvider');
import('plugins.generic.customContributorFields.controllers.grid.CustomContributorFieldsGridRow');
import('plugins.generic.customContributorFields.forms.CustomContributorFieldForm');

class CustomContributorFieldsGridHandler extends GridHandler {

    /** @copydoc GridHandler::initialize */
    function initialize($request, $args = null) {
        parent::initialize($request, $args);

        AppLocale::requireComponents(LOCALE_COMPONENT_APP_MANAGER);

        $this->addColumn(new GridColumn(
            'field_name',
            __('plugins.generic.customContributorFields.grid.name'),
            null,
            null,
            new DataObjectGridCellProvider()
        ));

        $this->addColumn(new GridColumn(
            'field_type',
            __('plugins.generic.customContributorFields.grid.type'),
            null,
            null,
            new DataObjectGridCellProvider()
        ));

        $this->addColumn(new GridColumn(
            'is_required',
            __('plugins.generic.customContributorFields.grid.required'),
            null,
            null,
            new DataObjectGridCellProvider()
        ));

        $this->addColumn(new GridColumn(
            'is_public',
            __('plugins.generic.customContributorFields.grid.public'),
            null,
            null,
            new DataObjectGridCellProvider()
        ));

        $this->setTitle(__('plugins.generic.customContributorFields.grid.title'));
        $this->setInstructions(__('plugins.generic.customContributorFields.grid.instructions'));

        // Botão de adicionar campo
        $router = $request->getRouter();
        $this->addAction(new LinkAction(
            'addField',
            new AjaxModal(
                $router->url($request, null, null, 'editField'),
                __('plugins.generic.customContributorFields.editField'),
                'modal_add'
            ),
            __('grid.action.addItem'),
            'add_item'
        ));

        // Hook para injetar os campos no template do formulário de contribuidores
        HookRegistry::register('TemplateManager::display', [$this, 'injectContributorFieldsHtml']);
    }

    function getRowInstance() {
        return new CustomContributorFieldsGridRow();
    }

    function loadData($request, $filter = null) {
        $context = $request->getContext();
        $contextId = $context->getId();

        $dao = DAORegistry::getDAO('CustomContributorFieldsDAO');
        return $dao->getFieldsByContextId($contextId);
    }

    function editField($args, $request) {
        $context = $request->getContext();
        $fieldId = isset($args['fieldId']) ? (int) $args['fieldId'] : null;

        $form = new CustomContributorFieldForm($context->getId(), $fieldId);
        $form->initData();

        return new JSONMessage(true, $form->fetch($request));
    }

    function deleteField($args, $request) {
        $fieldId = isset($args['fieldId']) ? (int) $args['fieldId'] : null;
        if ($fieldId) {
            $dao = DAORegistry::getDAO('CustomContributorFieldsDAO');
            $dao->update('DELETE FROM custom_contributor_fields WHERE id = ?', [$fieldId]);
        }
        return DAO::getDataChangedEvent();
    }

    function fetch($args, $request) {
        return parent::fetch($args, $request);
    }

    /**
     * Injeção dos campos personalizados no formulário de contribuidores
     */
    function handleAuthorFormDisplay($hookName, $args) {
        $form =& $args[0];
        $templateMgr = TemplateManager::getManager(Application::getRequest());
        $context = Application::getRequest()->getContext();

        $dao = DAORegistry::getDAO('CustomContributorFieldsDAO');
        $fields = $dao->getFieldsByContextId($context->getId());

        $customFieldsHtml = '';
        foreach ($fields as $field) {
            if (!$field['show_on_form']) continue;
            $value = '';
            $inputType = ($field['field_type'] === 'textarea') ? 'textarea' : 'text';
            $required = $field['is_required'] ? 'required' : '';

            $customFieldsHtml .= '<div class="pkp_form_field"><label>' . htmlspecialchars($field['field_name']) . '</label>';
            if ($inputType === 'textarea') {
                $customFieldsHtml .= '<textarea name="customField[' . $field['id'] . ']" ' . $required . '></textarea>';
            } else {
                $customFieldsHtml .= '<input type="' . $inputType . '" name="customField[' . $field['id'] . ']" value="' . htmlspecialchars($value) . '" ' . $required . ' />';
            }
            $customFieldsHtml .= '</div>';
        }

        $templateMgr->assign('customContributorFieldsHtml', $customFieldsHtml);
        return false;
    }

    /**
     * Injeta o HTML gerado no template correto (formulário de contribuidores)
     */
    function injectContributorFieldsHtml($hookName, $args) {
        $templateMgr =& $args[0];
        $template =& $args[1];

        if (strpos($template, 'submission/form/authorForm.tpl') !== false ||
            strpos($template, 'submission/form/step3.tpl') !== false) {
            $output =& $args[2];
            $output = str_replace('</form>', '{$customContributorFieldsHtml|default:"" nofilter}</form>', $output);
        }

        return false;
    }
}
