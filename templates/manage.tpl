{**
 * @file plugins/generic/customContributorFields/templates/manage.tpl
 * Template da interface de administração dos campos personalizados
**}

{extends file="layouts/backend.tpl"}

{block name="page"}
	<div id="customContributorFieldsGridContainer">
		<script type="text/javascript">
			$(function() {
				$('#customContributorFieldsGridContainer').pkpHandler('$.pkp.controllers.UrlInDivHandler', {
					sourceUrl: '{$gridUrl|escape:'javascript'}',
					scrollable: false
				});
			});
		</script>
	</div>
{/block}
