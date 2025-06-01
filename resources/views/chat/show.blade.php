{{-- resources/views/chat/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-screen-md mx-auto px-4 py-6 flex flex-col h-[calc(100vh-100px)]"> {{-- Batasi lebar container --}}
    <div class="flex flex-col bg-white rounded-lg shadow-xl overflow-hidden h-full">
        @php
            $otherParticipant = $chatRoom->tenant_id == $currentUser->id ? $chatRoom->owner : $chatRoom->tenant;
        @endphp

        {{-- Header Chat --}}
        <div class="bg-white shadow-sm p-4 border-b border-gray-200">
            <div class="flex items-center">
                <a href="{{ route('chat.index') }}" class="text-blue-600 hover:text-blue-800 mr-3 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <img class="h-10 w-10 rounded-full object-cover mr-2"
                     src="{{ $otherParticipant->profile_photo_url ?? asset('images/default-avatar.png') }}"
                     alt="Foto {{ $otherParticipant->name }}">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ $otherParticipant->name }}</h2>
                    @if($chatRoom->kos)
                        <p class="text-sm text-gray-600">{{ $chatRoom->kos->nama_kos }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Area Pesan --}}
        <div id="messageArea" class="flex-grow bg-gray-50 p-4 overflow-y-auto space-y-4 min-h-[200px]">
            @forelse ($messages as $message)
                <div data-message-container-id="{{ $message->id }}">
                    <div class="message-content-wrapper flex items-center {{ $message->sender_id == $currentUser->id ? 'justify-end' : 'justify-start' }}">

                        @if ($message->sender_id == $currentUser->id)
                            <div class="relative mr-2">
                                <button onclick="toggleMessageActions(event, '{{ $message->id }}')" class="p-1 text-sky-600 hover:text-sky-800 rounded-full focus:outline-none transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </button>
                                <div id="actions-dropdown-{{ $message->id }}" class="message-actions-dropdown hidden absolute right-0 mt-2 w-32 bg-white rounded-md shadow-lg z-20 border border-gray-200 dark:bg-gray-700 dark:border-gray-600">
                                    <button onclick="showEditForm('{{ $message->id }}'); closeAllActionDropdowns();" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600 transition-colors">
                                        Edit
                                    </button>
                                    <button onclick="deleteMessage('{{ $message->id }}');" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100 dark:hover:bg-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        @endif

                        <div class="message-bubble max-w-xs lg:max-w-md px-4 py-2 rounded-lg shadow {{ $message->sender_id == $currentUser->id ? 'bg-sky-100 text-sky-800' : 'bg-gray-200 text-gray-900' }}">
                            @if($message->sender_id != $currentUser->id && $chatRoom->owner_id != $chatRoom->tenant_id)
                                <p class="text-xs font-semibold mb-1 text-indigo-600">{{ $message->sender->name }}</p>
                            @elseif($message->sender_id != $currentUser->id)
                                <p class="text-xs font-semibold mb-1 text-gray-600">{{ $message->sender->name }}</p>
                            @endif
                            <p class="message-body text-sm break-words whitespace-pre-wrap">{{ $message->body }}</p>
                            <p class="message-time-status text-xs mt-1 {{ $message->sender_id == $currentUser->id ? 'text-sky-600' : 'text-gray-500' }} text-right flex items-center justify-end">
                                @if ($message->edited_at)
                                    <span class="italic mr-1">(diedit)</span>
                                @endif
                                <span>{{ $message->created_at->format('H:i') }}</span>
                                @if ($message->sender_id == $currentUser->id)
                                     @if ($message->read_at)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-sky-500" viewBox="0 0 20 20" fill="currentColor" title="Dibaca pada {{ \Carbon\Carbon::parse($message->read_at)->isoFormat('D MMM HH:mm') }}">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" transform="translate(-4, 0)" />
                                        </svg>
                                     @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-400" viewBox="0 0 20 20" fill="currentColor" title="Terkirim">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                     @endif
                                @endif
                            </p>
                        </div>
                    </div>
                    @if ($message->sender_id == $currentUser->id)
                    <div id="edit-form-{{ $message->id }}" class="edit-form-container hidden my-1 flex justify-end">
                         <div class="w-full max-w-xs lg:max-w-md">
                            <textarea class="w-full border border-gray-300 rounded-md p-2 text-sm bg-white text-gray-800 focus:ring-blue-500 focus:border-blue-500" rows="2">{{ $message->body }}</textarea>
                            <div class="text-right mt-1 space-x-2">
                                <button onclick="cancelEdit('{{ $message->id }}')" class="text-xs py-1 px-2.5 text-gray-600 hover:text-gray-800 rounded-md hover:bg-gray-200 transition-colors">Batal</button>
                                <button onclick="submitEdit('{{ $message->id }}')" class="text-xs py-1 px-3 bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors">Simpan</button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            @empty
                <div class="text-center text-gray-500 py-16">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                       <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                     </svg>
                     <p class="mt-4 text-lg">Belum ada pesan di chat ini.</p>
                     <p class="text-sm text-gray-400">Mulai percakapan sekarang!</p>
                </div>
            @endforelse
        </div>

        {{-- Input Area --}}
        <div class="bg-white p-4 border-t border-gray-200 w-full rounded-b-lg">
            <form action="{{ route('chat.store', $chatRoom) }}" method="POST" id="sendMessageForm" class="w-full">
                @csrf
                <div class="flex items-stretch w-full">
                    <input type="text" name="body" id="messageInput"
                           class="flex-grow border border-gray-300 rounded-l-md py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm text-gray-800 placeholder-gray-400 border-r-0 w-full"
                           placeholder="Ketik pesan Anda..." autocomplete="off">
                    <button type="submit" id="sendButton"
                            class="bg-blue-500 hover:bg-blue-600 text-gray font-semibold py-3 px-4 rounded-r-md transition-colors duration-150 focus:outline-none">
                        Kirim
                    </button>
                </div>
                @error('body')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </form>
        </div>
    </div>
</div>

<div id="toast-notification" class="fixed bottom-5 right-5 bg-gray-700 text-white py-2 px-4 rounded-lg shadow-md text-sm opacity-0 transition-opacity duration-300 z-50">
    Pesan notifikasi!
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const messageArea = document.getElementById('messageArea');
    const sendMessageForm = document.getElementById('sendMessageForm');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const currentUserId = {{ $currentUser->id }};
    const chatRoomId = {{ $chatRoom->id }};

    function scrollToBottom(behavior = 'smooth') {
        setTimeout(() => {
            if(messageArea) {
                messageArea.scrollTo({ top: messageArea.scrollHeight, behavior: behavior });
            }
        }, 50);
    }

    if (messageArea && messageArea.querySelector('[data-message-container-id]')) {
        scrollToBottom('auto');
    }

    if(sendMessageForm) {
        sendMessageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const messageBody = messageInput.value.trim();
            if (!messageBody) return;

            const originalButtonText = sendButton.textContent;
            sendButton.disabled = true;
            sendButton.innerHTML = `<svg class="animate-spin h-5 w-5 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"> <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle> <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path> </svg>`;

            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success && data.message) {
                    messageInput.value = '';
                    appendMessageToUI(data.message);
                    scrollToBottom();
                } else {
                    showToast('Gagal mengirim pesan: ' + (data.error || data.message || 'Error tidak diketahui'), 'error');
                }
            })
            .catch(error => {
                console.error('Send message error:', error);
                showToast('Terjadi kesalahan jaringan.', 'error');
            })
            .finally(() => {
                sendButton.disabled = false;
                sendButton.innerHTML = originalButtonText;
                messageInput.focus();
            });
        });
    }

    function escapeHtml(unsafe) {
        if (typeof unsafe !== 'string') return '';
        return unsafe.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }

    function formatTime(dateString) {
        const date = new Date(dateString);
        return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    }

    function formatReadAt(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString('id-ID', { day:'numeric', month:'short', hour:'2-digit', minute:'2-digit'});
    }

    function appendMessageToUI(message, prepend = false) {
        if(!messageArea) return;

        const isCurrentUserSender = message.sender_id == currentUserId;
        let senderNameHtml = '';
        if (!isCurrentUserSender && message.sender && ({{ $chatRoom->owner_id }} != {{ $chatRoom->tenant_id }})) {
             senderNameHtml = `<p class="text-xs font-semibold mb-1 text-indigo-600">${escapeHtml(message.sender.name)}</p>`;
        } else if (!isCurrentUserSender && message.sender) {
             senderNameHtml = `<p class="text-xs font-semibold mb-1 text-gray-600">${escapeHtml(message.sender.name)}</p>`;
        }

        let editedStatusHtml = '';
        if (message.edited_at) {
             editedStatusHtml = `<span class="italic mr-1">(diedit)</span>`;
        }

        let readStatusHtml = '';
        if (isCurrentUserSender) {
            if (message.read_at) {
                 readStatusHtml = `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-sky-500" viewBox="0 0 20 20" fill="currentColor" title="Dibaca pada ${formatReadAt(message.read_at)}"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" transform="translate(-4, 0)" /></svg>`;
            } else {
                 readStatusHtml = `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-400" viewBox="0 0 20 20" fill="currentColor" title="Terkirim"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>`;
            }
        }
        
        const canBeModified = typeof message.can_be_modified !== 'undefined' ? message.can_be_modified : isCurrentUserSender;

        let kebabButtonHtml = '';
        if (isCurrentUserSender && canBeModified) {
            kebabButtonHtml = `
            <div class="relative mr-2">
                <button onclick="toggleMessageActions(event, '${message.id}')" class="p-1 text-sky-600 hover:text-sky-800 rounded-full focus:outline-none transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                    </svg>
                </button>
                <div id="actions-dropdown-${message.id}" class="message-actions-dropdown hidden absolute right-0 mt-2 w-32 bg-white rounded-md shadow-lg z-20 border border-gray-200 dark:bg-gray-700 dark:border-gray-600">
                    <button onclick="showEditForm('${message.id}'); closeAllActionDropdowns();" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600 transition-colors">
                        Edit
                    </button>
                    <button onclick="deleteMessage('${message.id}');" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100 dark:hover:bg-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                        Hapus
                    </button>
                </div>
            </div>`;
        }

        const messageBubbleContentHtml = `
            <div class="message-bubble max-w-xs lg:max-w-md px-4 py-2 rounded-lg shadow ${isCurrentUserSender ? 'bg-sky-100 text-sky-800' : 'bg-gray-200 text-gray-900'}">
                ${senderNameHtml}
                <p class="message-body text-sm break-words whitespace-pre-wrap">${escapeHtml(message.body)}</p>
                <p class="message-time-status text-xs mt-1 ${isCurrentUserSender ? 'text-sky-600' : 'text-gray-500'} text-right flex items-center justify-end">
                    ${editedStatusHtml}
                    <span>${formatTime(message.created_at)}</span>
                    ${readStatusHtml}
                </p>
            </div>`;
        
        let finalMessageHtmlInsideWrapper = '';
        if (isCurrentUserSender) {
            finalMessageHtmlInsideWrapper = kebabButtonHtml + messageBubbleContentHtml;
        } else {
            finalMessageHtmlInsideWrapper = messageBubbleContentHtml;
        }

        const messageContentWrapperDiv = document.createElement('div');
        messageContentWrapperDiv.className = `message-content-wrapper flex items-center ${isCurrentUserSender ? 'justify-end' : 'justify-start'}`;
        messageContentWrapperDiv.innerHTML = finalMessageHtmlInsideWrapper;

        const editFormHtml = (isCurrentUserSender && canBeModified) ? `
            <div id="edit-form-${message.id}" class="edit-form-container hidden my-1 ${isCurrentUserSender ? 'flex justify-end' : 'flex justify-start'}">
                 <div class="w-full max-w-xs lg:max-w-md">
                    <textarea class="w-full border border-gray-300 rounded-md p-2 text-sm bg-white text-gray-800 focus:ring-blue-500 focus:border-blue-500" rows="2">${escapeHtml(message.body)}</textarea>
                    <div class="text-right mt-1 space-x-2">
                        <button onclick="cancelEdit('${message.id}')" class="text-xs py-1 px-2.5 text-gray-600 hover:text-gray-800 rounded-md hover:bg-gray-200 transition-colors">Batal</button>
                        <button onclick="submitEdit('${message.id}')" class="text-xs py-1 px-3 bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors">Simpan</button>
                    </div>
                </div>
            </div>` : '';

        const messageContainerDiv = document.createElement('div');
        messageContainerDiv.setAttribute('data-message-container-id', message.id);
        messageContainerDiv.appendChild(messageContentWrapperDiv);
        
        if (editFormHtml) {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = editFormHtml.trim();
            if (tempDiv.firstChild) { // Pastikan ada elemen sebelum append
                 messageContainerDiv.appendChild(tempDiv.firstChild);
            }
        }

        const emptyMessagePlaceholder = messageArea.querySelector('.text-center.text-gray-500.py-16');
        if (emptyMessagePlaceholder) {
            emptyMessagePlaceholder.remove();
        }

        if (prepend) {
            messageArea.insertBefore(messageContainerDiv, messageArea.firstChild);
        } else {
            messageArea.appendChild(messageContainerDiv);
        }
    }

    window.toggleMessageActions = function(event, messageId) {
        event.stopPropagation(); 
        const targetDropdown = document.getElementById(`actions-dropdown-${messageId}`);
        if (!targetDropdown) return;

        document.querySelectorAll('.message-actions-dropdown').forEach(dropdown => {
            if (dropdown.id !== `actions-dropdown-${messageId}`) {
                dropdown.classList.add('hidden');
            }
        });
        targetDropdown.classList.toggle('hidden');
    }

    window.closeAllActionDropdowns = function() {
        document.querySelectorAll('.message-actions-dropdown').forEach(dropdown => {
            dropdown.classList.add('hidden');
        });
    }

    document.addEventListener('click', function(event) {
        const openDropdown = document.querySelector('.message-actions-dropdown:not(.hidden)');
        if (openDropdown) {
            const isClickInsideDropdown = openDropdown.contains(event.target);
            const isKebabButton = event.target.closest('button[onclick^="toggleMessageActions"]');

            if (!isClickInsideDropdown && !isKebabButton) {
                closeAllActionDropdowns();
            }
        }
    });

    window.showEditForm = function(messageId) {
        document.querySelectorAll('.edit-form-container').forEach(form => {
            if (form.id !== `edit-form-${messageId}`) {
                form.classList.add('hidden');
                const otherMsgId = form.id.replace('edit-form-', '');
                const otherMsgContentWrapper = document.querySelector(`div[data-message-container-id="${otherMsgId}"] .message-content-wrapper`);
                if (otherMsgContentWrapper) otherMsgContentWrapper.classList.remove('hidden');
            }
        });

        const messageContentWrapper = document.querySelector(`div[data-message-container-id="${messageId}"] .message-content-wrapper`);
        const editFormContainer = document.getElementById(`edit-form-${messageId}`);

        if (messageContentWrapper && editFormContainer) {
            messageContentWrapper.classList.add('hidden'); 
            editFormContainer.classList.remove('hidden'); 
            const textarea = editFormContainer.querySelector('textarea');
            if(textarea) {
                textarea.focus();
                const messageBodyElement = messageContentWrapper.querySelector('.message-body');
                // Ambil teks asli dari bubble, bukan dari textarea yang mungkin sudah diedit sebelumnya tapi belum disimpan
                const originalMessageBubble = document.querySelector(`div[data-message-container-id="${messageId}"] .message-content-wrapper .message-body`);
                textarea.value = originalMessageBubble ? (originalMessageBubble.textContent || '') : '';
            }
        }
        closeAllActionDropdowns(); 
    }

     window.cancelEdit = function(messageId) {
        const messageContentWrapper = document.querySelector(`div[data-message-container-id="${messageId}"] .message-content-wrapper`);
        const editFormContainer = document.getElementById(`edit-form-${messageId}`);
        if (messageContentWrapper && editFormContainer) {
            editFormContainer.classList.add('hidden');
            messageContentWrapper.classList.remove('hidden');
        }
     }

    window.deleteMessage = function(messageId) {
        if (!confirm('Anda yakin ingin menghapus pesan ini? Tindakan ini tidak dapat diurungkan.')) {
            closeAllActionDropdowns(); 
            return;
        }
        fetch(`/chat/messages/${messageId}`, {
            method: 'DELETE',
            headers: {'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json'}
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`div[data-message-container-id="${data.message_id || messageId}"]`)?.remove();
                showToast('Pesan berhasil dihapus.', 'success');
                if (messageArea && !messageArea.querySelector('[data-message-container-id]')) {
                    if (!messageArea.querySelector('.text-center.text-gray-500.py-16')) { 
                        messageArea.innerHTML = `<div class="text-center text-gray-500 py-16"><svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg><p class="mt-4 text-lg">Belum ada pesan di chat ini.</p><p class="text-sm text-gray-400">Mulai percakapan sekarang!</p></div>`;
                    }
                }
            } else {
                showToast('Gagal menghapus pesan: ' + (data.error || data.message || 'Error'), 'error');
            }
        })
        .catch(error => {
            console.error('Delete message error:', error);
            showToast('Terjadi kesalahan jaringan saat menghapus.', 'error');
        })
        .finally(() => {
            closeAllActionDropdowns(); 
        });
    }

    window.submitEdit = function(messageId) {
        const editFormContainer = document.getElementById(`edit-form-${messageId}`);
        if(!editFormContainer) return;
        const textarea = editFormContainer.querySelector('textarea');
        if(!textarea) return;
        const newBody = textarea.value.trim();
        if (!newBody) {
            showToast('Pesan tidak boleh kosong.', 'error');
            return;
        }
        const saveButton = editFormContainer.querySelector('button[onclick^="submitEdit"]');
        const originalSaveButtonText = saveButton.innerHTML;
        saveButton.disabled = true;
        saveButton.innerHTML = `<svg class="animate-spin h-4 w-4 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;

        fetch(`/chat/messages/${messageId}`, {
            method: 'PUT',
            headers: {'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json'},
            body: JSON.stringify({ body: newBody })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.message) {
                const msgContentWrapper = document.querySelector(`div[data-message-container-id="${messageId}"] .message-content-wrapper`);
                if (msgContentWrapper) {
                    const bodyEl = msgContentWrapper.querySelector('.message-body');
                    if(bodyEl) bodyEl.textContent = data.message.body; 
                    
                    const timeStatusDiv = msgContentWrapper.querySelector('.message-time-status');
                    if(timeStatusDiv){
                        let editedSpan = timeStatusDiv.querySelector('.italic.mr-1');
                        if (!editedSpan && data.message.edited_at) { // Hanya tambah jika memang diedit
                            editedSpan = document.createElement('span');
                            editedSpan.classList.add('italic', 'mr-1');
                            const timeSpan = timeStatusDiv.querySelector('span:not(.italic)'); // cari span waktu
                            if(timeSpan) timeStatusDiv.insertBefore(editedSpan, timeSpan); // sisipkan sebelum span waktu
                            else timeStatusDiv.prepend(editedSpan); // fallback jika span waktu tidak ditemukan
                        }
                        if (editedSpan && data.message.edited_at) {
                             editedSpan.textContent = '(diedit)';
                        } else if (editedSpan && !data.message.edited_at) { // Hapus jika status diedit hilang (seharusnya tidak terjadi)
                            editedSpan.remove();
                        }
                    }
                }
                cancelEdit(messageId); 
                showToast('Pesan berhasil diedit!', 'success');
            } else {
                showToast('Gagal mengedit pesan: ' + (data.error || data.message || 'Error'), 'error');
            }
        })
        .catch(error => {
            console.error('Edit message error:', error);
            showToast('Terjadi kesalahan jaringan saat mengedit.', 'error');
        })
        .finally(() => {
            if(saveButton) {
                saveButton.disabled = false;
                saveButton.innerHTML = originalSaveButtonText;
            }
        });
    }

    const toastElement = document.getElementById('toast-notification');
    let toastTimeout;
    function showToast(message, type = 'info') {
        if(!toastElement) return;
        toastElement.textContent = message;
        // Reset classes
        toastElement.className = 'fixed bottom-5 right-5 text-white py-2 px-4 rounded-lg shadow-md text-sm opacity-0 transition-opacity duration-300 z-50';
        
        if (type === 'success') toastElement.classList.add('bg-green-500');
        else if (type === 'error') toastElement.classList.add('bg-red-500');
        else toastElement.classList.add('bg-gray-700'); // Default
        
        // Trigger reflow to restart animation
        void toastElement.offsetWidth; 

        toastElement.classList.remove('opacity-0');
        toastElement.classList.add('opacity-100');

        clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => {
            toastElement.classList.remove('opacity-100');
            toastElement.classList.add('opacity-0');
        }, 3000);
    }
});
</script>
@endpush