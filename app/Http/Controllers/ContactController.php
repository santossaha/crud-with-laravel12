<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Repositories\ContactRepositoryInterface;
use Illuminate\Http\JsonResponse;
use SimpleXMLElement;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreContactRequest;

class ContactController extends Controller
{
    protected $contactRepository;

    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function index()
    {
        return view('contacts.index');
    }

    public function import(Request $request)
    {
        $request->validate([
            'xml_file' => 'required|mimes:xml|max:2048'
        ]);

        try {
            $file = $request->file('xml_file');

            $xmlContent = file_get_contents($file->getPathname());
            $xml = new SimpleXMLElement($xmlContent);


            $importedCount = 0;
            $errors = [];

            foreach ($xml->contact as $contact) {
                try {
                    $name = (string) $contact->name;
                    $phone = (string) $contact->phone;

                    if (!empty($name) && !empty($phone)) {
                        $this->contactRepository->create([
                            'name' => $name,
                            'phone' => $phone
                        ]);
                        $importedCount++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Error processing contact: " . $e->getMessage();
                }
            }

            $message = "Successfully imported {$importedCount} contacts";
            if (!empty($errors)) {
                $message .= ". Errors: " . implode(', ', $errors);
            }

            return redirect()->route('contacts.index')->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->route('contacts.index')->with('error', 'Error importing XML file: ' . $e->getMessage());
        }
    }

    public function list(Request $request): JsonResponse
    {
        $query = $this->contactRepository->getForDataTable();

        return DataTables::of($query)
            ->addColumn('action', function($contact){
                return view('contacts.partials.actions', compact('contact'))->render();
            })

            ->toJson();
    }

    public function store(StoreContactRequest $request): JsonResponse
    {
        try {
        $contact = $this->contactRepository->create($request->only(['name', 'phone']));
        return Helper::response(true, 'Contact created successfully',  $contact);
        } catch (\Exception $e) {
            return Helper::response(false, 'Error creating contact: ' . $e->getMessage(), null, 500);
        }
    }

    public function show($id): JsonResponse
    {
        $contact = $this->contactRepository->find($id);
        if (!$contact) {
            return Helper::response(false, 'Contact not found',  null, 404);
        }
        return Helper::response(true, 'Contact created successfully',  $contact);
    }

    public function update(StoreContactRequest $request, $id): JsonResponse
    {
        try {
        $contact = $this->contactRepository->update($id, $request->only(['name', 'phone']));
            if (!$contact) {
                return Helper::response(false, 'Contact not found', null, 404);
            }
            return Helper::response(true, 'Contact updated successfully',  $contact);
        } catch (\Exception $e) {
            return Helper::response(false, 'Error creating updating: ' . $e->getMessage(), null, 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $deleted = $this->contactRepository->delete($id);

            if (!$deleted) {
                return Helper::response(false, 'Contact not found', null, 404);
            }
            return Helper::response(true, 'Contact deleted successfully',  null);
        } catch (\Exception $e) {
            return Helper::response(false, 'Error deleting contact: ' . $e->getMessage(), null, 500);
        }
    }
}
