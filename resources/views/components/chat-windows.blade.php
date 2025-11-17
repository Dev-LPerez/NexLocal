<!-- Componente de Chat estilo Facebook (ventanas flotantes desde abajo) -->
<div x-data="chatWindows()"
     @open-chat-window.window="openWindow($event.detail)"
     @close-chat-window.window="closeWindowByEvent($event.detail)"
     class="fixed bottom-0 right-0 z-40 flex items-end space-x-2 pr-4 pb-0">
    <template x-for="window in openWindows" :key="window.booking_id">
        <div class="bg-white dark:bg-gray-800 rounded-t-lg shadow-2xl border border-gray-300 dark:border-gray-600 w-80 flex flex-col"
             :class="{ 'h-96': !window.minimized, 'h-12': window.minimized }"
             style="margin-bottom: 0;">

            <!-- Header de la ventana de chat -->
            <div class="bg-indigo-600 dark:bg-indigo-700 text-white px-4 py-2 rounded-t-lg flex items-center justify-between cursor-pointer"
                 @click="toggleMinimize(window.booking_id)">
                <div class="flex items-center space-x-2 flex-1 min-w-0">
                    <template x-if="window.other_user.profile_photo_path">
                        <img :src="'/storage/' + window.other_user.profile_photo_path"
                             :alt="window.other_user.name"
                             class="w-8 h-8 rounded-full object-cover border-2 border-white">
                    </template>
                    <template x-if="!window.other_user.profile_photo_path">
                        <div class="w-8 h-8 rounded-full bg-white text-indigo-600 flex items-center justify-center font-semibold text-sm">
                            <span x-text="window.other_user.name.charAt(0).toUpperCase()"></span>
                        </div>
                    </template>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate" x-text="window.other_user.name"></p>
                        <p class="text-xs opacity-90 truncate" x-text="window.experience_title"></p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button @click.stop="toggleMinimize(window.booking_id)" class="hover:bg-indigo-500 rounded p-1">
                        <svg x-show="!window.minimized" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                        <svg x-show="window.minimized" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    </button>
                    <button @click.stop="closeWindow(window.booking_id)" class="hover:bg-indigo-500 rounded p-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Contenido de la ventana (solo visible cuando no está minimizada) -->
            <div x-show="!window.minimized" class="flex-1 flex flex-col overflow-hidden">
                <!-- Información de la reserva -->
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-2 border-b border-gray-200 dark:border-gray-600">
                    <div class="text-xs text-gray-600 dark:text-gray-300 space-y-1.5">
                        <p class="font-semibold truncate" x-text="'📍 ' + (window.booking_info?.experience_title || 'Sin título')"></p>

                        <!-- Fecha y Hora en un solo bloque -->
                        <div class="flex items-center">
                            <span class="inline-flex items-center gap-1 bg-white dark:bg-gray-600 px-2 py-1 rounded-md border border-gray-200 dark:border-gray-500">
                                <template x-if="window.booking_info?.date && window.booking_info?.time">
                                    <span x-text="'🗓️ ' + window.booking_info.date + ' • ' + window.booking_info.time"></span>
                                </template>
                                <template x-if="!window.booking_info?.date || !window.booking_info?.time">
                                    <span class="text-gray-400">Fecha pendiente</span>
                                </template>
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span x-show="window.booking_info?.num_travelers"
                                  x-text="'👥 ' + window.booking_info.num_travelers + (window.booking_info.num_travelers === 1 ? ' persona' : ' personas')"></span>
                            <span class="inline-block px-2 py-0.5 text-xs rounded-full"
                                  :class="{
                                      'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': window.booking_status === 'pending',
                                      'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': window.booking_status === 'confirmed',
                                      'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200': window.booking_status === 'in_progress',
                                      'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200': window.booking_status === 'completed'
                                  }"
                                  x-text="translateStatus(window.booking_status)">
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Área de mensajes -->
                <div class="flex-1 overflow-y-auto p-4 space-y-2 bg-gray-50 dark:bg-gray-900"
                     :id="'messages-container-' + window.booking_id">
                    <template x-if="window.messages.length === 0">
                        <div class="text-center text-sm text-gray-500 dark:text-gray-400 py-8">
                            No hay mensajes aún. ¡Inicia la conversación!
                        </div>
                    </template>

                    <template x-for="message in window.messages" :key="message.id">
                        <div :class="message.sender_id === {{ Auth::id() }} ? 'flex justify-end' : 'flex justify-start'">
                            <div :class="message.sender_id === {{ Auth::id() }}
                                         ? 'bg-indigo-600 text-white'
                                         : 'bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100'"
                                 class="max-w-xs px-3 py-2 rounded-lg shadow">
                                <p class="text-sm break-words" x-text="message.message"></p>
                                <p class="text-xs mt-1 opacity-70"
                                   x-text="formatMessageTime(message.created_at)"></p>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Input de mensaje -->
                <div class="border-t border-gray-200 dark:border-gray-600 p-3 bg-white dark:bg-gray-800">
                    <form @submit.prevent="sendMessage(window.booking_id)" class="flex space-x-2">
                        <input type="text"
                               x-model="window.newMessage"
                               placeholder="Escribe un mensaje..."
                               class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-100 text-sm"
                               maxlength="5000">
                        <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white p-2 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
    function chatWindows() {
        return {
            openWindows: [],

            async openWindow(conversation) {
                const existingWindow = this.openWindows.find(w => w.booking_id === conversation.booking_id);

                if (existingWindow) {
                    existingWindow.minimized = false;
                    this.scrollToBottom(existingWindow.booking_id);
                    return;
                }

                try {
                    const response = await fetch(`/chat/${conversation.booking_id}/messages`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    });
                    const data = await response.json();

                    const newWindow = {
                        booking_id: conversation.booking_id,
                        other_user: data.other_user,
                        experience_title: conversation.experience_title,
                        booking_status: conversation.booking_status,
                        booking_info: data.booking_info,
                        messages: data.messages,
                        newMessage: '',
                        minimized: false
                    };

                    this.openWindows.push(newWindow);

                    if (this.openWindows.length > 3) {
                        this.openWindows.shift();
                    }

                    this.$nextTick(() => {
                        this.scrollToBottom(newWindow.booking_id);
                    });

                    this.startPolling(newWindow.booking_id);

                } catch (error) {
                    console.error('Error al cargar mensajes:', error);
                }
            },

            closeWindow(bookingId) {
                const index = this.openWindows.findIndex(w => w.booking_id === bookingId);
                if (index > -1) {
                    this.openWindows.splice(index, 1);
                }
            },

            closeWindowByEvent(detail) {
                if (detail && detail.booking_id) {
                    this.closeWindow(detail.booking_id);
                }
            },

            toggleMinimize(bookingId) {
                const window = this.openWindows.find(w => w.booking_id === bookingId);
                if (window) {
                    window.minimized = !window.minimized;
                    if (!window.minimized) {
                        this.$nextTick(() => {
                            this.scrollToBottom(bookingId);
                        });
                    }
                }
            },

            async sendMessage(bookingId) {
                const window = this.openWindows.find(w => w.booking_id === bookingId);
                if (!window || !window.newMessage.trim()) return;

                try {
                    const response = await fetch(`/chat/${bookingId}/send`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ message: window.newMessage })
                    });

                    const data = await response.json();
                    window.messages.push(data.message);
                    window.newMessage = '';

                    this.$nextTick(() => {
                        this.scrollToBottom(bookingId);
                    });

                } catch (error) {
                    console.error('Error al enviar mensaje:', error);
                }
            },

            async loadMessages(bookingId) {
                try {
                    const response = await fetch(`/chat/${bookingId}/messages`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    });
                    const data = await response.json();

                    const window = this.openWindows.find(w => w.booking_id === bookingId);
                    if (window) {
                        const oldLength = window.messages.length;
                        window.messages = data.messages;

                        if (data.messages.length > oldLength) {
                            this.$nextTick(() => {
                                this.scrollToBottom(bookingId);
                            });
                        }
                    }
                } catch (error) {
                    console.error('Error al actualizar mensajes:', error);
                }
            },

            startPolling(bookingId) {
                setInterval(() => {
                    const window = this.openWindows.find(w => w.booking_id === bookingId);
                    if (window) {
                        this.loadMessages(bookingId);
                    }
                }, 5000);
            },

            scrollToBottom(bookingId) {
                const container = document.getElementById(`messages-container-${bookingId}`);
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            },

            formatMessageTime(timestamp) {
                const date = new Date(timestamp);
                const now = new Date();
                const diffMs = now - date;
                const diffMins = Math.floor(diffMs / 60000);

                if (diffMins < 1) return 'Ahora';
                if (diffMins < 60) return `Hace ${diffMins} min`;

                return date.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
            },

            translateStatus(status) {
                const translations = {
                    'pending': 'Pendiente',
                    'confirmed': 'Confirmada',
                    'in_progress': 'En Curso',
                    'completed': 'Completada',
                    'cancelled': 'Cancelada'
                };
                return translations[status] || status;
            }
        }
    }
</script>

