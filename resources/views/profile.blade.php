@extends('layouts.app')

@section('title', 'Profile')
@section('page-title', 'Profile')

@push('styles')
@endpush

@section('content')
	<!--begin::Row-->
		<!--begin::Basic info-->
		<div class="card mb-5 mb-xl-10">
			<!--begin::Card header-->
			<div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
				<!--begin::Card title-->
				<div class="card-title m-0">
					<h3 class="fw-bolder m-0">Profile Details</h3>
				</div>
				<!--end::Card title-->
			</div>
			<!--begin::Card header-->
			<!--begin::Content-->
			<div id="kt_account_profile_details" class="collapse show">
				<!--begin::Form-->
				<form id="kt_account_profile_details_form" class="form">
					<!--begin::Card body-->
					<div class="card-body border-top p-9">

						<!--begin::Input group-->
						<div class="row mb-6">
							<!--begin::Label-->
							<label class="col-lg-4 col-form-label required fw-bold fs-6">Name</label>
							<!--end::Label-->
							<!--begin::Col-->
							<div class="col-lg-8 fv-row">
								<!--begin::Row-->
									<input type="text" name="name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Name" value="{{ auth()->user()->name }}" />
								<!--end::Row-->
							</div>
							<!--end::Col-->
						</div>
						<!--end::Input group-->
						<!--begin::Input group-->
						<div class="row mb-6">
							<!--begin::Label-->
							<label class="col-lg-4 col-form-label required fw-bold fs-6">Email</label>
							<!--end::Label-->
							<!--begin::Col-->
							<div class="col-lg-8 fv-row">
								<input type="text" name="email" class="form-control form-control-lg form-control-solid" placeholder="Email address" value="{{ auth()->user()->email }}" />
							</div>
							<!--end::Col-->
						</div>
						<!--end::Input group-->
					</div>
					<!--end::Card body-->
					<!--begin::Actions-->
					<div class="card-footer d-flex justify-content-end py-6 px-9">
						<a href="{{ route('dashboard') }}" class="btn btn-light me-2">Discard</a>
						<button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Save Changes</button>
					</div>
					<!--end::Actions-->
				</form>
				<!--end::Form-->
			</div>
			<!--end::Content-->
		</div>
		<!--end::Basic info-->
	<!--end::Row-->
@endsection

@push('scripts')
@endpush
