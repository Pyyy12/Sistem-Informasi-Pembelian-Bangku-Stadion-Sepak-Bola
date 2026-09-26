@extends('layouts.app')
@section('title', 'Pilih Tiket Stadion Online')

@section('content')
<div class="max-w-4xl mx-auto pb-24" x-data="customerCinema()">

    <!-- Header Panduan Bioskop -->
    <div class="text-center mb-8">
        <h1 class="text-2xl md:text-3xl font-black text-white tracking-wide">PILIHAN KURSI PERTANDINGAN</h1>
        <p class="text-slate-400 text-xs md:text-sm mt-1">Klik pada kursi warna merah untuk menentukan pilihan Anda</p>

        <div class="flex justify-center items-center gap-6 mt-5 text-xs font-semibold">
            <div class="flex items-center gap-2">
                <span class="w-4 h-4 rounded bg-red-600 border border-red-400 shadow"></span>
                <span class="text-slate-300">Merah (Kosong)</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-4 h-4 rounded bg-yellow-400 border border-yellow-200 shadow"></span>
                <span class="text-slate-300">Kuning (Pilihan Anda)</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-4 h-4 rounded bg-emerald-600 border border-emerald-400 shadow"></span>
                <span class="text-slate-300">Hijau (Terisi)</span>
            </div>
        </div>
    </div>

    <!-- Layar / Lapangan Utama -->
    <div class="stadium-field rounded-2xl p-5 text-center shadow-inner mb-8 max-w-xl mx-auto">
        <div class="text-white/90 font-black tracking-widest text-xs uppercase">SISI LAPANGAN / TRIBUN UTAMA</div>
        <div class="w-full h-0.5 bg-white/30 my-2"></div>
        <div class="w-12 h-12 border-2 border-white/40 rounded-full mx-auto"></div>
    </div>

    <!-- Layout Grid Kursi -->
    @foreach($stands as $stand)
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl mb-6 shadow-xl">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-sm md:text-base font-bold text-emerald-400">{{ $stand->name }}</h2>
            <span class="text-xs bg-slate-800 text-slate-300 px-3 py-1 rounded-full border border-slate-700">Rp {{ number_format($stand->price, 0, ',', '.') }} / tiket</span>
        </div>

        <div class="grid grid-cols-10 gap-2.5">
            @foreach($stand->seats as $seat)
                @if($seat->status === 'available')
                    <!-- KURSI KOSONG (KLIK UNTUK MEMILIH) -->
                    <button 
                        type="button"
                        @click="toggleSeat({{ $seat->toJson() }}, {{ $stand->price }})"
                        :class="isSelected({{ $seat->id }}) 
                            ? 'bg-yellow-400 text-slate-950 font-black ring-4 ring-yellow-400/40 border-yellow-300 scale-105 shadow-yellow-500/50' 
                            : 'bg-red-600 hover:bg-red-500 text-white border-red-400'"
                        class="p-2.5 rounded-lg border text-center transition duration-150 transform hover:scale-105 shadow select-none"
                    >
                        <span class="text-xs block font-bold">{{ $seat->seat_code }}</span>
                    </button>
                @else
                    <!-- KURSI TERISI (HIJAU) -->
                    <div class="bg-emerald-600 border border-emerald-400 text-white p-2.5 rounded-lg text-center cursor-not-allowed select-none shadow">
                        <span class="text-xs block font-bold">{{ $seat->seat_code }}</span>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    @endforeach

    <!-- Floating Checkout Bottom Bar -->
    <div class="fixed bottom-0 left-0 right-0 bg-slate-900/95 border-t border-slate-800 backdrop-blur-md px-6 py-4 z-40" x-show="selectedSeats.length > 0" x-transition>
        <div class="max-w-4xl mx-auto flex items-center justify-between gap-4">
            <div>
                <span class="text-xs text-slate-400 block">Kursi Terpilih (<span x-text="selectedSeats.length"></span>):</span>
                <div class="flex flex-wrap gap-1.5 mt-1">
                    <template x-for="item in selectedSeats" :key="item.id">
                        <span class="bg-yellow-400 text-slate-950 font-bold px-2 py-0.5 rounded text-xs" x-text="item.seat_code"></span>
                    </template>
                </div>
            </div>

            <div class="flex items-center gap-5">
                <div class="text-right">
                    <span class="text-xs text-slate-400 block">Total Harga</span>
                    <span class="text-lg md:text-xl font-black text-emerald-400" x-text="'Rp ' + Number(totalPrice).toLocaleString('id-ID')"></span>
                </div>
                <button @click="isModalOpen = true" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black px-5 py-2.5 rounded-xl transition shadow-lg text-sm">
                    Pesan Sekarang
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Form Checkout -->
    <div class="fixed inset-0 bg-black/80 flex items-center justify-center p-4 z-50" x-show="isModalOpen" style="display: none;">
        <div class="bg-slate-900 border border-slate-800 w-full max-w-md p-6 rounded-2xl shadow-2xl" @click.outside="isModalOpen = false">
            <h3 class="text-lg font-bold text-white mb-4">Konfirmasi & Identitas Penonton</h3>

            <form action="{{ route('stadium.checkout') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="booking_type" value="online">

                <template x-for="item in selectedSeats" :key="item.id">
                    <input type="hidden" name="seat_ids[]" :value="item.id">
                </template>

                <div>
                    <label class="text-xs font-semibold text-slate-300 block mb-1">Nama Lengkap</label>
                    <input type="text" name="customer_name" required placeholder="Nama Anda" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="text-xs font-semibold text-slate-300 block mb-1">Nomor WhatsApp / HP</label>
                    <input type="text" name="customer_phone" required placeholder="08xxxxxxxxxx" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-emerald-500">
                </div>

                <div class="pt-3 border-t border-slate-800 flex justify-end gap-3">
                    <button type="button" @click="isModalOpen = false" class="px-4 py-2 rounded-lg text-slate-400 text-xs hover:bg-slate-800">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs">Konfirmasi & Bayar</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function customerCinema() {
    return {
        selectedSeats: [],
        isModalOpen: false,
        toggleSeat(seat, price) {
            const index = this.selectedSeats.findIndex(item => item.id === seat.id);
            if (index > -1) {
                this.selectedSeats.splice(index, 1);
            } else {
                this.selectedSeats.push({ ...seat, price });
            }
        },
        isSelected(seatId) {
            return this.selectedSeats.some(item => item.id === seatId);
        },
        get totalPrice() {
            return this.selectedSeats.reduce((sum, item) => sum + Number(item.price), 0);
        }
    }
}
</script>
@endsection