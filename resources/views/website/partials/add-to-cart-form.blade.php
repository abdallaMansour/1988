<form action="{{ route('website.cart.store') }}" method="POST" class="d-inline">
    @csrf
    <input type="hidden" name="type" value="{{ $type }}">
    <input type="hidden" name="id" value="{{ $id }}">
    @if ($type === 'product')
    <input type="hidden" name="quantity" value="{{ $quantity ?? 1 }}">
    @endif
    <button type="submit" class="btn btn-sm {{ $class ?? 'btn-outline-primary' }}">
        <i class="bx bx-cart me-1"></i> أضف للسلة
    </button>
</form>
