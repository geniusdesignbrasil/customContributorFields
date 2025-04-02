{**
 * @file plugins/generic/customContributorFields/templates/fieldForm.tpl
 * Template do formulário de criação/edição de campos personalizados
**}

<form class="pkp_form" id="customContributorFieldForm" method="post" action="{$formActionUrl}">
	{csrf}
	{fbvFormArea id="fieldFormArea"}

		{fbvFormSection label="plugins.generic.customContributorFields.fieldName" for="field_name"}
			{fbvElement type="text" id="field_name" value=$field_name required=true size="MEDIUM"}
		{/fbvFormSection}

		{fbvFormSection label="plugins.generic.customContributorFields.fieldType" for="field_type"}
			{fbvElement type="select" id="field_type" from=["text":"Text","textarea":"Textarea","url":"URL","select":"Select"] selected=$field_type required=true}
		{/fbvFormSection}

		{fbvFormSection label="plugins.generic.customContributorFields.required" for="is_required"}
			{fbvElement type="checkbox" id="is_required" checked=$is_required label="plugins.generic.customContributorFields.requiredLabel"}
		{/fbvFormSection}

		{fbvFormSection label="plugins.generic.customContributorFields.public" for="is_public"}
			{fbvElement type="checkbox" id="is_public" checked=$is_public label="plugins.generic.customContributorFields.publicLabel"}
		{/fbvFormSection}

		{fbvFormSection label="plugins.generic.customContributorFields.showOnForm" for="show_on_form"}
			{fbvElement type="checkbox" id="show_on_form" checked=$show_on_form label="plugins.generic.customContributorFields.showOnFormLabel"}
		{/fbvFormSection}

		{fbvFormSection label="plugins.generic.customContributorFields.showOnProfile" for="show_on_profile"}
			{fbvElement type="checkbox" id="show_on_profile" checked=$show_on_profile label="plugins.generic.customContributorFields.showOnProfileLabel"}
		{/fbvFormSection}

	{/fbvFormArea}

	{fbvFormButtons submitText="common.save" cancelText="common.cancel"}
</form>
