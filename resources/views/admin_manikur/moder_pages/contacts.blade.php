<?php
$url = url()->current();
if (str_contains($url, 'admin/contacts/remove')) {
    $res_route = route('admin.contacts.destroy');
    $remove_or_edit = true;
    $buttonname = 'Remove';
    $type = 'checkbox';
} elseif (str_contains($url, 'admin/contacts/edit')) {
    $res_route = route('admin.contacts.post_edit');
    $remove_or_edit = true;
    $buttonname = 'Edit';
    $type = 'radio';
} else {
    $buttonname = 'list';
    $remove_or_edit = false;
}

$title = 'Contacts '.$buttonname;
$page_meta_description = 'admins page, Contacts editing';
$page_meta_keywords = 'contacts, edit';
$robots = 'NOINDEX, NOFOLLOW';
?>

@extends('layouts/index_admin')
@section('content')
    @if (!empty($res)) <p class="content">MESSAGE: {{$res}}</p>

    @else

    <div class="content margintb1 ">
        <div class="price">
            @if (Auth::user()['status']==='admin' || Auth::user()['status']==='moder')
                @if (is_string($content))
                    {{$content}}
                @else

                    @if ( $remove_or_edit )
                        <form method="post" action="{{ $res_route }}" id="contacts_form" class="pad">
                            @csrf

                            <div class="form-element margintb1">
                                <table class="table" id="ctable">
                                    <thead>
                                    <tr>
                                        <th>N</th>
                                        <th>Type</th>
                                        <th>Data</th>
                                        <th>{{$buttonname}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($content as $key => $contact)

                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$contact->type}}</td>
                                        <td>{{$contact->data}}</td>
                                        <td class="text_center" style="padding: 0;">
                                            <input type="{{$type}}" name="contacts[]" value="{{$contact->id.'plusplus'.$contact->type.'plusplus'.$contact->data}}">
                                        </td>
                                    </tr>

                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-element mar">
                                <button type="submit" form="contacts_form" class="buttons" id="contacts_submit">{{$buttonname}}</button>
                                <button type="reset" form="contacts_form" class="buttons" id="contacts_reset">Reset</button>
                            </div>
                        </form>
                    @else
                        <table class="table">
                            <tr>
                                <th>N</th>
                                <th>Type</th>
                                <th>Data</th>
                            </tr>
                            @foreach ($content as $key => $contact)

                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$contact->type}}</td>
                                    <td>{{$contact->data}}</td>
                                </tr>

                            @endforeach
                        </table>
                    @endif
                @endif
            @else
            You are not authorized.
            @endif
        </div>

    </div>
    @endif
@stop

<script>
document.addEventListener("DOMContentLoaded", function() {
    let SUBM = document.querySelector('#contacts_submit');
    if (SUBM) {
        SUBM.disabled = true;

        let table = document.querySelector('#ctable');
        let edit = '<?php echo $buttonname; ?>';

        for(let i = 1; i < table.rows.length; i++)
        {
            table.rows[i].onclick = function()
            {
                //rIndex = this.rowIndex;
                /*
                document.getElementById("fname").value = this.cells[0].innerHTML;
                document.getElementById("lname").value = this.cells[1].innerHTML;
                document.getElementById("age").value = this.cells[2].innerHTML;
                */
                if ( edit == 'Edit') {
                    for(let i = 1; i < table.rows.length; i++)
                    {
                        table.rows[i].removeAttribute("style");
                    }
                }

                let input = this.cells[3].children[0];

                if (this.style.color == 'red') {
                    input.removeAttribute('checked');
                    this.removeAttribute("style");
                } else {
                    this.style.color = 'red';
                    input.setAttribute('checked', 'checked');
                }

                SUBM.disabled = false;
            };
        }

        document.querySelector('#contacts_reset').onclick = function () {
            for (let i = 1; i < table.rows.length; i++)
            {
                let input = table.rows[i].cells[3].children[0];
                input.removeAttribute('checked');
                table.rows[i].removeAttribute("style");
            }
        }
    }
});
</script>
