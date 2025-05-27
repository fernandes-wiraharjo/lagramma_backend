<!-- JAVASCRIPT -->
<script src="{{ URL::asset('build/libs/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('build/js/plugins.js') }}"></script>
<script>
    function formatIndonesianDate(dateString) {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = date.toLocaleString('en-US', { month: 'short' }); // Apr
        const year = date.getFullYear();
        return `${day} ${month}, ${year}`;
    }
</script>

@yield('scripts')
