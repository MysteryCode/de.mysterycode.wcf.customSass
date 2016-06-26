{include file='header'}

<header class="boxHeadline">
	<h1>{lang}wcf.acp.menu.link.style.customScss{/lang}</h1>
</header>

{include file='formError'}

{if $success|isset}
	<p class="success">{lang}wcf.global.success{/lang}</p>
{/if}

<div class="contentNavigation">
	<nav>
		<ul>
			{event name='contentNavigationButtons'}
		</ul>
	</nav>
</div>

<form method="post" action="{link controller='CustomScss'}{/link}">
	<div class="container containerPadding marginTop">
		<fieldset class="marginTop">
			<legend>{lang}wcf.acp.style.advanced.individualScss{/lang}</legend>
			
			<dl class="wide">
				<dd>
					<textarea id="individualScss" rows="20" cols="40" name="individualScss">{$individualScss}</textarea>
					<small>{lang}wcf.acp.style.advanced.individualScss.description{/lang}</small>
				</dd>
			</dl>
		</fieldset>
		{include file='codemirror' codemirrorMode='text/x-less' codemirrorSelector='#individualScss'}
		
		{event name='fieldsets'}
	</div>
	
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
		{@SECURITY_TOKEN_INPUT_TAG}
	</div>
</form>

{include file='footer'}