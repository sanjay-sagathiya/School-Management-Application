@extends('layouts.app')

@section('title', 'Teachers')
@section('page-title', 'Teachers')

@section('content')
	<!--begin::Col-->
	<div class="col-xl-12">
		<!--begin::Tables Widget 9-->
		<div class="card card-xl-stretch mb-5 mb-xl-8">
			<!--begin::Header-->
			<div class="card-header border-0 pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Teachers</span>
				</h3>
				<div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="Click to add a teacher">
					<button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#teacherModal" onclick="resetTeacherForm()">
						<span class="svg-icon svg-icon-3">
							<!-- simple plus icon -->
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z" fill="#000"/></svg>
						</span>
						New Teacher
					</button>
				</div>
			</div>
			<!--end::Header-->
			<!--begin::Body-->
			<div class="card-body py-3">
				<!--begin::Table container-->
				<div class="table-responsive">
					<!--begin::Table-->
					<table id="teachersTable" class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
						<!--begin::Table head-->
						<thead>
							<tr class="fw-bolder text-muted">
								<th class="min-w-150px">Name</th>
								<th class="min-w-200px">Email</th>
								<th class="min-w-150px">Created</th>
								<th class="min-w-100px text-end">Actions</th>
							</tr>
						</thead>
						<!--end::Table head-->
						<!--begin::Table body-->
						<tbody>
						@foreach($teachers as $teacher)
							<tr>
								<td>{{ $teacher->name }}</td>
								<td>{{ $teacher->email }}</td>
								<td>{{ $teacher->created_at?->format('Y-m-d') }}</td>
								<td class="text-end">
									<button class="btn btn-sm btn-light-primary" onclick="editTeacher({{ $teacher->id }})">Edit</button>
									<button class="btn btn-sm btn-light-danger" onclick="deleteTeacher({{ $teacher->id }})">Delete</button>
								</td>
							</tr>
						@endforeach
						</tbody>
						<!--end::Table body-->
					</table>
						<!--end::Table-->
						<div class="mt-3">
							{{ $teachers->links('pagination::bootstrap-5') }}
						</div>
				</div>
				<!--end::Table container-->
			</div>
			<!--begin::Body-->
		</div>
		<!--end::Tables Widget 9-->
	</div>
	<!--end::Col-->

	<!-- Modal for Create/Edit Teacher -->
	<div class="modal fade" id="teacherModal" tabindex="-1" aria-labelledby="teacherModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="teacherModalLabel">Add Teacher</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form id="teacherForm">
					<div class="modal-body">
						<input type="hidden" id="teacherId">
						@csrf
						<div class="mb-3">
							<label for="teacherName" class="form-label">Name</label>
							<input type="text" class="form-control" id="teacherName" name="name" required>
							<small class="text-danger" id="nameError"></small>
						</div>
						<div class="mb-3">
							<label for="teacherEmail" class="form-label">Email</label>
							<input type="email" class="form-control" id="teacherEmail" name="email" required>
							<small class="text-danger" id="emailError"></small>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save Teacher</button>
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

		function resetTeacherForm() {
			document.getElementById('teacherForm').reset();
			document.getElementById('teacherId').value = '';
			document.getElementById('teacherModalLabel').textContent = 'Add Teacher';
			document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');
		}

		function setupFormSubmit() {
			const form = document.getElementById('teacherForm');
			if (form) {
				form.addEventListener('submit', function(e) {
					e.preventDefault();
					saveTeacher();
				});
			}
		}

		async function saveTeacher() {
			const teacherId = document.getElementById('teacherId').value;
			const url = teacherId ? `/teachers/${teacherId}` : '/teachers';

			const formData = {
				name: document.getElementById('teacherName').value,
				email: document.getElementById('teacherEmail').value,
				_token: '{{ csrf_token() }}'
			};

			if (teacherId) {
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
					const modalEl = document.getElementById('teacherModal');
					const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
					modal.hide();
					resetTeacherForm();
					showAlert('success', data.message);
					location.reload();
				} else if (data.errors) {
					Object.keys(data.errors).forEach(key => {
						const el = document.getElementById(`${key}Error`);
						if (el) el.textContent = data.errors[key][0];
					});
				}
			} catch (error) {
				showAlert('error', error.message || 'An error occurred');
			}
		}

		function editTeacher(id) {
			fetch(`/teachers/${id}/edit`, {
				method: 'GET',
				headers: { 'Accept': 'application/json' }
			})
			.then(response => response.json())
			.then(teacher => {
				document.getElementById('teacherId').value = teacher.id;
				document.getElementById('teacherName').value = teacher.name;
				document.getElementById('teacherEmail').value = teacher.email;
				document.getElementById('teacherModalLabel').textContent = 'Edit Teacher';
				document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');
				new bootstrap.Modal(document.getElementById('teacherModal')).show();
			})
			.catch(() => showAlert('error', 'Failed to load teacher'));
		}

		function deleteTeacher(id) {
			if (confirm('Are you sure you want to delete this teacher?')) {
				fetch(`/teachers/${id}`, {
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
