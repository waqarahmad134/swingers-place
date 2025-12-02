import './bootstrap';

const THEME_STORAGE_KEY = 'theme';

const getSystemTheme = () => (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

const getStoredTheme = () => {
    try {
        const stored = localStorage.getItem(THEME_STORAGE_KEY);
        if (stored === 'light' || stored === 'dark') {
            return stored;
        }
    } catch (error) {
        // ignore persistence errors
    }

    return null;
};

const applyTheme = (theme) => {
    const root = document.documentElement;
    if (theme === 'dark') {
        root.classList.add('dark');
    } else {
        root.classList.remove('dark');
    }
};

const updateThemeIcons = (theme) => {
    // Update icons using data-theme-icon attribute (new approach)
    document.querySelectorAll('[data-theme-icon]').forEach((icon) => {
        const targetTheme = icon.getAttribute('data-theme-icon');
        icon.classList.toggle('hidden', targetTheme !== theme);
    });
    
    // Also support old class-based approach for backward compatibility
    const lightIcons = document.querySelectorAll('.theme-icon-light');
    const darkIcons = document.querySelectorAll('.theme-icon-dark');
    
    if (theme === 'dark') {
        lightIcons.forEach(icon => icon.classList.add('hidden'));
        darkIcons.forEach(icon => icon.classList.remove('hidden'));
    } else {
        lightIcons.forEach(icon => icon.classList.remove('hidden'));
        darkIcons.forEach(icon => icon.classList.add('hidden'));
    }
};

const updateThemeToggleLabels = (currentTheme) => {
    const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';
    const label = `Switch to ${nextTheme} mode`;

    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        button.setAttribute('aria-label', label);
    });
};

const persistTheme = (theme) => {
    try {
        localStorage.setItem(THEME_STORAGE_KEY, theme);
    } catch (error) {
        // ignore persistence errors
    }
};

const initializeTheme = () => {
    const stored = getStoredTheme();
    // Default to 'light' mode for new visitors instead of using system preference
    // This prevents unwanted dark mode for users who haven't explicitly chosen it
    const initialTheme = stored ?? 'light';
    applyTheme(initialTheme);
    updateThemeIcons(initialTheme);
    updateThemeToggleLabels(initialTheme);
    return initialTheme;
};

const setupThemeToggle = () => {
    // Initialize theme and get current state
    initializeTheme();

    // Support both data-theme-toggle attribute and id="theme-toggle"
    const toggleButtons = document.querySelectorAll('[data-theme-toggle], #theme-toggle');
    
    toggleButtons.forEach((button) => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            
            // Get current theme from DOM state (most reliable)
            const root = document.documentElement;
            const isCurrentlyDark = root.classList.contains('dark');
            const nextTheme = isCurrentlyDark ? 'light' : 'dark';

            // Apply the new theme
            applyTheme(nextTheme);
            updateThemeIcons(nextTheme);
            updateThemeToggleLabels(nextTheme);
            persistTheme(nextTheme);
        });
    });
};

const observeSystemTheme = () => {
    // Disabled: We no longer auto-switch based on system theme
    // Users must explicitly choose their theme preference via the toggle
    // This prevents unwanted theme changes when system preferences change
    return;
};

const setupMobileMenus = () => {
    document.querySelectorAll('[data-mobile-menu-toggle]').forEach((button) => {
        const targetId = button.getAttribute('data-mobile-menu-target');
        if (!targetId) {
            return;
        }

        const target = document.getElementById(targetId);
        if (!target) {
            return;
        }

        const setMenuState = (isOpen) => {
            button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            target.classList.toggle('hidden', !isOpen);

            button.querySelectorAll('[data-menu-icon]').forEach((icon) => {
                const iconState = icon.getAttribute('data-menu-icon');
                const shouldShow = (iconState === 'open' && isOpen) || (iconState === 'closed' && !isOpen);
                icon.classList.toggle('hidden', !shouldShow);
            });
        };

        button.addEventListener('click', (event) => {
            event.preventDefault();
            const isExpanded = button.getAttribute('aria-expanded') === 'true';
            setMenuState(!isExpanded);
        });

        target.querySelectorAll('[data-mobile-menu-close]').forEach((element) => {
            element.addEventListener('click', () => setMenuState(false));
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && button.getAttribute('aria-expanded') === 'true') {
                setMenuState(false);
            }
        });
    });
};

const setupCarousels = () => {
    document.querySelectorAll('[data-carousel]').forEach((carousel) => {
        const slides = Array.from(carousel.querySelectorAll('[data-carousel-slide]'));
        if (!slides.length) {
            return;
        }

        let activeIndex = 0;
        const indicators = Array.from(carousel.querySelectorAll('[data-carousel-indicator]'));
        const prevButton = carousel.querySelector('[data-carousel-prev]');
        const nextButton = carousel.querySelector('[data-carousel-next]');
        const autoplay = carousel.getAttribute('data-carousel-autoplay') === 'true';
        const interval = Number(carousel.getAttribute('data-carousel-interval') ?? 6000);
        let timer;

        const showSlide = (index) => {
            slides.forEach((slide, idx) => {
                const isActive = idx === index;
                // Ensure all slides maintain absolute positioning
                slide.classList.remove('relative');
                slide.classList.add('absolute');
                
                if (isActive) {
                    slide.classList.remove('opacity-0', '-z-10');
                    slide.classList.add('opacity-100', 'z-10');
                } else {
                    slide.classList.remove('opacity-100', 'z-10');
                    slide.classList.add('opacity-0', '-z-10');
                }
            });

            indicators.forEach((indicator, idx) => {
                if (idx === index) {
                    indicator.classList.remove('bg-white/60');
                    indicator.classList.add('bg-primary');
                } else {
                    indicator.classList.remove('bg-primary');
                    indicator.classList.add('bg-white/60');
                }
            });

            activeIndex = index;
        };

        const goTo = (index) => {
            const newIndex = (index + slides.length) % slides.length;
            showSlide(newIndex);
            if (autoplay) {
                restartTimer();
            }
        };

        const restartTimer = () => {
            if (!autoplay) {
                return;
            }
            if (timer) {
                window.clearInterval(timer);
            }
            timer = window.setInterval(() => {
                goTo(activeIndex + 1);
            }, interval);
        };

        // Prev/Next button handlers
        if (prevButton) {
            prevButton.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                goTo(activeIndex - 1);
            });
        }

        if (nextButton) {
            nextButton.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                goTo(activeIndex + 1);
            });
        }

        // Indicator dot handlers
        indicators.forEach((indicator, idx) => {
            indicator.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();
                goTo(idx);
            });
        });

        // Pause autoplay on hover (if enabled)
        if (autoplay) {
            carousel.addEventListener('mouseenter', () => {
                if (timer) {
                    window.clearInterval(timer);
                }
            });

            carousel.addEventListener('mouseleave', () => {
                restartTimer();
            });
        }

        // Initialize first slide
        showSlide(0);
        
        // Start autoplay only if enabled
        if (autoplay) {
            restartTimer();
        }
    });
};

const setupTabGroups = () => {
    document.querySelectorAll('[data-tab-group]').forEach((group) => {
        const triggers = Array.from(group.querySelectorAll('[data-tab-trigger]'));
        const panels = Array.from(group.querySelectorAll('[data-tab-panel]'));
        if (!triggers.length || !panels.length) {
            return;
        }

        const initial = group.getAttribute('data-tab-initial') ?? triggers[0]?.getAttribute('data-tab-target');

        const activate = (target) => {
            triggers.forEach((trigger) => {
                const isActive = trigger.getAttribute('data-tab-target') === target;
                trigger.classList.toggle('bg-primary', isActive);
                trigger.classList.toggle('text-white', isActive);
                trigger.classList.toggle('border-primary', isActive);
                trigger.classList.toggle('text-gray-700', !isActive);
                trigger.classList.toggle('dark:text-gray-300', !isActive);
            });

            panels.forEach((panel) => {
                const isActive = panel.getAttribute('data-tab-id') === target;
                panel.classList.toggle('hidden', !isActive);
            });
        };

        triggers.forEach((trigger) => {
            trigger.addEventListener('click', (event) => {
                event.preventDefault();
                const target = trigger.getAttribute('data-tab-target');
                if (!target) {
                    return;
                }
                activate(target);
            });
        });

        if (initial) {
            activate(initial);
        }
    });
};

// Toast notification system
const showToast = (message, type = 'success', duration = 3000) => {
    const container = document.getElementById('toast-container');
    if (!container) {
        // Try to create container if it doesn't exist
        const newContainer = document.createElement('div');
        newContainer.id = 'toast-container';
        newContainer.className = 'fixed bottom-4 right-4 z-50 flex flex-col gap-2 max-w-full sm:max-w-md px-4 sm:px-0';
        newContainer.setAttribute('aria-live', 'polite');
        newContainer.setAttribute('aria-atomic', 'true');
        document.body.appendChild(newContainer);
        return showToast(message, type, duration); // Retry with new container
    }

    const toastId = `toast-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
    const toast = document.createElement('div');
    toast.id = toastId;
    toast.setAttribute('role', 'alert');
    toast.className = `
        flex items-center gap-3 rounded-lg border px-4 py-3 shadow-lg
        transition-all duration-300 ease-in-out
        transform translate-x-full opacity-0
        ${type === 'success' 
            ? 'border-green-200 bg-green-50 text-green-800 dark:border-green-800 dark:bg-green-900/40 dark:text-green-300' 
            : type === 'error'
            ? 'border-red-200 bg-red-50 text-red-800 dark:border-red-800 dark:bg-red-900/40 dark:text-red-300'
            : type === 'info'
            ? 'border-blue-200 bg-blue-50 text-blue-800 dark:border-blue-800 dark:bg-blue-900/40 dark:text-blue-300'
            : 'border-gray-200 bg-gray-50 text-gray-800 dark:border-gray-800 dark:bg-gray-900/40 dark:text-gray-300'
        }
        w-full sm:min-w-[300px] sm:max-w-md
    `.replace(/\s+/g, ' ').trim();

    const icon = type === 'success' 
        ? '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        : type === 'error'
        ? '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        : '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';

    toast.innerHTML = `
        ${icon}
        <span class="flex-1 text-sm font-medium wrap-break-word">${message}</span>
        <button type="button" onclick="removeToast('${toastId}')" class="shrink-0 text-current opacity-60 hover:opacity-100 transition-opacity ml-2" aria-label="Close">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    `;

    container.appendChild(toast);

    // Trigger animation
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
            toast.classList.add('translate-x-0', 'opacity-100');
        });
    });

    // Auto remove after duration
    if (duration > 0) {
        setTimeout(() => {
            removeToast(toastId);
        }, duration);
    }

    return toastId;
};

const removeToast = (toastId) => {
    const toast = document.getElementById(toastId);
    if (!toast) {
        return;
    }

    toast.classList.add('translate-x-full', 'opacity-0');
    setTimeout(() => {
        toast.remove();
    }, 300);
};

// Make toast functions globally available
window.showToast = showToast;
window.removeToast = removeToast;

const setupChatInterface = () => {
    const chatRoot = document.querySelector('[data-chat]');
    if (!chatRoot) {
                return;
            }

    const messagesEl = chatRoot.querySelector('[data-chat-messages]');
    const emptyState = chatRoot.querySelector('[data-chat-empty]');
    const form = chatRoot.querySelector('[data-chat-form]');
    const textarea = chatRoot.querySelector('[data-chat-input]');

    if (!messagesEl || !form || !textarea) {
        return;
    }

    const sendUrl = chatRoot.getAttribute('data-chat-send-url') || form.getAttribute('action');
    const pollUrl = chatRoot.getAttribute('data-chat-poll-url');
    let lastMessageId = Number(chatRoot.getAttribute('data-chat-last-id') || '0');
    let isSending = false;
    let pollTimer;

    const scrollToBottom = () => {
        messagesEl.scrollTop = messagesEl.scrollHeight;
    };

    const createMessageElement = (message) => {
        const wrapper = document.createElement('div');
        wrapper.className = `flex flex-col gap-1 ${message.is_me ? 'items-end text-right' : 'items-start text-left'}`;
        wrapper.setAttribute('data-chat-message', '');
        wrapper.setAttribute('data-chat-message-id', message.id);

        const bubble = document.createElement('div');
        bubble.className = `max-w-[85%] rounded-2xl px-4 py-2 text-sm shadow-sm ${message.is_me ? 'bg-primary text-white rounded-br-md' : 'bg-white border border-gray-200 rounded-bl-md dark:bg-gray-800 dark:border-gray-700'}`;
        bubble.textContent = message.body;

        const meta = document.createElement('span');
        meta.className = 'text-xs text-gray-500 dark:text-gray-400';
        meta.textContent = message.time_for_humans || '';

        wrapper.appendChild(bubble);
        wrapper.appendChild(meta);

        return wrapper;
    };

    const appendMessages = (messages = []) => {
        if (!messages.length) {
        return;
    }

        if (emptyState) {
            emptyState.classList.add('hidden');
        }

        messages.forEach((message) => {
            const existing = chatRoot.querySelector(`[data-chat-message-id="${message.id}"]`);
            if (existing) {
                return;
            }

            const element = createMessageElement(message);
            messagesEl.appendChild(element);
            lastMessageId = Math.max(lastMessageId, Number(message.id));
        });

        scrollToBottom();
    };

    const fetchNewMessages = async () => {
        if (!pollUrl) {
            return;
        }

        try {
            const response = await fetch(`${pollUrl}?after=${lastMessageId}`, {
                headers: {
                    Accept: 'application/json',
                },
                credentials: 'same-origin',
                cache: 'no-store',
            });

            if (!response.ok) {
                throw new Error('Failed to fetch new messages');
            }

            const data = await response.json();
            if (Array.isArray(data.messages)) {
                appendMessages(data.messages);
            }
        } catch (error) {
            console.error('Message polling error:', error);
        }
    };

    const startPolling = () => {
        if (pollTimer) {
            window.clearInterval(pollTimer);
        }
        fetchNewMessages();
        pollTimer = window.setInterval(fetchNewMessages, 4000);
    };

    const sendMessage = async () => {
        if (isSending) {
            return;
        }

        const body = textarea.value.trim();
        if (!body) {
            textarea.focus();
                return;
            }

        isSending = true;
        form.classList.add('opacity-60', 'pointer-events-none');

        const formData = new FormData(form);
        formData.set('body', body);

        try {
            const response = await fetch(sendUrl, {
                    method: 'POST',
                    headers: {
                    Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
                cache: 'no-store',
                body: formData,
            });

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                throw new Error(errorData.message || 'Unable to send message');
                }

                const data = await response.json();
            if (data.message) {
                appendMessages([data.message]);
            }

            textarea.value = '';
            textarea.focus();
            } catch (error) {
            console.error('Message send error:', error);
            window.showToast?.(error.message || 'Unable to send message', 'error');
            } finally {
            isSending = false;
            form.classList.remove('opacity-60', 'pointer-events-none');
        }
    };

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
        void sendMessage();
    });

    const handleEnterKey = (event) => {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            void sendMessage();
        }
    };

    textarea.addEventListener('keydown', handleEnterKey);
    form.addEventListener('keydown', handleEnterKey);

    scrollToBottom();
    startPolling();

    const handleVisibilityChange = () => {
        if (document.hidden) {
            if (pollTimer) {
                window.clearInterval(pollTimer);
            }
        } else {
            startPolling();
        }
    };

    document.addEventListener('visibilitychange', handleVisibilityChange);

    window.addEventListener('beforeunload', () => {
        document.removeEventListener('visibilitychange', handleVisibilityChange);
        if (pollTimer) {
            window.clearInterval(pollTimer);
        }
    });
};

const setupMessagesDropdown = () => {
    const dropdown = document.querySelector('[data-messages-dropdown]');
    if (!dropdown) {
        return;
    }

    const toggle = dropdown.querySelector('[data-messages-toggle]');
    const panel = dropdown.querySelector('[data-messages-panel]');
    const closeBtn = dropdown.querySelector('[data-messages-close]');
    const countBadge = dropdown.querySelector('[data-messages-count]');
    const list = dropdown.querySelector('[data-messages-items]');
    const loadingEl = dropdown.querySelector('[data-messages-loading]');
    const emptyEl = dropdown.querySelector('[data-messages-empty]');
    const recentUrl = dropdown.getAttribute('data-messages-url') || '/messages/recent';

    let isOpen = false;
    let updateTimer;

    const openPanel = () => {
        isOpen = true;
        panel.classList.remove('opacity-0', 'invisible', 'pointer-events-none', 'translate-y-1');
        panel.classList.add('opacity-100', 'visible', 'pointer-events-auto', 'translate-y-0');
        loadMessages();
        startPolling();
    };

    const closePanel = () => {
        isOpen = false;
        panel.classList.add('opacity-0', 'invisible', 'pointer-events-none', 'translate-y-1');
        panel.classList.remove('opacity-100', 'visible', 'pointer-events-auto', 'translate-y-0');
        startPolling();
    };

    const createConversationItem = (conv) => {
        const item = document.createElement('a');
        item.href = `/messages/${conv.user_id}`;
        item.className = `block p-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors ${conv.unread_count > 0 ? 'bg-primary/5 dark:bg-primary/10' : ''}`;
        
        const avatarHtml = conv.user_avatar
            ? `<img src="${conv.user_avatar}" alt="${conv.user_name}" class="h-10 w-10 rounded-full object-cover">`
            : `<div class="h-10 w-10 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                 </svg>
               </div>`;

        const prefix = conv.latest_message.is_me ? 'You: ' : '';
        const unreadBadge = conv.unread_count > 0
            ? `<span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-primary rounded-full">${conv.unread_count}</span>`
            : '';

        item.innerHTML = `
            <div class="flex items-start gap-3">
                <div class="shrink-0">
                    ${avatarHtml}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2 mb-1">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">${conv.user_name}</p>
                        <span class="text-xs text-gray-500 dark:text-gray-400 shrink-0">${conv.latest_message.time_for_humans}</span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate">${prefix}${conv.latest_message.body}</p>
                        ${unreadBadge}
                    </div>
                </div>
            </div>
        `;

        return item;
    };

    const updateBadge = (unreadCount) => {
        if (countBadge) {
            if (unreadCount > 0) {
                countBadge.textContent = unreadCount > 99 ? '99+' : unreadCount.toString();
                countBadge.classList.remove('hidden');
            } else {
                countBadge.classList.add('hidden');
            }
        }
    };

    const loadMessages = async () => {
        try {
            const response = await fetch(recentUrl, {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
            });

            if (!response.ok) {
                throw new Error('Failed to load messages');
            }

            const data = await response.json();
            const conversations = data.conversations || [];
            const unreadCount = data.unread_count || 0;

            // Always update badge (even when dropdown is closed)
            updateBadge(unreadCount);

            // Only update list if dropdown is open
            if (!isOpen) {
                return;
            }

            // Update list
            if (loadingEl) {
                loadingEl.classList.add('hidden');
            }

            if (conversations.length === 0) {
                if (emptyEl) {
                    emptyEl.classList.remove('hidden');
                }
                if (list) {
                    list.innerHTML = '';
                }
            } else {
                if (emptyEl) {
                    emptyEl.classList.add('hidden');
                }
                if (list) {
                    list.innerHTML = '';
                    conversations.forEach(conv => {
                        list.appendChild(createConversationItem(conv));
                    });
                }
            }
        } catch (error) {
            console.error('Failed to load messages:', error);
            if (isOpen) {
                if (loadingEl) {
                    loadingEl.classList.add('hidden');
                }
                if (list) {
                    list.innerHTML = '<div class="p-4 text-center text-sm text-red-600 dark:text-red-400">Failed to load messages</div>';
                }
            }
        }
    };

    const stopPolling = () => {
        if (updateTimer) {
            clearInterval(updateTimer);
            updateTimer = null;
        }
    };

    const startPolling = () => {
        stopPolling();
        updateTimer = setInterval(() => {
            if (document.hidden) {
                    return;
            }
            loadMessages();
        }, isOpen ? 5000 : 10000); // Poll every 5 seconds when open, 10 seconds when closed
    };

    // Event listeners
    if (toggle) {
        toggle.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            if (isOpen) {
                closePanel();
            } else {
                openPanel();
            }
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            closePanel();
        });
    }

    // Close on outside click
    document.addEventListener('click', (e) => {
        if (isOpen && !dropdown.contains(e.target)) {
            closePanel();
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && isOpen) {
            closePanel();
        }
    });

    // Initial load and start polling
    loadMessages();
    startPolling();

    // Pause polling when page is hidden
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            stopPolling();
        } else {
            loadMessages();
            startPolling();
        }
    });
};

document.addEventListener('DOMContentLoaded', () => {
    setupThemeToggle();
    observeSystemTheme();
    setupMobileMenus();
    setupCarousels();
    setupTabGroups();
    setupChatInterface();
    setupMessagesDropdown();
});
