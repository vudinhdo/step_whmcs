<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductConfiguratorController extends Controller
{
    public function estimate(Request $request, Product $product): JsonResponse
    {
        $data = $request->validate([
            'billing_cycle' => ['required','string', 'in:monthly,annually'],
            'config'        => ['nullable','array'],
        ]);

        $cycle  = $data['billing_cycle'];
        $config = $data['config'] ?? [];

        // Load theo cycle để đỡ dư dữ liệu
        $product->load([
            'pricing' => fn ($q) => $q->where('billing_cycle', $cycle),
            'pricingRules' => fn ($q) => $q->where('billing_cycle', $cycle),
            'options.values',
        ]);

        // base price theo billing_cycle
        $pricing = $product->pricing->first(); // vì đã where cycle
        if (! $pricing) {
            return response()->json(['message' => 'Invalid billing cycle'], 422);
        }

        $price    = (float) $pricing->price;
        $setupFee = (float) ($pricing->setup_fee ?? 0);

        $base = $price + $setupFee;

        // 1) Number rules (cpu/ram/storage...)
        $rulesTotal = 0.0;
        $rulesBreakdown = [];

        foreach ($product->pricingRules as $rule) {
            $key = $rule->key;

            // nếu client không gửi key => lấy min hoặc 0 để tính ổn định
            $value = $config[$key] ?? ($rule->min ?? 0);
            $value = (int) $value;

            if (!is_null($rule->min) && $value < $rule->min) $value = (int) $rule->min;
            if (!is_null($rule->max) && $value > $rule->max) $value = (int) $rule->max;

            // step: làm tròn xuống theo step (an toàn hơn)
            $step = max(1, (int) $rule->step);
            $value = (int) (floor($value / $step) * $step);

            $line = $value * (float) $rule->price_per_unit;
            $rulesTotal += $line;

            $rulesBreakdown[] = [
                'key' => $key,
                'qty' => $value,
                'unit_price' => (float) $rule->price_per_unit,
                'line_total' => $line,
            ];
        }

        // 2) Select/radio options: price_delta
        $optionsTotal = 0.0;
        $optionsBreakdown = [];

        foreach ($product->options as $opt) {
            if (!in_array($opt->type, ['select','radio'], true)) continue;

            $key = $opt->key;
            if (!array_key_exists($key, $config)) continue;

            $selected = (string) $config[$key];
            $val = $opt->values->firstWhere('value', $selected);

            if ($val) {
                $delta = (float) ($val->price_delta ?? 0);
                $optionsTotal += $delta;

                $optionsBreakdown[] = [
                    'key' => $key,
                    'value' => $val->value,
                    'label' => $val->label,
                    'price_delta' => $delta,
                ];
            }
        }

        $total = $base + $rulesTotal + $optionsTotal;

        return response()->json([
            'currency'      => $pricing->currency ?? setting('default_currency','VND'),

            'price'         => $price,
            'setup_fee'     => $setupFee,

            'base'          => $base,
            'rules_total'   => $rulesTotal,
            'options_total' => $optionsTotal,
            'total'         => $total,

            'breakdown' => [
                'rules'   => $rulesBreakdown,
                'options' => $optionsBreakdown,
            ],
        ]);
    }
}
