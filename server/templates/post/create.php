<?php

/**
 * @var \core\View $this
 */

$this->setTitle('Create');
?>

<div class="columns m-0" id="create-post-wrapper">
	<div class="column">
		<div class="block pt-5 is-hidden-mobile">
			<h3 class="title is-3 m-0">
				Create Post
			</h3>
		</div>
		<div class="block">
			<div class="p-2">
				<form action="<?= $this->Url->link('create') ?>" method="post" enctype="multipart/form-data">
					<div class="field columns">
						<label class="label column is-one-quarter">Title</label>
						<div class="control column">
							<input class="input" type="text" name="title" placeholder="Give your post a title" required>
						</div>
					</div>
					<hr>
					<div class="field columns">
						<label class="label column is-one-quarter">Description</label>
						<div class="control column">
							<textarea class="textarea" name="body" placeholder="Describe your post..."
								required></textarea>
						</div>
					</div>
					<hr>
					<div class="field columns">
						<div class="column is-one-quarter">
							<label class="label">Image</label>
						</div>
						<div class="control column">
							<div id="file-js-example" class="file has-name">
								<label class="file-label">
									<input class="file-input" type="file" name="media" required>
									<span class="file-cta">
										<span class="file-icon">
											<i class="fas fa-upload"></i>
										</span>
										<span class="file-label">Choose a file…</span>
									</span>
									<span class="file-name">No file uploaded</span>
								</label>
							</div>
						</div>
					</div>
					<div class="field is-grouped is-justify-content-end">
						<div class="control">
							<button class="button is-link">Submit</button>
						</div>
						<div class="control">
							<a href="<?= $this->Url->link('home') ?>" class="button is-link is-light">Cancel</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	const fileInput = document.querySelector("#file-js-example input[type=file]");
	fileInput.onchange = () => {
		if (fileInput.files.length > 0) {
			const fileName = document.querySelector("#file-js-example .file-name");
			fileName.textContent = fileInput.files[0].name;
		}
	};
</script>