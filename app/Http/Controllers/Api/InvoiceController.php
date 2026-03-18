<?php

namespace App\Http\Controllers\Api;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InvoiceController extends Controller
{
    public function upload(Request $request)
    {
        // Validate the request
        $request->validate([
            'invoice' => 'required|file|mimes:pdf|max:2048', // Adjust validation as needed
        ]);

        // Check if the file is present
        if ($request->hasFile('invoice')) {
            try {
                // Retrieve the file from the request
                $file = $request->file('invoice');

                // Generate a unique filename
                $filename = time() . '.' . $file->getClientOriginalExtension();

                // Store the file in the 'invoices' directory
                $path = $file->storeAs('public/invoices', $filename);

                // Save file info to the database
                $invoice = new Invoice();
                $invoice->filename = $filename;
                $invoice->path = $path;
                $invoice->save();

                // Return a success response
                return response()->json([
                    'message' => 'Invoice uploaded successfully',
                    'invoice' => $invoice
                ], 201);

            } catch (\Exception $e) {
                // Handle any errors that occur during the upload process
                return response()->json([
                    'message' => 'Failed to upload invoice',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        // Return an error response if no file was uploaded
        return response()->json([
            'message' => 'No file uploaded'
        ], 400);
    }

}

