@props(['icon', 'class' => 'w-5 h-5'])

<x-filament::icon-button :icon="$icon" {{ $attributes->merge(['class' => $class]) }} />
