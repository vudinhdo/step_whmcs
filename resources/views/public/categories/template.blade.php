<x-public-layout>
    @php
        $accent = $page['theme']['accent'] ?? 'indigo';
        $emoji  = $page['theme']['emoji'] ?? '✨';
        $badge  = $page['theme']['badge'] ?? 'Dịch vụ';

        $heroTitle = $page['hero']['title'] ?? $group->name;
        $heroSub   = $page['hero']['subtitle'] ?? ($group->description ?: 'Giải pháp phù hợp cho doanh nghiệp.');
        $productsStyle = $page['layout']['products_style'] ?? 'cards';
        $showFilters   = $page['layout']['show_filters'] ?? true;
        $showFaq       = $page['layout']['show_faq'] ?? true;

        // map accent → class
        $accentBg = [
            'blue' => 'bg-blue-600 hover:bg-blue-500',
            'indigo' => 'bg-indigo-600 hover:bg-indigo-500',
            'purple' => 'bg-purple-600 hover:bg-purple-500',
            'emerald'=> 'bg-emerald-600 hover:bg-emerald-500',
            'orange' => 'bg-orange-600 hover:bg-orange-500',
            'slate'  => 'bg-slate-700 hover:bg-slate-600',
        ][$accent] ?? 'bg-indigo-600 hover:bg-indigo-500';

        $accentText = [
            'blue' => 'text-blue-600',
            'indigo' => 'text-indigo-600',
            'purple' => 'text-purple-600',
            'emerald'=> 'text-emerald-600',
            'orange' => 'text-orange-600',
            'slate'  => 'text-slate-700',
        ][$accent] ?? 'text-indigo-600';
    @endphp

    {{-- HERO --}}
    <section class="py-14 bg-gradient-to-br from-white to-gray-50 border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl p-3">
                <div class="inline-flex items-center rounded-full text-xs font-semibold px-4 py-2 bg-gray-100 text-gray-700">
                    <span class="mr-2">{{ $emoji }}</span> {{ $badge }}
                </div>

                <h1 class="mt-4 text-3xl md:text-4xl font-bold text-gray-900">
                    {{ $heroTitle }}
                </h1>

                <p class="mt-3 text-lg text-gray-600">
                    {{ $heroSub }}
                </p>

                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('products.index') }}"
                       class="inline-flex justify-center px-5 py-3 text-white rounded-lg text-sm font-semibold transition {{ $accentBg }}">
                        Xem sản phẩm
                    </a>
                    <a href="{{ route('contact') }}"
                       class="inline-flex justify-center px-5 py-3 border border-gray-300 rounded-lg text-sm font-semibold hover:border-gray-400 transition">
                        Liên hệ tư vấn
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- FEATURES --}}
    @if(!empty($page['features']['items']))
        <section class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">
                            {{ $page['features']['title'] ?? 'Lợi ích nổi bật' }}
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">Tối ưu cho nhu cầu doanh nghiệp</p>
                    </div>
                </div>

                <div class="mt-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($page['features']['items'] as $f)
                        <div class="bg-white rounded-2xl border shadow-sm p-6">
                            <div class="font-semibold text-gray-900">{{ $f['title'] ?? '' }}</div>
                            <div class="text-sm text-gray-600 mt-2">{{ $f['desc'] ?? '' }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- PRODUCTS --}}
    <section class="pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Sản phẩm trong danh mục</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $products->total() }} sản phẩm
                    </p>

                </div>
            </div>
            {{-- CLOUD PRESETS (chỉ hiện cho cloud) --}}
            @if(($group->slug === 'cloud') && !empty($page['presets']) && $products->count())
                <div class="mt-8 bg-white border rounded-2xl p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Chọn nhanh cấu hình Cloud</h3>
                            <p class="text-sm text-gray-500 mt-1">
                                Chọn preset để xem gợi ý cấu hình. Bạn có thể tuỳ chỉnh chi tiết ở trang sản phẩm.
                            </p>
                        </div>
                        <span class="text-xs bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full font-semibold">
                Preset
            </span>
                    </div>

                    @php
                        // dùng sản phẩm cloud đầu tiên làm target (bạn có thể đổi logic chọn)
                        $targetProduct = $products->first();
                    @endphp

                    <div class="mt-6 grid md:grid-cols-3 gap-4" id="cloud-presets">
                        @foreach($page['presets'] as $preset)
                            @php
                                $cfg = $preset['config'] ?? [];
                            @endphp

                            <button type="button"
                                    class="text-left border rounded-xl p-5 hover:shadow-md transition bg-white preset-btn"
                                    data-preset="{{ $preset['key'] }}"
                                    data-cpu="{{ $cfg['cpu'] ?? '' }}"
                                    data-ram="{{ $cfg['ram'] ?? '' }}"
                                    data-storage="{{ $cfg['storage'] ?? '' }}"
                            >
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="text-lg font-semibold text-gray-900">{{ $preset['name'] }}</div>
                                        <div class="text-sm text-gray-500 mt-1">{{ $preset['desc'] ?? '' }}</div>
                                    </div>

                                    @if(!empty($preset['badge']))
                                        <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-full">
                                {{ $preset['badge'] }}
                            </span>
                                    @endif
                                </div>

                                <div class="mt-4 grid grid-cols-3 gap-2 text-xs text-gray-600">
                                    <div class="bg-gray-50 border rounded-lg p-2">
                                        <div class="font-semibold text-gray-900">CPU</div>
                                        <div class="mt-1">{{ $cfg['cpu'] ?? '-' }} vCPU</div>
                                    </div>
                                    <div class="bg-gray-50 border rounded-lg p-2">
                                        <div class="font-semibold text-gray-900">RAM</div>
                                        <div class="mt-1">{{ $cfg['ram'] ?? '-' }} GB</div>
                                    </div>
                                    <div class="bg-gray-50 border rounded-lg p-2">
                                        <div class="font-semibold text-gray-900">SSD</div>
                                        <div class="mt-1">{{ $cfg['storage'] ?? '-' }} GB</div>
                                    </div>
                                </div>

                                <div class="mt-4 flex items-center text-indigo-600 font-semibold gap-2">
                                    <span>Chọn preset</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="w-5 h-5">
                                        <path d="M5 12h14"></path>
                                        <path d="m12 5 7 7-7 7"></path>
                                    </svg>
                                </div>
                            </button>
                        @endforeach
                    </div>

                    <div class="mt-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-t pt-4">
                        <div class="text-sm text-gray-600">
                            <div>Preset đang chọn: <span id="preset_name" class="font-semibold">-</span></div>
                            <div class="mt-1">Bạn sẽ được chuyển đến trang cấu hình chi tiết.</div>
                        </div>

                        <a id="preset_cta"
                           href="{{ $targetProduct ? route('products.show', $targetProduct->slug) : '#' }}"
                           class="inline-flex justify-center px-5 py-3 text-white rounded-lg text-sm font-semibold transition {{ $accentBg }}">
                            Xem cấu hình / chi tiết
                        </a>
                    </div>

                    <script>
                        (function(){
                            const buttons = document.querySelectorAll('#cloud-presets .preset-btn');
                            const presetNameEl = document.getElementById('preset_name');
                            const cta = document.getElementById('preset_cta');

                            const baseUrl = "{{ $targetProduct ? route('products.show', $targetProduct->slug) : '' }}";

                            function setActive(btn){
                                buttons.forEach(b => b.classList.remove('ring-2','ring-indigo-500','border-indigo-300'));
                                btn.classList.add('ring-2','ring-indigo-500','border-indigo-300');

                                const preset = btn.dataset.preset || '';
                                const cpu = btn.dataset.cpu || '';
                                const ram = btn.dataset.ram || '';
                                const storage = btn.dataset.storage || '';

                                presetNameEl.innerText = preset || '-';

                                // build query to prefill later
                                const params = new URLSearchParams();
                                if (preset) params.set('preset', preset);
                                if (cpu) params.set('cpu', cpu);
                                if (ram) params.set('ram', ram);
                                if (storage) params.set('storage', storage);

                                cta.href = baseUrl ? `${baseUrl}?${params.toString()}` : '#';
                            }

                            if (buttons.length) setActive(buttons[0]);
                            buttons.forEach(btn => btn.addEventListener('click', () => setActive(btn)));
                        })();
                    </script>
                </div>
            @endif

            {{-- Filters (tuỳ bạn nâng cấp sau) --}}
            @if($showFilters)
                <form method="GET" class="mt-6 bg-white border rounded-xl p-4">
                    <div class="grid md:grid-cols-5 gap-3">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Tìm kiếm</label>
                            <input name="q" value="{{ request('q') }}"
                                   placeholder="Nhập tên sản phẩm..."
                                   class="w-full border-gray-300 rounded-lg shadow-sm text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Chu kỳ</label>
                            <select name="cycle" class="w-full border-gray-300 rounded-lg shadow-sm text-sm">
                                <option value="">Tất cả</option>
                                @foreach($cycleOptions as $c)
                                    <option value="{{ $c }}" @selected(request('cycle')===$c)>
                                        {{ ucfirst($c) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Giá từ</label>
                            <input name="price_min" value="{{ request('price_min') }}"
                                   placeholder="0"
                                   class="w-full border-gray-300 rounded-lg shadow-sm text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Giá đến</label>
                            <input name="price_max" value="{{ request('price_max') }}"
                                   placeholder="99999999"
                                   class="w-full border-gray-300 rounded-lg shadow-sm text-sm">
                        </div>
                    </div>

                    <div class="mt-3 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                        <div class="flex items-center gap-3">
                            <label class="text-xs font-semibold text-gray-600">Sắp xếp</label>
                            <select name="sort" class="border-gray-300 rounded-lg shadow-sm text-sm">
                                <option value="relevance"  @selected(request('sort','relevance')==='relevance')>Phù hợp</option>
                                <option value="newest"     @selected(request('sort')==='newest')>Mới nhất</option>
                                <option value="price_asc"  @selected(request('sort')==='price_asc')>Giá tăng dần</option>
                                <option value="price_desc" @selected(request('sort')==='price_desc')>Giá giảm dần</option>
                                <option value="name_asc"   @selected(request('sort')==='name_asc')>Tên A-Z</option>
                                <option value="name_desc"  @selected(request('sort')==='name_desc')>Tên Z-A</option>
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button class="px-4 py-2 bg-gray-900 text-white rounded-lg text-sm font-semibold">
                                Lọc
                            </button>
                            <a href="{{ route('categories.show', $group->slug) }}"
                               class="px-4 py-2 border rounded-lg text-sm font-semibold">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>


            @endif


            <div class="mt-6">
                @if($productsStyle === 'table')
                    {{-- TABLE STYLE --}}
                    <div class="bg-white border rounded-xl overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-4 py-3">Sản phẩm</th>
                                <th class="text-left px-4 py-3">Mô tả</th>
                                <th class="text-right px-4 py-3">Giá từ</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $p)
                                @php
                                    $minPrice = $p->pricing->min('price');
                                    $currency = $p->pricing->first()->currency ?? setting('default_currency','VND');
                                @endphp
                                <tr class="border-t">
                                    <td class="px-4 py-3 font-semibold">
                                        <a class="hover:{{ $accentText }}" href="{{ route('products.show', $p->slug) }}">
                                            {{ $p->name }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">
                                        {{ \Illuminate\Support\Str::limit($p->description, 90) }}
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold">
                                        @if(!is_null($minPrice))
                                            {{ number_format($minPrice,0,',','.') }} {{ $currency }}
                                        @else
                                            Liên hệ
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('products.show', $p->slug) }}"
                                           class="inline-flex px-4 py-2 border rounded-lg text-sm font-semibold hover:border-gray-400 transition">
                                            Xem
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                @elseif($productsStyle === 'pricing')
                    {{-- PRICING STYLE (đẹp cho Cloud) --}}
                    <div class="grid md:grid-cols-3 gap-6">
                        @foreach($products as $p)
                            @php
                                $minPrice = $p->pricing->min('price');
                                $currency = $p->pricing->first()->currency ?? setting('default_currency','VND');
                            @endphp
                            <div class="bg-white border rounded-2xl shadow-sm p-6 hover:shadow-md transition flex flex-col">
                                <div class="text-lg font-semibold text-gray-900">
                                    <a class="hover:{{ $accentText }}" href="{{ route('products.show', $p->slug) }}">{{ $p->name }}</a>
                                </div>
                                <div class="text-sm text-gray-600 mt-2">
                                    {{ \Illuminate\Support\Str::limit($p->description, 110) }}
                                </div>

                                <div class="mt-5">
                                    <div class="text-sm text-gray-500">Giá từ</div>
                                    <div class="text-2xl font-bold {{ $accentText }}">
                                        @if(!is_null($minPrice))
                                            {{ number_format($minPrice,0,',','.') }} {{ $currency }}
                                        @else
                                            Liên hệ
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-auto pt-6">
                                    <a href="{{ route('products.show', $p->slug) }}"
                                       class="w-full inline-flex justify-center px-4 py-3 text-white rounded-lg text-sm font-semibold transition {{ $accentBg }}">
                                        Xem cấu hình / chi tiết
                                    </a>
                                </div>
                            </div>


                        @endforeach
                    </div>

                @else
                    {{-- CARDS STYLE (default) --}}
                    <div class="grid md:grid-cols-3 gap-6">
                        @foreach($products as $p)
                            @php
                                $minPrice = $p->pricing->min('price');
                                $currency = $p->pricing->first()->currency ?? setting('default_currency','VND');
                            @endphp
                            <div class="bg-white border rounded-2xl shadow-sm p-6 hover:shadow-md transition flex flex-col">
                                <a href="{{ route('products.show', $p->slug) }}"
                                   class="text-lg font-semibold text-gray-900 hover:{{ $accentText }}">
                                    {{ $p->name }}
                                </a>
                                <p class="text-sm text-gray-600 mt-2">
                                    {{ \Illuminate\Support\Str::limit($p->description, 120) }}
                                </p>

                                <div class="mt-4">
                                    @if(!is_null($minPrice))
                                        <div class="text-sm text-gray-500">Giá từ</div>
                                        <div class="text-xl font-bold {{ $accentText }}">
                                            {{ number_format($minPrice,0,',','.') }} {{ $currency }}
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-auto pt-5 flex gap-2">
                                    <a href="{{ route('products.show', $p->slug) }}"
                                       class="inline-flex px-4 py-2 border rounded-lg text-sm font-semibold hover:border-gray-400 transition">
                                        Xem chi tiết
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                @endif
            </div>
            <div class="mt-8">
                {{ $products->links() }}
            </div>
            {{-- FAQ --}}
            @if($showFaq && !empty($page['faq']))
                <div class="mt-14">
                    <h3 class="text-xl font-bold text-gray-900">Câu hỏi thường gặp</h3>
                    <div class="mt-4 space-y-3">
                        @foreach($page['faq'] as $f)
                            <div class="bg-white border rounded-xl p-5">
                                <div class="font-semibold text-gray-900">{{ $f['q'] }}</div>
                                <div class="text-sm text-gray-600 mt-2">{{ $f['a'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </section>
</x-public-layout>
