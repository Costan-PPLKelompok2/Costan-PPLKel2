<p>Halo {{ $recipientName }},</p>
<p>Anda menerima pesan baru dari <strong>{{ $senderName }}</strong> terkait kos <strong>{{ $kosName }}</strong>:</p>
<blockquote style="border-left: 4px solid #ccc; padding-left: 1em; margin-left: 1em; color: #555;">
    {{ $messageBody }}
</blockquote>
<p>
    <a href="{{ $chatLink }}">Klik di sini untuk melihat dan membalas pesan</a>.
</p>
<p>Terima kasih</p>