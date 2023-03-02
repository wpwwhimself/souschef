<span
    @if (!$template && $ingredient->template?->minimum_amount > $ingredient->amount) class="error" @endif
    title="{{ $ingredient->amount }}"
    >
    {{ $output }}
</span>
