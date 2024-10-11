@php
$title = "Form for search client";
$page_meta_description = "Form for search client";
$page_meta_keywords = "Form, search, client";
$robots = "NOINDEX, NOFOLLOW";
$uv = '';
@endphp
@extends("layouts/index_admin")

@section("content")

<div class="content" id="by_client">
    <p class="margin+tp+1rem">Search client by phone number:</p>
    <form action="{{url()->route('admin.signup.by_client.post')}}" method="post" class="form-recall-main" id="find_client">
    @csrf
        <div class="">
            <input type="text" placeholder="Ваша фамилия" name="last_name" id="last_name" maxlength="50" />
            <div class="form-group padt1">
                <input list="phone_numbers" type="tel" name="phone_number" id="client_phone_numbers"
                        title="Формат: +7 999 999 99 99" placeholder="+7 999 999 99 99"
                        minlength="6" maxlength="17"
                        pattern="^(\+?(7|8|38))[ ]{0,1}s?[\(]{0,1}?\d{3}[\)]{0,1}s?[\- ]{0,1}s?\d{1}[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?\d{1}s?[\- ]{0,1}?"
                        value="{{ old('phone_number') }}"
                        required />
                <datalist id="phone_numbers">
                    @foreach ($data['by_client'] as $client)
                        <option value="{{$client['phone']}}" id="{{$client['id']}}">
                    @endforeach
                </datalist>
            </div>

            <div class="form-group pad" id="sr_but">
                <button class="buttons" id="submit_by_client">Submit</button>
                <button class="buttons" type="reset">Reset</button>
                <input type="hidden" name="client_id" id="by_client_client_id" />
            </div>
        </div>
    </form>
</div>
<script>
window.onload = function() {
    let by_client = document.querySelector('#by_client');
    if (!!by_client) {
        let by_client_submit = document.querySelector('#submit_by_client');
        if (!!by_client_submit) {
            by_client_submit.addEventListener('click', function () {
                event.preventDefault();
                let input = document.querySelector("#client_phone_numbers");
                let client = document.querySelector('option[value="'+input.value+'"]');
                let last_name = document.querySelector('#last_name').value ?? '';
                if (!!input && !!client && client.id && last_name == '') {
                    document.querySelector('#by_client_client_id').value = client.id;
                    document.querySelector('#client_phone_numbers').remove();
                    document.querySelector('#find_client').submit();
                }

            }, false);
        }
    }
}
</script>
@stop
