@extends('layouts.app')

@section('title', 'Pesan & Tanya Jawab Kontrakan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col md:flex-row h-[700px] max-h-[85vh]">
        <!-- Left Sidebar: Conversations List -->
        <div class="w-full md:w-80 lg:w-96 border-b md:border-b-0 md:border-r border-slate-200 flex flex-col h-full bg-slate-50/50">
            <!-- Sidebar Header -->
            <div class="p-4 sm:p-5 border-b border-slate-200 bg-white">
                <div class="flex items-center justify-between mb-1">
                    <h1 class="text-lg font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        Pesan Masuk
                    </h1>
                    @if(!empty($navbarUnreadMessagesCount) && $navbarUnreadMessagesCount > 0)
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-brand-600 text-white">
                            {{ $navbarUnreadMessagesCount }} baru
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-600">Tanya jawab seputar aturan, lingkungan, atau fasilitas sewa.</p>
            </div>

            <!-- Conversations List Container -->
            <div class="flex-1 overflow-y-auto divide-y divide-slate-100">
                @forelse($conversations as $conv)
                    @php
                        $otherUser = $conv->getOtherUser(auth()->user());
                        $unread = $conv->unreadCountFor(auth()->user());
                        $isActive = isset($activeConversation) && $activeConversation->id === $conv->id;
                    @endphp
                    <a href="{{ route('chat.show', $conv) }}" class="block p-4 transition-colors {{ $isActive ? 'bg-white shadow-sm border-l-4 border-l-brand-600' : 'hover:bg-white/80' }}">
                        <div class="flex items-start gap-3">
                            <div class="relative flex-shrink-0">
                                <img src="{{ $otherUser->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($otherUser->name) . '&background=059669&color=fff' }}" alt="{{ $otherUser->name }}" class="w-11 h-11 rounded-2xl object-cover border border-slate-200">
                                @if($otherUser->isVerified())
                                    <span class="absolute -bottom-1 -right-1 bg-white rounded-full p-0.5 text-sky-500 shadow-sm" title="User Terverifikasi (KYC)">
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    </span>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <h2 class="text-xs font-bold text-slate-900 truncate flex items-center gap-1">
                                        {{ $otherUser->name }}
                                        <span class="text-[10px] font-normal text-slate-600">({{ $otherUser->role === 'pemilik' ? 'Pemilik' : 'Penyewa' }})</span>
                                    </h2>
                                    @if($conv->latestMessage)
                                        <span class="text-[10px] text-slate-600">{{ $conv->latestMessage->created_at->format('H:i') }}</span>
                                    @endif
                                </div>

                                @if($conv->property)
                                    <p class="text-[11px] font-semibold text-brand-700 truncate mb-1">
                                        🏡 {{ $conv->property->title }}
                                    </p>
                                @endif

                                <div class="flex items-center justify-between">
                                    <p class="text-xs text-slate-600 truncate {{ $unread > 0 ? 'font-bold text-slate-900' : '' }}">
                                        {{ $conv->latestMessage ? $conv->latestMessage->message : 'Belum ada pesan' }}
                                    </p>
                                    @if($unread > 0)
                                        <span class="ml-2 w-5 h-5 rounded-full bg-brand-600 text-white text-[10px] font-bold flex items-center justify-center flex-shrink-0">
                                            {{ $unread }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-8 text-center text-xs text-slate-600">
                        <svg class="w-10 h-10 text-slate-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        Belum ada obrolan. Kunjungi halaman kontrakan dan klik tombol <strong>"Chat Pemilik"</strong> untuk memulai tanya jawab.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right Pane: Active Chat Room -->
        <div class="flex-1 flex flex-col h-full bg-white relative" 
             @if($activeConversation)
             x-data="chatRoom({{ $activeConversation->id }}, {{ auth()->id() }})"
             x-init="initChat()"
             @endif>
            
            @if($activeConversation)
                @php
                    $counterpart = $activeConversation->getOtherUser(auth()->user());
                @endphp

                <!-- Chat Header -->
                <div class="p-4 sm:px-6 border-b border-slate-200 bg-white flex items-center justify-between">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="relative flex-shrink-0">
                            <img src="{{ $counterpart->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($counterpart->name) . '&background=059669&color=fff' }}" alt="{{ $counterpart->name }}" class="w-10 h-10 rounded-2xl object-cover border border-slate-200">
                            @if($counterpart->isVerified())
                                <span class="absolute -bottom-1 -right-1 bg-white rounded-full p-0.5 text-sky-500 shadow-sm" title="Terverifikasi KYC (Centang Biru)">
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                </span>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-1.5">
                                <h2 class="text-sm font-extrabold text-slate-900 truncate">{{ $counterpart->name }}</h2>
                                @if($counterpart->isVerified())
                                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.2 rounded text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                                        Verified
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-600 truncate">
                                {{ $counterpart->role === 'pemilik' ? 'Pemilik Properti' : 'Calon Penyewa' }} &bull; Aktif di platform
                            </p>
                        </div>
                    </div>

                    @if($activeConversation->property)
                        <div class="hidden sm:flex items-center gap-3 p-2 px-3 rounded-2xl bg-slate-50 border border-slate-200/80 max-w-xs">
                            <img src="{{ $activeConversation->property->main_image ?? 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=200&auto=format&fit=crop&q=80' }}" class="w-9 h-9 rounded-xl object-cover flex-shrink-0">
                            <div class="min-w-0">
                                <p class="text-[10px] text-slate-600 uppercase font-bold">Properti yang Dibahas</p>
                                <a href="{{ route('properties.show', $activeConversation->property->slug) }}" class="text-xs font-bold text-slate-900 hover:text-brand-600 truncate block">
                                    {{ $activeConversation->property->title }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Messages Thread Scroll Area -->
                <div id="messages-container" class="flex-1 p-4 sm:p-6 overflow-y-auto space-y-4 bg-slate-50/40">
                    <div class="text-center my-2">
                        <span class="px-3 py-1 rounded-full text-[11px] font-medium bg-slate-200/70 text-slate-600">
                            Awal percakapan seputar properti
                        </span>
                    </div>

                    <template x-for="msg in messages" :key="msg.id">
                        <div :class="msg.is_me ? 'flex justify-end' : 'flex justify-start'">
                            <div :class="msg.is_me ? 'bg-brand-600 text-white rounded-br-none' : 'bg-white text-slate-800 border border-slate-200/80 rounded-bl-none shadow-sm'" 
                                 class="max-w-[75%] sm:max-w-md px-4 py-3 rounded-2xl text-xs sm:text-sm leading-relaxed whitespace-pre-wrap break-words">
                                <p x-text="msg.text"></p>
                                <div class="text-right mt-1">
                                    <span :class="msg.is_me ? 'text-brand-100' : 'text-slate-600'" class="text-[10px]" x-text="msg.time"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Chat Input Box -->
                <div class="p-3 sm:p-4 border-t border-slate-200 bg-white">
                    <form @submit.prevent="sendMessage" class="flex items-end gap-2">
                        <div class="flex-1 relative">
                            <textarea x-model="newMessageText" 
                                      @keydown.enter.exact.prevent="sendMessage"
                                      rows="1" 
                                      placeholder="Tulis pesan... (Tekan Enter untuk mengirim)" 
                                      class="w-full resize-none px-4 py-3 text-xs sm:text-sm text-slate-900 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:bg-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500"></textarea>
                        </div>
                        <button type="submit" 
                                :disabled="!newMessageText.trim() || sending" 
                                class="p-3 rounded-2xl bg-brand-600 text-white hover:bg-brand-700 disabled:opacity-40 disabled:hover:bg-brand-600 transition-all flex-shrink-0 shadow-sm">
                            <svg class="w-5 h-5 rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        </button>
                    </form>
                    <p class="text-[10px] text-slate-600 mt-1 pl-1">Jaga sopan santun dan jangan bagikan password atau data rahasia perbankan.</p>
                </div>
            @else
                <!-- No Conversation Selected -->
                <div class="flex-1 flex flex-col items-center justify-center p-8 text-center text-slate-600 bg-slate-50/30">
                    <div class="w-16 h-16 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 mb-1">Pilih Obrolan untuk Membaca Pesan</h3>
                    <p class="text-xs max-w-sm">Pilih salah satu kontak di bilah sebelah kiri atau mulai obrolan dari halaman kontrakan favorit Anda.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function chatRoom(conversationId, currentUserId) {
        return {
            conversationId: conversationId,
            currentUserId: currentUserId,
            messages: [],
            newMessageText: '',
            sending: false,
            pollingInterval: null,

            initChat() {
                this.fetchMessages();
                // Real-time polling every 3.5 seconds
                this.pollingInterval = setInterval(() => {
                    this.fetchMessages(false);
                }, 3500);
            },

            async fetchMessages(shouldScroll = true) {
                try {
                    const res = await fetch(`/chat/${this.conversationId}/messages`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const data = await res.json();
                    if (data.success) {
                        const previousLength = this.messages.length;
                        this.messages = data.messages;
                        if (shouldScroll || this.messages.length > previousLength) {
                            this.$nextTick(() => this.scrollToBottom());
                        }
                    }
                } catch (e) {
                    console.error('Failed to fetch messages', e);
                }
            },

            async sendMessage() {
                const text = this.newMessageText.trim();
                if (!text || this.sending) return;

                this.sending = true;
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                try {
                    const res = await fetch(`/chat/${this.conversationId}/kirim`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ message: text })
                    });

                    const data = await res.json();
                    if (data.success) {
                        this.messages.push(data.message);
                        this.newMessageText = '';
                        this.$nextTick(() => this.scrollToBottom());
                    }
                } catch (e) {
                    console.error('Failed to send message', e);
                } finally {
                    this.sending = false;
                }
            },

            scrollToBottom() {
                const container = document.getElementById('messages-container');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            }
        };
    }
</script>
@endsection
