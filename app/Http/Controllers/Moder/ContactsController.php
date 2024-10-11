<?php

namespace App\Http\Controllers\Moder;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactsRequest;
use App\Http\Requests\UpdateContactsRequest;
use App\Models\Contacts;
use Illuminate\Http\Request;

class ContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $res = null)
    {
        $contacts = [];
        if (!empty(Contacts::first())) {
            // $contacts = Contacts::paginate(10);
            $contacts = Contacts::select('id', 'type', 'data')->get();
        } else {
            $contacts = 'Empty table Contacts';
        }

        return view('admin_manikur.moder_pages.contacts', ['content' => $contacts, 'res' => $res]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin_manikur.moder_pages.contacts_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactsRequest $request)
    {
        $create = Contacts::create(['type' => $request->type, 'data' => $request->data]);

        return view('admin_manikur.moder_pages.contacts_store', ['res' => $create->attributesToArray()]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Contacts $contacts)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $contacts_ids = [];
        foreach ($request->contacts as $contact) {
            list($id, $type, $data) = explode('plusplus', $contact);
            $contact_data[] = [
                'id' => $id,
                'type' => $type,
                'data' => $data,
            ];
        }

        return view('admin_manikur.moder_pages.contacts_edit_form', ['contact_data' => $contact_data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactsRequest $request, Contacts $contacts)
    {
        if ($contacts::where('id', $request->id)->update(['type' => $request->type, 'data' => $request->data])) {
            return redirect()->route('admin.contacts.list')->with('res', 'Contacts data have been updated!');
        } else {
            return redirect()->route('admin.contacts.list')->with('res', 'Contacts data have been NOT updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contacts $contacts, Request $request)
    {
        $contacts_ids = [];
        foreach ($request->contacts as $contact) {
            $cd = explode('plusplus', $contact);
            $id = array_shift($cd);
            $contacts_ids[] = $id;
        }

        if ($contacts->destroy($contacts_ids)) {
            return redirect()->route('admin.contacts.list')->with('res', 'Contacts data have been removed!');
        } else {
            return redirect()->route('admin.contacts.list')->with('res', 'Contacts data have been NOT removed!');
        }
    }
}
