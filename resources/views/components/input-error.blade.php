@props(['for' => null])

@error($for)
    <p {{ $attributes->merge(['class' => 'text-sm text-red-600']) }}>{{ $message }}</p>
@enderror
