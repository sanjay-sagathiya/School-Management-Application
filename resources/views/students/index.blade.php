@extends('layouts.app')

@section('title', 'Students')
@section('page-title', 'Students')

@section('content')
	<!--begin::Col-->
	<div class="col-xl-12">
		<!--begin::Tables Widget 9-->
		<div class="card card-xl-stretch mb-5 mb-xl-8">
			<!--begin::Header-->
			<div class="card-header border-0 pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Students</span>
				</h3>
				<div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="Click to add a student">
					<button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#studentModal" onclick="resetStudentForm()">
						<span class="svg-icon svg-icon-3">
							<!-- simple plus icon -->
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z" fill="#000"/></svg>
						</span>
						New Student
					</button>
				</div>
			</div>
			<!--end::Header-->
			<!--begin::Body-->
			<div class="card-body py-3">
				<!--begin::Table container-->
				<div class="table-responsive">
					<!--begin::Table-->
					<table id="studentsTable" class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
						<!--begin::Table head-->
						<thead>
							<tr class="fw-bolder text-muted">
								<th class="min-w-150px">Name</th>
								<th class="min-w-200px">Email</th>
								@if(auth()->user()->isAdmin())
									<th class="min-w-200px">Created By</th>
								@endif
								<th class="min-w-150px">Created</th>
								<th class="min-w-100px text-end">Actions</th>
							</tr>
						</thead>
						<!--end::Table head-->
						<!--begin::Table body-->
						<tbody>
						@foreach($students as $student)
							<tr>
								<td>{{ $student->name }}</td>
								<td>{{ $student->email }}</td>
								@if(auth()->user()->isAdmin())
									<td>{{ $student->teacher->name }}</td>
								@endif
								<td>{{ $student->created_at?->format('Y-m-d') }}</td>
								<td class="text-end">
									<button class="btn btn-sm btn-light-primary" onclick="editStudent({{ $student->id }})">Edit</button>
									<button class="btn btn-sm btn-light-danger" onclick="deleteStudent({{ $student->id }})">Delete</button>
								</td>
							</tr>
						@endforeach
						</tbody>
						<!--end::Table body-->
					</table>
						<!--end::Table-->
						<div class="mt-3">
							{{ $students->links('pagination::bootstrap-5') }}
						</div>
				</div>
				<!--end::Table container-->
			</div>
			<!--begin::Body-->
		</div>
		<!--end::Tables Widget 9-->
	</div>
	<!--end::Col-->

	<!-- Modal for Create/Edit Student -->
	<div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="studentModalLabel">Add Student</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form id="studentForm">
					<div class="modal-body">
						<input type="hidden" id="studentId">
						@csrf
						<div class="mb-3">
							<label for="studentName" class="form-label">Name</label>
							<input type="text" class="form-control" id="studentName" name="name" required>
							<small class="text-danger" id="nameError"></small>
						</div>
						<div class="mb-3">
							<label for="studentEmail" class="form-label">Email</label>
							<input type="email" class="form-control" id="studentEmail" name="email" required>
							<small class="text-danger" id="emailError"></small>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save Student</button>
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

		function resetStudentForm() {
			document.getElementById('studentForm').reset();
			document.getElementById('studentId').value = '';
			document.getElementById('studentModalLabel').textContent = 'Add Student';
			document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');
		}

		function setupFormSubmit() {
			const form = document.getElementById('studentForm');
			if (form) {
				form.addEventListener('submit', function(e) {
					e.preventDefault();
					saveStudent();
				});
			}
		}

		async function saveStudent() {
			const studentId = document.getElementById('studentId').value;
			const url = studentId ? `/students/${studentId}` : '/students';

			const formData = {
				name: document.getElementById('studentName').value,
				email: document.getElementById('studentEmail').value,
				_token: '{{ csrf_token() }}'
			};

			if (studentId) {
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
					const modalEl = document.getElementById('studentModal');
					const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
					modal.hide();
					resetStudentForm();
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

		function editStudent(id) {
			fetch(`/students/${id}/edit`, {
				method: 'GET',
				headers: { 'Accept': 'application/json' }
			})
			.then(response => response.json())
			.then(student => {
				document.getElementById('studentId').value = student.id;
				document.getElementById('studentName').value = student.name;
				document.getElementById('studentEmail').value = student.email;
				document.getElementById('studentModalLabel').textContent = 'Edit Student';
				document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');
				new bootstrap.Modal(document.getElementById('studentModal')).show();
			})
			.catch(() => showAlert('error', 'Failed to load student'));
		}

		function deleteStudent(id) {
			if (confirm('Are you sure you want to delete this student?')) {
				fetch(`/students/${id}`, {
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
