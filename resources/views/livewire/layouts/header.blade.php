@props(['navigation' => null])
<?php
/**
 * @var \Illuminate\Support\Collection<\Filament\Navigation\NavigationItem> $navigation
 * */
$chunks = $navigation->chunk(3);
?>

<div class="sticky top-0 z-20 overflow-x-clip">
    <nav x-data="navigationMenu"
        class="bg-gray-50 px-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 md:px-6 lg:px-8">
        <div class="relative">
            <ul
                class="flex items-center justify-center flex-1 p-1 space-x-1 list-none rounded-md text-neutral-700 border-neutral-200/80">
                <li>
                    <button
                        @mouseover="navigationMenuOpen=true; navigationMenuReposition($el); navigationMenu='getting-started'"
                        @mouseleave="navigationMenuLeave()"
                        class="inline-flex items-center justify-center h-10 px-4 py-2 text-sm font-medium transition-colors rounded-md text-secondary-foreground hover:text-secondary-foreground/50 focus:outline-none bg-gray-50 dark:bg-gray-900 w-max">
                        <span>Getting started</span>
                        <svg x-bind:class="{ '-rotate-180' : navigationMenuOpen==true && navigationMenu == 'getting-started' }"
                            class="relative top-[1px] ml-1 h-3 w-3 ease-out duration-300"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                </li>
                <li>
                    <button
                        @mouseover="navigationMenuOpen=true; navigationMenuReposition($el); navigationMenu='learn-more'"
                        @mouseleave="navigationMenuLeave()"
                        class="inline-flex items-center justify-center h-10 px-4 py-2 text-sm font-medium transition-colors rounded-md text-secondary-foreground hover:text-secondary-foreground/50 focus:outline-none bg-gray-50 dark:bg-gray-900 w-max">
                        <span>Learn More</span>
                        <svg x-bind:class="{ '-rotate-180' : navigationMenuOpen==true && navigationMenu == 'learn-more' }"
                            class="relative top-[1px] ml-1 h-3 w-3 ease-out duration-300"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                </li>
                <li>
                    <a href="#_"
                        class="inline-flex items-center justify-center h-10 px-4 py-2 text-sm font-medium transition-colors rounded-md text-secondary-foreground hover:text-secondary-foreground/50 focus:outline-none bg-gray-50 dark:bg-gray-900 w-max">
                        Documentation
                    </a>
                </li>
            </ul>
        </div>
        <div x-ref="navigationDropdown" x-show="navigationMenuOpen"
            x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
            @mouseover="navigationMenuClearCloseTimeout()" @mouseleave="navigationMenuLeave()"
            class="absolute top-3 duration-200 ease-out -translate-x-1/2 translate-y-11" x-cloak>

            <div
                class="flex justify-center w-auto h-auto overflow-hidden rounded-md shadow-sm ring-1 ring-gray-950/5 bg-background dark:bg-gray-900 dark:ring-white/10">
                <div x-show="navigationMenu == 'getting-started'"
                    class="flex items-stretch justify-center w-full max-w-2xl p-6 gap-x-3 h-[312px]">
                    <a href="{{ route('landing')}}" target="_blank" rel="noopener noreferrer"
                        @click="navigationMenuClose()"
                        class="flex-shrink-0 w-48 rounded pt-28 pb-7 bg-gradient-to-br from-primary-600 to-purple-700">
                        <div class="p-4 text-white flex flex-col justify-end h-full">
                            <span class="block font-bold">Porto</span>
                            <span class="block text-sm opacity-60">A full-stack portfolio that build using Laravel
                                Filament.</span>
                        </div>
                    </a>
                    <div class="w-72">
                        <a href="https://filamentphp.com/" target="_blank" rel="noopener noreferrer"
                            @click="navigationMenuClose()"
                            class="block px-3.5 py-3 text-sm rounded hover:bg-secondary-foreground/10">
                            <span class="block mb-1 font-medium text-secondary-foreground">Panel Builder</span>
                            <span class="block font-light leading-5 opacity-50">Full-stack admin panel that build on top
                                of Filament.</span>
                        </a>
                        <a href="https://laravel.com/" target="_blank" rel="noopener noreferrer"
                            @click="navigationMenuClose()"
                            class="block px-3.5 py-3 text-sm rounded hover:bg-secondary-foreground/10">
                            <span class="block mb-1 font-medium text-secondary-foreground">Framework</span>
                            <span class="block leading-5 opacity-50">A PHP framework for building modern web
                                applications.</span>
                        </a>
                        <a href="https://github.com/ProblematicToucan/fullstack-porto" target="_blank"
                            rel="noopener noreferrer" @click="navigationMenuClose()"
                            class="block px-3.5 py-3 text-sm rounded hover:bg-secondary-foreground/10">
                            <span class="block mb-1 font-medium text-secondary-foreground">Contributing</span>
                            <span class="block leading-5 opacity-50">Feel free to contribute your expertise. All these
                                elements are open-source.</span>
                        </a>
                    </div>
                </div>
                <div x-show="navigationMenu == 'learn-more'"
                    class="flex items-stretch justify-center w-full p-6 h-[312px]">
                    @foreach ($chunks as $chunk)
                        <div class="w-72">
                            @foreach ($chunk as $item)
                                <a href={{ $item->getUrl() }} @click="navigationMenuClose()"
                                    class="block px-3.5 py-3 text-sm rounded hover:bg-secondary-foreground/10">
                                    <span
                                        class="block mb-1 font-medium text-secondary-foreground">{{ $item->getLabel() }}</span>
                                    <span class="block font-light leading-5 opacity-50">Create and manage filament
                                        {{ strtolower($item->getLabel()) }} resource.</span>
                                </a>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </nav>
</div>
<script src="{{ asset('js/alpine/navigation.js') }}"></script>
