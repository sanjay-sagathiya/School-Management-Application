@extends('layouts.app')

@section('title', 'Parents')
@section('page-title', 'Parents')

@section('content')
	<!--begin::Col-->
	<div class="col-xl-12">
		<!--begin::Tables Widget 9-->
		<div class="card card-xl-stretch mb-5 mb-xl-8">
			<!--begin::Header-->
			<div class="card-header border-0 pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">Parents</span>
				</h3>
				<div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="Click to add a parent">
					<button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#parentModal" onclick="resetParentForm()">
						<span class="svg-icon svg-icon-3">
							<!-- simple plus icon -->
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z" fill="#000"/></svg>
						</span>
						New Parent
					</button>
				</div>
			</div>
			<!--end::Header-->
			<!--begin::Body-->
			<div class="card-body py-3">
				<!--begin::Table container-->
				<div class="table-responsive">
					<!--begin::Table-->
					<table id="parentsTable" class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
						<!--begin::Table head-->
						<thead>
							<tr class="fw-bolder text-muted">
								<th class="min-w-150px">Name</th>
								<th class="min-w-200px">Email</th>
								@if(auth()->user()->role === 'admin')
									<th class="min-w-200px">Created By</th>
								@endif
								<th class="min-w-150px">Created</th>
								<th class="min-w-100px text-end">Actions</th>
							</tr>
						</thead>
						<!--end::Table head-->
						<!--begin::Table body-->
						<tbody>
						@foreach($parents as $parent)
							<tr>
								<td>{{ $parent->name }}</td>
								<td>{{ $parent->email }}</td>
								@if(auth()->user()->role === 'admin')
									<td>{{ $parent->teacher->name }}</td>
								@endif
								<td>{{ $parent->created_at?->format('Y-m-d') }}</td>
								<td class="text-end">
									<button class="btn btn-sm btn-light-primary" onclick="editParent({{ $parent->id }})">Edit</button>
									<button class="btn btn-sm btn-light-danger" onclick="deleteParent({{ $parent->id }})">Delete</button>
								</td>
							</tr>
						@endforeach
						</tbody>
						<!--end::Table body-->
					</table>
						<!--end::Table-->
						<div class="mt-3">
							{{ $parents->links('pagination::bootstrap-5') }}
						</div>
				</div>
				<!--end::Table container-->
			</div>
			<!--begin::Body-->
		</div>
		<!--end::Tables Widget 9-->
	</div>
	<!--end::Col-->

	<!-- Modal for Create/Edit Parent -->
	<div class="modal fade" id="parentModal" tabindex="-1" aria-labelledby="parentModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="parentModalLabel">Add Parent</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form id="parentForm">
					<div class="modal-body">
						<input type="hidden" id="parentId">
						@csrf
						<div class="mb-3">
							<label for="parentName" class="form-label">Name</label>
							<input type="text" class="form-control" id="parentName" name="name" required>
							<small class="text-danger" id="nameError"></small>
						</div>
						<div class="mb-3">
							<label for="parentEmail" class="form-label">Email</label>
							<input type="email" class="form-control" id="parentEmail" name="email" required>
							<small class="text-danger" id="emailError"></small>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save Parent</button>
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

		function resetParentForm() {
			document.getElementById('parentForm').reset();
			document.getElementById('parentId').value = '';
			document.getElementById('parentModalLabel').textContent = 'Add Parent';
			document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');
		}

		function setupFormSubmit() {
			const form = document.getElementById('parentForm');
			if (form) {
				form.addEventListener('submit', function(e) {
					e.preventDefault();
					saveParent();
				});
			}
		}

		async function saveParent() {
			const parentId = document.getElementById('parentId').value;
			const url = parentId ? `/parents/${parentId}` : '/parents';

			const formData = {
				name: document.getElementById('parentName').value,
				email: document.getElementById('parentEmail').value,
				_token: '{{ csrf_token() }}'
			};

			if (parentId) {
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
					const modalEl = document.getElementById('parentModal');
					const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
					modal.hide();
					resetParentForm();
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

		function editParent(id) {
			fetch(`/parents/${id}/edit`, {
				method: 'GET',
				headers: { 'Accept': 'application/json' }
			})
			.then(response => response.json())
			.then(parent => {
				document.getElementById('parentId').value = parent.id;
				document.getElementById('parentName').value = parent.name;
				document.getElementById('parentEmail').value = parent.email;
				document.getElementById('parentModalLabel').textContent = 'Edit Parent';
				document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');
				new bootstrap.Modal(document.getElementById('parentModal')).show();
			})
			.catch(() => showAlert('error', 'Failed to load parent'));
		}

		function deleteParent(id) {
			if (confirm('Are you sure you want to delete this parent?')) {
				fetch(`/parents/${id}`, {
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
