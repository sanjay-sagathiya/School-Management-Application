<!DOCTYPE html>

<html lang="en">
	<!--begin::Head-->
<head><base href="">
	<meta charset="utf-8" />
		<title>@yield('title', 'SMA')</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="shortcut icon" href="{{ asset("assets/media/logos/favicon.ico") }}" />
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->

		<!--begin::Global Stylesheets Bundle(used by all pages)-->
		<link href="{{ asset("assets/plugins/global/plugins.bundle.css") }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset("assets/css/style.bundle.css") }}" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
		@stack('styles')
	</head>
	<!--end::Head-->

	<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed toolbar-tablet-and-mobile-fixed aside-enabled aside-fixed" style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
		<!--begin::Main-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Page-->
			<div class="page d-flex flex-row flex-column-fluid">
				@include('partials.aside')
				<!--begin::Wrapper-->
				<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
					@include('partials.header')
					<!--begin::Content-->
					<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
						@include('partials.toolbar')
						<!--begin::Container-->
						<div class="post d-flex flex-column-fluid" id="kt_post">
							<!--begin::Post-->
							<div id="kt_content_container" class="container-fluid">
								@yield('content')
							</div>
							<!--end::Post-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Content-->
					@include('partials.footer')
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::Root-->

		<!--begin::Javascript-->
			<!--begin::Global Javascript Bundle(used by all pages)-->
			<script src="{{ asset("assets/plugins/global/plugins.bundle.js") }}"></script>
			<script src="{{ asset("assets/js/scripts.bundle.js") }}"></script>
			<!--end::Global Javascript Bundle-->

			<!--begin::Page Custom Javascript(used by this page)-->
			@stack('scripts')
			<!--end::Page Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>
