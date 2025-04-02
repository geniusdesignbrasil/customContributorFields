<?php
/**
 * @file plugins/generic/customContributorFields/controllers/grid/CustomContributorFieldsGridRow.inc.php
 *
 * GridRow para exibir ações de edição e remoção de campos personalizados.
 */

import('lib.pkp.classes.controllers.grid.GridRow');

class CustomContributorFieldsGridRow extends GridRow {

    /** @copydoc GridRow::initialize */
    function initialize($request, $template = null) {
        parent::initialize($request, $template);

        $rowId = $this->getId();
        if (!is_numeric($rowId)) return;

        $router = $request->getRouter();
        $actionArgs = array_merge(
            $this->getRequestArgs(),
            ['fieldId' => $rowId]
        );

        // Ação de edição
        $this->addAction(new LinkAction(
            'editField',
            new AjaxModal(
                $router->url($request, null, null, 'editField', null, $actionArgs),
                __('plugins.generic.customContributorFields.editField'),
                'modal_edit'
            ),
            __('grid.action.edit'),
            'edit'
        ));

        // Ação de exclusão
        $this->addAction(new LinkAction(
            'deleteField',
            new RemoteActionConfirmationModal(
                __('plugins.generic.customContributorFields.confirmDelete'),
                __('common.delete'),
                $router->url($request, null, null, 'deleteField', null, $actionArgs)
            ),
            __('grid.action.delete'),
            'delete'
        ));
    }
}
