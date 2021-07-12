<div>
    <!-- Filters and Add Buttons -->
    @include('thotam-hr::livewire.hr.sub.filters')

    <!-- Incluce các modal -->
    @include('thotam-hr::livewire.hr.modal.add_edit')
    @include('thotam-hr::livewire.hr.modal.set_team_modal')
    @include('thotam-hr::livewire.hr.modal.set_permission_modal')

    <!-- Scripts -->
    @push('livewires')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                window.thotam_livewire = @this;
                Livewire.emit("dynamic_update_method");
            });
        </script>
    @endpush

    <!-- Styles -->
    @push('styles')
        @include('thotam-hr::livewire.hr.sub.style')
    @endpush
</div>
