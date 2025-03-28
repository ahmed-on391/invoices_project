<?php

namespace App\Http\Controllers;

use App\Models\invoices_attachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;




class InvoicesAttachmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file_name' => 'mimes:pdf,jpeg,png,jpg',
        ], [
            'file_name.mimes' => 'صيغة المرفق يجب ان تكون pdf, jpeg , png , jpg',
        ]);
        
        if ($request->hasFile('file_name')) {
            $image = $request->file('file_name');
            $file_name = $image->getClientOriginalName();
        
            $attachments = new invoices_attachments();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $request->invoice_number;
            $attachments->invoice_id = $request->invoice_id;
            $attachments->Created_by = Auth::user()->name;
            $attachments->save();
               
            // نقل الصورة
            $image->move(public_path('Attachments/' . $request->invoice_number), $file_name);
            
            session()->flash('Add', 'تم اضافة المرفق بنجاح');
            return back();
        } else {
            return back()->with('error', 'لم يتم تحميل الملف بنجاح');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(invoices_attachments $invoices_attachments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(invoices_attachments $invoices_attachments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoices_attachments $invoices_attachments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(invoices_attachments $invoices_attachments)
    {
        //
    }
}
