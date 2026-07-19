@extends('layouts.app')

@section('title', __('messages.notifications_title'))
@section('description', __('messages.notifications_description'))

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-serif font-bold text-foreground mb-2">{{ __('messages.notifications_title') }}</h1>
    <p class="text-muted-foreground text-sm mb-8">{{ __('messages.notifications_description') }}</p>

    <div id="notifications-list" class="space-y-2">
        <div class="text-center py-12">
            <div class="inline-block w-8 h-8 border-2 border-[#8B0000] border-t-transparent rounded-full animate-spin"></div>
            <p class="text-muted-foreground text-sm mt-3">{{ __('messages.loading') }}</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('/api/v1/notifications')
        .then(r => r.json())
        .then(data => {
            const container = document.getElementById('notifications-list');
            if (!data.data || data.data.length === 0) {
                container.innerHTML = '<div class="text-center py-12 text-muted-foreground text-sm">{{ __("messages.no_notifications") }}</div>';
                return;
            }
            container.innerHTML = data.data.map(n => {
                const wrapper = n.url ? 'a' : 'div';
                const href = n.url ? ` href="${n.url}"` : '';
                const extraAttrs = n.url ? '' : ' class="block cursor-default"';
                const dotColor = n.is_read ? 'bg-gray-600' : 'bg-[#CC0000]';
                return `<${wrapper}${href}${extraAttrs}>
                    <div class="bg-card border border-border p-4 ${n.is_read ? '' : 'border-l-2 border-l-[#8B0000]'}">
                        <div class="flex items-start gap-3">
                            <span class="shrink-0 mt-1 w-2 h-2 rounded-full ${dotColor}"></span>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-foreground font-semibold text-sm">${n.title}</h3>
                                ${n.body ? `<p class="text-muted-foreground text-xs mt-1">${n.body}</p>` : ''}
                                <p class="text-gray-500 text-[10px] mt-1">${new Date(n.created_at).toLocaleDateString()}</p>
                            </div>
                        </div>
                    </div>
                </${wrapper}>`;
            }).join('');
        })
        .catch(() => {
            document.getElementById('notifications-list').innerHTML = 
                '<div class="text-center py-12 text-muted-foreground text-sm">{{ __("messages.error_loading") }}</div>';
        });
});
</script>
@endpush
@endsection
