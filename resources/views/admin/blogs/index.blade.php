@extends('layouts.admin')

@section('header', 'Manage Blogs')

@section('content')
<div class="glass overflow-hidden rounded-2xl md:rounded-[2rem] shadow-xl border border-white/20">
    <div class="p-6 md:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-100 dark:border-slate-700 bg-white/50 dark:bg-slate-800/50">
        <div>
            <h3 class="text-2xl font-black text-gray-800 dark:text-white">All Stories</h3>
            <p class="text-sm text-gray-500 font-medium">Manage and monitor your blog content</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto">
            <div class="relative flex-1 sm:w-64">
                <input type="text" id="adminSearchInput" placeholder="Search blogs..." class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-2 focus:ring-zinc-900 outline-none text-sm text-gray-700 dark:text-white shadow-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            </div>
            <a href="{{ route('admin.blogs.create') }}" class="btn-premium px-6 py-3 text-sm whitespace-nowrap">
                <i class="fa-solid fa-plus me-2"></i> Create New
            </a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50/50 dark:bg-slate-900/50 text-gray-400 uppercase text-xs font-bold tracking-wider">
                    <th class="px-4 md:px-8 py-4 md:py-5">Title & Info</th>
                    <th class="px-4 md:px-8 py-4 md:py-5 text-center">Status</th>
                    <th class="px-4 md:px-8 py-4 md:py-5">Date Published</th>
                    <th class="px-4 md:px-8 py-4 md:py-5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50 transition-opacity duration-200" id="blogTableBody">
                @include('admin.blogs.partials.table-rows')
            </tbody>
        </table>
    </div>
    <div class="p-4 md:p-8 bg-gray-50/50 dark:bg-slate-900/50" id="paginationContainer">
        {{ $blogs->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    let adminSearchTimeout;

    // Trigger debounced search inside the admin panel
    $('#adminSearchInput').on('input', function() {
        clearTimeout(adminSearchTimeout);
        adminSearchTimeout = setTimeout(function() {
            fetchAdminBlogs();
        }, 400);
    });

    // Intercept pagination clicks
    $(document).on('click', '#paginationContainer a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        if (url) {
            let urlParams = new URLSearchParams(url.split('?')[1]);
            let page = urlParams.get('page');
            fetchAdminBlogs(page);
        }
    });

    // Perform AJAX request to fetch blogs in the admin panel
    function fetchAdminBlogs(page = 1) {
        let search = $('#adminSearchInput').val();
        
        $('#blogTableBody').addClass('opacity-50 pointer-events-none');
        
        $.ajax({
            url: "{{ route('admin.blogs') }}",
            type: 'GET',
            data: {
                search: search,
                page: page
            },
            success: function(response) {
                $('#blogTableBody').removeClass('opacity-50 pointer-events-none');
                $('#blogTableBody').html(response.html);
                $('#paginationContainer').html(response.pagination);
            },
            error: function() {
                $('#blogTableBody').removeClass('opacity-50 pointer-events-none');
                console.error('Failed to load admin blog posts.');
            }
        });
    }

    function deleteBlog(id, url) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This story will be deleted permanently!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#18181b',
            cancelButtonColor: '#71717a',
            confirmButtonText: 'Yes, delete it!',
            borderRadius: '24px',
            background: '#fff',
            customClass: {
                title: 'font-black text-slate-800',
                content: 'font-medium text-slate-600',
                confirmButton: 'rounded-xl px-6 py-3 font-bold',
                cancelButton: 'rounded-xl px-6 py-3 font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $(`#blog-row-${id}`).fadeOut(400, function() {
                            $(this).remove();
                        });
                        Swal.fire({
                            title: 'Deleted!',
                            text: response.success,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false,
                            borderRadius: '24px'
                        });
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong.',
                            icon: 'error',
                            borderRadius: '24px'
                        });
                    }
                });
            }
        })
    }
</script>
@endpush
