@extends('layouts.app')

@section('title', 'Announcements')
@section('page-title', 'Announcements')

@section('content')
	<div class="col-xl-12">
		<div class="card card-xl-stretch mb-5 mb-xl-8">
			<div class="card-header border-0 pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Announcements</span>
				</h3>
				<div class="card-toolbar">
					<button class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#announcementModal" onclick="resetAnnouncementForm()">
						<span class="svg-icon svg-icon-3">
							<!-- simple plus icon -->
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z" fill="#000"/></svg>
						</span>
						New Announcement
					</button>
				</div>
			</div>
			<div class="card-body py-3">
				<div class="table-responsive">
					<table id="announcementsTable" class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
						<thead>
							<tr class="fw-bolder text-muted">
								<th class="min-w-100px">Subject</th>
								<th class="min-w-250px">Description</th>
								@if(auth()->user()->role === 'admin')
									<th class="min-w-150px">Teacher</th>
								@endif
								<th class="min-w-100px text-end">Actions</th>
							</tr>
						</thead>
						<tbody>
						@foreach($announcements as $announcement)
							<tr>
								<td>{{ $announcement->subject }}</td>
								<td>{{ \Illuminate\Support\Str::limit($announcement->description, 100) }}</td>
								@if(auth()->user()->role === 'admin')
									<td>{{ $announcement->user?->name}}</td>
								@endif
								<td class="text-end">
									<button class="btn btn-sm btn-light-primary" onclick="editAnnouncement({{ $announcement->id }})">Edit</button>
									<button class="btn btn-sm btn-light-danger" onclick="deleteAnnouncement({{ $announcement->id }})">Delete</button>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
					<div class="mt-3">{{ $announcements->links('pagination::bootstrap-5') }}</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header"><h5 class="modal-title" id="announcementModalLabel">Add Announcement</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
				<form id="announcementForm">
					@csrf
					<div class="modal-body">
						<input type="hidden" id="announcementId">
						<div class="mb-3">
							<label class="form-label">Subject</label>
							<input class="form-control" id="announcementSubject" name="subject" required>
							<small class="text-danger" id="subjectError"></small>
						</div>
						<div class="mb-3">
							<label class="form-label">Description</label>
							<textarea class="form-control" id="announcementDescription" name="description" required></textarea>
							<small class="text-danger" id="descriptionError"></small>
						</div>
						@if(auth()->user()->role === 'teacher')
							<div class="mb-3">
								<label class="form-label">Receiver</label>
								<select name="receiver" id="receiver" class="form-select" placeholder="Select receiver" required>
										<option value="">Select receiver</option>
										<option value="students">Students</option>
										<option value="parents">Parents</option>
										<option value="both">Both</option>
								</select>
								<small class="text-danger" id="receiverError"></small>
							</div>
						@endif
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
<script>
	document.addEventListener('DOMContentLoaded', function() {
		setupFormSubmit();
	});

	function resetAnnouncementForm() {
		document.getElementById('announcementForm').reset();
		document.getElementById('announcementId').value = '';
		$('#receiver').val(null).trigger('change');
		document.getElementById('announcementModalLabel').textContent = 'Add Announcement';
		document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');
	}

	function setupFormSubmit() {
		const form = document.getElementById('announcementForm');
		if (form) {
			form.addEventListener('submit', function(e) {
				e.preventDefault();
				saveAnnouncement();
			});
		}
	}

	async function saveAnnouncement() {
		const announcementId = document.getElementById('announcementId').value;
		const url = announcementId ? `/announcements/${announcementId}` : '/announcements';

		const formData = {
			subject: document.getElementById('announcementSubject').value,
			description: document.getElementById('announcementDescription').value,
			_token: '{{ csrf_token() }}'
		};

		const receiverEl = document.getElementById('receiver');

		// Only add receiver if it actually exists + has value
		if (receiverEl && receiverEl.value) {
			formData.receiver = receiverEl.value;
		}

		if (announcementId) {
			formData._method = 'PUT';
		}

		try {
			const response = await fetch(url, {
				method: 'POST',
				headers: {
					'X-CSRF-TOKEN': '{{ csrf_token() }}',
					'Accept': 'application/json',
				},
				body: new URLSearchParams(formData)
			});

			const data = await response.json();
			if (data.success) {
				const announcementModalEl = document.getElementById('announcementModal');
				const modal = bootstrap.Modal.getInstance(announcementModalEl) || new bootstrap.Modal(announcementModalEl);
				modal.hide();
				resetAnnouncementForm();
				showAlert('success', data.message);
				location.reload();
			} else if (data.errors) {
				Object.keys(data.errors).forEach(key => {
					const el = document.getElementById(`${key}Error`);
					if (el) el.textContent = data.errors[key][0];
				});
			}
		} catch (e) {
			showAlert('error', e.message || 'An error occurred');
		}
	}

	function editAnnouncement(id) {
		fetch(`/announcements/${id}/edit`, {
			method: 'GET',
			headers: { 'Accept': 'application/json' }
		})
		.then(response => response.json())
		.then(announcement => {
			// Fill basic fields
			document.getElementById('announcementId').value = announcement.id;
			document.getElementById('announcementSubject').value = announcement.subject;
			document.getElementById('announcementDescription').value = announcement.description;

			// Pre-fill receiver (only if exists)
			const receiverEl = document.getElementById('receiver');

			if (receiverEl && announcement.receiver) {
				// Clear previous selection
				receiverEl.value = '';

				// Find matching option and select it
				const option = receiverEl.querySelector(
					`option[value="${announcement.receiver}"]`
				);

				if (option) {
					option.selected = true;
				}
			}


			document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');
			new bootstrap.Modal(document.getElementById('announcementModal')).show();
		})
		.catch(() => showAlert('error', 'Failed to load announcement'));
	}

	function deleteAnnouncement(id) {
		if (confirm('Are you sure you want to delete this announcement?')) {
			fetch(`/announcements/${id}`, {
				method: 'POST',
				headers: {
					'X-CSRF-TOKEN': '{{ csrf_token() }}',
					'Accept': 'application/json',
				},
				body: new URLSearchParams({ _method: 'DELETE', _token: '{{ csrf_token() }}' })
			})
			.then(response => response.json())
			.then(data => {
				if (data.success) {
					location.reload();
				}
			});
		}
	}

	function showAlert(type, message) {
		const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
		const alertHtml = `
			<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
				${message}
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		`;
		document.body.insertAdjacentHTML('afterbegin', alertHtml);
		setTimeout(() => { const alert = document.querySelector('.alert'); if (alert) alert.remove(); }, 3000);
	}
</script>
@endpush
