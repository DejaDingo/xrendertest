
<button onclick="window.location.href='/modules/xrendertest';">
	&nbsp; Back to Forms Test Index &nbsp;
</button>
<hr>
<{ if $no_action == true }>
<div style="margin-left: 20px;">
	<b>This is a mock-up form.  No form action will occur.</b>
</div>
<hr>
<{ /if }>

<{$xoForm.rendered}>
