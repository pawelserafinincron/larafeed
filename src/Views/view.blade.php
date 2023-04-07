@if (config('larafeed.enabled') && !app()->request->is(config('larafeed.ignore_paths', [])))
    <link rel="stylesheet" href="{{ asset('vendor/larafeed/css/larafeed.css') }}">
    <script src="{{ asset('vendor/larafeed/js/html2canvas.min.js') }}"></script>

    <button data-html2canvas-ignore type="button"
        class="larabtn larafeed_button_blue larafeed_button btn btn-link bg-hover-info text-hover-white text-muted py-0 mx-4">
        <i class="las la-envelope"></i> @lang('Help us improving system')
    </button>

    <!-- Modal -->
    <div class="larafeed_modal shadow fs-6" data-html2canvas-ignore>
        <form id="feedback_form" action="{{ route('larafeed_store') }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="larafeed_modal_content p-4">
                <div class="larafeed_modal_title">
                    <strong>@lang('Send your feedback')</strong>
                </div>

                <input type="hidden" class="larafeed_control" name="name" id="name"
                    value="{{ larafeedUser()->name }}" />

                <input type="hidden" class="larafeed_control" name="email" id="email"
                    value="{{ larafeedUser()->email }}" />

                <div class="larafeed_field">
                    <textarea class="larafeed_control autogrow" name="message" id="message" style="height: 130px;" required
                        rows="5"></textarea>
                </div>

                <input type="hidden" name="ip" value="{{ request()->ip() }}">
                <input type="hidden" name="uri" value="{{ request()->fullUrl() }}">
                <input type="hidden" name="screenshot" id="screenshot" value="">
                <input type="hidden" name="browser" id="browser"
                    value="{{ (larafeedGetBrowser()->getBrowser() ?: 'Unknown') . ' (' . larafeedGetBrowser()->getVersion() . ')' }}">
                <input type="hidden" name="os" id="os"
                    value="{{ larafeedGetBrowser()->getPlatform() ?: 'Unknown' }}">

                <div class="pull-right">
                    <button type="button"
                        class="larabtn larafeed_button_close btn btn-default">@lang('Close')</button>
                    <button type="submit" id="feedback_submit"
                        class="larabtn larafeed_button_blue btn btn-primary">@lang('Send Feedback')
                    </button>
                </div>
                <div class="clear"></div>

            </div>
        </form>
    </div>
    <!-- /Modal -->

    <script>
        var modal = document.querySelector(".larafeed_modal");

        function toggleModal() {
            modal.classList.toggle("larafeed_show_modal");
        }

        document.querySelector(".larafeed_button_close").addEventListener("click", toggleModal);
        document.querySelector(".larafeed_button").addEventListener("click", toggleModal);

        document.addEventListener("click", function(event) {
            if (event.target === modal) {
                toggleModal();
            }
        });

        document.querySelector("#feedback_form").addEventListener("submit", function(event) {
            var $this = this;
            var options = {
                //backgroundColor: null,
                imageTimeout: 0,
                logging: false,
            };

            event.preventDefault();

            document.querySelector("#feedback_submit").disabled = true;
            document.querySelector("#feedback_submit").innerHTML = "Please wait";

            setTimeout(function() {
                html2canvas(document.querySelector(
                    "{{ config('larafeed.screenshots.screenshot_selector', 'body') }}"), options).then(
                    canvas => {
                        document.querySelector("#screenshot").value = canvas.toDataURL("image/png");
                        $this.submit();
                    });
            }, 100);
        });
    </script>
@endif
