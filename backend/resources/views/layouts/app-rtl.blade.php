{{-- RTL layout that extends the main app layout --}}
{{-- This layout adds RTL-specific classes for Kurdish language support --}}

@extends('layouts.app')

@section('head')
    @parent
    <style>
        /* RTL-specific overrides */
        [dir="rtl"] .space-x-reverse > :not([hidden]) ~ :not([hidden]) {
            --tw-space-x-reverse: 1;
        }

        [dir="rtl"] .prose {
            text-align: right;
        }

        [dir="rtl"] .ml-auto {
            margin-left: 0;
            margin-right: auto;
        }

        [dir="rtl"] .mr-auto {
            margin-right: 0;
            margin-left: auto;
        }

        [dir="rtl"] .mr-2 {
            margin-right: 0;
            margin-left: 0.5rem;
        }

        [dir="rtl"] .ml-2 {
            margin-left: 0;
            margin-right: 0.5rem;
        }

        [dir="rtl"] .mr-3 {
            margin-right: 0;
            margin-left: 0.75rem;
        }

        [dir="rtl"] .ml-3 {
            margin-left: 0;
            margin-right: 0.75rem;
        }

        [dir="rtl"] .space-x-4 > :not([hidden]) ~ :not([hidden]) {
            --tw-space-x-reverse: 1;
        }

        [dir="rtl"] .space-x-2 > :not([hidden]) ~ :not([hidden]) {
            --tw-space-x-reverse: 1;
        }

        [dir="rtl"] .space-x-3 > :not([hidden]) ~ :not([hidden]) {
            --tw-space-x-reverse: 1;
        }

        [dir="rtl"] .space-x-6 > :not([hidden]) ~ :not([hidden]) {
            --tw-space-x-reverse: 1;
        }

        [dir="rtl"] .ticker-animation {
            animation-direction: reverse;
        }

        [dir="rtl"] .transform {
            --tw-translate-x: calc(var(--tw-translate-x, 0) * -1);
        }
    </style>
@stop

{{-- The content section is inherited from the child views --}}
