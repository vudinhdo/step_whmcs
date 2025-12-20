<?php

namespace App\Http\Controllers;

use App\Models\ProductInquiry;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function show(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        return view('public.contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'phone'   => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10'],
        ]);

        ProductInquiry::create($data);

        return back()->with('status', 'Cảm ơn bạn! Chúng tôi đã nhận được yêu cầu và sẽ liên hệ sớm.');
    }
}
