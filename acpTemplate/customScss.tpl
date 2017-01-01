{include file='header' pageTitle='wcf.acp.menu.link.style.customScss'}

<header class="boxHeadline">
	<h1>{lang}wcf.acp.menu.link.style.customScss{/lang}</h1>
</header>

{include file='formError'}

{if $success|isset}
	<p class="success">{lang}wcf.global.success{/lang}</p>
{/if}
<form method="post" action="{link controller='CustomScss'}{/link}">
	<section class="section">
		<h2 class="sectionTitle">{lang}wcf.acp.style.advanced.individualScss{/lang}</h2>

		<dl class="wide">
			<dd>
				<textarea id="individualScss" rows="20" cols="40" name="individualScss">{$individualScss}</textarea>
				<small>{lang}wcf.acp.style.advanced.individualScss.description{/lang}</small>
				{if $errorField == 'individualScss'}
					<small class="innerError">
						{if $errorType == 'empty'}
							{lang}wcf.global.form.error.empty{/lang}
						{else}
							{lang}wcf.acp.style.error.{$errorType}{/lang}
						{/if}

						{if !$error|empty}<br>{@$error->getMessage()}{/if}
					</small>
				{/if}
			</dd>
		</dl>
		{if !'ACE_THEME'|defined}
			{include file='codemirror' codemirrorMode='text/x-less' codemirrorSelector='#individualScss' sandbox=true}
		{else}
			{include file='ace' aceMode='scss' aceSelector='individualScss' sandbox=true}
		{/if}

		{event name='fieldsets'}
	</section>

	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
		{@SECURITY_TOKEN_INPUT_TAG}
	</div>
</form>

{include file='footer'}
