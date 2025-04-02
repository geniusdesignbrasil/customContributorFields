<?php
/**
 * @file plugins/generic/customContributorFields/page/ManageHandler.inc.php
 *
 * Handler para exibir a interface de administração dos campos personalizados.
 */

import('lib.pkp.classes.handler.Handler');

class ManageHandler extends Handler {

    function __construct() {
        parent::__construct();
        $this->addRoleAssignment(
            [ROLE_ID_MANAGER],
            ['index']
        );
    }

    /**
     * Ponto de entrada da interface de administração do plugin
     */
    function index($args, $request) {
        $templateMgr = TemplateManager::getManager($request);

        // Define a URL base do grid
        $plugin = PluginRegistry::getPlugin('generic', 'customcontributorfieldsplugin');
        $gridUrl = $request->getDispatcher()->url(
            $request, null, null, 'grid', 'fetch', null,
            ['router' => COMPONENT_ROUTER, 'component' => 'plugins.generic.customContributorFields.controllers.grid.CustomContributorFieldsGridHandler']
        );

        $templateMgr->assign('gridUrl', $gridUrl);
        $templateMgr->display($plugin->getTemplatePath() . 'manage.tpl');
    }
}
