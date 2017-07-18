@push('scripts')
    <script src="/js/addpicture.js"></script>
@endpush

<form enctype="multipart/form-data" method="post">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="form-group">
        <input name="fileToUpload" class="file file-loading" type="file" data-allowed-file-extensions='["jpg", "png", "gif"]'>
    </div>
</form>