<script>

    function imagePreview(input,imageTagClass){
            // var input = this;
            var url = $(input).val();
            var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
            if (input.files && input.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg"))
            {
                var reader = new FileReader();

                reader.onload = function (e) {
                $(imageTagClass).attr('src', e.target.result);
                $(imageTagClass).show();
                }
            reader.readAsDataURL(input.files[0]);
            }
            else
            {
            $(imageTagClass).attr('src', '/assets/no_preview.png');
            }
    }


    setTimeout(() => {
        $.ajax({
            type: "GET",
            url: "{{route('makeLastLoginAtColumnNull')}}",
            success: function (response) {

            }
        });
    },600000 ); // 10 minutes

    window.addEventListener('beforeunload', function (e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: "{{route('makeLastLoginAtColumnNull')}}",
            success: function (response) {
            }
        });

    });


</script>
