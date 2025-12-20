<x-public-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow-sm rounded-lg p-6">
                <p class="text-sm text-gray-500 mb-2">
                    Nhóm: {{ $product->group->name ?? '-' }}
                </p>
                <div class="prose max-w-none">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>

            @if($product->type === 'cloud' && $product->pricing->isNotEmpty())
                <div class="bg-white shadow-sm rounded-xl p-6">
                    <div class="grid lg:grid-cols-3 gap-6" id="cloud-configurator">

                        {{-- LEFT: CONFIG --}}
                        <div class="lg:col-span-2 space-y-6">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="text-lg font-semibold">Cấu hình Cloud</h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Tuỳ chỉnh tài nguyên theo nhu cầu sử dụng thực tế.
                                    </p>
                                </div>
                                <a href="{{ route('cart.index') }}" class="text-sm text-indigo-600 whitespace-nowrap">
                                    Xem giỏ hàng →
                                </a>
                            </div>

                            {{-- Billing cycle --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Chu kỳ thanh toán</label>
                                <select id="billing_cycle" class="w-full border-gray-300 rounded-lg shadow-sm text-sm">
                                    @foreach ($product->pricing as $price)
                                        <option value="{{ $price->billing_cycle }}">
                                            {{ ucfirst($price->billing_cycle) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Number rules --}}
                            @if(!empty($product->pricingRules) && $product->pricingRules->count())
                                <div class="grid md:grid-cols-3 gap-4">
                                    @foreach($product->pricingRules as $rule)
                                        <div class="border rounded-lg p-4">
                                            <div class="flex items-center justify-between">
                                                <label class="text-sm font-medium text-gray-700 uppercase">
                                                    {{ $rule->key }}
                                                </label>
                                                <span class="text-xs text-gray-500">
                                        / {{ number_format($rule->price_per_unit, 0, ',', '.') }}
                                    </span>
                                            </div>

                                            <input
                                                type="number"
                                                class="mt-2 w-full border-gray-300 rounded-md shadow-sm text-sm config-input"
                                                data-key="{{ $rule->key }}"
                                                value="{{ $rule->min ?? 1 }}"
                                                min="{{ $rule->min ?? 1 }}"
                                                @if($rule->max) max="{{ $rule->max }}" @endif
                                                step="{{ $rule->step ?? 1 }}"
                                            >

                                            <p class="mt-1 text-xs text-gray-500">
                                                Min {{ $rule->min ?? 1 }}
                                                @if($rule->max)
                                                    · Max {{ $rule->max }}
                                                @endif
                                                @if($rule->step)
                                                    · Step {{ $rule->step }}
                                                @endif
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Select/radio options --}}
                            @if(!empty($product->options) && $product->options->count())
                                <div class="grid md:grid-cols-2 gap-4">
                                    @foreach($product->options as $opt)
                                        @if(in_array($opt->type, ['select','radio'], true))
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 mb-1">{{ $opt->label }}</label>
                                                <select
                                                    class="w-full border-gray-300 rounded-lg shadow-sm text-sm config-select"
                                                    data-key="{{ $opt->key }}">
                                                    @foreach($opt->values as $v)
                                                        <option value="{{ $v->value }}">
                                                            {{ $v->label }}
                                                            @if((float)$v->price_delta !== 0.0)
                                                                ({{ $v->price_delta > 0 ? '+' : '' }}{{ number_format($v->price_delta, 0, ',', '.') }}
                                                                )
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- RIGHT: SUMMARY (sticky) --}}
                        <div class="bg-gray-50 rounded-xl p-5 lg:sticky lg:top-24 h-fit">
                            <h4 class="text-sm font-semibold text-gray-700 mb-4">Tóm tắt chi phí</h4>

                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Base</span>
                                    <span id="price_base" class="font-medium">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Rule</span>
                                    <span id="price_rules" class="font-semibold">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tuỳ chọn</span>
                                    <span id="price_opt" class="font-medium">-</span>
                                </div>
                            </div>

                            <div class="border-t my-4"></div>

                            <div class="text-center">
                                <div class="text-xs text-gray-500">Tổng dự tính</div>
                                <div class="text-3xl font-bold text-indigo-600">
                                    <span id="price_total">-</span>
                                    <span class="text-sm font-medium"
                                          id="price_currency">{{ setting('default_currency','VND') }}</span>
                                </div>
                            </div>



                            {{-- Add to cart --}}
                            <form method="POST" action="{{ route('cart.add') }}" class="mt-5" id="addCartForm">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="billing_cycle" id="cart_billing_cycle" value="">
                                <input type="hidden" name="config_json" id="cart_config_json" value="">
                                <input type="hidden" name="config_price" id="cart_config_price" value="0">
                                <input type="hidden" name="qty" value="1">

                                <button
                                    class="w-full px-4 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-sm font-semibold transition">
                                    Thêm vào giỏ hàng
                                </button>
                            </form>

                            <p class="mt-3 text-xs text-gray-500 text-center">
                                * Giá hiển thị là dự tính, chưa bao gồm VAT (nếu có).
                            </p>
                        </div>

                    </div>



                    <script>

                        async function estimatePrice() {
                            const cycleEl = document.getElementById('billing_cycle');
                            const cycle = cycleEl ? cycleEl.value : 'monthly';

                            document.getElementById('cart_billing_cycle').value = cycle;

                            const config = {};

                            document.querySelectorAll('#cloud-configurator .config-input').forEach(el => {
                                config[el.dataset.key] = parseInt(el.value || '0', 10);
                            });

                            document.querySelectorAll('#cloud-configurator .config-select').forEach(el => {
                                config[el.dataset.key] = el.value;
                            });

                            const res = await fetch("{{ route('products.estimate', $product->slug) }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Accept": "application/json",
                                },
                                body: JSON.stringify({billing_cycle: cycle, config})
                            });

                            const json = await res.json();

                            if (!res.ok) {
                                console.log(json);
                                return;
                            }

                            const fmt = new Intl.NumberFormat('vi-VN');

                            document.getElementById('price_currency').innerText = json.currency;
                            document.getElementById('price_base').innerText = fmt.format(json.base);
                            document.getElementById('price_rules').innerText = fmt.format(json.rules_total);
                            document.getElementById('price_opt').innerText = fmt.format(json.options_total);
                            document.getElementById('price_total').innerText = fmt.format(json.total);

                            document.getElementById('cart_config_json').value = JSON.stringify(config);
                            document.getElementById('cart_config_price').value = json.rules_total + json.options_total;
                        }

                        document.getElementById('billing_cycle')?.addEventListener('change', estimatePrice);
                        document.querySelectorAll('#cloud-configurator .config-input').forEach(el => el.addEventListener('input', estimatePrice));
                        document.querySelectorAll('#cloud-configurator .config-select').forEach(el => el.addEventListener('change', estimatePrice));

                        function applyPresetFromUrl() {
                            const url = new URL(window.location.href);

                            // mapping query -> key trong pricingRules
                            // mặc định key: cpu, ram, storage
                            const map = {
                                cpu: 'cpu',
                                ram: 'ram',
                                storage: 'storage',
                            };

                            let changed = false;

                            Object.keys(map).forEach(param => {
                                const v = url.searchParams.get(param);
                                if (!v) return;

                                const key = map[param];
                                const input = document.querySelector(`#cloud-configurator .config-input[data-key="${key}"]`);
                                if (!input) return;

                                input.value = parseInt(v, 10);
                                changed = true;
                            });

                            // nếu có preset (starter/business/enterprise) bạn có thể show label nhỏ (optional)
                            const preset = url.searchParams.get('preset');
                            if (preset) {
                                console.log('Preset:', preset);
                            }

                            return changed;
                        }
                        const changed = applyPresetFromUrl();
                        estimatePrice();
                    </script>
                </div>
            @endif


            {{-- KHU VỰC “ĐĂNG KÝ DỊCH VỤ” GIỮ NGUYÊN (portal order) --}}
{{--            <div class="bg-white shadow-sm rounded-lg p-6">--}}
{{--                <h3 class="text-lg font-semibold mb-4">Đăng ký dịch vụ</h3>--}}

{{--                @guest--}}
{{--                    <p class="mb-4 text-sm text-gray-700">--}}
{{--                        Bạn cần <a href="{{ route('login') }}" class="text-indigo-600">đăng nhập</a>--}}
{{--                        hoặc <a href="{{ route('register') }}" class="text-indigo-600">đăng ký</a> để đặt mua theo--}}
{{--                        Portal.--}}
{{--                        <br>--}}
{{--                        Nếu bạn chỉ muốn tham khảo và gửi yêu cầu, bạn có thể dùng phần “Thêm vào giỏ hàng” (ở trên).--}}
{{--                    </p>--}}
{{--                @else--}}
{{--                    @if ($product->pricing->isEmpty())--}}
{{--                        <p>Hiện chưa có bảng giá cho sản phẩm này.</p>--}}
{{--                    @else--}}
{{--                        <form method="POST" action="{{ route('client.orders.store', $product) }}" class="space-y-4">--}}
{{--                            @csrf--}}

{{--                            <div>--}}
{{--                                <label class="block text-sm font-medium text-gray-700 mb-1">--}}
{{--                                    Chu kỳ thanh toán--}}
{{--                                </label>--}}
{{--                                <select name="billing_cycle"--}}
{{--                                        class="border-gray-300 rounded-md shadow-sm w-full">--}}
{{--                                    @foreach ($product->pricing as $price)--}}
{{--                                        <option value="{{ $price->billing_cycle }}">--}}
{{--                                            {{ ucfirst($price->billing_cycle) }} ---}}
{{--                                            {{ number_format($price->price, 0, ',', '.') }} {{ $price->currency }}--}}
{{--                                            @if($price->setup_fee > 0)--}}
{{--                                                (+ setup {{ number_format($price->setup_fee, 0, ',', '.') }})--}}
{{--                                            @endif--}}
{{--                                        </option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                                @error('billing_cycle')--}}
{{--                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>--}}
{{--                                @enderror--}}
{{--                            </div>--}}

{{--                            <div>--}}
{{--                                <label class="block text-sm font-medium text-gray-700 mb-1">--}}
{{--                                    Ghi chú thêm (tuỳ chọn)--}}
{{--                                </label>--}}
{{--                                <textarea name="notes" rows="3"--}}
{{--                                          class="border-gray-300 rounded-md shadow-sm w-full">{{ old('notes') }}</textarea>--}}
{{--                            </div>--}}

{{--                            <div class="flex justify-end">--}}
{{--                                <button type="submit"--}}
{{--                                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-500">--}}
{{--                                    Đặt mua--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                    @endif--}}
{{--                @endguest--}}
{{--            </div>--}}

        </div>
    </div>
</x-public-layout>
