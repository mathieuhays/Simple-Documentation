<?php
/**
 * Meta Box Attachments
 */

?>
<style>
	.sd-attachment-button {
		background: #fff;
		border: 1px solid #999;
		border-radius: 5px;
		margin-top: 15px;
		padding: 6px 20px;
		width: 100%;
	}

	.sp-attachment-item__wrapper {
		padding: 5px 0;
	}

	.sp-attachment-item {
		border-top: 1px solid #ccc;
	}

	.sp-attachment-item:first-child {
		border-top: 0;
	}

	.sp-attachment-item__title {
		font-size: 14px;
		margin: 0 0 5px;
	}

	.sp-attachment-item__description {
		font-size: 12px;
		margin-bottom: 5px;
	}

	.sp-attachment-item__description p {
		font-size: inherit;
		margin: 0;
	}

	.sp-attachment-item__description p + p {
		margin-top: 15px;
	}

	.sp-attachment-item__actions {
		font-size: 12px;
	}
</style>

<div class="sp-attachment-item__wrapper">
	<div class="sp-attachment-item">
		<h3 class="sp-attachment-item__title">tutorial.pdf</h3>
		<div class="sp-attachment-item__description">
			<p>
				Maecenas faucibus mollis interdum. Maecenas faucibus mollis interdum.
			</p>
		</div>
		<div class="sp-attachment-item__actions">
			<a href="" target="_blank">View</a>
		</div>
	</div>
</div>

<button class="sd-attachment-button">Add Attachment</button>
