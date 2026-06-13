@extends('layouts.frontend')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 fade-in">
    <!-- Header Card -->
    <div class="glass overflow-hidden rounded-[2.5rem] shadow-2xl border border-white/20 mb-10">
        <div class="h-48 bg-gradient-to-r from-zinc-800 to-zinc-950 relative">
            <div class="absolute -bottom-16 left-1/2 -translate-x-1/2 md:left-12 md:translate-x-0">
                <div class="relative group">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" id="avatarPreview" class="w-40 h-40 rounded-full border-8 border-white object-cover shadow-2xl transition-transform duration-500 group-hover:scale-105">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=18181b&color=fff&size=200" id="avatarPreview" class="w-40 h-40 rounded-full border-8 border-white shadow-2xl transition-transform duration-500 group-hover:scale-105">
                    @endif
                    <label for="avatarInput" class="absolute bottom-2 right-2 w-10 h-10 bg-white text-zinc-900 rounded-full flex items-center justify-center shadow-lg cursor-pointer hover:bg-zinc-50 transition-colors">
                        <i class="fa-solid fa-camera"></i>
                    </label>
                </div>
            </div>
        </div>
        <div class="pt-20 pb-10 px-12 text-center md:text-left">
            <div class="md:ml-48">
                <h1 class="text-3xl font-black text-slate-800">{{ auth()->user()->name }}</h1>
                <p class="text-slate-500 font-medium">Update your account settings and security preferences</p>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-10">
        <!-- Settings Sidebar Navigation (Left) -->
        <div class="w-full lg:w-1/4 space-y-4">
            <div class="bg-white border border-zinc-200/80 rounded-3xl p-5 shadow-sm space-y-1 lg:sticky lg:top-28">
                <a href="#personal-info-section" class="settings-nav-link flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-bold text-zinc-900 bg-zinc-100 transition-all">
                    <i class="fa-solid fa-user text-zinc-800"></i>
                    <span>Personal Info</span>
                </a>
                <a href="#security-section" class="settings-nav-link flex items-center space-x-3 px-4 py-3 rounded-2xl text-sm font-bold text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900 transition-all">
                    <i class="fa-solid fa-shield-halved text-zinc-400"></i>
                    <span>Security</span>
                </a>
            </div>
            <div class="text-center py-4 bg-zinc-50 border border-zinc-200/50 rounded-3xl hidden lg:block">
                <p class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest">Member since {{ auth()->user()->created_at->format('M Y') }}</p>
            </div>
        </div>

        <!-- Form Panels (Right) -->
        <div class="w-full lg:w-3/4 space-y-8">
            <!-- Personal Info Card -->
            <div id="personal-info-section" class="bg-white border border-zinc-200/80 p-8 md:p-10 rounded-[2rem] shadow-sm scroll-mt-28">
                <h3 class="text-xl font-black text-slate-800 mb-8 flex items-center">
                    <span class="w-10 h-10 bg-zinc-100 text-zinc-900 rounded-xl flex items-center justify-center mr-4 text-sm">
                        <i class="fa-solid fa-user"></i>
                    </span>
                    Personal Information
                </h3>

                <form id="profileForm" class="space-y-6">
                    <input type="file" id="avatarInput" name="avatar" class="hidden" accept="image/*">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">Full Name</label>
                            <input type="text" name="name" class="w-full px-6 py-4 rounded-2xl border border-zinc-200 bg-zinc-50/50 focus:bg-white focus:ring-4 focus:ring-zinc-950/10 focus:border-zinc-950 outline-none transition-all font-semibold text-zinc-800" value="{{ auth()->user()->name }}">
                            <p class="error-msg text-red-500 text-xs mt-1 ml-2 hidden"></p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">Email Address</label>
                            <input type="email" name="email" class="w-full px-6 py-4 rounded-2xl border border-zinc-200 bg-zinc-50/50 focus:bg-white focus:ring-4 focus:ring-zinc-950/10 focus:border-zinc-950 outline-none transition-all font-semibold text-zinc-800" value="{{ auth()->user()->email }}">
                            <p class="error-msg text-red-500 text-xs mt-1 ml-2 hidden"></p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 ml-1">Phone Number</label>
                        <input type="text" name="phone" class="w-full px-6 py-4 rounded-2xl border border-zinc-200 bg-zinc-50/50 focus:bg-white focus:ring-4 focus:ring-zinc-950/10 focus:border-zinc-950 outline-none transition-all font-semibold text-zinc-800" value="{{ auth()->user()->phone }}" placeholder="+1 (555) 000-0000">
                        <p class="error-msg text-red-500 text-xs mt-1 ml-2 hidden"></p>
                    </div>

                    <button type="submit" class="btn-premium w-full md:w-auto px-12 py-4">
                        <i class="fa-solid fa-circle-check mr-2"></i> Save Changes
                    </button>
                </form>
            </div>

            <!-- Security Card -->
            <div id="security-section" class="bg-white border border-zinc-200/80 p-8 md:p-10 rounded-[2rem] shadow-sm scroll-mt-28">
                <h3 class="text-xl font-black text-slate-800 mb-8 flex items-center">
                    <span class="w-10 h-10 bg-zinc-100 text-zinc-900 rounded-xl flex items-center justify-center mr-4 text-sm">
                        <i class="fa-solid fa-shield-halved"></i>
                    </span>
                    Security & Password
                </h3>

                <form id="passwordForm" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 ml-1">Current Password</label>
                        <input type="password" name="old_password" class="w-full px-6 py-4 rounded-2xl border border-zinc-200 bg-zinc-50/50 focus:bg-white focus:ring-4 focus:ring-zinc-950/10 focus:border-zinc-950 outline-none transition-all font-semibold text-zinc-800" placeholder="••••••••">
                        <p class="error-msg text-red-500 text-xs mt-1 ml-2 hidden"></p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 ml-1">New Password</label>
                        <input type="password" name="new_password" class="w-full px-6 py-4 rounded-2xl border border-zinc-200 bg-zinc-50/50 focus:bg-white focus:ring-4 focus:ring-zinc-950/10 focus:border-zinc-950 outline-none transition-all font-semibold text-zinc-800" placeholder="Min. 8 characters">
                        <p class="error-msg text-red-500 text-xs mt-1 ml-2 hidden"></p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 ml-1">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="w-full px-6 py-4 rounded-2xl border border-zinc-200 bg-zinc-50/50 focus:bg-white focus:ring-4 focus:ring-zinc-950/10 focus:border-zinc-950 outline-none transition-all font-semibold text-zinc-800" placeholder="Repeat new password">
                        <p class="error-msg text-red-500 text-xs mt-1 ml-2 hidden"></p>
                    </div>

                    <button type="submit" class="w-full bg-zinc-900 text-white font-black py-4 rounded-2xl shadow-xl hover:bg-zinc-800 transition-all active:scale-95">
                        <i class="fa-solid fa-lock mr-2"></i> Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Smooth scroll on sidebar links click
    $('.settings-nav-link').on('click', function(e) {
        e.preventDefault();
        const target = $(this).attr('href');
        $('html, body').animate({
            scrollTop: $(target).offset().top - 100
        }, 500);
        window.history.pushState(null, null, target);
    });

    // Scrollspy helper for settings navigation sidebar
    const sections = $('.scroll-mt-28');
    const navLinks = $('.settings-nav-link');

    $(window).on('scroll', function() {
        const scrollPos = $(document).scrollTop() + 150;
        sections.each(function() {
            const top = $(this).offset().top;
            const bottom = top + $(this).outerHeight();
            const id = $(this).attr('id');

            if (scrollPos >= top && scrollPos < bottom) {
                navLinks.removeClass('bg-zinc-100 text-zinc-900').addClass('text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900');
                navLinks.find('i').removeClass('text-zinc-800').addClass('text-zinc-400');
                
                const activeLink = $(`.settings-nav-link[href="#${id}"]`);
                activeLink.addClass('bg-zinc-100 text-zinc-900').removeClass('text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900');
                activeLink.find('i').addClass('text-zinc-800').removeClass('text-zinc-400');
            }
        });
    });

    // Avatar Preview
    $('#avatarInput').change(function() {
        const file = this.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(event) {
                $('#avatarPreview').attr('src', event.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    // Profile Form Submit
    $('#profileForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const btn = form.find('button[type="submit"]');
        
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i> Saving...');
        form.find('.error-msg').addClass('hidden').text('');
        form.find('input').removeClass('border-red-500');

        $.ajax({
            url: "{{ route('profile.update') }}",
            method: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function(res) {
                Swal.fire({
                    icon: 'success',
                    title: 'Profile Updated!',
                    text: res.success,
                    timer: 2000,
                    showConfirmButton: false,
                    borderRadius: '24px'
                });
                if (res.avatar_url) {
                    $('.navbar img').attr('src', res.avatar_url);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        let input = form.find(`[name="${field}"]`);
                        input.addClass('border-red-500');
                        input.siblings('.error-msg').removeClass('hidden').text(messages[0]);
                    });
                }
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fa-solid fa-circle-check mr-2"></i> Save Changes');
            }
        });
    });

    // Password Form Submit
    $('#passwordForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const btn = form.find('button[type="submit"]');

        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i> Updating...');
        form.find('.error-msg').addClass('hidden').text('');
        form.find('input').removeClass('border-red-500');

        $.ajax({
            url: "{{ route('password.reset') }}",
            method: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function(res) {
                Swal.fire({
                    icon: 'success',
                    title: 'Password Updated!',
                    text: res.success,
                    timer: 2000,
                    showConfirmButton: false,
                    borderRadius: '24px'
                });
                form[0].reset();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    if (xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(field, messages) {
                            let input = form.find(`[name="${field}"]`);
                            input.addClass('border-red-500');
                            input.siblings('.error-msg').removeClass('hidden').text(messages[0]);
                        });
                    }
                    if (xhr.responseJSON.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON.error,
                            borderRadius: '24px'
                        });
                    }
                }
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fa-solid fa-lock mr-2"></i> Update Password');
            }
        });
    });
});
</script>
@endpush
