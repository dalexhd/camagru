<?php

/**
 * @var \core\View $this
 */

$this->setTitle('Create');
$stickers = $stickers ?? [];
?>

<div class="columns m-0" id="create-post-wrapper">
	<div class="column">
		<div class="block pt-5 is-hidden-mobile">
			<h3 class="title is-3 m-0">
				Create Post
			</h3>
		</div>

		<!-- Mode Toggle -->
		<div class="block">
			<div class="tabs is-toggle is-fullwidth">
				<ul>
					<li id="mode-toggle-webcam" class="is-active">
						<a>
							<span class="icon is-small"><i class="fas fa-camera"></i></span>
							<span>Webcam</span>
						</a>
					</li>
					<li id="mode-toggle-upload">
						<a>
							<span class="icon is-small"><i class="fas fa-upload"></i></span>
							<span>Upload File</span>
						</a>
					</li>
				</ul>
			</div>
		</div>

		<!-- Sticker Gallery -->
		<?php if (!empty($stickers)): ?>
			<div class="block">
				<div class="p-2">
					<h5 class="title is-5">Select a Sticker</h5>
					<div class="columns is-multiline">
						<?php foreach ($stickers as $sticker): ?>
							<div class="column is-one-fifth">
								<div class="sticker-item" data-sticker-id="<?= $sticker['id'] ?>"
									style="cursor: pointer; border: 3px solid transparent; border-radius: 8px; padding: 8px; transition: all 0.2s;">
									<img src="<?= $this->Url->asset('img/stickers/' . $sticker['filename']) ?>"
										alt="<?= $sticker['id'] ?>" style="width: 100%; height: auto;">
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<div class="block">
			<div class="p-2">
				<form id="create-post-form" action="<?= $this->Url->link('create') ?>" method="post"
					enctype="multipart/form-data">
					<input type="hidden" name="csrf_token" value="<?= \core\Security::generateCSRFToken() ?>">

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

					<!-- Webcam Mode -->
					<div id="webcam-mode">
						<div class="field columns">
							<div class="column is-one-quarter">
								<label class="label">Camera</label>
							</div>
							<div class="column">
								<!-- Webcam Preview -->
								<video id="webcam-preview" autoplay playsinline
									style="width: 100%; max-width: 640px; border-radius: 8px; background: #000;"
									aria-label="Live webcam preview of the photo to be captured"></video>

								<!-- Capture Preview (hidden initially) -->
								<div id="preview-container" class="is-hidden">
									<img id="capture-preview"
										style="width: 100%; max-width: 640px; border-radius: 8px;"
										alt="Preview of the captured photo" />
								</div>

								<!-- Hidden canvas for capture -->
								<canvas id="webcam-canvas" style="display: none;"></canvas>

								<!-- Capture Controls -->
								<div id="capture-controls" class="mt-3">
									<button type="button" id="capture-btn" class="button is-primary">
										<span class="icon"><i class="fas fa-camera"></i></span>
										<span>Capture Photo</span>
									</button>
								</div>

								<!-- Preview Controls (hidden initially) -->
								<div id="preview-controls" class="mt-3 is-hidden">
									<button type="button" id="retake-btn" class="button is-warning">
										<span class="icon"><i class="fas fa-redo"></i></span>
										<span>Retake</span>
									</button>
									<button type="button" id="submit-webcam-btn" class="button is-success">
										<span class="icon"><i class="fas fa-check"></i></span>
										<span>Use This Photo</span>
									</button>
								</div>
							</div>
						</div>
					</div>

					<!-- Upload Mode (hidden initially) -->
					<div id="upload-mode" class="is-hidden">
						<div class="field columns">
							<div class="column is-one-quarter">
								<label class="label">Image</label>
							</div>
							<div class="control column">
								<div id="file-js-example" class="file has-name">
									<label class="file-label">
										<input class="file-input" type="file" name="media" accept="image/*">
										<span class="file-cta">
											<span class="icon">
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
								<button type="submit" class="button is-link">Submit</button>
							</div>
							<div class="control">
								<a href="<?= $this->Url->link('home') ?>" class="button is-link is-light">Cancel</a>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<style>
	.sticker-item.is-active {
		border-color: #3273dc !important;
		background-color: #f0f7ff;
	}

	.sticker-item:hover {
		border-color: #b5b5b5 !important;
	}
</style>

<script>
	// File input name display
	const fileInput = document.querySelector("#file-js-example input[type=file]");
	if (fileInput) {
		fileInput.onchange = () => {
			if (fileInput.files.length > 0) {
				const fileName = document.querySelector("#file-js-example .file-name");
				fileName.textContent = fileInput.files[0].name;
			}
		};
	}
</script>