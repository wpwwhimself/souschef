<span
    @if (!$template && $ingredient->template?->minimum_amount > $ingredient->amount) class="error" @endif
    @if ($ingredient->template->unit === "JNO")
    {{ Popper::pop($ingredient->amount) }}
    @endif
    >
    {{ $output }}
</span>
