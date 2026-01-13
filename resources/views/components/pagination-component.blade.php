<div class="d-flex justify-content-center mt-3">
    {{ $collection->onEachSide(1)->links('pagination::bootstrap-4') }}
</div>

<style>
    @media (max-width: 600px) {
        .page-link {
            padding: .5rem .5rem;
        }
    }
</style>
