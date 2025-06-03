<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class FaqController extends Controller
{
    public function show()
    {
        $faqs = Faq::all();
        return view('home.faq', compact('faqs'));
    }

    public function sendHelpRequest(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        // Kirim email atau simpan ke database
        Mail::raw($request->message, function ($message) use ($request) {
            $message->to('support@costan.com') // ganti sesuai email pusat bantuan
                    ->subject('Permintaan Bantuan dari ' . $request->name)
                    ->replyTo($request->email);
        });

        return back()->with('success', 'Pertanyaan Anda telah dikirim!');
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        Faq::create($request->only(['question', 'answer']));

        return redirect()->route('faq.manage.index')->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function edit(Faq $manage)
    {
        return view('admin.faqs.edit', ['faq' => $manage]);
    }

    public function update(Request $request, Faq $manage)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        $manage->update($request->only(['question', 'answer']));

        return redirect()->route('faq.manage.index')->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroy(Faq $manage)
    {
        $manage->delete();
        return redirect()->route('faq.manage.index')->with('success', 'FAQ berhasil dihapus.');
    }

    public function index()
    {
        $faqs = Faq::orderByDesc('id')->get();
        return view('admin.faqs.index', compact('faqs'));
    }


}
