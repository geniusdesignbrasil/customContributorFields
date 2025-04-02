<?php
/**
 * @file plugins/generic/customContributorFields/CustomContributorFieldsPlugin.inc.php
 *
 * Plugin para adicionar campos personalizados ao formulário de contribuidores.
 * Compatível com OJS 3.3.x e 3.4.x
 */

import('lib.pkp.classes.plugins.GenericPlugin');

class CustomContributorFieldsPlugin extends GenericPlugin {

    /** @copydoc Plugin::register */
    function register($category, $path, $mainContextId = null) {
        if (!parent::register($category, $path)) return false;

        // Carrega DAOs, formulários, etc.
        $this->import('CustomContributorFieldsDAO');

        // Registra DAO personalizado
        DAORegistry::registerDAO('CustomContributorFieldsDAO', new CustomContributorFieldsDAO());

        // Hook para injetar campos personalizados no formulário de autor
        HookRegistry::register('AuthorForm::display', array($this, 'handleAuthorFormDisplay'));
        HookRegistry::register('AuthorForm::initData', array($this, 'handleAuthorFormInitData'));
        HookRegistry::register('AuthorForm::readInputData', array($this, 'handleAuthorFormReadInputData'));
        HookRegistry::register('AuthorForm::execute', array($this, 'handleAuthorFormExecute'));

        // Hook para exibir os campos no perfil do usuário (versão simplificada)
        HookRegistry::register('TemplateManager::display', array($this, 'handleTemplateDisplay'));

        // Admin UI: Adiciona menu em Configurações > Website
        HookRegistry::register('TemplateManager::display', array($this, 'addSettingsTab'));

        return true;
    }

    /** @copydoc Plugin::getDisplayName */
    function getDisplayName() {
        return __('plugins.generic.customContributorFields.name');
    }

    /** @copydoc Plugin::getDescription */
    function getDescription() {
        return __('plugins.generic.customContributorFields.description');
    }

    /**
     * Hook: Adiciona campos no formulário de contribuidores
     */
    function handleAuthorFormDisplay($hookName, $args) {
        return false;
    }

    function handleAuthorFormInitData($hookName, $args) {
        return false;
    }

    function handleAuthorFormReadInputData($hookName, $args) {
        return false;
    }

    function handleAuthorFormExecute($hookName, $args) {
        return false;
    }

    /**
     * Hook: Exibe os campos no template público, se necessário
     */
    function handleTemplateDisplay($hookName, $args) {
        return false;
    }

    /**
     * Adiciona uma aba em Configurações > Website
     */
    function addSettingsTab($hookName, $args) {
        $templateManager =& $args[0];
        $template =& $args[1];

        if ($template == 'management/settings/settings.tpl') {
            $customTab = '<li><a href="' . $this->getPluginPath() . '/manage" id="customContributorFieldsTab">' . __('plugins.generic.customContributorFields.tabName') . '</a></li>';
            $output =& $args[2];
            $output .= $customTab;
        }

        return false;
    }
}