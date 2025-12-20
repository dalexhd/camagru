import CamagruModule from './module.js';

export default class extends CamagruModule {
	constructor() {
		super();
		this.stream = null;
		this.videoElement = null;
		this.canvasElement = null;
		this.selectedSticker = null;
		this.capturedImage = null;
		console.log('Camera constructor');
	}

	async initWebcam() {
		try {
			this.videoElement = document.querySelector('#webcam-preview');
			this.canvasElement = document.querySelector('#webcam-canvas');
			
			if (!this.videoElement || !this.canvasElement) {
				console.error('Webcam elements not found');
				console.log('Video element:', this.videoElement);
				console.log('Canvas element:', this.canvasElement);
				return false;
			}

			console.log('Requesting camera access...');
			
			// Request camera access
			this.stream = await navigator.mediaDevices.getUserMedia({ 
				video: { 
					width: { ideal: 1280 },
					height: { ideal: 720 }
				} 
			});
			
			console.log('Camera access granted, stream:', this.stream);
			
			this.videoElement.srcObject = this.stream;
			
			// Wait for video to be ready
			await this.videoElement.play();
			
			console.log('Video playing, dimensions:', this.videoElement.videoWidth, 'x', this.videoElement.videoHeight);
			
			// Show webcam mode, hide upload mode
			document.querySelector('#webcam-mode')?.classList.remove('is-hidden');
			document.querySelector('#upload-mode')?.classList.add('is-hidden');
			document.querySelector('#mode-toggle-webcam')?.classList.add('is-active');
			document.querySelector('#mode-toggle-upload')?.classList.remove('is-active');
			
			return true;
		} catch (error) {
			console.error('Error accessing webcam:', error);
			console.error('Error name:', error.name);
			console.error('Error message:', error.message);
			this.showUploadMode();
			alert('Could not access webcam. Please use file upload instead. Make sure your browser has permission to use the camera and that no other application is using it.');
			return false;
		}
	}

	stopWebcam() {
		if (this.stream) {
			this.stream.getTracks().forEach(track => track.stop());
			this.stream = null;
		}
		if (this.videoElement) {
			this.videoElement.srcObject = null;
		}
	}

	captureImage() {
		if (!this.selectedSticker) {
			alert('Please select a sticker first');
			return null;
		}

		if (!this.videoElement || !this.canvasElement) {
			console.error('Cannot capture: elements not found');
			return null;
		}

		// Set canvas size to match video
		this.canvasElement.width = this.videoElement.videoWidth;
		this.canvasElement.height = this.videoElement.videoHeight;
		
		// Draw current video frame to canvas
		const ctx = this.canvasElement.getContext('2d');
		ctx.drawImage(this.videoElement, 0, 0);
		
		// Convert to Base64
		this.capturedImage = this.canvasElement.toDataURL('image/png');
		
		// Show preview
		this.showCapturePreview();
		
		return this.capturedImage;
	}

	showCapturePreview() {
		const previewImg = document.querySelector('#capture-preview');
		const previewContainer = document.querySelector('#preview-container');
		
		if (previewImg && this.capturedImage) {
			previewImg.src = this.capturedImage;
			previewContainer?.classList.remove('is-hidden');
			
			// Hide video, show preview
			document.querySelector('#webcam-preview')?.classList.add('is-hidden');
			document.querySelector('#capture-controls')?.classList.add('is-hidden');
			document.querySelector('#preview-controls')?.classList.remove('is-hidden');
		}
	}

	retakePhoto() {
		this.capturedImage = null;
		const previewContainer = document.querySelector('#preview-container');
		previewContainer?.classList.add('is-hidden');
		
		// Show video again
		document.querySelector('#webcam-preview')?.classList.remove('is-hidden');
		document.querySelector('#capture-controls')?.classList.remove('is-hidden');
		document.querySelector('#preview-controls')?.classList.add('is-hidden');
	}

	selectSticker(stickerId) {
		this.selectedSticker = stickerId;
		
		// Update UI - remove active class from all stickers
		document.querySelectorAll('.sticker-item').forEach(item => {
			item.classList.remove('is-active');
		});
		
		// Add active class to selected sticker
		const selectedItem = document.querySelector(`[data-sticker-id="${stickerId}"]`);
		if (selectedItem) {
			selectedItem.classList.add('is-active');
		}

		// Enable capture button
		const captureBtn = document.querySelector('#capture-btn');
		if (captureBtn) {
			captureBtn.disabled = false;
		}
	}

	showUploadMode() {
		this.stopWebcam();
		document.querySelector('#webcam-mode')?.classList.add('is-hidden');
		document.querySelector('#upload-mode')?.classList.remove('is-hidden');
		document.querySelector('#mode-toggle-webcam')?.classList.remove('is-active');
		document.querySelector('#mode-toggle-upload')?.classList.add('is-active');
	}

	showWebcamMode() {
		document.querySelector('#upload-mode')?.classList.add('is-hidden');
		this.initWebcam();
	}

	submitPost() {
		const form = document.querySelector('#create-post-form');
		if (!form) {
			console.error('Form not found');
			return;
		}

		// Validate title and description are filled
		const title = form.querySelector('input[name="title"]');
		const body = form.querySelector('textarea[name="body"]');
		
		if (!title || !title.value.trim()) {
			alert('Please enter a title for your post');
			title?.focus();
			return;
		}
		
		if (!body || !body.value.trim()) {
			alert('Please enter a description for your post');
			body?.focus();
			return;
		}

		console.log('Submitting post...');
		console.log('Captured image:', this.capturedImage ? 'Yes' : 'No');
		console.log('Selected sticker:', this.selectedSticker);

		if (this.capturedImage) {
			// Add webcam image to form
			let webcamInput = document.querySelector('input[name="webcam_image"]');
			if (!webcamInput) {
				webcamInput = document.createElement('input');
				webcamInput.type = 'hidden';
				webcamInput.name = 'webcam_image';
				form.appendChild(webcamInput);
			}
			webcamInput.value = this.capturedImage;
			
			// Remove file input requirement
			const fileInput = document.querySelector('input[name="media"]');
			if (fileInput) {
				fileInput.removeAttribute('required');
			}
		}

		if (this.selectedSticker) {
			// Add sticker ID to form
			let stickerInput = document.querySelector('input[name="sticker_id"]');
			if (!stickerInput) {
				stickerInput = document.createElement('input');
				stickerInput.type = 'hidden';
				stickerInput.name = 'sticker_id';
				form.appendChild(stickerInput);
			}
			stickerInput.value = this.selectedSticker;
		}

		console.log('Form data prepared, submitting...');
		form.submit();
	}

	init() {
		console.log('Camera init');
		
		// Mode toggle buttons
		document.querySelector('#mode-toggle-webcam')?.addEventListener('click', () => {
			this.showWebcamMode();
		});
		
		document.querySelector('#mode-toggle-upload')?.addEventListener('click', () => {
			this.showUploadMode();
		});
		
		// Capture button
		document.querySelector('#capture-btn')?.addEventListener('click', () => {
			this.captureImage();
		});
		
		// Retake button
		document.querySelector('#retake-btn')?.addEventListener('click', () => {
			this.retakePhoto();
		});
		
		// Sticker selection
		document.querySelectorAll('.sticker-item').forEach(item => {
			item.addEventListener('click', () => {
				const stickerId = item.dataset.stickerId;
				this.selectSticker(stickerId);
			});
		});
		
		// Submit button (when using webcam)
		document.querySelector('#submit-webcam-btn')?.addEventListener('click', () => {
			this.submitPost();
		});
		
		// Form submit handler (for upload mode)
		const form = document.querySelector('#create-post-form');
		if (form) {
			form.addEventListener('submit', (e) => {
				// Add sticker ID if one is selected
				if (this.selectedSticker) {
					let stickerInput = document.querySelector('input[name="sticker_id"]');
					if (!stickerInput) {
						stickerInput = document.createElement('input');
						stickerInput.type = 'hidden';
						stickerInput.name = 'sticker_id';
						form.appendChild(stickerInput);
					}
					stickerInput.value = this.selectedSticker;
				}
			});
		}
		
		// Initialize webcam by default
		this.initWebcam();
	}
}
