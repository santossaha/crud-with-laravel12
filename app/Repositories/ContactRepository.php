<?php

namespace App\Repositories;

use App\Models\Contact;

class ContactRepository implements ContactRepositoryInterface
{
    public function all()
    {
        return Contact::all();
    }

    public function find($id)
    {
        return Contact::find($id);
    }

    public function create(array $data)
    {
        return Contact::create($data);
    }

    public function update($id, array $data)
    {
        $contact = Contact::find($id);
        if ($contact) {
            $contact->update($data);
            return $contact;
        }
        return false;
    }

    public function delete($id)
    {
        $contact = Contact::find($id);
        return $contact ? $contact->delete() : false;
    }

    public function getForDataTable()
    {
        return Contact::select(['id', 'name', 'phone', 'created_at'])
            ->orderBy('created_at', 'desc');
    }
}
