<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Universal Image Converter - Fast & Lossless Image Conversion</title>
    
    <!-- Meta SEO -->
    <meta name="description" content="Convert your images (JPG, JPEG, PNG, WEBP, BMP) online instantly. Fast, lossless, and secure image format conversion.">



    <!-- CSS / JS compilation -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-zinc-900 font-sans antialiased min-h-screen flex flex-col bg-grid-light">

    <!-- Sticky Navigation Header -->
    <header class="sticky top-0 z-50 w-full border-b border-zinc-200/50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 h-14 flex items-center justify-between">
            
            <!-- Brand Logo -->
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-2 focus:outline-none focus:ring-1 focus:ring-zinc-400 rounded-md p-1" aria-label="Image Converter homepage">
                    <svg class="w-6 h-6 text-zinc-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    <span class="text-sm font-bold text-zinc-900 tracking-tight">Image <span class="text-zinc-500">Converter</span></span>
                </a>
            </div>

            <!-- Central Nav Links (Desktop) -->
            <nav class="hidden md:flex items-center gap-8" aria-label="Main Navigation">
                <a href="#hero" class="text-xs font-semibold text-zinc-500 hover:text-zinc-900 transition-colors uppercase tracking-wider">Home</a>
                <a href="#why-choose-us" class="text-xs font-semibold text-zinc-500 hover:text-zinc-900 transition-colors uppercase tracking-wider">Quality</a>
                <a href="{{ route('blog') }}" class="text-xs font-semibold text-zinc-500 hover:text-zinc-900 transition-colors uppercase tracking-wider">Blog</a>
            </nav>

            <!-- Navigation Controls (Right) -->
            <div class="flex items-center gap-3">
                @auth
                    <span class="text-xs font-bold text-zinc-700">Xin chào, {{ auth()->user()->name }}</span>
                    @if(auth()->user()->role)
                        <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-850 uppercase tracking-wider">Trang quản trị</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-xs font-semibold text-zinc-500 hover:text-zinc-900 uppercase tracking-wider">Đăng xuất</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-xs font-semibold text-zinc-500 hover:text-zinc-900 transition-colors py-1.5 px-2.5 uppercase tracking-wider">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="inline-flex text-xs font-semibold text-white bg-zinc-900 hover:bg-zinc-800 active:bg-black transition-colors py-2 px-4 rounded-md tracking-wide">Đăng ký</a>
                @endauth
            </div>

        </div>
    </header>

    <main class="flex-grow">
        
        <!-- Hero Section: Centered Upload Zone -->
        <section id="hero" class="py-16 sm:py-20 max-w-4xl mx-auto px-6 sm:px-8">
            <div class="w-full">
                    
                    <div id="upload-dropzone" class="dropzone-container relative flex flex-col items-center justify-center p-6 h-[520px] sm:h-[560px] cursor-pointer">
                        
                        <input type="file" id="file-input" class="hidden" accept=".jpg,.jpeg,.png,.webp,.bmp">
                        
                        <!-- State 1: Idle (Extremely Minimal, empty space) -->
                        <div id="state-idle" class="flex flex-col items-center justify-center text-center transition-all duration-300">
                            <!-- Large Upload Icon (80-100px) -->
                            <div class="upload-icon-anim w-24 h-24 text-zinc-300 mb-8 flex items-center justify-center">
                                <svg class="w-20 h-20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                            </div>
                            
                            <p class="text-sm font-semibold text-zinc-800 mb-1.5">Drop your image here</p>
                            <p class="text-[11px] text-zinc-400 font-medium">JPG • JPEG • WEBP • BMP • PNG — Max 20 MB</p>
                        </div>

                        <!-- State 2: Uploading File Card (Centered inside large container) -->
                        <div id="state-uploading" class="hidden w-full max-w-md flex flex-col items-center justify-center transition-all duration-300">
                            <div class="w-full bg-zinc-50 border border-zinc-200 rounded-xl p-5 mb-6 text-left">
                                <div class="flex items-start gap-4">
                                    <!-- Image symbol -->
                                    <div id="card-symbol" class="w-10 h-10 bg-emerald-50 text-emerald-600 border border-emerald-100 font-extrabold text-[10px] rounded flex items-center justify-center shrink-0 select-none">
                                        IMG
                                    </div>
                                    
                                    <div class="flex-grow min-w-0">
                                        <div class="flex items-center justify-between mb-1">
                                            <span id="card-file-name" class="text-xs font-bold text-zinc-950 truncate pr-4">image.jpg</span>
                                            <span id="card-progress" class="text-xs font-bold text-zinc-950">0%</span>
                                        </div>
                                        
                                        <!-- Minimal progress bar -->
                                        <div class="w-full bg-zinc-200 rounded-full h-1 mb-2.5 overflow-hidden">
                                            <div id="card-progress-bar" class="bg-accent-600 h-full rounded-full transition-all duration-100" style="width: 0%"></div>
                                        </div>
                                        
                                        <div class="flex items-center justify-between text-[10px] text-zinc-400 font-semibold">
                                            <div class="flex items-center gap-2">
                                                <span id="card-file-size">0 KB</span>
                                                <span class="w-0.5 h-0.5 bg-zinc-200 rounded-full"></span>
                                                <span id="card-speed">0 MB/s</span>
                                                <span class="w-0.5 h-0.5 bg-zinc-200 rounded-full"></span>
                                                <span id="card-remaining">Calculating...</span>
                                            </div>
                                            <span id="card-status-text" class="text-zinc-500 font-bold">Uploading...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Target Format Selector -->
                            <div class="w-full bg-zinc-50 border border-zinc-200 rounded-xl p-5 mb-6 text-left flex items-center justify-between">
                                <label for="target-format-select" class="text-xs font-semibold text-zinc-700">Convert to:</label>
                                <select id="target-format-select" class="bg-white border border-zinc-200 text-xs font-bold text-zinc-800 rounded-md px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-zinc-400 cursor-pointer">
                                    <option value="png" selected>PNG (Lossless)</option>
                                    <option value="jpg">JPG (Compressed)</option>
                                    <option value="webp">WEBP (Modern Web)</option>
                                    <option value="bmp">BMP (Uncompressed, large)</option>
                                </select>
                            </div>

                            <div class="flex items-center justify-center gap-2.5">
                                <button id="btn-replace-file" class="text-xs font-semibold text-zinc-600 hover:text-zinc-900 py-1.5 px-3 border border-zinc-200 rounded-md hover:bg-zinc-50 transition-colors">
                                    Replace Image
                                </button>
                                <button id="btn-cancel-upload" class="text-xs font-semibold text-zinc-600 hover:text-zinc-900 py-1.5 px-3 border border-zinc-200 rounded-md hover:bg-zinc-50 transition-colors">
                                    Remove Image
                                </button>
                                <button id="btn-retry-upload" class="hidden text-xs font-semibold text-white bg-red-600 hover:bg-red-700 py-1.5 px-3 rounded-md transition-colors">
                                    Retry
                                </button>
                            </div>
                        </div>

                        <!-- State 3: Converting Pipeline (Centered inside large container) -->
                        <div id="state-converting" class="hidden w-full max-w-xl flex flex-col justify-center transition-all duration-300">
                            <!-- Header progress -->
                            <div class="flex items-center justify-between text-xs font-bold text-zinc-900 mb-2">
                                <span id="proc-time-remaining">Processing image...</span>
                                <span id="proc-progress-percent">0%</span>
                            </div>
                            <div class="w-full bg-zinc-100 rounded-full h-1 mb-6 overflow-hidden">
                                <div id="proc-progress-bar" class="bg-accent-600 h-full rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-stretch">
                                <!-- Steps tracker -->
                                <div class="md:col-span-5 flex flex-col justify-between py-1 border-r border-zinc-100">
                                    <div id="step-uploading" class="console-step-row text-neutral-600">
                                        <div id="step-uploading-dot" class="console-dot"></div>
                                        <span class="text-[11px] font-medium">Upload streams</span>
                                    </div>
                                    <div id="step-preparing" class="console-step-row text-neutral-600">
                                        <div id="step-preparing-dot" class="console-dot"></div>
                                        <span class="text-[11px] font-medium">Initialize GD</span>
                                    </div>
                                    <div id="step-reading" class="console-step-row text-neutral-600">
                                        <div id="step-reading-dot" class="console-dot"></div>
                                        <span class="text-[11px] font-medium">Decode pixels</span>
                                    </div>
                                    <div id="step-converting" class="console-step-row text-neutral-600">
                                        <div id="step-converting-dot" class="console-dot"></div>
                                        <span class="text-[11px] font-medium">Render transparency</span>
                                    </div>
                                    <div id="step-optimizing" class="console-step-row text-neutral-600">
                                        <div id="step-optimizing-dot" class="console-dot"></div>
                                        <span class="text-[11px] font-medium">Optimize channels</span>
                                    </div>
                                    <div id="step-generating" class="console-step-row text-neutral-600">
                                        <div id="step-generating-dot" class="console-dot"></div>
                                        <span class="text-[11px] font-medium">Build output stream</span>
                                    </div>
                                    <div id="step-finalizing" class="console-step-row text-neutral-600">
                                        <div id="step-finalizing-dot" class="console-dot"></div>
                                        <span class="text-[11px] font-medium">Wipe cache</span>
                                    </div>
                                </div>

                                <!-- Monospace terminal -->
                                <div class="md:col-span-7 flex flex-col">
                                    <div id="console-terminal" class="console-logs-window min-h-[190px] max-h-[190px] overflow-y-auto scrollbar-none flex-grow"></div>
                                </div>
                            </div>
                        </div>

                        <!-- State 4: Success Screen (Centered inside large container) -->
                        <div id="state-success" class="hidden w-full max-w-md flex flex-col items-center justify-center transition-all duration-300">
                            <div class="w-10 h-10 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-full flex items-center justify-center mb-4 select-none">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </div>
                            
                            <h3 class="text-sm font-bold text-zinc-950 mb-1">Conversion Successful</h3>
                            <p class="text-[11px] text-zinc-400 mb-6">Your image has been processed and converted to the target format.</p>

                            <div class="w-full border border-zinc-200 rounded-lg p-4 bg-zinc-50/50 mb-6 text-left">
                                <div class="flex items-start gap-3">
                                    <div id="success-symbol" class="w-9 h-9 bg-emerald-50 text-emerald-600 border border-emerald-100 font-extrabold text-[10px] rounded flex items-center justify-center shrink-0 select-none">
                                        PNG
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <span id="success-file-name" class="text-xs font-semibold text-zinc-900 truncate block">image.png</span>
                                        <div class="flex items-center gap-2 mt-1.5 text-[10px] text-zinc-400 font-medium">
                                            <span id="success-file-size">0 KB</span>
                                            <span class="w-0.5 h-0.5 bg-zinc-200 rounded-full"></span>
                                            <span id="success-time">0.0s</span>
                                            <span class="w-0.5 h-0.5 bg-zinc-200 rounded-full"></span>
                                            <span id="success-compression" class="text-emerald-600 font-semibold">0% compressed</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-2 w-full mb-6">
                                <a id="btn-download" href="#" class="inline-flex items-center justify-center gap-2 text-xs font-bold text-white bg-zinc-900 hover:bg-zinc-800 active:bg-black transition-colors py-2 px-4 rounded-md shadow-sm flex-1">
                                    Download Image
                                </a>
                                <button id="btn-preview" class="inline-flex items-center justify-center gap-2 text-xs font-bold text-zinc-700 bg-white border border-zinc-200 hover:border-zinc-300 active:bg-zinc-50 py-2 px-4 rounded-md transition-colors flex-1">
                                    Preview
                                </button>
                            </div>

                            <div class="flex items-center justify-center gap-3 text-[10px] font-bold text-zinc-400 uppercase tracking-wider">
                                <button id="btn-copy-link" class="hover:text-zinc-950 transition-colors">Copy Link</button>
                                <span class="w-0.5 h-0.5 bg-zinc-200 rounded-full"></span>
                                <button id="btn-share" class="hover:text-zinc-950 transition-colors">Share</button>
                                <span class="w-0.5 h-0.5 bg-zinc-200 rounded-full"></span>
                                <button id="btn-convert-another" class="text-zinc-600 hover:text-zinc-950 transition-colors">Convert Another</button>
                            </div>
                        </div>

                    </div>

                    <!-- Convert CTA Button -->
                    <div id="convert-cta-container" class="hidden mt-4 text-center w-full max-w-md mx-auto">
                        <button id="btn-convert" type="button" class="w-full inline-flex items-center justify-center gap-2 text-xs font-bold text-white bg-zinc-900 hover:bg-zinc-800 active:bg-black disabled:bg-zinc-200 disabled:text-zinc-400 disabled:cursor-not-allowed transition-all py-2.5 px-6 rounded-md tracking-wider uppercase">
                            Convert to PNG
                        </button>
                    </div>

                </div>
        </section>

        <!-- Dynamic Split-Screen Layout Comparison Viewport Slider -->
        <section id="why-choose-us" class="py-20 bg-zinc-50 border-t border-b border-zinc-200/50">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
                
                <div class="text-center max-w-2xl mx-auto mb-12">
                    <h2 class="text-2xl font-extrabold text-zinc-900 tracking-tight mb-3">Alpha Preservation Engine</h2>
                    <p class="text-sm text-zinc-500 leading-relaxed font-normal">Slide the divider to compare the original image format (left) with the lossless PNG output preserving transparency (right).</p>
                </div>

                <!-- Slider element widget -->
                <div class="max-w-3xl mx-auto relative select-none">
                    <div class="split-slider-container min-h-[360px] max-h-[360px]">
                        
                        <!-- Left Pane: Image Details Mock -->
                        <div class="split-pane p-8 flex flex-col justify-between">
                            <div class="flex items-center justify-between border-b border-zinc-100 pb-4 mb-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-4 h-4 bg-emerald-600 rounded-sm flex items-center justify-center text-[8px] font-bold text-white">J</div>
                                    <span class="text-xs font-bold text-zinc-500">original_photo.jpg</span>
                                </div>
                                <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Original JPG</span>
                            </div>
                            
                            <!-- Document content -->
                            <div class="flex-grow space-y-4 max-w-sm">
                                <h3 class="text-xl font-bold text-zinc-900 leading-tight">Original JPG Image</h3>
                                <p class="text-xs text-zinc-500 leading-relaxed">Compressed image containing lossy artifacts around sharp edges and text elements. Transparency is replaced with a solid background.</p>
                            </div>
                            
                            <span class="text-[10px] text-zinc-400 mt-6 block">Fidelity: Lossy Compression</span>
                        </div>

                        <!-- Right Pane: PNG Document Mock (Overlaid) -->
                        <div id="split-pane-right" class="split-pane-right">
                            <div class="w-[766px] h-full p-8 flex flex-col justify-between absolute right-0 top-0">
                                <div class="flex items-center justify-between border-b border-zinc-150 pb-4 mb-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-4 h-4 bg-emerald-600 rounded-sm flex items-center justify-center text-[8px] font-bold text-white">P</div>
                                        <span class="text-xs font-bold text-zinc-700">converted_photo.png</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Lossless PNG</span>
                                </div>
                                
                                <div class="flex-grow space-y-4 max-w-sm">
                                    <h3 class="text-xl font-bold text-zinc-950 leading-tight tracking-tight">Lossless PNG Image</h3>
                                    <p class="text-xs text-zinc-600 leading-relaxed font-normal">Reconstructed pixels with perfect alpha channels, preserving original vectors and sharp transparent borders. Ideal for design mockups.</p>
                                </div>
                                
                                <span class="text-[10px] text-zinc-500 font-semibold mt-6 block">Fidelity: Lossless alpha channels</span>
                            </div>
                        </div>

                        <!-- Divider Slider Line -->
                        <div id="split-divider-line" class="split-divider-line">
                            <div class="split-handle">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l3 3-3 3m8-6l3 3-3 3" />
                                </svg>
                            </div>
                        </div>

                        <!-- Range Input overlay -->
                        <input type="range" min="0" max="100" value="50" id="split-range-input" class="split-input-range" aria-label="Compare image formats">
                    </div>
                </div>

            </div>
        </section>


    </main>

    <!-- Footer Section -->
    <footer class="bg-zinc-950 text-zinc-400 text-xs border-t border-zinc-800 py-12">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
            
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8 mb-12">
                <div>
                    <h4 class="text-[10px] font-bold text-zinc-300 uppercase tracking-widest mb-4">Products</h4>
                    <ul class="space-y-2.5">
                        <li><a href="#" class="hover:text-white transition-colors">Image Converter</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">PNG Compressor</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-[10px] font-bold text-zinc-300 uppercase tracking-widest mb-4">Company</h4>
                    <ul class="space-y-2.5">
                        <li><a href="#" class="hover:text-white transition-colors">About</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Careers</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Press</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-[10px] font-bold text-zinc-300 uppercase tracking-widest mb-4">Resources</h4>
                    <ul class="space-y-2.5">
                        <li><a href="#" class="hover:text-white transition-colors">Developer Portal</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">API Docs</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-[10px] font-bold text-zinc-300 uppercase tracking-widest mb-4">Support</h4>
                    <ul class="space-y-2.5">
                        <li><a href="#" class="hover:text-white transition-colors">Help Articles</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Forum</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">System Status</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-[10px] font-bold text-zinc-300 uppercase tracking-widest mb-4">Legal</h4>
                    <ul class="space-y-2.5">
                        <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-zinc-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold text-zinc-500">© 2026 Universal Image Converter. Edge instances maintained securely.</span>
                </div>
                
                <!-- Language drop selection -->
                <div class="relative">
                    <select id="language-select" class="bg-zinc-900 border border-zinc-800 text-[10px] font-bold text-zinc-300 rounded-md px-2 py-1.5 focus:outline-none focus:ring-1 focus:ring-zinc-600 cursor-pointer">
                        <option value="en" selected>English (US)</option>
                        <option value="vi">Tiếng Việt</option>
                    </select>
                </div>
            </div>

        </div>
    </footer>

    <!-- Toast alerts -->
    <div id="toast-notification" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 bg-zinc-950 text-white text-xs font-semibold py-2.5 px-5 rounded-lg shadow-lg border border-zinc-800 flex items-center gap-2 transition-all duration-300 translate-y-12 opacity-0 pointer-events-none" role="alert">
        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span id="toast-message">Action successful</span>
    </div>

</body>
</html>
