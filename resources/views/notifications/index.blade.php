@extends('layouts.app')

@section('title', 'Notifications')
@section('page-title', 'All Notifications')

@section('content')
	<div class="col-xl-12">
		<div class="card card-xl-stretch mb-5 mb-xl-8">
			<div class="card-header border-0 pt-5">
				<h3 class="card-title align-items-start flex-column">
					<span class="card-label fw-bolder fs-3 mb-1">All Notifications</span>
				</h3>
			</div>
			<div class="card-body py-3">
				<div class="table-responsive">
					<table id="notificationsTable" class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
						<thead>
							<tr class="fw-bolder text-muted">
								<th class="min-w-150px">Subject</th>
								<th class="min-w-350px">Message</th>
								<th class="min-w-100px text-end">Actions</th>
							</tr>
						</thead>
						<tbody>
						@foreach($notifications as $notification)
							<tr class="{{ $notification->unread() ? 'table-warning' : '' }}">
								<td class="{{ $notification->unread() ? 'fw-bold' : '' }}">{{ $notification->data['subject'] }}</td>
								<td class="{{ $notification->unread() ? 'fw-bold' : '' }}">{{ $notification->data['description'] }}</td>
								<td class="text-end">
									@if ($notification->unread())
										<button class="btn btn-sm btn-light-primary" onclick="readNotification('{{ $notification->id }}')">Read</button>
									@endif
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
					<div class="mt-3">{{ $notifications->links('pagination::bootstrap-5') }}</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('scripts')

<script>
	function readNotification(id) {
		fetch(`/notifications/${id}/read`, {
			method: 'POST',
			headers: {
				'X-CSRF-TOKEN': '{{ csrf_token() }}',
				'Accept': 'application/json',
			},
		})
		.then(response => {
			if (response.ok) {
				location.reload();
			} else {
				alert('Failed to mark notification as read.');
			}
		})
		.catch(error => {
			console.error('Error:', error);
			alert('An error occurred.');
		});
	}
</script>
@endpush
