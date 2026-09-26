@extends('layouts.app')
@section('title', 'Admin POS - Drag & Drop Kursi')

@section('content')
<div class="max-w-7xl mx-auto" x-data="adminDragDrop()">
    <div class="flex flex-col lg:flex-row gap-6">

        <!-- Sisi Denah Stadion -->
        <div class="flex-1 bg-slate-900 p-6 rounded-2xl border border-slate-800 shadow-xl">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-800">
                <div>
                    <h2 class="text-xl font-bold text-white">Denah Stadion (Admin Kasir)</h2>
                    <p class="text-xs text-slate-400 mt-1">Seret (<span class="italic text-yellow-400">drag</span>) kursi warna <b class="text-red-400">merah</b> ke kotak checkout di sebelah kanan.</p>
                </div>
                <!-- Legend -->
                <div class="flex items-center gap-4 text-xs font-semibold">
                    <div class="flex items-center gap-1.5">
                        <span class="w-4 h-4 rounded bg-red-600 border border-red-400 inline-block shadow"></span>
                        <span class="text-red-300">Merah (Kosong / Siap Tarik)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-4 h-4 rounded bg-emerald-600 border border-emerald-400 inline-block shadow"></span>
                        <span class="text-emerald-300">Hijau (Terisi)</span>
                    </div>
                </div>
            </div>

            <!-- Lapangan Stadion -->
            <div class="stadium-field rounded-xl p-4 mb-8 text-center text-white/80 font-bold uppercase tracking-widest text-xs shadow-inner">
                ⚽ LAPANGAN PERTANDINGAN / PITCH ⚽
            </div>

            <!-- Kategori / Tribun -->
            @foreach($stands as $stand)
            <div class="mb-8">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-bold text-emerald-400 text-sm tracking-wide">{{ $stand->name }}</h3>
                    <span class="text-xs bg-slate-800 text-slate-300 px-2.5 py-1 rounded border border-slate-700">Rp {{ number_format($stand->price, 0, ',', '.') }}</span>
                </div>

                <div class="grid grid-cols-10 gap-2.5 p-4 bg-slate-950/70 rounded-xl border border-slate-800">
                    @foreach($stand->seats as $seat)
                        @if($seat->status === 'available')
                            <!-- KURSI KOSONG (MERAH) - BISA DI-DRAG -->
                            <div 
                                draggable="true"
                                @dragstart="onDragStart($event, {{ $seat->toJson() }}, {{ $stand->price }})"
                                :class="isInCart({{ $seat->id }}) ? 'opacity-30 border-dashed border-yellow-400 ring-2 ring-yellow-400' : 'bg-red-600 hover:bg-red-500 border-red-400'"
                                class="cursor-grab active:cursor-grabbing text-white border rounded-lg p-2 text-center transition duration-150 transform hover:-translate-y-1 shadow select-none"
                            >
                                <span class="text-xs font-bold block">{{ $seat->seat_code }}</span>
                                <span class="text-[9px] uppercase tracking-wider block opacity-80">Kosong</span>
                            </div>
                        @else
                            <!-- KURSI TERISI (HIJAU) - TERKUNCI -->
                            <div class="bg-emerald-600 border border-emerald-400 text-white rounded-lg p-2 text-center opacity-85 cursor-not-allowed select-none shadow">
                                <span class="text-xs font-bold block">{{ $seat->seat_code }}</span>
                                <span class="text-[9px] uppercase tracking-wider block opacity-90">Terisi</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <!-- Sidebar Kasir (Drop Zone) -->
        <div class="w-full lg:w-96">
            <div class="bg-slate-900 p-6 rounded-2xl border border-slate-800 shadow-xl sticky top-24">
                <h3 class="text-base font-bold text-white mb-1">Kasir On-The-Spot</h3>
                <p class="text-xs text-slate-400 mb-4">Lepaskan (<span class="italic text-yellow-400">drop</span>) bangku yang diseret ke dalam area ini.</p>

                <!-- Drop Target -->
                <div 
                    @dragover.prevent="isOver = true"
                    @dragleave.prevent="isOver = false"
                    @drop.prevent="onDrop($event)"
                    :class="isOver ? 'border-yellow-400 bg-yellow-400/10 scale-102' : 'border-slate-700 bg-slate-950/60'"
                    class="border-2 border-dashed rounded-xl p-4 min-h-[160px] flex flex-col justify-center items-center text-center transition-all duration-150"
                >
                    <template x-if="cart.length === 0">
                        <div class="text-slate-500 text-xs">
                            <svg class="w-8 h-8 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                            Tarik bangku merah & lepaskan di sini
                        </div>
                    </template>

                    <div class="w-full space-y-2" x-show="cart.length > 0">
                        <template x-for="(item, index) in cart" :key="item.id">
                            <div class="flex justify-between items-center bg-slate-800 border border-slate-700 px-3 py-2 rounded-lg text-xs">
                                <div>
                                    <span class="font-bold text-white" x-text="item.seat_code"></span>
                                    <span class="text-slate-400 ml-1.5" x-text="'(Rp ' + Number(item.price).toLocaleString('id-ID') + ')'"></span>
                                </div>
                                <button type="button" @click="removeItem(index)" class="text-red-400 hover:text-red-300 font-bold px-1.5 py-0.5 rounded">✕</button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Form Transaksi Kasir -->
                <form action="{{ route('stadium.checkout') }}" method="POST" class="mt-5 space-y-4">
                    @csrf
                    <input type="hidden" name="booking_type" value="on_the_spot">

                    <template x-for="item in cart" :key="item.id">
                        <input type="hidden" name="seat_ids[]" :value="item.id">
                    </template>

                    <div>
                        <label class="text-xs font-semibold text-slate-300 block mb-1">Nama Pembeli</label>
                        <input type="text" name="customer_name" required placeholder="Contoh: Budi Santoso" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-300 block mb-1">Nomor Kontak / WhatsApp</label>
                        <input type="text" name="customer_phone" required placeholder="Contoh: 081234567890" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>

                    <div class="pt-3 border-t border-slate-800 flex justify-between items-center">
                        <span class="text-xs text-slate-400">Total Tagihan:</span>
                        <span class="text-lg font-black text-emerald-400" x-text="'Rp ' + Number(totalPrice).toLocaleString('id-ID')"></span>
                    </div>

                    <button 
                        type="submit" 
                        :disabled="cart.length === 0"
                        class="w-full py-2.5 rounded-lg font-bold text-sm bg-indigo-600 hover:bg-indigo-500 disabled:bg-slate-800 disabled:text-slate-600 text-white transition shadow-lg"
                    >
                        Checkout Tiket
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
function adminDragDrop() {
    return {
        cart: [],
        isOver: false,
        onDragStart(e, seat, price) {
            e.dataTransfer.setData('application/json', JSON.stringify({ ...seat, price }));
        },
        onDrop(e) {
            this.isOver = false;
            try {
                const data = JSON.parse(e.dataTransfer.getData('application/json'));
                if (!this.isInCart(data.id)) {
                    this.cart.push(data);
                }
            } catch (err) {
                console.error("Format payload tidak valid", err);
            }
        },
        removeItem(index) {
            this.cart.splice(index, 1);
        },
        isInCart(seatId) {
            return this.cart.some(item => item.id === seatId);
        },
        get totalPrice() {
            return this.cart.reduce((sum, item) => sum + Number(item.price), 0);
        }
    }
}
</script>
@endsection