<div>
    <!-- Filters and Add Buttons -->
    @include('thotam-hr::livewire.hr.sub.filters')

    <!-- Incluce các modal -->
    @include('thotam-hr::livewire.hr.modal.add_edit')

    <!-- Scripts -->
    @push('livewires')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                window.thotam_livewire = @this;
                Livewire.emit("dynamic_update_method");
            });
        </script>
    @endpush
</div>
