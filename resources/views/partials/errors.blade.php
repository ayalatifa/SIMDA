@if ($errors->any())
    <div class="alert alert-error" role="alert"><x-icon name="alert" :size="18" /><div><strong>Periksa kembali data yang dimasukkan.</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div></div>
@endif
