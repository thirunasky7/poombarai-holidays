<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function index()
    {
        return view('admin.enquiries.index', [
            'enquiries' => Enquiry::query()->latest()->paginate(20),
        ]);
    }

    public function show(Enquiry $enquiry)
    {
        return view('admin.enquiries.show', ['enquiry' => $enquiry]);
    }

    public function update(Request $request, Enquiry $enquiry)
    {
        $data = $request->validate([
            'status' => ['required', 'in:new,pending,confirmed,closed'],
            'internal_note' => ['nullable', 'string', 'max:2000'],
        ]);
        $enquiry->update($data);

        return back()->with('status', 'Enquiry updated.');
    }
}
