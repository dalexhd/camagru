<?php

/**
 * @var \core\View $this
 */

$this->setTitle('Create');
?>

<form action="<?= $this->Url->link('create') ?>" method="post" class="box m-4" enctype="multipart/form-data">
	<div class="field">
		<label class="label">Title</label>
		<div class="control">
			<input class="input" type="text" name="title" required>
		</div>
	</div>
	<div class="field">
		<label class="label">Description</label>
		<div class="control">
			<textarea class="textarea" name="body" required></textarea>
		</div>
	</div>
	<div id="file-js-example" class="file has-name">
		<label class="file-label">
			<input class="file-input" type="file" name="media" required>
			<span class="file-cta">
				<span class="file-icon">
					<i class="fas fa-upload"></i>
				</span>
				<span class="file-label"> Choose a file… </span>
			</span>
			<span class="file-name"> No file uploaded </span>
		</label>
	</div>
	<div class="field is-grouped">
		<div class="control">
			<button class="button is-link">Submit</button>
		</div>
		<div class="control">
			<button class="button is-link is-light">Cancel</button>
		</div>
	</div>
</form>
<script>
	const fileInput = document.querySelector("#file-js-example input[type=file]");
	fileInput.onchange = () => {
		if (fileInput.files.length > 0) {
			const fileName = document.querySelector("#file-js-example .file-name");
			fileName.textContent = fileInput.files[0].name;
		}
	};
</script>