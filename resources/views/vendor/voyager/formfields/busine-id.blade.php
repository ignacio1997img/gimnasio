{{-- <select
    class="form-control select2"
    name="{{ $row->field }}"
    data-name="{{ $row->display_name }}"
    @if($row->required == 1) required @endif
>
    <option value="{{ Auth::user()->id }}">{{ Auth::user()->name }}</option>
</select> --}}

{{-- <input type="text" name="{{ $row->field }}" data-name="{{ $row->display_name }}" value="{{ Auth::user()->bisune_id}}" name="busine_id" > --}}

<input @if($row->required == 1) required @endif type="hidden" class="form-control" name="{{ $row->field }}"
        placeholder="{{ old($row->field, $options->placeholder ?? $row->getTranslatedAttribute('display_name')) }}"
       {!! isBreadSlugAutoGenerator($options) !!}
       value="{{ Auth::user()->busine_id }}">