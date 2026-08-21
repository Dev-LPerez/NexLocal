<div x-data="chatWindows('{{ Auth::user()->role }}')"
     @open-chat-window.window="openWindow($event.detail)"
     @close-chat-window.window="closeWindowByEvent($event.detail)"
     class="fixed bottom-0 right-4 z-50 flex items-end gap-3 pointer-events-none">

    <template x-for="window in openWindows" :key="window.booking_id">
        <div class="bg-white dark:bg-gray-900 rounded-t-2xl shadow-2xl border border-gray-200 dark:border-gray-700 w-80 sm:w-96 flex flex-col pointer-events-auto transition-all duration-300 transform"
             :class="{ 'h-[34rem]': !window.minimized, 'h-14': window.minimized }">

            <div class="bg-gradient-to-r from-indigo-600 to-violet-600 dark:from-indigo-800 dark:to-violet-900 px-4 py-3 rounded-t-2xl flex items-center justify-between cursor-pointer shadow-md shrink-0 z-20 relative"
                 @click="toggleMinimize(window.booking_id)">

                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="relative flex-shrink-0">
                        <template x-if="window.other_user.profile_photo_path">
                            <img :src="'{{ rtrim(Storage::url(''), '/') }}/' + window.other_user.profile_photo_path"
                                 class="w-10 h-10 rounded-full object-cover border-2 border-white/30">
                        </template>
                        <template x-if="!window.other_user.profile_photo_path">
                            <div class="w-10 h-10 rounded-full bg-white/20 text-white flex items-center justify-center font-bold border-2 border-white/30 text-sm">
                                <span x-text="window.other_user.name.charAt(0).toUpperCase()"></span>
                            </div>
                        </template>
                        <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-indigo-600 rounded-full"></span>
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <h4 class="text-white font-bold text-sm truncate" x-text="window.other_user.name"></h4>
                            <span class="w-2 h-2 rounded-full"
                                  :class="{
                                      'bg-yellow-400': window.booking_status === 'pending',
                                      'bg-green-400': window.booking_status === 'confirmed',
                                      'bg-blue-400': window.booking_status === 'in_progress',
                                      'bg-purple-400': window.booking_status === 'completed',
                                      'bg-red-400': window.booking_status === 'cancelled'
                                  }"
                                  :title="translateStatus(window.booking_status)">
                            </span>
                        </div>
                        <p class="text-indigo-100 text-xs truncate opacity-90 font-medium" x-text="window.experience_title"></p>
                    </div>
                </div>

                <div class="flex items-center gap-1 text-white/80 pl-2">
                    <button @click.stop="toggleMinimize(window.booking_id)" class="p-1.5 hover:bg-white/20 rounded-full transition">
                        <svg x-show="!window.minimized" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        <svg x-show="window.minimized" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                    </button>
                    <button @click.stop="closeWindow(window.booking_id)" class="p-1.5 hover:bg-red-500/80 rounded-full transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>

            <div x-show="!window.minimized" class="flex-1 flex flex-col overflow-hidden bg-slate-50 dark:bg-gray-900/50">

                <div class="bg-white/95 dark:bg-gray-800/95 backdrop-blur-md border-b dark:border-gray-700 px-4 py-2.5 text-xs flex justify-between items-center shrink-0 z-10 shadow-sm">

                    <div class="flex flex-col gap-0.5">
                        <div class="flex items-center gap-1.5 text-gray-700 dark:text-gray-300 font-semibold">
                            <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            <span x-text="window.booking_info?.date || 'Fecha por definir'"></span>
                        </div>
                        <div class="flex items-center gap-1.5 text-gray-500 dark:text-gray-400 pl-5">
                            <span x-text="window.booking_info?.time ? (window.booking_info.time + ' horas') : '--:--'"></span>
                            <span>•</span>
                            <span x-text="(window.booking_info?.num_travelers || 1) + ' personas'"></span>
                        </div>
                    </div>

                    <div class="flex flex-col items-end gap-1">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide border"
                              :class="{
                                  'bg-yellow-50 text-yellow-700 border-yellow-200': window.booking_status === 'pending',
                                  'bg-green-50 text-green-700 border-green-200': window.booking_status === 'confirmed',
                                  'bg-blue-50 text-blue-700 border-blue-200': window.booking_status === 'in_progress',
                                  'bg-purple-50 text-purple-700 border-purple-200': window.booking_status === 'completed',
                                  'bg-red-50 text-red-700 border-red-200': window.booking_status === 'cancelled'
                              }"
                              x-text="translateStatus(window.booking_status)">
                        </span>

                        {{-- Enlace a la reserva (solo si es guía o turista involucrado) --}}
                        <a :href="window.type === 'order' ? (userRole === 'owner' ? '/dashboard' : '/orders') : (userRole === 'guide' ? '/dashboard' : '/bookings')"
                           class="text-[10px] text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-0.5">
                            <span x-text="window.type === 'order' ? 'Ver pedido' : 'Ver reserva'"></span>
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar" :id="'messages-container-' + window.booking_id">
                    <template x-if="window.messages.length === 0">
                        <div class="flex flex-col items-center justify-center h-full text-gray-400 dark:text-gray-500 space-y-3 p-6 text-center">
                            <div class="w-16 h-16 bg-indigo-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-2">
                                <svg class="w-8 h-8 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-300">¡Comienza la charla!</p>
                                <p class="text-xs mt-1">Coordina los detalles del encuentro aquí.</p>
                            </div>
                        </div>
                    </template>

                    <template x-for="message in window.messages" :key="message.id">
                        <div class="flex flex-col" :class="message.sender_id === {{ Auth::id() }} ? 'items-end' : 'items-start'">
                            <div class="max-w-[85%] px-4 py-2.5 text-sm shadow-sm relative group transition-all"
                                 :class="message.sender_id === {{ Auth::id() }}
                                    ? 'bg-indigo-600 text-white rounded-2xl rounded-tr-sm'
                                    : 'bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 border border-gray-100 dark:border-gray-600 rounded-2xl rounded-tl-sm'">
                                <p class="break-words leading-relaxed" x-text="message.message"></p>
                                <span class="text-[9px] absolute bottom-1 right-2 opacity-60"
                                      :class="message.sender_id === {{ Auth::id() }} ? 'text-indigo-100' : 'text-gray-400'"
                                      x-text="formatMessageTime(message.created_at)">
                                </span>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="p-3 bg-white dark:bg-gray-800 border-t dark:border-gray-700">
                    <form @submit.prevent="sendMessage(window.booking_id)" class="relative flex items-end gap-2">
                        <textarea x-model="window.newMessage"
                                  placeholder="Escribe un mensaje..."
                                  class="w-full pl-4 pr-10 py-2.5 bg-gray-100 dark:bg-gray-900 border-0 text-gray-900 dark:text-gray-100 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-gray-900 transition-all text-sm resize-none custom-scrollbar"
                                  rows="1"
                                  style="min-height: 42px; max-height: 100px;"
                                  @keydown.enter.prevent="if(!$event.shiftKey) sendMessage(window.booking_id)"
                                  @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'"></textarea>

                        <button type="submit"
                                :disabled="!window.newMessage.trim()"
                                class="p-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-300 dark:disabled:bg-gray-700 text-white rounded-full transition-colors shadow-sm flex-shrink-0 mb-0.5">
                            <svg class="w-5 h-5 transform rotate-90" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" /></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>

{{-- SCRIPT JS (Incluido al final) --}}
<script>
    // CAMBIO 3: Recibir el parámetro role
    function chatWindows(role) {
        return {
            userRole: role, // Guardamos el rol
            openWindows: [],

            async openWindow(conversation) {
                const existingWindow = this.openWindows.find(w => w.booking_id === conversation.booking_id);
                if (existingWindow) {
                    existingWindow.minimized = false;
                    this.scrollToBottom(existingWindow.booking_id);
                    return;
                }
                try {
                    let url = conversation.type === 'order' ? `/chat/orders/${conversation.id}/messages` : `/chat/${conversation.id}/messages`;
                    const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });
                    const data = await response.json();

                    const newWindow = {
                        booking_id: conversation.booking_id, // kept for backward compatibility
                        id: conversation.id,
                        type: conversation.type,
                        other_user: data.other_user,
                        experience_title: conversation.experience_title,
                        booking_status: conversation.booking_status,
                        booking_info: data.booking_info,
                        messages: data.messages,
                        newMessage: '',
                        minimized: false
                    };
                    this.openWindows.push(newWindow);
                    if (this.openWindows.length > 3) this.openWindows.shift();
                    this.$nextTick(() => { this.scrollToBottom(newWindow.booking_id); });
                    this.startPolling(newWindow.booking_id);
                } catch (error) { console.error(error); }
            },

            closeWindow(bookingId) {
                const index = this.openWindows.findIndex(w => w.booking_id === bookingId);
                if (index > -1) this.openWindows.splice(index, 1);
            },

            closeWindowByEvent(detail) {
                if (detail && detail.booking_id) this.closeWindow(detail.booking_id);
            },

            toggleMinimize(bookingId) {
                const window = this.openWindows.find(w => w.booking_id === bookingId);
                if (window) {
                    window.minimized = !window.minimized;
                    if (!window.minimized) this.$nextTick(() => { this.scrollToBottom(bookingId); });
                }
            },

            async sendMessage(bookingId) {
                const window = this.openWindows.find(w => w.booking_id === bookingId);
                if (!window || !window.newMessage.trim()) return;
                try {
                    let url = window.type === 'order' ? `/chat/orders/${window.id}/send` : `/chat/${window.id}/send`;
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                        body: JSON.stringify({ message: window.newMessage })
                    });
                    const data = await response.json();
                    window.messages.push(data.message);
                    window.newMessage = '';
                    // Resetear altura del textarea
                    // (Esto requiere acceder al DOM, pero Alpine lo maneja reactivamente con x-model)
                    this.$nextTick(() => { this.scrollToBottom(bookingId); });
                } catch (error) { console.error(error); }
            },

            async loadMessages(bookingId) {
                try {
                    const window = this.openWindows.find(w => w.booking_id === bookingId);
                    if (!window) return;
                    let url = window.type === 'order' ? `/chat/orders/${window.id}/messages` : `/chat/${window.id}/messages`;
                    const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });
                    const data = await response.json();
                    if (window) {
                        const oldLength = window.messages.length;
                        window.messages = data.messages;
                        if (data.messages.length > oldLength) this.$nextTick(() => { this.scrollToBottom(bookingId); });
                    }
                } catch (error) {}
            },

            startPolling(bookingId) { setInterval(() => { const window = this.openWindows.find(w => w.booking_id === bookingId); if (window) this.loadMessages(bookingId); }, 5000); },
            scrollToBottom(bookingId) { const container = document.getElementById(`messages-container-${bookingId}`); if (container) container.scrollTop = container.scrollHeight; },

            formatMessageTime(timestamp) {
                const date = new Date(timestamp);
                return date.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
            },

            translateStatus(status) {
                const translations = { 'pending': 'Pendiente', 'confirmed': 'Confirmada', 'in_progress': 'En Curso', 'completed': 'Completada', 'cancelled': 'Cancelada' };
                return translations[status] || status;
            }
        };
    }
</script>
