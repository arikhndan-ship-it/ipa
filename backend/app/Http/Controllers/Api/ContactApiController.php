<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactApiController extends Controller
{
    public function send(Request $request)
    {
        // Set locale from Accept-Language header
        $locale = $request->header('Accept-Language', 'ckb');
        app()->setLocale(in_array($locale, ['en', 'ckb']) ? $locale : 'ckb');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Store in database
        Contact::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'locale' => $locale,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('messages.message_sent'),
        ]);
    }
}
