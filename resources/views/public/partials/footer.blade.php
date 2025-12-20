<footer class="border-t bg-white mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-xs text-gray-500 flex flex-col md:flex-row gap-2 md:items-center md:justify-between">
        <div>
            {{ setting('footer_text', '© ' . date('Y') . ' ' . setting('company_name', config('app.name'))) }}
        </div>
        <div class="flex flex-wrap gap-3">
            @if(setting('support_email'))
                <span>Email: {{ setting('support_email') }}</span>
            @endif
            @if(setting('hotline'))
                <span>Hotline: {{ setting('hotline') }}</span>
            @endif
        </div>
    </div>
</footer>
